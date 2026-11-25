<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Resep;
use App\Models\VarianProduk;
use App\Support\KapasitasMenu;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProdukController extends Controller
{
    private const UKURAN_GAMBAR_MAKSIMAL = 100 * 1024;

    private const DAFTAR_LEBAR = [1200, 1000, 800, 640, 512, 400, 320];

    private const DAFTAR_KUALITAS = [82, 72, 62, 52];

    public function __construct(private KapasitasMenu $kapasitasMenu) {}

    public function index(Request $request): View
    {
        $query = Produk::with(['kategori', 'varian']);

        if ($request->filled('cari')) {
            $query->where('nama', 'like', "%{$request->cari}%");
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->integer('kategori_id'));
        }

        if ($request->status === 'aktif') {
            $query->where('aktif', 1);
        } elseif ($request->status === 'nonaktif') {
            $query->where('aktif', 0);
        }

        $daftarProduk = $query->latest()->paginate(12)->withQueryString();
        $daftarKategori = Kategori::orderBy('nama')->pluck('nama', 'id');
        $jumlahAktif = Produk::where('aktif', 1)->count();
        $jumlahStokHabis = Produk::where('aktif', 1)->where('stok', '<=', 0)->count();

        return view('panel.admin.menu.index', compact(
            'daftarProduk',
            'daftarKategori',
            'jumlahAktif',
            'jumlahStokHabis'
        ));
    }

    public function create(): View
    {
        $daftarKategori = Kategori::orderBy('nama')->pluck('nama', 'id');
        $daftarBahan = $this->daftarBahan();
        $resepBahan = [];

        return view('panel.admin.menu.create', compact('daftarKategori', 'daftarBahan', 'resepBahan'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validasi($request);

        $produk = Produk::create([
            'kategori_id' => $this->kategoriId($data['kategori_nama'] ?? null),
            'nama' => $data['nama'],
            'slug' => $this->slugMenu($data['nama']),
            'deskripsi' => $data['deskripsi'] ?? null,
            'harga' => $data['harga'],
            'stok' => 0,
            'berat_gram' => $data['berat_gram'] ?? null,
            'gambar' => $request->hasFile('gambar')
                ? $this->simpanGambarTerkompres($request->file('gambar'), $this->slugMenu($data['nama']))
                : null,
            'aktif' => $request->boolean('aktif', true),
            'dibuat_oleh' => auth()->id(),
            'diubah_oleh' => auth()->id(),
        ]);

        VarianProduk::create([
            'produk_id' => $produk->id,
            'nama' => 'Reguler',
            'harga' => $produk->harga,
            'aktif' => 1,
        ]);

        $this->simpanResep($produk, $data);
        $this->kapasitasMenu->sinkronkan();

        return redirect()->route('panel.admin.menu.index')
            ->with('sukses', "Menu {$produk->nama} berhasil ditambahkan. Stok otomatis dihitung dari resep dan bahan yang tersedia!");
    }

    public function edit(int $id): View
    {
        $produk = Produk::with('varian.resepAktif.bahanList')->findOrFail($id);
        $daftarKategori = Kategori::orderBy('nama')->pluck('nama', 'id');
        $daftarBahan = $this->daftarBahan();
        $resepBahan = $this->resepBahan($produk);

        return view('panel.admin.menu.edit', compact(
            'produk',
            'daftarKategori',
            'daftarBahan',
            'resepBahan'
        ));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $produk = Produk::findOrFail($id);
        $data = $this->validasi($request, $produk->id);

        $gambar = $produk->gambar;
        if ($request->hasFile('gambar')) {
            $gambar = $this->simpanGambarTerkompres($request->file('gambar'), $this->slugMenu($data['nama']));
            if ($produk->gambar && ! str_starts_with($produk->gambar, 'http')) {
                Storage::disk('public')->delete($produk->gambar);
            }
        }

        $produk->update([
            'kategori_id' => $this->kategoriId($data['kategori_nama'] ?? null),
            'nama' => $data['nama'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'harga' => $data['harga'],
            'berat_gram' => $data['berat_gram'] ?? null,
            'gambar' => $gambar,
            'aktif' => $request->boolean('aktif'),
            'diubah_oleh' => auth()->id(),
        ]);

        VarianProduk::where('produk_id', $produk->id)
            ->where('nama', 'Reguler')
            ->update(['harga' => $produk->harga]);

        $this->simpanResep($produk, $data);
        $this->kapasitasMenu->sinkronkan();

        return redirect()->route('panel.admin.menu.index')
            ->with('sukses', "Menu {$produk->nama} berhasil diperbarui. Stok otomatis disesuaikan dengan resep terbaru!");
    }

    public function destroy(int $id): RedirectResponse
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();
        $this->kapasitasMenu->sinkronkan();

        return redirect()->route('panel.admin.menu.index')
            ->with('sukses', "Menu {$produk->nama} berhasil dinonaktifkan. Stok menu lain diperbarui.");
    }

    public function hitungUlang(): RedirectResponse
    {
        $hasil = $this->kapasitasMenu->sinkronkan();
        $totalCup = array_sum($hasil['kapasitas']);

        return back()->with('sukses', "Stok menu berhasil dihitung ulang dari resep dan bahan. Total kapasitas: {$totalCup} cup.");
    }

    /**
     * @return array<string, mixed>
     */
    private function validasi(Request $request, ?int $produkId = null): array
    {
        $unikNama = 'unique:produk,nama'.($produkId ? ','.$produkId : '');

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:150', $unikNama],
            'kategori_nama' => ['nullable', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'harga' => ['required', 'integer', 'min:0'],
            'berat_gram' => ['nullable', 'integer', 'min:0'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'aktif' => ['nullable', 'boolean'],
            'resep_bahan' => ['required', 'array', 'min:1'],
            'resep_bahan.*.bahan_id' => ['required', 'integer', 'exists:bahan,id'],
            'resep_bahan.*.takaran' => ['required', 'numeric', 'min:0.001', 'max:100000'],
        ], [
            'resep_bahan.required' => 'Minimal satu bahan harus ditambahkan pada resep per 1 cup.',
            'resep_bahan.min' => 'Minimal satu bahan harus ditambahkan pada resep per 1 cup.',
            'resep_bahan.*.bahan_id.required' => 'Pilih bahan untuk setiap baris resep.',
            'resep_bahan.*.bahan_id.exists' => 'Bahan yang dipilih tidak ditemukan.',
            'resep_bahan.*.takaran.required' => 'Isi takaran bahan per 1 cup.',
            'resep_bahan.*.takaran.min' => 'Takaran harus lebih besar dari nol.',
        ]);

        $bahanId = collect($data['resep_bahan'])->pluck('bahan_id')->all();

        if (count($bahanId) !== count(array_unique($bahanId))) {
            throw ValidationException::withMessages([
                'resep_bahan' => 'Bahan yang sama tidak boleh ditambahkan dua kali dalam satu resep.',
            ]);
        }

        return $data;
    }

    /**
     * @return Collection<int|string, string>
     */
    private function daftarBahan()
    {
        return Bahan::where('aktif', 1)->orderBy('nama')->pluck('nama', 'id');
    }

    /**
     * @return array<int, array{bahan_id: int, takaran: string}>
     */
    private function resepBahan(Produk $produk): array
    {
        foreach ($produk->varian as $varian) {
            $resep = $varian->resepAktif;

            if (! $resep || $resep->bahanList->isEmpty()) {
                continue;
            }

            return $resep->bahanList
                ->map(fn (Bahan $bahan): array => [
                    'bahan_id' => (int) $bahan->id,
                    'takaran' => (string) $bahan->pivot->takaran,
                ])
                ->values()
                ->all();
        }

        return [];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function simpanResep(Produk $produk, array $data): void
    {
        $varian = $produk->varian()->where('nama', 'Reguler')->first()
            ?? $produk->varian()->first();

        if (! $varian) {
            $varian = VarianProduk::create([
                'produk_id' => $produk->id,
                'nama' => 'Reguler',
                'harga' => $produk->harga,
                'aktif' => 1,
            ]);
        }

        Resep::where('varian_id', $varian->id)->where('aktif', 1)->update(['aktif' => 0]);

        $resep = Resep::create([
            'varian_id' => $varian->id,
            'versi' => (int) Resep::where('varian_id', $varian->id)->max('versi') + 1,
            'berlaku_mulai' => now(),
            'aktif' => 1,
            'alasan_perubahan' => 'Resep diperbarui dari panel admin',
            'dibuat_oleh' => auth()->id(),
        ]);

        $takaran = [];

        foreach ($data['resep_bahan'] as $bahan) {
            $takaran[(int) $bahan['bahan_id']] = ['takaran' => (float) $bahan['takaran']];
        }

        $resep->bahanList()->sync($takaran);
    }

    private function slugMenu(string $nama): string
    {
        return (Str::slug($nama) ?: 'menu').'-'.Str::lower(Str::random(6));
    }

    private function kategoriId(?string $namaKategori): ?int
    {
        $namaKategori = trim((string) $namaKategori);

        if ($namaKategori === '') {
            return null;
        }

        $kategori = Kategori::firstOrCreate(
            ['slug' => Str::slug($namaKategori)],
            ['nama' => $namaKategori, 'aktif' => 1]
        );

        return $kategori->id;
    }

    private function simpanGambarTerkompres(UploadedFile $gambar, string $slug): string
    {
        $pathFile = $gambar->getRealPath();
        $info = getimagesize($pathFile);

        if ($info === false) {
            throw ValidationException::withMessages(['gambar' => 'Gambar menu tidak dapat dibaca.']);
        }

        $sumber = match ($info[2]) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($pathFile),
            IMAGETYPE_PNG => imagecreatefrompng($pathFile),
            IMAGETYPE_WEBP => imagecreatefromwebp($pathFile),
            default => false,
        };

        if ($sumber === false) {
            throw ValidationException::withMessages(['gambar' => 'Format gambar tidak didukung.']);
        }

        $lebarAsli = imagesx($sumber);
        $tinggiAsli = imagesy($sumber);

        foreach (self::DAFTAR_LEBAR as $lebarMaksimal) {
            $skala = min(1, $lebarMaksimal / max($lebarAsli, $tinggiAsli));
            $lebar = max(1, (int) round($lebarAsli * $skala));
            $tinggi = max(1, (int) round($tinggiAsli * $skala));

            $kanvas = imagecreatetruecolor($lebar, $tinggi);
            imagealphablending($kanvas, false);
            imagesavealpha($kanvas, true);
            imagefilledrectangle($kanvas, 0, 0, $lebar, $tinggi, imagecolorallocatealpha($kanvas, 0, 0, 0, 127));
            imagecopyresampled($kanvas, $sumber, 0, 0, 0, 0, $lebar, $tinggi, $lebarAsli, $tinggiAsli);

            foreach (self::DAFTAR_KUALITAS as $kualitas) {
                $fileSementara = tempnam(sys_get_temp_dir(), 'omyu-menu-');
                imagewebp($kanvas, $fileSementara, $kualitas);

                if (filesize($fileSementara) <= self::UKURAN_GAMBAR_MAKSIMAL) {
                    $namaFile = $slug.'-'.Str::lower(Str::random(10)).'.webp';
                    Storage::disk('public')->put('menu/'.$namaFile, file_get_contents($fileSementara));
                    imagedestroy($kanvas);
                    imagedestroy($sumber);
                    unlink($fileSementara);

                    return 'menu/'.$namaFile;
                }

                unlink($fileSementara);
            }

            imagedestroy($kanvas);
        }

        imagedestroy($sumber);

        throw ValidationException::withMessages(['gambar' => 'Gambar terlalu besar untuk dikompresi.']);
    }
}

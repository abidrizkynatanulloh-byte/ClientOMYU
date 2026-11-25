<?php

namespace App\Http\Controllers\Staf;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Support\KapasitasMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PesananController extends Controller
{
    private const TRANSISI_STATUS = [
        'menunggu_pembayaran' => ['menunggu_verifikasi', 'dibatalkan', 'kedaluwarsa'],
        'menunggu_verifikasi' => ['dikonfirmasi', 'dibatalkan'],
        'dikonfirmasi' => ['diproses', 'siap_diambil', 'dibatalkan'],
        'diproses' => ['siap_diambil', 'selesai', 'dibatalkan'],
        'siap_diambil' => ['selesai', 'dibatalkan'],
    ];

    public function __construct(private KapasitasMenu $kapasitasMenu) {}

    public function index(Request $request)
    {
        $query = Pesanan::with(['itemPesanan.produk', 'penangan', 'pembayaran'])->latest();

        if ($request->filled('tab')) {
            $tab = $request->tab;
            if ($tab === 'menunggu_cek') {
                $query->whereIn('status_pesanan', ['menunggu_pembayaran', 'menunggu_verifikasi']);
            } elseif ($tab === 'sudah_bayar') {
                $query->where('status_pembayaran', 'lunas');
            } elseif ($tab === 'diproses') {
                $query->where('status_pesanan', 'diproses');
            } elseif ($tab === 'siap') {
                $query->where('status_pesanan', 'siap_diambil');
            } elseif ($tab === 'selesai') {
                $query->where('status_pesanan', 'selesai');
            }
        } elseif ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'baru') {
                $query->whereIn('status_pesanan', ['menunggu_pembayaran', 'menunggu_verifikasi']);
            } elseif (in_array($status, ['dikirim', 'siap'], true)) {
                $query->where('status_pesanan', 'siap_diambil');
            } else {
                $query->where('status_pesanan', $status);
            }
        }

        if ($request->filled('pembayaran')) {
            $query->where('status_pembayaran', $request->pembayaran);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        if ($request->filled('cari')) {
            $cari = trim(str_replace('#', '', $request->cari));
            $query->where(function ($q) use ($cari) {
                $q->where('kode_pesanan', 'like', "%{$cari}%")
                    ->orWhere('nama_pembeli', 'like', "%{$cari}%")
                    ->orWhere('telepon_pembeli', 'like', "%{$cari}%");
            });
        }

        $antreanOrderMasuk = Pesanan::whereIn('status_pesanan', ['menunggu_pembayaran', 'menunggu_verifikasi', 'dikonfirmasi'])->count();
        $perluVerifikasiCount = Pesanan::whereIn('status_pesanan', ['menunggu_pembayaran', 'menunggu_verifikasi'])->count();
        $prosesDiBlenderCount = Pesanan::where('status_pesanan', 'diproses')->count();

        $semuaCount = Pesanan::count();
        $menungguCekCount = $perluVerifikasiCount;
        $sudahBayarCount = Pesanan::where('status_pembayaran', 'lunas')->count();
        $siapAmbilCount = Pesanan::where('status_pesanan', 'siap_diambil')->count();
        $selesaiCount = Pesanan::where('status_pesanan', 'selesai')->count();
        $selesaiHariIniCount = Pesanan::where(function ($q) {
            $q->whereDate('diselesaikan_pada', today())->orWhere(function ($q2) {
                $q2->whereNull('diselesaikan_pada')
                    ->where('status_pesanan', 'selesai')
                    ->whereDate('updated_at', today());
            });
        })->count();

        $daftarPesanan = $query->paginate(15)->withQueryString();

        return view('panel.staf.pesanan.index', compact(
            'daftarPesanan',
            'antreanOrderMasuk',
            'perluVerifikasiCount',
            'prosesDiBlenderCount',
            'semuaCount',
            'menungguCekCount',
            'sudahBayarCount',
            'siapAmbilCount',
            'selesaiCount',
            'selesaiHariIniCount'
        ));
    }

    public function detail($id)
    {
        $pesanan = Pesanan::with(['itemPesanan.produk', 'itemPesanan.varian', 'pembayaran.verifikator', 'penangan'])->findOrFail($id);
        $pembayaranAktif = $this->pembayaranAktif($pesanan);
        $buktiPembayaranTersedia = $pembayaranAktif?->memilikiBuktiPembayaran() ?? false;

        return view('panel.staf.pesanan.detail', compact('pesanan', 'pembayaranAktif', 'buktiPembayaranTersedia'));
    }

    private function pembayaranAktif(Pesanan $pesanan): ?Pembayaran
    {
        return $pesanan->pembayaran()
            ->whereIn('status', ['menunggu', 'menunggu_verifikasi'])
            ->latest()
            ->first()
            ?? $pesanan->pembayaran()->latest()->first();
    }

    public function verifikasiPembayaran(Request $request, $id)
    {
        $request->validate([
            'catatan_verifikasi' => 'nullable|string|max:255',
        ]);

        $pesanan = Pesanan::with('pembayaran')->findOrFail($id);

        if ($pesanan->status_pembayaran === 'lunas') {
            return back()->with('info', 'Pembayaran pesanan '.$pesanan->kode_pesanan.' sudah diverifikasi sebelumnya.');
        }

        if (in_array($pesanan->status_pesanan, ['dibatalkan', 'kedaluwarsa', 'selesai'], true)) {
            return back()->with('error', 'Pembayaran pesanan ini tidak dapat diverifikasi karena status pesanan tidak memungkinkan.');
        }

        $pembayaran = $this->pembayaranAktif($pesanan);

        $nominal = $pembayaran?->jumlah ?? $pesanan->total;

        if ($nominal === null || (float) $nominal !== (float) $pesanan->total) {
            return back()->with('error', 'Nominal pembayaran tidak sesuai dengan total pesanan (Rp '.number_format($pesanan->total, 0, ',', '.').').');
        }

        $catatan = trim((string) $request->catatan_verifikasi);
        $metode = $pesanan->metode_bayar;

        if (in_array($metode, ['qris', 'transfer'], true) && ! $pembayaran?->memilikiBuktiPembayaran()) {
            return back()->with('error', 'Bukti pembayaran belum tersedia pada pesanan ini. Minta pembeli mengunggah bukti terlebih dahulu.');
        }

        $statusSebelum = [
            'status_pembayaran' => $pesanan->status_pembayaran,
            'status_pesanan' => $pesanan->status_pesanan,
        ];

        DB::transaction(function () use ($request, $pesanan, $pembayaran, $catatan, $metode, $nominal, $statusSebelum) {
            $audit = [
                'status' => 'lunas',
                'bukti_path' => $pembayaran?->bukti_path,
                'dibayar_pada' => now(),
                'diverifikasi_oleh' => auth()->id(),
                'diverifikasi_pada' => now(),
                'catatan_verifikasi' => $catatan ?: 'Diverifikasi oleh staf',
            ];

            if ($pembayaran) {
                $pembayaran->update($audit);
                $pesanan->pembayaran()
                    ->whereIn('status', ['menunggu', 'menunggu_verifikasi'])
                    ->whereKeyNot($pembayaran->id)
                    ->update(['status' => 'ditolak']);
            } else {
                Pesanan::findOrFail($pesanan->id)->pembayaran()->create(array_merge($audit, [
                    'gerbang' => 'manual',
                    'referensi_gerbang' => 'VERIF-'.strtoupper(uniqid()),
                    'metode' => $metode,
                    'jumlah' => $nominal,
                ]));
            }

            $pesanan->status_pembayaran = 'lunas';
            $pesanan->dibayar_pada = now();
            $pesanan->ditangani_oleh = auth()->id();

            if (in_array($pesanan->status_pesanan, ['menunggu_pembayaran', 'menunggu_verifikasi'], true)) {
                $pesanan->status_pesanan = 'dikonfirmasi';
            }

            if ($pesanan->jenis !== 'langsung') {
                $pesanan->terbitkanTokenAkses();
            }

            $pesanan->save();

            LogAktivitas::create([
                'pengguna_id' => auth()->id(),
                'aksi' => 'verifikasi_pembayaran',
                'tipe_objek' => 'pesanan',
                'id_objek' => $pesanan->id,
                'deskripsi' => 'Staf '.auth()->user()->nama.' mengonfirmasi pembayaran pesanan '.$pesanan->kode_pesanan,
                'alasan' => $catatan ?: null,
                'ip_address' => $request->ip(),
                'nilai_sebelum' => $statusSebelum,
                'nilai_sesudah' => [
                    'status_pembayaran' => 'lunas',
                    'status_pesanan' => $pesanan->status_pesanan,
                ],
            ]);
        });

        return back()->with('sukses', 'Pembayaran pesanan '.$pesanan->kode_pesanan.' berhasil diverifikasi.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pesanan' => 'required|in:menunggu_pembayaran,menunggu_verifikasi,dikonfirmasi,diproses,siap_diambil,selesai',
        ]);

        $pesanan = Pesanan::with('itemPesanan.produk')->findOrFail($id);
        $statusLama = $pesanan->status_pesanan;
        $statusBaru = $request->status_pesanan;

        if ($statusLama === $statusBaru) {
            return back()->with('info', 'Status pesanan tidak berubah.');
        }

        if (! isset(self::TRANSISI_STATUS[$statusLama]) || ! in_array($statusBaru, self::TRANSISI_STATUS[$statusLama], true)) {
            return back()->with('error', "Transisi status dari {$statusLama} ke {$statusBaru} tidak diizinkan.");
        }

        if (in_array($statusBaru, ['dikonfirmasi', 'diproses', 'siap_diambil', 'selesai'], true) && $pesanan->status_pembayaran !== 'lunas') {
            return back()->with('error', 'Pembayaran harus diverifikasi sebelum pesanan memasuki tahap tersebut.');
        }

        try {
            DB::transaction(function () use ($pesanan, $statusLama, $statusBaru, $request) {
                if ($statusBaru === 'selesai') {
                    $pesanan->catatPenguranganStokProduksi(auth()->id());
                    $pesanan->diselesaikan_pada = now();
                    $pesanan->terbitkanTokenAkses();
                }

                $pesanan->status_pesanan = $statusBaru;
                $pesanan->ditangani_oleh = auth()->id();
                $pesanan->save();

                LogAktivitas::create([
                    'pengguna_id' => auth()->id(),
                    'aksi' => $statusBaru === 'selesai' ? 'penyelesaian_pesanan' : 'update_status_pesanan',
                    'tipe_objek' => 'pesanan',
                    'id_objek' => $pesanan->id,
                    'deskripsi' => 'Mengubah status pesanan '.$pesanan->kode_pesanan.' dari '.$statusLama.' ke '.$statusBaru,
                    'ip_address' => $request->ip(),
                    'nilai_sebelum' => ['status_pesanan' => $statusLama],
                    'nilai_sesudah' => ['status_pesanan' => $statusBaru],
                ]);
            });
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        if ($statusBaru === 'selesai') {
            $this->kapasitasMenu->sinkronkan();
        }

        return back()->with('sukses', 'Status pesanan '.$pesanan->kode_pesanan.' berhasil diubah menjadi '.$statusBaru.'.');
    }

    public function batalkan(Request $request, $id)
    {
        $request->validate([
            'alasan_batal' => 'required|string|min:5|max:255',
        ]);

        $pesanan = Pesanan::findOrFail($id);

        if (in_array($pesanan->status_pesanan, ['selesai', 'dibatalkan', 'kedaluwarsa'], true)) {
            return back()->with('error', 'Pesanan ini tidak dapat dibatalkan.');
        }

        $alasan = $request->alasan_batal;

        try {
            DB::transaction(function () use ($pesanan, $alasan, $request) {
                $pesanan->kembalikanStokPesanan(auth()->id());
                $pesanan->status_pesanan = 'dibatalkan';
                $pesanan->alasan_batal = $alasan;
                $pesanan->catatan = ($pesanan->catatan ? $pesanan->catatan.' | ' : '').'[Ditolak: '.$alasan.']';
                $pesanan->ditangani_oleh = auth()->id();
                $pesanan->terbitkanTokenAkses();
                $pesanan->save();

                LogAktivitas::create([
                    'pengguna_id' => auth()->id(),
                    'aksi' => 'batalkan_pesanan',
                    'tipe_objek' => 'pesanan',
                    'id_objek' => $pesanan->id,
                    'deskripsi' => 'Membatalkan / menolak pesanan '.$pesanan->kode_pesanan.': '.$alasan,
                    'alasan' => $alasan,
                    'ip_address' => $request->ip(),
                ]);
            });
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        $this->kapasitasMenu->sinkronkan();

        return back()->with('sukses', 'Pesanan '.$pesanan->kode_pesanan.' telah ditolak / dibatalkan.');
    }
}

@extends('layouts.admin')

@section('title', 'Tambah Menu Baru')
@section('page_title', 'Tambah Menu Baru')
@section('badge', 'O-03A')
@section('page_subtitle', 'Daftarkan menu jualan baru dengan varian dan harga berbeda (misalnya Hot/Ice). Gambar otomatis dikompres menjadi WebP maksimal 100 KB.')

@section('header_actions')
    <a href="{{ route('panel.admin.menu.index') }}" class="btn-press inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Kembali ke Daftar Menu</span>
    </a>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">

    <div class="glass-card anim-fade-up delay-1 rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-soft">

        <div class="flex items-center gap-3.5 pb-6 mb-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-brand-100 text-brand-800 flex items-center justify-center text-xl font-bold shrink-0">
                <i class="fa-solid fa-glass-water"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-slate-900">Formulir Menu Baru</h3>
                <p class="text-xs text-slate-500 mt-0.5">Lengkapi nama, varian &amp; harga, resep, dan gambar menu.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('panel.admin.menu.store') }}" enctype="multipart/form-data" class="space-y-5"
              data-confirm="Simpan menu baru ini dan tampilkan di katalog?"
              data-confirm-title="Simpan Menu Baru?"
              data-confirm-btn="Ya, Simpan"
              data-confirm-type="success">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Menu <span class="text-rose-500">*</span></label>
                <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Jus Melon Segar" required
                       class="w-full px-4 py-2.5 rounded-xl border @error('nama') border-rose-300 bg-rose-50/30 @else border-slate-200 bg-slate-50/40 @enderror text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400 transition-all">
                @error('nama') <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Kategori</label>
                <input type="text" name="kategori_nama" value="{{ old('kategori_nama') }}" list="daftar-kategori" placeholder="Contoh: Jus Buah (ketik untuk membuat kategori baru)"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                <datalist id="daftar-kategori">
                    @foreach($daftarKategori as $namaKategori)
                        <option value="{{ $namaKategori }}"></option>
                    @endforeach
                </datalist>
                <span class="text-[11px] text-slate-400 mt-1 block">Boleh dikosongkan. Ketik nama baru untuk membuat kategori otomatis.</span>
                @error('kategori_nama') <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi</label>
                <textarea name="deskripsi" rows="3" placeholder="Ceritakan sedikit tentang menu ini..."
                          class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">{{ old('deskripsi') }}</textarea>
                @error('deskripsi') <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Harga Dasar (Rp) <span class="text-rose-500">*</span></label>
                    <input type="number" name="harga" value="{{ old('harga', 12000) }}" min="0" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                    <span class="text-[11px] text-slate-400 mt-1 block">Menjadi harga varian pertama. Harga tiap varian bisa diisi berbeda.</span>
                    @error('harga') <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>
                <div class="rounded-xl bg-brand-50 border border-brand-200 p-3">
                    <label class="block text-xs font-extrabold text-brand-900 mb-1">Stok Menu</label>
                    <p class="text-[11px] text-brand-800 leading-relaxed">
                        Dihitung otomatis dari resep &amp; stok bahan. Tidak diisi manual.
                    </p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Berat (gram)</label>
                    <input type="number" name="berat_gram" value="{{ old('berat_gram') }}" min="0"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                    @error('berat_gram') <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>
            </div>

            @php
                $barisVarian = old('varians', $daftarVarian);
                $barisResep = old('resep_bahan', $resepBahan ?: [['bahan_id' => '', 'takaran' => '']]);
            @endphp

            <div class="rounded-2xl bg-white border border-slate-200 p-4">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3">
                    <div>
                        <h4 class="text-xs font-extrabold text-slate-900">
                            <i class="fa-solid fa-layer-group mr-1 text-brand-600"></i> Varian &amp; Harga <span class="text-rose-500">*</span>
                        </h4>
                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                            Bisa lebih dari satu, misalnya <strong>Reguler</strong>, <strong>Hot</strong>, dan <strong>Ice</strong> dengan harga berbeda.
                            Varian pertama menjadi harga utama menu.
                        </p>
                    </div>
                    <button type="button" id="tambah-varian"
                            class="btn-press shrink-0 px-3 py-2 rounded-xl bg-brand-100 hover:bg-brand-200 text-brand-900 font-bold text-xs">
                        <i class="fa-solid fa-plus mr-1"></i> Tambah Varian
                    </button>
                </div>

                <div id="baris-varian" class="space-y-2">
                    @foreach($barisVarian as $indeks => $varian)
                        <div class="baris-varian grid grid-cols-1 sm:grid-cols-[1fr_10rem_auto] gap-2 items-start">
                            <input type="hidden" name="varians[{{ $indeks }}][id]" value="{{ $varian['id'] ?? '' }}">
                            <input type="text" name="varians[{{ $indeks }}][nama]" value="{{ $varian['nama'] ?? '' }}" placeholder="Contoh: Hot" maxlength="50" required
                                   class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                            <input type="number" name="varians[{{ $indeks }}][harga]" value="{{ $varian['harga'] ?? '' }}" min="0" placeholder="Harga (Rp)" required
                                   class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                            <button type="button" class="hapus-varian w-10 h-10 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center" title="Hapus varian">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    @endforeach
                </div>

                @error('varians') <p class="text-xs text-rose-600 mt-2 font-semibold">{{ $message }}</p> @enderror
                @error('varians.*.nama') <p class="text-xs text-rose-600 mt-2 font-semibold">{{ $message }}</p> @enderror
                @error('varians.*.harga') <p class="text-xs text-rose-600 mt-2 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="rounded-2xl bg-white border border-slate-200 p-4">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3">
                    <div>
                        <h4 class="text-xs font-extrabold text-slate-900">
                            <i class="fa-solid fa-flask mr-1 text-brand-600"></i> Resep per 1 Cup <span class="text-rose-500">*</span>
                        </h4>
                        <p class="text-[11px] text-slate-500 mt-1 leading-relaxed">
                            Isi takaran dengan satuan dasar bahan (gr / ml / pcs). Contoh: buah 20, gula 10, cup 1.
                            Stok menu dihitung otomatis dari bahan yang tersedia.
                        </p>
                    </div>
                    <button type="button" id="tambah-bahan"
                            class="btn-press shrink-0 px-3 py-2 rounded-xl bg-brand-100 hover:bg-brand-200 text-brand-900 font-bold text-xs">
                        <i class="fa-solid fa-plus mr-1"></i> Tambah Bahan
                    </button>
                </div>

                <div id="baris-resep" class="space-y-2">
                    @foreach($barisResep as $indeks => $baris)
                        <div class="baris-resep grid grid-cols-1 sm:grid-cols-[1fr_8rem_auto] gap-2 items-start">
                            <select name="resep_bahan[{{ $indeks }}][bahan_id]" required
                                    class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                                <option value="">-- Pilih bahan --</option>
                                @foreach($daftarBahan as $idBahan => $namaBahan)
                                    <option value="{{ $idBahan }}" @selected((string) ($baris['bahan_id'] ?? '') === (string) $idBahan)>{{ $namaBahan }}</option>
                                @endforeach
                            </select>
                            <input type="number" step="0.001" min="0.001" max="100000" required
                                   name="resep_bahan[{{ $indeks }}][takaran]"
                                   value="{{ $baris['takaran'] ?? '' }}" placeholder="Takaran"
                                   class="w-full px-3 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                            <button type="button" class="hapus-baris w-10 h-10 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 flex items-center justify-center" title="Hapus baris">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    @endforeach
                </div>

                @error('resep_bahan') <p class="text-xs text-rose-600 mt-2 font-semibold">{{ $message }}</p> @enderror
                @error('resep_bahan.*.bahan_id') <p class="text-xs text-rose-600 mt-2 font-semibold">{{ $message }}</p> @enderror
                @error('resep_bahan.*.takaran') <p class="text-xs text-rose-600 mt-2 font-semibold">{{ $message }}</p> @enderror
            </div>

            <div class="rounded-2xl bg-brand-50/70 border border-brand-200 p-4">
                <label class="block text-xs font-extrabold text-brand-900 mb-1.5">
                    <i class="fa-solid fa-image mr-1"></i> Gambar Menu (Opsional)
                </label>
                <input type="file" name="gambar" accept="image/jpeg,image/png,image/webp" id="preview-gambar"
                       class="w-full text-xs text-slate-600 file:mr-3 file:px-3 file:py-2 file:rounded-lg file:border-0 file:bg-brand-400 file:text-brand-950 file:font-bold file:text-xs hover:file:bg-brand-500">
                <p class="text-[11px] text-brand-800 mt-2 leading-relaxed">
                    Format JPG/PNG/WebP, maksimal upload 10 MB. Sistem akan mengecilkan ukuran dan mengompres otomatis menjadi <strong>WebP maksimal 100 KB</strong> sebelum disimpan.
                </p>
                <img id="gambar-preview" src="#" alt="Pratinjau gambar" class="hidden mt-3 w-32 h-32 object-cover rounded-xl border border-brand-200">
                @error('gambar') <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
            </div>

            <label class="flex items-center gap-2.5 text-xs font-bold text-slate-700 cursor-pointer">
                <input type="hidden" name="aktif" value="0">
                <input type="checkbox" name="aktif" value="1" {{ old('aktif', '1') ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-slate-300 text-brand-500 focus:ring-brand-400">
                Tampilkan menu ini di katalog pelanggan
            </label>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('panel.admin.menu.index') }}" class="btn-press px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">Batal</a>
                <button type="submit" class="btn-press px-6 py-2.5 rounded-xl bg-brand-400 hover:bg-brand-500 text-brand-950 font-extrabold text-xs sm:text-sm shadow-xs flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Menu Baru</span>
                </button>
            </div>

        </form>

    </div>

</div>
@endsection

@push('scripts')
<script>
    document.getElementById('preview-gambar')?.addEventListener('change', function (event) {
        const pratinjau = document.getElementById('gambar-preview');
        const berkas = event.target.files[0];
        if (!berkas) {
            pratinjau.classList.add('hidden');
            return;
        }
        pratinjau.src = URL.createObjectURL(berkas);
        pratinjau.classList.remove('hidden');
    });

    const wadahVarian = document.getElementById('baris-varian');
    let indeksVarian = wadahVarian.querySelectorAll('.baris-varian').length;
    const hargaDasar = document.querySelector('[name="harga"]');
    const hargaVarianPertama = wadahVarian.querySelector('[name$="[harga]"]');
    let hargaVarianDiubah = false;

    hargaVarianPertama?.addEventListener('input', function () {
        hargaVarianDiubah = true;
    });

    hargaDasar?.addEventListener('input', function () {
        if (!hargaVarianDiubah && hargaVarianPertama) {
            hargaVarianPertama.value = hargaDasar.value;
        }
    });

    document.getElementById('tambah-varian')?.addEventListener('click', function () {
        const contoh = wadahVarian.querySelector('.baris-varian');
        const barisBaru = contoh.cloneNode(true);

        barisBaru.querySelectorAll('[name]').forEach(function (elemen) {
            elemen.name = elemen.name.replace(/\[\d+\]/, '[' + indeksVarian + ']');
        });
        barisBaru.querySelector('[name$="[id]"]').value = '';
        barisBaru.querySelector('[name$="[nama]"]').value = '';
        barisBaru.querySelector('[name$="[harga]"]').value = '';

        wadahVarian.appendChild(barisBaru);
        indeksVarian++;
    });

    wadahVarian.addEventListener('click', function (event) {
        const tombolHapus = event.target.closest('.hapus-varian');
        if (!tombolHapus) {
            return;
        }
        if (wadahVarian.querySelectorAll('.baris-varian').length > 1) {
            tombolHapus.closest('.baris-varian').remove();
        }
    });

    const wadahResep = document.getElementById('baris-resep');
    let indeksResep = wadahResep.querySelectorAll('.baris-resep').length;

    document.getElementById('tambah-bahan')?.addEventListener('click', function () {
        const contoh = wadahResep.querySelector('.baris-resep');
        const barisBaru = contoh.cloneNode(true);

        barisBaru.querySelectorAll('[name]').forEach(function (elemen) {
            elemen.name = elemen.name.replace(/\[\d+\]/, '[' + indeksResep + ']');
        });
        barisBaru.querySelector('select').value = '';
        barisBaru.querySelector('input[type="number"]').value = '';

        wadahResep.appendChild(barisBaru);
        indeksResep++;
    });

    wadahResep.addEventListener('click', function (event) {
        const tombolHapus = event.target.closest('.hapus-baris');
        if (!tombolHapus) {
            return;
        }
        if (wadahResep.querySelectorAll('.baris-resep').length > 1) {
            tombolHapus.closest('.baris-resep').remove();
        }
    });
</script>
@endpush

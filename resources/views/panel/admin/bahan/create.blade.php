@extends('layouts.admin')

@section('title', 'Tambah Bahan Baku Baru')
@section('page_title', 'Tambah Bahan Baku')
@section('badge', 'O-04')
@section('page_subtitle', 'Daftarkan buah segar, bahan pembantu, atau kemasan baru ke dalam sistem persediaan OMYU.')

@section('header_actions')
    <a href="{{ route('panel.admin.bahan.index') }}" class="btn-press inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Kembali ke Persediaan</span>
    </a>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="glass-card anim-fade-up delay-1 rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-soft">
        
        <div class="flex items-center gap-3.5 pb-6 mb-6 border-b border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-brand-100 text-brand-800 flex items-center justify-center text-xl font-bold shrink-0">
                <i class="fa-solid fa-apple-whole"></i>
            </div>
            <div>
                <h3 class="text-lg font-extrabold text-slate-900">Formulir Pendaftaran Bahan Baku</h3>
                <p class="text-xs text-slate-500 mt-0.5">Lengkapi parameter satuan, konversi, rendemen buah, dan batas peringatan stok.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('panel.admin.bahan.store') }}" class="space-y-5"
              data-confirm="Simpan bahan baku baru ke gudang?"
              data-confirm-title="Simpan Bahan Baru?"
              data-confirm-btn="Ya, Simpan"
              data-confirm-type="success">
            @csrf

            <!-- Nama Bahan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Nama Bahan Baku / Buah <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="nama" value="{{ old('nama') }}" 
                       placeholder="Contoh: Belimbing Manis, Melon Madu, Gula Pasir..."
                       required
                       class="w-full px-4 py-2.5 rounded-xl border @error('nama') border-rose-300 bg-rose-50/30 @else border-slate-200 bg-slate-50/40 @enderror text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400 transition-all">
                @error('nama')
                    <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Jenis Bahan -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Jenis Kategori Bahan <span class="text-rose-500">*</span>
                </label>
                <select name="jenis" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                    <option value="buah" {{ old('jenis') == 'buah' ? 'selected' : '' }}>Buah Segar (Melon, Belimbing, Mangga, dll)</option>
                    <option value="bahan_lain" {{ old('jenis') == 'bahan_lain' ? 'selected' : '' }}>Bahan Lain / Pembantu (Gula, Susu, Es Batu)</option>
                    <option value="kemasan" {{ old('jenis') == 'kemasan' ? 'selected' : '' }}>Kemasan (Cup Plastik, Tutup, Sedotan)</option>
                </select>
            </div>

            <!-- Satuan Beli & Satuan Dasar -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Satuan Beli di Toko/Pasar <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="satuan-beli" name="satuan_beli" value="{{ old('satuan_beli', 'kg') }}" 
                           placeholder="Contoh: kg, liter, pack"
                           required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                    <span class="text-[11px] text-slate-400 mt-1 block">Satuan ketika belanja pembelian.</span>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Satuan Dasar di Gudang / Resep <span class="text-rose-500">*</span>
                    </label>
                    <select id="satuan-dasar" name="satuan_dasar" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                        <option value="g" {{ old('satuan_dasar') == 'g' ? 'selected' : '' }}>Gram (g) - untuk padatan/buah</option>
                        <option value="ml" {{ old('satuan_dasar') == 'ml' ? 'selected' : '' }}>Mililiter (ml) - untuk cairan/sirup</option>
                        <option value="pcs" {{ old('satuan_dasar') == 'pcs' ? 'selected' : '' }}>Pcs - untuk kemasan/cup</option>
                    </select>
                    <span class="text-[11px] text-slate-400 mt-1 block">Satuan standar kalkulasi takaran.</span>
                </div>
            </div>

            <!-- Konversi -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Nilai Konversi Satuan Beli ke Dasar <span class="text-rose-500">*</span>
                </label>
                <input type="number" step="any" min="1" id="konversi" name="konversi" value="{{ old('konversi', 1000) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                <span class="text-[11px] text-slate-400 mt-1 block">Contoh: 1 kg = 1000 g, 1 liter = 1000 ml, 1 pack cup = 50 pcs.</span>
            </div>

            <!-- Rendemen Card -->
            <div class="rounded-2xl bg-brand-50/70 border border-brand-200 p-4">
                <label class="block text-xs font-extrabold text-brand-900 mb-1">
                    Rendemen Standar Daging Buah (%) <span class="text-rose-500">*</span>
                </label>
                <input type="number" step="0.01" min="1" max="100" name="rendemen_standar" value="{{ old('rendemen_standar', 100) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-brand-300 bg-white text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-brand-400 font-bold">
                <p class="text-[11px] text-brand-800 mt-1.5 leading-relaxed">
                    Persentase daging buah siap jus setelah dikupas & dibuang biji/kulitnya. (Contoh: Melon ±70%, Belimbing ±85%. Untuk gula pasir atau kemasan isi 100%).
                </p>
            </div>

            <!-- Batas Minimum Stok Kritis -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    Batas Minimum Peringatan Stok (Satuan Dasar) <span class="text-rose-500">*</span>
                </label>
                <input type="number" step="any" name="batas_minimum" value="{{ old('batas_minimum', 500) }}" required
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                <span class="text-[11px] text-slate-400 mt-1 block">Sistem akan memicu alert "Stok Kritis" jika persediaan di bawah angka ini.</span>
            </div>

            <!-- Stok Awal & Biaya per Satuan Beli -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Stok Awal Gudang / Kulkas (Opsional)
                    </label>
                    <input type="number" step="any" name="stok_awal" value="{{ old('stok_awal', 0) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                    <span class="text-[11px] text-slate-400 mt-1 block">Dalam satuan dasar (g / ml / pcs).</span>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">
                        Biaya per <span id="label-satuan-beli">{{ old('satuan_beli', 'kg') }}</span> (Rp)
                    </label>
                    <input type="number" step="0.0001" min="0" id="harga-beli" name="harga_beli" value="{{ old('harga_beli', 0) }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/40 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-400">
                    <span class="text-[11px] text-slate-400 mt-1 block">Isi harga sesuai satuan beli. Jeruk 1 kg = 20000 → isi 20000. Cup per pack isi 10 → isi harga per pack.</span>
                    <span id="ringkasan-biaya" class="text-[11px] text-brand-700 mt-1 block font-semibold"></span>
                    @error('harga_beli') <p class="text-xs text-rose-600 mt-1 font-semibold">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('panel.admin.bahan.index') }}" class="btn-press px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition-colors">
                    Batal
                </a>
                <button type="submit" class="btn-press px-6 py-2.5 rounded-xl bg-brand-400 hover:bg-brand-500 text-brand-950 font-extrabold text-xs sm:text-sm shadow-xs flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Bahan Baru</span>
                </button>
            </div>

        </form>

    </div>

</div>
@push('scripts')
<script>
    const satuanBeliInput = document.getElementById('satuan-beli');
    const konversiInput = document.getElementById('konversi');
    const satuanDasarInput = document.getElementById('satuan-dasar');
    const hargaBeliInput = document.getElementById('harga-beli');
    const labelSatuanBeli = document.getElementById('label-satuan-beli');
    const ringkasanBiaya = document.getElementById('ringkasan-biaya');

    function perbaruiRingkasanBiaya() {
        const satuanBeli = satuanBeliInput.value.trim() || 'satuan beli';
        const satuanDasar = satuanDasarInput.value;
        const konversi = Math.max(1, Number(konversiInput.value) || 1);
        const harga = Number(hargaBeliInput.value) || 0;
        const perDasar = harga / konversi;

        labelSatuanBeli.textContent = satuanBeli;
        ringkasanBiaya.textContent = `Disimpan sebagai Rp ${perDasar.toLocaleString('id-ID', { maximumFractionDigits: 4 })}/${satuanDasar}.`;
    }

    [satuanBeliInput, konversiInput, satuanDasarInput, hargaBeliInput].forEach(function (elemen) {
        elemen.addEventListener('input', perbaruiRingkasanBiaya);
        elemen.addEventListener('change', perbaruiRingkasanBiaya);
    });

    perbaruiRingkasanBiaya();
</script>
@endpush
@endsection
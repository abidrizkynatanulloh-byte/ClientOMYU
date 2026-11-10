<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Bahan Baku Baru - OMYU</title>
    <style>
        body { font-family: sans-serif; padding: 24px; background: #f8fafc; color: #1e293b; max-width: 650px; margin: auto; }
        .box { background: #fff; padding: 24px; border-radius: 10px; border: 1px solid #e2e8f0; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-weight: bold; margin-bottom: 6px; font-size: 14px; }
        input, select { width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; }
        small { color: #64748b; font-size: 12px; display: block; margin-top: 4px; }
        button { background: #16a34a; color: white; border: none; padding: 10px 18px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px; }
        .galat { color: #dc2626; font-size: 13px; margin-top: 4px; }
    </style>
</head>
<body>

    <p><a href="{{ route('panel.admin.bahan.index') }}">← Kembali ke Daftar Bahan</a></p>
    <h1>Form Tambah Bahan Baru</h1>

    <div class="box">
        <form method="POST" action="{{ route('panel.admin.bahan.store') }}">
            @csrf

            <div class="form-group">
                <label>Nama Bahan / Buah</label>
                <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Belimbing, Melon, Gula" required>
                @error('nama') <div class="galat">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Jenis Bahan</label>
                <select name="jenis" required>
                    <option value="buah" {{ old('jenis') == 'buah' ? 'selected' : '' }}>Buah Segar</option>
                    <option value="bahan_lain" {{ old('jenis') == 'bahan_lain' ? 'selected' : '' }}>Bahan Lain (Gula, Susu, Es)</option>
                    <option value="kemasan" {{ old('jenis') == 'kemasan' ? 'selected' : '' }}>Kemasan (Cup, Sedotan, Tutup)</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label>Satuan Beli di Toko/Pasar</label>
                    <input type="text" name="satuan_beli" value="{{ old('satuan_beli', 'kg') }}" placeholder="Contoh: kg, liter, pack" required>
                </div>
                <div class="form-group">
                    <label>Satuan Dasar di Gudang</label>
                    <select name="satuan_dasar" required>
                        <option value="g" {{ old('satuan_dasar') == 'g' ? 'selected' : '' }}>Gram (g)</option>
                        <option value="ml" {{ old('satuan_dasar') == 'ml' ? 'selected' : '' }}>Mililiter (ml)</option>
                        <option value="pcs" {{ old('satuan_dasar') == 'pcs' ? 'selected' : '' }}>Pcs (Buah)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Konversi ke Satuan Dasar</label>
                <input type="number" step="any" name="konversi" value="{{ old('konversi', 1000) }}" required>
                <small>Contoh: 1 kg = 1000 g, 1 liter = 1000 ml, 1 pack = 50 pcs</small>
            </div>

            <div class="form-group" style="background: #f0fdf4; padding: 12px; border-radius: 8px; border: 1px solid #bbf7d0;">
                <label style="color: #166534;">Rendemen Standar Daging Buah (%)</label>
                <input type="number" step="0.01" min="1" max="100" name="rendemen_standar" value="{{ old('rendemen_standar', 100) }}" required>
                <small>Berapa % daging buah yang bisa dipakai setelah dikupas. (Melon: 70%. Untuk gula/kemasan isi 100%).</small>
            </div>

            <div class="form-group">
                <label>Batas Minimum Peringatan Stok</label>
                <input type="number" step="any" name="batas_minimum" value="{{ old('batas_minimum', 500) }}" required>
                <small>Jika stok di bawah angka ini (dalam satuan dasar), sistem akan memberi peringatan Stok Kritis.</small>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label>Stok Awal di Kulkas (Opsional)</label>
                    <input type="number" step="any" name="stok_awal" value="{{ old('stok_awal', 0) }}"><small>(gram)</small>
                </div>
                <div class="form-group">
                    <label>Harga Rata-rata per Satuan Dasar (Rp)</label>
                    <input type="number" step="0.0001" name="biaya_rata_rata" value="{{ old('biaya_rata_rata', 0) }}">
                </div>
            </div>

            <button type="submit">Simpan Bahan Baru</button>
        </form>
    </div>

</body>
</html>
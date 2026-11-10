<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Bahan {{ $bahan->nama }} - OMYU</title>
    <style>
        body { font-family: sans-serif; padding: 24px; background: #f8fafc; color: #1e293b; max-width: 650px; margin: auto; }
        .box { background: #fff; padding: 24px; border-radius: 10px; border: 1px solid #e2e8f0; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-weight: bold; margin-bottom: 6px; font-size: 14px; }
        input, select { width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; }
        small { color: #64748b; font-size: 12px; display: block; margin-top: 4px; }
        button { background: #0284c7; color: white; border: none; padding: 10px 18px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px; }
    </style>
</head>
<body>

    <p><a href="{{ route('panel.admin.bahan.index') }}">← Kembali ke Daftar Bahan</a></p>
    <h1>Edit Bahan: {{ $bahan->nama }}</h1>

    <div class="box">
        <form method="POST" action="{{ route('panel.admin.bahan.update', $bahan->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Bahan</label>
                <input type="text" name="nama" value="{{ old('nama', $bahan->nama) }}" required>
            </div>

            <div class="form-group">
                <label>Jenis Bahan</label>
                <select name="jenis" required>
                    <option value="buah" {{ $bahan->jenis == 'buah' ? 'selected' : '' }}>Buah Segar</option>
                    <option value="bahan_lain" {{ $bahan->jenis == 'bahan_lain' ? 'selected' : '' }}>Bahan Lain (Gula, Susu, Es)</option>
                    <option value="kemasan" {{ $bahan->jenis == 'kemasan' ? 'selected' : '' }}>Kemasan (Cup, Sedotan)</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div class="form-group">
                    <label>Satuan Beli</label>
                    <input type="text" name="satuan_beli" value="{{ old('satuan_beli', $bahan->satuan_beli) }}" required>
                </div>
                <div class="form-group">
                    <label>Satuan Dasar</label>
                    <select name="satuan_dasar" required>
                        <option value="g" {{ $bahan->satuan_dasar == 'g' ? 'selected' : '' }}>Gram (g)</option>
                        <option value="ml" {{ $bahan->satuan_dasar == 'ml' ? 'selected' : '' }}>Mililiter (ml)</option>
                        <option value="pcs" {{ $bahan->satuan_dasar == 'pcs' ? 'selected' : '' }}>Pcs</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Konversi ke Satuan Dasar</label>
                <input type="number" step="any" name="konversi" value="{{ old('konversi', (float) $bahan->konversi) }}" required>
            </div>

            <div class="form-group" style="background: #f0fdf4; padding: 12px; border-radius: 8px; border: 1px solid #bbf7d0;">
                <label style="color: #166534;">Rendemen Standar Daging Buah (%)</label>
                <input type="number" step="0.01" min="1" max="100" name="rendemen_standar" value="{{ old('rendemen_standar', (float) $bahan->rendemen_standar) }}" required>
            </div>

            <div class="form-group">
                <label>Batas Minimum Peringatan Stok</label>
                <input type="number" step="any" name="batas_minimum" value="{{ old('batas_minimum', (float) $bahan->batas_minimum) }}" required>
            </div>

            <div class="form-group">
                <label>Status Aktif</label>
                <select name="aktif" required>
                    <option value="1" {{ $bahan->aktif ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ !$bahan->aktif ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <button type="submit">Perbarui Data Bahan</button>
        </form>
    </div>

</body>
</html>
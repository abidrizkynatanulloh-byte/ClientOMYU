<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Akun {{ $karyawan->nama }} - OMYU</title>
    <style>
        body { font-family: sans-serif; padding: 24px; background: #f8fafc; color: #1e293b; max-width: 600px; margin: auto; }
        .box { background: #fff; padding: 24px; border-radius: 10px; border: 1px solid #e2e8f0; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-weight: bold; margin-bottom: 6px; font-size: 14px; }
        input, select { width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; }
        button { background: #0284c7; color: white; border: none; padding: 10px 18px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px; }
    </style>
</head>
<body>

    <p><a href="{{ route('panel.admin.karyawan.index') }}">← Kembali ke Daftar Karyawan</a></p>
    <h1>Edit Akun: {{ $karyawan->nama }}</h1>

    <div class="box">
        <form method="POST" action="{{ route('panel.admin.karyawan.update', $karyawan->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama', $karyawan->nama) }}" required>
            </div>

            <div class="form-group">
                <label>Alamat Email</label>
                <input type="email" name="email" value="{{ old('email', $karyawan->email) }}" required>
            </div>

            <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="text" name="telepon" value="{{ old('telepon', $karyawan->telepon) }}">
            </div>

            <div class="form-group" style="background: #fffbeb; padding: 12px; border-radius: 8px; border: 1px solid #fef3c7;">
                <label>Ganti Kata Sandi (Kosongkan jika tidak ingin diubah)</label>
                <input type="password" name="password" placeholder="Isi hanya jika ingin mereset password">
            </div>

            <div class="form-group">
                <label>Peran</label>
                <select name="peran" required>
                    <option value="karyawan" {{ $karyawan->peran == 'karyawan' ? 'selected' : '' }}>Karyawan / Kasir</option>
                    <option value="admin" {{ $karyawan->peran == 'admin' ? 'selected' : '' }}>Admin / Owner</option>
                </select>
            </div>

            <div class="form-group">
                <label>Status Akun</label>
                <select name="aktif" required>
                    <option value="1" {{ $karyawan->aktif ? 'selected' : '' }}>Aktif (Bisa Login)</option>
                    <option value="0" {{ !$karyawan->aktif ? 'selected' : '' }}>Nonaktif (Tidak Bisa Login)</option>
                </select>
            </div>

            <div class="form-group" style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                <label style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="bisa_lihat_hpp" value="1" {{ $karyawan->bisa_lihat_hpp ? 'checked' : '' }} style="width: auto;">
                    Beri izin melihat HPP dan Margin
                </label>
            </div>

            <button type="submit">Perbarui Akun</button>
        </form>
    </div>

</body>
</html>
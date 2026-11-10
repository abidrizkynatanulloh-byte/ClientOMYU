<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Karyawan Baru - OMYU</title>
    <style>
        body { font-family: sans-serif; padding: 24px; background: #f8fafc; color: #1e293b; max-width: 600px; margin: auto; }
        .box { background: #fff; padding: 24px; border-radius: 10px; border: 1px solid #e2e8f0; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-weight: bold; margin-bottom: 6px; font-size: 14px; }
        input, select { width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; }
        button { background: #16a34a; color: white; border: none; padding: 10px 18px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px; }
        .galat { color: #dc2626; font-size: 13px; margin-top: 4px; }
    </style>
</head>
<body>

    <p><a href="{{ route('panel.admin.karyawan.index') }}">← Kembali ke Daftar Karyawan</a></p>
    <h1>Tambah Akun Staf Baru</h1>

    <div class="box">
        <form method="POST" action="{{ route('panel.admin.karyawan.store') }}">
            @csrf

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Budi Santoso" required>
                @error('nama') <div class="galat">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Contoh: budi@omyu.test" required>
                @error('email') <div class="galat">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Nomor WhatsApp / Telepon</label>
                <input type="text" name="telepon" value="{{ old('telepon') }}" placeholder="Contoh: 08123456789">
            </div>

            <div class="form-group">
                <label>Kata Sandi (Password)</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" required>
                @error('password') <div class="galat">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Peran Pengguna</label>
                <select name="peran" required>
                    <option value="karyawan">Karyawan / Kasir</option>
                    <option value="admin">Admin / Owner</option>
                </select>
            </div>

            <div class="form-group" style="background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                <label style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" name="bisa_lihat_hpp" value="1" style="width: auto;">
                    Beri izin melihat HPP dan Margin (Opsional untuk Kasir)
                </label>
                <small style="color: #64748b; margin-top: 4px; display: block;">Jika dicentang, kasir ini diperbolehkan melihat modal HPP per cup sesuai kebijakan Owner (Bab 1.6).</small>
            </div>

            <button type="submit">Buat Akun Karyawan</button>
        </form>
    </div>

</body>
</html>
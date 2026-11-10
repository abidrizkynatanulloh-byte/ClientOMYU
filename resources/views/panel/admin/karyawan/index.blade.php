<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Karyawan - OMYU</title>
    <style>
        body { font-family: sans-serif; padding: 24px; background: #f8fafc; color: #1e293b; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px; }
        .card { background: #fff; padding: 16px; border-radius: 10px; border: 1px solid #e2e8f0; }
        .card h3 { margin: 0 0 6px; font-size: 13px; color: #64748b; }
        .card .angka { font-size: 20px; font-weight: bold; margin: 0; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; margin-top: 16px; border: 1px solid #e2e8f0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        th { background: #f1f5f9; }
        .nav a { margin-right: 10px; text-decoration: none; padding: 6px 12px; background: #e2e8f0; color: #334155; border-radius: 6px; font-size: 13px; font-weight: bold; }
        .nav a.active { background: #0f172a; color: white; }
        .badge { padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: bold; }
        .badge-aktif { background: #dcfce7; color: #15803d; }
        .badge-nonaktif { background: #fee2e2; color: #b91c1c; }
        .pesan-sukses { background: #dcfce7; color: #15803d; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-weight: bold; }
        .pesan-galat { background: #fee2e2; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-weight: bold; }
        .btn-tambah { background: #16a34a; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>

    <div class="nav" style="margin-bottom: 20px;">
        <a href="{{ route('panel.admin.beranda') }}">📊 Dashboard</a>
        <a href="{{ route('panel.admin.penjualan.index') }}">🛒 Penjualan</a>
        <a href="{{ route('panel.admin.resep.index') }}">🍹 Resep & HPP</a>
        <a href="{{ route('panel.admin.bahan.index') }}">📦 Persediaan</a>
        <a href="{{ route('panel.admin.keuangan.laba_rugi') }}">💰 Laba Rugi</a>
        <a href="{{ route('panel.admin.karyawan.index') }}" class="active">👥 Karyawan (O-09)</a>
    </div>

    @if(session('sukses'))
        <div class="pesan-sukses">{{ session('sukses') }}</div>
    @endif
    @if(session('galat'))
        <div class="pesan-galat">{{ session('galat') }}</div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h1>Daftar Akun Karyawan & Staf (O-09)</h1>
        <a href="{{ route('panel.admin.karyawan.create') }}" class="btn-tambah">+ Tambah Karyawan Baru</a>
    </div>

    <div class="grid">
        <div class="card">
            <h3>Total Staf Terdaftar</h3>
            <p class="angka">{{ $totalStaf }} Orang</p>
        </div>
        <div class="card">
            <h3>Admin / Owner</h3>
            <p class="angka">{{ $totalAdmin }} Akun</p>
        </div>
        <div class="card">
            <h3>Kasir / Karyawan Aktif</h3>
            <p class="angka" style="color: #16a34a;">{{ $totalKaryawanAktif }} Orang</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>No Telepon</th>
                <th>Peran</th>
                <th>Izin Lihat HPP</th>
                <th>Status Akun</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($daftarKaryawan as $k)
                <tr>
                    <td><strong>{{ $k->nama }}</strong></td>
                    <td>{{ $k->email }}</td>
                    <td>{{ $k->telepon ?? '-' }}</td>
                    <td>
                        <span style="font-weight: bold; text-transform: uppercase; color: {{ $k->peran == 'admin' ? '#0369a1' : '#475569' }};">
                            {{ $k->peran }}
                        </span>
                    </td>
                    <td>
                        @if($k->peran === 'admin')
                            <small style="color: #15803d;">Ya (Otomatis)</small>
                        @else
                            {{ $k->bisa_lihat_hpp ? '✅ Diizinkan' : '❌ Dilarang' }}
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $k->aktif ? 'badge-aktif' : 'badge-nonaktif' }}">
                            {{ $k->aktif ? 'AKTIF' : 'NONAKTIF' }}
                        </span>
                    </td>
                    <td style="display: flex; gap: 8px;">
                        <a href="{{ route('panel.admin.karyawan.edit', $k->id) }}">Edit</a>
                        
                        @if(auth()->id() !== $k->id)
                            <form method="POST" action="{{ route('panel.admin.karyawan.toggle_status', $k->id) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" style="background: none; border: none; color: {{ $k->aktif ? '#dc2626' : '#16a34a' }}; cursor: pointer; text-decoration: underline; padding: 0;">
                                    {{ $k->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
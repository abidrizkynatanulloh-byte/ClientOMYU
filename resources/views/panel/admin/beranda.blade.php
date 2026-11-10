<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Owner - OMYU</title>
    <style>
        body { font-family: sans-serif; padding: 24px; background: #f8fafc; color: #1e293b; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px; }
        .card { background: #fff; padding: 18px; border-radius: 12px; border: 1px solid #e2e8f0; }
        .card h3 { margin: 0 0 8px; font-size: 14px; color: #64748b; }
        .card .angka { font-size: 24px; font-weight: bold; margin: 0; color: #0f172a; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; margin-bottom: 24px; border: 1px solid #e2e8f0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #f1f5f9; font-size: 14px; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: bold; background: #fee2e2; color: #b91c1c; }
    </style>
</head>
<body>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h1>Dashboard Eksekutif Owner (OMYU)</h1>
        
            <!-- Menu Navigasi Modul Owner -->
    <div style="display: flex; gap: 10px; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #e2e8f0;">
        <a href="{{ route('panel.admin.beranda') }}" style="padding: 8px 14px; background: #0f172a; color: white; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: bold;">
            📊 Dashboard (O-01)
        </a>
        <a href="{{ route('panel.admin.penjualan.index') }}" style="padding: 8px 14px; background: #fff; color: #0f172a; border: 1px solid #cbd5e1; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: bold;">
            🛒 Penjualan & Pesanan (O-02)
        </a>
                <a href="{{ route('panel.admin.resep.index') }}" style="padding: 8px 14px; background: #fff; color: #0f172a; border: 1px solid #cbd5e1; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: bold;">
            🍹 Resep & HPP (O-03)
        </a>
                <a href="{{ route('panel.admin.bahan.index') }}" style="padding: 8px 14px; background: #fff; color: #0f172a; border: 1px solid #cbd5e1; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: bold;">
            📦 Persediaan Bahan (O-04)
        </a>
                <a href="{{ route('panel.admin.keuangan.laba_rugi') }}" style="padding: 8px 14px; background: #fff; color: #0f172a; border: 1px solid #cbd5e1; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: bold;">
            💰 Keuangan & Laba Rugi (O-05)
        </a>
                <a href="{{ route('panel.admin.karyawan.index') }}" style="padding: 8px 14px; background: #fff; color: #0f172a; border: 1px solid #cbd5e1; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: bold;">
            👥 Karyawan (O-09)
        </a>
        <form method="POST" action="{{ route('keluar') }}">
            @csrf
            <button type="submit" style="padding: 8px 16px; cursor: pointer;">Keluar</button>
        </form>
    </div>
    </div>

    <!-- Ringkasan Angka Bisnis Bulan Ini (O-01) -->
    <div class="grid">
        <div class="card">
            <h3>Omzet Bulan Ini</h3>
            <p class="angka">Rp {{ number_format($omzetBulanIni, 0, ',', '.') }}</p>
            <small>{{ $jumlahPesananBulanIni }} transaksi selesai</small>
        </div>
        <div class="card">
            <h3>Total Cup Terjual</h3>
            <p class="angka">{{ number_format($totalCupBulanIni, 0, ',', '.') }} Cup</p>
            <small>Bulan berjalan</small>
        </div>
        <div class="card">
            <h3>Estimasi Laba Kotor</h3>
            <p class="angka">Rp {{ number_format($labaKotorBulanIni, 0, ',', '.') }}</p>
            <small>Margin: {{ $marginLabaKotor }}%</small>
        </div>
        <div class="card">
            <h3>Omzet Hari Ini</h3>
            <p class="angka">Rp {{ number_format($omzetHariIni, 0, ',', '.') }}</p>
            <small>{{ $jumlahPesananHariIni }} transaksi hari ini</small>
        </div>
    </div>

    <!-- Peringatan Stok Kritis Bahan Baku -->
    <h2>Peringatan Bahan Baku (Stok Kritis)</h2>
    @if($bahanKritis->isEmpty())
        <p style="color: #16a34a;">Semua stok bahan baku aman di atas batas minimum.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Nama Bahan</th>
                    <th>Jenis</th>
                    <th>Stok Tersedia</th>
                    <th>Batas Minimum</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bahanKritis as $b)
                    <tr>
                        <td><strong>{{ $b->nama }}</strong></td>
                        <td>{{ ucfirst($b->jenis) }}</td>
                        <td>{{ (float) $b->stok_tersedia }} {{ $b->satuan_dasar }}</td>
                        <td>{{ (float) $b->batas_minimum }} {{ $b->satuan_dasar }}</td>
                        <td><span class="badge">Perlu Beli!</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Menu Terlaris -->
    <h2>5 Menu Terlaris Bulan Ini</h2>
    @if($menuTerlaris->isEmpty())
        <p style="color: #64748b;">Belum ada data penjualan pesanan selesai bulan ini.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Cup Terjual</th>
                    <th>Total Omzet</th>
                </tr>
            </thead>
            <tbody>
                @foreach($menuTerlaris as $menu)
                    <tr>
                        <td>{{ $menu->nama_produk }}</td>
                        <td>{{ $menu->total_terjual }} cup</td>
                        <td>Rp {{ number_format($menu->total_omzet, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</body>
</html>
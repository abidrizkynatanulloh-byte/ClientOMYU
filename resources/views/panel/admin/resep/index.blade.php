<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Analisis Resep, HPP & Margin - OMYU</title>
    <style>
        body { font-family: sans-serif; padding: 24px; background: #f8fafc; color: #1e293b; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; margin-top: 16px; border: 1px solid #e2e8f0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        th { background: #f1f5f9; }
        .nav a { margin-right: 10px; text-decoration: none; padding: 6px 12px; background: #e2e8f0; color: #334155; border-radius: 6px; font-size: 13px; font-weight: bold; }
        .nav a.active { background: #0f172a; color: white; }
        .badge { padding: 3px 8px; border-radius: 6px; font-size: 12px; font-weight: bold; }
        .badge-aman { background: #dcfce7; color: #15803d; }
        .badge-warning { background: #fee2e2; color: #b91c1c; }
    </style>
</head>
<body>

    <div class="nav" style="margin-bottom: 20px;">
        <a href="{{ route('panel.admin.beranda') }}">📊 Dashboard</a>
        <a href="{{ route('panel.admin.penjualan.index') }}">🛒 Penjualan</a>
        <a href="{{ route('panel.admin.resep.index') }}" class="active">🍹 Resep & HPP (O-03)</a>
        <a href="{{ route('panel.admin.bahan.index') }}">📦 Persediaan Bahan (O-04)</a>
        <a href="{{ route('panel.admin.keuangan.laba_rugi') }}" >
            💰 Keuangan & Laba Rugi (O-05)
        </a>
    </div>

    <h1>Tabel HPP, Margin & Kapasitas Cup per Menu</h1>
    <p style="color: #64748b;">Dihitung otomatis dari resep takaran dan biaya rata-rata bahan di database.</p>

    <table>
        <thead>
            <tr>
                <th>Menu</th>
                <th>Varian</th>
                <th>Kategori</th>
                <th>Harga Jual</th>
                <th>HPP Bahan</th>
                <th>Laba Kotor</th>
                <th>Margin (%)</th>
                <th>Kapasitas Stok Saat Ini</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($menuAnalisis as $m)
                <tr>
                    <td><strong>{{ $m['nama_produk'] }}</strong></td>
                    <td>{{ $m['nama_varian'] }}</td>
                    <td>{{ $m['kategori'] }}</td>
                    <td>Rp {{ number_format($m['harga_jual'], 0, ',', '.') }}</td>
                    <td>
                        @if($m['ada_resep'])
                            Rp {{ number_format($m['hpp_bahan'], 0, ',', '.') }}
                        @else
                            <span style="color: #ef4444; font-size: 12px;">Belum ada resep</span>
                        @endif
                    </td>
                    <td>Rp {{ number_format($m['laba_kotor'], 0, ',', '.') }}</td>
                    <td>
                        <strong>{{ $m['margin'] }}%</strong>
                    </td>
                    <td>
                        @if($m['ada_resep'])
                            <span class="badge {{ $m['kapasitas_cup'] > 10 ? 'badge-aman' : 'badge-warning' }}">
                                {{ $m['kapasitas_cup'] }} Cup
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('panel.admin.resep.detail', $m['varian_id']) }}">Detail Resep</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: #64748b;">Belum ada data produk/varian di database.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
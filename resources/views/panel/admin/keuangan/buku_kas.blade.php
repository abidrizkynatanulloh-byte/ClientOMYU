<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Arus Kas - OMYU</title>
    <style>
        body { font-family: sans-serif; padding: 24px; background: #f8fafc; color: #1e293b; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px; }
        .card { background: #fff; padding: 16px; border-radius: 10px; border: 1px solid #e2e8f0; }
        .card h3 { margin: 0 0 6px; font-size: 13px; color: #64748b; }
        .card .angka { font-size: 20px; font-weight: bold; margin: 0; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; margin-top: 16px; border: 1px solid #e2e8f0; }
        th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        th { background: #f1f5f9; }
        .nav a { margin-right: 10px; text-decoration: none; padding: 6px 12px; background: #e2e8f0; color: #334155; border-radius: 6px; font-size: 13px; font-weight: bold; }
        .nav a.active { background: #0f172a; color: white; }
    </style>
</head>
<body>

    <div class="nav" style="margin-bottom: 20px;">
        <a href="{{ route('panel.admin.beranda') }}">📊 Dashboard</a>
        <a href="{{ route('panel.admin.penjualan.index') }}">🛒 Penjualan</a>
        <a href="{{ route('panel.admin.resep.index') }}">🍹 Resep & HPP</a>
        <a href="{{ route('panel.admin.bahan.index') }}">📦 Persediaan</a>
        <a href="{{ route('panel.admin.keuangan.laba_rugi') }}">💰 Laba Rugi (O-05)</a>
        <a href="{{ route('panel.admin.keuangan.buku_kas') }}" class="active">📖 Buku Kas</a>
    </div>

    <h1>Buku Mutasi Arus Kas Harian</h1>

    <div class="grid">
        <div class="card">
            <h3>Total Kas Masuk</h3>
            <p class="angka" style="color: #16a34a;">Rp {{ number_format($totalKasMasuk, 0, ',', '.') }}</p>
        </div>
        <div class="card">
            <h3>Total Kas Keluar</h3>
            <p class="angka" style="color: #dc2626;">Rp {{ number_format($totalKasKeluar, 0, ',', '.') }}</p>
        </div>
        <div class="card">
            <h3>Saldo Kas Bersih</h3>
            <p class="angka" style="color: #0284c7;">Rp {{ number_format($saldoKasBersih, 0, ',', '.') }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Jenis</th>
                <th>Keterangan</th>
                <th>Jumlah (Rp)</th>
                <th>Dicatat Oleh</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mutasiKas as $k)
                <tr>
                    <td>{{ $k->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <strong style="color: {{ $k->jenis == 'masuk' ? '#16a34a' : '#dc2626' }};">
                            {{ strtoupper($k->jenis) }}
                        </strong>
                    </td>
                    <td>{{ $k->keterangan }}</td>
                    <td><strong>Rp {{ number_format($k->jumlah, 0, ',', '.') }}</strong></td>
                    <td>{{ $k->pencatat?->nama ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #64748b;">Belum ada catatan mutasi kas di periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 16px;">
        {{ $mutasiKas->links() }}
    </div>

</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Penjualan & Pesanan - Owner OMYU</title>
    <style>
        body { font-family: sans-serif; padding: 24px; background: #f8fafc; color: #1e293b; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px; }
        .card { background: #fff; padding: 16px; border-radius: 10px; border: 1px solid #e2e8f0; }
        .card h3 { margin: 0 0 6px; font-size: 13px; color: #64748b; }
        .card .angka { font-size: 20px; font-weight: bold; margin: 0; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; margin-top: 16px; border: 1px solid #e2e8f0; }
        th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        th { background: #f1f5f9; }
        .filter-box { background: #fff; padding: 16px; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 20px; }
        .filter-box input, .filter-box select, .filter-box button { padding: 6px 10px; margin-right: 8px; border: 1px solid #cbd5e1; border-radius: 6px; }
        .badge { padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: bold; text-transform: uppercase; }
        .badge-selesai { background: #dcfce7; color: #15803d; }
        .badge-batal { background: #fee2e2; color: #b91c1c; }
        .badge-proses { background: #fef9c3; color: #a16207; }
        .nav a { margin-right: 10px; text-decoration: none; padding: 6px 12px; background: #e2e8f0; color: #334155; border-radius: 6px; font-size: 13px; font-weight: bold; }
        .nav a.active { background: #0f172a; color: white; }
    </style>
</head>
<body>

    <div class="nav" style="margin-bottom: 20px;">
        <a href="{{ route('panel.admin.beranda') }}">📊 Dashboard</a>
        <a href="{{ route('panel.admin.penjualan.index') }}"class="active">🛒 Penjualan</a>
        <a href="{{ route('panel.admin.resep.index') }}" >🍹 Resep & HPP (O-03)</a>
        <a href="{{ route('panel.admin.bahan.index') }}">📦 Persediaan Bahan (O-04)</a>
        <a href="{{ route('panel.admin.keuangan.laba_rugi') }}">
            💰 Keuangan & Laba Rugi (O-05)
        </a>
    </div>

    <h1>Rekap Penjualan & Pesanan (O-02)</h1>

    <!-- Ringkasan Nilai Terfilter -->
    <div class="grid">
        <div class="card">
            <h3>Total Transaksi</h3>
            <p class="angka">{{ $totalTransaksi }}</p>
        </div>
        <div class="card">
            <h3>Omzet Terfilter</h3>
            <p class="angka">Rp {{ number_format($omzetTerfilter, 0, ',', '.') }}</p>
        </div>
        <div class="card">
            <h3>Rata-rata Belanja (AOV)</h3>
            <p class="angka">Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Form Filter -->
    <div class="filter-box">
        <form method="GET" action="{{ route('panel.admin.penjualan.index') }}">
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari Kode / Nama...">
            
            <select name="status">
                <option value="">Semua Status</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="menunggu_pembayaran" {{ request('status') == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Bayar</option>
                <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
            </select>

            <select name="metode_bayar">
                <option value="">Semua Metode</option>
                <option value="qris" {{ request('metode_bayar') == 'qris' ? 'selected' : '' }}>QRIS</option>
                <option value="tunai" {{ request('metode_bayar') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                <option value="transfer" {{ request('metode_bayar') == 'transfer' ? 'selected' : '' }}>Transfer</option>
            </select>

            <select name="jenis">
                <option value="">Semua Jenis</option>
                <option value="online" {{ request('jenis') == 'online' ? 'selected' : '' }}>Online</option>
                <option value="langsung" {{ request('jenis') == 'langsung' ? 'selected' : '' }}>Langsung (Kasir)</option>
            </select>

            <input type="date" name="dari_tanggal" value="{{ request('dari_tanggal') }}">
            <input type="date" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}">

            <button type="submit" style="background: #0284c7; color: white; border: none; cursor: pointer;">Terapkan Filter</button>
            <a href="{{ route('panel.admin.penjualan.index') }}" style="font-size: 13px; margin-left: 8px;">Reset</a>
        </form>
    </div>

    <!-- Tabel Data Transaksi -->
    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Pembeli</th>
                <th>Total</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Kasir/Penangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($daftarPesanan as $p)
                <tr>
                    <td><strong>{{ $p->kode_pesanan }}</strong></td>
                    <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ ucfirst($p->jenis) }}</td>
                    <td>{{ $p->nama_pembeli ?? 'Pelanggan Langsung' }}</td>
                    <td>Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                    <td>{{ strtoupper($p->metode_bayar ?? '-') }}</td>
                    <td>
                        <span class="badge {{ $p->status_pesanan == 'selesai' ? 'badge-selesai' : ($p->status_pesanan == 'dibatalkan' ? 'badge-batal' : 'badge-proses') }}">
                            {{ str_replace('_', ' ', $p->status_pesanan) }}
                        </span>
                    </td>
                    <td>{{ $p->penangan?->nama ?? '-' }}</td>
                    <td>
                        <a href="{{ route('panel.admin.penjualan.detail', $p->id) }}">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; color: #64748b;">Tidak ada data transaksi yang sesuai filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 16px;">
        {{ $daftarPesanan->links() }}
    </div>

</body>
</html>
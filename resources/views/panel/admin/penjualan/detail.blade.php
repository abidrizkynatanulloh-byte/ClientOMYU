<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Transaksi {{ $pesanan->kode_pesanan }}</title>
    <style>
        body { font-family: sans-serif; padding: 24px; background: #f8fafc; color: #1e293b; max-width: 800px; margin: auto; }
        .box { background: #fff; padding: 20px; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        th { background: #f1f5f9; }
    </style>
</head>
<body>

    <p><a href="{{ route('panel.admin.penjualan.index') }}">← Kembali ke Daftar Penjualan</a></p>
    <h1>Detail Pesanan #{{ $pesanan->kode_pesanan }}</h1>

    <div class="box">
        <h3>Informasi Pesanan</h3>
        <p><strong>Waktu Transaksi:</strong> {{ $pesanan->created_at->format('d F Y, H:i:s') }}</p>
        <p><strong>Jenis Penjualan:</strong> {{ ucfirst($pesanan->jenis) }}</p>
        <p><strong>Nama Pembeli:</strong> {{ $pesanan->nama_pembeli ?? '-' }} (Telp: {{ $pesanan->telepon_pembeli ?? '-' }})</p>
        <p><strong>Status Pesanan:</strong> <strong>{{ strtoupper(str_replace('_', ' ', $pesanan->status_pesanan)) }}</strong></p>
        <p><strong>Metode Pembayaran:</strong> {{ strtoupper($pesanan->metode_bayar ?? '-') }} (Status: {{ ucfirst($pesanan->status_pembayaran) }})</p>
        <p><strong>Kasir / Dilayani Oleh:</strong> {{ $pesanan->penangan?->nama ?? 'Sistem' }}</p>
        @if($pesanan->catatan)
            <p><strong>Catatan:</strong> <em>{{ $pesanan->catatan }}</em></p>
        @endif
    </div>

    <div class="box">
        <h3>Item Produk yang Dipesan</h3>
        <table>
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Varian</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesanan->itemPesanan as $item)
                    <tr>
                        <td><strong>{{ $item->nama_produk }}</strong></td>
                        <td>{{ $item->nama_varian ?? 'Reguler' }}</td>
                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td>{{ $item->jumlah }} cup</td>
                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" style="text-align: right;">Total Bayar:</th>
                    <th>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

</body>
</html>
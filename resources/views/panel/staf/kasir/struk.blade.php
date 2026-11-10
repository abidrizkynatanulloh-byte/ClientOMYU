<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Transaksi {{ $pesanan->kode_pesanan }} - OMYU</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; background: #f1f5f9; padding: 20px; display: flex; flex-direction: column; align-items: center; }
        .struk-card { background: #ffffff; width: 320px; padding: 20px; border: 1px dashed #cbd5e1; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .center { text-align: center; }
        .line { border-bottom: 1px dashed #64748b; margin: 10px 0; }
        .item-row { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 4px; }
        .item-sub { display: flex; justify-content: space-between; font-size: 11px; color: #475569; margin-bottom: 6px; }
        .total-row { display: flex; justify-content: space-between; font-weight: bold; font-size: 13px; margin-top: 6px; }
        .btn-group { margin-top: 20px; display: flex; gap: 10px; }
        .btn { padding: 8px 16px; border-radius: 6px; font-size: 13px; font-weight: bold; text-decoration: none; border: none; cursor: pointer; }
        .btn-print { background: #0f172a; color: white; }
        .btn-back { background: #6aa332; color: white; }
        
        @media print {
            body { background: white; padding: 0; }
            .btn-group { display: none; }
            .struk-card { border: none; box-shadow: none; width: 100%; }
        }
    </style>
</head>
<body>

    @php
        $pembayaran = $pesanan->pembayaran->first();
        $raw = $pembayaran ? $pembayaran->data_mentah : [];
        $uangDiterima = $raw['uang_diterima'] ?? $pesanan->total;
        $kembalian = $raw['kembalian'] ?? 0;
    @endphp

    <div class="struk-card">
        <div class="center">
            <h2 style="margin: 0; font-size: 18px; letter-spacing: 1px;">OMYU JUICE</h2>
            <div style="font-size: 11px; color: #475569;">Fresh Juice, Smarter Business</div>
            <div style="font-size: 10px; color: #64748b; margin-top: 4px;">Jl. Raya UMKM Segar No. 88</div>
        </div>

        <div class="line"></div>

        <div style="font-size: 11px;">
            <div>No. Nota: <strong>{{ $pesanan->kode_pesanan }}</strong></div>
            <div>Waktu   : {{ $pesanan->created_at->format('d/m/Y H:i') }}</div>
            <div>Kasir   : {{ $pesanan->penangan ? $pesanan->penangan->nama : 'Kasir OMYU' }}</div>
            <div>Pembeli : {{ $pesanan->nama_pembeli }}</div>
        </div>

        <div class="line"></div>

        <div>
            @foreach($pesanan->itemPesanan as $item)
                <div class="item-row">
                    <span>{{ $item->nama_produk }}</span>
                    <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="item-sub">
                    <span>{{ $item->jumlah }} x Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <div class="line"></div>

        <div class="total-row">
            <span>TOTAL:</span>
            <span>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span>
        </div>
        <div class="item-row" style="margin-top: 4px;">
            <span>Metode:</span>
            <span>{{ strtoupper($pembayaran ? $pembayaran->metode : 'TUNAI') }}</span>
        </div>
        <div class="item-row">
            <span>Diterima:</span>
            <span>Rp {{ number_format($uangDiterima, 0, ',', '.') }}</span>
        </div>
        <div class="item-row" style="font-weight: bold;">
            <span>Kembalian:</span>
            <span>Rp {{ number_format($kembalian, 0, ',', '.') }}</span>
        </div>

        <div class="line"></div>

        <div class="center" style="font-size: 11px; margin-top: 10px;">
            <div>Terima kasih atas kunjungan Anda!</div>
            <div style="font-size: 10px; color: #64748b; margin-top: 4px;">Nikmati kesegaran jus asli tanpa pengawet.</div>
        </div>
    </div>

    <div class="btn-group">
        <button onclick="window.print()" class="btn btn-print">🖨 Cetak Struk</button>
        <a href="{{ route('panel.staf.kasir.index') }}" class="btn btn-back">🛒 Transaksi Baru</a>
    </div>

</body>
</html>


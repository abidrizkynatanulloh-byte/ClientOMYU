@extends('layouts.staf')

@section('title', 'Struk ' . $pesanan->kode_pesanan)

@section('styles')
<style>
    body { background: #f4f8f4; }
    .struk-page { max-width: 420px; margin: 0 auto; }
    .struk-actions { display: flex; gap: 8px; margin-bottom: 12px; }
    .struk-actions a, .struk-actions button { flex: 1; text-align: center; text-decoration: none; border: 0; border-radius: 10px; padding: 10px 12px; font-family: inherit; font-size: 12px; font-weight: 800; cursor: pointer; }
    .btn-kembali { background: #eaf8ef; color: #166534; }
    .btn-cetak { background: #0f5132; color: #ffffff; }
    .struk-card { background: #ffffff; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 20px; font-family: 'Courier New', Courier, monospace; color: #0f172a; box-shadow: 0 4px 12px rgba(15, 23, 42, .05); }
    .struk-center { text-align: center; }
    .struk-card h1 { font-size: 18px; letter-spacing: 1px; margin: 0; }
    .struk-sub { font-size: 10px; color: #64748b; margin-top: 3px; }
    .struk-garis { border-bottom: 1px dashed #94a3b8; margin: 12px 0; }
    .struk-baris { display: flex; justify-content: space-between; gap: 10px; font-size: 11px; padding: 3px 0; }
    .struk-baris span:first-child { color: #475569; }
    .struk-item { margin-bottom: 8px; }
    .struk-item-utama { display: flex; justify-content: space-between; gap: 8px; font-size: 12px; font-weight: 700; }
    .struk-item-detail { font-size: 10px; color: #64748b; margin-top: 2px; }
    .struk-total { display: flex; justify-content: space-between; font-size: 14px; font-weight: 900; border-top: 1px dashed #94a3b8; margin-top: 10px; padding-top: 9px; }
    .struk-catatan { font-size: 11px; color: #64748b; margin-top: 6px; }

    @media print {
        body { background: #ffffff !important; padding: 0 !important; }
        .app-topbar, .bottom-nav, .alert, .struk-actions { display: none !important; }
        .app-container { max-width: none !important; padding: 0 !important; }
        .struk-page { max-width: none; margin: 0; }
        .struk-card { border: 0; border-radius: 0; box-shadow: none; padding: 0; }
    }
</style>
@endsection

@section('content')
@php
    $pembayaran = $pesanan->pembayaran->first();
    $uangDiterima = $pesanan->uang_diterima ?? data_get($pembayaran, 'data_mentah.uang_diterima', $pesanan->total);
    $kembalian = $pesanan->kembalian ?? data_get($pembayaran, 'data_mentah.kembalian', 0);
    $metodeBayar = $pembayaran?->metode ?: $pesanan->metode_bayar;
    $namaKasir = $pesanan->penangan?->nama ?: data_get($pembayaran, 'data_mentah.kasir', 'Kasir OMYU');
    $referensiBayar = data_get($pembayaran, 'data_mentah.referensi_bayar');
    $waktuTransaksi = $pesanan->dibayar_pada ?? $pesanan->created_at;
@endphp

<div class="struk-page">
    <div class="struk-actions">
        <a href="{{ route('panel.staf.kasir.index') }}" class="btn-kembali">&larr; Kembali ke Kasir</a>
        <button type="button" id="cetakStruk" class="btn-cetak">Cetak Struk</button>
    </div>

    <div class="struk-card">
        <div class="struk-center">
            <h1>OMYU JUICE</h1>
            <div class="struk-sub">Less Sugar, More Fruits</div>
            <div class="struk-sub">Struk Penjualan Langsung</div>
        </div>

        <div class="struk-garis"></div>

        <div class="struk-baris"><span>Kode pesanan</span><strong>{{ $pesanan->kode_pesanan }}</strong></div>
        <div class="struk-baris"><span>Waktu</span><span>{{ $waktuTransaksi->format('d/m/Y H:i') }} WIB</span></div>
        <div class="struk-baris"><span>Kasir</span><span>{{ $namaKasir }}</span></div>
        <div class="struk-baris"><span>Pembeli</span><span>{{ $pesanan->nama_pembeli }}</span></div>

        <div class="struk-garis"></div>

        @foreach($pesanan->itemPesanan as $item)
            <div class="struk-item">
                <div class="struk-item-utama">
                    <span>{{ $item->nama_produk }}</span>
                    <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="struk-item-detail">
                    {{ $item->jumlah }} × Rp {{ number_format($item->harga, 0, ',', '.') }}
                    @if($item->nama_varian) · {{ $item->nama_varian }} @endif
                </div>
            </div>
        @endforeach

        <div class="struk-garis"></div>

        <div class="struk-baris"><span>Subtotal</span><span>Rp {{ number_format($pesanan->subtotal, 0, ',', '.') }}</span></div>
        <div class="struk-baris"><span>Metode bayar</span><span>{{ strtoupper($metodeBayar ?? '-') }}</span></div>
        <div class="struk-baris"><span>Uang diterima</span><span>Rp {{ number_format($uangDiterima, 0, ',', '.') }}</span></div>
        <div class="struk-baris"><span>Kembalian</span><span>Rp {{ number_format($kembalian, 0, ',', '.') }}</span></div>
        @if($referensiBayar)
            <div class="struk-baris"><span>Referensi</span><span>{{ $referensiBayar }}</span></div>
        @endif
        <div class="struk-total"><span>TOTAL</span><span>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span></div>

        @if($pesanan->catatan)
            <div class="struk-catatan">Catatan: {{ $pesanan->catatan }}</div>
        @endif

        <div class="struk-garis"></div>

        <div class="struk-center struk-sub">Terima kasih atas kunjungan Anda!</div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('cetakStruk').addEventListener('click', function () {
        window.print();
    });
</script>
@endsection

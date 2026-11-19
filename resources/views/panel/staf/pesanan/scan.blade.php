@extends('layouts.staf')

@section('title', 'Scan QR Pengambilan')

@section('styles')
<style>
    .scan-wrap { display: grid; gap: 16px; }
    .scan-hero { background: linear-gradient(135deg,#0f5132,#198754); color: #fff; border-radius: 18px; padding: 20px; }
    .scan-hero h1 { font-size: 20px; font-weight: 900; }
    .scan-hero p { font-size: 13px; opacity: .85; margin-top: 4px; }
    .scan-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 18px; }
    .scan-card h2 { font-size: 15px; font-weight: 800; color: #153e2b; margin-bottom: 12px; }
    .btn-scan, .btn-confirm { border: 0; border-radius: 11px; padding: 12px 16px; font-weight: 800; cursor: pointer; font-size: 14px; }
    .btn-scan { background: #198754; color: #fff; width: 100%; }
    .btn-confirm { background: #0f5132; color: #fff; width: 100%; margin-top: 12px; }
    .scan-manual { display: flex; gap: 8px; margin-top: 12px; }
    .scan-manual input { flex: 1; min-width: 0; border: 1px solid #d7e4dc; border-radius: 10px; padding: 11px 12px; font-size: 13px; outline: none; }
    .scan-manual button { border: 1px solid #198754; background: #eaf8ef; color: #0f5132; border-radius: 10px; padding: 0 14px; font-weight: 800; cursor: pointer; }
    .scan-result { margin-top: 14px; border-radius: 14px; padding: 14px; font-size: 13px; display: none; }
    .scan-result.show { display: block; }
    .scan-result.valid { background: #eaf8ef; border: 1px solid #bbf7d0; color: #14532d; }
    .scan-result.invalid { background: #fff1f1; border: 1px solid #fecaca; color: #991b1b; }
    .scan-result h3 { font-size: 15px; font-weight: 900; margin-bottom: 8px; }
    .scan-row { display: flex; justify-content: space-between; gap: 12px; padding: 5px 0; border-bottom: 1px dashed rgba(0,0,0,.08); }
    .scan-row:last-child { border-bottom: 0; }
    .scan-list { display: grid; gap: 8px; }
    .scan-list-item { display: flex; justify-content: space-between; gap: 10px; border: 1px solid #e2e8f0; border-radius: 12px; padding: 11px 12px; font-size: 12px; color: #334155; }
    .scan-list-item strong { color: #0f172a; }
    .scan-empty { font-size: 13px; color: #64748b; text-align: center; padding: 18px 0; }
    @media (min-width: 768px) { .scan-wrap { grid-template-columns: 1.2fr 1fr; align-items: start; } }
</style>
@endsection

@section('content')
<div class="scan-wrap">
    <section class="scan-hero">
        <h1>Scan QR Pengambilan</h1>
        <p>Validasi QR pesanan pelanggan sebelum menyerahkan minuman. QR ini berbeda dengan barcode produk di kasir.</p>
    </section>

    <section class="scan-card">
        <h2>Pemindai QR</h2>
        <button type="button" class="btn-scan" id="bukaQr">Buka Scanner QR</button>
        <form class="scan-manual" id="formToken">
            <input type="text" id="tokenManual" placeholder="Atau masukkan token QR manually" autocomplete="off">
            <button type="submit">Validasi</button>
        </form>

        <div class="scan-result" id="hasilScan"></div>
        <button type="button" class="btn-confirm" id="konfirmasiAmb" style="display:none">Konfirmasi Pengambilan</button>
    </section>

    <section class="scan-card">
        <h2>Pesanan Siap Diambil ({{ $siapDiambil->count() }})</h2>
        <div class="scan-list">
            @forelse($siapDiambil as $item)
                <div class="scan-list-item">
                    <div>
                        <strong>#{{ $item->kode_pesanan }}</strong><br>
                        {{ $item->nama_pembeli }}
                    </div>
                    <span>{{ $item->itemPesanan->sum('jumlah') }} cup</span>
                </div>
            @empty
                <div class="scan-empty">Belum ada pesanan siap diambil.</div>
            @endforelse
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
(function () {
    const hasil = document.getElementById('hasilScan');
    const tombolKonfirmasi = document.getElementById('konfirmasiAmb');
    const inputToken = document.getElementById('tokenManual');
    let tokenAktif = '';

    function tampilkanHasil(data) {
        hasil.className = 'scan-result show ' + (data.valid ? 'valid' : 'invalid');

        if (!data.valid) {
            tokenAktif = '';
            tombolKonfirmasi.style.display = 'none';
            hasil.innerHTML = '<h3>QR tidak valid</h3><div>' + data.alasan + '</div>';
            return;
        }

        const p = data.pesanan;
        tokenAktif = inputToken.value.trim();
        const items = p.items.map(function (item) {
            return item.nama + ' × ' + item.jumlah;
        }).join(', ');

        hasil.innerHTML = '<h3># ' + p.kode_pesanan + '</h3>' +
            '<div class="scan-row"><span>Pelanggan</span><strong>' + p.nama_pembeli + '</strong></div>' +
            '<div class="scan-row"><span>Produk</span><strong>' + items + '</strong></div>' +
            '<div class="scan-row"><span>Total</span><strong>Rp ' + Number(p.total).toLocaleString('id-ID') + '</strong></div>' +
            '<div class="scan-row"><span>Pembayaran</span><strong>' + p.status_pembayaran + '</strong></div>' +
            '<div class="scan-row"><span>Status pesanan</span><strong>' + p.status_pesanan + '</strong></div>' +
            '<div class="scan-row"><span>Produksi</span><strong>' + p.status_produksi + '</strong></div>' +
            '<div class="scan-row"><span>Pengambilan</span><strong>' + p.status_pengambilan + '</strong></div>';
        tombolKonfirmasi.style.display = 'block';
    }

    async function validasi(token) {
        inputToken.value = token;
        hasil.className = 'scan-result show';
        hasil.innerHTML = '<h3>Memvalidasi QR...</h3>';

        const response = await fetch('{{ route('panel.staf.pesanan.scan.validasi') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ token: token }),
        });

        const data = await response.json();
        window.scanPesananId = data.valid ? data.pesanan.id : null;
        tampilkanHasil(data);
    }

    document.getElementById('bukaQr').addEventListener('click', function () {
        window.StafScanner.bukaQr({
            onResult: function (token) { validasi(token); },
        });
    });

    document.getElementById('formToken').addEventListener('submit', function (event) {
        event.preventDefault();
        if (inputToken.value.trim()) {
            validasi(inputToken.value.trim());
        }
    });

    tombolKonfirmasi.addEventListener('click', async function () {
        if (!tokenAktif) {
            return;
        }

        tombolKonfirmasi.disabled = true;
        const response = await fetch('{{ route('panel.staf.pesanan.terima-qr', '__ID__') }}'.replace('__ID__', window.scanPesananId), {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ token: tokenAktif }),
        });

        const data = await response.json();

        if (data.valid) {
            hasil.className = 'scan-result show valid';
            hasil.innerHTML = '<h3>Pengambilan berhasil</h3><div>Pesanan ' + data.pesanan.kode_pesanan + ' selesai pada ' + data.pesanan.diselesaikan_pada + '.</div>';
            tombolKonfirmasi.style.display = 'none';
            setTimeout(function () { window.location.reload(); }, 1600);
        } else {
            tampilkanHasil(data);
        }

        tombolKonfirmasi.disabled = false;
    });

    window.scanPesananId = null;
})();
</script>
@endsection

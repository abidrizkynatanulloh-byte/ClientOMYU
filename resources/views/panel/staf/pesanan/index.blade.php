@extends('layouts.staf')

@section('title', 'Pesanan Masuk - Barista & Kasir')

@section('styles')
<style>
    .pesanan-page { display: grid; gap: 14px; width: 100%; max-width: 100%; min-width: 0; }
    .pesanan-page > * { min-width: 0; max-width: 100%; }
    .page-head { display: flex; flex-wrap: wrap; gap: 10px; justify-content: space-between; align-items: flex-start; }
    .page-head h1 { font-size: 21px; font-weight: 900; color: #0f172a; }
    .page-head p { font-size: 12px; color: #64748b; margin-top: 3px; }
    .page-head p strong { color: #15803d; }
    .btn-scan-link { text-decoration: none; background: #0f5132; color: #ffffff; border-radius: 11px; padding: 10px 14px; font-size: 12px; font-weight: 800; }

    .metrics-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; }
    .metric-card { border-radius: 14px; padding: 10px 8px; text-align: center; text-decoration: none; display: flex; flex-direction: column; align-items: center; justify-content: center; }
    .metric-label { font-size: 10px; font-weight: 800; }
    .metric-num { font-size: 25px; font-weight: 900; line-height: 1.1; }
    .metric-sub { font-size: 9px; font-weight: 800; text-transform: uppercase; margin-top: 2px; }
    .m-antrean { background: #e8f7ee; border: 1px solid #bbf7d0; color: #166534; }
    .m-verif { background: #fff1ec; border: 1px solid #fed7aa; color: #9a3412; }
    .m-proses { background: #fefde8; border: 1px solid #fef08a; color: #854d0e; }

    .cari-form { display: flex; flex-wrap: wrap; gap: 8px; }
    .cari-input { flex: 1; min-width: 180px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 22px; padding: 10px 14px; font-size: 13px; font-family: inherit; outline: none; }
    .cari-input:focus { border-color: #198754; }
    .cari-form input[type="date"] { border: 1px solid #cbd5e1; border-radius: 22px; padding: 9px 10px; font-size: 12px; font-family: inherit; }
    .cari-form button { border: 0; border-radius: 22px; background: #198754; color: #ffffff; padding: 0 18px; font-size: 12px; font-weight: 800; cursor: pointer; }

    .filter-row { display: flex; gap: 6px; overflow-x: auto; padding-bottom: 4px; scrollbar-width: none; }
    .filter-row::-webkit-scrollbar { display: none; }
    .filter-chip { flex-shrink: 0; text-decoration: none; white-space: nowrap; border: 1px solid #cbd5e1; background: #ffffff; color: #475569; border-radius: 999px; padding: 6px 12px; font-size: 11px; font-weight: 800; }
    .filter-chip.active { background: #15803d; color: #ffffff; border-color: #15803d; }
    .status-caption { font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: .4px; }

    .order-card { background: #ffffff; border: 1px solid #e2e8f0; border-left-width: 6px; border-radius: 16px; padding: 13px; box-shadow: 0 2px 8px rgba(15, 23, 42, .03); display: grid; gap: 9px; }
    .border-orange { border-left-color: #f97316; }
    .border-green { border-left-color: #16a34a; }
    .border-blue { border-left-color: #3b82f6; }
    .border-red { border-left-color: #dc2626; }
    .border-gray { border-left-color: #94a3b8; }

    .oc-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 8px; }
    .oc-ident { display: grid; gap: 2px; min-width: 0; }
    .oc-code { font-size: 11px; font-weight: 900; color: #15803d; word-break: break-all; }
    .oc-ident strong { font-size: 14px; color: #0f172a; }
    .badge { border-radius: 999px; padding: 4px 9px; font-size: 10px; font-weight: 800; white-space: nowrap; background: #e2e8f0; color: #334155; }
    .badge-lunas, .badge-selesai, .badge-siap_diambil { background: #dcfce7; color: #166534; }
    .badge-menunggu_pembayaran, .badge-menunggu_verifikasi { background: #fef3c7; color: #92400e; }
    .badge-dikonfirmasi, .badge-diproses { background: #dbeafe; color: #1e40af; }
    .badge-dibatalkan, .badge-kedaluwarsa { background: #fee2e2; color: #991b1b; }

    .oc-meta { display: flex; flex-wrap: wrap; gap: 6px; font-size: 10px; color: #64748b; font-weight: 700; }
    .oc-meta span { background: #f8fafc; border-radius: 8px; padding: 3px 7px; }
    .oc-note { background: #fffbeb; border: 1px solid #fef3c7; color: #78350f; border-radius: 9px; padding: 8px 10px; font-size: 11px; }
    .oc-note.error { background: #fef2f2; border-color: #fecaca; color: #991b1b; }

    .oc-items { background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 11px; padding: 9px 11px; }
    .oc-item { display: flex; justify-content: space-between; gap: 10px; font-size: 12px; padding: 4px 0; }
    .oc-item:not(:last-child) { border-bottom: 1px solid #e2e8f0; }
    .oc-item strong { color: #1e293b; display: block; }
    .oc-item small { color: #64748b; }
    .oc-item > span { font-weight: 800; color: #334155; white-space: nowrap; }

    .oc-pay { display: flex; justify-content: space-between; align-items: center; gap: 8px; }
    .badge-method { background: #ffedd5; color: #9a3412; border-radius: 9px; padding: 4px 8px; font-size: 10px; font-weight: 800; }
    .oc-total { font-size: 16px; font-weight: 900; color: #0f172a; }
    .oc-proof { font-size: 11px; font-weight: 800; color: #15803d; text-decoration: underline; }
    .oc-proof-static { font-size: 11px; color: #64748b; }

    .oc-actions, .oc-flow { display: flex; flex-wrap: wrap; gap: 7px; }
    .btn { display: inline-flex; justify-content: center; align-items: center; text-align: center; text-decoration: none; border: 0; border-radius: 10px; padding: 9px 12px; font-family: inherit; font-size: 11px; font-weight: 800; cursor: pointer; flex: 1; }
    .btn-detail { background: #eaf8ef; color: #166534; }
    .btn-scan { background: #4f46e5; color: #ffffff; }
    .btn-muted { background: #f1f5f9; color: #334155; }
    .btn-primary { background: #198754; color: #ffffff; }
    .btn-danger { background: #fee2e2; color: #b91c1c; }
    .btn-warning { background: #fef3c7; color: #92400e; }
    .oc-flow form { flex: 1; display: flex; }
    .oc-flow form .btn { width: 100%; }

    .empty-state { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 36px 16px; text-align: center; }
    .empty-state strong { display: block; font-size: 14px; color: #0f172a; }
    .empty-state span { font-size: 12px; color: #64748b; }
    .paginasi { display: flex; justify-content: center; }

    @media (min-width: 768px) { .pesanan-page { max-width: 860px; margin: 0 auto; } }
</style>
@endsection

@section('content')
@php
    $labelStatus = [
        'menunggu_pembayaran' => 'Menunggu Pembayaran',
        'menunggu_verifikasi' => 'Menunggu Verifikasi',
        'dikonfirmasi' => 'Dikonfirmasi',
        'diproses' => 'Diproses',
        'siap_diambil' => 'Siap Diambil',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
        'kedaluwarsa' => 'Kedaluwarsa',
    ];
    $statusAktif = request('status');
    $tabAktif = request('tab');
@endphp

<div class="pesanan-page">
    <section class="page-head">
        <div>
            <h1>Pesanan Masuk</h1>
            <p>Antrean order masuk {{ $antreanOrderMasuk }} &middot; selesai hari ini <strong>{{ $selesaiHariIniCount }}</strong></p>
        </div>
        <a href="{{ route('panel.staf.pesanan.scan') }}" class="btn-scan-link">Scan QR Pengambilan</a>
    </section>

    <div class="metrics-row">
        <a href="{{ route('panel.staf.pesanan.index', ['tab' => 'semua']) }}" class="metric-card m-antrean">
            <span class="metric-label">Antrean</span>
            <span class="metric-num">{{ $antreanOrderMasuk }}</span>
            <span class="metric-sub">Order masuk</span>
        </a>
        <a href="{{ route('panel.staf.pesanan.index', ['tab' => 'menunggu_cek']) }}" class="metric-card m-verif">
            <span class="metric-label">Verifikasi</span>
            <span class="metric-num">{{ $perluVerifikasiCount }}</span>
            <span class="metric-sub">Perlu cek</span>
        </a>
        <a href="{{ route('panel.staf.pesanan.index', ['tab' => 'diproses']) }}" class="metric-card m-proses">
            <span class="metric-label">Proses</span>
            <span class="metric-num">{{ $prosesDiBlenderCount }}</span>
            <span class="metric-sub">Di blender</span>
        </a>
    </div>

    <form method="GET" action="{{ route('panel.staf.pesanan.index') }}" class="cari-form">
        @if($tabAktif)
            <input type="hidden" name="tab" value="{{ $tabAktif }}">
        @endif
        @if($statusAktif)
            <input type="hidden" name="status" value="{{ $statusAktif }}">
        @endif
        <input type="text" name="cari" value="{{ request('cari') }}" class="cari-input" placeholder="Cari kode, nama, atau telepon pembeli...">
        <input type="date" name="tanggal" value="{{ request('tanggal') }}">
        <button type="submit">Cari</button>
    </form>

    <div class="filter-row">
        <a href="{{ route('panel.staf.pesanan.index') }}" class="filter-chip {{ ! $tabAktif && ! $statusAktif ? 'active' : '' }}">Semua ({{ $semuaCount }})</a>
        <a href="{{ route('panel.staf.pesanan.index', ['tab' => 'menunggu_cek']) }}" class="filter-chip {{ $tabAktif === 'menunggu_cek' ? 'active' : '' }}">Menunggu Cek ({{ $menungguCekCount }})</a>
        <a href="{{ route('panel.staf.pesanan.index', ['tab' => 'sudah_bayar']) }}" class="filter-chip {{ $tabAktif === 'sudah_bayar' ? 'active' : '' }}">Sudah Bayar ({{ $sudahBayarCount }})</a>
        <a href="{{ route('panel.staf.pesanan.index', ['tab' => 'diproses']) }}" class="filter-chip {{ $tabAktif === 'diproses' ? 'active' : '' }}">Di Blender ({{ $prosesDiBlenderCount }})</a>
        <a href="{{ route('panel.staf.pesanan.index', ['tab' => 'siap']) }}" class="filter-chip {{ $tabAktif === 'siap' ? 'active' : '' }}">Siap Ambil ({{ $siapAmbilCount }})</a>
        <a href="{{ route('panel.staf.pesanan.index', ['tab' => 'selesai']) }}" class="filter-chip {{ $tabAktif === 'selesai' ? 'active' : '' }}">Selesai ({{ $selesaiCount }})</a>
    </div>

    <div class="status-caption">Filter status pesanan</div>
    <div class="filter-row">
        @foreach($labelStatus as $nilaiStatus => $namaStatus)
            <a href="{{ route('panel.staf.pesanan.index', ['status' => $nilaiStatus]) }}" class="filter-chip {{ $statusAktif === $nilaiStatus ? 'active' : '' }}">{{ $namaStatus }}</a>
        @endforeach
    </div>

    @forelse($daftarPesanan as $pesanan)
        @php
            $statusPesanan = $pesanan->status_pesanan;
            $pembayaran = $pesanan->pembayaran->first();
            $referensiPembayaran = data_get($pembayaran, 'data_mentah.referensi');
            $borderClass = in_array($statusPesanan, ['menunggu_pembayaran', 'menunggu_verifikasi'], true)
                ? 'border-orange'
                : ($statusPesanan === 'diproses'
                    ? 'border-green'
                    : ($statusPesanan === 'siap_diambil'
                        ? 'border-blue'
                        : (in_array($statusPesanan, ['dibatalkan', 'kedaluwarsa'], true) ? 'border-red' : 'border-gray')));
        @endphp

        <article class="order-card {{ $borderClass }}">
            <div class="oc-head">
                <div class="oc-ident">
                    <span class="oc-code">#{{ $pesanan->kode_pesanan }}</span>
                    <strong>{{ $pesanan->nama_pembeli }}</strong>
                </div>
                <span class="badge badge-{{ $statusPesanan }}">{{ $labelStatus[$statusPesanan] ?? strtoupper(str_replace('_', ' ', $statusPesanan)) }}</span>
            </div>

            <div class="oc-meta">
                <span>Dipesan {{ $pesanan->created_at->format('d/m/Y H:i') }}</span>
                @if($statusPesanan === 'selesai' && $pesanan->diselesaikan_pada)
                    <span>Selesai {{ $pesanan->diselesaikan_pada->format('d/m/Y H:i') }}</span>
                @elseif($pesanan->jadwal_ambil_mulai)
                    <span>Ambil {{ $pesanan->jadwal_ambil_mulai->format('d/m H:i') }}@if($pesanan->jadwal_ambil_selesai) - {{ $pesanan->jadwal_ambil_selesai->format('H:i') }}@endif</span>
                @endif
                @if($statusPesanan === 'menunggu_pembayaran' && $pesanan->kedaluwarsa_pada)
                    <span>Bayar sebelum {{ $pesanan->kedaluwarsa_pada->format('d/m H:i') }}</span>
                @endif
            </div>

            @if($pesanan->catatan)
                <div class="oc-note">Catatan: {{ $pesanan->catatan }}</div>
            @endif
            @if($pesanan->alasan_batal)
                <div class="oc-note error">Alasan pembatalan: {{ $pesanan->alasan_batal }}</div>
            @endif

            <div class="oc-items">
                @foreach($pesanan->itemPesanan as $item)
                    <div class="oc-item">
                        <div>
                            <strong>{{ $item->jumlah }}× {{ $item->nama_produk }}</strong>
                            @if($item->nama_varian)<small>{{ $item->nama_varian }}</small>@endif
                        </div>
                        <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            <div class="oc-pay">
                <span class="badge-method">{{ strtoupper($pesanan->metode_bayar ?? '-') }} &middot; {{ strtoupper($pesanan->status_pembayaran) }}</span>
                <strong class="oc-total">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</strong>
            </div>

            @if($pembayaran && $pembayaran->bukti_path)
                <a class="oc-proof" href="{{ Storage::url($pembayaran->bukti_path) }}" target="_blank" rel="noopener">Lihat bukti pembayaran</a>
            @elseif($referensiPembayaran)
                <div class="oc-proof-static">Referensi pembayaran: {{ $referensiPembayaran }}</div>
            @endif

            <div class="oc-actions">
                <a href="{{ route('panel.staf.pesanan.detail', $pesanan->id) }}" class="btn btn-detail">Detail Pesanan</a>
                @if($statusPesanan === 'siap_diambil')
                    <a href="{{ route('panel.staf.pesanan.scan') }}" class="btn btn-scan">Scan QR Ambil</a>
                @endif
                @if($pesanan->jenis === 'langsung')
                    <a href="{{ route('panel.staf.kasir.struk', $pesanan->id) }}" class="btn btn-muted">Struk Fisik</a>
                @endif
            </div>

            @if(! in_array($statusPesanan, ['selesai', 'dibatalkan', 'kedaluwarsa'], true))
                <div class="oc-flow">
                    @if($statusPesanan === 'menunggu_verifikasi')
                        <form method="POST" action="{{ route('panel.staf.pesanan.batal', $pesanan->id) }}">
                            @csrf
                            <input type="hidden" name="alasan_batal" value="Nominal pembayaran tidak sesuai, mohon verifikasi ulang">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak pembayaran pesanan #{{ $pesanan->kode_pesanan }}?')">Tolak</button>
                        </form>
                        <form method="POST" action="{{ route('panel.staf.pesanan.verifikasi-bayar', $pesanan->id) }}">
                            @csrf
                            <input type="hidden" name="nominal_dibayar" value="{{ $pembayaran?->jumlah ?? $pesanan->total }}">
                            <button type="submit" class="btn btn-primary">Verifikasi Bayar</button>
                        </form>
                    @elseif($statusPesanan === 'dikonfirmasi')
                        <form method="POST" action="{{ route('panel.staf.pesanan.update-status', $pesanan->id) }}">
                            @csrf
                            <input type="hidden" name="status_pesanan" value="diproses">
                            <button type="submit" class="btn btn-warning">Mulai Proses</button>
                        </form>
                    @elseif($statusPesanan === 'diproses')
                        <form method="POST" action="{{ route('panel.staf.pesanan.update-status', $pesanan->id) }}">
                            @csrf
                            <input type="hidden" name="status_pesanan" value="siap_diambil">
                            <button type="submit" class="btn btn-primary">Siap Diambil</button>
                        </form>
                    @elseif($statusPesanan === 'siap_diambil')
                        <form method="POST" action="{{ route('panel.staf.pesanan.update-status', $pesanan->id) }}">
                            @csrf
                            <input type="hidden" name="status_pesanan" value="selesai">
                            <button type="submit" class="btn btn-primary" onclick="return confirm('Serahkan pesanan #{{ $pesanan->kode_pesanan }} dan selesaikan?')">Serahkan Pesanan</button>
                        </form>
                    @endif
                </div>
            @endif
        </article>
    @empty
        <div class="empty-state">
            <strong>Tidak ada pesanan</strong>
            <span>Coba ubah pencarian atau filter status yang dipilih.</span>
        </div>
    @endforelse

    <div class="paginasi">{{ $daftarPesanan->links() }}</div>
</div>
@endsection

@section('scripts')
@endsection

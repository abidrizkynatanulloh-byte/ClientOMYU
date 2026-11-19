@extends('layouts.staf')

@section('title', 'Dashboard Operasional Staf')

@section('styles')
<style>
    .dash-page { display: grid; gap: 16px; }
    .dash-hero {
        background: linear-gradient(135deg,#137333,#0f5132);
        color: #fff;
        border-radius: 18px;
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        box-shadow: 0 8px 20px -12px rgba(15,81,50,.8);
    }
    .dash-kicker { font-size: 10px; font-weight: 900; letter-spacing: 1px; color: #bbf7d0; text-transform: uppercase; }
    .dash-hero h1 { font-size: 19px; font-weight: 900; margin-top: 5px; line-height: 1.25; }
    .dash-hero p { font-size: 12px; opacity: .85; margin-top: 5px; }
    .dash-hero-count { background: rgba(255,255,255,.16); border: 1px solid rgba(255,255,255,.25); border-radius: 14px; padding: 10px 14px; text-align: center; flex-shrink: 0; }
    .dash-hero-count strong { display: block; font-size: 24px; font-weight: 900; line-height: 1; }
    .dash-hero-count span { display: block; font-size: 10px; font-weight: 800; margin-top: 4px; opacity: .85; }
    .dash-head { display: flex; justify-content: space-between; align-items: baseline; gap: 10px; margin-bottom: 9px; }
    .dash-head h2 { font-size: 15px; font-weight: 900; color: #0f172a; }
    .dash-head a, .dash-head span { font-size: 11px; font-weight: 800; color: #15803d; text-decoration: none; }
    .dash-head span { color: #64748b; }
    .stat-grid { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 10px; }
    .stat-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 14px; text-decoration: none; transition: .15s; }
    .stat-card:hover { border-color: #86efac; transform: translateY(-1px); }
    .stat-top { display: flex; justify-content: space-between; align-items: center; gap: 8px; }
    .stat-tag { font-size: 9px; font-weight: 900; letter-spacing: .4px; text-transform: uppercase; padding: 3px 8px; border-radius: 999px; }
    .stat-num { font-size: 27px; font-weight: 900; line-height: 1; margin: 10px 0 3px; color: #0f172a; }
    .stat-label { font-size: 11px; font-weight: 800; color: #475569; }
    .tag-baru { background: #dcfce7; color: #166534; }
    .tag-verif { background: #fef3c7; color: #92400e; }
    .tag-proses { background: #dbeafe; color: #1e40af; }
    .tag-siap { background: #ede9fe; color: #5b21b6; }
    .tag-selesai { background: #e2e8f0; color: #334155; }
    .dash-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 16px; }
    .stok-grid { display: grid; grid-template-columns: repeat(3,minmax(0,1fr)); gap: 8px; }
    .stok-cell { border-radius: 12px; padding: 11px 8px; text-align: center; border: 1px solid #e2e8f0; background: #f8fafc; }
    .stok-cell.aman { background: #f0fdf4; border-color: #bbf7d0; }
    .stok-cell.menipis { background: #fff7ed; border-color: #fed7aa; }
    .stok-cell.habis { background: #fef2f2; border-color: #fecaca; }
    .stok-cell strong { display: block; font-size: 21px; font-weight: 900; line-height: 1; }
    .stok-cell span { display: block; font-size: 10px; font-weight: 800; margin-top: 5px; color: #475569; }
    .stok-cell small { display: block; font-size: 10px; margin-top: 3px; color: #64748b; word-break: break-word; }
    .stok-foot { font-size: 11px; color: #64748b; margin-top: 10px; text-align: center; }
    .quick-grid { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 10px; }
    .quick-link { display: flex; align-items: center; gap: 10px; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 12px; text-decoration: none; color: #0f172a; transition: .15s; }
    .quick-link:hover { border-color: #198754; }
    .quick-icon { width: 34px; height: 34px; border-radius: 10px; background: #e8f5e9; color: #166534; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .quick-icon svg { width: 17px; height: 17px; }
    .quick-link strong { display: block; font-size: 12px; font-weight: 900; }
    .quick-link small { display: block; font-size: 10px; color: #64748b; margin-top: 2px; }
    .queue-list { display: grid; gap: 10px; }
    .queue-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 14px; }
    .queue-top { display: flex; justify-content: space-between; align-items: center; gap: 8px; }
    .queue-code { font-size: 14px; font-weight: 900; color: #0f172a; }
    .badge { padding: 4px 9px; border-radius: 999px; font-size: 9px; font-weight: 900; background: #e2e8f0; color: #334155; text-transform: uppercase; }
    .badge-dikonfirmasi { background: #fef3c7; color: #92400e; }
    .badge-menunggu_verifikasi { background: #ffedd5; color: #9a3412; }
    .badge-diproses { background: #dbeafe; color: #1e40af; }
    .badge-siap_diambil { background: #dcfce7; color: #166534; }
    .queue-meta { font-size: 11px; color: #64748b; margin: 7px 0 10px; }
    .queue-items { display: grid; gap: 5px; }
    .queue-item { display: flex; justify-content: space-between; gap: 8px; background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 9px; padding: 7px 9px; font-size: 11px; }
    .queue-item strong { color: #0f172a; }
    .queue-note { margin-top: 8px; background: #fffbeb; border: 1px solid #fef3c7; color: #78350f; border-radius: 9px; padding: 8px; font-size: 11px; }
    .queue-actions { display: flex; gap: 8px; margin-top: 12px; }
    .btn { border-radius: 10px; padding: 10px 12px; font-size: 12px; font-weight: 900; cursor: pointer; text-align: center; text-decoration: none; border: 0; }
    .btn-primary { background: #198754; color: #fff; flex: 1; }
    .btn-ghost { background: #f1f5f9; color: #334155; }
    .queue-actions form { flex: 1; display: flex; }
    .queue-actions form .btn { width: 100%; }
    .dash-empty { background: #fff; border: 1px dashed #cbd5e1; border-radius: 16px; padding: 30px 16px; text-align: center; color: #64748b; font-size: 13px; }
    @media (min-width: 900px) {
        .desktop-split-layout { grid-template-columns: 1fr 1fr; align-items: start; }
    }
</style>
@endsection

@section('content')
<div class="desktop-split-layout">
    <div class="dash-page">
        <section class="dash-hero">
            <div>
                <div class="dash-kicker">Shift Berjalan</div>
                <h1>{{ $shiftInfo }}</h1>
                <p>{{ auth()->user()->nama }} &middot; Update {{ $waktuUpdate }}</p>
            </div>
            <div class="dash-hero-count">
                <strong>{{ $pesananDiproses }}</strong>
                <span>Sedang Diracik</span>
            </div>
        </section>

        <section>
            <div class="dash-head">
                <h2>Ringkasan Antrean Pesanan</h2>
                <span>{{ $waktuUpdate }}</span>
            </div>
            <div class="stat-grid">
                <a href="{{ route('panel.staf.pesanan.index', ['tab' => 'menunggu_cek']) }}" class="stat-card">
                    <div class="stat-top"><span class="stat-tag tag-baru">Baru</span></div>
                    <div class="stat-num">{{ $pesananBaru }}</div>
                    <div class="stat-label">Menunggu Pembayaran</div>
                </a>
                <a href="{{ route('panel.staf.pesanan.index', ['status' => 'menunggu_verifikasi']) }}" class="stat-card">
                    <div class="stat-top"><span class="stat-tag tag-verif">Verif</span></div>
                    <div class="stat-num">{{ $pesananVerif }}</div>
                    <div class="stat-label">Perlu Verifikasi</div>
                </a>
                <a href="{{ route('panel.staf.pesanan.index', ['tab' => 'diproses']) }}" class="stat-card">
                    <div class="stat-top"><span class="stat-tag tag-proses">Live</span></div>
                    <div class="stat-num">{{ $pesananDiproses }}</div>
                    <div class="stat-label">Sedang Diracik</div>
                </a>
                <a href="{{ route('panel.staf.pesanan.index', ['tab' => 'siap']) }}" class="stat-card">
                    <div class="stat-top"><span class="stat-tag tag-siap">Pick Up</span></div>
                    <div class="stat-num">{{ $pesananSiap }}</div>
                    <div class="stat-label">Siap Diambil</div>
                </a>
                <a href="{{ route('panel.staf.pesanan.index', ['tab' => 'selesai']) }}" class="stat-card">
                    <div class="stat-top"><span class="stat-tag tag-selesai">Hari Ini</span></div>
                    <div class="stat-num">{{ $pesananSelesaiHariIni }}</div>
                    <div class="stat-label">Pesanan Selesai</div>
                </a>
            </div>
        </section>

        <section class="dash-card">
            <div class="dash-head">
                <h2>Bahan Baku Segar & Ketersediaan Produk</h2>
                <a href="{{ route('panel.staf.stok.index') }}">Lihat stok &rarr;</a>
            </div>
            <div class="stok-grid">
                <div class="stok-cell aman">
                    <strong>{{ $produkTersedia }}</strong>
                    <span>Tersedia</span>
                    <small>dari {{ $totalSku }} SKU aktif</small>
                </div>
                <div class="stok-cell menipis">
                    <strong>{{ $produkMenipis }}</strong>
                    <span>Menipis</span>
                    <small>{{ $namaMenipis ?: 'Tidak ada' }}</small>
                </div>
                <div class="stok-cell habis">
                    <strong>{{ $produkHabis }}</strong>
                    <span>Habis</span>
                    <small>{{ $namaHabis ?: 'Tidak ada' }}</small>
                </div>
            </div>
            <div class="stok-foot">Batas stok menipis: {{ $ambangStok }} cup per produk</div>
        </section>

        <section>
            <div class="dash-head"><h2>Aksi Cepat Barista</h2></div>
            <div class="quick-grid">
                <a href="{{ route('panel.staf.produksi.index') }}" class="quick-link">
                    <span class="quick-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 2h10l-2 11a4 4 0 0 1-8 0L5 2z"></path><path d="M10 16v4"></path><path d="M7 20h10"></path></svg></span>
                    <span><strong>Produksi</strong><small>Antrean racik jus</small></span>
                </a>
                <a href="{{ route('panel.staf.pesanan.scan') }}" class="quick-link">
                    <span class="quick-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect><rect x="3" y="14" width="7" height="7" rx="1"></rect><path d="M14 14h3v3h-3zM19 19h2v2h-2z"></path></svg></span>
                    <span><strong>Scan QR</strong><small>Validasi pengambilan</small></span>
                </a>
                <a href="{{ route('panel.staf.kasir.index') }}" class="quick-link">
                    <span class="quick-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="M6 8h12M6 12h4v4H6zM14 12h4M14 16h4"></path></svg></span>
                    <span><strong>Kasir POS</strong><small>Menerima Pesanan & transaksi langsung</small></span>
                </a>
                <a href="{{ route('panel.staf.stok.index') }}" class="quick-link">
                    <span class="quick-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8l-9-5-9 5v8l9 5 9-5z"></path><path d="M3.3 7L12 12l8.7-5M12 22V12"></path></svg></span>
                    <span><strong>Stok & Bahan</strong><small>Pasokan dan audit</small></span>
                </a>
                <a href="{{ route('panel.staf.aktivitas.index') }}" class="quick-link">
                    <span class="quick-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-3"></path><path d="M9 3h6v4H9zM8 12h8M8 16h8"></path></svg></span>
                    <span><strong>Aktivitas</strong><small>Riwayat audit staf</small></span>
                </a>
                <a href="{{ route('panel.staf.profil.index') }}" class="quick-link">
                    <span class="quick-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg></span>
                    <span><strong>Profil</strong><small>Akun dan kata sandi</small></span>
                </a>
            </div>
        </section>
    </div>

    <div class="dash-page">
        <section>
            <div class="dash-head">
                <h2>Antrean Prioritas Cepat</h2>
                <a href="{{ route('panel.staf.pesanan.index') }}">Semua pesanan &rarr;</a>
            </div>
            <div class="queue-list">
                @forelse($antreanPesanan as $pesanan)
                    <article class="queue-card">
                        <div class="queue-top">
                            <span class="queue-code">#{{ $pesanan->kode_pesanan }}</span>
                            <span class="badge badge-{{ $pesanan->status_pesanan }}">{{ str_replace('_', ' ', $pesanan->status_pesanan) }}</span>
                        </div>
                        <div class="queue-meta">{{ $pesanan->nama_pembeli }} &middot; {{ $pesanan->created_at->format('H:i') }} ({{ $pesanan->created_at->diffForHumans() }})</div>
                        <div class="queue-items">
                            @foreach($pesanan->itemPesanan as $item)
                                <div class="queue-item">
                                    <strong>{{ $item->jumlah }}x {{ $item->nama_produk }}</strong>
                                    @if($item->nama_varian)<span>{{ $item->nama_varian }}</span>@endif
                                </div>
                            @endforeach
                        </div>
                        @if($pesanan->catatan)
                            <div class="queue-note">Catatan: {{ $pesanan->catatan }}</div>
                        @endif
                        <div class="queue-actions">
                            @if($pesanan->status_pesanan === 'menunggu_verifikasi')
                                <form method="POST" action="{{ route('panel.staf.pesanan.update-status', $pesanan->id) }}">
                                    @csrf
                                    <input type="hidden" name="status_pesanan" value="dikonfirmasi">
                                    <button type="submit" class="btn btn-primary">Konfirmasi</button>
                                </form>
                            @elseif($pesanan->status_pesanan === 'dikonfirmasi')
                                <form method="POST" action="{{ route('panel.staf.pesanan.update-status', $pesanan->id) }}">
                                    @csrf
                                    <input type="hidden" name="status_pesanan" value="diproses">
                                    <button type="submit" class="btn btn-primary">Mulai Racik</button>
                                </form>
                            @elseif($pesanan->status_pesanan === 'diproses')
                                <form method="POST" action="{{ route('panel.staf.pesanan.update-status', $pesanan->id) }}">
                                    @csrf
                                    <input type="hidden" name="status_pesanan" value="siap_diambil">
                                    <button type="submit" class="btn btn-primary">Siap Diambil</button>
                                </form>
                            @elseif($pesanan->status_pesanan === 'siap_diambil')
                                <form method="POST" action="{{ route('panel.staf.pesanan.update-status', $pesanan->id) }}">
                                    @csrf
                                    <input type="hidden" name="status_pesanan" value="selesai">
                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Serahkan pesanan ini ke pembeli?')">Serahkan</button>
                                </form>
                            @endif
                            <a href="{{ route('panel.staf.pesanan.detail', $pesanan->id) }}" class="btn btn-ghost">Detail</a>
                        </div>
                    </article>
                @empty
                    <div class="dash-empty">Tidak ada antrean pesanan aktif saat ini.</div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection

@extends('layouts.staf')

@section('title', 'Manajemen Stok & Bahan')

@section('styles')
<style>
    [hidden] { display: none !important; }
    .stok-page { display: grid; gap: 14px; width: 100%; max-width: 100%; min-width: 0; }
    .stok-page > * { min-width: 0; max-width: 100%; }
    .stok-hero { background: linear-gradient(135deg,#137333,#0f5132); color: #fff; border-radius: 18px; padding: 18px; min-width: 0; }
    .stok-hero h1 { font-size: 19px; font-weight: 900; word-break: break-word; }
    .stok-hero p { font-size: 12px; opacity: .85; margin-top: 4px; word-break: break-word; }
    .metric-grid { display: grid; grid-template-columns: repeat(3,minmax(0,1fr)); gap: 8px; width: 100%; }
    .metric { display: flex; flex-direction: column; align-items: center; justify-content: center; text-decoration: none; text-align: center; border-radius: 14px; padding: 10px 4px; border: 1px solid #e2e8f0; background: #fff; min-width: 0; }
    .metric strong { display: block; font-size: 20px; font-weight: 900; line-height: 1; color: #0f172a; }
    .metric span { display: block; font-size: 10px; font-weight: 800; margin-top: 5px; color: #475569; word-break: break-word; line-height: 1.25; }
    .metric.total { background: #f0fdf4; border-color: #bbf7d0; }
    .metric.kritis { background: #fff7ed; border-color: #fed7aa; }
    .metric.habis { background: #fef2f2; border-color: #fecaca; }
    .aksi-grid { display: grid; grid-template-columns: 1fr; gap: 9px; width: 100%; }
    .btn { display: block; width: 100%; border: 0; border-radius: 12px; padding: 12px 14px; font-size: 13px; font-weight: 900; cursor: pointer; text-align: center; text-decoration: none; font-family: inherit; }
    .btn-hijau { background: #198754; color: #fff; }
    .btn-merah { background: #fff1f2; color: #9f1239; border: 1px solid #fecdd3; }
    .btn-abu { background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; }
    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 15px; width: 100%; max-width: 100%; min-width: 0; box-sizing: border-box; overflow: hidden; }
    .card-head { display: flex; justify-content: space-between; align-items: baseline; flex-wrap: wrap; gap: 6px 10px; margin-bottom: 12px; }
    .card-head h2 { font-size: 15px; font-weight: 900; color: #0f172a; }
    .card-head span { font-size: 11px; font-weight: 800; color: #64748b; }
    .search-form { display: flex; gap: 8px; margin-bottom: 10px; width: 100%; }
    .search-form input { flex: 1; min-width: 0; border: 1px solid #d7e4dc; border-radius: 10px; padding: 10px 12px; font-size: 13px; outline: none; }
    .search-form input:focus { border-color: #198754; box-shadow: 0 0 0 3px rgba(25,135,84,.12); }
    .search-form button { border: 0; border-radius: 10px; padding: 0 15px; background: #198754; color: #fff; font-weight: 900; font-size: 12px; cursor: pointer; flex-shrink: 0; }
    .filter-pills { display: flex; gap: 7px; overflow-x: auto; padding-bottom: 4px; margin-bottom: 12px; width: 100%; -webkit-overflow-scrolling: touch; scrollbar-width: none; }
    .filter-pills::-webkit-scrollbar { display: none; }
    .filter-pills a { flex-shrink: 0; white-space: nowrap; text-decoration: none; font-size: 11px; font-weight: 900; padding: 7px 12px; border-radius: 999px; border: 1px solid #cbd5e1; color: #475569; background: #fff; }
    .filter-pills a.active { background: #137333; border-color: #137333; color: #fff; }
    .bahan-grid { display: grid; grid-template-columns: 1fr; gap: 10px; width: 100%; min-width: 0; }
    .bahan-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 15px; padding: 13px; border-left: 4px solid #22c55e; width: 100%; box-sizing: border-box; min-width: 0; }
    .bahan-card.kritis { border-left-color: #f97316; }
    .bahan-card.habis { border-left-color: #e11d48; }
    .bahan-top { display: flex; justify-content: space-between; align-items: center; gap: 8px; }
    .bahan-top strong { font-size: 14px; font-weight: 900; color: #0f172a; word-break: break-word; }
    .status-pill { font-size: 9px; font-weight: 900; padding: 4px 9px; border-radius: 999px; text-transform: uppercase; background: #dcfce7; color: #166534; flex-shrink: 0; }
    .status-pill.kritis { background: #ffedd5; color: #9a3412; }
    .status-pill.habis { background: #fee2e2; color: #991b1b; }
    .stok-utama { font-size: 22px; font-weight: 900; color: #0f172a; margin: 10px 0 2px; }
    .stok-utama small { font-size: 11px; font-weight: 800; color: #64748b; }
    .stok-rinci { display: flex; flex-wrap: wrap; gap: 6px 12px; font-size: 11px; color: #64748b; }
    .stok-rinci b { color: #334155; }
    .konversi { font-size: 10px; color: #475569; margin-top: 7px; background: #f8fafc; border-radius: 8px; padding: 6px 8px; word-break: break-word; }
    .bahan-actions { display: flex; flex-wrap: wrap; align-items: center; gap: 7px; margin-top: 12px; }
    .btn-mini { border: 1px solid #cbd5e1; background: #fff; color: #334155; border-radius: 9px; padding: 7px 10px; font-size: 11px; font-weight: 900; cursor: pointer; font-family: inherit; }
    .btn-mini.merah { border-color: #fecdd3; color: #9f1239; background: #fff1f2; }
    .toggle { position: relative; display: inline-block; width: 38px; height: 22px; }
    .toggle input { opacity: 0; width: 0; height: 0; }
    .toggle span { position: absolute; inset: 0; background: #cbd5e1; border-radius: 999px; transition: .2s; cursor: pointer; }
    .toggle span:before { content: ""; position: absolute; width: 16px; height: 16px; left: 3px; bottom: 3px; background: #fff; border-radius: 50%; transition: .2s; }
    .toggle input:checked + span { background: #15803d; }
    .toggle input:checked + span:before { transform: translateX(16px); }
    .toggle-label { font-size: 10px; font-weight: 800; color: #64748b; }
    .katalog-row { border-bottom: 1px solid #f1f5f9; padding: 10px 0; }
    .katalog-row:last-child { border-bottom: 0; }
    .katalog-row summary { cursor: pointer; display: flex; justify-content: space-between; gap: 10px; font-size: 12px; font-weight: 800; color: #0f172a; list-style: none; }
    .katalog-row summary::-webkit-details-marker { display: none; }
    .katalog-row summary span:last-child { color: #15803d; white-space: nowrap; }
    .katalog-detail { margin-top: 10px; background: #f8fafc; border-radius: 11px; padding: 11px; display: grid; gap: 8px; font-size: 11px; color: #475569; }
    .qr-mini { background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 8px; width: fit-content; }
    .katalog-detail .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .katalog-detail label { display: block; font-size: 10px; font-weight: 900; margin-bottom: 4px; color: #334155; }
    .katalog-detail input, .katalog-detail select { width: 100%; border: 1px solid #cbd5e1; border-radius: 8px; padding: 8px; font-size: 12px; font-family: inherit; }
    .tabel-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%; max-width: 100%; scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
    table { width: 100%; border-collapse: collapse; font-size: 11px; min-width: 480px; }
    th { text-align: left; color: #64748b; font-weight: 800; padding: 7px 6px; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
    td { padding: 9px 6px; border-bottom: 1px solid #f1f5f9; color: #334155; }
    td.kanan { text-align: right; }
    .log-item { padding: 9px 0; border-bottom: 1px dashed #f1f5f9; font-size: 11px; }
    .log-item:last-child { border-bottom: 0; }
    .log-item strong { display: block; color: #0f172a; font-size: 12px; }
    .log-item span { color: #94a3b8; }
    .empty { text-align: center; color: #64748b; font-size: 12px; padding: 20px 8px; }
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.6); z-index: 100; align-items: center; justify-content: center; padding: 16px; }
    .modal-overlay.active { display: flex; }
    .modal-card { background: #fff; border-radius: 16px; width: 100%; max-width: 440px; max-height: 90vh; overflow-y: auto; padding: 18px; box-shadow: 0 20px 40px -20px rgba(15,23,42,.6); }
    .modal-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
    .modal-head h2 { font-size: 16px; font-weight: 900; color: #0f172a; }
    .modal-head button { background: none; border: 0; font-size: 22px; cursor: pointer; color: #64748b; }
    .form-label { display: block; font-size: 11px; font-weight: 900; color: #334155; margin: 11px 0 5px; }
    .form-input, .form-select { width: 100%; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 11px; font-size: 13px; outline: none; background: #fff; font-family: inherit; }
    .form-input:focus, .form-select:focus { border-color: #198754; box-shadow: 0 0 0 3px rgba(25,135,84,.12); }
    .form-dua { display: grid; grid-template-columns: 2fr 1fr; gap: 9px; }
    .modal-actions { display: flex; gap: 8px; margin-top: 16px; }
    .modal-actions .btn { flex: 1; }
    @media (min-width: 768px) {
        .bahan-grid { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .aksi-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (min-width: 1100px) { .bahan-grid { grid-template-columns: repeat(3,minmax(0,1fr)); } }
</style>
@endsection

@section('content')
@php
    $satuanOptions = ['kg', 'g', 'gr', 'pcs', 'ml', 'liter'];
@endphp
<div class="stok-page">
    <section class="stok-hero">
        <h1>Manajemen Stok & Bahan</h1>
        <p>{{ $totalItem }} item tercatat &middot; {{ $stokKritisCount }} kritis &middot; {{ $habisCount }} habis</p>
        <p>Audit Rutin: Pukul 14:00 WIB</p>
    </section>

    <div class="metric-grid">
        <a href="{{ route('panel.staf.stok.index', ['tab' => 'semua']) }}" class="metric total">
            <strong>{{ $totalItem }}</strong><span>Semua Item</span>
        </a>
        <a href="{{ route('panel.staf.stok.index', ['tab' => 'kritis']) }}" class="metric kritis">
            <strong>{{ $stokKritisCount }}</strong><span>Stok Menipis / Kritis</span>
        </a>
        <a href="{{ route('panel.staf.stok.index', ['tab' => 'habis']) }}" class="metric habis">
            <strong>{{ $habisCount }}</strong><span>Habis</span>
        </a>
    </div>

    <div class="aksi-grid">
        <button type="button" class="btn btn-hijau" id="bukaPasokan">Input Pasokan / Masuk</button>
        <button type="button" class="btn btn-merah" id="bukaPembuangan">Catat Pembuangan (Rusak/Busuk)</button>
    </div>

    <section class="card">
        <div class="card-head">
            <h2>Bahan Baku Segar</h2>
            <span>Stok tersedia = fisik dikurangi cadangan</span>
        </div>

        <form method="GET" action="{{ route('panel.staf.stok.index') }}" class="search-form">
            @if($statusFilter !== 'semua')
                <input type="hidden" name="tab" value="{{ $statusFilter }}">
            @endif
            <input type="search" name="cari" value="{{ $cari }}" placeholder="Cari nama bahan..." aria-label="Cari bahan">
            <button type="submit">Cari</button>
        </form>

        <div class="filter-pills">
            <a href="{{ route('panel.staf.stok.index', ['tab' => 'semua', 'cari' => $cari]) }}" class="{{ $statusFilter === 'semua' ? 'active' : '' }}">Semua ({{ $totalItem }})</a>
            <a href="{{ route('panel.staf.stok.index', ['tab' => 'kritis', 'cari' => $cari]) }}" class="{{ $statusFilter === 'kritis' ? 'active' : '' }}">Kritis ({{ $stokKritisCount }})</a>
            <a href="{{ route('panel.staf.stok.index', ['tab' => 'habis', 'cari' => $cari]) }}" class="{{ $statusFilter === 'habis' ? 'active' : '' }}">Habis ({{ $habisCount }})</a>
        </div>

        <div class="bahan-grid">
            @forelse($daftarBahan as $bahan)
                @php
                    $satuan = $bahan->satuan_dasar;
                    $tersedia = (float) $bahan->stok_tersedia;
                    $kelas = $tersedia <= 0 ? 'habis' : ($tersedia <= (float) $bahan->batas_minimum ? 'kritis' : 'aman');
                @endphp
                <article class="bahan-card {{ $kelas }}">
                    <div class="bahan-top">
                        <strong>{{ $bahan->nama }}</strong>
                        <span class="status-pill {{ $kelas }}">{{ $kelas === 'habis' ? 'Habis' : ($kelas === 'kritis' ? 'Kritis' : 'Aman') }}</span>
                    </div>
                    <div class="stok-utama">{{ number_format($tersedia, 2, ',', '.') }} <small>{{ $satuan }}</small></div>
                    <div class="stok-rinci">
                        <span>Fisik: <b>{{ number_format((float) $bahan->stok, 2, ',', '.') }} {{ $satuan }}</b></span>
                        <span>Cadangan: <b>{{ number_format((float) $bahan->stok_dicadangkan, 2, ',', '.') }} {{ $satuan }}</b></span>
                        <span>Minimum: <b>{{ number_format((float) $bahan->batas_minimum, 2, ',', '.') }} {{ $satuan }}</b></span>
                    </div>
                    @if($bahan->satuan_beli && strtolower((string) $bahan->satuan_beli) !== strtolower((string) $satuan))
                        <div class="konversi">Konversi beli: 1 {{ $bahan->satuan_beli }} = {{ number_format((float) $bahan->konversi, 3, ',', '.') }} {{ $satuan }}</div>
                    @endif
                    <div class="bahan-actions">
                        <form method="POST" action="{{ route('panel.staf.stok.toggle-aktif', $bahan->id) }}">
                            @csrf
                            <button type="submit" class="btn-mini"
                                    data-confirm="Apakah Anda yakin ingin {{ $bahan->aktif ? 'menutup' : 'mengaktifkan' }} bahan {{ $bahan->nama }}?"
                                    data-confirm-title="{{ $bahan->aktif ? 'Tutup' : 'Aktifkan' }} Bahan?"
                                    data-confirm-btn="{{ $bahan->aktif ? 'Ya, Tutup' : 'Ya, Aktifkan' }}"
                                    data-confirm-type="{{ $bahan->aktif ? 'danger' : 'success' }}">{{ $bahan->aktif ? 'Tutup' : 'Aktifkan' }}</button>
                        </form>
                        <span class="toggle-label">{{ $bahan->aktif ? 'Tersedia' : 'Ditutup' }}</span>
                        <button type="button" class="btn-mini" data-pasokan="{{ $bahan->id }}" data-nama="{{ $bahan->nama }}" data-satuan="{{ $bahan->satuan_beli ?: $satuan }}">Pasokan</button>
                        <button type="button" class="btn-mini" data-buang="{{ $bahan->id }}" data-nama="{{ $bahan->nama }}" data-satuan="{{ $satuan }}">Buang</button>
                        <button type="button" class="btn-mini" data-audit="{{ $bahan->id }}" data-nama="{{ $bahan->nama }}" data-stok="{{ (float) $bahan->stok }}" data-satuan="{{ $satuan }}">Audit</button>
                    </div>
                </article>
            @empty
                <div class="empty">Tidak ada bahan yang cocok dengan filter ini.</div>
            @endforelse
        </div>
    </section>

    <section class="card">
        <div class="card-head">
            <h2>Katalog Resep & Stok Cup</h2>
            <span>{{ $katalogProduk->count() }} produk ditampilkan</span>
        </div>
        @forelse($katalogProduk as $produk)
            <details class="katalog-row">
                <summary>
                    <span>{{ $produk->nama }}</span>
                    <span>{{ $produk->stok }} cup</span>
                </summary>
                <div class="katalog-detail">
                    <div>Kategori: <strong>{{ $produk->kategori?->nama ?? '-' }}</strong></div>
                    <div>Barcode: <strong>{{ $produk->barcode ?: '-' }}</strong></div>
                    @if($produk->barcode)
                        <div class="qr-mini" data-qr-value="{{ $produk->barcode }}"></div>
                    @endif
                    <form method="POST" action="{{ route('panel.staf.stok.penyesuaian', $produk->id) }}">
                        @csrf
                        <div class="form-row">
                            <div>
                                <label for="tipe-{{ $produk->id }}">Tipe Penyesuaian</label>
                                <select id="tipe-{{ $produk->id }}" name="tipe_aksi">
                                    <option value="tambah">Tambah stok</option>
                                    <option value="kurang">Kurangi stok</option>
                                    <option value="atur">Atur ke jumlah</option>
                                </select>
                            </div>
                            <div>
                                <label for="jumlah-{{ $produk->id }}">Jumlah (cup)</label>
                                <input type="number" id="jumlah-{{ $produk->id }}" name="jumlah" min="1" step="1" value="{{ max(1, (int) $produk->stok) }}" required>
                            </div>
                        </div>
                        <label for="alasan-{{ $produk->id }}">Alasan</label>
                        <input type="text" id="alasan-{{ $produk->id }}" name="alasan" minlength="3" maxlength="255" placeholder="Contoh: stok opname cup" required>
                        <button type="submit" class="btn btn-hijau" style="margin-top:9px;"
                                data-confirm="Simpan penyesuaian stok {{ $produk->nama }} sesuai tipe dan jumlah yang diisi?"
                                data-confirm-title="Simpan Penyesuaian Stok?"
                                data-confirm-btn="Ya, Simpan"
                                data-confirm-type="warning">Simpan Penyesuaian</button>
                    </form>
                </div>
            </details>
        @empty
            <div class="empty">Tidak ada produk yang cocok dengan pencarian.</div>
        @endforelse
    </section>

    <section class="card">
        <div class="card-head">
            <h2>Pergerakan Stok Bahan</h2>
            <span>{{ $pergerakanBahan->count() }} catatan terbaru</span>
        </div>
        <div class="tabel-wrap">
            <table>
                <thead>
                    <tr><th>Bahan</th><th>Jenis: Penggunaan / Kerusakan Bahan</th><th class="kanan">Jumlah</th><th class="kanan">Stok Sesudah</th><th>Catatan</th><th>Waktu</th></tr>
                </thead>
                <tbody>
                    @forelse($pergerakanBahan as $gerakan)
                        <tr>
                            <td><strong>{{ $gerakan->nama_bahan }}</strong></td>
                            <td>{{ str_replace('_', ' ', $gerakan->jenis) }}</td>
                            <td class="kanan">{{ number_format((float) $gerakan->jumlah, 2, ',', '.') }} {{ $gerakan->satuan_dasar }}</td>
                            <td class="kanan">{{ number_format((float) $gerakan->stok_setelah, 2, ',', '.') }} {{ $gerakan->satuan_dasar }}</td>
                            <td>{{ $gerakan->alasan ?: '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($gerakan->created_at)->format('d M H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty">Belum ada pergerakan stok bahan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="card">
        <div class="card-head">
            <h2>Riwayat Stok Produk</h2>
            <span>{{ $riwayatStok->count() }} catatan terbaru</span>
        </div>
        <div class="tabel-wrap">
            <table>
                <thead>
                    <tr><th>Produk</th><th>Jenis</th><th class="kanan">Jumlah</th><th class="kanan">Stok Sesudah</th><th>Catatan</th><th>Oleh</th><th>Waktu</th></tr>
                </thead>
                <tbody>
                    @forelse($riwayatStok as $riwayat)
                        <tr>
                            <td><strong>{{ $riwayat->produk?->nama ?? '-' }}</strong></td>
                            <td>{{ str_replace('_', ' ', $riwayat->jenis) }}</td>
                            <td class="kanan">{{ $riwayat->jumlah > 0 ? '+' : '' }}{{ number_format((float) $riwayat->jumlah, 0, ',', '.') }} cup</td>
                            <td class="kanan">{{ number_format((float) $riwayat->stok_setelah, 0, ',', '.') }} cup</td>
                            <td>{{ $riwayat->catatan ?: '-' }}</td>
                            <td>{{ $riwayat->pengguna?->nama ?? '-' }}</td>
                            <td>{{ $riwayat->created_at?->format('d M H:i') ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty">Belum ada riwayat stok produk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section class="card">
        <div class="card-head">
            <h2>Log Aktivitas Stok</h2>
            <span>{{ $logAktivitasStok->count() }} catatan terbaru</span>
        </div>
        @forelse($logAktivitasStok as $log)
            <div class="log-item">
                <strong>{{ ucfirst(str_replace('_', ' ', $log->aksi)) }}</strong>
                <div>{{ $log->deskripsi }}</div>
                <span>{{ $log->pengguna?->nama ?? 'Sistem' }} &middot; {{ $log->created_at?->format('d M H:i') ?? '-' }}</span>
            </div>
        @empty
            <div class="empty">Belum ada aktivitas stok tercatat.</div>
        @endforelse
    </section>
</div>

<div class="modal-overlay" id="modalPasokan">
    <div class="modal-card">
        <div class="modal-head">
            <h2>Input Pasokan Masuk</h2>
            <button type="button" data-tutup="modalPasokan" aria-label="Tutup">&times;</button>
        </div>
        <form method="POST" action="{{ route('panel.staf.stok.pasokan') }}">
            @csrf
            <label class="form-label" for="pasokanBahan">Bahan Baku</label>
            <select class="form-select" id="pasokanBahan" name="bahan_id" required>
                <option value="">Pilih bahan</option>
                @foreach($semuaBahanList as $bahan)
                    <option value="{{ $bahan->id }}" data-satuan="{{ $bahan->satuan_beli ?: $bahan->satuan_dasar }}">{{ $bahan->nama }} ({{ number_format($bahan->stok_tersedia, 2, ',', '.') }} {{ $bahan->satuan_dasar }})</option>
                @endforeach
            </select>
            <div class="form-dua">
                <div>
                    <label class="form-label" for="jumlahPasokan">Jumlah Masuk</label>
                    <input class="form-input" type="number" id="jumlahPasokan" name="jumlah_masuk" min="0.001" step="0.001" required>
                </div>
                <div>
                    <label class="form-label" for="satuanPasokan">Satuan</label>
                    <select class="form-select" id="satuanPasokan" name="satuan_input" required>
                        @foreach($satuanOptions as $satuan)
                            <option value="{{ $satuan }}">{{ $satuan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <label class="form-label" for="totalHargaPasokan">Uang Keluar (Rp)</label>
            <input class="form-input" type="number" id="totalHargaPasokan" name="total_harga" min="0" step="1" placeholder="Isi harga beli sebenarnya">
            <p class="text-xs text-slate-500 mt-1">Isi total tagihan pembelian sebenarnya. Harga rata-rata bahan dan HPP per cup akan menyesuaikan otomatis, dan nominalnya muncul di Buku Mutasi Kas. Kosongkan untuk memakai harga rata-rata saat ini.</p>
            <label class="form-label" for="catatanPasokan">Catatan / Supplier</label>
            <input class="form-input" type="text" id="catatanPasokan" name="catatan" maxlength="255" placeholder="Contoh: pasokan supplier hari ini">
            <div class="modal-actions">
                <button type="button" class="btn btn-abu" data-tutup="modalPasokan">Batal</button>
                <button type="submit" class="btn btn-hijau"
                        data-confirm="Simpan pasokan masuk ini ke gudang dan catat uang keluar kas?"
                        data-confirm-title="Simpan Pasokan?"
                        data-confirm-btn="Ya, Simpan Pasokan"
                        data-confirm-type="success">Simpan Pasokan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="modalPembuangan">
    <div class="modal-card">
        <div class="modal-head">
            <h2>Catat Pembuangan Bahan</h2>
            <button type="button" data-tutup="modalPembuangan" aria-label="Tutup">&times;</button>
        </div>
        <form method="POST" action="{{ route('panel.staf.stok.pembuangan') }}">
            @csrf
            <label class="form-label" for="buangBahan">Bahan Rusak / Busuk</label>
            <select class="form-select" id="buangBahan" name="bahan_id" required>
                <option value="">Pilih bahan</option>
                @foreach($semuaBahanList as $bahan)
                    <option value="{{ $bahan->id }}" data-satuan="{{ $bahan->satuan_dasar }}">{{ $bahan->nama }} (tersedia {{ number_format($bahan->stok_tersedia, 2, ',', '.') }} {{ $bahan->satuan_dasar }})</option>
                @endforeach
            </select>
            <div class="form-dua">
                <div>
                    <label class="form-label" for="jumlahBuang">Jumlah Dibuang</label>
                    <input class="form-input" type="number" id="jumlahBuang" name="jumlah_buang" min="0.001" step="0.001" required>
                </div>
                <div>
                    <label class="form-label" for="satuanBuang">Satuan</label>
                    <select class="form-select" id="satuanBuang" name="satuan_input" required>
                        @foreach($satuanOptions as $satuan)
                            <option value="{{ $satuan }}">{{ $satuan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <label class="form-label" for="alasanBuang">Alasan Pembuangan</label>
            <input class="form-input" type="text" id="alasanBuang" name="alasan" minlength="3" maxlength="255" placeholder="Contoh: buah berjamur saat sortiran" required>
            <div class="modal-actions">
                <button type="button" class="btn btn-abu" data-tutup="modalPembuangan">Batal</button>
                <button type="submit" class="btn btn-merah"
                        data-confirm="Catat pembuangan bahan ini dan kurangi stok gudang?"
                        data-confirm-title="Catat Pembuangan?"
                        data-confirm-btn="Ya, Catat"
                        data-confirm-type="danger">Catat Pembuangan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal-overlay" id="modalAudit">
    <div class="modal-card">
        <div class="modal-head">
            <h2>Audit Stok Bahan</h2>
            <button type="button" data-tutup="modalAudit" aria-label="Tutup">&times;</button>
        </div>
        <form method="POST" id="formAudit" action="">
            @csrf
            <label class="form-label" for="auditNama">Nama Bahan</label>
            <input class="form-input" type="text" id="auditNama" readonly>
            <div class="form-dua">
                <div>
                    <label class="form-label" for="auditStok">Stok Fisik</label>
                    <input class="form-input" type="number" id="auditStok" name="stok_fisik" min="0" step="0.001" required>
                </div>
                <div>
                    <label class="form-label" for="auditSatuan">Satuan</label>
                    <select class="form-select" id="auditSatuan" name="satuan_input" required>
                        @foreach($satuanOptions as $satuan)
                            <option value="{{ $satuan }}">{{ $satuan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <label class="form-label" for="auditCatatan">Catatan Audit</label>
            <input class="form-input" type="text" id="auditCatatan" name="catatan" minlength="3" maxlength="255" placeholder="Contoh: opname pagi" required>
            <div class="modal-actions">
                <button type="button" class="btn btn-abu" data-tutup="modalAudit">Batal</button>
                <button type="submit" class="btn btn-hijau"
                        data-confirm="Simpan hasil audit stok fisik ini?"
                        data-confirm-title="Simpan Audit Stok?"
                        data-confirm-btn="Ya, Simpan Audit"
                        data-confirm-type="warning">Simpan Audit</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    const ruteAudit = '{{ route('panel.staf.stok.audit', '__ID__') }}';

    function buka(id) {
        document.getElementById(id).classList.add('active');
    }

    function tutup(id) {
        document.getElementById(id).classList.remove('active');
    }

    function pilihSatuan(select, satuan) {
        if (!satuan) {
            return;
        }
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].value === satuan) {
                select.selectedIndex = i;
                return;
            }
        }
        const opsi = document.createElement('option');
        opsi.value = satuan;
        opsi.textContent = satuan;
        opsi.selected = true;
        select.appendChild(opsi);
    }

    document.getElementById('bukaPasokan').addEventListener('click', function () { buka('modalPasokan'); });
    document.getElementById('bukaPembuangan').addEventListener('click', function () { buka('modalPembuangan'); });

    document.querySelectorAll('[data-pasokan]').forEach(function (tombol) {
        tombol.addEventListener('click', function () {
            document.getElementById('pasokanBahan').value = tombol.dataset.pasokan;
            pilihSatuan(document.getElementById('satuanPasokan'), tombol.dataset.satuan);
            buka('modalPasokan');
        });
    });

    document.querySelectorAll('[data-buang]').forEach(function (tombol) {
        tombol.addEventListener('click', function () {
            document.getElementById('buangBahan').value = tombol.dataset.buang;
            pilihSatuan(document.getElementById('satuanBuang'), tombol.dataset.satuan);
            buka('modalPembuangan');
        });
    });

    document.querySelectorAll('[data-audit]').forEach(function (tombol) {
        tombol.addEventListener('click', function () {
            document.getElementById('formAudit').action = ruteAudit.replace('__ID__', tombol.dataset.audit);
            document.getElementById('auditNama').value = tombol.dataset.nama;
            document.getElementById('auditStok').value = tombol.dataset.stok;
            pilihSatuan(document.getElementById('auditSatuan'), tombol.dataset.satuan);
            buka('modalAudit');
        });
    });

    document.getElementById('pasokanBahan').addEventListener('change', function () {
        const pilihan = this.selectedOptions[0];
        if (pilihan) {
            pilihSatuan(document.getElementById('satuanPasokan'), pilihan.dataset.satuan);
        }
    });

    document.getElementById('buangBahan').addEventListener('change', function () {
        const pilihan = this.selectedOptions[0];
        if (pilihan) {
            pilihSatuan(document.getElementById('satuanBuang'), pilihan.dataset.satuan);
        }
    });

    document.querySelectorAll('[data-tutup]').forEach(function (tombol) {
        tombol.addEventListener('click', function () { tutup(tombol.dataset.tutup); });
    });

    document.querySelectorAll('.modal-overlay').forEach(function (modal) {
        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                modal.classList.remove('active');
            }
        });
    });

    document.querySelectorAll('.qr-mini[data-qr-value]').forEach(function (el) {
        if (window.StafScanner && window.StafScanner.renderQr) {
            window.StafScanner.renderQr(el, el.dataset.qrValue);
        }
    });

    /* Live Instant Filter Bahan */
    const inputCari = document.querySelector('.search-form input[name="cari"]');
    if (inputCari) {
        inputCari.addEventListener('input', function () {
            const q = this.value.toLowerCase().trim();
            document.querySelectorAll('.bahan-card').forEach(function (card) {
                const nama = card.querySelector('.bahan-top strong')?.textContent.toLowerCase() || '';
                card.style.display = (!q || nama.includes(q)) ? '' : 'none';
            });
        });
    }

    /* Escape key closing modals */
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active').forEach(function(m) {
                m.classList.remove('active');
            });
        }
    });
})();
</script>
@endsection

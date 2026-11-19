@extends('layouts.staf')

@section('title', 'Antrean Racik Jus & KDS')

@section('styles')
<style>
    [hidden] { display: none !important; }
    .produksi-page { display: grid; gap: 14px; }
    .prod-hero { background: linear-gradient(135deg,#137333,#0f5132); color: #fff; border-radius: 18px; padding: 18px; display: flex; justify-content: space-between; align-items: center; gap: 12px; }
    .prod-hero h1 { font-size: 19px; font-weight: 900; }
    .prod-hero p { font-size: 12px; opacity: .85; margin-top: 4px; }
    .beban-pill { padding: 8px 12px; border-radius: 999px; font-size: 11px; font-weight: 900; white-space: nowrap; }
    .beban-merah { background: #fee2e2; color: #991b1b; }
    .beban-oranye { background: #ffedd5; color: #9a3412; }
    .beban-hijau { background: #dcfce7; color: #166534; }
    .segmented { display: flex; gap: 4px; background: #e2e8f0; border-radius: 14px; padding: 4px; }
    .segmented a { flex: 1; text-decoration: none; text-align: center; border-radius: 10px; padding: 9px 8px; font-size: 12px; font-weight: 900; color: #64748b; }
    .segmented a.active { background: #fff; color: #0f172a; box-shadow: 0 2px 6px rgba(0,0,0,.06); }
    .prod-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; }
    .prod-card.aktif { border-color: #137333; box-shadow: 0 6px 14px -10px rgba(19,115,51,.6); }
    .prod-card-head { display: flex; justify-content: space-between; align-items: center; gap: 8px; padding: 11px 14px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
    .prod-card.aktif .prod-card-head { background: #137333; color: #fff; border-bottom-color: #137333; }
    .prod-code { font-size: 15px; font-weight: 900; }
    .prod-badge { padding: 4px 9px; border-radius: 999px; font-size: 10px; font-weight: 900; text-transform: uppercase; background: #e2e8f0; color: #334155; }
    .prod-badge.diproses { background: #dcfce7; color: #166534; }
    .prod-badge.dikonfirmasi { background: #fef3c7; color: #92400e; }
    .prod-badge.menunggu_verifikasi { background: #ffedd5; color: #9a3412; }
    .prod-durasi { font-size: 11px; font-weight: 900; background: #fee2e2; color: #991b1b; padding: 4px 9px; border-radius: 999px; white-space: nowrap; }
    .prod-body { padding: 14px; }
    .prod-buyer { display: flex; justify-content: space-between; gap: 8px; font-size: 13px; font-weight: 800; color: #0f172a; margin-bottom: 10px; }
    .prod-buyer span:last-child { font-size: 11px; font-weight: 700; color: #64748b; }
    .prod-items { display: grid; gap: 6px; background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 12px; padding: 10px; margin-bottom: 10px; }
    .prod-item { display: flex; justify-content: space-between; gap: 8px; font-size: 12px; }
    .prod-item strong { color: #0f172a; }
    .prod-catatan { font-size: 11px; color: #15803d; font-weight: 700; }
    .prod-note { background: #fffbeb; border: 1px solid #fef3c7; color: #78350f; border-radius: 10px; padding: 9px; font-size: 11px; margin-bottom: 10px; }
    .btn { display: block; width: 100%; border: 0; border-radius: 11px; padding: 12px 14px; font-size: 13px; font-weight: 900; cursor: pointer; text-align: center; text-decoration: none; }
    .btn-selesai { background: #198754; color: #fff; }
    .btn-mulai { background: #ea580c; color: #fff; }
    .btn-ringan { background: #f1f5f9; color: #334155; }
    .prod-empty { background: #fff; border: 1px dashed #cbd5e1; border-radius: 16px; padding: 28px 16px; text-align: center; color: #64748b; font-size: 13px; }
    .prod-subhead { display: flex; justify-content: space-between; align-items: baseline; gap: 10px; margin-bottom: 9px; }
    .prod-subhead h2 { font-size: 15px; font-weight: 900; color: #0f172a; }
    .prod-subhead span { font-size: 11px; font-weight: 800; color: #15803d; text-decoration: none; }
    .pickup-list { display: grid; gap: 8px; }
    .pickup-item { display: flex; justify-content: space-between; align-items: center; gap: 10px; background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 11px 12px; font-size: 12px; }
    .pickup-item strong { display: block; color: #0f172a; font-size: 13px; }
    .pickup-item small { color: #64748b; }
    .kds-grid { display: grid; grid-template-columns: 1fr; gap: 12px; }
    .kds-head { display: flex; justify-content: space-between; align-items: center; gap: 10px; }
    .kds-head button { background: #fff; border: 1px solid #cbd5e1; border-radius: 9px; padding: 7px 10px; font-size: 11px; font-weight: 800; color: #334155; cursor: pointer; }
    .waste-open { background: #fee2e2; border: 1px solid #fecaca; border-radius: 13px; padding: 13px 15px; color: #991b1b; font-size: 13px; font-weight: 900; cursor: pointer; text-align: left; }
    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15,23,42,.6); z-index: 100; align-items: center; justify-content: center; padding: 16px; }
    .modal-overlay.active { display: flex; }
    .modal-card { background: #fff; border-radius: 16px; width: 100%; max-width: 440px; max-height: 90vh; overflow-y: auto; padding: 18px; box-shadow: 0 20px 40px -20px rgba(15,23,42,.6); }
    .modal-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
    .modal-head h2 { font-size: 16px; font-weight: 900; color: #991b1b; }
    .modal-head button { background: none; border: 0; font-size: 22px; cursor: pointer; color: #64748b; }
    .form-label { display: block; font-size: 11px; font-weight: 900; color: #334155; margin: 11px 0 5px; }
    .form-input, .form-select { width: 100%; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 11px; font-size: 13px; outline: none; background: #fff; font-family: inherit; }
    .form-input:focus, .form-select:focus { border-color: #198754; box-shadow: 0 0 0 3px rgba(25,135,84,.12); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 9px; }
    .modal-actions { display: flex; gap: 8px; margin-top: 16px; }
    .modal-actions .btn { flex: 1; }
    .modal-actions .btn-batal { background: #f1f5f9; color: #334155; }
    @media (min-width: 640px) { .kds-grid { grid-template-columns: repeat(2,minmax(0,1fr)); } }
    @media (min-width: 992px) { .kds-grid { grid-template-columns: repeat(3,minmax(0,1fr)); } }
</style>
@endsection

@section('content')
<div class="produksi-page">
    <section class="prod-hero">
        <div>
            <h1>Antrean Racik Jus</h1>
            <p>PESANAN AKTIF: {{ $totalAktifCount }} pesanan menunggu diproses</p>
            <p>Blender Station 01 Aktif</p>
        </div>
        <div class="beban-pill beban-{{ $bebanWarna }}">Beban {{ $statusBeban }}</div>
    </section>

    <div class="segmented">
        <a href="{{ route('panel.staf.produksi.index', ['mode' => 'kartu']) }}" class="{{ $mode === 'kartu' ? 'active' : '' }}">Tampilan Kartu</a>
        <a href="{{ route('panel.staf.produksi.index', ['mode' => 'kds']) }}" class="{{ $mode === 'kds' ? 'active' : '' }}">KDS Mode</a>
    </div>

    @if($mode === 'kartu')
        @if(! $sedangDibuat && ! $berikutnya && $antreanMenunggu->isEmpty())
            <div class="prod-empty">Belum ada pesanan yang perlu diracik saat ini.</div>
        @endif

        @if($sedangDibuat)
            <article class="prod-card aktif">
                <div class="prod-card-head">
                    <span class="prod-code">#{{ $sedangDibuat->kode_pesanan }}</span>
                    <span class="prod-badge diproses">{{ str_replace('_', ' ', $sedangDibuat->status_pesanan) }}</span>
                    <span class="prod-durasi">Menunggu {{ (int) $sedangDibuat->durasi_menit }} menit</span>
                </div>
                <div class="prod-body">
                    <div class="prod-buyer">
                        <span>{{ $sedangDibuat->nama_pembeli }}</span>
                        <span>{{ $sedangDibuat->created_at->format('H:i') }}</span>
                    </div>
                    <div class="prod-items">
                        @foreach($sedangDibuat->itemPesanan as $item)
                            <div class="prod-item">
                                <strong>{{ $item->jumlah }}x {{ $item->nama_produk }}</strong>
                                @if($item->nama_varian)<span>{{ $item->nama_varian }}</span>@endif
                            </div>
                        @endforeach
                    </div>
                    @if($sedangDibuat->catatan)
                        <div class="prod-note">Catatan: {{ $sedangDibuat->catatan }}</div>
                    @endif
                    @if(in_array($sedangDibuat->status_pesanan, ['diproses', 'dikonfirmasi'], true))
                        <form method="POST" action="{{ route('panel.staf.produksi.selesai-racik', $sedangDibuat->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-selesai">Selesai Racik &middot; Siap Diambil</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('panel.staf.produksi.mulai-racik', $sedangDibuat->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-mulai">Mulai Racik Sekarang</button>
                        </form>
                    @endif
                </div>
            </article>
        @endif

        @if($berikutnya)
            <article class="prod-card">
                <div class="prod-card-head">
                    <span class="prod-code">#{{ $berikutnya->kode_pesanan }}</span>
                    <span class="prod-badge {{ $berikutnya->status_pesanan }}">{{ str_replace('_', ' ', $berikutnya->status_pesanan) }}</span>
                    <span class="prod-durasi">Menunggu {{ (int) $berikutnya->durasi_menit }} menit</span>
                </div>
                <div class="prod-body">
                    <div class="prod-buyer">
                        <span>{{ $berikutnya->nama_pembeli }}</span>
                        <span>{{ $berikutnya->created_at->format('H:i') }}</span>
                    </div>
                    <div class="prod-items">
                        @foreach($berikutnya->itemPesanan as $item)
                            <div class="prod-item">
                                <strong>{{ $item->jumlah }}x {{ $item->nama_produk }}</strong>
                                @if($item->nama_varian)<span>{{ $item->nama_varian }}</span>@endif
                            </div>
                        @endforeach
                    </div>
                    @if($berikutnya->catatan)
                        <div class="prod-note">Catatan: {{ $berikutnya->catatan }}</div>
                    @endif
                    <form method="POST" action="{{ route('panel.staf.produksi.mulai-racik', $berikutnya->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-mulai">Mulai Racik Sekarang</button>
                    </form>
                </div>
            </article>
        @endif

        @foreach($antreanMenunggu as $menunggu)
            <article class="prod-card">
                <div class="prod-card-head">
                    <span class="prod-code">#{{ $menunggu->kode_pesanan }}</span>
                    <span class="prod-badge {{ $menunggu->status_pesanan }}">{{ str_replace('_', ' ', $menunggu->status_pesanan) }}</span>
                    <span class="prod-durasi">Menunggu {{ (int) $menunggu->durasi_menit }} menit</span>
                </div>
                <div class="prod-body">
                    <div class="prod-buyer">
                        <span>{{ $menunggu->nama_pembeli }}</span>
                        <span>{{ $menunggu->created_at->format('H:i') }}</span>
                    </div>
                    <div class="prod-items">
                        @foreach($menunggu->itemPesanan as $item)
                            <div class="prod-item">
                                <strong>{{ $item->jumlah }}x {{ $item->nama_produk }}</strong>
                                @if($item->nama_varian)<span>{{ $item->nama_varian }}</span>@endif
                            </div>
                        @endforeach
                    </div>
                    @if($menunggu->catatan)
                        <div class="prod-note">Catatan: {{ $menunggu->catatan }}</div>
                    @endif
                    <form method="POST" action="{{ route('panel.staf.produksi.mulai-racik', $menunggu->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-ringan">Ambil ke Blender</button>
                    </form>
                </div>
            </article>
        @endforeach

        @if($siapDiambil->isNotEmpty())
            <section>
                <div class="prod-subhead">
                    <h2>Siap Diambil ({{ $siapDiambil->count() }})</h2>
                    <a href="{{ route('panel.staf.pesanan.scan') }}">Buka scanner QR &rarr;</a>
                </div>
                <div class="pickup-list">
                    @foreach($siapDiambil as $siap)
                        <div class="pickup-item">
                            <div>
                                <strong>#{{ $siap->kode_pesanan }}</strong>
                                <small>{{ $siap->nama_pembeli }} &middot; {{ (int) $siap->durasi_menit }} menit produksi</small>
                            </div>
                            <a href="{{ route('panel.staf.pesanan.detail', $siap->id) }}" class="btn btn-ringan" style="width:auto;padding:8px 11px;">Detail</a>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    @else
        <div class="kds-head">
            <div style="font-size:12px;font-weight:800;color:#475569;">Kitchen Display System &middot; {{ $pesananAktif->count() }} kartu aktif</div>
            <button type="button" onclick="window.location.reload()">Refresh Layar</button>
        </div>
        <div class="kds-grid">
            @forelse($pesananAktif as $order)
                <article class="prod-card {{ $order->status_pesanan === 'diproses' ? 'aktif' : '' }}">
                    <div class="prod-card-head">
                        <span class="prod-code">#{{ $order->kode_pesanan }}</span>
                        <span class="prod-badge {{ $order->status_pesanan }}">{{ str_replace('_', ' ', $order->status_pesanan) }}</span>
                        <span class="prod-durasi">{{ (int) $order->durasi_menit }} menit</span>
                    </div>
                    <div class="prod-body">
                        <div class="prod-buyer">
                            <span>{{ $order->nama_pembeli }}</span>
                            <span>{{ $order->created_at->format('H:i') }}</span>
                        </div>
                        <div class="prod-items">
                            @foreach($order->itemPesanan as $item)
                                <div class="prod-item">
                                    <strong>{{ $item->jumlah }}x {{ $item->nama_produk }}</strong>
                                    @if($item->nama_varian)<span>{{ $item->nama_varian }}</span>@endif
                                </div>
                            @endforeach
                        </div>
                        @if($order->catatan)
                            <div class="prod-note">Catatan: {{ $order->catatan }}</div>
                        @endif
                        @if($order->status_pesanan === 'diproses')
                            <form method="POST" action="{{ route('panel.staf.produksi.selesai-racik', $order->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-selesai">Selesai Racik</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('panel.staf.produksi.mulai-racik', $order->id) }}">
                                @csrf
                                <button type="submit" class="btn btn-mulai">Mulai Racik</button>
                            </form>
                        @endif
                    </div>
                </article>
            @empty
                <div class="prod-empty" style="grid-column:1/-1;">Tidak ada pesanan aktif di dapur.</div>
            @endforelse
        </div>
    @endif

    <button type="button" class="waste-open" id="bukaWaste">Catat gagal racik / bahan tumpah</button>
</div>

<div class="modal-overlay" id="modalWaste">
    <div class="modal-card">
        <div class="modal-head">
            <h2>Catat Waste</h2>
            <p>Catat Bahan Tumpah / Gagal / gagal racik, dll</p>
            <button type="button" id="tutupWaste" aria-label="Tutup">&times;</button>
        </div>
        <form method="POST" action="{{ route('panel.staf.produksi.catat-gagal') }}">
            @csrf
            <label class="form-label" for="wasteTipe">Tipe Item</label>
            <select class="form-select" id="wasteTipe" name="tipe_item" required>
                <option value="produk">Produk jadi (cup)</option>
                <option value="bahan">Bahan baku</option>
            </select>

            <div id="wasteProdukWrap">
                <label class="form-label" for="wasteProduk">Produk</label>
                <select class="form-select" id="wasteProduk">
                    <option value="">Pilih produk</option>
                    @foreach($daftarProduk as $produk)
                        <option value="{{ $produk->id }}" data-satuan="cup">{{ $produk->nama }} (stok {{ $produk->stok }})</option>
                    @endforeach
                </select>
            </div>

            <div id="wasteBahanWrap" hidden>
                <label class="form-label" for="wasteBahan">Bahan</label>
                <select class="form-select" id="wasteBahan">
                    <option value="">Pilih bahan</option>
                    @foreach($daftarBahan as $bahan)
                        <option value="{{ $bahan->id }}" data-satuan="{{ $bahan->satuan_dasar }}">{{ $bahan->nama }} (tersedia {{ number_format($bahan->stok_tersedia, 2, ',', '.') }} {{ $bahan->satuan_dasar }})</option>
                    @endforeach
                </select>
            </div>

            <input type="hidden" name="item_id" id="wasteItemId">

            <div class="form-row">
                <div>
                    <label class="form-label" for="wasteJumlah">Jumlah</label>
                    <input class="form-input" type="number" id="wasteJumlah" name="jumlah" min="0.001" step="0.001" value="1" required>
                </div>
                <div>
                    <label class="form-label" for="wasteSatuan">Satuan</label>
                    <input class="form-input" type="text" id="wasteSatuan" name="satuan" maxlength="10" value="cup" required>
                </div>
            </div>

            <label class="form-label" for="wasteAlasan">Alasan</label>
            <input class="form-input" type="text" id="wasteAlasan" name="alasan" minlength="3" maxlength="255" placeholder="Contoh: tumpah saat penuangan" required>

            <div class="modal-actions">
                <button type="button" class="btn btn-batal" id="batalWaste">Batal</button>
                <button type="submit" class="btn btn-mulai">Simpan Waste</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function () {
    const modal = document.getElementById('modalWaste');
    const tipe = document.getElementById('wasteTipe');
    const produk = document.getElementById('wasteProduk');
    const bahan = document.getElementById('wasteBahan');
    const produkWrap = document.getElementById('wasteProdukWrap');
    const bahanWrap = document.getElementById('wasteBahanWrap');
    const itemId = document.getElementById('wasteItemId');
    const satuan = document.getElementById('wasteSatuan');

    function sinkron() {
        const pakaiBahan = tipe.value === 'bahan';
        produkWrap.hidden = pakaiBahan;
        bahanWrap.hidden = !pakaiBahan;
        produk.disabled = pakaiBahan;
        bahan.disabled = !pakaiBahan;

        const aktif = pakaiBahan ? bahan : produk;
        const pilihan = aktif.selectedOptions[0];
        itemId.value = aktif.value;
        satuan.value = pilihan ? (pilihan.dataset.satuan || '') : '';
    }

    document.getElementById('bukaWaste').addEventListener('click', function () {
        modal.classList.add('active');
        sinkron();
    });

    function tutup() {
        modal.classList.remove('active');
    }

    document.getElementById('tutupWaste').addEventListener('click', tutup);
    document.getElementById('batalWaste').addEventListener('click', tutup);
    modal.addEventListener('click', function (event) {
        if (event.target === modal) {
            tutup();
        }
    });

    tipe.addEventListener('change', sinkron);
    produk.addEventListener('change', sinkron);
    bahan.addEventListener('change', sinkron);
    sinkron();
})();
</script>
@endsection

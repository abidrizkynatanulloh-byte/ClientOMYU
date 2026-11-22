@extends('layouts.staf')

@section('title', 'Kelola Pesanan ' . $pesanan->kode_pesanan)

@section('styles')
<style>
    .detail-page { display: grid; gap: 16px; width: 100%; max-width: 100%; min-width: 0; }
    .detail-head a { color: #64748b; text-decoration: none; font-size: 13px; font-weight: 700; }
    .detail-title { display: flex; flex-wrap: wrap; gap: 8px; justify-content: space-between; align-items: center; margin-top: 8px; }
    .detail-title h1 { font-size: 21px; font-weight: 900; margin: 0; color: #0f172a; word-break: break-word; }
    .badge-row { display: flex; flex-wrap: wrap; gap: 6px; }
    .badge { display: inline-block; padding: 5px 10px; border-radius: 999px; font-size: 11px; font-weight: 800; background: #e2e8f0; color: #334155; }
    .badge-lunas, .badge-selesai, .badge-siap_diambil { background: #dcfce7; color: #166534; }
    .badge-menunggu, .badge-menunggu_pembayaran, .badge-menunggu_verifikasi { background: #fef3c7; color: #92400e; }
    .badge-diproses { background: #dbeafe; color: #1e40af; }
    .badge-dibatalkan, .badge-kedaluwarsa { background: #fee2e2; color: #991b1b; }
    .detail-grid { display: grid; gap: 16px; align-items: start; width: 100%; max-width: 100%; min-width: 0; }
    .detail-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 16px; width: 100%; max-width: 100%; min-width: 0; box-sizing: border-box; overflow: hidden; }
    .detail-card h3 { font-size: 14px; font-weight: 900; color: #153e2b; margin-bottom: 12px; }
    .detail-info { display: grid; grid-template-columns: repeat(auto-fit,minmax(140px,1fr)); gap: 12px; font-size: 13px; }
    .detail-label { color: #64748b; display: block; font-size: 12px; }
    .detail-value { font-weight: 700; color: #0f172a; margin-top: 2px; word-break: break-word; }
    .detail-note { grid-column: 1 / -1; background: #fffbeb; border: 1px solid #fef3c7; padding: 10px; border-radius: 10px; color: #78350f; word-break: break-word; }
    .table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%; max-width: 100%; }
    .detail-table { width: 100%; border-collapse: collapse; font-size: 12px; min-width: 480px; }
    .detail-table th { text-align: left; color: #64748b; font-weight: 700; padding: 8px 6px; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
    .detail-table td { padding: 10px 6px; border-bottom: 1px solid #f1f5f9; }
    .detail-table .right { text-align: right; }
    .detail-total { display: grid; gap: 6px; margin-top: 12px; font-size: 13px; }
    .detail-total-row { display: flex; justify-content: space-between; }
    .detail-total-row.final { font-size: 16px; font-weight: 900; color: #0f5132; border-top: 1px dashed #cbd5e1; padding-top: 8px; }
    .detail-actions { display: grid; gap: 10px; }
    .btn-action { border: 0; border-radius: 11px; padding: 11px 14px; font-weight: 800; font-size: 13px; cursor: pointer; text-align: center; width: 100%; }
    .btn-primary { background: #198754; color: #fff; }
    .btn-indigo { background: #4f46e5; color: #fff; }
    .btn-green { background: #16a34a; color: #fff; }
    .btn-danger { background: #fee2e2; color: #991b1b; }
    .detail-form label { display: block; font-size: 12px; font-weight: 700; color: #334155; margin: 10px 0 5px; }
    .detail-form input, .detail-form textarea { width: 100%; border: 1px solid #d7e4dc; border-radius: 10px; padding: 10px; font-size: 13px; outline: none; }
    .detail-form input:focus, .detail-form textarea:focus { border-color: #198754; box-shadow: 0 0 0 3px rgba(25,135,84,.12); }
    .detail-check { display: flex; gap: 8px; align-items: flex-start; margin-top: 10px; font-size: 12px; color: #475569; }
    .detail-qr { text-align: center; padding: 14px; background: #f6fbf7; border: 1px dashed #86efac; border-radius: 14px; }
    .detail-qr p { font-size: 12px; color: #15803d; margin-top: 8px; font-weight: 700; }
    .bukti-img { width: 100%; max-height: 220px; object-fit: contain; border-radius: 10px; border: 1px solid #e2e8f0; }
    .detail-divider { border: 0; border-top: 1px solid #e2e8f0; margin: 14px 0; }
    @media (min-width: 900px) { .detail-grid { grid-template-columns: 1.7fr 1fr; } }
</style>
@endsection

@section('content')
<div class="detail-page">
    <div class="detail-head">
        <a href="{{ route('panel.staf.pesanan.index') }}">&larr; Kembali ke Daftar Pesanan</a>
        <div class="detail-title">
            <h1>Pesanan: {{ $pesanan->kode_pesanan }}</h1>
            <div class="badge-row">
                <span class="badge badge-{{ $pesanan->status_pesanan }}">{{ strtoupper(str_replace('_', ' ', $pesanan->status_pesanan)) }}</span>
                <span class="badge {{ $pesanan->status_pembayaran === 'lunas' ? 'badge-lunas' : 'badge-menunggu' }}">BAYAR: {{ strtoupper($pesanan->status_pembayaran) }}</span>
                <span class="badge">{{ strtoupper($pesanan->jenis ?? 'online') }}</span>
            </div>
        </div>
    </div>

    <div class="detail-grid">
        <div>
            <section class="detail-card">
                <h3>Informasi Pembeli & Penyerahan</h3>
                <div class="detail-info">
                    <div><span class="detail-label">Nama pemesan</span><div class="detail-value">{{ $pesanan->nama_pembeli }}</div></div>
                    <div>
                        <span class="detail-label">Nomor WhatsApp</span>
                        <div class="detail-value">
                            {{ $pesanan->telepon_pembeli }}
                            @if($pesanan->telepon_pembeli && $pesanan->telepon_pembeli !== '-')
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pesanan->telepon_pembeli) }}" target="_blank" rel="noopener" style="color:#16a34a;font-size:11px;">Chat WA</a>
                            @endif
                        </div>
                    </div>
                    <div><span class="detail-label">Waktu pemesanan</span><div class="detail-value">{{ $pesanan->created_at->format('d M Y, H:i') }} WIB</div></div>
                    <div><span class="detail-label">Staf penangan</span><div class="detail-value">{{ $pesanan->penangan?->nama ?? 'Belum ditangani' }}</div></div>
                    @if($pesanan->catatan)
                        <div class="detail-note"><strong>Catatan pembeli:</strong> {{ $pesanan->catatan }}</div>
                    @endif
                    @if($pesanan->alasan_batal)
                        <div class="detail-note" style="background:#fef2f2;border-color:#fecaca;color:#991b1b;"><strong>Alasan pembatalan:</strong> {{ $pesanan->alasan_batal }}</div>
                    @endif
                </div>
            </section>

            <section class="detail-card" style="margin-top:16px;">
                <h3>Menu yang Dipesan</h3>
                <div class="table-wrap">
                    <table class="detail-table">
                        <thead>
                            <tr>
                                <th>Produk / Varian</th>
                                <th class="right">Harga</th>
                                <th class="right">Jumlah</th>
                                <th class="right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan->itemPesanan as $item)
                                <tr>
                                    <td><strong>{{ $item->nama_produk }}</strong>@if($item->nama_varian)<br><small>{{ $item->nama_varian }}</small>@endif</td>
                                    <td class="right">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td class="right">× {{ $item->jumlah }}</td>
                                    <td class="right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="detail-total">
                    <div class="detail-total-row"><span>Subtotal</span><span>Rp {{ number_format($pesanan->subtotal, 0, ',', '.') }}</span></div>
                    <div class="detail-total-row"><span>Ongkos kirim</span><span>Rp {{ number_format($pesanan->ongkir, 0, ',', '.') }}</span></div>
                    <div class="detail-total-row final"><span>Total pembayaran</span><span>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</span></div>
                </div>
            </section>

            @if($pesanan->pembayaran->isNotEmpty())
                <section class="detail-card" style="margin-top:16px;">
                    <h3>Detail Pembayaran</h3>
                    @foreach($pesanan->pembayaran as $bayar)
                        <div class="detail-info">
                            <div><span class="detail-label">Metode</span><div class="detail-value">{{ strtoupper($bayar->metode ?? '-') }}</div></div>
                            <div><span class="detail-label">Gerbang</span><div class="detail-value">{{ $bayar->gerbang }}</div></div>
                            <div><span class="detail-label">Referensi</span><div class="detail-value">{{ $bayar->referensi_gerbang }}</div></div>
                            <div><span class="detail-label">Nominal tercatat</span><div class="detail-value">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</div></div>
                            <div><span class="detail-label">Status</span><div class="detail-value">{{ strtoupper($bayar->status) }}</div></div>
                            <div><span class="detail-label">Diverifikasi oleh</span><div class="detail-value">{{ $bayar->verifikator?->nama ?? '-' }}</div></div>
                            @if($bayar->catatan_verifikasi)
                                <div class="detail-note">{{ $bayar->catatan_verifikasi }}</div>
                            @endif
                        </div>
                        @if($bayar->bukti_path)
                            <div style="margin-top:12px;"><img class="bukti-img" src="{{ Storage::url($bayar->bukti_path) }}" alt="Bukti pembayaran"></div>
                        @elseif(data_get($bayar->data_mentah, 'nama_file'))
                            <div class="detail-note" style="margin-top:12px;">Bukti referensi lama: {{ data_get($bayar->data_mentah, 'nama_file') }}</div>
                        @endif
                        <hr class="detail-divider">
                    @endforeach
                </section>
            @endif
        </div>

        <div>
            <section class="detail-card">
                <h3>Verifikasi Pembayaran</h3>
                @if($pesanan->status_pembayaran === 'lunas')
                    <div style="background:#f0fdf4;border:1px solid #bbf7d0;padding:12px;border-radius:10px;text-align:center;">
                        <strong style="color:#166534;">Pembayaran sudah lunas</strong>
                        <div style="font-size:12px;color:#15803d;margin-top:4px;">{{ $pesanan->dibayar_pada?->format('d/m/Y H:i') }}</div>
                    </div>
                @else
                    <form method="POST" action="{{ route('panel.staf.pesanan.verifikasi-bayar', $pesanan->id) }}" enctype="multipart/form-data" class="detail-form">
                        @csrf
                        <p style="font-size:12px;color:#64748b;">Periksa bukti dan nominal sebelum verifikasi. Nominal wajib sesuai total pesanan.</p>
                        <label>Nominal dibayar</label>
                        <input type="number" name="nominal_dibayar" min="0" step="1" value="{{ $pesanan->pembayaran->first()?->jumlah ?? $pesanan->total }}">
                        <label>Bukti pembayaran (QRIS/transfer)</label>
                        <input type="file" name="bukti_bayar" accept="image/jpeg,image/png,image/webp">
                        <label>Catatan verifikasi</label>
                        <textarea name="catatan_verifikasi" rows="2" maxlength="255" placeholder="Contoh: Cocok dengan mutasi BCA 17/11 10:24"></textarea>
                        <label class="detail-check">
                            <input type="checkbox" name="konfirmasi_tanpa_bukti" value="1" style="width:auto;margin-top:2px;">
                            <span>Verifikasi tanpa bukti (khusus data lama, wajib isi catatan minimal 10 karakter)</span>
                        </label>
                        <button type="submit" class="btn-action btn-primary" style="margin-top:12px;">Verifikasi Pembayaran Lunas</button>
                    </form>
                @endif
            </section>

            @if($pesanan->qrDapatDiterbitkan())
                <section class="detail-card" style="margin-top:16px;">
                    <h3>QR Pengambilan Pesanan</h3>
                    <div class="detail-qr">
                        <div id="qrPesanan"></div>
                        <p>Tunjukkan QR ini kepada staf saat pengambilan</p>
                    </div>
                    <a href="{{ route('panel.staf.pesanan.scan') }}" class="btn-action btn-indigo" style="display:block;text-decoration:none;margin-top:10px;">Buka Scanner QR Pengambilan</a>
                </section>
            @endif

            <section class="detail-card" style="margin-top:16px;">
                <h3>Alur Status Pesanan</h3>
                @if(in_array($pesanan->status_pesanan, ['selesai', 'dibatalkan', 'kedaluwarsa'], true))
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;padding:12px;border-radius:10px;text-align:center;font-size:13px;font-weight:800;">
                        Pesanan berada pada status akhir: {{ strtoupper(str_replace('_', ' ', $pesanan->status_pesanan)) }}
                    </div>
                @else
                    <div class="detail-actions">
                        @if(in_array($pesanan->status_pesanan, ['dikonfirmasi'], true))
                            <form method="POST" action="{{ route('panel.staf.pesanan.update-status', $pesanan->id) }}">
                                @csrf
                                <input type="hidden" name="status_pesanan" value="diproses">
                                <button type="submit" class="btn-action btn-primary">Mulai Proses Pembuatan</button>
                            </form>
                        @elseif($pesanan->status_pesanan === 'diproses')
                            <form method="POST" action="{{ route('panel.staf.pesanan.update-status', $pesanan->id) }}">
                                @csrf
                                <input type="hidden" name="status_pesanan" value="siap_diambil">
                                <button type="submit" class="btn-action btn-indigo">Selesai Dibuat & Siap Diambil</button>
                            </form>
                        @elseif($pesanan->status_pesanan === 'siap_diambil')
                            <form method="POST" action="{{ route('panel.staf.pesanan.update-status', $pesanan->id) }}">
                                @csrf
                                <input type="hidden" name="status_pesanan" value="selesai">
                                <button type="submit" class="btn-action btn-green" onclick="return confirm('Serahkan pesanan dan selesaikan transaksi?')">Serahkan & Selesaikan Pesanan</button>
                            </form>
                        @else
                            <div style="font-size:12px;color:#64748b;">Selesaikan verifikasi pembayaran untuk melanjutkan pesanan ke produksi.</div>
                        @endif

                        <details style="font-size:12px;">
                            <summary style="cursor:pointer;color:#ef4444;font-weight:700;">Batalkan pesanan ini</summary>
                            <form method="POST" action="{{ route('panel.staf.pesanan.batal', $pesanan->id) }}" style="margin-top:10px;">
                                @csrf
                                <label>Alasan pembatalan (wajib)</label>
                                <textarea name="alasan_batal" rows="2" minlength="5" maxlength="255" required placeholder="Contoh: Stok bahan habis mendadak"></textarea>
                                <button type="submit" class="btn-action btn-danger" style="margin-top:8px;" onclick="return confirm('Yakin membatalkan pesanan ini?')">Konfirmasi Pembatalan</button>
                            </form>
                        </details>
                    </div>
                @endif
            </section>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if($pesanan->qrDapatDiterbitkan())
<script>
    window.StafScanner.renderQr(document.getElementById('qrPesanan'), '{{ $pesanan->token_akses }}');
</script>
@endif
@endsection

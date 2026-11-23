@if(($daftarPesanan ?? collect())->count() > 0)
    <div class="lacak-list">
        @foreach($daftarPesanan as $pesanan)
            @php
                $statusLabel = match($pesanan->status_pesanan) {
                    'menunggu_pembayaran' => 'Menunggu Pembayaran',
                    'menunggu_verifikasi' => 'Menunggu Verifikasi',
                    'diproses' => 'Diproses',
                    'siap_diambil' => 'Siap Diambil',
                    'selesai' => 'Selesai',
                    'dibatalkan' => 'Dibatalkan',
                    default => ucwords(str_replace('_', ' ', $pesanan->status_pesanan ?? '-')),
                };
                $statusClass = match($pesanan->status_pesanan) {
                    'selesai' => 'is-selesai',
                    'siap_diambil' => 'is-siap',
                    'dibatalkan' => 'is-batal',
                    'menunggu_pembayaran', 'menunggu_verifikasi' => 'is-tunggu',
                    default => 'is-proses',
                };
            @endphp
            <details class="lacak-card" data-kode="{{ $pesanan->kode_pesanan }}">
                <summary class="lacak-card-summary">
                    <div class="lacak-col lacak-col-no">
                        <span class="lacak-label">Nomor</span>
                        <span class="lacak-value">#{{ $pesanan->kode_pesanan }}</span>
                    </div>
                    <div class="lacak-col lacak-col-nama">
                        <span class="lacak-label">Nama Pesanan</span>
                        <span class="lacak-value">{{ $pesanan->nama_pembeli ?: '-' }}</span>
                    </div>
                    <div class="lacak-col lacak-col-status">
                        <span class="lacak-label">Status</span>
                        <span class="lacak-status {{ $statusClass }}">{{ $statusLabel }}</span>
                    </div>
                    <div class="lacak-col lacak-col-detail">
                        <span class="lacak-detail-btn">Detail</span>
                    </div>
                </summary>

                <div class="lacak-detail">
                    <div class="lacak-detail-grid">
                        <div class="lacak-detail-main">
                            <div class="lacak-info-grid">
                                <div class="lacak-info-item"><span>Kode</span><strong>{{ $pesanan->kode_pesanan }}</strong></div>
                                <div class="lacak-info-item"><span>Nama</span><strong>{{ $pesanan->nama_pembeli ?: '-' }}</strong></div>
                                <div class="lacak-info-item"><span>No. WhatsApp</span><strong>{{ $pesanan->telepon_pembeli ?: '-' }}</strong></div>
                                <div class="lacak-info-item"><span>Metode Bayar</span><strong>{{ strtoupper(str_replace('_', ' ', $pesanan->metode_bayar ?? '-')) }}</strong></div>
                                <div class="lacak-info-item"><span>Status Pembayaran</span><strong>{{ ucwords(str_replace('_', ' ', $pesanan->status_pembayaran ?? '-')) }}</strong></div>
                                <div class="lacak-info-item"><span>Total</span><strong>Rp {{ number_format($pesanan->total ?? 0, 0, ',', '.') }}</strong></div>
                            </div>

                            <div class="lacak-items-box">
                                <div class="lacak-items-title">Detail Item Pesanan</div>
                                @foreach($pesanan->itemPesanan as $item)
                                    <div class="lacak-item-row">
                                        <div>
                                            <div class="lacak-item-name">{{ $item->nama_produk }}</div>
                                            <div class="lacak-item-sub">{{ $item->nama_varian ?: 'Reguler' }} · x{{ $item->jumlah }}</div>
                                        </div>
                                        <div class="lacak-item-price">Rp {{ number_format($item->subtotal ?? (($item->harga ?? 0) * ($item->jumlah ?? 0)), 0, ',', '.') }}</div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="lacak-note">
                                <strong>Catatan/Jadwal:</strong> {{ $pesanan->catatan ?: 'Segera dalam 15-20 menit' }}
                            </div>
                        </div>

                        <div class="lacak-detail-qr">
                            <div class="qr-display-box">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode($pesanan->kode_pesanan) }}" alt="QR Code Pesanan">
                            </div>
                            <div style="font-size:12px;color:#64748b;font-weight:700;text-align:center;">Tunjukkan QR ini saat ambil pesanan.</div>
                        </div>
                    </div>
                </div>
            </details>
        @endforeach
    </div>
@elseif(($kode ?? '') !== '')
    <div style="background: rgba(254, 226, 226, 0.95); color: #b91c1c; padding: 18px 24px; border-radius: 20px; text-align: center; font-weight: 700; border: 1px solid #fecaca;">
        Pesanan dengan kata kunci <strong>{{ $kode }}</strong> tidak ditemukan di perangkat ini.
    </div>
@else
    <div style="background: rgba(255,255,255,0.92); color: #475569; padding: 18px 24px; border-radius: 20px; text-align: center; font-weight: 700; border: 1px solid #e2e8f0;">
        Belum ada pesanan. Kamu bisa pesan tanpa login (tersimpan di perangkat ini), atau <a href="{{ route('pelanggan.login') }}" style="color:#4d8822;text-decoration:underline;">login</a> agar riwayat tersimpan ke akun.
    </div>
@endif

@extends('layouts.staf')

@section('title', 'Kelola Pesanan ' . $pesanan->kode_pesanan)

@section('content')
    <div style="margin-bottom: 20px;">
        <a href="{{ route('panel.staf.pesanan.index') }}" style="color: #64748b; text-decoration: none; font-size: 13px; font-weight: 600;">&larr; Kembali ke Daftar Pesanan</a>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
            <h1 style="margin: 0; font-size: 22px;">Pesanan: {{ $pesanan->kode_pesanan }}</h1>
            <div style="display: flex; gap: 8px;">
                <span class="badge badge-{{ $pesanan->status_pesanan }}" style="font-size: 13px; padding: 6px 12px;">
                    STATUS: {{ $pesanan->status_pesanan === 'dikirim' ? 'SIAP DIAMBIL' : strtoupper($pesanan->status_pesanan) }}
                </span>
                <span class="badge {{ $pesanan->status_pembayaran === 'lunas' ? 'badge-lunas' : 'badge-menunggu' }}" style="font-size: 13px; padding: 6px 12px;">
                    BAYAR: {{ strtoupper($pesanan->status_pembayaran) }}
                </span>
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
        <!-- Kolom Kiri: Rincian Produk & Pemesan -->
        <div>
            <!-- Data Pembeli & Waktu -->
            <div class="card" style="margin-bottom: 20px;">
                <h3 style="margin-bottom: 12px; font-size: 14px; color: #0f172a; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                    👤 Informasi Pembeli & Penyerahan
                </h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13px;">
                    <div>
                        <span style="color: #64748b;">Nama Pemesan:</span>
                        <div style="font-weight: 700; font-size: 14px; margin-top: 2px;">{{ $pesanan->nama_pembeli }}</div>
                    </div>
                    <div>
                        <span style="color: #64748b;">Nomor WhatsApp:</span>
                        <div style="font-weight: 700; font-size: 14px; margin-top: 2px;">
                            {{ $pesanan->telepon_pembeli }}
                            @if($pesanan->telepon_pembeli && $pesanan->telepon_pembeli !== '-')
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pesanan->telepon_pembeli) }}" target="_blank" style="color: #16a34a; font-size: 11px; margin-left: 6px; text-decoration: none;">[Chat WA]</a>
                            @endif
                        </div>
                    </div>
                    <div>
                        <span style="color: #64748b;">Waktu Pemesanan:</span>
                        <div style="font-weight: 600; margin-top: 2px;">{{ $pesanan->created_at->format('d M Y, H:i') }} WIB</div>
                    </div>
                    <div>
                        <span style="color: #64748b;">Staf Penangan:</span>
                        <div style="font-weight: 600; margin-top: 2px;">{{ $pesanan->penangan ? $pesanan->penangan->nama : 'Belum ditangani' }}</div>
                    </div>
                    @if($pesanan->catatan)
                        <div style="grid-column: span 2; background: #fffbeb; border: 1px solid #fef3c7; padding: 10px; border-radius: 6px;">
                            <span style="color: #b45309; font-weight: 700;">Catatan Pembeli:</span>
                            <div style="margin-top: 2px; color: #78350f;">{{ $pesanan->catatan }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Rincian Produk Dipesan -->
            <div class="card" style="margin-bottom: 20px;">
                <h3 style="margin-bottom: 12px; font-size: 14px; color: #0f172a; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                    🍹 Menu yang Dipesan
                </h3>
                <table>
                    <thead>
                        <tr>
                            <th>Menu Produk</th>
                            <th style="text-align: right;">Harga Satuan</th>
                            <th style="text-align: center;">Jumlah</th>
                            <th style="text-align: right;">Subtotal</th>
                            <th>Stok Fisik</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pesanan->itemPesanan as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->nama_produk }}</strong>
                                </td>
                                <td style="text-align: right;">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                <td style="text-align: center; font-weight: 700;">x{{ $item->jumlah }}</td>
                                <td style="text-align: right; font-weight: 700;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                <td>
                                    @if($item->produk)
                                        <span style="font-size: 12px; font-weight: 600; color: {{ $item->produk->stok < $item->jumlah ? '#dc2626' : '#16a34a' }};">
                                            {{ $item->produk->stok }} cup
                                        </span>
                                    @else
                                        <span style="color: #94a3b8;">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: 700; font-size: 14px;">Total Pesanan:</td>
                            <td style="text-align: right; font-weight: 800; font-size: 16px; color: #6aa332;">
                                Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Kolom Kanan: Aksi Workflow Staf -->
        <div>
            <!-- Aksi Pembayaran (K-05) -->
            <div class="card" style="margin-bottom: 20px;">
                <h3 style="font-size: 14px; color: #0f172a; margin-bottom: 12px;">💳 Verifikasi Pembayaran (K-05)</h3>
                
                @if($pesanan->status_pembayaran === 'lunas')
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 12px; border-radius: 6px; text-align: center;">
                        <span style="color: #166534; font-weight: 700; font-size: 14px;">✓ Pembayaran Sudah Lunas</span>
                        <div style="font-size: 11px; color: #15803d; margin-top: 4px;">
                            {{ $pesanan->dibayar_pada ? $pesanan->dibayar_pada->format('d/m/Y H:i') : '' }}
                        </div>
                    </div>
                @else
                    <p style="font-size: 13px; color: #64748b; margin-bottom: 14px;">
                        Periksa mutasi rekening / bukti transfer pembeli. Jika pembayaran valid, klik tombol verifikasi di bawah ini:
                    </p>
                    <form method="POST" action="{{ route('panel.staf.pesanan.verifikasi-bayar', $pesanan->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="width: 100%; text-align: center;" onclick="return confirm('Apakah Anda yakin pembayaran pesanan ini sudah masuk dan valid?')">
                            ✓ Verifikasi Pembayaran Lunas
                        </button>
                    </form>
                @endif
            </div>

            <!-- Alur Status Pesanan (K-04, K-06, K-12) -->
            <div class="card" style="margin-bottom: 20px;">
                <h3 style="font-size: 14px; color: #0f172a; margin-bottom: 12px;">⚙️ Alur Produksi & Status Pesanan</h3>

                @if($pesanan->status_pesanan === 'selesai')
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 14px; border-radius: 6px; text-align: center;">
                        <span style="color: #166534; font-weight: 800; font-size: 15px;">✓ PESANAN SELESAI</span>
                        <div style="font-size: 12px; color: #15803d; margin-top: 4px;">
                            Jus telah diserahkan ke pembeli dan stok bahan telah otomatis terpotong.
                        </div>
                    </div>
                @elseif($pesanan->status_pesanan === 'dibatalkan')
                    <div style="background: #fef2f2; border: 1px solid #fecaca; padding: 14px; border-radius: 6px; text-align: center;">
                        <span style="color: #991b1b; font-weight: 800; font-size: 15px;">✕ PESANAN DIBATALKAN</span>
                        <div style="font-size: 12px; color: #b91c1c; margin-top: 4px;">
                            Pesanan ini telah dibatalkan dan tidak dapat diproses lagi.
                        </div>
                    </div>
                @else
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        @if($pesanan->status_pesanan === 'baru')
                            <form method="POST" action="{{ route('panel.staf.pesanan.update-status', $pesanan->id) }}">
                                @csrf
                                <input type="hidden" name="status_pesanan" value="diproses">
                                <button type="submit" class="btn btn-primary" style="width: 100%;">
                                    ▶ Mulai Proses Pembuatan Jus (K-06)
                                </button>
                            </form>
                        @elseif($pesanan->status_pesanan === 'diproses')
                            <form method="POST" action="{{ route('panel.staf.pesanan.update-status', $pesanan->id) }}">
                                @csrf
                                <input type="hidden" name="status_pesanan" value="dikirim">
                                <button type="submit" class="btn" style="width: 100%; background: #4f46e5; color: #fff;">
                                    ✓ Jus Selesai Dibuat & Siap Diambil
                                </button>
                            </form>
                        @elseif($pesanan->status_pesanan === 'dikirim')
                            <form method="POST" action="{{ route('panel.staf.pesanan.update-status', $pesanan->id) }}">
                                @csrf
                                <input type="hidden" name="status_pesanan" value="selesai">
                                <button type="submit" class="btn" style="width: 100%; background: #16a34a; color: #fff; font-size: 14px;" onclick="return confirm('Serahkan jus ke pembeli dan selesaikan pesanan? Stok produk akan otomatis terpotong.')">
                                    🤝 Serahkan ke Pembeli & Selesai (K-12)
                                </button>
                            </form>
                        @endif

                        <hr style="border: none; border-top: 1px solid #f1f5f9; margin: 8px 0;">

                        <!-- Tombol Batal Pesanan -->
                        <details style="font-size: 13px;">
                            <summary style="cursor: pointer; color: #ef4444; font-weight: 600;">Batalkan Pesanan Ini...</summary>
                            <form method="POST" action="{{ route('panel.staf.pesanan.batal', $pesanan->id) }}" style="margin-top: 10px;">
                                @csrf
                                <label style="display: block; font-size: 12px; margin-bottom: 4px; font-weight: 600;">Alasan Pembatalan (Wajib):</label>
                                <textarea name="alasan_batal" rows="2" style="width: 100%; padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 12px;" placeholder="Contoh: Stok bahan habis mendadak, atau pembeli membatalkan..." required></textarea>
                                <button type="submit" class="btn btn-danger btn-sm" style="margin-top: 6px; width: 100%;" onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                    ✕ Konfirmasi Pembatalan Pesanan
                                </button>
                            </form>
                        </details>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection


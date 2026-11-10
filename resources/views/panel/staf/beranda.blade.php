@extends('layouts.staf')

@section('title', 'Dashboard Operasional Staf (K-02)')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h1 style="margin: 0; font-size: 22px;">Dashboard Operasional Kasir & Barista</h1>
            <p style="margin: 4px 0 0; color: #64748b; font-size: 14px;">Ringkasan pekerjaan dan ketersediaan stok bahan hari ini.</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('panel.staf.kasir.index') }}" class="btn btn-primary">🛒 Buka Kasir POS</a>
            <a href="{{ route('panel.staf.pesanan.index') }}" class="btn btn-secondary">📋 Lihat Semua Pesanan</a>
        </div>
    </div>

    <!-- Metrik Cepat Operasional Hari Ini -->
    <div class="grid">
        <div class="card">
            <h3>Pesanan Baru / Masuk</h3>
            <p class="angka" style="color: #2563eb;">{{ $pesananBaru }}</p>
            <div class="desc">Perlu verifikasi & konfirmasi</div>
        </div>
        <div class="card">
            <h3>Sedang Diproses</h3>
            <p class="angka" style="color: #d97706;">{{ $pesananDiproses }}</p>
            <div class="desc">Sedang diracik di blender</div>
        </div>
        <div class="card">
            <h3>Siap Diambil</h3>
            <p class="angka" style="color: #4f46e5;">{{ $pesananSiap }}</p>
            <div class="desc">Menunggu pelanggan datang</div>
        </div>
        <div class="card">
            <h3>Selesai Hari Ini</h3>
            <p class="angka" style="color: #16a34a;">{{ $pesananSelesai }}</p>
            <div class="desc">Total {{ $totalPesananHariIni }} transaksi hari ini</div>
        </div>
    </div>

    <!-- Cek Ketersediaan Stok (K-02) -->
    <div style="background: #ffffff; padding: 18px; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
            <h3 style="margin: 0; font-size: 15px; font-weight: 700;">📦 Cek Ketersediaan Menu & Stok Produk (K-02)</h3>
            <a href="{{ route('panel.staf.stok.index') }}" style="font-size: 13px; color: #6aa332; text-decoration: none; font-weight: 600;">Kelola Stok &rarr;</a>
        </div>
        <div style="display: flex; gap: 16px; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 150px; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 12px; border-radius: 8px;">
                <span style="font-size: 12px; color: #166534; font-weight: 600;">Stok Tersedia</span>
                <p style="margin: 4px 0 0; font-size: 20px; font-weight: 800; color: #15803d;">{{ $produkTersedia }} <span style="font-size: 13px; font-weight: 400;">menu</span></p>
            </div>
            <div style="flex: 1; min-width: 150px; background: #fefce8; border: 1px solid #fef08a; padding: 12px; border-radius: 8px;">
                <span style="font-size: 12px; color: #854d0e; font-weight: 600;">Stok Menipis (&le; 5 cup)</span>
                <p style="margin: 4px 0 0; font-size: 20px; font-weight: 800; color: #a16207;">{{ $produkMenipis }} <span style="font-size: 13px; font-weight: 400;">menu</span></p>
            </div>
            <div style="flex: 1; min-width: 150px; background: #fef2f2; border: 1px solid #fecaca; padding: 12px; border-radius: 8px;">
                <span style="font-size: 12px; color: #991b1b; font-weight: 600;">Stok Habis (Kosong)</span>
                <p style="margin: 4px 0 0; font-size: 20px; font-weight: 800; color: #b91c1c;">{{ $produkHabis }} <span style="font-size: 13px; font-weight: 400;">menu</span></p>
            </div>
        </div>
    </div>

    <!-- Antrean Pesanan Mendesak / Yang Perlu Ditangani -->
    <div style="background: #ffffff; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden;">
        <div style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 15px; font-weight: 700;">⏱ Antrean Pesanan Aktif (Mendesak)</h3>
            <span style="font-size: 12px; color: #64748b;">Diurutkan dari jadwal / pesanan paling awal</span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Waktu / Kode</th>
                    <th>Nama Pembeli</th>
                    <th>Item Pesanan</th>
                    <th>Total</th>
                    <th>Status Bayar</th>
                    <th>Status Pesanan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($antreanPesanan as $pesanan)
                    <tr>
                        <td>
                            <strong>{{ $pesanan->kode_pesanan }}</strong>
                            <div style="font-size: 11px; color: #94a3b8;">{{ $pesanan->created_at->format('H:i') }} WIB</div>
                        </td>
                        <td>
                            {{ $pesanan->nama_pembeli }}
                            <div style="font-size: 11px; color: #64748b;">{{ $pesanan->telepon_pembeli }}</div>
                        </td>
                        <td>
                            @foreach($pesanan->itemPesanan as $item)
                                <div style="font-size: 12px;">• {{ $item->nama_produk }} (x{{ $item->jumlah }})</div>
                            @endforeach
                        </td>
                        <td><strong>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</strong></td>
                        <td>
                            <span class="badge {{ $pesanan->status_pembayaran === 'lunas' ? 'badge-lunas' : 'badge-menunggu' }}">
                                {{ $pesanan->status_pembayaran }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $pesanan->status_pesanan }}">
                                {{ $pesanan->status_pesanan === 'dikirim' ? 'Siap Diambil' : ucfirst($pesanan->status_pesanan) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('panel.staf.pesanan.detail', $pesanan->id) }}" class="btn btn-secondary btn-sm">Buka Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #94a3b8; padding: 30px;">
                            Tidak ada antrean pesanan yang sedang aktif saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection


@extends('layouts.staf')

@section('title', 'Manajemen & Cek Stok (K-09, K-10, K-11)')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h1 style="margin: 0; font-size: 22px;">Manajemen & Cek Ketersediaan Stok (K-09)</h1>
            <p style="margin: 4px 0 0; color: #64748b; font-size: 14px;">Pantau stok siap jual, catat penyesuaian opname (K-11), atau kerusakan produk (K-10).</p>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="filter-bar">
        <form method="GET" action="{{ route('panel.staf.stok.index') }}" style="display: flex; flex-wrap: wrap; gap: 10px; width: 100%;">
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari nama produk..." style="flex: 1; min-width: 200px;">
            
            <select name="kategori_id">
                <option value="">Semua Kategori</option>
                @foreach($kategoriList as $kat)
                    <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                @endforeach
            </select>

            <select name="status_stok">
                <option value="">Status Stok: Semua</option>
                <option value="tersedia" {{ request('status_stok') == 'tersedia' ? 'selected' : '' }}>Tersedia (&gt; 5 cup)</option>
                <option value="menipis" {{ request('status_stok') == 'menipis' ? 'selected' : '' }}>Menipis (&le; 5 cup)</option>
                <option value="habis" {{ request('status_stok') == 'habis' ? 'selected' : '' }}>Habis (0 cup)</option>
            </select>

            <button type="submit" class="btn btn-secondary">Filter</button>
            <a href="{{ route('panel.staf.stok.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>

    <!-- Tabel Daftar Produk -->
    <div style="background: #ffffff; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 24px;">
        <table>
            <thead>
                <tr>
                    <th>Menu Produk</th>
                    <th>Kategori</th>
                    <th>Harga Jual</th>
                    <th>Stok Saat Ini</th>
                    <th>Status Ketersediaan</th>
                    <th style="text-align: right;">Aksi Penyesuaian (K-10/K-11)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($daftarProduk as $produk)
                    <tr>
                        <td>
                            <strong>{{ $produk->nama }}</strong>
                            <div style="font-size: 11px; color: #94a3b8;">{{ $produk->slug }}</div>
                        </td>
                        <td>{{ $produk->kategori ? $produk->kategori->nama : '-' }}</td>
                        <td>Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                        <td>
                            <strong style="font-size: 16px;">{{ $produk->stok }}</strong> cup
                        </td>
                        <td>
                            @if($produk->stok <= 0)
                                <span class="badge badge-batal">Habis</span>
                            @elseif($produk->stok <= 5)
                                <span class="badge badge-proses">Menipis</span>
                            @else
                                <span class="badge badge-selesai">Tersedia</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <details style="display: inline-block; text-align: left;">
                                <summary class="btn btn-secondary btn-sm" style="cursor: pointer;">⚙️ Sesuaikan Stok</summary>
                                <div style="position: absolute; right: 24px; margin-top: 6px; width: 300px; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); padding: 14px; z-index: 10;">
                                    <h4 style="margin: 0 0 10px; font-size: 13px; color: #0f172a;">Sesuaikan: {{ $produk->nama }}</h4>
                                    
                                    <form method="POST" action="{{ route('panel.staf.stok.penyesuaian', $produk->id) }}">
                                        @csrf
                                        <div style="margin-bottom: 8px;">
                                            <label style="font-size: 11px; font-weight: 700; color: #475569;">Jenis Aksi:</label>
                                            <select name="tipe_aksi" style="width: 100%; padding: 6px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 12px;">
                                                <option value="tambah">➕ Tambah Stok Fisik</option>
                                                <option value="kurang">➖ Rusak / Basi / Terbuang (K-10)</option>
                                                <option value="atur">📋 Koreksi Opname Fisik (K-11)</option>
                                            </select>
                                        </div>

                                        <div style="margin-bottom: 8px;">
                                            <label style="font-size: 11px; font-weight: 700; color: #475569;">Jumlah (Cup):</label>
                                            <input type="number" name="jumlah" min="1" required style="width: 100%; padding: 6px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 12px;" placeholder="Contoh: 5">
                                        </div>

                                        <div style="margin-bottom: 10px;">
                                            <label style="font-size: 11px; font-weight: 700; color: #475569;">Alasan Penyesuaian (Wajib):</label>
                                            <input type="text" name="alasan" required style="width: 100%; padding: 6px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 12px;" placeholder="Contoh: Buah basi, stok opname mingguan...">
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-sm" style="width: 100%; text-align: center;">
                                            Simpan Penyesuaian
                                        </button>
                                    </form>
                                </div>
                            </details>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #94a3b8; padding: 30px;">
                            Tidak ada data produk ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-bottom: 24px;">
        {{ $daftarProduk->links() }}
    </div>

    <!-- Riwayat Pergerakan Stok Terakhir -->
    <div style="background: #ffffff; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden;">
        <div style="padding: 14px 18px; border-bottom: 1px solid #e2e8f0;">
            <h3 style="margin: 0; font-size: 14px; font-weight: 700;">📝 Riwayat Pergerakan Stok Terakhir</h3>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Produk</th>
                    <th>Jenis Mutasi</th>
                    <th>Perubahan</th>
                    <th>Stok Akhir</th>
                    <th>Keterangan / Alasan</th>
                    <th>Staf</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayatTerakhir as $riwayat)
                    <tr>
                        <td style="font-size: 12px; color: #64748b;">{{ $riwayat->created_at->format('d/m/Y H:i') }}</td>
                        <td><strong>{{ $riwayat->produk ? $riwayat->produk->nama : '-' }}</strong></td>
                        <td>
                            <span class="badge {{ $riwayat->jenis === 'masuk' ? 'badge-selesai' : ($riwayat->jenis === 'keluar_pesanan' ? 'badge-baru' : 'badge-proses') }}">
                                {{ str_replace('_', ' ', $riwayat->jenis) }}
                            </span>
                        </td>
                        <td>
                            <strong style="color: {{ $riwayat->jumlah > 0 ? '#16a34a' : '#dc2626' }};">
                                {{ $riwayat->jumlah > 0 ? '+' . $riwayat->jumlah : $riwayat->jumlah }}
                            </strong>
                        </td>
                        <td>{{ $riwayat->stok_setelah }} cup</td>
                        <td style="font-size: 12px; color: #475569;">{{ $riwayat->catatan ?: '-' }}</td>
                        <td style="font-size: 12px;">{{ $riwayat->pengguna ? $riwayat->pengguna->nama : 'Sistem' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #94a3b8; padding: 20px;">
                            Belum ada riwayat pergerakan stok.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection


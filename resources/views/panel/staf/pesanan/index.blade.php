@extends('layouts.staf')

@section('title', 'Pesanan Masuk (K-03)')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h1 style="margin: 0; font-size: 22px;">Daftar Pesanan Masuk (K-03)</h1>
            <p style="margin: 4px 0 0; color: #64748b; font-size: 14px;">Kelola alur verifikasi, produksi, dan penyerahan pesanan jus.</p>
        </div>
        <a href="{{ route('panel.staf.kasir.index') }}" class="btn btn-primary">+ Pesanan Langsung (Kasir)</a>
    </div>

    <!-- Status Tab Navigasi -->
    <div style="display: flex; gap: 8px; margin-bottom: 16px; overflow-x: auto; padding-bottom: 4px;">
        <a href="{{ route('panel.staf.pesanan.index') }}" class="btn {{ !request('status') ? 'btn-primary' : 'btn-secondary' }} btn-sm">
            Semua ({{ $semuaCount }})
        </a>
        <a href="{{ route('panel.staf.pesanan.index', ['status' => 'baru']) }}" class="btn {{ request('status') == 'baru' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
            Baru / Masuk ({{ $baruCount }})
        </a>
        <a href="{{ route('panel.staf.pesanan.index', ['status' => 'diproses']) }}" class="btn {{ request('status') == 'diproses' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
            Diproses ({{ $prosesCount }})
        </a>
        <a href="{{ route('panel.staf.pesanan.index', ['status' => 'dikirim']) }}" class="btn {{ request('status') == 'dikirim' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
            Siap Diambil ({{ $siapCount }})
        </a>
        <a href="{{ route('panel.staf.pesanan.index', ['status' => 'selesai']) }}" class="btn {{ request('status') == 'selesai' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
            Selesai ({{ $selesaiCount }})
        </a>
        <a href="{{ route('panel.staf.pesanan.index', ['status' => 'dibatalkan']) }}" class="btn {{ request('status') == 'dibatalkan' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
            Dibatalkan ({{ $batalCount }})
        </a>
    </div>

    <!-- Form Filter & Pencarian -->
    <div class="filter-bar">
        <form method="GET" action="{{ route('panel.staf.pesanan.index') }}" style="display: flex; flex-wrap: wrap; gap: 10px; width: 100%;">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari Kode / Nama / No HP..." style="flex: 1; min-width: 220px;">
            
            <select name="pembayaran">
                <option value="">Status Bayar: Semua</option>
                <option value="lunas" {{ request('pembayaran') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                <option value="menunggu" {{ request('pembayaran') == 'menunggu' ? 'selected' : '' }}>Menunggu Bayar</option>
            </select>

            <input type="date" name="tanggal" value="{{ request('tanggal') }}">

            <button type="submit" class="btn btn-secondary">Filter</button>
            <a href="{{ route('panel.staf.pesanan.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>

    <!-- Tabel Daftar Pesanan -->
    <div style="background: #ffffff; border-radius: 10px; border: 1px solid #e2e8f0; overflow: hidden;">
        <table>
            <thead>
                <tr>
                    <th>Waktu / Kode</th>
                    <th>Nama Pembeli</th>
                    <th>Menu Dipesan</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status Pesanan</th>
                    <th>Staf Penangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($daftarPesanan as $pesanan)
                    <tr>
                        <td>
                            <strong>{{ $pesanan->kode_pesanan }}</strong>
                            <div style="font-size: 11px; color: #94a3b8;">{{ $pesanan->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td>
                            <strong>{{ $pesanan->nama_pembeli }}</strong>
                            <div style="font-size: 11px; color: #64748b;">{{ $pesanan->telepon_pembeli }}</div>
                        </td>
                        <td>
                            @foreach($pesanan->itemPesanan as $item)
                                <div style="font-size: 12px;">• {{ $item->nama_produk }} <span style="color:#64748b;">(x{{ $item->jumlah }})</span></div>
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
                            <span style="font-size: 12px; color: #475569;">
                                {{ $pesanan->penangan ? $pesanan->penangan->nama : '-' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('panel.staf.pesanan.detail', $pesanan->id) }}" class="btn btn-secondary btn-sm">Kelola</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: #94a3b8; padding: 40px;">
                            Tidak ditemukan data pesanan yang sesuai dengan filter.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 16px;">
        {{ $daftarPesanan->links() }}
    </div>
@endsection


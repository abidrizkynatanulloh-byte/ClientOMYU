<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Persediaan & Bahan Baku - OMYU</title>
    <style>
        body { font-family: sans-serif; padding: 24px; background: #f8fafc; color: #1e293b; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 20px; }
        .card { background: #fff; padding: 16px; border-radius: 10px; border: 1px solid #e2e8f0; }
        .card h3 { margin: 0 0 6px; font-size: 13px; color: #64748b; }
        .card .angka { font-size: 20px; font-weight: bold; margin: 0; }
        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; margin-top: 16px; border: 1px solid #e2e8f0; }
        th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        th { background: #f1f5f9; }
        .nav a { margin-right: 10px; text-decoration: none; padding: 6px 12px; background: #e2e8f0; color: #334155; border-radius: 6px; font-size: 13px; font-weight: bold; }
        .nav a.active { background: #0f172a; color: white; }
        .badge { padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: bold; }
        .badge-aman { background: #dcfce7; color: #15803d; }
        .badge-kritis { background: #fee2e2; color: #b91c1c; }
        .pesan-sukses { background: #dcfce7; color: #15803d; padding: 12px; border-radius: 8px; margin-bottom: 16px; font-weight: bold; }
        .btn-tambah { background: #16a34a; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>

    <div class="nav" style="margin-bottom: 20px;">
        <a href="{{ route('panel.admin.beranda') }}">📊 Dashboard</a>
        <a href="{{ route('panel.admin.penjualan.index') }}">🛒 Penjualan</a>
        <a href="{{ route('panel.admin.resep.index') }}">🍹 Resep & HPP</a>
        <a href="{{ route('panel.admin.bahan.index') }}" class="active">📦 Persediaan Bahan (O-04)</a>
        <a href="{{ route('panel.admin.keuangan.laba_rugi') }}">
            💰 Keuangan & Laba Rugi (O-05)
        </a>
    </div>

    @if(session('sukses'))
        <div class="pesan-sukses">{{ session('sukses') }}</div>
    @endif

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
        <h1>Daftar Persediaan & Stok Bahan Baku (O-04)</h1>
        <a href="{{ route('panel.admin.bahan.create') }}" class="btn-tambah">+ Tambah Bahan Baru</a>
    </div>

    <!-- Ringkasan Aset Persediaan -->
    <div class="grid">
        <div class="card">
            <h3>Total Jenis Bahan</h3>
            <p class="angka">{{ $totalJenisBahan }} Jenis</p>
        </div>
        <div class="card">
            <h3>Total Nilai Persediaan</h3>
            <p class="angka" style="color: #16a34a;">Rp {{ number_format($totalNilaiPersediaan, 0, ',', '.') }}</p>
            <small>Aset di gudang/kulkas</small>
        </div>
        <div class="card">
            <h3>Bahan Stok Kritis</h3>
            <p class="angka" style="color: #dc2626;">{{ $jumlahBahanKritis }} Bahan</p>
            <small>Perlu segera belanja</small>
        </div>
    </div>

    <!-- Tabel Data Bahan -->
    <table>
        <thead>
            <tr>
                <th>Nama Bahan</th>
                <th>Jenis</th>
                <th>Satuan Beli</th>
                <th>Rendemen Standar</th>
                <th>Stok Siap Pakai</th>
                <th>Batas Minimum</th>
                <th>Biaya Rata-rata</th>
                <th>Nilai Persediaan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($daftarBahan as $b)
                <tr>
                    <td><strong>{{ $b->nama }}</strong></td>
                    <td>{{ ucfirst($b->jenis) }}</td>
                    <td>1 {{ $b->satuan_beli }} = {{ (float) $b->konversi }} {{ $b->satuan_dasar }}</td>
                    <td><strong>{{ (float) $b->rendemen_standar }}%</strong></td>
                    <td>
                        <strong>{{ (float) $b->stok_tersedia }}</strong> {{ $b->satuan_dasar }}
                        @if((float) $b->stok_dicadangkan > 0)
                            <br><small style="color: #f59e0b;">(Cadangan: {{ (float) $b->stok_dicadangkan }})</small>
                        @endif
                    </td>
                    <td>{{ (float) $b->batas_minimum }} {{ $b->satuan_dasar }}</td>
                    <td>Rp {{ number_format($b->biaya_rata_rata, 2, ',', '.') }} / {{ $b->satuan_dasar }}</td>
                    <td>Rp {{ number_format((float) $b->stok * (float) $b->biaya_rata_rata, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $b->is_kritis ? 'badge-kritis' : 'badge-aman' }}">
                            {{ $b->is_kritis ? 'KRITIS' : 'AMAN' }}
                        </span>
                    </td>
                                        <td style="display: flex; gap: 10px; align-items: center;">
                        <a href="{{ route('panel.admin.bahan.edit', $b->id) }}">Edit</a>

                        <form method="POST" action="{{ route('panel.admin.bahan.destroy', $b->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus bahan {{ $b->nama }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; text-decoration: underline; padding: 0; font-size: 14px;">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" style="text-align: center; color: #64748b;">Belum ada bahan baku yang terdaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 16px;">
        {{ $daftarBahan->links() }}
    </div>

</body>
</html>
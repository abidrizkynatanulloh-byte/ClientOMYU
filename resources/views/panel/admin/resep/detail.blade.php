<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Breakdown Resep {{ $varian->produk->nama }} ({{ $varian->nama }})</title>
    <style>
        body { font-family: sans-serif; padding: 24px; background: #f8fafc; color: #1e293b; max-width: 900px; margin: auto; }
        .box { background: #fff; padding: 20px; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        th { background: #f1f5f9; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-top: 10px; }
        .card { background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid #e2e8f0; }
        .card h4 { margin: 0 0 4px; font-size: 12px; color: #64748b; }
        .card p { margin: 0; font-size: 18px; font-weight: bold; }
    </style>
</head>
<body>

    <p><a href="{{ route('panel.admin.resep.index') }}">← Kembali ke Daftar Resep & HPP</a></p>
    <h1>Rincian HPP: {{ $varian->produk->nama }} ({{ $varian->nama }})</h1>

    <!-- Ringkasan Finansial Per Cup -->
    <div class="box">
        <h3>Ringkasan Margin Per Cup (Bab 2.8)</h3>
        <div class="grid">
            <div class="card">
                <h4>Harga Jual</h4>
                <p>Rp {{ number_format($hargaJual, 0, ',', '.') }}</p>
            </div>
            <div class="card">
                <h4>Total HPP Bahan</h4>
                <p style="color: #dc2626;">Rp {{ number_format($totalHpp, 0, ',', '.') }}</p>
            </div>
            <div class="card">
                <h4>Laba Kotor per Cup</h4>
                <p style="color: #16a34a;">Rp {{ number_format($labaKotor, 0, ',', '.') }}</p>
            </div>
            <div class="card">
                <h4>Margin Laba</h4>
                <p>{{ $margin }}%</p>
            </div>
            <div class="card">
                <h4>Kapasitas Maksimal</h4>
                <p>{{ $kapasitasFinal }} Cup</p>
                <small style="color: #dc2626;">Dibatasi: {{ $bahanPembatas ?? '-' }}</small>
            </div>
        </div>
    </div>

    <!-- Tabel Rincian Takaran Bahan -->
    <div class="box">
        <h3>Komposisi Resep & Biaya Bahan Baku</h3>
        <table>
            <thead>
                <tr>
                    <th>Bahan</th>
                    <th>Jenis</th>
                    <th>Takaran Resep</th>
                    <th>Biaya Rata-rata / Satuan</th>
                    <th>Biaya per Cup</th>
                    <th>Sisa Stok Gudang</th>
                    <th>Bisa Jadi (Cup)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rincianBahan as $b)
                    <tr>
                        <td><strong>{{ $b['nama_bahan'] }}</strong></td>
                        <td>{{ ucfirst($b['jenis']) }}</td>
                        <td>{{ $b['takaran'] }} {{ $b['satuan'] }}</td>
                        <td>Rp {{ number_format($b['biaya_satuan'], 2, ',', '.') }} / {{ $b['satuan'] }}</td>
                        <td><strong>Rp {{ number_format($b['biaya_per_cup'], 0, ',', '.') }}</strong></td>
                        <td>{{ $b['stok_tersedia'] }} {{ $b['satuan'] }}</td>
                        <td>{{ $b['kapasitas_cup'] }} cup</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #64748b;">Belum ada komposisi bahan pada resep ini.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" style="text-align: right;">Total HPP Bahan per Cup:</th>
                    <th colspan="3" style="color: #dc2626;">Rp {{ number_format($totalHpp, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

</body>
</html>
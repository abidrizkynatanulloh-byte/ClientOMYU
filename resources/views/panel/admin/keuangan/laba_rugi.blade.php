<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Laba Rugi - OMYU</title>
    <style>
        body { font-family: sans-serif; padding: 24px; background: #f8fafc; color: #1e293b; max-width: 850px; margin: auto; }
        .box { background: #fff; padding: 24px; border-radius: 10px; border: 1px solid #e2e8f0; margin-top: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        td { padding: 10px; border-bottom: 1px solid #f1f5f9; font-size: 15px; }
        .tebal { font-weight: bold; background: #f8fafc; }
        .nominal { text-align: right; font-family: monospace; font-size: 16px; }
        .nav a { margin-right: 10px; text-decoration: none; padding: 6px 12px; background: #e2e8f0; color: #334155; border-radius: 6px; font-size: 13px; font-weight: bold; }
        .nav a.active { background: #0f172a; color: white; }
    </style>
</head>
<body>

    <div class="nav" style="margin-bottom: 20px;">
        <a href="{{ route('panel.admin.beranda') }}">📊 Dashboard</a>
        <a href="{{ route('panel.admin.penjualan.index') }}">🛒 Penjualan</a>
        <a href="{{ route('panel.admin.resep.index') }}">🍹 Resep & HPP</a>
        <a href="{{ route('panel.admin.bahan.index') }}">📦 Persediaan</a>
        <a href="{{ route('panel.admin.keuangan.laba_rugi') }}" class="active">💰 Laba Rugi (O-05)</a>
        <a href="{{ route('panel.admin.keuangan.buku_kas') }}">📖 Buku Kas</a>
    </div>

    <h1>Laporan Laba Rugi Usaha (SAK EMKM)</h1>

    <!-- Filter Periode Bulan & Tahun -->
    <form method="GET" action="{{ route('panel.admin.keuangan.laba_rugi') }}" style="margin-bottom: 16px;">
        <label>Pilih Periode: </label>
        <select name="bulan" style="padding: 6px; border-radius: 6px;">
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                    {{ Carbon\Carbon::create(null, $m)->translatedFormat('F') }}
                </option>
            @endfor
        </select>
        <select name="tahun" style="padding: 6px; border-radius: 6px;">
            @for($y = Carbon\Carbon::now()->year; $y >= 2024; $y--)
                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
        <button type="submit" style="padding: 6px 14px; background: #0f172a; color: white; border: none; border-radius: 6px; cursor: pointer;">Tampilkan</button>
    </form>

    <div class="box">
        <h3 style="margin-top: 0; text-align: center;">OMYU Fresh Juice</h3>
        <p style="text-align: center; color: #64748b; margin-top: -6px;">Periode: {{ Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}</p>

        <table>
            <tbody>
                <tr class="tebal">
                    <td><strong>PENDAPATAN USAHA (OMZET)</strong></td>
                    <td class="nominal"><strong>Rp {{ number_format($penjualan, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td style="padding-left: 24px; color: #dc2626;">Harga Pokok Penjualan (HPP Bahan & Kemasan)</td>
                    <td class="nominal" style="color: #dc2626;">(Rp {{ number_format($hpp, 0, ',', '.') }})</td>
                </tr>
                <tr class="tebal" style="background: #f0fdf4;">
                    <td><strong>LABA KOTOR (Margin: {{ $marginLabaKotor }}%, Food Cost: {{ $foodCostRatio }}%)</strong></td>
                    <td class="nominal" style="color: #16a34a;"><strong>Rp {{ number_format($labaKotor, 0, ',', '.') }}</strong></td>
                </tr>

                <tr><td colspan="2" style="border: none; padding: 10px;"></td></tr>

                <tr class="tebal">
                    <td colspan="2"><strong>BEBAN OPERASIONAL:</strong></td>
                </tr>
                @forelse($daftarBeban as $beban)
                    <tr>
                        <td style="padding-left: 24px;">{{ $beban->keterangan }}</td>
                        <td class="nominal" style="color: #dc2626;">(Rp {{ number_format($beban->jumlah, 0, ',', '.') }})</td>
                    </tr>
                @empty
                    <tr>
                        <td style="padding-left: 24px; color: #64748b;" colspan="2"><em>Belum ada beban operasional kas keluar pada periode ini.</em></td>
                    </tr>
                @endforelse
                <tr class="tebal" style="background: #fef2f2;">
                    <td><strong>TOTAL BEBAN OPERASIONAL</strong></td>
                    <td class="nominal" style="color: #dc2626;"><strong>(Rp {{ number_format($totalBebanOperasional, 0, ',', '.') }})</strong></td>
                </tr>

                <tr><td colspan="2" style="border: none; padding: 10px;"></td></tr>

                <tr class="tebal" style="background: #e0f2fe; border-top: 2px solid #0284c7; border-bottom: 2px solid #0284c7;">
                    <td><strong style="font-size: 16px;">LABA BERSIH USAHA (Margin Bersih: {{ $marginLabaBersih }}%)</strong></td>
                    <td class="nominal" style="color: #0369a1; font-size: 18px;"><strong>Rp {{ number_format($labaBersih, 0, ',', '.') }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

</body>
</html>
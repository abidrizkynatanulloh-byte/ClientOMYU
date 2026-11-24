@extends('layouts.staf')

@section('title', 'Pusat Bantuan')

@section('styles')
<style>
    .bantuan-page { display: grid; gap: 16px; max-width: 1040px; margin: 0 auto; }
    .bantuan-hero { background: linear-gradient(135deg,#198754,#0f5132); color: #fff; border-radius: 18px; padding: 22px; }
    .bantuan-hero h1 { font-size: 22px; font-weight: 900; }
    .bantuan-hero p { font-size: 12px; opacity: .85; margin-top: 5px; }
    .bantuan-search { margin-top: 14px; display: flex; align-items: center; gap: 9px; background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3); border-radius: 11px; padding: 9px 12px; }
    .bantuan-search svg { width: 16px; height: 16px; flex-shrink: 0; }
    .bantuan-search input { flex: 1; min-width: 0; background: none; border: 0; outline: 0; color: #fff; font-size: 13px; }
    .bantuan-search input::placeholder { color: rgba(255,255,255,.7); }
    .bantuan-section-title { font-size: 11px; font-weight: 900; letter-spacing: 1px; text-transform: uppercase; color: #94a3b8; margin: 4px 0 -5px; }
    .bantuan-grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(230px,1fr)); gap: 12px; }
    .bantuan-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px; text-decoration: none; display: flex; gap: 12px; align-items: flex-start; transition: transform .15s, box-shadow .15s; }
    .bantuan-card:hover { transform: translateY(-2px); box-shadow: 0 10px 24px -12px rgba(15,23,42,.25); }
    .bantuan-card-icon { width: 36px; height: 36px; border-radius: 10px; background: #f0fdf4; color: #198754; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .bantuan-card-icon svg { width: 18px; height: 18px; }
    .bantuan-card strong { display: block; font-size: 13px; font-weight: 900; color: #0f172a; }
    .bantuan-card span { display: block; font-size: 11px; color: #64748b; margin-top: 3px; line-height: 1.5; }
    .bantuan-faq { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; overflow: hidden; }
    .bantuan-faq details { border-bottom: 1px solid #f1f5f9; padding: 14px 16px; }
    .bantuan-faq details:last-child { border-bottom: 0; }
    .bantuan-faq summary { cursor: pointer; font-size: 13px; font-weight: 800; color: #0f172a; list-style: none; display: flex; justify-content: space-between; gap: 10px; }
    .bantuan-faq summary::-webkit-details-marker { display: none; }
    .bantuan-faq summary::after { content: '+'; color: #198754; font-weight: 900; }
    .bantuan-faq details[open] summary::after { content: '−'; }
    .bantuan-faq p { font-size: 12px; color: #64748b; line-height: 1.65; margin: 9px 0 0; }
    .bantuan-kontak { display: flex; align-items: center; justify-content: space-between; gap: 14px; flex-wrap: wrap; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px; }
    .bantuan-kontak strong { font-size: 13px; font-weight: 900; color: #0f172a; }
    .bantuan-kontak p { font-size: 11px; color: #64748b; margin-top: 3px; }
    .bantuan-wa { display: inline-flex; align-items: center; gap: 7px; background: #16a34a; color: #fff; text-decoration: none; padding: 10px 14px; border-radius: 10px; font-size: 12px; font-weight: 900; }
    @media (max-width: 520px) {
        .bantuan-hero { padding: 18px; }
        .bantuan-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="bantuan-page">
    <div class="bantuan-hero">
        <h1>Pusat Bantuan Staf</h1>
        <p>Panduan singkat operasional harian. Cari topik atau buka salah satu panduan di bawah.</p>
        <label class="bantuan-search">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-3.5-3.5"></path></svg>
            <input type="search" id="cariBantuan" placeholder="Cari bantuan, misal: stok, kasir, kata sandi..." autocomplete="off">
        </label>
    </div>

    <div class="bantuan-section-title">Panduan cepat</div>
    <div class="bantuan-grid">
        <a href="{{ route('panel.staf.kasir.index') }}" class="bantuan-card">
            <span class="bantuan-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"></rect><path d="M2 10h20"></path><path d="M6 15h4"></path></svg></span>
            <span><strong>Kasir POS</strong><span>Input pesanan langsung, cari produk, dan cetak struk transaksi.</span></span>
        </a>
        <a href="{{ route('panel.staf.pesanan.index') }}" class="bantuan-card">
            <span class="bantuan-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2h12v20l-3-2-3 2-3-2-3 2z"></path><path d="M9 7h6"></path><path d="M9 11h6"></path></svg></span>
            <span><strong>Antrean Pesanan</strong><span>Pantau status pesanan dan konfirmasi pembayaran pelanggan.</span></span>
        </a>
        <a href="{{ route('panel.staf.produksi.index') }}" class="bantuan-card">
            <span class="bantuan-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 2h10l-2 11a4 4 0 0 1-4 3h0a4 4 0 0 1-4-3L5 2z"></path><path d="M10 16v4"></path><path d="M7 20h10"></path></svg></span>
            <span><strong>Produksi</strong><span>Lihat antrean racik jus, mulai racik, dan tandai selesai.</span></span>
        </a>
        <a href="{{ route('panel.staf.stok.index') }}" class="bantuan-card">
            <span class="bantuan-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"></path><path d="m3.3 7 8.7 5 8.7-5"></path><path d="M12 22V12"></path></svg></span>
            <span><strong>Stok Bahan</strong><span>Catat pasokan masuk, pembuangan, dan audit stok harian.</span></span>
        </a>
        <a href="{{ route('panel.staf.aktivitas.index') }}" class="bantuan-card">
            <span class="bantuan-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 2"></path><circle cx="12" cy="12" r="9"></circle></svg></span>
            <span><strong>Riwayat Aktivitas</strong><span>Telusuri jejak aktivitas dan perubahan data yang tercatat.</span></span>
        </a>
        <a href="{{ route('panel.staf.profil.index') }}" class="bantuan-card">
            <span class="bantuan-card-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"></circle><path d="M4 21a8 8 0 0 1 16 0"></path></svg></span>
            <span><strong>Profil &amp; Akun</strong><span>Ubah foto profil dan kata sandi akun staf Anda.</span></span>
        </a>
    </div>

    <div class="bantuan-section-title">Pertanyaan umum</div>
    <div class="bantuan-faq" id="daftarFaq">
        <details open>
            <summary>Transaksi kasir gagal dicatat?</summary>
            <p>Pastikan koneksi internet stabil, stok bahan masih tersedia, dan metode pembayaran sudah dipilih. Jika tetap gagal, jangan ulangi berkali-kali—hubungi admin agar transaksi tidak dobel.</p>
        </details>
        <details>
            <summary>Stok bahan menipis atau habis?</summary>
            <p>Buka menu <strong>Stok</strong>, cek bahan yang berstatus kritis, lalu catat pasokan masuk. Untuk barang rusak, gunakan form <strong>Pembuangan</strong> agar tercatat di riwayat aktivitas.</p>
        </details>
        <details>
            <summary>Cara mengganti kata sandi?</summary>
            <p>Buka <strong>Profil</strong> lalu isi kata sandi lama dan kata sandi baru pada kartu <strong>Ubah Kata Sandi</strong>. Gunakan minimal 8 karakter.</p>
        </details>
        <details>
            <summary>Jam berapa shift staf berakhir?</summary>
            <p>Shift Pagi berlangsung <strong>07:00–16:00</strong>, Shift Sore <strong>16:00–22:00</strong>. Di luar jam tersebut panel menampilkan notifikasi <strong>Shift Selesai</strong>.</p>
        </details>
        <details>
            <summary>Notifikasi sudah dibaca tapi badge tetap muncul?</summary>
            <p>Tekan tombol <strong>Tandai dibaca</strong> pada dropdown notifikasi. Penanda dibaca disimpan di browser perangkat sehingga akan tetap tersembunyi hingga ada notifikasi baru.</p>
        </details>
    </div>

    <div class="bantuan-kontak">
        <div>
            <strong>Masih butuh bantuan?</strong>
            <p>Hubungi admin lewat WhatsApp untuk bantuan akun, data, atau kendala teknis.</p>
        </div>
        <a class="bantuan-wa" href="https://wa.me/6285807106570?text={{ rawurlencode('Halo Admin OMYU, saya butuh bantuan panel staf') }}" target="_blank" rel="noopener">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15l-1.3 4.8 4.9-1.3A10 10 0 1 0 12 2Zm5.3 14.1c-.2.7-1.3 1.3-1.8 1.4-.5.1-1 .1-1.7-.1-.4-.1-.9-.3-1.5-.6-2.7-1.2-4.4-4-4.5-4.2-.1-.2-1.1-1.4-1.1-2.7s.6-1.9.8-2.2c.2-.3.5-.4.7-.4h.5c.2 0 .4 0 .6.5l.8 1.9c.1.2.1.3 0 .5l-.4.6c-.2.2-.3.4-.1.7.2.3.8 1.3 1.7 2.1 1.2 1 2.1 1.4 2.4 1.5.2.1.4.1.6-.1l.8-.9c.2-.2.4-.2.6-.1l1.8.9c.3.1.5.2.5.4.1.1.1.6-.1 1.4Z"></path></svg>
            Hubungi Admin
        </a>
    </div>
</div>
@endsection

@section('scripts')
<script>
    (function () {
        const input = document.getElementById('cariBantuan');
        if (!input) return;
        input.addEventListener('input', function () {
            const q = this.value.trim().toLowerCase();
            document.querySelectorAll('#daftarFaq details').forEach(function (item) {
                item.hidden = q !== '' && !item.textContent.toLowerCase().includes(q);
            });
        });
    })();
</script>
@endsection

@extends('layouts.staf')

@section('title', 'Profil Staf')

@section('styles')
<style>
    .profil-page { display: grid; gap: 16px; max-width: 980px; margin: 0 auto; }
    .profil-heading h1 { font-size: 22px; font-weight: 900; color: #0f172a; }
    .profil-heading p { font-size: 12px; color: #64748b; margin-top: 3px; }
    .profil-hero { background: linear-gradient(135deg,#198754,#0f5132); color: #fff; border-radius: 18px; padding: 19px; display: flex; align-items: center; gap: 14px; }
    .profil-avatar { width: 58px; height: 58px; border-radius: 18px; background: rgba(255,255,255,.18); border: 1px solid rgba(255,255,255,.3); display: flex; align-items: center; justify-content: center; font-size: 23px; font-weight: 900; flex-shrink: 0; }
    .profil-hero h2 { font-size: 18px; font-weight: 900; }
    .profil-hero p { font-size: 12px; opacity: .85; margin-top: 3px; word-break: break-word; }
    .profil-pills { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 9px; }
    .profil-pill { padding: 4px 9px; border-radius: 999px; border: 1px solid rgba(255,255,255,.28); background: rgba(255,255,255,.16); font-size: 10px; font-weight: 900; text-transform: uppercase; }
    .profil-pill.off { background: rgba(239,68,68,.28); border-color: rgba(239,68,68,.45); }
    .profil-grid { display: grid; grid-template-columns: 1fr; gap: 14px; }
    .card { background: #fff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 16px; }
    .card h3 { font-size: 14px; font-weight: 900; color: #0f172a; }
    .card-sub { font-size: 11px; color: #64748b; margin-top: 3px; margin-bottom: 12px; }
    .baris { display: flex; justify-content: space-between; gap: 12px; padding: 10px 0; border-bottom: 1px dashed #eef2f7; font-size: 12px; }
    .baris:last-child { border-bottom: 0; padding-bottom: 0; }
    .baris span:first-child { color: #64748b; font-weight: 700; }
    .baris span:last-child { color: #0f172a; font-weight: 900; text-align: right; word-break: break-word; }
    .chip { display: inline-block; padding: 3px 9px; border-radius: 999px; font-size: 10px; font-weight: 900; background: #e0f2fe; color: #0369a1; }
    .chip.ok { background: #dcfce7; color: #15803d; }
    .chip.no { background: #fee2e2; color: #b91c1c; }
    .field { margin-bottom: 12px; }
    .field label { display: block; font-size: 11px; font-weight: 900; color: #334155; margin-bottom: 5px; }
    .field input { width: 100%; border: 1px solid #d7e4dc; border-radius: 10px; padding: 11px 12px; font-size: 13px; outline: none; font-family: inherit; }
    .field input:focus { border-color: #198754; box-shadow: 0 0 0 3px rgba(25,135,84,.12); }
    .btn { display: block; width: 100%; border: 0; border-radius: 11px; padding: 12px; background: #198754; color: #fff; font-size: 13px; font-weight: 900; cursor: pointer; }
    .btn-ghost { display: inline-block; width: auto; background: #f1f5f9; color: #334155; text-decoration: none; text-align: center; padding: 10px 14px; margin-top: 10px; }
    .errors { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; border-radius: 11px; padding: 11px 13px; font-size: 12px; }
    .errors ul { margin: 0; padding-left: 18px; }
    .catatan { font-size: 11px; color: #94a3b8; text-align: center; margin-top: 9px; }
    @media (min-width: 768px) { .profil-grid { grid-template-columns: 1fr 1fr; align-items: start; } }
</style>
@endsection

@section('content')
<div class="profil-page">
    <div class="profil-heading">
        <h1>Profil Saya</h1>
        <p>Kelola informasi akun staf dan perbarui kata sandi Anda.</p>
    </div>

    @if($errors->any())
        <div class="errors">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="profil-hero">
        <div class="profil-avatar">{{ strtoupper(substr($pengguna->nama, 0, 1)) }}</div>
        <div>
            <h2>{{ $pengguna->nama }}</h2>
            <p>{{ $pengguna->email }}</p>
            <div class="profil-pills">
                <span class="profil-pill">{{ ucfirst($pengguna->peran) }}</span>
                <span class="profil-pill {{ $pengguna->aktif ? '' : 'off' }}">{{ $pengguna->aktif ? 'Aktif' : 'Nonaktif' }}</span>
            </div>
        </div>
    </section>

    <div class="profil-grid">
        <section class="card">
            <h3>Data Akun</h3>
            <div class="card-sub">Informasi dasar akun yang digunakan saat ini</div>
            <div class="baris"><span>Nama Lengkap</span><span>{{ $pengguna->nama }}</span></div>
            <div class="baris"><span>Alamat Email</span><span>{{ $pengguna->email }}</span></div>
            <div class="baris"><span>Nomor Telepon</span><span>{{ $pengguna->telepon ?: 'Belum diisi' }}</span></div>
            <div class="baris"><span>Peran Akses</span><span><span class="chip">{{ strtoupper($pengguna->peran) }}</span></span></div>
            <div class="baris"><span>Status Akun</span><span><span class="chip {{ $pengguna->aktif ? 'ok' : 'no' }}">{{ $pengguna->aktif ? 'AKTIF' : 'NONAKTIF' }}</span></span></div>
            <div class="baris"><span>Terdaftar Sejak</span><span>{{ $pengguna->created_at?->format('d F Y') ?? '-' }}</span></div>
            <a href="{{ route('panel.staf.aktivitas.index') }}" class="btn btn-ghost">Lihat Riwayat Aktivitas</a>
        </section>

        <section class="card">
            <h3>Ganti Kata Sandi</h3>
            <div class="card-sub">Gunakan minimal 8 karakter agar akun tetap aman</div>
            <form method="POST" action="{{ route('panel.staf.profil.password') }}">
                @csrf
                <div class="field">
                    <label for="password_lama">Kata Sandi Saat Ini</label>
                    <input type="password" id="password_lama" name="password_lama" autocomplete="current-password" placeholder="Masukkan kata sandi saat ini" required>
                </div>
                <div class="field">
                    <label for="password">Kata Sandi Baru</label>
                    <input type="password" id="password" name="password" autocomplete="new-password" placeholder="Minimal 8 karakter" minlength="8" required>
                </div>
                <div class="field">
                    <label for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" placeholder="Ulangi kata sandi baru" minlength="8" required>
                </div>
                <button type="submit" class="btn">Perbarui Kata Sandi</button>
                <div class="catatan">Anda tetap masuk setelah kata sandi diperbarui.</div>
            </form>
        </section>
    </div>
</div>
@endsection

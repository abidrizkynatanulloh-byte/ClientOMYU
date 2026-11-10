@extends('layouts.staf')

@section('title', 'Profil & Keamanan Akun (K-01)')

@section('content')
    <div style="margin-bottom: 20px;">
        <h1 style="margin: 0; font-size: 22px;">Profil Saya & Keamanan Akun (K-01)</h1>
        <p style="margin: 4px 0 0; color: #64748b; font-size: 14px;">Kelola informasi akun staf Anda dan perbarui kata sandi secara berkala.</p>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; max-width: 900px;">
        <!-- Card Info Akun -->
        <div class="card">
            <h3 style="font-size: 15px; color: #0f172a; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                👤 Data Akun Staf
            </h3>

            <div style="display: flex; flex-direction: column; gap: 14px; font-size: 13px;">
                <div>
                    <span style="color: #64748b; font-size: 12px;">Nama Lengkap:</span>
                    <div style="font-weight: 700; font-size: 15px; margin-top: 2px;">{{ $pengguna->nama }}</div>
                </div>

                <div>
                    <span style="color: #64748b; font-size: 12px;">Alamat Email:</span>
                    <div style="font-weight: 600; margin-top: 2px;">{{ $pengguna->email }}</div>
                </div>

                <div>
                    <span style="color: #64748b; font-size: 12px;">Nomor Telepon:</span>
                    <div style="font-weight: 600; margin-top: 2px;">{{ $pengguna->telepon ?: 'Belum diisi' }}</div>
                </div>

                <div>
                    <span style="color: #64748b; font-size: 12px;">Peran Hak Akses:</span>
                    <div style="margin-top: 4px;">
                        <span class="badge" style="background: #e2e8f0; color: #334155; font-size: 12px; padding: 4px 10px;">
                            {{ strtoupper($pengguna->peran) }}
                        </span>
                    </div>
                </div>

                <div>
                    <span style="color: #64748b; font-size: 12px;">Status Akun:</span>
                    <div style="margin-top: 4px;">
                        <span class="badge {{ $pengguna->aktif ? 'badge-selesai' : 'badge-batal' }}">
                            {{ $pengguna->aktif ? 'AKTIF' : 'NONAKTIF' }}
                        </span>
                    </div>
                </div>

                <div>
                    <span style="color: #64748b; font-size: 12px;">Terdaftar Sejak:</span>
                    <div style="font-weight: 500; color: #475569; margin-top: 2px;">{{ $pengguna->created_at ? $pengguna->created_at->format('d F Y') : '-' }}</div>
                </div>
            </div>
        </div>

        <!-- Card Ganti Kata Sandi -->
        <div class="card">
            <h3 style="font-size: 15px; color: #0f172a; margin-bottom: 16px; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px;">
                🔒 Ganti Kata Sandi (Password)
            </h3>

            <form method="POST" action="{{ route('panel.staf.profil.password') }}">
                @csrf

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Kata Sandi Saat Ini:</label>
                    <input type="password" name="password_lama" required style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                </div>

                <div style="margin-bottom: 14px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Kata Sandi Baru (Min. 6 Karakter):</label>
                    <input type="password" name="password" required minlength="6" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 6px;">Konfirmasi Kata Sandi Baru:</label>
                    <input type="password" name="password_confirmation" required minlength="6" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px;">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px; font-size: 14px;">
                    ✓ Perbarui Kata Sandi
                </button>
            </form>
        </div>
    </div>
@endsection


# etoKos — Sistem Manajemen Kos-Kosan Modern Indonesia (DEMO)

**etoKos** adalah aplikasi web DEMO *Vertical SaaS Property Management System* yang dirancang khusus untuk pemilik kos, pengelola kos, dan penghuni kos di Indonesia. Seluruh antarmuka menggunakan **Bahasa Indonesia** dan mata uang **Rupiah (IDR)** dengan desain bersih, hangat, profesional, serta *mobile-first*.

---

## 🚀 Cara Menjalankan Aplikasi

Pastikan **Node.js** (v18+) dan **npm** telah terpasang di perangkat Anda.

```bash
# 1. Masuk ke direktori proyek
cd c:\laragon\www\ClientOMYU\ETOKOS

# 2. Instal seluruh dependensi
npm install

# 3. Jalankan server pengembangan (Development Server)
npm run dev
```

Buka browser dan kunjungi: **`http://localhost:3000`**

---

## 🎭 Tombol Peran Demo (1-Klik Role Switcher)

Untuk memudahkan eksplorasi tanpa backend sungguhan, tersedia **Halaman Login Demo (`/login`)** serta **Demo Top Bar** melayang di bagian atas layar:
- 👑 **Masuk sebagai Owner / Pengelola** (`/owner/dashboard`)
- 👤 **Masuk sebagai Penghuni** (`/penghuni` — *Budi Santoso, Kamar #102*)
- 🌐 **Masuk sebagai Pengunjung / Calon Penyewa** (`/` — *Listing Publik*)
- 🔄 **Reset Data Demo**: Mengembalikan seluruh perubahan `localStorage` ke data awal kapan saja.

---

## ✨ Panduan Menjalankan Alur "Wow" Terintegrasi

Aplikasi ini mendemonstrasikan siklus operasional ke finansial secara mulus (**Penghuni Lapor AC Rusak → Owner Tugaskan Teknisi → Selesai → Biaya Perbaikan Masuk Pengeluaran → Laba Rugi Berubah**):

### Cara 1: Simulasi Otomatis 1-Klik
1. Klik tombol **"⚡ Alur 'Wow': Lapor AC → Laba Rugi"** di bilah atas layar.
2. Klik **"Jalankan Alur Sekarang"** untuk mengeksekusi penugasan teknisi hingga pencatatan pengeluaran Rp 350.000 secara instan, lalu klik **"Buka Laporan Akuntansi Sekarang"**.

### Cara 2: Eksplorasi Langkah demi Langkah
1. **Lapor Keluhan (Penghuni)**:
   - Klik tombol **Penghuni** di bar atas, lalu buka menu **Lapor Keluhan** (`/penghuni/keluhan`).
   - Kirim laporan keluhan *"AC Kamar 102 Kurang Dingin & Bocor"* beserta foto.
2. **Tugaskan Teknisi (Owner)**:
   - Klik tombol **"Lanjut ke Portal Owner (Kanban)"** atau buka `/owner/keluhan`.
   - Pada tiket di kolom **Tiket Baru Masuk**, klik **"Tugaskan Teknisi"** dan pilih *Pak Joko (Spesialis AC & Listrik)*. Tiket berpindah ke kolom **Sedang Dikerjakan Teknisi**.
3. **Selesai & Catat Biaya**:
   - Klik **"Tandai Selesai & Biaya"** pada tiket tersebut, masukkan biaya perbaikan **Rp 350.000**, lalu simpan.
4. **Cek Akuntansi & Laba Rugi**:
   - Klik tombol **"Lihat Perubahan Laba Rugi"** (`/owner/akuntansi`).
   - Biaya perbaikan Rp 350.000 otomatis tercatat di tabel pengeluaran dengan badge *"Otomatis dari Tiket"*, dan nilai **Laba Bersih Operasional** langsung terhitung ulang!

---

## 📋 Daftar Fitur Lengkap

### 1. Halaman Publik (`/` & `/kos/[id]`)
- **Beranda Listing**: Daftar kos dengan foto, harga mulai/bulan, lokasi, jumlah kamar kosong, serta filter interaktif berdasarkan **Harga**, **Tipe Kos (Putra/Putri/Campur)**, dan **Fasilitas (AC, WiFi, KM Dalam)**.
- **Detail Kos**: Galeri foto interaktif, daftar fasilitas, peraturan kos, simulasi peta lokasi strategis, daftar seluruh kamar beserta status real-time, tombol **Chat WhatsApp**, dan modal formulir **"Ajukan Sewa Kamar"**.

### 2. Portal Owner / Pengelola (`/owner/*`)
1. **Dashboard (`/owner/dashboard`)**:
   - KPI Tingkat Okupansi (%), Pendapatan Bulan Ini, Tagihan Menunggak, Tiket Keluhan Terbuka, dan Kontrak Segera Habis.
   - Grafik **Recharts** perbandingan Pendapatan Kotor, Laba Operasional, dan Beban Pengeluaran 6 bulan terakhir.
   - Perbandingan performa antar-properti (*Multi-Property Switcher*).
2. **Kamar & Penghuni (`/owner/kamar`)**:
   - Denah kamar visual berwarna sesuai status (*Kosong/Tersedia, Terisi, Dipesan, Dalam Perbaikan*).
   - CRUD Kamar (Tambah kamar, ubah status, hapus kamar).
   - Inspector profil penghuni lengkap, masa kontrak sewa, kontak darurat, dan status uang jaminan (*Deposit*).
3. **Tagihan & Pembayaran (`/owner/tagihan`)**:
   - Tombol **Generate Tagihan Massal** bulanan otomatis dari kontrak aktif.
   - Rincian komponen tagihan (sewa kamar, WiFi, kebersihan, denda keterlambatan otomatis).
   - Modal **Invoice Resmi** yang dapat dicetak / disimpan sebagai PDF.
   - Verifikasi bukti bayar (*Approve / Tolak*), fitur **Catatan Koreksi Tagihan**, dan tombol **"Kirim Pengingat WhatsApp"**.
4. **Tiket Keluhan (`/owner/keluhan`)**:
   - Papan Kanban 3 kolom (*Baru, Diproses, Selesai*), badge prioritas, foto bukti, diskusi komentar, penugasan teknisi, dan integrasi biaya ke Akuntansi.
5. **Akuntansi & Keuangan (`/owner/akuntansi`)**:
   - Pemasukan otomatis dari tagihan lunas.
   - Pencatatan pengeluaran operasional per kategori.
   - Laporan **Laba Rugi** dan Arus Kas bulanan, lengkap dengan tombol **Ekspor CSV** dan **Cetak Laporan**.
6. **Pengaturan (`/owner/pengaturan`)**:
   - Konfigurasi profil properti, tarif utilitas, denda keterlambatan mingguan, rekening bank, template pengingat WhatsApp, dan penerbitan pengumuman penghuni.
7. **Log Aktivitas (`/owner/aktivitas`)**:
   - *Audit Trail* yang merekam setiap aksi penting beserta waktu dan pelakunya.

### 3. Portal Penghuni (`/penghuni/*`)
- Ringkasan kamar, masa kontrak aktif, dan saldo uang deposit jaminan.
- Daftar **Tagihan Saya** & riwayat pembayaran 6 bulan, lengkap dengan modal **Bayar & Unggah Bukti Transfer / QRIS**.
- Formulir **Lapor Keluhan** fasilitas kamar dengan lampiran foto dan pelacakan status teknisi.
- Papan **Pengumuman** resmi dari pengelola kos.

---

## 🛡️ Aturan Bisnis yang Diterapkan

1. **Proteksi Kamar Terisi**: Kamar yang berstatus `TERISI` tidak dapat dihapus (sistem akan menampilkan pesan proteksi aturan bisnis).
2. **Integritas Pembayaran**: Pembayaran dan tagihan tidak dapat dihapus, melainkan hanya dapat disesuaikan melalui **Catatan Koreksi** disertai alasan yang wajib diisi.
3. **Validasi Kalkulasi**: Seluruh perhitungan subtotal, denda, pemasukan, dan pengeluaran dihitung ulang di sisi logika dan dijamin tidak bernilai negatif.
4. **Rekam Jejak Audit**: Setiap tindakan krusial (buat tagihan, verifikasi pembayaran, koreksi nilai, laporan keluhan, hingga pengeluaran otomatis) tercatat di **Log Aktivitas**.

---

## 📦 Data Contoh (Mock Seed)

- **2 Properti**: *OMYU Living BSD* (Tangerang Selatan - 14 Kamar) & *Griya Asri Gejayan* (Yogyakarta - 10 Kamar).
- **24 Kamar** dengan variasi tipe (*Standard, Deluxe, VIP Suite*) di Lantai 1 & 2.
- **18 Penghuni Aktif** dengan profil realistis.
- **6 Bulan Riwayat Tagihan & Keuangan** (Januari – Juni 2026).
- **12 Tiket Keluhan** dengan berbagai kategori dan status.
- **Pengeluaran Operasional Realistis** (Internet fiber, token listrik umum, gaji kebersihan, perbaikan fasilitas).

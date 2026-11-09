```
 ██████╗ ███╗   ███╗██╗   ██╗██╗   ██╗
██╔═══██╗████╗ ████║╚██╗ ██╔╝██║   ██║
██║   ██║██╔████╔██║ ╚████╔╝ ██║   ██║
██║   ██║██║╚██╔╝██║  ╚██╔╝  ██║   ██║
╚██████╔╝██║ ╚═╝ ██║   ██║   ╚██████╔╝
 ╚═════╝ ╚═╝     ╚═╝   ╚═╝    ╚═════╝ 
```
# 💼 ClientOMYU
(sesuaikan) Satu kalimat singkat tentang apa yang dilakukan aplikasi ini.

`PHP` `Laravel` `MySQL` `Status: Development`

---

## 📋 Tentang Proyek

(sesuaikan) ClientOMYU adalah aplikasi web yang ... Jelaskan tujuan proyek, siapa penggunanya, dan masalah apa yang diselesaikan.

## ✨ Fitur

| Fitur | Deskripsi |
|---|---|
| 🔐 Login & Role | (sesuaikan) Autentikasi dan pembagian hak akses |
| 📦 Fitur 1 | (sesuaikan) Deskripsi singkat |
| 📝 Fitur 2 | (sesuaikan) Deskripsi singkat |
| 📊 Fitur 3 | (sesuaikan) Deskripsi singkat |

## 🛠️ Tech Stack

```
Frontend  → Blade · Tailwind/Bootstrap · JavaScript
Backend   → PHP (Laravel)
Database  → MySQL
```

(sesuaikan kalau stack-nya berbeda)

## 🚀 Cara Install (Pertama Kali)

### Prasyarat
- Laragon (atau XAMPP) sudah terinstall
- PHP >= 8.1
- Composer
- Git
- Sudah menerima undangan **collaborator** dari pemilik repo (cek email / notifikasi GitHub)

### Langkah-langkah

```bash
# 0. Atur identitas Git (sekali saja di laptop masing-masing)
#    Pakai email yang SAMA dengan akun GitHub supaya muncul di Contributors
git config --global user.name "nama_kamu"
git config --global user.email "email_github_kamu@gmail.com"

# 1. Clone repo (jalankan dari folder induk, misalnya C:\laragon\www)
cd C:\laragon\www
git clone https://github.com/abidrizkynatanulloh-byte/ClientOMYU.git
cd ClientOMYU

# 2. Install dependencies Laravel
composer install

# 3. Buat file konfigurasi
cp .env.example .env
php artisan key:generate
```

> ⚠️ Jangan `git clone` di dalam folder yang sudah bernama `ClientOMYU`, nanti error `destination path already exists`.

Buka file `.env`, sesuaikan bagian database:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clientomyu
DB_USERNAME=root
DB_PASSWORD=
```

```bash
# 4. Buat database "clientomyu" di phpMyAdmin (http://localhost/phpmyadmin)

# 5. Jalankan migration (buat semua tabel otomatis)
php artisan migrate

# (opsional) isi data contoh
php artisan migrate --seed

# 6. Jalankan aplikasi
php artisan serve
```

Buka browser → `http://localhost:8000` 🎉

(Kalau pakai Laragon dengan auto virtual host, bisa juga lewat `http://clientomyu.test`)

## 🔄 Workflow Sebelum Ngoding

Wajib dilakukan setiap kali mau mulai kerja!

```bash
# 1. Pindah ke main dan ambil update terbaru dari teman
git checkout main
git pull origin main

# 2. Buat branch baru untuk tugasmu
git checkout -b nama-fitur
```

> Kalau ada migration baru dari teman setelah `git pull`, jalankan `php artisan migrate` lagi biar tabel barunya ikut dibuat.
>
> Kalau ada package baru di `composer.json`, jalankan `composer install` lagi.

## 📤 Upload Perubahan

```bash
# 1. Cek file apa saja yang berubah
git status

# 2. Tambahkan file yang berubah
git add .

# 3. Tulis pesan commit yang jelas
git commit -m "feat: tambah fitur login"

# 4. Push ke branch kamu
git push -u origin nama-fitur
```

Lalu di GitHub:
1. Klik tombol **Compare & pull request**
2. Tulis ringkasan perubahan, klik **Create pull request**
3. Minta teman mengecek, lalu **Merge**
4. Kembali ke terminal: `git checkout main` lalu `git pull origin main`

> Nama kamu muncul di daftar **Contributors** setelah commit-mu masuk ke branch `main` (lewat merge Pull Request).

### Format Pesan Commit

| Awalan | Arti | Contoh |
|---|---|---|
| `feat:` | Tambah fitur | `feat: tambah halaman dashboard` |
| `fix:` | Perbaiki bug | `fix: perbaiki validasi form` |
| `docs:` | Dokumentasi | `docs: update README` |
| `style:` | Tampilan / format | `style: rapikan CSS navbar` |
| `refactor:` | Rapikan kode | `refactor: pisahkan fungsi helper` |

## 📁 Struktur Folder

(sesuaikan dengan struktur proyek yang sebenarnya)

```
$ tree
.
├── app
│   ├── Http
│   │   └── Controllers
│   └── Models
├── database
│   ├── migrations
│   └── seeders
├── resources
│   └── views
├── routes
│   └── web.php
├── .env.example
├── composer.json
└── README.md
```

## 🗂️ Struktur Database

(sesuaikan)

| Tabel | Isi |
|---|---|
| `users` | Data pengguna |
| `...` | ... |

## 🆘 Masalah Umum

| Masalah | Penyebab | Solusi |
|---|---|---|
| `fatal: not a git repository` | Folder belum jadi repo Git | Masuk ke folder hasil `git clone` |
| `destination path already exists` | Clone di dalam folder yang sama | Pindah ke folder induk dulu, lalu clone |
| `403 Permission denied` saat push | Belum jadi collaborator | Terima undangan dari pemilik repo |
| `nothing to commit` | Belum ada file yang berubah | Edit & simpan (Ctrl+S), cek `git status` |
| Push ditolak / `behind` | Ada update baru di GitHub | `git pull origin main` dulu |
| Merge conflict | Dua orang edit baris yang sama | Buka file konflik, pilih versi yang benar, hapus tanda `<<<<<<<`, lalu `git add` & `git commit` |
| Nama tidak muncul di Contributors | Email commit tidak cocok dengan GitHub | Samakan `git config user.email` dengan email di GitHub (Settings → Emails) |
| Error `.env` / `APP_KEY` | Belum generate key | `php artisan key:generate` |
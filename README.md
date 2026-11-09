# ClientOMYU

Repositori proyek **ClientOMYU**. Dokumen ini adalah panduan supaya semua anggota tim bisa menjalankan proyek dan berkontribusi tanpa bingung.

> Catatan: bagian bertanda `(sesuaikan)` perlu diisi/diubah sesuai kebutuhan proyek.

---

## Daftar Isi

1. [Gambaran Proyek](#gambaran-proyek)
2. [Kebutuhan Awal](#kebutuhan-awal)
3. [Cara Menjalankan Proyek di Komputer](#cara-menjalankan-proyek-di-komputer)
4. [Alur Kerja Tim (Git)](#alur-kerja-tim-git)
5. [Aturan Penulisan Commit](#aturan-penulisan-commit)
6. [Masalah Umum dan Solusinya](#masalah-umum-dan-solusinya)
7. [Anggota Tim](#anggota-tim)

---

## Gambaran Proyek

(sesuaikan) Tulis 2-3 kalimat tentang proyek ini: tujuannya, fitur utama, dan teknologi yang dipakai.

Contoh:
- Bahasa/Framework: PHP / HTML / CSS / JavaScript (sesuaikan)
- Database: MySQL (sesuaikan)
- Server lokal: Laragon

---

## Kebutuhan Awal

Pastikan sudah terpasang di komputer:

| Aplikasi | Fungsi |
|----------|--------|
| [Git](https://git-scm.com/downloads) | Mengelola versi kode |
| [Laragon](https://laragon.org/download/) | Server lokal (Apache/Nginx, PHP, MySQL) |
| [VS Code](https://code.visualstudio.com/) | Editor kode |
| Akun [GitHub](https://github.com/) | Untuk berkontribusi |

---

## Cara Menjalankan Proyek di Komputer

### 1. Terima undangan collaborator

Pemilik repo akan mengundang kamu. Buka email atau notifikasi GitHub, lalu klik **Accept invitation**. Tanpa ini kamu tidak bisa `push`.

### 2. Atur identitas Git (sekali saja)

Gunakan **email yang sama dengan akun GitHub-mu** supaya namamu muncul di daftar Contributors.

```bash
git config --global user.name "NamaKamu"
git config --global user.email "emailgithubmu@contoh.com"
```

### 3. Clone repositori

Buka terminal, pindah ke folder `www` milik Laragon, lalu clone:

```bash
cd C:\laragon\www
git clone https://github.com/abidrizkynatanulloh-byte/ClientOMYU.git
cd ClientOMYU
```

> Jangan menjalankan `git clone` di dalam folder yang sudah bernama `ClientOMYU`, karena akan error `destination path already exists`.

### 4. Siapkan database (jika ada)

(sesuaikan)
1. Nyalakan Laragon, klik **Start All**.
2. Buka phpMyAdmin / HeidiSQL, buat database baru, misalnya `clientomyu`.
3. Import file `.sql` dari proyek (jika tersedia).
4. Atur koneksi database di file konfigurasi (misalnya `config.php` atau `.env`).

### 5. Jalankan proyek

Buka di browser:

```
http://clientomyu.test
```

atau

```
http://localhost/ClientOMYU
```

(Laragon biasanya membuat alamat `.test` otomatis. Kalau tidak jalan, klik kanan ikon Laragon, lalu **Menu, Apache, Reload**.)

---

## Alur Kerja Tim (Git)

Aturan utama: **jangan langsung mengerjakan di `main`**. Buat branch sendiri untuk setiap tugas.

### Langkah setiap kali mulai mengerjakan tugas

**1. Ambil update terbaru dulu**

```bash
git checkout main
git pull origin main
```

**2. Buat branch baru**

```bash
git checkout -b nama-fitur
```

Contoh nama branch: `fitur-login`, `perbaikan-navbar`, `tambah-readme`.

**3. Kerjakan dan simpan perubahan**

```bash
git status
git add .
git commit -m "feat: tambah halaman login"
```

**4. Kirim ke GitHub**

```bash
git push origin nama-fitur
```

**5. Buat Pull Request (PR)**

1. Buka repo di GitHub.
2. Klik tombol **Compare & pull request**.
3. Tulis ringkasan perubahan, lalu klik **Create pull request**.
4. Minta teman satu tim mengecek, lalu **Merge** setelah disetujui.

**6. Setelah di-merge, sinkronkan lagi**

```bash
git checkout main
git pull origin main
```

> Contributor akan tercatat di halaman repo setelah commit masuk ke branch `main` (lewat merge PR).

### Ringkasan alur

```
pull main -> buat branch -> kerjakan -> commit -> push -> Pull Request -> merge -> pull main
```

---

## Aturan Penulisan Commit

Pakai pesan yang singkat dan jelas, diawali jenis perubahan:

| Awalan | Arti | Contoh |
|--------|------|--------|
| `feat:` | Menambah fitur | `feat: tambah form pendaftaran` |
| `fix:` | Memperbaiki bug | `fix: perbaiki tampilan di mobile` |
| `docs:` | Dokumentasi | `docs: update README` |
| `style:` | Perubahan tampilan/format | `style: rapikan CSS navbar` |
| `refactor:` | Merapikan kode | `refactor: pisahkan fungsi koneksi` |

---

## Masalah Umum dan Solusinya

| Masalah | Penyebab | Solusi |
|---------|----------|--------|
| `fatal: not a git repository` | Folder belum jadi repo Git | Masuk ke folder hasil `git clone`, atau jalankan `git init` lalu `git remote add origin <url>` |
| `destination path already exists` | Clone ke folder yang sudah ada | Pindah dulu ke folder induk (`cd C:\laragon\www`) lalu clone |
| `403 Permission denied` saat push | Belum jadi collaborator | Terima undangan, atau gunakan Fork + Pull Request |
| `nothing to commit` | Tidak ada file yang berubah | Edit dan simpan (Ctrl+S) dulu, cek dengan `git status` |
| `Your branch is behind` / push ditolak | Ada perubahan baru di GitHub | Jalankan `git pull origin main` dulu, lalu push lagi |
| Merge conflict | Dua orang mengubah baris yang sama | Buka file yang konflik, pilih versi yang benar, hapus tanda `<<<<<<<`, `=======`, `>>>>>>>`, lalu `git add` dan `git commit` |
| Nama tidak muncul di Contributors | Email commit tidak cocok dengan akun GitHub | Tambahkan email di GitHub (Settings, Emails) dan samakan dengan `git config user.email` |

---

## Anggota Tim

(sesuaikan)

| Nama | GitHub | Tugas |
|------|--------|-------|
| Abid Rizky Natanulloh | [@abidrizkynatanulloh-byte](https://github.com/abidrizkynatanulloh-byte) | Pemilik repo |
| ... | ... | ... |

---

Kalau ada yang kurang jelas, tanyakan di grup tim sebelum mengubah `main`. Selamat berkontribusi!
# Sistem Informasi Inventaris & Pengelolaan Barang

Starter project untuk kelompok:
- 1972029 — Gerry Audie B.
- 1972043 — Michael Marenden
- 2372069 — Allegra

Stack yang digunakan:
- Laravel 13 + PHP 8.4
- MySQL 8.4
- Docker Compose
- Apache di dalam container PHP
- Git + GitHub

> Paket ini menyiapkan fondasi aplikasi dan fitur inti. Setelah setup, aplikasi perlu dijalankan dan diuji pada komputer kelompok. Jangan menyebut fitur sebagai selesai sebelum benar-benar diuji.

## 1. Prasyarat

1. Install Docker Desktop dan pastikan Docker Engine berjalan.
2. Install Git.
3. Tidak perlu memasang PHP, Composer, MySQL, atau Apache secara lokal karena disediakan melalui container.
4. Koneksi internet diperlukan saat pertama kali membuat Laravel dan menarik image Docker.

## 2. Membuat proyek Laravel

Ekstrak ZIP ini, buka terminal di folder `inventaris-laravel-starter`, lalu jalankan salah satu:

**Windows PowerShell**

```powershell
Set-ExecutionPolicy -Scope Process Bypass
.\setup.ps1
```

**macOS / Linux**

```bash
chmod +x setup.sh
./setup.sh
```

Script akan:
1. Membuat aplikasi Laravel 13 ke folder `app/` menggunakan container Composer.
2. Menyalin kode inventaris, Dockerfile, migration, model, controller, service, route, dan tampilan ke aplikasi Laravel.
3. Membuat `app/.env` untuk koneksi MySQL di Docker.

Jangan menjalankan setup script lagi setelah mulai mengubah kode. Script hanya untuk scaffold awal.

## 3. Menjalankan Docker

Setelah setup selesai:

```bash
docker compose up -d --build
```

Tunggu service `db` berstatus `healthy` dan service `app` berstatus `healthy` saat menjalankan `docker compose ps`, lalu jalankan:

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

Buka:
- Aplikasi: http://localhost:8000
- phpMyAdmin: http://localhost:8081 (server `db`, user `inventaris_user`, password `inventaris_password`)

Login demo:

| Role | Email | Password |
|---|---|---|
| Admin | admin@inventaris.test | password |
| Petugas Gudang | gudang@inventaris.test | password |

Akun tersebut hanya untuk pengembangan/demo lokal. Ganti password dan jangan gunakan kredensial ini di server publik.

## 4. Container yang digunakan

| Service | Fungsi | Alamat |
|---|---|---|
| `app` | Laravel + PHP 8.4 + Apache | http://localhost:8000 |
| `db` | MySQL 8.4 | port host 3307 |
| `phpmyadmin` | Antarmuka database | http://localhost:8081 |

Port MySQL host menggunakan `3307` agar tidak bentrok dengan MySQL XAMPP yang biasanya memakai `3306`. Antarkontainer, Laravel tetap mengakses database melalui `DB_HOST=db` dan `DB_PORT=3306`.

Docker Compose menyediakan volume bernama `db_data` agar data MySQL tetap tersimpan saat container dihentikan.

## 5. Perintah Docker yang sering digunakan

```bash
# Melihat status container
docker compose ps

# Melihat log aplikasi
docker compose logs -f app

# Melihat log database
docker compose logs -f db

# Menjalankan migration setelah ada perubahan schema
docker compose exec app php artisan migrate

# Menghentikan container (data database tetap ada)
docker compose down

# Menyalakan kembali
docker compose up -d

# Menghapus container dan volume database (HATI-HATI: data database terhapus)
docker compose down -v
```

Karena folder `app/` di-mount ke container, perubahan file PHP/Blade dari komputer anggota langsung terbaca. Setelah mengubah `Dockerfile` atau dependency Composer, jalankan build ulang atau `composer install` sesuai kebutuhan.

## 6. Fitur yang sudah disiapkan dalam starter

- Login dan role Admin / Petugas Gudang.
- Dashboard ringkasan inventaris dan indikator stok minimum.
- CRUD kategori (Admin).
- CRUD supplier (Admin).
- CRUD barang (Admin), daftar barang dapat dilihat oleh pengguna yang login.
- Transaksi barang masuk dengan supplier.
- Transaksi barang keluar dengan validasi kecukupan stok.
- Penguncian baris database saat memperbarui stok untuk mengurangi risiko transaksi bersamaan.
- Riwayat barang masuk dan keluar dengan filter tanggal dan jenis transaksi.
- Export riwayat transaksi ke CSV.
- Migration dan seeder data demo.

Hal yang masih perlu disempurnakan dan diuji oleh kelompok:
- Filter laporan berdasarkan kategori barang (filter tanggal dan jenis transaksi pada riwayat sudah disiapkan).
- Export laporan ke PDF.
- Manajemen akun pengguna dari halaman Admin.
- Pengujian otomatis dan pengujian penerimaan pengguna.
- Validasi tampilan responsif dan keamanan sebelum deployment.
- Perbaikan UX berdasarkan hasil demo dan masukan dosen.

## 7. Alur Git untuk tiga anggota

Buat repository GitHub kosong, lalu dari folder utama:

```bash
git init
git add .
git commit -m "chore: initialize inventory management project"
git branch -M main
git remote add origin https://github.com/USERNAME/inventaris-barang.git
git push -u origin main
```

Saran kerja kolaborasi:

- `main`: versi stabil yang siap didemonstrasikan.
- `develop`: integrasi pekerjaan anggota.
- `feature/master-data`: kategori, supplier, dan barang.
- `feature/transactions`: barang masuk dan keluar.
- `feature/dashboard-report`: dashboard, monitoring, dan riwayat.

Setiap anggota:

```bash
git switch develop
git pull origin develop
git switch -c feature/nama-fitur
# edit dan uji kode

git add .
git commit -m "feat: add item management"
git push -u origin feature/nama-fitur
```

Buat Pull Request ke `develop`, minta anggota lain melakukan review, lalu merge setelah diuji. Jangan bekerja langsung di branch `main`.

Contoh commit yang baik:
- `feat: add supplier management`
- `fix: prevent negative stock on outgoing transaction`
- `docs: update ERD and setup guide`
- `test: add stock transaction tests`

## 8. Pembagian tugas awal (dapat disepakati ulang)

| Anggota | Fokus utama |
|---|---|
| Gerry Audie | Database, kategori, dan data barang |
| Michael Marenden | Supplier dan transaksi barang masuk/keluar |
| Allegra | Dashboard, monitoring stok, riwayat, dan laporan |

Login, integrasi, Docker, Git review, dan pengujian dikerjakan bersama. Pembagian ini adalah usulan awal, bukan bukti kontribusi yang sudah terjadi. Catat kontribusi aktual melalui commit dan Pull Request.

## 9. Catatan rancangan data

ERD awal dipertahankan dengan lima entitas inti: Kategori, Supplier, Barang, BarangMasuk, BarangKeluar. Perbaikan yang ditambahkan:
- `users` untuk autentikasi dan role.
- `location` dan `unit` pada Barang.
- `user_id` pada transaksi untuk jejak pelaku.
- Supplier wajib dipilih untuk transaksi barang masuk.
- Relasi transaksi menggunakan foreign key dan penghapusan data master dibatasi jika masih direferensikan.

Nama tabel implementasi Laravel menggunakan konvensi bahasa Inggris: `categories`, `suppliers`, `items`, `stock_ins`, `stock_outs`, dan `users`.

## 10. Troubleshooting awal

**Docker command tidak dikenali**: jalankan Docker Desktop, tunggu status Engine berjalan, kemudian buka terminal baru.

**Port 8000 / 8081 / 3307 bentrok**: ubah sisi kiri mapping `ports` pada `docker-compose.yml` (contoh `8001:80`) dan sesuaikan alamat browser.

**Laravel tidak dapat terhubung ke MySQL**: pastikan `DB_HOST=db`, bukan `localhost`; periksa `docker compose ps` dan `docker compose logs db`.

**Perubahan dependency Composer**: jalankan `docker compose exec app composer install`. Jika vendor bermasalah, hentikan container dan rebuild.

**Halaman error setelah setup**: jalankan `docker compose logs -f app`, periksa `.env`, lalu pastikan `key:generate` dan `migrate --seed` sudah dijalankan.

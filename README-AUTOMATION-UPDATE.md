# Sistem Informasi Inventaris & Pengelolaan Barang

Starter aplikasi kelompok berbasis Laravel 13, PHP 8.4, MySQL 8.4, Docker Compose, dan Git.

## Otomatisasi operasional pada patch terbaru

- Kode barang dibuat otomatis oleh server (`BRG-YYYYMMDD-xxxxxx`).
- Stok awal barang baru dimulai dari 0; penambahan stok dilakukan lewat transaksi barang masuk agar riwayat terjaga.
- Nomor transaksi dibuat otomatis (`IN-...` dan `OUT-...`).
- Tanggal transaksi otomatis menggunakan tanggal hari ini jika tanggal tidak diisi.
- Petugas transaksi diambil dari akun yang sedang login.
- Stok diperbarui atomik bersama riwayat transaksi; barang keluar ditolak jika stok tidak cukup.
- Saran restock dihitung otomatis dari `target_stock - stock`; Admin tetap memutuskan pembelian.
- Dashboard menampilkan barang yang mencapai batas minimum.

## Teknologi

- Laravel 13 + PHP 8.4
- MySQL 8.4
- Apache
- Docker + Docker Compose
- phpMyAdmin

## Menjalankan di Mac dan Windows

Salin `env.docker.example` ke `app/.env` (jangan pernah commit file `app/.env`).

macOS:

```bash
cp env.docker.example app/.env
chmod +x setup.sh
./setup.sh
```

Windows PowerShell:

```powershell
Copy-Item .\env.docker.example .\app\.env
.\setup.ps1
```

Jika Laravel sudah dibuat pada folder `app`, setup akan berhenti agar kode tidak tertimpa. Pastikan Dockerfile aplikasi memakai `FROM php:8.4-apache`.

Jalankan container:

```bash
docker compose up -d --build
```

Inisialisasi database pada instalasi baru:

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
```

Untuk update schema pada instalasi yang sudah berisi data, gunakan `php artisan migrate` tanpa `--seed`:

```bash
docker compose exec app php artisan migrate
```

Periksa service:

```bash
docker compose ps
```

Alamat lokal:

- Laravel: http://localhost:8000
- phpMyAdmin: http://localhost:8081
- MySQL dari host laptop: `127.0.0.1:3308` (port host; Laravel dalam Docker tetap menggunakan `db:3306`)

Kredensial database lokal contoh berada di `env.docker.example`. Ganti untuk lingkungan produksi dan jangan menyimpan rahasia di Git.

## Akun demo seeder

- Admin: `admin@inventaris.test` / `password`
- Petugas: `gudang@inventaris.test` / `password`

Ganti password demo sebelum aplikasi digunakan di lingkungan nyata.

## Pengembangan fitur V2/V3

Dokumen scope, aturan otomatisasi, roadmap, skenario pengujian, dan diagram berada di `docs/pengembangan-v2/` dan `docs/automation-v3/`.

Purchase Order, penerimaan PO, Stock Opname dengan approval, lokasi terstruktur, Audit Log, barcode, email, dan laporan terjadwal tetap merupakan modul lanjutan sampai source code-nya diimplementasikan dan diuji.

## Pengujian

```bash
docker compose exec app php artisan test
```

Jangan gunakan `docker compose down -v` kecuali memang bermaksud menghapus volume database.

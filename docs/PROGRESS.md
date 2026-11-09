# Progress UTS — baseline tanggal 9 November 2026

Status harus diperbarui berdasarkan hasil nyata di komputer kelompok. Kode yang disiapkan di ZIP belum berarti aplikasi sudah terpasang atau lolos pengujian.

## Kondisi awal dan pekerjaan yang disiapkan

| Bagian | Kondisi saat ini | Bukti yang harus dikumpulkan |
|---|---|---|
| Judul, latar belakang, gambaran sistem | Sudah ada di materi konsep | PDF konsep |
| Use Case, Activity, ERD, Class Diagram | Sudah ada rancangan awal; versi revisi disertakan dalam folder `docs/` | File diagram revisi |
| Pemilihan teknologi | Diputuskan: Laravel 13, PHP 8.3, MySQL 8.4, Docker Compose, GitHub | README dan Dockerfile |
| Starter code Laravel | Disiapkan, belum dijalankan pada komputer kelompok | Screenshot halaman login/dashboard |
| Database migrations dan seeder | Disiapkan, belum dieksekusi | Screenshot tabel dan data demo |
| Login dan role | Kode awal disiapkan, belum diuji | Login sebagai Admin dan Petugas |
| CRUD kategori, supplier, barang | Kode awal disiapkan, belum diuji | Demo tambah/edit/hapus |
| Transaksi barang masuk/keluar | Kode awal disiapkan, belum diuji | Bukti stok berubah dan validasi stok cukup |
| Dashboard dan monitoring stok minimum | Kode awal disiapkan, belum diuji | Screenshot dashboard |
| Riwayat transaksi, filter tanggal/jenis, export CSV | Kode awal disiapkan, belum diuji | Screenshot filter dan file CSV |
| GitHub dan kontribusi anggota | Repository belum dibuat | Link repository, commit, Pull Request |
| Docker | Dockerfile dan Compose disiapkan, belum dibuild | `docker compose ps` dan demo browser |
| Filter dan export laporan | Belum dikerjakan | Demo filter/export |
| Pengujian dan integrasi akhir | Belum dikerjakan | Hasil test dan checklist |

## Papan fitur untuk penghitungan progress

Gunakan 10 modul berikut sebagai hitungan fitur tingkat atas:

1. Login dan role pengguna.
2. Manajemen kategori.
3. Manajemen supplier.
4. Manajemen barang.
5. Transaksi barang masuk.
6. Transaksi barang keluar.
7. Dashboard dan monitoring stok.
8. Peringatan stok minimum.
9. Riwayat transaksi dan filter.
10. Laporan/export CSV.

Status implementasi aktual per tanggal dokumen ini dibuat: **0 fitur telah diverifikasi selesai, 0 fitur sedang diverifikasi oleh tim, dan 10 fitur masih harus dijalankan/diuji**. Starter code telah disiapkan sebagai fondasi, tetapi belum diuji runtime karena belum dijalankan di komputer kelompok.

## Milestone pengerjaan

| Milestone | Hasil yang harus ada | Kriteria selesai |
|---|---|---|
| 1. Setup | Laravel dapat dibuka, MySQL terhubung, Docker berjalan | Login tampil; migration sukses; container sehat |
| 2. Data master | Kategori, supplier, barang | CRUD dan validasi berfungsi |
| 3. Transaksi | Barang masuk dan keluar | Stok dan riwayat berubah secara benar; stok negatif ditolak |
| 4. Monitoring | Dashboard dan stok minimum | Barang kritis terlihat pada dashboard |
| 5. Laporan | Riwayat, filter tanggal, export | Hasil filter dan file export sesuai data |
| 6. Finalisasi | Pengujian, perbaikan, dokumentasi, presentasi | Semua alur utama didemokan dan repository rapi |


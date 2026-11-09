# Spesifikasi Sistem — Sistem Informasi Inventaris & Pengelolaan Barang

## Tujuan

Membangun aplikasi web untuk mengelola data barang, kategori, supplier, transaksi stok masuk/keluar, monitoring batas stok minimum, dan riwayat pergerakan barang.

## Aktor dan hak akses pada implementasi saat ini

| Fitur | Admin | Petugas Gudang |
|---|---:|---:|
| Login/logout | Ya | Ya |
| Melihat dashboard | Ya | Ya |
| Melihat data barang, kategori, supplier | Ya | Ya |
| Tambah/edit/hapus kategori | Ya | Tidak |
| Tambah/edit/hapus supplier | Ya | Tidak |
| Tambah/edit/hapus barang | Ya | Tidak |
| Mencatat barang masuk | Ya | Ya |
| Mencatat barang keluar | Ya | Ya |
| Melihat riwayat transaksi | Ya | Ya |
| Filter riwayat berdasarkan tanggal dan jenis | Ya | Ya |
| Ekspor riwayat ke CSV | Ya | Ya |
| Mengelola akun pengguna melalui UI | Belum tersedia | Tidak |

Pembatasan CRUD master data dilakukan melalui pemeriksaan `isAdmin()` pada controller. Akses ke dashboard, daftar master data, transaksi, riwayat, dan ekspor saat ini hanya mensyaratkan autentikasi.

## Entitas implementasi

- `users`: nama, email, password, role, serta kolom autentikasi Laravel.
- `categories`: nama dan deskripsi.
- `suppliers`: nama, contact person, telepon, email, dan alamat.
- `items`: kode unik, nama, kategori, lokasi, satuan, stok, dan stok minimum.
- `stock_ins`: barang, supplier, pengguna pencatat, tanggal, jumlah, dan catatan opsional.
- `stock_outs`: barang, pengguna pencatat, tanggal, jumlah, dan keterangan.

## Aturan bisnis

1. Kode barang harus unik.
2. Satu barang terhubung ke satu kategori.
3. Satu kategori dapat memiliki banyak barang.
4. Barang masuk harus memiliki barang, supplier, jumlah minimal 1, tanggal, dan pengguna pencatat.
5. Barang keluar harus memiliki barang, jumlah minimal 1, tanggal, keterangan, dan pengguna pencatat.
6. Stok tidak boleh berkurang melebihi jumlah yang tersedia.
7. Pengubahan stok dan pencatatan transaksi dilakukan dalam satu database transaction.
8. Baris barang dikunci dengan `lockForUpdate()` selama pemrosesan transaksi untuk mengurangi risiko dua transaksi bersamaan membaca stok yang sama.
9. Status stok minimum ditentukan dengan `stock <= minimum_stock`.
10. Data kategori, supplier, dan barang yang masih dipakai transaksi/relasi tidak dapat dihapus melalui alur aplikasi.
11. Jika pengguna dihapus, foreign key pencatat transaksi dibuat `NULL` sesuai aturan migration.

## Batasan versi saat ini

- Satu record transaksi mengacu pada satu jenis barang.
- Lokasi disimpan sebagai teks pada kolom `items.location`, belum menjadi master tabel lokasi.
- Belum ada multi-gudang, approval berjenjang, barcode, purchase order, pengelolaan akun pengguna melalui UI, atau notifikasi email/WhatsApp.
- Ekspor laporan yang tersedia adalah CSV, bukan PDF.

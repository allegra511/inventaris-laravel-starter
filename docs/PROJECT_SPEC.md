# Spesifikasi sistem — versi pengembangan

## Tujuan
Membangun aplikasi inventaris berbasis web untuk mengelola data barang, kategori, supplier, transaksi stok masuk/keluar, monitoring batas stok minimum, dan riwayat pergerakan barang.

## Aktor dan hak akses

| Fitur | Admin | Petugas Gudang |
|---|---:|---:|
| Login/logout | Ya | Ya |
| Lihat dashboard dan monitoring stok | Ya | Ya |
| Lihat data barang, kategori, supplier | Ya | Ya |
| Tambah/edit/hapus kategori | Ya | Tidak |
| Tambah/edit/hapus supplier | Ya | Tidak |
| Tambah/edit/hapus barang | Ya | Tidak |
| Catat barang masuk | Ya | Ya |
| Catat barang keluar | Ya | Ya |
| Lihat riwayat transaksi | Ya | Ya |
| Laporan dengan filter dan export | Ya | Dapat ditetapkan kemudian |
| Kelola akun pengguna | Rencana lanjutan | Tidak |

## Data dan aturan bisnis

1. Kode barang harus unik.
2. Setiap barang terhubung ke satu kategori.
3. Satu kategori dapat memiliki banyak barang.
4. Barang masuk harus menyebutkan barang, supplier, jumlah positif, tanggal, dan pengguna pencatat.
5. Barang keluar harus menyebutkan barang, jumlah positif, tanggal, keterangan, dan pengguna pencatat.
6. Stok tidak boleh negatif. Validasi dilakukan di server, bukan hanya pada tampilan.
7. Update stok dan penyimpanan transaksi harus dilakukan dalam satu database transaction.
8. Penghapusan barang/supplier/kategori ditolak jika masih memiliki relasi transaksi atau barang.
9. Status stok kritis ditentukan dengan `stock <= minimum_stock`.
10. Lokasi penyimpanan dan satuan disimpan pada data barang.

## Entitas implementasi

- `users`: autentikasi, nama, email, role.
- `categories`: nama dan deskripsi.
- `suppliers`: nama, kontak, telepon, email, alamat.
- `items`: kode, nama, kategori, lokasi, satuan, stok, stok minimum.
- `stock_ins`: barang, supplier, user, tanggal, jumlah, catatan.
- `stock_outs`: barang, user, tanggal, jumlah, catatan.

## Batasan versi awal

- Satu baris transaksi mencatat satu jenis barang.
- Belum ada multi-gudang, approval berjenjang, barcode, purchase order, atau notifikasi email/WhatsApp.
- Laporan awal menampilkan riwayat transaksi; filter/export menjadi pekerjaan lanjutan.

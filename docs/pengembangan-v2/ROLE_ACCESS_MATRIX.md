# Matriks hak akses target

| Modul/aksi | Admin | Petugas Gudang |
|---|---:|---:|
| Login/logout | Ya | Ya |
| Lihat dashboard | Ya | Ya |
| Lihat barang/kategori/supplier/lokasi | Ya | Ya |
| Kelola kategori | Ya | Tidak |
| Kelola supplier | Ya | Tidak |
| Kelola barang | Ya | Tidak |
| Kelola lokasi | Ya | Tidak |
| Catat barang masuk langsung | Ya | Ya |
| Catat barang keluar | Ya | Ya |
| Lihat riwayat transaksi | Ya | Ya |
| Export laporan | Ya | Sesuai kebijakan kelompok (default: tidak) |
| Buat purchase order | Ya | Tidak |
| Lihat purchase order | Ya | Ya (read-only) |
| Catat penerimaan PO | Ya | Ya |
| Buat sesi stock opname | Ya | Ya |
| Input hitung fisik | Ya | Ya |
| Setujui hasil stock opname | Ya | Tidak |
| Kelola akun dan role | Ya | Tidak |
| Lihat audit log | Ya | Tidak |

## Catatan implementasi
Matriks ini adalah kebijakan target V2. Kode starter saat ini hanya memiliki pemeriksaan Admin pada operasi CRUD master tertentu; belum semua baris matriks ini dibuat sebagai policy/middleware. Sebelum rilis, gunakan middleware/policy yang eksplisit dan test untuk setiap role.

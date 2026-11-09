# Aturan bisnis otomatis sistem

1. Kode barang dibuat server-side setelah ID item tersedia; kode tidak boleh dikirim dari browser.
2. Stok awal barang baru adalah 0. Stok awal harus masuk melalui transaksi barang masuk agar jejak stok tersedia.
3. Nomor transaksi barang masuk/keluar dibuat server-side dan memiliki unique index database.
4. Tanggal transaksi default adalah tanggal server saat ini bila pengguna tidak mengirim tanggal.
5. `user_id` transaksi selalu diambil dari pengguna terautentikasi, bukan dari form.
6. Kuantitas transaksi harus integer positif.
7. Update stok dan insert riwayat berada pada satu database transaction.
8. Baris item dikunci selama perubahan stok agar permintaan bersamaan tidak menghasilkan stok negatif.
9. Barang keluar ditolak jika kuantitas lebih besar dari stok tersedia.
10. Peringatan kritis aktif jika `stock <= minimum_stock`.
11. Jumlah restock yang disarankan dihitung `max(target_stock - stock, 0)`.
12. Saran restock tidak otomatis membuat pesanan ke supplier.
13. Stock opname menghitung selisih, tetapi tidak menyesuaikan stok sebelum approval Admin.
14. Penerimaan PO meningkatkan stok hanya sebesar kuantitas yang dinyatakan diterima dan belum pernah diproses.
15. Semua fitur yang mengubah stok wajib mempunyai test untuk alur sukses dan gagal.

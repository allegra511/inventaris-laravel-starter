# Matriks otomatisasi sistem inventaris

Dokumen ini memisahkan otomatisasi yang dimasukkan pada patch ini dengan otomatisasi yang masih menjadi target modul lanjutan.

## Otomatisasi pada patch ini (kode disediakan; wajib diuji setelah dipasang)

| Proses | Input pengguna | Otomatis oleh sistem | Aturan |
|---|---|---|---|
| Kode barang | Nama, kategori, satuan, stok minimum, target | Kode `BRG-YYYYMMDD-xxxxxx` | ID database menjadi suffix unik; kode tidak diketik |
| Stok awal | Data master barang | Stok awal selalu 0 | Penambahan stok pertama dicatat sebagai barang masuk |
| Nomor barang masuk | Barang, supplier, jumlah, catatan | Nomor `IN-YYYYMMDD-xxxxxx` | Dibuat sesudah record tersimpan dalam transaksi DB |
| Nomor barang keluar | Barang, jumlah, alasan | Nomor `OUT-YYYYMMDD-xxxxxx` | Dibuat sesudah record tersimpan dalam transaksi DB |
| Tanggal transaksi | Opsional mengubah tanggal | Default tanggal hari ini | Server juga memberi fallback `now()` jika tanggal kosong |
| Petugas pencatat | Tidak perlu input | ID user diambil dari sesi login | Tidak menerima `user_id` dari form |
| Perubahan stok | Jenis transaksi dan kuantitas | Stok bertambah/berkurang | DB transaction + row lock; stok tidak boleh negatif |
| Saran restock | Stok minimum dan target stok diset saat master barang | Jumlah saran = target stok - stok saat ini | Hanya menghitung saran; pembelian tetap keputusan Admin |
| Peringatan stok kritis | Tidak perlu input | Dashboard mencari stock <= minimum_stock | Status dihitung dari data terbaru |
| Ekspor CSV | Filter opsional | Nama file berwaktu dan BOM UTF-8 | Baris laporan mengikuti filter |

## Otomatisasi yang aman tetapi memerlukan konfirmasi manusia

1. **Restock/pengadaan:** sistem menyarankan jumlah pembelian dan supplier; Admin tetap memeriksa dan menyetujui Purchase Order.
2. **Stock opname:** sistem menghitung selisih fisik dengan sistem; stok baru disesuaikan setelah pengesahan Admin.
3. **Penerimaan Purchase Order:** stok ditambah hanya sesuai kuantitas fisik yang diterima; petugas harus mengonfirmasi jumlah diterima.
4. **Notifikasi:** sistem dapat menampilkan peringatan stok minimum; pengiriman email/WhatsApp memerlukan konfigurasi kanal dan kredensial resmi.

## Tidak boleh diotomatisasi tanpa kontrol

- Jangan mengurangi/menambah stok hanya karena perkiraan atau rekomendasi.
- Jangan membuat Purchase Order dan mengirimkannya ke supplier tanpa persetujuan Admin.
- Jangan menimpa hitungan stok fisik tanpa menyimpan stok sistem, selisih, alasan, petugas, dan persetujuan.
- Jangan menyimpan password, token, APP_KEY atau kredensial di audit log.

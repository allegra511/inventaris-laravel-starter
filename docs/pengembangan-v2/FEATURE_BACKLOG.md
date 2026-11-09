# Backlog dan prioritas pengembangan

## Definisi status
- **Fondasi tersedia:** ada source code awal tetapi belum tentu diuji oleh kelompok.
- **Perlu diverifikasi:** harus dicoba di lingkungan Docker kelompok.
- **Direncanakan V2:** belum tersedia sebagai fitur lengkap.
- **Opsional:** dikerjakan hanya jika modul inti selesai.

## Backlog terurut

| ID | Epic | User story | Prioritas | Kriteria penerimaan ringkas |
|---|---|---|---|---|
| INV-01 | Setup | Sebagai anggota, saya dapat menjalankan aplikasi dengan Docker | P0 | app sehat, MySQL sehat, halaman login terbuka di Mac dan Windows |
| INV-02 | Auth | Admin/Petugas dapat login dan logout | P0 | kredensial valid diterima; invalid ditolak; session diregenerasi |
| INV-03 | Kategori | Admin dapat mengelola kategori | P0 | validasi nama unik dan relasi barang dijaga |
| INV-04 | Supplier | Admin dapat mengelola supplier | P0 | data kontak tervalidasi |
| INV-05 | Barang | Admin dapat mengelola barang | P0 | kode unik; kategori wajib; stok dan stok minimum tidak negatif |
| INV-06 | Barang masuk | Petugas dapat menambah stok | P0 | stok bertambah dan transaksi tersimpan atomik |
| INV-07 | Barang keluar | Petugas dapat mengurangi stok | P0 | stok cukup; jika tidak cukup stok tidak berubah |
| INV-08 | Dashboard | Pengguna dapat melihat status stok | P0 | jumlah total dan barang kritis sesuai database |
| INV-09 | Riwayat | Pengguna dapat menelusuri transaksi | P0 | tipe/periode filter konsisten dengan sumber data |
| INV-10 | Export | Pengguna berwenang dapat mengunduh CSV | P1 | kolom dan baris mengikuti filter |
| INV-11 | Lokasi | Admin mengelola lokasi penyimpanan | P1 | lokasi memiliki kode/nama unik; barang dapat dipetakan ke lokasi |
| INV-12 | Stock opname | Petugas mencatat hasil hitung fisik | P1 | sistem menyimpan stok sistem, fisik, selisih dan alasan |
| INV-13 | Approval opname | Admin mengesahkan hasil opname | P1 | stok baru disesuaikan setelah pengesahan; hasil terkunci |
| INV-14 | Purchase order | Admin membuat PO supplier | P1 | nomor PO unik, minimal satu item, kuantitas positif |
| INV-15 | Penerimaan PO | Petugas menerima PO sebagian/penuh | P1 | stok bertambah hanya sejumlah diterima; tidak double count |
| INV-16 | Audit log | Sistem menyimpan aktivitas penting | P2 | aktor, aksi, entitas, id, waktu tercatat tanpa data rahasia |
| INV-17 | Laporan | Admin melihat ringkasan stok dan transaksi | P2 | filter periode/kategori/lokasi/supplier berfungsi |
| INV-18 | Import CSV | Admin mengimpor data barang | P3 | validasi per baris, tampilkan error, tidak menyimpan baris invalid |
| INV-19 | Barcode | Petugas mencari barang dengan kode | P3 | kode unik dapat dicari; fallback pencarian manual tersedia |

## Definisi selesai (Definition of Done)
1. Kode mengikuti struktur Laravel yang disepakati.
2. Validasi server-side tersedia.
3. Kebijakan akses role diuji.
4. Migration memiliki `up()` dan `down()` yang benar.
5. Test feature untuk alur normal dan kondisi gagal tersedia.
6. Tidak ada credential atau file `.env` di commit.
7. Perubahan masuk melalui branch dan Pull Request.
8. README atau dokumentasi terkait diperbarui.
9. Fitur benar-benar diuji pada Docker, bukan hanya lolos pemeriksaan sintaks.

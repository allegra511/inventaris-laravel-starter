# Skenario pengujian yang disarankan

## MVP — wajib diuji sebelum demo UTS

| ID | Skenario | Hasil yang diharapkan |
|---|---|---|
| T-01 | Login dengan akun valid | Masuk ke dashboard sesuai role |
| T-02 | Login dengan password salah | Ditolak dan pesan validasi muncul |
| T-03 | Petugas membuka form CRUD master yang khusus Admin | Akses ditolak |
| T-04 | Admin membuat kode barang duplikat | Validasi menolak |
| T-05 | Barang masuk 10 unit | Stok naik tepat 10 dan satu baris stock_in tersimpan |
| T-06 | Barang keluar 3 unit dari stok cukup | Stok turun tepat 3 dan satu baris stock_out tersimpan |
| T-07 | Barang keluar melebihi stok | Ditolak; stok tidak berubah; stock_out tidak tercatat |
| T-08 | Dua pengeluaran bersamaan | Stok tidak menjadi negatif; transaksi kedua divalidasi ulang |
| T-09 | Stok sama dengan batas minimum | Ditandai kritis sesuai aturan `<=` |
| T-10 | Filter riwayat berdasarkan tanggal | Hanya transaksi dalam rentang tersebut yang muncul |
| T-11 | Export CSV setelah filter | Isi CSV sama dengan hasil filter |
| T-12 | Restart container | Data database tetap ada karena volume persisten |

## Ekstensi V2 — diuji setelah fitur dibangun

| ID | Skenario | Hasil yang diharapkan |
|---|---|---|
| V2-01 | Buat PO dengan dua item | Header dan dua detail tersimpan |
| V2-02 | Terima sebagian jumlah PO | Stok bertambah sesuai jumlah diterima; status partially_received |
| V2-03 | Terima sisa PO | Stok bertambah hanya untuk sisanya; status received |
| V2-04 | Terima melebihi sisa PO | Ditolak dan stok tidak berubah |
| V2-05 | Input stock opname dengan selisih | Selisih dihitung dan alasan diminta |
| V2-06 | Petugas mencoba menyetujui opname | Akses ditolak |
| V2-07 | Admin menyetujui opname | Stok disesuaikan sekali, sesi dikunci, audit log dibuat |
| V2-08 | Edit hasil opname yang sudah disetujui | Ditolak; koreksi harus melalui sesi/penyesuaian baru |

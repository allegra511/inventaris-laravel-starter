# Diagram yang disesuaikan untuk implementasi Laravel

Sumber diagram dapat dibuka terpisah pada file `.mmd` di folder ini menggunakan Mermaid Live Editor atau ekstensi Mermaid di editor.

## Perubahan dari rancangan awal

- Menambahkan `User` karena sistem memiliki login dan role Admin/Petugas Gudang.
- Menambahkan `location` dan `unit` pada barang.
- Menambahkan `user_id` pada transaksi agar pencatat transaksi dapat ditelusuri.
- Mempertahankan lima entitas inti awal.
- Pada kode Laravel, `Transaksi` adalah abstract service; `BarangMasukService` dan `BarangKeluarService` menjalankan logika stok. Model `BarangMasuk` dan `BarangKeluar` menyimpan riwayat ke database.
- Diagram Use Case memisahkan CRUD master data untuk Admin, sementara kedua aktor dapat melakukan transaksi dan monitoring.

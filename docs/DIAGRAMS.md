# Dokumen Diagram Sistem Inventaris dan Pengelolaan Barang

**Versi:** Revisi untuk implementasi Laravel + MySQL  
**Cakupan:** Use Case, dua Activity Diagram, ERD, dan Class Diagram.

File `.mmd` di folder ini adalah sumber diagram Mermaid yang dapat diedit. Buka file di Visual Studio Code dengan ekstensi Mermaid, atau salin isinya ke Mermaid Live Editor.

## 1. Use Case Diagram — `use-case.mmd`

Aktor sistem:

- **Admin:** login/logout, melihat dashboard dan data master, melakukan CRUD kategori/supplier/barang, mencatat transaksi masuk/keluar, memonitor stok, melihat/filter riwayat, dan mengekspor CSV.
- **Petugas Gudang:** login/logout, melihat dashboard dan data master, mencatat transaksi masuk/keluar, memonitor stok, melihat/filter riwayat, dan mengekspor CSV.

Pembatasan hak akses ini mengikuti kode saat ini: method create/store/edit/update/destroy pada controller master data memeriksa `isAdmin()`, sedangkan halaman index, transaksi, riwayat, dan ekspor berada di dalam middleware `auth` tanpa pembatasan role tambahan.

## 2. Activity Diagram — barang masuk dan barang keluar

- `activity-barang-masuk.mmd` menggambarkan validasi input, penguncian baris barang (`lockForUpdate`), penambahan stok, penyimpanan record `stock_ins`, dan commit transaksi database.
- `activity-barang-keluar.mmd` menggambarkan validasi input, penguncian baris barang, pemeriksaan stok, pengurangan stok, penyimpanan record `stock_outs`, serta rollback otomatis ketika exception terjadi.

Kedua alur memerlukan pengguna yang sudah login. Jumlah harus berupa bilangan bulat minimal 1.

## 3. ERD — `erd.mmd`

ERD mengikuti enam tabel aplikasi:

1. `users`
2. `categories`
3. `suppliers`
4. `items`
5. `stock_ins`
6. `stock_outs`

Relasi penting:

- Satu kategori dapat memiliki banyak barang.
- Satu barang dapat memiliki banyak transaksi masuk dan transaksi keluar.
- Satu supplier dapat memasok banyak transaksi barang masuk.
- Satu pengguna dapat mencatat banyak transaksi masuk dan keluar.

Kolom `user_id` pada `stock_ins` dan `stock_outs` nullable serta menggunakan `ON DELETE SET NULL`. Foreign key barang/kategori/supplier menggunakan pembatasan penghapusan sesuai migration.

`location` disimpan sebagai kolom pada `items`, bukan tabel lokasi terpisah. Satuan barang disimpan pada kolom `unit`.

## 4. Class Diagram — `class-diagram.mmd`

Diagram memisahkan:

- **Eloquent Model:** `User`, `Kategori`, `Supplier`, `Barang`, `BarangMasuk`, dan `BarangKeluar`.
- **Service transaksi:** abstract class `Transaksi`, serta `BarangMasukService` dan `BarangKeluarService` yang mewarisinya.

Penting: yang mewarisi abstract class `Transaksi` adalah service transaksi, bukan model `BarangMasuk` dan `BarangKeluar`. Kedua model tersebut adalah Eloquent Model yang menyimpan riwayat transaksi ke tabel `stock_ins` dan `stock_outs`.

## Aturan konsistensi saat presentasi

- Gunakan nama tabel database pada ERD dan nama class PHP pada Class Diagram.
- Jangan menyatakan fitur kelola akun pengguna, multi-gudang, barcode, purchase order, atau notifikasi email/WhatsApp sudah tersedia; fitur-fitur itu belum termasuk cakupan source code ini.
- Jangan menyebut laporan PDF; implementasi ekspor yang tersedia adalah CSV.
- Use Case mengikuti pembatasan role yang benar-benar tertulis di controller dan route saat ini.
- Perbarui diagram lagi apabila kode, migration, atau aturan hak akses berubah setelah revisi ini.

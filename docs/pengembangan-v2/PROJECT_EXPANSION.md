# Pengembangan V2 — Sistem Informasi Inventaris & Pengelolaan Barang

## 1. Visi produk
Membangun aplikasi inventaris berbasis web untuk mencatat, mengawasi, dan menelusuri siklus hidup persediaan barang: mulai dari pengelolaan master barang dan pemasok, penerimaan dan pengeluaran barang, pemantauan stok minimum, pengadaan ulang, stock opname, hingga pelaporan dan audit aktivitas.

## 2. Masalah yang ingin diselesaikan
1. Pencatatan manual menyebabkan perbedaan antara stok fisik dan stok pada sistem.
2. Barang dapat habis tanpa peringatan sehingga kegiatan operasional terganggu.
3. Riwayat barang masuk dan keluar sulit ditelusuri.
4. Proses pembelian ulang belum terhubung secara sistematis dengan supplier dan kondisi stok.
5. Selisih stok fisik dengan catatan sistem sulit ditelusuri penyebab dan petugas pencatatnya.
6. Lokasi penyimpanan yang hanya berupa teks bebas membuat penempatan barang tidak konsisten.

## 3. Pengguna dan peran

### Admin
Mengelola master data, akun pengguna, pengadaan, laporan, audit aktivitas, dan konfigurasi inventaris.

### Petugas Gudang
Melihat data inventaris, mencatat penerimaan dan pengeluaran barang, menjalankan stock opname, serta memantau stok.

> Peran tetap dua aktor seperti rancangan awal. Tanggung jawab pengadaan dapat dilakukan Admin; jika dosen/kelompok menginginkan aktor bagian Purchasing terpisah, perubahan aktor harus disepakati dan diikuti implementasi role.

## 4. Ruang lingkup fitur

### A. MVP / fitur inti yang sudah memiliki fondasi source code
Status berarti kode tersedia di starter project, bukan otomatis sudah lolos uji runtime.

| Modul | Kemampuan | Status awal |
|---|---|---|
| Autentikasi | Login, logout, role Admin/Petugas | Source code tersedia; perlu diuji |
| Dashboard | Ringkasan jumlah barang dan indikator stok minimum | Source code tersedia; perlu diuji |
| Kategori | Tambah, lihat, ubah, hapus (Admin) | Source code tersedia; perlu diuji |
| Supplier | Tambah, lihat, ubah, hapus (Admin) | Source code tersedia; perlu diuji |
| Barang | Kode unik, nama, kategori, lokasi teks, satuan, stok, batas minimum | Source code tersedia; perlu diuji |
| Barang masuk | Catat barang, supplier, jumlah, tanggal, pencatat; tambah stok dalam transaksi DB | Source code tersedia; perlu diuji |
| Barang keluar | Validasi stok, catat jumlah/tanggal/pencatat; cegah stok negatif | Source code tersedia; perlu diuji |
| Riwayat | Daftar transaksi masuk/keluar dan filter | Source code tersedia; perlu diuji |
| Ekspor | Ekspor CSV riwayat | Source code tersedia; perlu diuji |

### B. Ekstensi V2 yang direkomendasikan

| Prioritas | Modul | Kemampuan | Alasan |
|---|---|---|---|
| P1 | Lokasi penyimpanan | Master lokasi/rak/ruangan; barang terhubung ke lokasi | Data lokasi konsisten dan mudah difilter |
| P1 | Stock opname | Hitung stok fisik, bandingkan dengan stok sistem, catat selisih dan alasan | Menjawab masalah selisih stok fisik |
| P1 | Purchase order (PO) | Buat pesanan supplier, item dan jumlah, status draft/dipesan/diterima/sebagian/dibatalkan | Menghubungkan stok minimum dengan pengadaan |
| P2 | Penerimaan PO | Penerimaan barang berdasarkan item PO; membuat transaksi barang masuk dan memperbarui sisa pesanan | Mencegah penerimaan tidak terlacak |
| P2 | Audit aktivitas | Catat siapa melakukan perubahan penting, kapan, dan pada data apa | Akuntabilitas dan penelusuran |
| P2 | Laporan lanjutan | Filter periode, kategori, lokasi, supplier dan tipe transaksi; ringkasan stok | Membantu evaluasi operasional |
| P3 | Import CSV | Impor master barang dengan validasi dan pratinjau | Mempercepat input awal |
| P3 | Barcode/QR | Pencarian barang melalui kode | Mempercepat operasional gudang |

## 5. Alur bisnis utama

### Barang masuk langsung
Petugas memilih barang dan supplier, mengisi kuantitas serta tanggal, sistem memvalidasi input, mengunci baris stok yang relevan, menambah stok dan menyimpan transaksi dalam satu database transaction.

### Barang keluar
Petugas memilih barang, mengisi kuantitas dan alasan, sistem mengunci baris barang, memeriksa kecukupan stok, mengurangi stok dan menyimpan transaksi. Jika stok tidak cukup, seluruh transaksi dibatalkan.

### Purchase order sampai penerimaan
Admin membuat PO ke supplier, menambahkan satu atau beberapa item, menyimpan PO sebagai draft, lalu mengubah status menjadi dipesan. Saat barang tiba, petugas mencatat jumlah diterima per item. Sistem hanya menambah stok berdasarkan kuantitas yang benar-benar diterima, membuat catatan barang masuk yang terhubung ke PO, dan memperbarui status menjadi sebagian diterima atau selesai diterima.

### Stock opname
Admin/Petugas membuat sesi opname untuk tanggal dan lokasi tertentu, memasukkan jumlah fisik per barang, sistem membandingkannya dengan stok sistem, menghitung selisih, meminta alasan untuk selisih, lalu Admin menyetujui penyesuaian. Stok sistem hanya berubah setelah hasil opname disahkan.

## 6. Aturan bisnis yang wajib
1. Kode barang unik.
2. Kuantitas transaksi harus bilangan bulat positif.
3. Stok tidak boleh negatif.
4. Perubahan stok dan penyimpanan catatan transaksi dilakukan dalam satu database transaction.
5. Gunakan row locking saat memproses perubahan stok agar dua transaksi bersamaan tidak mengakibatkan stok salah.
6. Barang yang memiliki riwayat transaksi tidak dihapus permanen; gunakan status aktif/nonaktif jika dibutuhkan.
7. Penerimaan PO tidak boleh melebihi jumlah yang masih terbuka tanpa konfirmasi/aturan khusus.
8. Stock opname yang sudah disahkan tidak boleh diedit; koreksi dilakukan dengan catatan penyesuaian baru.
9. Setiap transaksi mencatat pengguna dan waktu.
10. Audit log tidak menyimpan password, token, APP_KEY, atau nilai rahasia lainnya.
11. Stok kritis ditetapkan ketika `stok <= stok_minimum`.
12. Export laporan harus mengikuti filter yang sedang dipilih pengguna.

## 7. Ruang lingkup yang jangan dikerjakan dulu
Agar proyek tetap selesai tepat waktu, jangan langsung menambahkan multi-cabang, akuntansi, integrasi ERP, aplikasi mobile native, notifikasi WhatsApp, approval berlapis, atau forecasting berbasis AI. Modul tersebut dapat menjadi ide pengembangan masa depan, bukan komitmen semester ini.

## 8. Kriteria keberhasilan proyek
- Admin dan Petugas dapat login dengan hak akses yang benar.
- Barang, kategori, supplier dan lokasi dapat dikelola tanpa duplikasi kode.
- Barang masuk dan keluar memperbarui stok dengan benar.
- Transaksi yang tidak valid ditolak dan stok tidak berubah.
- Dashboard menunjukkan barang di bawah atau sama dengan stok minimum.
- Riwayat dan laporan konsisten dengan transaksi.
- PO dapat diterima sebagian dan penuh tanpa menambah stok ganda.
- Stock opname mencatat selisih dan hanya mengubah stok setelah disahkan.
- Setiap aksi penting dapat ditelusuri melalui audit log.
- Proyek dapat dijalankan anggota kelompok di macOS dan Windows menggunakan Docker Compose.

## 9. Batas antara rancangan dan realisasi
Dokumen ini adalah rancangan target V2. Tabel, controller, service, halaman, route, dan test baru belum otomatis tersedia di source code saat dokumen ini dibuat. Setiap modul baru harus melalui implementasi, migration, pengujian, review, dan dokumentasi sebelum diberi status selesai.

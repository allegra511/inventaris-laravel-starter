# Roadmap implementasi untuk kelompok

## Prinsip
Jangan menambahkan modul V2 sebelum alur MVP stabil. Target UTS adalah memperlihatkan progres yang benar-benar berjalan, bukan jumlah fitur sebanyak mungkin.

## Fase 0 — Stabilkan fondasi (sekarang)
- Pastikan `docker compose ps` menunjukkan app healthy dan db healthy.
- Pastikan `php artisan --version` berjalan.
- Jalankan `php artisan migrate --seed`.
- Uji login Admin dan Petugas.
- Jalankan test transaksi.
- Catat port lokal yang digunakan di `docker-compose.yml`; di laptop Allegra port host MySQL telah diubah menjadi 3308 karena 3307 bentrok. Commit perubahan port yang disepakati atau dokumentasikan override lokal agar semua anggota konsisten.

## Fase 1 — Selesaikan MVP untuk UTS
1. Auth dan role.
2. CRUD kategori, supplier, barang.
3. Barang masuk dan keluar.
4. Dashboard stok minimum.
5. Riwayat dan export CSV.
6. Uji alur normal dan gagal.
7. Pastikan Mac dan Windows dapat menjalankan Docker dari README.

**Gerbang selesai:** setiap fitur dapat didemonstrasikan dari browser dan memiliki bukti test atau checklist uji.

## Fase 2 — Tambahkan dua fitur pembeda
1. Stock opname dengan pengesahan Admin.
2. Purchase order dan penerimaan sebagian/penuh.

**Gerbang selesai:** penerimaan PO menambah stok satu kali saja; stock opname tidak mengubah stok sebelum disetujui.

## Fase 3 — Penguatan sistem
1. Lokasi sebagai master data.
2. Audit log.
3. Laporan per periode, kategori, supplier dan lokasi.
4. Penyempurnaan role policy dan halaman manajemen pengguna.

## Pembagian awal tugas
| Anggota | Fokus utama | Hasil yang dapat dibuktikan |
|---|---|---|
| Gerry Audie B. (1972029) | Database, migration, relasi, lokasi | Migration, ERD, test integritas data |
| Michael Marenden (1972043) | Supplier, purchase order, transaksi penerimaan | Controller/service, alur PO, test penerimaan |
| Allegra (2372069) | Dashboard, stock opname, riwayat/laporan, dokumentasi | Halaman dashboard, alur opname, dokumentasi/test UI |
| Semua anggota | Auth, integrasi Docker, review PR, test lintas OS | Commit/PR dan checklist integrasi |

Pembagian ini usulan awal, bukan catatan bahwa tugas telah dikerjakan. Sepakati ulang berdasarkan waktu dan kemampuan setiap anggota.

## Contoh branch workflow
- `main`: versi stabil yang siap didemonstrasikan.
- `develop`: integrasi pekerjaan kelompok.
- `feature/nama-modul`: pekerjaan per fitur.
- Setiap fitur masuk melalui Pull Request dan direview anggota lain.

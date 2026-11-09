# Memasang diagram revisi

Arsip ini berisi revisi dokumentasi diagram untuk proyek Sistem Informasi Inventaris & Pengelolaan Barang.

## Cara memasang

1. Ekstrak arsip.
2. Salin folder `docs/` dari arsip ke folder utama repository `inventaris-laravel-starter/` yang sudah ada.
3. Pilih **Replace** untuk file yang namanya sama.
4. Buka file `.mmd` dengan ekstensi Mermaid di Visual Studio Code, atau gunakan Mermaid Live Editor.
5. Periksa kembali diagram terhadap source code setiap kali ada perubahan implementasi.
6. Setelah disetujui kelompok, commit dan push pembaruan dokumentasi.

File yang diperbarui:

- `docs/use-case.mmd`
- `docs/activity-barang-masuk.mmd`
- `docs/activity-barang-keluar.mmd`
- `docs/erd.mmd`
- `docs/class-diagram.mmd`
- `docs/DIAGRAMS.md`
- `docs/PROJECT_SPEC.md`

Diagram diselaraskan dengan file route, controller, model, service, migration, dan seeder dalam starter project. Diagram menggambarkan rancangan kode yang tersedia, bukan bukti bahwa seluruh fitur telah lulus pengujian runtime.

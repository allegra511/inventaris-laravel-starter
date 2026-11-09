# Cara memasang patch otomatisasi

Patch ini ditujukan untuk repository kelompok yang sudah memiliki folder `app/`, `docker-compose.yml`, dan folder `laravel-overlay/`.

## Sebelum memasang

1. Commit dan push perubahan yang sudah ada.
2. Pastikan database/volume Docker tidak dihapus.
3. Buat backup database jika sudah ada data penting.
4. Ekstrak isi ZIP ke **folder utama repository** `inventaris-laravel-starter/` dan pilih Replace/Merge untuk file yang sama. Patch tidak menyertakan `.env`, `vendor`, atau database dump.

## File yang diperbarui

- Kode barang otomatis dan stok awal 0.
- Nomor transaksi masuk/keluar otomatis.
- Tanggal transaksi memiliki default server-side.
- Pengguna transaksi tetap diambil dari sesi login.
- Target stok dan jumlah restock yang disarankan.
- Dashboard dan riwayat menampilkan informasi otomatisasi.
- Migration baru menambah `target_stock` dan `transaction_code`.
- Dockerfile PHP 8.4 dan port MySQL host 3308 agar sesuai dengan konfigurasi yang sudah digunakan Allegra.
- Diagram dan dokumentasi otomatisasi.

## Jalankan di Mac

```bash
cd ~/Downloads/inventaris-laravel-starter

docker compose ps
# Pastikan app dan db sudah running/healthy

docker compose exec app php artisan migrate
```

Tidak perlu menjalankan `migrate --seed` pada database yang sudah berisi data. Migration baru akan menambahkan kolom dan mengisi target stock awal dari `minimum_stock * 2`.

Kemudian bersihkan cache:

```bash
docker compose exec app php artisan optimize:clear
```

Jalankan test:

```bash
docker compose exec app php artisan test
```

## Setelah berhasil

```bash
git status --short
git add app docker-compose.yml laravel-overlay docs
git commit -m "feat: add automated inventory workflows"
git push
```

## Catatan penting

- Patch ini adalah pembaruan otomatisasi fondasi, bukan implementasi penuh Purchase Order, Stock Opname, lokasi terstruktur, atau Audit Log.
- Fitur lanjutan tersebut didefinisikan di `docs/automation-v3/` sebagai roadmap.
- Untuk laptop anggota baru, gunakan repo terbaru, setup sesuai README, lalu `php artisan migrate --seed` pada database baru.
- Jangan pernah meng-commit `app/.env` atau mengganti APP_KEY lintas anggota.

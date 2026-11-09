# Roadmap otomatisasi dan perluasan fitur

## Fase A — Otomatisasi operasional inti

| Fitur | Status dalam patch | Hasil yang diharapkan |
|---|---|---|
| Kode barang otomatis | Kode disediakan | Admin tidak perlu mengetik kode |
| Nomor transaksi otomatis | Kode disediakan | Referensi transaksi unik dan mudah dilacak |
| Tanggal default hari ini | Kode disediakan | Tanggal tidak perlu diketik untuk transaksi hari ini |
| Petugas otomatis dari login | Sudah ada pada fondasi; dipertahankan | Identitas pencatat tidak diinput manual |
| Stok real-time setelah transaksi | Sudah ada pada fondasi; dipertahankan | Stok konsisten dalam transaksi DB |
| Saran jumlah restock | Kode disediakan | Sistem menghitung target dikurangi stok |
| Peringatan stok minimum | Sudah ada pada fondasi; ditampilkan dashboard | Barang kritis mudah dilihat |

## Fase B — Pengadaan (Purchase Order)

1. Dashboard mengidentifikasi barang dengan stok <= minimum.
2. Sistem menghitung jumlah saran pembelian dari target_stock - stock.
3. Sistem menampilkan supplier yang biasa memasok barang jika relasi pemasok barang telah dikonfigurasi.
4. Admin memeriksa rekomendasi, harga, supplier, dan tanggal kebutuhan.
5. Admin menyimpan PO sebagai draft lalu menyetujui/mengubah status menjadi ordered.
6. Saat barang tiba, Petugas memasukkan kuantitas yang benar-benar diterima.
7. Sistem membuat transaksi barang masuk yang terhubung ke PO dan memperbarui jumlah diterima serta sisa pesanan.
8. Status PO otomatis menjadi partially_received atau received.

Kontrol: penerimaan PO harus idempotent terhadap setiap baris penerimaan agar pengiriman form ulang tidak menggandakan stok.

## Fase C — Stock Opname

1. Admin membuat sesi opname untuk gudang/lokasi dan tanggal.
2. Sistem mengambil snapshot kuantitas sistem saat sesi dimulai.
3. Petugas mengisi hasil hitung fisik (bisa melalui barcode pada fase lanjutan).
4. Sistem menghitung selisih = jumlah fisik - jumlah sistem.
5. Petugas memberi alasan jika terdapat selisih.
6. Petugas mengirim hasil untuk persetujuan.
7. Admin meninjau lalu menyetujui.
8. Setelah disetujui, sistem membuat adjustment movement dan memperbarui stok satu kali.

Kontrol: sebelum approval, stok tidak boleh berubah. Hasil yang sudah disetujui tidak boleh diedit langsung; koreksi menggunakan transaksi koreksi baru.

## Fase D — Lokasi dan audit

- Lokasi terstruktur: gudang, ruangan, rak/bin, kode lokasi.
- Transisi dari `items.location` teks ke `location_id` harus mempertahankan dan memetakan nilai lama.
- Audit log untuk create/update/delete penting, transaksi stok, approval PO/opname, serta login/logout.
- Audit log tidak boleh menyimpan password, cookie, token, APP_KEY, atau kredensial.

## Fase E — Integrasi opsional

- Import CSV dengan pratinjau dan validasi per baris.
- Pencarian barcode/QR menggunakan kamera perangkat atau scanner USB sebagai keyboard wedge.
- Email peringatan stok rendah memakai SMTP yang dikonfigurasi melalui secret environment.
- Laporan periodik terjadwal menggunakan Laravel Scheduler; kirim hanya ke penerima yang disetujui.

## Prioritas untuk kelompok tiga orang

**P1:** automasi inti, PO dan penerimaan PO.

**P2:** stock opname dan approval.

**P3:** lokasi terstruktur, audit log, import CSV.

**P4:** barcode, email terjadwal, laporan periodik.

Setiap fase harus memiliki migration, policy/role check, validasi, test normal/gagal, serta demonstrasi sebelum dinyatakan selesai.

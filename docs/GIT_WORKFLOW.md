# Git workflow kelompok

1. Satu repository bersama di GitHub.
2. `main` hanya untuk versi stabil.
3. `develop` menjadi branch integrasi harian.
4. Setiap anggota membuat branch fitur dari `develop`.
5. Commit harus kecil dan menjelaskan perubahan.
6. Push branch dan buat Pull Request ke `develop`.
7. Minimal satu anggota lain melakukan review sebelum merge.
8. Uji fitur setelah merge; jika stabil, merge `develop` ke `main`.
9. Jangan commit `.env`, password, `vendor/`, atau `node_modules/`.
10. Jangan membuat commit fiktif. Riwayat Git harus merepresentasikan pekerjaan yang benar-benar dilakukan.

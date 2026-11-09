#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")"

if [ -f app/artisan ]; then
  echo "Laravel sudah tersedia di folder app. Setup dibatalkan agar kode tidak tertimpa."
  exit 0
fi

if find app -mindepth 1 -maxdepth 1 ! -name .gitkeep | grep -q .; then
  echo "Folder app tidak kosong. Amankan isinya atau gunakan folder proyek baru."
  exit 1
fi

rm -f app/.gitkeep
echo "Membuat Laravel 13 melalui Docker Composer..."
docker compose --profile setup run --rm scaffold
echo "Menyalin starter code..."
cp -a laravel-overlay/. app/
cp env.docker.example app/.env
chmod +x app/docker/entrypoint.sh
echo "Selesai. Jalankan: docker compose up -d --build"

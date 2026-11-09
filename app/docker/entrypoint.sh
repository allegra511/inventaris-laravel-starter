#!/bin/sh
set -eu
cd /var/www/html

if [ ! -f artisan ]; then
  echo "Laravel belum ditemukan. Jalankan setup.ps1 atau setup.sh terlebih dahulu."
  exit 1
fi

if [ ! -f vendor/autoload.php ]; then
  echo "Menginstall dependency Composer pertama kali..."
  composer install --no-interaction --prefer-dist
fi

mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache || true
exec apache2-foreground

$ErrorActionPreference = 'Stop'
$root = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $root

if (Test-Path '.\app\artisan') {
    Write-Host 'Laravel sudah tersedia di folder app. Setup dibatalkan agar kode yang ada tidak tertimpa.' -ForegroundColor Yellow
    exit 0
}

$existing = Get-ChildItem '.\app' -Force | Where-Object { $_.Name -ne '.gitkeep' }
if ($existing) {
    throw 'Folder app tidak kosong. Amankan isinya atau gunakan folder proyek yang baru.'
}

Write-Host 'Membuat Laravel 13 melalui Docker Composer...' -ForegroundColor Cyan
Remove-Item '.\app\.gitkeep' -ErrorAction SilentlyContinue
docker compose --profile setup run --rm scaffold
if ($LASTEXITCODE -ne 0) { throw 'Laravel scaffold gagal. Periksa Docker Desktop dan koneksi internet.' }

Write-Host 'Menyalin starter code...' -ForegroundColor Cyan
Copy-Item '.\laravel-overlay\*' '.\app' -Recurse -Force
Copy-Item '.\env.docker.example' '.\app\.env' -Force
Write-Host 'Selesai. Jalankan: docker compose up -d --build' -ForegroundColor Green

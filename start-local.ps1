# Run from: C:\Users\Hp\smart-leading
# Usage: powershell -ExecutionPolicy Bypass -File .\start-local.ps1

$ErrorActionPreference = "Stop"
$ProjectRoot = $PSScriptRoot

Write-Host "Smart Leading - local start" -ForegroundColor Cyan
Write-Host "Folder: $ProjectRoot"

$heroFile = Join-Path $ProjectRoot "wp-content\themes\smart-leading-net\template-parts\sections\hero-banner.php"
$composeFile = Join-Path $ProjectRoot "docker-compose.yml"

if (-not (Test-Path $heroFile)) {
    Write-Host "ERROR: Theme file not found." -ForegroundColor Red
    Write-Host "Expected: $heroFile"
    exit 1
}

if (-not (Test-Path $composeFile)) {
    Write-Host "ERROR: docker-compose.yml not found in $ProjectRoot" -ForegroundColor Red
    exit 1
}

$heroText = Get-Content $heroFile -Raw
if ($heroText -notmatch "NEW section added \(1\)") {
    Write-Host "ERROR: Homepage section missing. Pull latest team-web first." -ForegroundColor Red
    Write-Host "Run: git checkout team-web"
    Write-Host "Run: git pull origin team-web"
    exit 1
}

Write-Host "OK: NEW section added (1) found in hero-banner.php" -ForegroundColor Green

Set-Location $ProjectRoot

Write-Host "Pulling latest team-web..." -ForegroundColor Yellow
git checkout team-web
git pull origin team-web

Write-Host "Starting Docker..." -ForegroundColor Yellow
docker compose down
docker compose up -d

Start-Sleep -Seconds 5
docker compose ps

Write-Host ""
Write-Host "Open in browser: http://localhost:8080" -ForegroundColor Green
Write-Host "Homepage should show hero, then: NEW section added (1)" -ForegroundColor Green

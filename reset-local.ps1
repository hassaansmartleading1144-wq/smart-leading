# Full local reset — fixes "Error establishing a database connection"
# Safe: your theme files in wp-content are NOT deleted
# Run: powershell -ExecutionPolicy Bypass -File .\reset-local.ps1

$ErrorActionPreference = "Stop"
Set-Location $PSScriptRoot

Write-Host ""
Write-Host "=== Smart Leading - FULL LOCAL RESET ===" -ForegroundColor Cyan
Write-Host ""

if (-not (Get-Command docker -ErrorAction SilentlyContinue)) {
    Write-Host "ERROR: Install and open Docker Desktop first." -ForegroundColor Red
    exit 1
}

docker info 2>&1 | Out-Null
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Docker Desktop is not running. Open it and wait for Engine running." -ForegroundColor Red
    exit 1
}

Write-Host "Step 1: Stop and remove old database + WordPress volumes..." -ForegroundColor Yellow
docker compose down -v

Write-Host ""
Write-Host "Step 2: Start fresh MySQL + WordPress..." -ForegroundColor Yellow
docker compose up -d

Write-Host ""
Write-Host "Step 3: Wait for MySQL (about 30-60 seconds)..." -ForegroundColor Yellow
Start-Sleep -Seconds 20

$ready = $false
for ($i = 1; $i -le 20; $i++) {
    $dbId = docker compose ps -q db 2>$null
    if ($dbId) {
        $health = docker inspect --format='{{.State.Health.Status}}' $dbId 2>$null
        if ($health -eq "healthy") {
            $ready = $true
            break
        }
    }
    Write-Host "  Still starting... ($($i * 3)s)" -ForegroundColor DarkGray
    Start-Sleep -Seconds 3
}

docker compose ps

Write-Host ""
if ($ready) {
    Write-Host "Database is ready." -ForegroundColor Green
} else {
    Write-Host "Containers started — give it 1 more minute if site still shows error." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "=== DONE ===" -ForegroundColor Green
Write-Host "Open:  http://localhost:8080" -ForegroundColor Green
Write-Host ""
Write-Host "You will see WordPress INSTALL screen (first time setup)." -ForegroundColor Yellow
Write-Host "After install:" -ForegroundColor Yellow
Write-Host "  1. Appearance -> Themes -> activate Smart Leading Net" -ForegroundColor Yellow
Write-Host "  2. Your site will work again" -ForegroundColor Yellow
Write-Host ""

# Fix "Error establishing a database connection" on local Docker WordPress
# Run: powershell -ExecutionPolicy Bypass -File .\fix-database.ps1

$ErrorActionPreference = "Stop"
$ProjectRoot = $PSScriptRoot
Set-Location $ProjectRoot

Write-Host "Smart Leading - database connection fix" -ForegroundColor Cyan
Write-Host "Folder: $ProjectRoot"
Write-Host ""

if (-not (Get-Command docker -ErrorAction SilentlyContinue)) {
    Write-Host "ERROR: Docker is not installed. Open Docker Desktop first." -ForegroundColor Red
    exit 1
}

$dockerInfo = docker info 2>&1
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Docker Desktop is not running." -ForegroundColor Red
    Write-Host "Open Docker Desktop, wait for 'Engine running', then run this script again."
    exit 1
}

Write-Host "Step 1: Stop containers..." -ForegroundColor Yellow
docker compose down

Write-Host ""
Write-Host "Step 2: Start database first and wait until healthy..." -ForegroundColor Yellow
docker compose up -d db

$maxWait = 90
$waited = 0
$dbHealthy = $false

while ($waited -lt $maxWait) {
    $status = docker inspect --format='{{.State.Health.Status}}' (docker compose ps -q db) 2>$null
    if ($status -eq "healthy") {
        $dbHealthy = $true
        break
    }
    Start-Sleep -Seconds 3
    $waited += 3
    Write-Host "  Waiting for MySQL... ($waited s)" -ForegroundColor DarkGray
}

if (-not $dbHealthy) {
    Write-Host "WARNING: MySQL health check timed out. Starting WordPress anyway..." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Step 3: Start WordPress..." -ForegroundColor Yellow
docker compose up -d wordpress

Start-Sleep -Seconds 8
docker compose ps

Write-Host ""
Write-Host "Step 4: Test database from WordPress container..." -ForegroundColor Yellow
$test = docker compose exec -T wordpress php -r "mysqli_report(MYSQLI_REPORT_OFF); `$m=@new mysqli('db','wordpress','wordpress','wordpress'); echo (`$m->connect_error)?'FAIL: '.`$m->connect_error:'OK: database connected';" 2>&1
Write-Host $test

if ($test -match "^FAIL:") {
    Write-Host ""
    Write-Host "Database still not connecting. Resetting Docker volumes (fresh WordPress install)..." -ForegroundColor Yellow
    Write-Host "This deletes local WordPress data only — your theme files in wp-content are safe." -ForegroundColor DarkGray
    docker compose down -v
    docker compose up -d

    Start-Sleep -Seconds 15
    docker compose ps

    Write-Host ""
    Write-Host "Fresh install ready. Open http://localhost:8080 and complete WordPress setup again." -ForegroundColor Green
    exit 0
}

Write-Host ""
Write-Host "Done! Open http://localhost:8080" -ForegroundColor Green
Write-Host "If the page still shows an error, hard refresh with Ctrl+F5." -ForegroundColor Green

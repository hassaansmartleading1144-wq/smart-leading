# Run from: C:\Users\Hp\smart-leading
# Usage: powershell -ExecutionPolicy Bypass -File .\start-local.ps1
#
# This script automatically:
# 1. Pulls latest team-web from GitHub
# 2. Verifies theme files include the latest sections
# 3. Starts Docker

$ErrorActionPreference = "Stop"
$ProjectRoot = $PSScriptRoot

Write-Host "Smart Leading - auto sync & local start" -ForegroundColor Cyan
Write-Host "Folder: $ProjectRoot"

$heroFile = Join-Path $ProjectRoot "wp-content\themes\smart-leading-net\template-parts\sections\hero-banner.php"
$contactFile = Join-Path $ProjectRoot "wp-content\themes\smart-leading-net\contact-template.php"
$teamFile = Join-Path $ProjectRoot "wp-content\themes\smart-leading-net\template-parts\sections\team.php"
$composeFile = Join-Path $ProjectRoot "docker-compose.yml"

if (-not (Test-Path $composeFile)) {
    Write-Host "ERROR: docker-compose.yml not found in $ProjectRoot" -ForegroundColor Red
    exit 1
}

Set-Location $ProjectRoot

Write-Host ""
Write-Host "Step 1: Auto-pull latest team-web from GitHub..." -ForegroundColor Yellow
git fetch origin
git checkout team-web
git pull origin team-web

Write-Host ""
Write-Host "Step 2: Verify theme files..." -ForegroundColor Yellow

if (-not (Test-Path $heroFile)) {
    Write-Host "ERROR: Theme file not found." -ForegroundColor Red
    Write-Host "Expected: $heroFile"
    exit 1
}

$heroText = Get-Content $heroFile -Raw
if ($heroText -notmatch "NEW section added \(1\)") {
    Write-Host "ERROR: Homepage section still missing after git pull." -ForegroundColor Red
    Write-Host "Check your internet connection and try again."
    exit 1
}
Write-Host "OK: NEW section added (1) found in hero-banner.php" -ForegroundColor Green

if (-not (Test-Path $contactFile)) {
    Write-Host "ERROR: Contact template not found." -ForegroundColor Red
    Write-Host "Expected: $contactFile"
    exit 1
}

$contactText = Get-Content $contactFile -Raw
if ($contactText -notmatch "Section added1") {
    Write-Host "ERROR: Contact page section still missing after git pull." -ForegroundColor Red
    Write-Host "Check your internet connection and try again."
    exit 1
}
Write-Host "OK: Section added1 found in contact-template.php" -ForegroundColor Green

if (-not (Test-Path $teamFile)) {
    Write-Host "ERROR: Team section file not found." -ForegroundColor Red
    Write-Host "Expected: $teamFile"
    exit 1
}

$teamText = Get-Content $teamFile -Raw
if ($teamText -notmatch "Our Team") {
    Write-Host "ERROR: Team section heading still missing after git pull." -ForegroundColor Red
    Write-Host "Check your internet connection and try again."
    exit 1
}
Write-Host "OK: Our Team section found in team.php" -ForegroundColor Green

Write-Host ""
Write-Host "Step 3: Check Docker Desktop..." -ForegroundColor Yellow

if (-not (Get-Command docker -ErrorAction SilentlyContinue)) {
    Write-Host "ERROR: Docker is not installed." -ForegroundColor Red
    Write-Host ""
    Write-Host "Do this first:" -ForegroundColor Yellow
    Write-Host "  1. Download Docker Desktop: https://www.docker.com/products/docker-desktop/"
    Write-Host "  2. Install it and restart your PC if asked"
    Write-Host "  3. Open Docker Desktop from the Start menu"
    Write-Host "  4. Wait until the bottom-left says 'Engine running'"
    Write-Host "  5. Run this script again"
    exit 1
}

$dockerInfo = docker info 2>&1
if ($LASTEXITCODE -ne 0) {
    Write-Host "ERROR: Docker Desktop is not running." -ForegroundColor Red
    Write-Host ""
    Write-Host "Do this first:" -ForegroundColor Yellow
    Write-Host "  1. Press Windows key and search for 'Docker Desktop'"
    Write-Host "  2. Open Docker Desktop"
    Write-Host "  3. Wait 1-2 minutes until it shows 'Engine running' (green)"
    Write-Host "  4. Run this script again:"
    Write-Host "     powershell -ExecutionPolicy Bypass -File .\start-local.ps1"
    exit 1
}
Write-Host "OK: Docker Desktop is running" -ForegroundColor Green

Write-Host ""
Write-Host "Step 4: Starting WordPress + MySQL containers..." -ForegroundColor Yellow
Write-Host "(First time may take 2-5 minutes to download images — please wait)" -ForegroundColor DarkGray
docker compose down
docker compose up -d

Start-Sleep -Seconds 5
docker compose ps

Write-Host ""
Write-Host "Done! Open in browser:" -ForegroundColor Green
Write-Host "  Homepage:  http://localhost:8080" -ForegroundColor Green
Write-Host "  Contact:   http://localhost:8080/contact-us/" -ForegroundColor Green
Write-Host "  (or)       http://localhost:8080/?page_id=210" -ForegroundColor Green
Write-Host ""
Write-Host "Homepage should show 'Our Team' section above the footer CTA." -ForegroundColor Green
Write-Host "Contact page should show 'Section added1' above the footer." -ForegroundColor Green

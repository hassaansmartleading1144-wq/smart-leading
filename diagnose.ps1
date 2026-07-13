# Show why database connection fails
# Run: powershell -ExecutionPolicy Bypass -File .\diagnose.ps1

$ErrorActionPreference = "Continue"
Set-Location $PSScriptRoot

Write-Host ""
Write-Host "=== Smart Leading - DIAGNOSE ===" -ForegroundColor Cyan
Write-Host ""

Write-Host "--- Docker running? ---" -ForegroundColor Yellow
docker info 2>&1 | Select-Object -First 5

Write-Host ""
Write-Host "--- Containers ---" -ForegroundColor Yellow
docker compose -p smart-leading ps -a

Write-Host ""
Write-Host "--- Port 8080 ---" -ForegroundColor Yellow
docker ps --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}" | Select-String "8080"

Write-Host ""
Write-Host "--- Database logs (last 25 lines) ---" -ForegroundColor Yellow
docker compose -p smart-leading logs --tail 25 db

Write-Host ""
Write-Host "--- WordPress logs (last 25 lines) ---" -ForegroundColor Yellow
docker compose -p smart-leading logs --tail 25 wordpress

Write-Host ""
Write-Host "--- Volumes ---" -ForegroundColor Yellow
docker volume ls | Select-String "smart-leading"

Write-Host ""
Write-Host "--- DB test from WordPress container ---" -ForegroundColor Yellow
docker compose -p smart-leading exec -T wordpress php -r "mysqli_report(MYSQLI_REPORT_OFF); `$m=@new mysqli('db','wordpress','wordpress','wordpress'); echo (`$m->connect_error)?'FAIL: '.`$m->connect_error:'OK: connected';" 2>&1

Write-Host ""
Write-Host "Copy ALL output above and send to support." -ForegroundColor Green
Write-Host ""

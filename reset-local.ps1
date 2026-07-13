# Full local reset — fixes "Error establishing a database connection"
# Safe: your theme files in wp-content are NOT deleted
# Run: powershell -ExecutionPolicy Bypass -File .\reset-local.ps1

$ErrorActionPreference = "Stop"
Set-Location $PSScriptRoot

$ProjectName = "smart-leading"

Write-Host ""
Write-Host "=== Smart Leading - FULL LOCAL RESET ===" -ForegroundColor Cyan
Write-Host "Project: $ProjectName"
Write-Host "Folder:  $PSScriptRoot"
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

Write-Host "Step 1: Stop smart-leading containers..." -ForegroundColor Yellow
docker compose -p $ProjectName down --remove-orphans 2>$null

Write-Host ""
Write-Host "Step 2: Force-delete old Docker volumes..." -ForegroundColor Yellow
$volumes = @(
    "${ProjectName}_db_data",
    "${ProjectName}_wordpress_core",
    "smart-leading_db_data",
    "smart-leading_wordpress_core"
)
foreach ($vol in $volumes) {
    docker volume rm $vol 2>$null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "  Removed volume: $vol" -ForegroundColor DarkGray
    }
}

Write-Host ""
Write-Host "Step 3: Stop other WordPress/MySQL containers using port 8080..." -ForegroundColor Yellow
docker ps --format "{{.ID}} {{.Names}} {{.Ports}}" | Select-String "8080" | ForEach-Object {
    $id = ($_ -split " ")[0]
    if ($id) {
        Write-Host "  Stopping container: $id" -ForegroundColor DarkGray
        docker stop $id 2>$null
    }
}

Write-Host ""
Write-Host "Step 4: Start fresh MariaDB + WordPress..." -ForegroundColor Yellow
docker compose -p $ProjectName up -d

Write-Host ""
Write-Host "Step 5: Wait for database (up to 2 minutes)..." -ForegroundColor Yellow
$ready = $false
for ($i = 1; $i -le 40; $i++) {
    $dbId = docker compose -p $ProjectName ps -q db 2>$null
    if ($dbId) {
        $health = docker inspect --format='{{.State.Health.Status}}' $dbId 2>$null
        $running = docker inspect --format='{{.State.Running}}' $dbId 2>$null
        if ($health -eq "healthy") {
            $ready = $true
            break
        }
        if ($running -ne "true") {
            Write-Host ""
            Write-Host "ERROR: Database container stopped/crashed. Logs:" -ForegroundColor Red
            docker compose -p $ProjectName logs --tail 30 db
            exit 1
        }
    }
    Write-Host "  Waiting... ($($i * 3)s) health=$health" -ForegroundColor DarkGray
    Start-Sleep -Seconds 3
}

Write-Host ""
Write-Host "Step 6: Restart WordPress so it picks up fresh database..." -ForegroundColor Yellow
docker compose -p $ProjectName restart wordpress
Start-Sleep -Seconds 8

docker compose -p $ProjectName ps

Write-Host ""
if ($ready) {
    Write-Host "Database is healthy." -ForegroundColor Green
} else {
    Write-Host "WARNING: Database health check did not pass. Showing logs:" -ForegroundColor Yellow
    docker compose -p $ProjectName logs --tail 20 db
}

Write-Host ""
Write-Host "=== DONE ===" -ForegroundColor Green
Write-Host "Open:  http://localhost:8080" -ForegroundColor Green
Write-Host ""
Write-Host "You should see WordPress INSTALL screen." -ForegroundColor Yellow
Write-Host "After install -> Appearance -> Themes -> Smart Leading Net" -ForegroundColor Yellow
Write-Host ""

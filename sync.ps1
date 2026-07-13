# Simple sync — downloads ALL latest files from GitHub to your PC
# Run: powershell -ExecutionPolicy Bypass -File .\sync.ps1

cd $PSScriptRoot
git fetch origin
git checkout team-web
git pull origin team-web
Write-Host "Done. All files updated." -ForegroundColor Green

# Smart Leading — Local Development (Docker Desktop)

Run the `team-web` branch on your PC with Docker Desktop, then view theme changes (including the **Our Team** homepage section) at `http://localhost:8080`.

## Prerequisites

1. **Git** — [https://git-scm.com/downloads](https://git-scm.com/downloads)
2. **Docker Desktop** — [https://www.docker.com/products/docker-desktop/](https://www.docker.com/products/docker-desktop/)
   - Install and start Docker Desktop before running the site.
   - On first launch, wait until Docker shows **Engine running**.

## Step 1: Clone the repository

Open **PowerShell** or **Command Prompt** and run:

```powershell
cd C:\Users\YourName
git clone -b team-web https://github.com/hassaansmartleading1144-wq/smart-leading.git
cd smart-leading
```

Replace `YourName` with your Windows user folder. You can use any folder you prefer.

> **Note:** This repo only contains `wp-content` (theme/plugins/uploads), not full WordPress core. Docker downloads WordPress automatically on first start.

## Step 2: Start the local site

### Windows (recommended)

```powershell
powershell -ExecutionPolicy Bypass -File .\start-local.ps1
```

This script will:

1. Pull the latest `team-web` branch from GitHub
2. Verify theme files (including the Team section)
3. Start WordPress + MySQL in Docker on port **8080**

### Mac / Linux

```bash
chmod +x ./start-local.sh
./start-local.sh
```

## Step 3: Open in browser

| Page | URL |
|------|-----|
| Homepage | http://localhost:8080 |
| WordPress admin | http://localhost:8080/wp-admin |

### First-time WordPress setup

On the first visit, WordPress shows the installation wizard:

1. Choose language → **Continue**
2. Site title: `Smart Leading Local`
3. Username / password: choose your own (for local use only)
4. Email: your email
5. Click **Install WordPress**

After setup:

1. Go to **Appearance → Themes**
2. Activate **Smart Leading Net**
3. Go to **Settings → Reading**
4. Set **Your homepage displays** to **A static page** (or **Your latest posts** if you use the theme front page)
5. Visit http://localhost:8080 — scroll down to see the **Our Team** heading section

## What you should see

- **Homepage:** An **Our Team** section (heading only) appears near the bottom of the page, above the footer CTA.
- **Contact page:** `Section added1` above the footer (at `/contact-us/` if that page exists).

## Sync latest changes from GitHub

```powershell
powershell -ExecutionPolicy Bypass -File .\sync.ps1
```

Or manually:

```powershell
git fetch origin
git checkout team-web
git pull origin team-web
docker compose restart wordpress
```

## Useful Docker commands

```powershell
# Stop the site
docker compose down

# Start again (after sync or code edits)
docker compose up -d

# View logs
docker compose logs -f wordpress

# Full reset (deletes local database — use only if needed)
docker compose down -v
docker compose up -d
```

## Project structure

```
smart-leading/
├── docker-compose.yml      # WordPress + MySQL containers
├── start-local.ps1         # Windows: pull + verify + start Docker
├── start-local.sh          # Mac/Linux: pull + verify + start Docker
├── sync.ps1                # Quick git pull only
└── wp-content/
    └── themes/
        └── smart-leading-net/
            ├── front-page.php
            └── template-parts/sections/team.php   # Our Team section
```

## Troubleshooting

| Problem | Fix |
|---------|-----|
| Port 8080 already in use | Change `8080:80` to `8081:80` in `docker-compose.yml`, then open http://localhost:8081 |
| Docker not running | Open Docker Desktop and wait for it to start |
| Theme not visible | Activate **Smart Leading Net** in wp-admin → Appearance → Themes |
| Old content showing | Hard refresh (Ctrl+F5) or `docker compose restart wordpress` |
| Permission denied on clone | Ensure you have access to the GitHub repo with your account |

## Making your own changes

Edit files under `wp-content/themes/smart-leading-net/`. Changes appear immediately on refresh (no rebuild needed). To save work back to GitHub:

```powershell
git checkout -b my-feature
git add .
git commit -m "Describe your change"
git push -u origin my-feature
```

Then open a pull request on GitHub into `team-web`.

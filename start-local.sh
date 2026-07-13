# Run from project root on Mac/Linux (bash).
# Usage: bash ./start-local.sh

set -euo pipefail

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$PROJECT_ROOT"

echo "Smart Leading - auto sync & local start"
echo "Folder: $PROJECT_ROOT"

HERO_FILE="$PROJECT_ROOT/wp-content/themes/smart-leading-net/template-parts/sections/hero-banner.php"
CONTACT_FILE="$PROJECT_ROOT/wp-content/themes/smart-leading-net/contact-template.php"
TEAM_FILE="$PROJECT_ROOT/wp-content/themes/smart-leading-net/template-parts/sections/team.php"
COMPOSE_FILE="$PROJECT_ROOT/docker-compose.yml"

if [[ ! -f "$COMPOSE_FILE" ]]; then
  echo "ERROR: docker-compose.yml not found in $PROJECT_ROOT"
  exit 1
fi

echo ""
echo "Step 1: Auto-pull latest team-web from GitHub..."
git fetch origin
git checkout team-web
git pull origin team-web

echo ""
echo "Step 2: Verify theme files..."

if [[ ! -f "$HERO_FILE" ]]; then
  echo "ERROR: Theme file not found: $HERO_FILE"
  exit 1
fi

if ! grep -q "NEW section added (1)" "$HERO_FILE"; then
  echo "ERROR: Homepage section still missing after git pull."
  exit 1
fi
echo "OK: NEW section added (1) found in hero-banner.php"

if [[ ! -f "$CONTACT_FILE" ]]; then
  echo "ERROR: Contact template not found: $CONTACT_FILE"
  exit 1
fi

if ! grep -q "Section added1" "$CONTACT_FILE"; then
  echo "ERROR: Contact page section still missing after git pull."
  exit 1
fi
echo "OK: Section added1 found in contact-template.php"

if [[ ! -f "$TEAM_FILE" ]]; then
  echo "ERROR: Team section file not found: $TEAM_FILE"
  exit 1
fi

if ! grep -q "Our Team" "$TEAM_FILE"; then
  echo "ERROR: Team section heading still missing after git pull."
  exit 1
fi
echo "OK: Our Team section found in team.php"

echo ""
echo "Step 3: Starting Docker..."
docker compose down
docker compose up -d

sleep 5
docker compose ps

echo ""
echo "Done! Open in browser:"
echo "  Homepage:  http://localhost:8080"
echo "  Contact:   http://localhost:8080/contact-us/"
echo ""
echo "Homepage should show 'Our Team' section above the footer CTA."
echo "Contact page should show 'Section added1' above the footer."

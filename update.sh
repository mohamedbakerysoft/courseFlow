#!/usr/bin/env bash

echo "CourseFlow Update Script"
echo "------------------------"

set -u

SERVER_DIR="server"

if [ ! -d "$SERVER_DIR" ]; then
  echo "Error: '$SERVER_DIR' directory not found. Please run this script from the project root."
  exit 1
fi

cd "$SERVER_DIR"

finish() {
  echo "Bringing application out of maintenance mode..."
  php artisan up || true
}

trap finish EXIT

echo "Enabling maintenance mode..."
php artisan down || true

echo "Running database migrations..."
if ! php artisan migrate --force; then
  echo "Migration failed. Exiting."
  exit 1
fi

echo "Clearing caches..."
php artisan optimize:clear || true

echo "Rebuilding caches..."
php artisan optimize || true

if [ -f "package.json" ]; then
  echo "Detected package.json. Checking for npm..."
  if command -v npm >/dev/null 2>&1; then
    echo "Installing frontend dependencies..."
    npm install || echo "npm install failed; continuing without frontend build."
    echo "Building frontend assets..."
    npm run build || echo "npm run build failed; continuing."
  else
    echo "npm is not available; skipping frontend build."
  fi
else
  echo "No package.json found; skipping frontend build."
fi

echo "Update steps completed."
echo "Disabling maintenance mode..."
php artisan up || true

trap - EXIT
echo "Done."


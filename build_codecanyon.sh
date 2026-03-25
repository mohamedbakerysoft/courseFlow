#!/usr/bin/env bash
set -Eeuo pipefail

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SERVER_DIR="${PROJECT_ROOT}/server"
DIST_DIR="${PROJECT_ROOT}/codecanyon_build"
PACKAGE_ROOT="${DIST_DIR}/Learnova"
ZIP_NAME="${ZIP_NAME:-Learnova_Codecanyon_v1.0.zip}"

log() {
    printf '[codecanyon-build] %s\n' "$1"
}

require_cmd() {
    if ! command -v "$1" >/dev/null 2>&1; then
        printf 'Missing required command: %s\n' "$1" >&2
        exit 1
    fi
}

require_cmd composer
require_cmd npm
require_cmd rsync
require_cmd zip

log "Cleaning previous package output"
rm -rf "$DIST_DIR"
rm -f "${PROJECT_ROOT}/${ZIP_NAME}"

log "Installing production PHP dependencies"
(
    cd "$SERVER_DIR"
    composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
)

log "Building frontend assets"
(
    cd "$SERVER_DIR"
    npm install
    npm run build
)

if [ ! -f "${SERVER_DIR}/public/build/manifest.json" ]; then
    printf 'Missing built asset manifest: %s\n' "${SERVER_DIR}/public/build/manifest.json" >&2
    exit 1
fi

log "Preparing CodeCanyon staging directory"
mkdir -p "$PACKAGE_ROOT"

log "Copying buyer package files"
rsync -a "$SERVER_DIR/" "$PACKAGE_ROOT/" \
    --exclude '.git/' \
    --exclude '.github/' \
    --exclude '.env' \
    --exclude '.env.*' \
    --exclude 'node_modules/' \
    --exclude 'tests/' \
    --exclude 'phpunit.xml' \
    --exclude '.phpunit.result.cache' \
    --exclude 'storage/logs/*.log' \
    --exclude 'storage/framework/cache/data/*' \
    --exclude 'storage/framework/sessions/*' \
    --exclude 'storage/framework/testing/*' \
    --exclude 'storage/framework/views/*.php' \
    --exclude 'storage/app/private/*' \
    --exclude 'database/*.sqlite'

log "Removing project-only files from buyer package"
rm -rf "${PACKAGE_ROOT}/ops"
rm -f "${PACKAGE_ROOT}/build_codecanyon.sh"

log "Ensuring installer defaults are present"
if [ ! -f "${PACKAGE_ROOT}/.env.example" ]; then
    cp "${SERVER_DIR}/.env.example" "${PACKAGE_ROOT}/.env.example"
fi

log "Creating CodeCanyon zip archive"
(
    cd "$DIST_DIR"
    zip -rq "../${ZIP_NAME}" "Learnova"
)

log "Cleaning staging directory"
rm -rf "$DIST_DIR"

log "Build completed"
printf 'Package ready: %s/%s\n' "$PROJECT_ROOT" "$ZIP_NAME"

#!/usr/bin/env bash
set -Eeuo pipefail

SITE_DOMAIN="${SITE_DOMAIN:-learnova.bakerysoft.net}"
DEPLOY_BASE="${DEPLOY_BASE:-/var/www/${SITE_DOMAIN}}"
SOURCE_DIR="${SOURCE_DIR:-${DEPLOY_BASE}/source}"
APP_DIR="${APP_DIR:-${SOURCE_DIR}/server}"
SHARED_DIR="${SHARED_DIR:-${DEPLOY_BASE}/shared}"
SHARED_STORAGE_DIR="${SHARED_STORAGE_DIR:-${SHARED_DIR}/storage}"
SHARED_DB_DIR="${SHARED_DB_DIR:-${SHARED_DIR}/database}"
SHARED_ENV_FILE="${SHARED_ENV_FILE:-${SHARED_DIR}/.env}"
SHARED_DB_FILE="${SHARED_DB_FILE:-${SHARED_DB_DIR}/database.sqlite}"
WORKSPACE="${GITHUB_WORKSPACE:-$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)}"
PHP_BIN="${PHP_BIN:-php}"
COMPOSER_BIN="${COMPOSER_BIN:-composer}"
NPM_BIN="${NPM_BIN:-npm}"

log() {
    printf '[learnova-deploy] %s\n' "$1"
}

require_cmd() {
    if ! command -v "$1" >/dev/null 2>&1; then
        printf 'Missing required command: %s\n' "$1" >&2
        exit 1
    fi
}

write_default_env() {
    cat >"$SHARED_ENV_FILE" <<EOF
APP_NAME=Learnova
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://${SITE_DOMAIN}

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=warning

DB_CONNECTION=sqlite
DB_DATABASE=${SHARED_DB_FILE}

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database

CACHE_STORE=database

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@${SITE_DOMAIN}"
MAIL_FROM_NAME="\${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="\${APP_NAME}"
EOF
}

require_cmd rsync
require_cmd "$PHP_BIN"
require_cmd "$COMPOSER_BIN"
require_cmd "$NPM_BIN"
require_cmd git

log "Preparing directories under ${DEPLOY_BASE}"
mkdir -p "$SOURCE_DIR" "$SHARED_STORAGE_DIR" "$SHARED_DB_DIR"
mkdir -p \
    "$SHARED_STORAGE_DIR/app/public" \
    "$SHARED_STORAGE_DIR/framework/cache" \
    "$SHARED_STORAGE_DIR/framework/sessions" \
    "$SHARED_STORAGE_DIR/framework/testing" \
    "$SHARED_STORAGE_DIR/framework/views" \
    "$SHARED_STORAGE_DIR/logs"
touch "$SHARED_DB_FILE"

if [ ! -f "$SHARED_ENV_FILE" ]; then
    log "Creating initial shared .env"
    write_default_env
fi

log "Syncing repository into ${SOURCE_DIR}"
rsync -a --delete \
    --exclude='.git/' \
    --exclude='server/.env' \
    --exclude='server/storage/' \
    --exclude='server/database/database.sqlite' \
    "$WORKSPACE"/ "$SOURCE_DIR"/

log "Linking shared runtime files"
rm -rf "$APP_DIR/storage"
ln -sfn "$SHARED_STORAGE_DIR" "$APP_DIR/storage"
rm -f "$APP_DIR/.env"
ln -sfn "$SHARED_ENV_FILE" "$APP_DIR/.env"
rm -f "$APP_DIR/database/database.sqlite"
ln -sfn "$SHARED_DB_FILE" "$APP_DIR/database/database.sqlite"

log "Installing PHP dependencies"
(
    cd "$APP_DIR"
    "$COMPOSER_BIN" install --no-dev --prefer-dist --no-interaction --optimize-autoloader
)

log "Installing frontend dependencies"
(
    cd "$APP_DIR"
    "$NPM_BIN" ci
    "$NPM_BIN" run build
)

if ! grep -q '^APP_KEY=base64:' "$SHARED_ENV_FILE"; then
    log "Generating app key"
    (
        cd "$APP_DIR"
        "$PHP_BIN" artisan key:generate --force
    )
fi

log "Running Laravel deployment tasks"
(
    cd "$APP_DIR"
    "$PHP_BIN" artisan storage:link --force
    "$PHP_BIN" artisan migrate --force
    "$PHP_BIN" artisan optimize:clear
    "$PHP_BIN" artisan config:cache
    "$PHP_BIN" artisan view:cache
)

log "Fixing permissions for runtime directories"
sudo chown -R jarvis:www-data "$DEPLOY_BASE"
sudo chown -R jarvis:www-data "$APP_DIR/bootstrap/cache"
sudo chmod -R ug+rwX "$SHARED_STORAGE_DIR" "$SHARED_DB_DIR" "$APP_DIR/bootstrap/cache"

log "Deployment completed successfully"

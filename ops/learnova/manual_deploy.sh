#!/usr/bin/env bash
set -Eeuo pipefail

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(cd "${SCRIPT_DIR}/../.." && pwd)"

SSH_HOST="${SSH_HOST:-jarvis@jarvis.local}"
SITE_DOMAIN="${SITE_DOMAIN:-learnova.bakerysoft.net}"
REMOTE_WORKSPACE="${REMOTE_WORKSPACE:-/home/jarvis/deploy-src/courseFlow}"
REPO_URL="${REPO_URL:-$(git -C "$REPO_ROOT" config --get remote.origin.url 2>/dev/null || true)}"
REF="${REF:-main}"
DRY_RUN=false

usage() {
    cat <<'EOF'
Usage: bash ops/learnova/manual_deploy.sh [options]

Manual Learnova deployment helper.
It connects to the server over SSH, prepares an isolated git checkout there,
then runs the same deploy script used by GitHub Actions.

Options:
  --ref <git-ref>              Git ref to deploy. Default: main
  --host <ssh-host>            SSH target. Default: jarvis@jarvis.local
  --repo <git-url>             Repository URL. Default: current origin remote
  --remote-workspace <path>    Remote checkout path. Default: /home/jarvis/deploy-src/courseFlow
  --site-domain <domain>       Site domain passed to deploy script. Default: learnova.bakerysoft.net
  --dry-run                    Print the remote plan without executing it
  -h, --help                   Show this help

Examples:
  bash ops/learnova/manual_deploy.sh
  bash ops/learnova/manual_deploy.sh --ref main
  bash ops/learnova/manual_deploy.sh --ref demo
EOF
}

log() {
    printf '[learnova-manual-deploy] %s\n' "$1"
}

require_cmd() {
    if ! command -v "$1" >/dev/null 2>&1; then
        printf 'Missing required command: %s\n' "$1" >&2
        exit 1
    fi
}

while [ "$#" -gt 0 ]; do
    case "$1" in
        --ref)
            REF="${2:?Missing value for --ref}"
            shift 2
            ;;
        --host)
            SSH_HOST="${2:?Missing value for --host}"
            shift 2
            ;;
        --repo)
            REPO_URL="${2:?Missing value for --repo}"
            shift 2
            ;;
        --remote-workspace)
            REMOTE_WORKSPACE="${2:?Missing value for --remote-workspace}"
            shift 2
            ;;
        --site-domain)
            SITE_DOMAIN="${2:?Missing value for --site-domain}"
            shift 2
            ;;
        --dry-run)
            DRY_RUN=true
            shift
            ;;
        -h|--help)
            usage
            exit 0
            ;;
        *)
            printf 'Unknown option: %s\n' "$1" >&2
            usage >&2
            exit 1
            ;;
    esac
done

require_cmd ssh
require_cmd git

if [ -z "$REPO_URL" ]; then
    printf 'Could not determine repository URL. Pass it explicitly with --repo.\n' >&2
    exit 1
fi

REMOTE_SCRIPT=$(cat <<'EOF'
set -Eeuo pipefail

REMOTE_WORKSPACE="$1"
REF="$2"
REPO_URL="$3"
SITE_DOMAIN="$4"

log() {
    printf '[remote-learnova-manual-deploy] %s\n' "$1"
}

resolve_target() {
    local ref="$1"

    if git rev-parse --verify --quiet "refs/remotes/origin/${ref}" >/dev/null; then
        printf 'refs/remotes/origin/%s' "$ref"
        return 0
    fi

    if git rev-parse --verify --quiet "$ref" >/dev/null; then
        printf '%s' "$ref"
        return 0
    fi

    git fetch --depth=1 origin "$ref"
    printf 'FETCH_HEAD'
}

log "Preparing remote workspace at ${REMOTE_WORKSPACE}"
mkdir -p "$(dirname "$REMOTE_WORKSPACE")"

if [ ! -d "${REMOTE_WORKSPACE}/.git" ]; then
    log "Cloning repository into remote workspace"
    git clone "$REPO_URL" "$REMOTE_WORKSPACE"
fi

cd "$REMOTE_WORKSPACE"
git remote set-url origin "$REPO_URL"
git fetch --prune origin --tags

TARGET="$(resolve_target "$REF")"
log "Deploying ref ${REF} (${TARGET})"
git checkout --detach "$TARGET"
git reset --hard "$TARGET"
git clean -fdx

log "Running Learnova deploy script"
SITE_DOMAIN="$SITE_DOMAIN" bash ops/learnova/deploy_from_workspace.sh
EOF
)

if [ "$DRY_RUN" = true ]; then
    log "Dry run only"
    log "Host: ${SSH_HOST}"
    log "Repo: ${REPO_URL}"
    log "Ref: ${REF}"
    log "Remote workspace: ${REMOTE_WORKSPACE}"
    log "Site domain: ${SITE_DOMAIN}"
    exit 0
fi

log "Connecting to ${SSH_HOST}"
log "Deploying ${REF} from ${REPO_URL}"
ssh "$SSH_HOST" bash -s -- "$REMOTE_WORKSPACE" "$REF" "$REPO_URL" "$SITE_DOMAIN" <<<"$REMOTE_SCRIPT"
log "Manual deployment completed"

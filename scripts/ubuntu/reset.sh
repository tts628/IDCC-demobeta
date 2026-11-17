#!/usr/bin/env bash
set -Eeuo pipefail
die() { printf 'ERROR: %s\n' "$*" >&2; exit 1; }
project_root=$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")/../.." && pwd -P)
plan=false
case ${1:-} in
  --plan) plan=true; shift ;;
  -h|--help) printf 'Usage: bash reset.sh [--plan]\n'; exit 0 ;;
esac
(($# == 0)) || die 'Unknown option. Usage: bash reset.sh [--plan]'
printf 'Project: idcc-demobata (%s)\n' "$project_root"
printf '%s\n' 'REMOVE: project containers and network; idcc-demobata-web:local image.'   'KEEP: all PostgreSQL volumes/data, .env, .env.postgres, source files, Docker, other projects.'   'The app will stop. Run bash install.sh afterwards to rebuild.'
$plan && exit 0
docker_bin=$(type -P docker) || die 'Docker CLI not found. Nothing was removed.'
endpoint=${DOCKER_HOST:-unix:///var/run/docker.sock}
[[ $endpoint == unix:///* ]] || die 'Only a local Unix Docker socket is allowed.'
prefix=()
docker_call() { "${prefix[@]}" env -u DOCKER_HOST -u DOCKER_CONTEXT "$docker_bin" --host "$endpoint" "$@"; }
if ! docker_call info >/dev/null 2>&1; then
  if ((EUID != 0)) && command -v sudo >/dev/null; then prefix=(sudo --); fi
  docker_call info >/dev/null 2>&1 || die 'Start the local Docker engine and retry. Nothing was removed.'
fi
docker_call ps -a --filter label=com.docker.compose.project=idcc-demobata --format 'table {{.Names}}\t{{.Status}}'
read -r -p 'Type RESET to remove the listed project environment: ' confirmation || die 'No confirmation received.'
[[ $confirmation == RESET ]] || { printf 'Cancelled. Nothing was removed.\n'; exit 0; }
docker_call compose --project-name idcc-demobata --project-directory "$project_root"   --env-file "$project_root/docker/reset.env" --file "$project_root/docker/reset.compose.yaml"   down --remove-orphans --timeout 30
# No --volumes, no prune, no force removal; retain shared PostgreSQL image.
if docker_call image inspect idcc-demobata-web:local >/dev/null 2>&1; then
  docker_call image rm idcc-demobata-web:local
fi
printf 'Reset complete. PostgreSQL data and credentials were kept. Run bash install.sh to rebuild.\n'

#!/usr/bin/env bash
# Ubuntu installer: bash ./install.sh [--port 8081] [--plan] [--no-install]
set -Eeuo pipefail
trap 'printf "ERROR: installation stopped at line %s. Review the preceding error.\n" "$LINENO" >&2' ERR

die() {
  printf 'ERROR: %s\n' "$*" >&2
  exit 1
}
log() { printf '\n==> %s\n' "$*"; }
port=8080
plan=false
no_install=false
while (($#)); do
  case "$1" in
  --port)
    (($# >= 2)) || die '--port requires a number.'
    port=$2
    shift 2
    ;;
  --plan)
    plan=true
    shift
    ;;
  --no-install)
    no_install=true
    shift
    ;;
  -h | --help)
    printf 'Usage: bash ./install.sh [--port 8080] [--plan] [--no-install]\n'
    exit 0
    ;;
  *) die "Unknown option: $1" ;;
  esac
done
[[ $port =~ ^[0-9]{1,5}$ ]] || die 'Port must be a number from 1024 to 65535.'
port=$((10#$port))
((port >= 1024 && port <= 65535)) || die 'Port must be from 1024 to 65535.'
project_root=$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")/../.." && pwd -P)
[[ $(uname -s) == Linux ]] || die 'On Windows run install.cmd. This shell installer supports Ubuntu Linux.'
[[ -r /etc/os-release ]] || die 'Cannot detect Linux distribution.'
# shellcheck disable=SC1091
source /etc/os-release
[[ ${ID:-} == ubuntu ]] || die "Unsupported OS: ${ID:-unknown}. Ubuntu is required."
case ${VERSION_ID:-} in
22.04 | 24.04 | 26.04 | 26.10) ;;
*) die "Ubuntu ${VERSION_ID:-unknown} is not in this installer's supported list (22.04/24.04/26.04/26.10)." ;;
esac
codename=${UBUNTU_CODENAME:-${VERSION_CODENAME:-}}
[[ $codename =~ ^[a-z]+$ ]] || die 'Cannot determine Ubuntu release codename.'
arch=$(dpkg --print-architecture)
[[ $arch == amd64 || $arch == arm64 ]] || die "Unsupported architecture: $arch (amd64/arm64 required)."
docker_bin=$(type -P docker || true)
if $plan; then
  printf 'OS: Ubuntu %s (%s)\nProject: %s\nDocker: %s\nInstall missing components: %s\nURL: http://127.0.0.1:%s/DemoBeta/Home/\n' \
    "$VERSION_ID" "$arch" "$project_root" "${docker_bin:-not installed}" "$(! $no_install && printf yes || printf no)" "$port"
  exit 0
fi
for required in compose.yaml docker/Dockerfile Home/index.php Home/vendor/autoload.php env docker/health.php common/IDCC_plugin/jquery/jquery.min.js common/language/language.php; do
  [[ -e $project_root/$required ]] || die "Missing $required. Extract the entire repository first."
done
as_root() {
  if ((EUID == 0)); then "$@"; else
    command -v sudo >/dev/null || die 'sudo is required to install/start system components.'
    sudo -- "$@"
  fi
}
package_installed() { [[ $(dpkg-query -W -f='${Status}' "$1" 2>/dev/null || true) == 'install ok installed' ]]; }
configure_docker_repo() {
  as_root apt-get update
  as_root apt-get install -y ca-certificates curl
  as_root install -m 0755 -d /etc/apt/keyrings
  as_root curl --fail --silent --show-error --location --proto '=https' --proto-redir '=https' \
    https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
  as_root chmod a+r /etc/apt/keyrings/docker.asc
  # Existing official sources are reused rather than overwritten.
  if ! grep -rqs 'download.docker.com/linux/ubuntu' /etc/apt/sources.list.d /etc/apt/sources.list; then
    printf 'Types: deb\nURIs: https://download.docker.com/linux/ubuntu\nSuites: %s\nComponents: stable\nArchitectures: %s\nSigned-By: /etc/apt/keyrings/docker.asc\n' "$codename" "$arch" |
      as_root tee /etc/apt/sources.list.d/docker.sources >/dev/null
  fi
  as_root apt-get update
}
if [[ -z $docker_bin ]]; then
  $no_install && die 'Docker is missing; --no-install prevents installing it.'
  conflicts=()
  for package in docker.io docker-compose docker-compose-v2 docker-doc docker-buildx podman-docker containerd runc; do
    if package_installed "$package"; then conflicts+=("$package"); fi
  done
  ((${#conflicts[@]} == 0)) || die "Existing packages conflict with Docker CE: ${conflicts[*]}. Review them before installing; this script will not remove them."
  log 'Installing Docker Engine and Compose from the official Docker apt repository...'
  configure_docker_repo
  as_root apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
  docker_bin=$(type -P docker) || die 'Docker executable was not found after installation.'
else
  log "Docker detected; skipping installation: $docker_bin"
fi
# Only a local Unix socket is permitted. Do not deploy to a remote Docker context.
endpoint=${DOCKER_HOST:-unix:///var/run/docker.sock}
[[ $endpoint == unix:///* ]] || die 'DOCKER_HOST points to a non-local engine. Unset it or select a local unix:/// socket.'
docker_prefix=()
apache_user=www-data
apache_group=www-data
owner_uid=$(stat -c '%u' "$project_root")
owner_gid=$(stat -c '%g' "$project_root")
if ((owner_uid > 0 && owner_gid > 0)); then
  apache_user="#$owner_uid"
  apache_group="#$owner_gid"
else
  printf 'NOTE: project is owned by root. Apache will use www-data; grant write access only to needed output directories if necessary.\n'
fi
docker_call() {
  "${docker_prefix[@]}" env -u DOCKER_HOST -u DOCKER_CONTEXT \
    "IDCC_HTTP_PORT=$port" "IDCC_APACHE_USER=$apache_user" "IDCC_APACHE_GROUP=$apache_group" \
    "$docker_bin" --host "$endpoint" "$@"
}
if ! docker_call info >/dev/null 2>&1; then
  if ((EUID != 0)) && command -v sudo >/dev/null; then docker_prefix=(sudo --); fi
  if ! docker_call info >/dev/null 2>&1; then
    [[ $endpoint == unix:///var/run/docker.sock ]] || die 'The selected local Docker socket is unavailable. Start that engine and rerun.'
    if command -v systemctl >/dev/null && [[ -d /run/systemd/system ]]; then
      log 'Starting the existing Docker service...'
      as_root systemctl enable --now docker
    else
      die 'Docker is not running. Start Docker manually (systemd is unavailable), then rerun.'
    fi
  fi
fi
[[ $(docker_call info --format '{{.OSType}}') == linux ]] || die 'A running local Linux Docker engine is required.'
if ! docker_call compose version >/dev/null 2>&1; then
  $no_install && die 'Docker Compose plugin is missing; --no-install prevents installing it.'
  log 'Installing the missing Compose plugin; preserving the existing Docker Engine...'
  if package_installed docker.io; then
    as_root apt-get update
    as_root apt-get install -y docker-compose-v2
  elif package_installed docker-ce || package_installed docker-ce-cli; then
    configure_docker_repo
    as_root apt-get install -y docker-compose-plugin
  else
    die 'Docker was installed by another method. Install its Compose v2+ plugin and rerun; the existing Docker installation was preserved.'
  fi
fi
compose=(compose --project-directory "$project_root" --file "$project_root/compose.yaml")
docker_call compose up --help | grep -- '--wait-timeout' >/dev/null || die 'Compose is too old. Update its plugin to a version supporting --wait-timeout, then rerun.'
log 'Validating Compose and building Apache/PHP (the first build can take several minutes)...'
# Never overwrite credentials for an existing PostgreSQL volume.
if [[ ! -e "$project_root/.env.postgres" ]]; then
  (umask 077
   password=$(od -An -N32 -tx1 /dev/urandom | tr -d ' \n')
   [[ ${#password} == 64 ]] || exit 1
   set -o noclobber
   printf 'POSTGRES_PASSWORD=%s\n' "$password" > "$project_root/.env.postgres") || die 'Cannot create PostgreSQL credential file.'
fi
docker_call "${compose[@]}" config --quiet
docker_call "${compose[@]}" up --detach --build --wait --wait-timeout 180
docker_call "${compose[@]}" ps
printf '\nDocker environment verified. Open http://127.0.0.1:%s/DemoBeta/Home/\n' "$port"
printf 'env is mounted read-only. Original database settings and application dependencies were preserved.\n'
printf 'NOTE: PhpSpreadsheet 1.12.0 declares PHP ^7.1; application compatibility with PHP 8.3 still needs validation.\n'

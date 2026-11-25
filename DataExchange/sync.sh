#!/usr/bin/env bash
set -Eeuo pipefail
project_root=$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")/.." && pwd -P)
check=false
yes=false
extra=()
while (($#)); do
  case $1 in
    --check) check=true; shift ;;
    --yes) yes=true; shift ;;
    --batch-size|--source-timeout)
      (($# >= 2)) || { printf 'Missing option value.\n' >&2; exit 1; }
      extra+=("$1" "$2"); shift 2 ;;
    -h|--help) printf 'Usage: bash DataExchange/sync.sh [--check] [--yes] [--batch-size 500] [--source-timeout 0]\n'; exit 0 ;;
    *) printf 'Unknown option. Use --help.\n' >&2; exit 1 ;;
  esac
done
docker_bin=$(type -P docker) || { printf 'Docker is missing. Run ubuntu/install.sh first.\n' >&2; exit 1; }
endpoint=${DOCKER_HOST:-unix:///var/run/docker.sock}
[[ $endpoint == unix:///* ]] || { printf 'Only a local Unix Docker socket is allowed.\n' >&2; exit 1; }
prefix=()
docker_call(){ "${prefix[@]}" env -u DOCKER_HOST -u DOCKER_CONTEXT "$docker_bin" --host "$endpoint" "$@"; }
if ! docker_call info >/dev/null 2>&1; then
  if ((EUID != 0)) && command -v sudo >/dev/null; then prefix=(sudo --); fi
  docker_call info >/dev/null 2>&1 || { printf 'Start the local Docker engine and retry.\n' >&2; exit 1; }
fi
compose=(compose --project-directory "$project_root" --file "$project_root/compose.yaml")
docker_call "${compose[@]}" exec -T web php DataExchange/sync.php --check "${extra[@]}"
$check && exit 0
if ! $yes; then
  printf '即將全量替換正式 IDCC 的七張目標表，來源零筆也會清空對應目標表。SPC 與 DemoIDCC 不會被修改。\n'
  read -r -p '請輸入大寫 SYNC 確認，其他輸入取消：' confirmation || exit 1
  [[ $confirmation == SYNC ]] || { printf '已取消，沒有更動資料。\n'; exit 0; }
fi
docker_call "${compose[@]}" exec -T web php DataExchange/sync.php --sync --yes "${extra[@]}"

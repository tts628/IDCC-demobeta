#!/usr/bin/env bash
set -Eeuo pipefail
project_root=$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")/.." && pwd -P)
for argument in "$@"; do
  case $argument in
    -h|--help)
      printf 'Usage: bash ubuntu/setup.sh [--screen main|database|pages] [--check]\n'
      exit 0 ;;
  esac
done
[[ $(uname -s) == Linux ]] || { printf 'This terminal launcher requires Ubuntu Linux. On Windows use windows/setup.cmd.\n' >&2; exit 1; }
# shellcheck disable=SC1091
source /etc/os-release
[[ ${ID:-} == ubuntu ]] || { printf 'Ubuntu is required.\n' >&2; exit 1; }
if [[ ! -x /usr/bin/python3 ]]; then
  if [[ " $* " == *' --check '* ]]; then
    printf 'Python 3 is missing; --check never installs packages. Install python3 first.\n' >&2
    exit 1
  fi
  printf 'Installing the missing Ubuntu Python 3 runtime for the terminal setup...\n'
  prefix=()
  if ((EUID != 0)); then prefix=(sudo --); fi
  "${prefix[@]}" apt-get update
  "${prefix[@]}" apt-get install -y python3
fi
exec /usr/bin/python3 "$project_root/ubuntu/setup_tui.py" "$@"

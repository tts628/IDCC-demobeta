#!/usr/bin/env bash
# Keep WSL alive for the current Windows login session; no scheduled task is installed.
set -Eeuo pipefail
exec flock -n /run/idcc-demobeta-keepalive.lock sleep infinity

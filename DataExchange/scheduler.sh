#!/usr/bin/env bash
set -uo pipefail
interval=${DATA_EXCHANGE_INTERVAL_SECONDS:-600}
if ! [[ $interval =~ ^[0-9]+$ && ${#interval} -le 5 ]]; then
  printf 'Invalid interval.\n' >&2; exit 2;
fi
if ((10#$interval < 60 || 10#$interval > 86400)); then
  printf 'Invalid interval: use 60..86400 seconds.\n' >&2; exit 2;
fi
interval=$((10#$interval))
project_root=$(cd -- "$(dirname -- "${BASH_SOURCE[0]}")/.." && pwd -P) || exit 1
child=''
stopping=false
stop(){
  stopping=true
  if [[ -n $child ]]; then kill -TERM "$child" 2>/dev/null || true; fi
}
trap stop TERM INT
printf 'DataExchange scheduler: every %s seconds; first run immediately.\n' "$interval"
while ! $stopping; do
  started=$SECONDS
  printf '[%s] Sync starting.\n' "$(date -u +%FT%TZ)"
  php "$project_root/DataExchange/sync.php" --sync --yes &
  child=$!
  wait "$child"; code=$?
  if $stopping; then wait "$child" 2>/dev/null || true; break; fi
  child=''
  printf '[%s] Sync finished; exit code %s.\n' "$(date -u +%FT%TZ)" "$code"
  delay=$((interval - (SECONDS - started)))
  # Do not queue missed runs or retry continuously after an overlong run.
  if ((delay <= 0)); then delay=$interval; fi
  sleep "$delay" &
  child=$!
  wait "$child" || true
  if $stopping; then wait "$child" 2>/dev/null || true; break; fi
  child=''
done
printf 'DataExchange scheduler stopped.\n'

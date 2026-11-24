<?php
// Compatibility is limited to old SQL settings before the one-time layout upgrade.
function idccDemoValue(string $suffix) {
    $value = getenv('DEMOIDCC_DB_' . $suffix);
    if ($value !== false) return $value;
    if (getenv('IDCC_CONNECTION_LAYOUT') === '2' || strtolower(getenv('IDCC_DB_DRIVER') ?: '') === 'pgsql') return false;
    foreach (['HOST','NAME','UID','PWD','TRUST_SERVER_CERTIFICATE'] as $key) if (getenv('DEMOIDCC_DB_' . $key) !== false) return false;
    return getenv('IDCC_DB_' . $suffix);
}

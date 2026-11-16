<?php
// Run inside the container: php docker/diagnose-db.php [IDCC|SPC]
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
ini_set('display_errors', '0');

function idccReport(string $stage, bool $ok, array $details = []): void
{
    echo json_encode(['stage' => $stage, 'ok' => $ok] + $details, JSON_UNESCAPED_SLASHES) . PHP_EOL;
    flush();
}

function idccErrorSummary(array $errors): array
{
    $result = [];
    foreach ($errors as $error) {
        $message = strtolower((string) ($error['message'] ?? ''));
        $code = (int) ($error['code'] ?? 0);
        $category = 'SQL_ERROR';
        if (str_contains($message, 'certificate') || str_contains($message, 'ssl provider')) {
            $category = 'TLS_CERTIFICATE';
        } elseif ($code === 18456 || str_contains($message, 'login failed')) {
            $category = 'LOGIN_FAILED';
        } elseif ($code === 4060) {
            $category = 'DATABASE_UNAVAILABLE_OR_DENIED';
        } elseif ($code === 208) {
            $category = 'TABLE_OR_SCHEMA_NOT_FOUND';
        } elseif ($code === 229) {
            $category = 'PERMISSION_DENIED';
        } elseif (in_array($code, [245, 8114], true)) {
            $category = 'DATA_TYPE_CONVERSION';
        } elseif (str_contains($message, 'timeout') || str_contains($message, 'timed out')) {
            $category = 'TIMEOUT';
        } elseif (($error['SQLSTATE'] ?? '') === '08001') {
            $category = 'NETWORK_OR_CONNECTION';
        }
        // Do not print driver messages: they may contain server/login names.
        $result[] = ['category' => $category, 'sqlstate' => $error['SQLSTATE'] ?? '', 'code' => $code];
    }
    return $result;
}

function idccQuery($connection, string $sql, array $params = []): array
{
    $statement = @sqlsrv_query($connection, $sql, $params, ['QueryTimeout' => 8]);
    if ($statement === false) {
        return ['ok' => false, 'errors' => idccErrorSummary(sqlsrv_errors(SQLSRV_ERR_ERRORS) ?: [])];
    }
    $row = sqlsrv_fetch_array($statement, SQLSRV_FETCH_ASSOC);
    sqlsrv_free_stmt($statement);
    return ['ok' => true, 'row' => $row];
}

function idccDiagnose(array $arguments): int
{
    $database = strtoupper($arguments[1] ?? 'IDCC');
    if (!in_array($database, ['IDCC', 'SPC'], true)) {
        idccReport('arguments', false, ['usage' => 'php docker/diagnose-db.php [IDCC|SPC]']);
        return 2;
    }
    $keys = array_map(static fn($suffix) => $database . '_DB_' . $suffix, ['HOST', 'NAME', 'UID', 'PWD']);
    $values = array_map(static fn($key) => getenv($key), $keys);
    $missing = [];
    foreach ($keys as $index => $key) {
        if ($values[$index] === false || $values[$index] === '') {
            $missing[] = $key;
        }
    }
    idccReport('environment', !$missing, ['database_slot' => $database, 'missing_keys' => $missing]);
    if ($missing) {
        return 2;
    }
    $drivers = extension_loaded('sqlsrv') && extension_loaded('pdo_sqlsrv');
    idccReport('drivers', $drivers, ['php' => PHP_VERSION, 'sqlsrv' => extension_loaded('sqlsrv'), 'pdo_sqlsrv' => extension_loaded('pdo_sqlsrv')]);
    if (!$drivers) {
        return 2;
    }
    [$host, $name, $user, $password] = $values;
    $trustServerCertificate = filter_var(getenv($database . '_DB_TRUST_SERVER_CERTIFICATE') ?: 'false', FILTER_VALIDATE_BOOLEAN);
    idccReport('tls_settings', true, ['encrypt' => true, 'trust_server_certificate' => $trustServerCertificate]);
    $options = ['Database' => $name, 'UID' => $user, 'PWD' => $password, 'CharacterSet' => 'UTF-8', 'LoginTimeout' => 8, 'Encrypt' => true, 'TrustServerCertificate' => $trustServerCertificate];
    $connection = @sqlsrv_connect($host, $options);
    if (!$connection) {
        idccReport('sqlsrv_connect', false, ['errors' => idccErrorSummary(sqlsrv_errors(SQLSRV_ERR_ERRORS) ?: [])]);
        return 1;
    }
    idccReport('sqlsrv_connect', true);
    try {
        $tlsDsn = ';Encrypt=1;TrustServerCertificate=' . ($trustServerCertificate ? '1' : '0');
        $pdo = new PDO("sqlsrv:server=$host;Database=$name;LoginTimeout=8" . $tlsDsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        $pdo->query('SELECT 1')->fetchColumn();
        idccReport('pdo_connect', true);
    } catch (PDOException $error) {
        $info = $error->errorInfo ?: [];
        idccReport('pdo_connect', false, ['errors' => idccErrorSummary([['SQLSTATE' => $info[0] ?? '', 'code' => $info[1] ?? 0, 'message' => $info[2] ?? $error->getMessage()]])]);
        sqlsrv_close($connection);
        return 1;
    }
    idccReport('clock', true, ['php_now' => date(DATE_ATOM), 'timezone' => date_default_timezone_get()]);
    if ($database === 'IDCC') {
        foreach (['DispenserMachines', 'DispenserEvent', 'DyeMachines', 'DyeEvent', 'DyeEnergyUsage', 'FinishEnergyUsage'] as $table) {
            $probe = idccQuery($connection, "SELECT TOP (1) 1 AS present FROM [$table]");
            idccReport('table', $probe['ok'], ['table' => $table] + ($probe['ok'] ? ['has_rows' => is_array($probe['row'])] : ['errors' => $probe['errors']]));
        }
        $windows = [
            ['DispenserProduction', date('Y-m-d H:i:s', strtotime('-1 day')), date('Y-m-d H:i:s')],
            ['DyeProduction', date('Y-m-d', strtotime('-1 day')) . ' 00:00:00.000', date('Y-m-d', strtotime('-1 day')) . ' 23:59:59.999'],
        ];
        foreach ($windows as [$table, $start, $end]) {
            $latest = idccQuery($connection, "SELECT TOP (1) EndTime FROM [$table] WHERE EndTime IS NOT NULL ORDER BY EndTime DESC");
            if (!$latest['ok']) {
                idccReport('data_window', false, ['table' => $table, 'errors' => $latest['errors']]);
                continue;
            }
            $last = $latest['row']['EndTime'] ?? null;
            if ($last instanceof DateTimeInterface) { $last = $last->format('Y-m-d H:i:s'); }
            $within = idccQuery($connection, "SELECT TOP (1) 1 AS present FROM [$table] WHERE EndTime > ? AND EndTime < ?", [$start, $end]);
            idccReport('data_window', $within['ok'], ['table' => $table, 'start' => $start, 'end' => $end, 'latest_end_time' => $last] + ($within['ok'] ? ['has_rows_in_window' => is_array($within['row'])] : ['errors' => $within['errors']]));
        }
        // Exercise the same HTTP/PHP endpoints the homepage uses, without printing records.
        foreach (['get_data_2.php', 'get_data_4.php', 'get_table_2.php', 'get_table_4.php'] as $endpoint) {
            $http_response_header = [];
            $body = @file_get_contents('http://127.0.0.1/DemoBeta/Home/Legacy/Homepage/' . $endpoint, false, stream_context_create(['http' => ['timeout' => 12, 'ignore_errors' => true]]));
            $payload = $body === false ? null : json_decode($body, true);
            $valid = $body !== false && json_last_error() === JSON_ERROR_NONE && is_array($payload) && preg_match('~^HTTP/\S+ 200\b~', $http_response_header[0] ?? '');
            idccReport('homepage_api', (bool) $valid, ['endpoint' => $endpoint, 'http_status' => $http_response_header[0] ?? 'NO_RESPONSE', 'valid_json_array' => (bool) $valid]);
        }
    }
    sqlsrv_close($connection);
    idccReport('finished', true, ['note' => 'Review every stage; finished does not mean every stage passed. No credentials or record contents were printed.']);
    return 0;
}

if (realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === __FILE__) {
    exit(idccDiagnose($argv));
}

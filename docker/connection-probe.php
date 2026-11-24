<?php
// Shared by CLI diagnostics. No application tables or HTTP endpoints are queried.
function idccConnectionCategory(string $message, string $state = '', int $code = 0): string
{
    $message = strtolower($message);
    if (str_contains($message, 'certificate') || str_contains($message, 'ssl provider')) return 'TLS_CERTIFICATE';
    if ($code === 18456 || $state === '28000' || $state === '28P01' || str_contains($message, 'login failed') || str_contains($message, 'password authentication failed')) return 'LOGIN_FAILED';
    if ($code === 4060 || $state === '3D000' || str_contains($message, 'does not exist') || str_contains($message, 'cannot open database')) return 'DATABASE_UNAVAILABLE_OR_DENIED';
    if (str_contains($message, 'timeout') || str_contains($message, 'timed out') || in_array($state, ['HYT00', 'HYT01'], true)) return 'TIMEOUT';
    if (str_starts_with($state, '08') || str_contains($message, 'connection refused') || str_contains($message, 'could not translate host') || str_contains($message, 'could not resolve') || str_contains($message, 'server was not found')) return 'NETWORK_OR_CONNECTION';
    return 'CONNECTION_FAILED';
}

function idccConnectionProbe(string $slot, array $services = []): array
{
    $env = $services['env'] ?? static fn($key) => getenv($key);
    $driver = $services['driver'] ?? static fn($name) => extension_loaded($name);
    $pdoFactory = $services['pdo'] ?? static fn($dsn, $user, $password, $options) => new PDO($dsn, $user, $password, $options);
    $result = ['stage'=>'connection_result', 'database_slot'=>$slot, 'status'=>'failed', 'ok'=>false, 'drivers'=>[]];
    $get = static function ($key, $fallback = null) use ($env) {
        $value = $env($key);
        if (($value === false || $value === '') && $fallback !== null) $value = $env($fallback);
        return $value;
    };
    if ($slot === 'PostgreSQL') {
        $host = $get('IDCC_PG_HOST') ?: 'postgres';
        $port = $get('IDCC_PG_PORT') ?: '5432';
        $name = $get('POSTGRES_DB') ?: 'idcc';
        $user = $get('POSTGRES_USER') ?: 'idcc';
        $password = $get('POSTGRES_PASSWORD');
        if ($password === false || $password === '') return array_replace($result, ['status'=>'not_configured','ok'=>null,'reason'=>'NOT_CONFIGURED']);
        foreach ([$host, $port, $name] as $part) {
            if (preg_match('/[;\x00-\x20]/', $part)) return $result + ['reason'=>'INVALID_SETTING'];
        }
        if (!$driver('pdo_pgsql')) return $result + ['reason'=>'DRIVER_MISSING'];
        $dsn = "pgsql:host=$host;port=$port;dbname=$name;connect_timeout=8";
        $pdoName = 'PDO_PGSQL';
    } elseif (in_array($slot, ['IDCC','SPC'], true)) {
        $fallback = $slot === 'SPC' ? ['HOST'=>'HOST','NAME'=>'MDSVN_SPC_DB','UID'=>'UID','PWD'=>'PWD'] : [];
        $values = [];
        $missing = [];
        foreach (['HOST','NAME','UID','PWD'] as $key) {
            $values[$key] = $get($slot . '_DB_' . $key, $fallback[$key] ?? null);
            if ($values[$key] === false || $values[$key] === '') $missing[] = $key;
        }
        if ($missing) return array_replace($result, ['status'=>'not_configured','ok'=>null,'reason'=>'NOT_CONFIGURED','missing_keys'=>$missing]);
        ['HOST'=>$host,'NAME'=>$name,'UID'=>$user,'PWD'=>$password] = $values;
        if (preg_match('/[;\x00]/', $host . $name)) return $result + ['reason'=>'INVALID_SETTING'];
        $trust = filter_var($get($slot . '_DB_TRUST_SERVER_CERTIFICATE') ?: 'false', FILTER_VALIDATE_BOOLEAN);
        if (!$driver('sqlsrv')) {
            $result['drivers'][] = ['name'=>'SQLSRV','ok'=>false,'category'=>'DRIVER_MISSING'];
        } else {
            $connect = $services['sqlsrv_connect'] ?? 'sqlsrv_connect';
            $close = $services['sqlsrv_close'] ?? 'sqlsrv_close';
            $errors = $services['sqlsrv_errors'] ?? static fn() => sqlsrv_errors(SQLSRV_ERR_ERRORS) ?: [];
            try {
                $connection = @$connect($host, ['Database'=>$name,'UID'=>$user,'PWD'=>$password,'CharacterSet'=>'UTF-8','LoginTimeout'=>8,'Encrypt'=>true,'TrustServerCertificate'=>$trust]);
                if ($connection) {
                    $close($connection);
                    $result['drivers'][] = ['name'=>'SQLSRV','ok'=>true];
                } else {
                    $categories = [];
                    foreach ($errors() as $error) $categories[] = idccConnectionCategory((string) ($error['message'] ?? ''), (string) ($error['SQLSTATE'] ?? ''), (int) ($error['code'] ?? 0));
                    // Prefer an actionable login/certificate error over a generic connection error.
                    $category = 'CONNECTION_FAILED';
                    foreach (['TLS_CERTIFICATE','LOGIN_FAILED','DATABASE_UNAVAILABLE_OR_DENIED','TIMEOUT','NETWORK_OR_CONNECTION'] as $candidate) if (in_array($candidate, $categories, true)) { $category = $candidate; break; }
                    $result['drivers'][] = ['name'=>'SQLSRV','ok'=>false,'category'=>$category];
                }
            } catch (Throwable $error) {
                $result['drivers'][] = ['name'=>'SQLSRV','ok'=>false,'category'=>'CONNECTION_FAILED'];
            }
        }
        $pdoName = 'PDO_SQLSRV';
        $dsn = "sqlsrv:server=$host;Database=$name;LoginTimeout=8;Encrypt=1;TrustServerCertificate=" . ($trust ? '1' : '0');
        if (!$driver('pdo_sqlsrv')) {
            $result['drivers'][] = ['name'=>$pdoName,'ok'=>false,'category'=>'DRIVER_MISSING'];
            return $result;
        }
    } else {
        throw new InvalidArgumentException('Unknown database slot.');
    }
    try {
        $pdo = $pdoFactory($dsn, $user, $password, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
        // Constant-only probe; no table/schema/data/date checks.
        $pdo->query('SELECT 1')->fetchColumn();
        $pdo = null;
        $result['drivers'][] = ['name'=>$pdoName,'ok'=>true];
    } catch (Throwable $error) {
        $info = $error instanceof PDOException ? ($error->errorInfo ?: []) : [];
        $result['drivers'][] = ['name'=>$pdoName,'ok'=>false,'category'=>idccConnectionCategory($error->getMessage(), (string) ($info[0] ?? $error->getCode()), (int) ($info[1] ?? 0))];
    }
    $failed = array_filter($result['drivers'], static fn($item) => !$item['ok']);
    $result['status'] = $failed ? 'failed' : 'success';
    $result['ok'] = !$failed;
    return $result;
}

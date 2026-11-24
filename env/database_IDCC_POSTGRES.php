<?php
$primary = strtolower(getenv('IDCC_DB_DRIVER') ?: '') === 'pgsql';
$host = ($primary ? getenv('IDCC_DB_HOST') : false) ?: getenv('IDCC_PG_HOST') ?: 'postgres';
$port = ($primary ? getenv('IDCC_DB_PORT') : false) ?: getenv('IDCC_PG_PORT') ?: '5432';
$Database = ($primary ? getenv('IDCC_DB_NAME') : false) ?: getenv('POSTGRES_DB') ?: 'idcc';
$UID = ($primary ? getenv('IDCC_DB_UID') : false) ?: getenv('POSTGRES_USER') ?: 'idcc';
$PWD = ($primary ? getenv('IDCC_DB_PWD') : false) ?: getenv('POSTGRES_PASSWORD');
if (!$PWD) throw new \RuntimeException('Missing PostgreSQL password. Run the Docker installer.');
foreach ([$host, $port, $Database] as $part) {
    if (preg_match('/[;\x00-\x20]/', $part)) throw new \RuntimeException('Invalid PostgreSQL connection setting.');
}
try {
    $PDOconn = new \PDO("pgsql:host=$host;port=$port;dbname=$Database;connect_timeout=8", $UID, $PWD, [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    ]);
} catch (\PDOException $error) {
    throw new \RuntimeException('PostgreSQL connection failed. Run docker/diagnose-idcc.php.');
}
// A PostgreSQL PDO object cannot be passed to legacy sqlsrv_* functions.
$conn = null;
$serverName = null;
$connectionInfo = null;

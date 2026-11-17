<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once dirname(__DIR__) . '/env/idcc-mode.php';
$demo = idccDemoMode();
echo json_encode(['demo_mode' => $demo, 'driver' => $demo ? 'sqlsrv' : 'pgsql']) . PHP_EOL;
// Probe PostgreSQL even while Demo mode remains enabled. Never print credentials.
try {
    require dirname(__DIR__) . '/env/database_IDCC_POSTGRES.php';
    $PDOconn->query('SELECT 1')->fetchColumn();
    echo json_encode(['stage' => 'postgres_connect', 'ok' => true]) . PHP_EOL;
} catch (\Throwable $error) {
    echo json_encode(['stage' => 'postgres_connect', 'ok' => false]) . PHP_EOL;
    exit(1);
}

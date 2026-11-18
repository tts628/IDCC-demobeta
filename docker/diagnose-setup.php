<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$failed = false;
passthru(escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg(__DIR__ . '/diagnose-idcc.php'), $code);
$failed = $code !== 0;
foreach (['IDCC', 'SPC'] as $slot) {
    $ready = true;
    foreach (['HOST','NAME','UID','PWD'] as $key) if (!getenv($slot . '_DB_' . $key)) $ready = false;
    if (!$ready) {
        echo json_encode(['stage'=>'sqlserver_test','database_slot'=>$slot,'skipped'=>true,'reason'=>'not_configured']) . PHP_EOL;
        continue;
    }
    passthru(escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg(__DIR__ . '/diagnose-db.php') . ' ' . $slot, $code);
    $failed = $failed || $code !== 0;
}
exit($failed ? 1 : 0);

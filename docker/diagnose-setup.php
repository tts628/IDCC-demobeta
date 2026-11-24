<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
ini_set('display_errors', '0');
require_once __DIR__ . '/connection-probe.php';
$human = in_array('--human', $argv, true);
$reasons = [
    'TLS_CERTIFICATE'=>'憑證驗證未通過', 'LOGIN_FAILED'=>'帳號或密碼不正確',
    'DATABASE_UNAVAILABLE_OR_DENIED'=>'資料庫不存在或無存取權限',
    'TIMEOUT'=>'連線逾時', 'NETWORK_OR_CONNECTION'=>'無法連到主機，請檢查網路與連接埠',
    'DRIVER_MISSING'=>'缺少必要驅動', 'INVALID_SETTING'=>'設定格式不正確',
    'CONNECTION_FAILED'=>'無法建立連線', 'CHECK_FAILED'=>'無法執行測試',
];
$failed = false;
foreach (['PostgreSQL','IDCC','SPC'] as $slot) {
    if ($human) echo '正在測試 ' . $slot . '…' . PHP_EOL;
    else echo json_encode(['stage'=>'connection_start','database_slot'=>$slot]) . PHP_EOL;
    flush();
    try { $result = idccConnectionProbe($slot); }
    catch (Throwable $error) { $result = ['stage'=>'connection_result','database_slot'=>$slot,'status'=>'failed','ok'=>false,'reason'=>'CHECK_FAILED','drivers'=>[]]; }
    $failed = $failed || $result['status'] === 'failed';
    if (!$human) echo json_encode($result, JSON_UNESCAPED_SLASHES) . PHP_EOL;
    elseif ($result['status'] === 'success') echo $slot . '：連線成功' . PHP_EOL;
    elseif ($result['status'] === 'not_configured') echo $slot . '：尚未設定或設定未完整，已略過' . PHP_EOL;
    else {
        $details = [];
        foreach ($result['drivers'] as $method) $details[] = $method['name'] . '：' . ($method['ok'] ? '成功' : ($reasons[$method['category']] ?? '無法建立連線'));
        echo $slot . '：連線失敗（' . ($details ? implode('；', $details) : ($reasons[$result['reason'] ?? ''] ?? '無法執行測試')) . '）' . PHP_EOL;
    }
    flush();
}
exit($failed ? 1 : 0);

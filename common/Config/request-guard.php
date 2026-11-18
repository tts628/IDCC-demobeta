<?php
if (PHP_SAPI !== 'cli') {
    require_once __DIR__ . '/PageFeatures.php';
    $root = str_replace('\\', '/', dirname(__DIR__, 2)) . '/';
    $script = str_replace('\\', '/', realpath($_SERVER['SCRIPT_FILENAME'] ?? '') ?: '');
    try {
        if (str_starts_with($script, $root) && !idccPathAllowed(substr($script, strlen($root)))) {
            http_response_code(403);
            header('Content-Type: text/html; charset=utf-8');
            echo '<!doctype html><meta charset="utf-8"><h2>此頁面未啟用</h2><p>請透過 Windows 頁面設定工具重新勾選。</p>';
            exit;
        }
    } catch (Throwable $error) {
        http_response_code(503); exit('Page settings are invalid. Open the local page setup tool.');
    }
}

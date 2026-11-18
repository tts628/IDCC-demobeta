<?php
function idccRequireDatabaseSettings(array $settings): void {
    foreach ($settings as $value) {
        if ($value !== false && $value !== null && $value !== '') continue;
        if (PHP_SAPI === 'cli') throw new RuntimeException('Database is not configured. Run windows/database-setup.cmd.');
        http_response_code(503);
        header('Content-Type: text/html; charset=utf-8');
        echo '<!doctype html><meta charset="utf-8"><h2>尚未設定資料庫</h2><p>環境已安裝。請在電腦上開啟 windows/database-setup.cmd，填寫連線資訊並按「儲存並套用」。</p>';
        exit;
    }
}

<?php
header('Content-Type: application/json');
$required = ['curl', 'openssl', 'ctype', 'dom', 'fileinfo', 'gd', 'iconv', 'mbstring',
    'SimpleXML', 'xml', 'xmlreader', 'xmlwriter', 'zip', 'mysqli', 'pdo_mysql', 'sqlsrv', 'pdo_sqlsrv'];
$missing = array_values(array_diff($required, get_loaded_extensions()));
$root = rtrim($_SERVER['DOCUMENT_ROOT'] ?? '/var/www/html', '/');
$assets = [
    '/common/IDCC_plugin/jquery/jquery.min.js',
    '/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.min.css',
    '/common/IDCC_plugin/bootstrap-5.0.0-dist/js/bootstrap.min.js',
    '/common/IDCC_plugin/@popperjs/core/dist/umd/popper.min.js',
    '/common/IDCC_plugin/jquery-ui-1.13.2.custom/jquery-ui.min.js',
    '/common/IDCC_plugin/bootstrap-toggle/bootstrap-toggle.min.js',
    '/common/IDCC_plugin/bootstrap-toggle/bootstrap-toggle.min.css',
    '/common/IDCC_plugin/fa-icons/fa-icons.css',
    '/common/IDCC_plugin/apexcharts/apexcharts.js',
    '/common/IDCC_plugin/ag-grid/ag-grid-community.min.js',
    '/common/IDCC_plugin/sweetalert/dist/sweetalert2.min.js',
    '/common/IDCC_plugin/sweetalert/dist/sweetalert2.min.css',
    '/common/language/language.php',
    '/common/language/zh-tw/sidebar.php',
    '/common/language/en-us/sidebar.php',
    '/common/legacyTemplate/head.php',
    '/common/IDCC_plugin/legacyPlugins/google1.css',
    '/common/IDCC_plugin/legacyPlugins/google_icon.woff2',
    '/common/autoload.php',
    '/common/Config/DbMapper.php',
];
$missingAssets = array_values(array_filter($assets, static function ($path) use ($root) {
    $handle = @fopen($root . $path, 'rb');
    if ($handle === false) {
        return true;
    }
    fclose($handle);
    return false;
}));
$ok = !$missing && !$missingAssets;
http_response_code($ok ? 200 : 503);
echo json_encode(['ok' => $ok, 'php' => PHP_VERSION, 'missing' => $missing, 'missingAssets' => $missingAssets]);

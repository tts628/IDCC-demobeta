<?php
header('Content-Type: application/json');
$required = ['curl', 'openssl', 'ctype', 'dom', 'fileinfo', 'gd', 'iconv', 'mbstring',
    'SimpleXML', 'xml', 'xmlreader', 'xmlwriter', 'zip', 'mysqli', 'pdo_mysql', 'sqlsrv', 'pdo_sqlsrv'];
$missing = array_values(array_diff($required, get_loaded_extensions()));
http_response_code($missing ? 503 : 200);
echo json_encode(['ok' => !$missing, 'php' => PHP_VERSION, 'missing' => $missing]);

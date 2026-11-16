<?php
$context = stream_context_create(['http' => ['timeout' => 4, 'ignore_errors' => true]]);
$response = @file_get_contents('http://127.0.0.1/__idcc_health', false, $context);
$health = $response === false ? null : json_decode($response, true);
if (!is_array($health) || ($health['ok'] ?? false) !== true) {
    fwrite(STDERR, "PHP extensions or shared assets are unavailable.\n");
    exit(1);
}
// Exercise Apache routing as well as filesystem checks, without opening a database.
foreach (['/common/IDCC_plugin/jquery/jquery.min.js', '/DemoBeta/Home/', '/DemoBeta/Home/Legacy/Homepage/'] as $path) {
    $http_response_header = [];
    $body = @file_get_contents('http://127.0.0.1' . $path, false, $context);
    $status = $http_response_header[0] ?? '';
    if ($body === false || !preg_match('~^HTTP/\S+ 200\b~', $status) || $body === '') {
        fwrite(STDERR, "Homepage or shared asset HTTP check failed: $path\n");
        exit(1);
    }
}
exit(0);

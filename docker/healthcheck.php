<?php
$context = stream_context_create(['http' => ['timeout' => 4, 'ignore_errors' => true]]);
$response = @file_get_contents('http://127.0.0.1/__idcc_health', false, $context);
$health = $response === false ? null : json_decode($response, true);
exit(is_array($health) && ($health['ok'] ?? false) === true ? 0 : 1);

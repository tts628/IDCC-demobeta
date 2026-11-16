<?php
// Load shared classes without importing another deployment's client configuration.
spl_autoload_register(static function (string $class): void {
    if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*(?:\\\\[A-Za-z_][A-Za-z0-9_]*)*$/D', $class)) {
        return;
    }
    $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});

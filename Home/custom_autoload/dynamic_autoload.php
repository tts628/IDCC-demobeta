<?php
// dynamic_autoload.php

spl_autoload_register(function ($class) {
    // Get the client root from the request URI
    $clientRoot = explode('/', trim($_SERVER["REQUEST_URI"], '/'))[0];

    if ($clientRoot === "DemoBeta") {
        $clientRoot = $_GET["client"] ?? "Demo";
    }

    $rootPath = $_SERVER['DOCUMENT_ROOT'];

    // Load the clients map
    $clientsMap = require "$rootPath/common/Config/clients.php";

    // Find the corresponding client directory
    $client = null;
    foreach ($clientsMap as $clientDir => $clientRoots) {
        if (in_array($clientRoot, $clientRoots, true)) {
            $client = $clientDir;
            break;
        }
    }

    // Handle the case where the client was not found
    if ($client === null) {
        throw new Exception("Client '$clientRoot' not found in the configuration.");
    }

    // Construct the base path and class file path
    $basePath = __DIR__ . '/../features/dataUpload/models/extractorApi/' . $client . '/';
    $prefix = 'DataUpload\\Models\\Extractor\\';
    $len = strlen($prefix);

    // Ensure the class matches the expected namespace prefix
    if (strncmp($prefix, $class, $len) !== 0) {
        return; // Not the correct namespace, return to let other autoloaders handle it
    }

    // Build the file path based on the class name
    $relativeClass = substr($class, $len);
    $file = $basePath . str_replace('\\', '/', $relativeClass) . '.php';

    // Include the file if it exists
    if (file_exists($file)) {
        require $file;
    } else {
        throw new Exception("File not found: $file");
    }
});

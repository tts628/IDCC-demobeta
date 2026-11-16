<?php
// ExcelProcessorLoader.php
namespace DataUpload\Models;

/**
 * Function to load all processors from a dynamically determined directory.
 *
 * @return array An array of instantiated processor objects.
 */
function loadProcessors()
{
    $files = scandir(buildProcessorPath());
    $processors = [];

    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            $className = pathinfo($file, PATHINFO_FILENAME);
            $fullyQualifiedClassName = "DataUpload\Models\Processor\\$className";

            if (class_exists($fullyQualifiedClassName) && is_subclass_of($fullyQualifiedClassName, ProcessorAbstract::class)) {
                $processors[] = new $fullyQualifiedClassName();
            }
        }
    }

    return $processors;
}

/**
 * Helper function to build the dynamic path to the processors.
 *
 * @return string The full path to the processor directory.
 */
function buildProcessorPath()
{
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

    // Construct the processor directory path
    $processorDir = realpath(__DIR__ . '/processorApi/' . $client);

    return $processorDir;
}

return loadProcessors();
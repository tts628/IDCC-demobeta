<?php
// file_validation.php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Define the log directory and log file
$logDirectory = __DIR__ . '/logs';
$logFile = $logDirectory . '/execution_time_and_memory.log';

// Set dev switch
$log = false; // Change to false to disable logging

// Ensure the log directory exists if logging is enabled
if ($log && !is_dir($logDirectory)) {
    mkdir($logDirectory, 0777, true);
}

// Record the start time of the script and memory if logging is enabled
if ($log) {
    $startTime = microtime(true);
    $initialMemory = memory_get_usage();
}

// Register a shutdown function to log in case of script termination, if logging is enabled
if ($log) {
    register_shutdown_function(function () use ($startTime, $initialMemory, $logFile) {
        $endTime = microtime(true);
        $finalMemory = memory_get_usage();
        $peakMemory = memory_get_peak_usage();
        $executionTime = $endTime - $startTime;

        $logMessage = date('Y-m-d H:i:s') . " - Script execution time: " . $executionTime . " seconds" . PHP_EOL;
        $logMessage .= "Initial memory usage: " . $initialMemory . " bytes" . PHP_EOL;
        $logMessage .= "Final memory usage: " . $finalMemory . " bytes" . PHP_EOL;
        $logMessage .= "Peak memory usage: " . $peakMemory . " bytes" . PHP_EOL . PHP_EOL;

        file_put_contents($logFile, $logMessage, FILE_APPEND);
    });
}

require_once realpath('../../vendor/autoload.php');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");

use DataUpload\Models\Utils\SheetTermFinder;
use DataUpload\Models\Utils\SpreadsheetLoader;
use DataUpload\Models\ExcelProcessors\BasicExcelProcessor;
use DataUpload\Models\ExcelProcessors\ExcelProcessor;
use DataUpload\Models\CsvProcessors\CsvProcessor;
use DataUpload\Models\BatchValidator;
use DataUpload\Models\TypeValidator;
use DataUpload\Controllers\FileUploadController;
use DataUpload\Models\DatabaseOperations\FileMapperFetcher;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;

try {
    $sheetTermFinder = new SheetTermFinder();
    $spreadsheetLoader = new SpreadsheetLoader();
    $basicExcelProcessor = new BasicExcelProcessor();
    $fileMapperFetcher = new FileMapperFetcher();
    $excelProcessor = new ExcelProcessor($spreadsheetLoader, $sheetTermFinder, $basicExcelProcessor, $fileMapperFetcher);
    $csvProcessor = new CsvProcessor();
    $dataValidator = new BatchValidator($excelProcessor, $csvProcessor);
    $typeValidator = new TypeValidator();
    $fileUploadController = new FileUploadController($dataValidator, $typeValidator);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $psr17Factory = new Psr17Factory();
        $creator = new ServerRequestCreator(
            $psr17Factory, // ServerRequestFactory
            $psr17Factory, // UriFactory
            $psr17Factory, // UploadedFileFactory
            $psr17Factory  // StreamFactory
        );

        $request = $creator->fromGlobals();
        $response = $psr17Factory->createResponse();

        $response = $fileUploadController->handleUpload($request, $response);

        // Emit the response
        header(sprintf('HTTP/%s %s %s', $response->getProtocolVersion(), $response->getStatusCode(), $response->getReasonPhrase()));
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
        echo $response->getBody();
    }
} catch (Exception $e) {
    if ($log) {
        error_log($e->getMessage(), 3, $logFile);
    }
    http_response_code(500);
    echo json_encode(['error' => 'An unexpected error occurred.']);
}

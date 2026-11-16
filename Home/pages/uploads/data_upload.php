<?php
// data_upload.php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// ini_set('max_execution_time', -1);
ini_set('memory_limit', '256M');

// Set dev switch
$log = false; // Change to false to disable logging

// Define the log directory and log file
$logDirectory = __DIR__ . '/logs';
$logFile = $logDirectory . '/data_upload.log';

// Record the start time and memory usage if logging is enabled
if ($log) {
    if (!is_dir($logDirectory)) {
        mkdir($logDirectory, 0777, true);
    }
    $startTime = microtime(true);
    $initialMemory = memory_get_usage();
}

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();

require_once realpath('../../vendor/autoload.php');

use DataUpload\Models\DataSanitizer;
use DataUpload\Models\DatabaseOperations\ColumnRetriever;
use DataUpload\Models\DatabaseOperations\InsertValuePreparer;
use DataUpload\Models\DatabaseOperations\InsertQueryBuilder;
use DataUpload\Models\DatabaseOperations\DataInserter;
use DataUpload\Models\DatabaseOperations\IdentitySelector;
use DataUpload\Models\DatabaseOperations\InsertHandler;
use DataUpload\Models\DatabaseOperations\UpdateData;
use DataUpload\Models\DataUploader;
use DataUpload\Controllers\DataUploadController;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;


$dataSanitizer = new DataSanitizer();
$columnRetriever = new ColumnRetriever($dbMapper);
$valuePreparer = new InsertValuePreparer();
$insertQueryBuilder = new InsertQueryBuilder($columnRetriever, $valuePreparer);
$dataInserter = new DataInserter($dbMapper, $insertQueryBuilder);
$identitySelector = new IdentitySelector($dbMapper, false);
$insertHandler = new InsertHandler($dataInserter, $identitySelector);
$updateData = new UpdateData();
$dataUploader = new DataUploader($insertHandler, $updateData);
$dataUploadController = new DataUploadController($dataSanitizer, $dataUploader);

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

    $response = $dataUploadController->handleUpload($request, $response);

    if ($log) {
        // Record the end time, final memory usage, and peak memory usage
        $endTime = microtime(true);
        $finalMemory = memory_get_usage();
        $peakMemory = memory_get_peak_usage();

        // Calculate the execution time in seconds
        $executionTime = $endTime - $startTime;

        // Prepare the log message
        $logMessage = date('Y-m-d H:i:s') . " - Script execution time: " . $executionTime . " seconds" . PHP_EOL;
        $logMessage .= "Initial memory usage: " . $initialMemory . " bytes" . PHP_EOL;
        $logMessage .= "Final memory usage: " . $finalMemory . " bytes" . PHP_EOL;
        $logMessage .= "Peak memory usage: " . $peakMemory . " bytes" . PHP_EOL . PHP_EOL;

        // Write the log message to the log file
        file_put_contents($logFile, $logMessage, FILE_APPEND);
    }

    // Emit the response
    header(sprintf('HTTP/%s %s %s', $response->getProtocolVersion(), $response->getStatusCode(), $response->getReasonPhrase()));
    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            header(sprintf('%s: %s', $name, $value), false);
        }
    }
    echo $response->getBody();
}

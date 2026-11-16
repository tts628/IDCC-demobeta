<?php
// DataUploader.php
namespace DataUpload\Models;

use DataUpload\Models\DatabaseOperations\InsertHandler;
use DataUpload\Models\DatabaseOperations\UpdateData;

/**
 * Class DataUploader
 * Handles the uploading or processing of sanitized data.
 */
class DataUploader
{
    private $insertHandler;
    private $updateData;

    public function __construct(InsertHandler $insertHandler, UpdateData $updateData) {
        $this->insertHandler = $insertHandler;
        $this->updateData = $updateData;
    }

    /**
     * Perform upload or further processing on sanitized data.
     *
     * @param array $sanitizedData
     * 
     * @return array
     */
    public function uploadData(array $sanitizedData): array
    {
        // Check if there are errors in the sanitized data
        if (!empty($sanitizedData['error'])) {
            return ['error' => $sanitizedData['error']];
        }

        // Proceed with the upload process

        return $this->insertHandler->handleInsertProcess($sanitizedData);

        // return $this->tempRandomResponseGenerator($sanitizedData);
        // For demonstration purposes, assuming this data format
        // return [
        //     'error' => [
        //         'upload' => [
        //             [
        //                 'sheet' => 'sheetX',
        //                 'message' => 'error code Y'
        //             ],
        //             [
        //                 'sheet' => 'sheetX',
        //                 'message' => 'error code Y'
        //             ]
        //         ]
        //     ],
        //     'success' => ['upload' => ['sheetX', 'sheetX']],
        //     'data' => $sanitizedData
        // ];
    }

    // private function tempRandomResponseGenerator($sanitizedData)
    // {
    //     // Initialize an empty response array
    //     $response = [
    //         'error' => [],
    //         'success' => [],
    //         'data' => $sanitizedData
    //     ];

    //     // Generate a random number between 3 and 10 for success and error entries
    //     $errorCount = rand(3, 10);
    //     $successCount = rand(3, 10);

    //     switch (true) {
    //         case true:
    //             // Add random success entries
    //             for ($i = 0; $i < $successCount; $i++) {
    //                 $response['success'][] = $this->generateSuccessMessage();
    //             }
    //             break;
    //     }

    //     switch (false) {
    //         case true:
    //             // Add random error entries
    //             for ($i = 0; $i < $errorCount; $i++) {
    //                 $response['error'][] = $this->generateErrorMessage();
    //             }
    //             break;
    //     }

    //     // Return the constructed random response
    //     return $response;
    // }

    // // Function to generate a random error message
    // private function generateErrorMessage()
    // {
    //     return [
    //         'sheet' => 'sheet' . rand(1, 5), // Random sheet number
    //         'message' => 'error code ' . rand(100, 999) // Random error code
    //     ];
    // }

    // // Function to generate a random success message
    // private function generateSuccessMessage()
    // {
    //     return 'sheet' . rand(1, 5); // Random success sheet
    // }
}

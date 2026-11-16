<?php
// DataUploadController.php
namespace DataUpload\Controllers;

use DataUpload\Models\DataSanitizer;
use DataUpload\Models\DataUploader;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use DataUpload\Controllers\Utils\RequestParser;

/**
 * Class DataUploadController
 * Handles data uploads, sanitizes and validates the input data, and checks the format.
 */
class DataUploadController
{
    private DataSanitizer $dataSanitizer;
    private DataUploader $dataUploader;

    public function __construct(DataSanitizer $dataSanitizer, DataUploader $dataUploader)
    {
        $this->dataSanitizer = $dataSanitizer;
        $this->dataUploader = $dataUploader;
    }

    public function handleUpload(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        try {
            // Use the utility function to get the input data
            $inputData = RequestParser::getParsedInput($request);

            if (empty($inputData)) {
                return $this->respondWithError($response, ['No data provided.']);
            }

            // Sanitize and validate data
            $sanitizedData = $this->dataSanitizer->sanitizeAndValidate($inputData);

            if (!empty($sanitizedData['error'])) {
                $errors = $sanitizedData['error'];
                return $this->respondWithResult($response, $sanitizedData['file'] ?? 'Unknown', [], $errors);
            }

            // Perform further operations using the sanitized data
            $uploadResult = $this->dataUploader->uploadData($sanitizedData);

            $errors = [];

            // Check for errors in the upload result
            if (!empty($uploadResult['error'])) {
                $errors = $uploadResult['error'];
            }

            return $this->respondWithResult($response, $sanitizedData['file'] ?? 'Unknown', $uploadResult['success'], $errors);

        } catch (\InvalidArgumentException $e) {
            return $this->respondWithError($response, [$e->getMessage()]);
        }
    }

    private function respondWithResult(ResponseInterface $response, string $file, array $success, array $errors): ResponseInterface
    {
        $payload = json_encode([
            'file' => $file,
            'success' => $success,
            'error' => $errors
        ]);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    private function respondWithError(ResponseInterface $response, array $errorMsg): ResponseInterface
    {
        return $this->respondWithResult($response, 'Unknown', [], $errorMsg);
    }
}

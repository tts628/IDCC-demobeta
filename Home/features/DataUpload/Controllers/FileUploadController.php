<?php
// FileUploadController.php
namespace DataUpload\Controllers;

// For processing slightly large dataset
ini_set('memory_limit', '1G');
ini_set('max_execution_time', '-1');

use DataUpload\Models\BatchValidator;
use DataUpload\Models\TypeValidator;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class FileUploadController
 * Handles file uploads, validating both type and data.
 */
class FileUploadController
{
    private BatchValidator $batchValidator;
    private TypeValidator $typeValidator;

    public function __construct(BatchValidator $batchValidator, TypeValidator $typeValidator)
    {
        $this->batchValidator = $batchValidator;
        $this->typeValidator = $typeValidator;
    }

    public function handleUpload(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $uploadedFiles = $request->getUploadedFiles();

        if (!isset($uploadedFiles['uploaded-data']) || empty($uploadedFiles['uploaded-data'])) {
            return $this->respondWithError($response, 'No file uploaded.', null);
        }

        $file = $uploadedFiles['uploaded-data'];
        $fileName = $file->getClientFilename();

        if ($file->getSize() === 0 || empty($fileName)) {
            return $this->respondWithError($response, 'No file uploaded.', $fileName);
        }

        $typeValidationResult = $this->typeValidator->validateType($file);
        if (!$typeValidationResult['isValid']) {
            return $this->respondWithError($response, $typeValidationResult['message'], $fileName);
        }

        $dataValidationResult = $this->batchValidator->processValidation($file);
        if (!empty($dataValidationResult['validation']['error'])) {
            $errorHtml = $this->formatErrors($dataValidationResult['validation']['error']);
            return $this->respondWithError($response, $errorHtml, $fileName);
        }

        // Successful validation
        return $this->respondWithSuccess($response, $dataValidationResult, $fileName);
    }

    private function respondWithError(ResponseInterface $response, string $errorMsg, ?string $fileName): ResponseInterface
    {
        $payload = json_encode([
            'error' => [$errorMsg],
            'success' => [],
            'file' => $fileName
        ]);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    private function respondWithSuccess(ResponseInterface $response, array $successData, string $fileName): ResponseInterface
    {
        $payload = json_encode([
            'error' => [''],
            'success' => $successData,
            'file' => $fileName
        ]);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    private function formatErrors(array $errors): string
    {
        $errorHtml = "";
        foreach ($errors as $error) {
            $errorHtml .= htmlentities($error);
        }
        return $errorHtml;
    }
}

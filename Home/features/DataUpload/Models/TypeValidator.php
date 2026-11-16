<?php
// TypeValidator.php
namespace DataUpload\Models;

use DataUpload\Models\Utils\FileTypeHandler;

class TypeValidator
{
    /**
     * Validates the type of the uploaded file.
     *
     * @param UploadedFile $file The file to validate.
     * @return array An array containing the validation result: 'isValid' (bool) and 'message' (string).
     */
    public function validateType($file): array
    {
        $fileName = $file->getClientFilename();
        $mimeType = $file->getClientMediaType();
        
        // Extract the file extension
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        // Validate the file extension
        if (!FileTypeHandler::validateExtension($fileName)) {
            return $this->createErrorMessage('檔案副檔名', $extension, FileTypeHandler::$allowedFileExtensions);
        }

        // Validate the MIME type
        if (!FileTypeHandler::validateMimeType($fileName, $mimeType)) {
            return $this->createErrorMessage('檔案類別', $mimeType, FileTypeHandler::$allowedMimeTypes);
        }

        return ['isValid' => true, 'message' => ''];
    }

    /**
     * Creates a formatted error message.
     *
     * @param string $type The type of validation (e.g., '檔案副檔名', '檔案類別').
     * @param string $invalidValue The invalid value (e.g., the file extension or MIME type).
     * @param array $allowedValues The allowed values for the type.
     * @return array The validation result with 'isValid' as false and the generated message.
     */
    private function createErrorMessage(string $type, string $invalidValue, array $allowedValues): array
    {
        $allowedValuesString = implode(", ", $allowedValues);
        $message = "無效的{$type}. {$type}: <b>'{$invalidValue}'</b>, 可接受的{$type}: <span class='text-black bg-light-gray'>'{$allowedValuesString}'</span>";

        return [
            'isValid' => false,
            'message' => $message
        ];
    }
}

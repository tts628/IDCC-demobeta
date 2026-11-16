<?php
// FileTypeHandler.php
namespace DataUpload\Models\Utils;

class FileTypeHandler
{
    public static $allowedFileExtensions = ['csv', 'xls', 'xlsx'];
    public static $allowedMimeTypes = [
        'csv' => 'text/csv',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/csv' => 'csv' // alternative csv MIME type
    ];

    /**
     * Validates if the file extension is allowed.
     *
     * @param string $fileName The name of the file.
     * @return bool Whether the file extension is valid.
     */
    public static function validateExtension(string $fileName): bool
    {
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        return in_array($fileExtension, self::$allowedFileExtensions, true);
    }

    /**
     * Validates if the MIME type is allowed for the given file extension.
     *
     * @param string $fileName The name of the file.
     * @param string $mimeType The MIME type of the file.
     * @return bool Whether the MIME type is valid for the extension.
     */
    public static function validateMimeType(string $fileName, string $mimeType): bool
    {
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        return isset(self::$allowedMimeTypes[$fileExtension]) && $mimeType === (self::$allowedMimeTypes[$fileExtension]);
    }

    /**
     * Gets the file type (csv or excel) based on the MIME type.
     *
     * @param string $mimeType The MIME type of the file.
     * @return string The file type ('csv' or 'excel').
     * @throws \RuntimeException If the MIME type is not supported.
     */
    public static function getFileType(string $mimeType): string
    {
        // Check for the MIME type in the allowed MIME types list
        if (!in_array($mimeType, self::$allowedMimeTypes, true)) {
            throw new \RuntimeException("File type '$mimeType' is currently not supported.");
        }

        // Return the type ('csv' or 'excel') based on the MIME type value
        foreach (self::$allowedMimeTypes as $type => $mime) {
            if ($mimeType === $mime) {
                return $type === 'csv' || $type === 'application/csv' ? 'csv' : 'excel';
            }
        }

        throw new \RuntimeException("Extractor for MIME type '$mimeType' is not implemented.");
    }
}

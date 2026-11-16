<?php
// DataSanitizer.php
namespace DataUpload\Models;

/**
 * Class DataSanitizer
 * Handles sanitizing and basic validation of input data.
 */
class DataSanitizer
{
    /**
     * Sanitize and validate the data based on expected types.
     *
     * @param array $inputData
     * 
     * @return array
     */
    public function sanitizeAndValidate(array $inputData): array
    {
        // Example static keys for type validation
        $staticKeys = ['file' => 'string', 'validation' => 'array'];
        $errors = [];

        // Validate and sanitize data
        foreach ($staticKeys as $key => $expectedType) {
            if (!isset($inputData[$key]) || gettype($inputData[$key]) !== $expectedType) {
                $errors[] = "Invalid data type for key '{$key}' (Expected: {$expectedType}).";
            }
        }

        return [
            'file' => $inputData['file'] ?? 'Unknown',
            'validation' => $inputData['validation'] ?? [],
            'error' => $errors
        ];
    }
}

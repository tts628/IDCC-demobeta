<?php
// RequestParser.php
namespace DataUpload\Controllers\Utils;

use Psr\Http\Message\ServerRequestInterface;

class RequestParser
{
    /**
     * Handles the input from the frontend, whether it is FormData or JSON.
     *
     * @param ServerRequestInterface $request
     * @return array The parsed input data.
     */
    public static function getParsedInput(ServerRequestInterface $request): array
    {
        // Try to get the parsed body (works for application/x-www-form-urlencoded and multipart/form-data)
        $inputData = $request->getParsedBody();

        // If the parsed body is empty, try to get the raw JSON body
        if (empty($inputData)) {
            $rawData = $request->getBody()->getContents();
            $inputData = json_decode($rawData, true);

            // Handle JSON decode errors
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \InvalidArgumentException('Invalid JSON input: ' . json_last_error_msg());
            }
        }

        return $inputData;
    }
}

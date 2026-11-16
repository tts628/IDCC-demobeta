<?php
// BatchValidator.php
declare(strict_types=1);

namespace DataUpload\Models;

use DataUpload\Models\Utils\FileTypeHandler;
use DataUpload\Models\ExcelProcessors\ExcelProcessor;
use DataUpload\Models\CsvProcessors\CsvProcessor;
use Nyholm\Psr7\UploadedFile;

/**
 * Processs data from Excel / csv (currently not available for csv)
 */

class BatchValidator
{
    private ExcelProcessor $excelProcessor;
    private CsvProcessor $csvProcessor;

    /**
     * Constructs from Excel and CSV Processor
     *
     * @param ExcelProcessor $excelProcessor excel processors
     * @param CsvProcessor $csvProcessor csv processors
     */
    public function __construct(ExcelProcessor $excelProcessor, CsvProcessor $csvProcessor)
    {
        $this->excelProcessor = $excelProcessor;
        $this->csvProcessor = $csvProcessor;
    }

    /**
     * Processs data from the uploaded file by identifying its type and using the appropriate processor.
     *
     * @param UploadedFile $file The file to be validated and processed.
     * @return array The result of the procession, including the file name and processed data or error.
     * @throws \RuntimeException If there is an issue during procession.
     */
    public function processValidation(UploadedFile $file): array
    {
        try {
            $mimeType = $file->getClientMediaType();
            $tmpName = $file->getStream()->getMetadata('uri');

            // Determine the appropriate processor based on mime type using the FileTypeHandler utility
            $processorType = FileTypeHandler::getFileType($mimeType);

            // Run the processor on the file and get the result
            $result = $this->runProcessor($processorType, $tmpName);

            return [
                'validation' => $result,
            ];
        } catch (\RuntimeException $e) {
            return [
                'validation' => ["error" => ["Procession error: " . $e->getMessage()]],
            ];
        } catch (\Exception $e) {
            return [
                'validation' => ["error" => ["Unexpected error: " . $e->getMessage()]],
            ];
        }
    }

    private function runProcessor(string $processorType, string $tmpName): array
    {
        switch ($processorType) {
            case 'excel':
                return $this->excelProcessor->processExcelData($tmpName);
            case 'csv':
                return $this->csvProcessor->processCSVData($tmpName);
            default:
                throw new \RuntimeException("Processor for type '$processorType' is not implemented.");
        }
    }
}

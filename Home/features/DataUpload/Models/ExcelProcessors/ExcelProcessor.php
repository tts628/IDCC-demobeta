<?php
// ExcelProcessor.php
namespace DataUpload\Models\ExcelProcessors;

use DataUpload\Models\Utils\SpreadsheetLoader;
use DataUpload\Models\Utils\SheetTermFinder;
use DataUpload\Models\ExcelProcessors\BasicExcelProcessor;
use DataUpload\Models\DatabaseOperations\FileMapperFetcher;

/**
 * Class ExcelProcessor
 * Handles the processing of Excel data using various processors.
 */
class ExcelProcessor
{
    /**
     * @var SpreadsheetLoader Handles the loading and validation of spreadsheets.
     */
    private $spreadsheetLoader;

    /**
     * @var SheetTermFinder Responsible for finding terms in sheets.
     */
    private $sheetTermFinder;

    /**
     * @var BasicExcelProcessor Processes basic Excel data operations.
     */
    private $basicExcelProcessor;

    /**
     * @var FileMapperFetcher Fetches the mapping information from the database.
     */
    private $fileMapperFetcher;

    /**
     * @var array Holds the fetched mapper data.
     */
    private $mapper;

    /**
     * ExcelProcessor constructor.
     * Initializes necessary dependencies for processing Excel data.
     *
     * @param SpreadsheetLoader $spreadsheetLoader Loads and validates spreadsheets.
     * @param SheetTermFinder $sheetTermFinder Finds relevant terms within sheets.
     * @param BasicExcelProcessor $basicExcelProcessor Processes Excel sheets with basic functionality.
     * @param FileMapperFetcher $fileMapperFetcher Retrieves file mapping data from the database.
     */
    public function __construct(
        SpreadsheetLoader $spreadsheetLoader,
        SheetTermFinder $sheetTermFinder,
        BasicExcelProcessor $basicExcelProcessor,
        FileMapperFetcher $fileMapperFetcher
    ) {
        $this->spreadsheetLoader = $spreadsheetLoader;
        $this->sheetTermFinder = $sheetTermFinder;
        $this->basicExcelProcessor = $basicExcelProcessor;
        $this->fileMapperFetcher = $fileMapperFetcher;
    }

    /**
     * Processes Excel data from a file.
     *
     * This method loads the Excel file, applies validation based on the
     * fetched mapper, and processes the data. If any exceptions occur,
     * they are caught and returned as error messages.
     *
     * @param string $fileTmpPath The temporary file path to the uploaded Excel file.
     * @return array Returns processed data or error messages.
     */
    public function processExcelData(string $fileTmpPath): array
    {
        try {
            // Fetch the mapper data
            $this->mapper = $this->fileMapperFetcher->fetchMapper();

            // Prepare the mapper for validation
            $sheetMapper = $this->prepareSheetMapper($this->mapper);

            // Load and validate the spreadsheet
            $spreadsheet = $this->spreadsheetLoader->loadSpreadsheetWithValidation($fileTmpPath, $sheetMapper);

            // Process the validated spreadsheet data
            return $this->processExcel($spreadsheet);

        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return ["error" => [$e->getMessage()]];
        }
    }

    /**
     * Prepares the sheet mapper for use in validation.
     *
     * This method processes the fetched mapper data, grouping it by the Excel sheet name and
     * storing the relevant headers for each sheet.
     *
     * @param array $mapper The mapper data fetched from the database.
     * @return array The prepared sheet mapper, structured by worksheet names.
     */
    private function prepareSheetMapper(array $mapper): array
    {
        $data = [];

        foreach ($mapper as $row) {
            $worksheet = $row['ExcelSheet'];

            if (!isset($data[$worksheet])) {
                $data[$worksheet] = [];
            }

            // Append the column header to the corresponding worksheet's data
            $data[$worksheet][] = $row['Header'];
        }

        return $data;
    }

    /**
     * Processes data from a loaded spreadsheet.
     *
     * This method checks for empty or invalid spreadsheets, then processes the data using
     * the `BasicExcelProcessor` and returns the processed data or error messages.
     *
     * @param \PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet The loaded spreadsheet object.
     * @return array Returns processed data or an array of error messages.
     */
    private function processExcel(\PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet): array
    {
        if (empty($spreadsheet)) {
            return ["error" => ["Spreadsheet is empty or invalid."]];
        }

        // Process the spreadsheet data with the basic processor
        $processRes = $this->basicExcelProcessor->processData($spreadsheet, $this->mapper);

        // Return appropriate error if no matching data was found
        if (empty($processRes["data"])) {
            return !empty($processRes["error"])
                ? ["error" => $processRes["error"]]
                : ["error" => ["無匹配資料"]];
        }

        // Return the processed data
        return $processRes;
    }
}

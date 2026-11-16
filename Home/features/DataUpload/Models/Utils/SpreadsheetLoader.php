<?php
// SpreadsheetLoader.php
namespace DataUpload\Models\Utils;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;
use PhpOffice\PhpSpreadsheet\Reader\IReader;

/**
 * Class WorksheetColumnFilter
 *
 * A read filter that validates if a worksheet contains the specified columns.
 */
class WorksheetColumnFilter implements IReadFilter
{
    /**
     * @var array An array that maps worksheet names to the required column names.
     */
    private $worksheetColumnMap;

    /**
     * @var string|null The current worksheet name being processed.
     */
    private $worksheetName = null;

    /**
     * @var array The required columns for the current worksheet.
     */
    private $columnsToCheck = [];

    /**
     * @var bool Whether the worksheet is valid based on the column check.
     */
    private $isValidWorksheet = false;

    /**
     * WorksheetColumnFilter constructor.
     * 
     * @param array $worksheetColumnMap Array that maps worksheet names to required columns.
     */
    public function __construct(array $worksheetColumnMap)
    {
        $this->worksheetColumnMap = $worksheetColumnMap;
    }

    /**
     * Determines whether a cell should be read.
     * 
     * @param string $column The column being read.
     * @param int $row The row being read.
     * @param string $worksheetName The name of the worksheet being read.
     * 
     * @return bool True if the cell should be read, false otherwise.
     */
    public function readCell($column, $row, $worksheetName = '')
    {
        // Check if the worksheet name matches the keys in the provided array
        if (array_key_exists($worksheetName, $this->worksheetColumnMap)) {
            $this->worksheetName = $worksheetName;
            $this->columnsToCheck = $this->worksheetColumnMap[$worksheetName];
            $this->isValidWorksheet = true;
            
            return true; // Allow reading the entire row for validation
        }

        return false; // Skip reading if the worksheet name does not match
    }

    /**
     * Validates whether the current worksheet contains the required columns.
     * 
     * @param Spreadsheet $spreadsheet The spreadsheet object.
     * 
     * @return bool True if the worksheet contains the required columns, false otherwise.
     */
    public function validateWorksheet(Spreadsheet $spreadsheet): bool
    {
        if (!$this->isValidWorksheet) {
            return false; // Skip the worksheet if no match
        }

        $worksheet = $spreadsheet->getSheetByName($this->worksheetName);
        if (!$worksheet) {
            return false; // If the worksheet is not available, skip
        }

        $highestRow = $worksheet->getHighestRow();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($worksheet->getHighestColumn());

        // Loop through rows and check if any row contains all the required columns (non-strict match)
        for ($row = 1; $row <= $highestRow; $row++) {
            $columnsFound = [];

            // Check each column in the row
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cellValue = $worksheet->getCellByColumnAndRow($col, $row)->getValue();

                // Check if any of the required column names is included in the cell value
                foreach ($this->columnsToCheck as $requiredColumn) {
                    if (stripos($cellValue, $requiredColumn) !== false) {
                        $columnsFound[] = $requiredColumn;
                        break; // Stop further checks for this cell
                    }
                }
            }

            // Check if all required columns (non-strict match) were found in this row
            if (count(array_intersect($this->columnsToCheck, $columnsFound)) === count($this->columnsToCheck)) {
                return true; // Valid worksheet, all columns found
            }
        }

        return false; // Skip if no valid rows found
    }
}

/**
 * Class SpreadsheetLoader
 *
 * This class is responsible for loading a spreadsheet and applying validation to its worksheets.
 */
class SpreadsheetLoader
{
    /**
     * Loads and validates a spreadsheet based on a worksheet column map.
     * 
     * @param string $fileTmpPath The file path of the spreadsheet to load.
     * @param array $worksheetColumnMap An array that maps worksheet names to required columns.
     * 
     * @return Spreadsheet The validated spreadsheet, with invalid worksheets removed.
     */
    public function loadSpreadsheetWithValidation(string $fileTmpPath, array $worksheetColumnMap): Spreadsheet
    {
        /** @var IReader $reader */
        $reader = IOFactory::createReaderForFile($fileTmpPath);
        $filter = new WorksheetColumnFilter($worksheetColumnMap);

        // Set the filter to the reader
        $reader->setReadFilter($filter);

        // Load the spreadsheet
        $spreadsheet = $reader->load($fileTmpPath);

        // Validate worksheets based on the requirements
        foreach ($spreadsheet->getSheetNames() as $worksheetName) {
            if (array_key_exists($worksheetName, $worksheetColumnMap)) {
                // Perform the validation
                if (!$filter->validateWorksheet($spreadsheet)) {
                    // If the worksheet is invalid, remove it
                    $spreadsheet->removeSheetByIndex($spreadsheet->getIndex($spreadsheet->getSheetByName($worksheetName)));
                }
            }
        }

        return $spreadsheet;
    }
}

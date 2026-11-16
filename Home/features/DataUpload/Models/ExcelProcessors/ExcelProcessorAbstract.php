<?php
// ExcelProcessorAbstract.php
namespace DataUpload\Models\ExcelProcessors;

use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * Class ExcelProcessorAbstract
 * Abstract class that provides methods for identifying data within a spreadsheet.
 */
abstract class ExcelProcessorAbstract
{
    /**
     * Extract data from a spreadsheet.
     * 
     * @param Spreadsheet $spreadsheet The spreadsheet from which to extract data.
     * 
     * @return array The extracted data from the spreadsheet.
     */
    abstract public function extractData(Spreadsheet $spreadsheet): array;
}

<?php
// BasicExcelProcessor.php

namespace DataUpload\Models\ExcelProcessors;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * Class BasicExcelProcessor
 * Provides functionality to process a spreadsheet based on headers and retrieve calculated values.
 */
class BasicExcelProcessor
{
    /**
     * Processes the entire spreadsheet and returns identity and data based on headers.
     * It loops through each worksheet, processes its data, and structures the final result.
     *
     * @param Spreadsheet $spreadsheet The Spreadsheet object to process, which can contain multiple worksheets.
     * @param array $mapper The mapping array that matches spreadsheet titles with expected headers.
     * @return array An associative array with "identity" and "data" keys:
     *               - "identity": Matched identities for each worksheet.
     *               - "data": Processed data from each worksheet.
     */
    public function processData(Spreadsheet $spreadsheet, array $mapper): array
    {
        $processRes = [
            "identity" => [],
            "data" => []
        ];

        foreach ($spreadsheet->getAllSheets() as $worksheet) {
            $this->processWorksheet($worksheet, $mapper, $processRes);
            // Clean each sheet's data immediately after processing
            $lastSheetIndex = array_key_last($processRes["data"]);
            if ($lastSheetIndex !== null) {
                $processRes["data"][$lastSheetIndex] = $this->removeEmptyArraysAndObjects($processRes["data"][$lastSheetIndex]);
            }
        }

        return $processRes;
    }

    /**
     * Processes a specific worksheet and adds relevant data to the result array.
     * The method looks for a match between the worksheet title and the mapper, and if found, processes the data.
     *
     * @param Worksheet $worksheet The worksheet being processed.
     * @param array $mapper The mapping data that includes spreadsheet titles and headers.
     * @param array &$processRes The result array to store identities and data. Passed by reference.
     */
    private function processWorksheet(Worksheet $worksheet, array $mapper, array &$processRes): void
    {
        $title = $worksheet->getTitle();

        // Find the worksheet title in the mapper
        $mapperIdentity = $this->findTitleInMapper($title, $mapper);

        // If a match is found in the mapper, process the sheet data
        if ($mapperIdentity) {
            $headersInSheet = $this->getHeadersInSheet($mapperIdentity, $mapper);
            $sheetData = $this->extractData($worksheet, $headersInSheet);
            $processRes["identity"][] = $mapperIdentity;
            $processRes["data"][$mapperIdentity] = $sheetData;
        }
    }

    /**
     * Retrieves the relevant headers from the mapper for the given worksheet identity.
     * These headers will be used to extract and validate data from the worksheet.
     *
     * @param string $identity The identity found in the mapper that corresponds to the worksheet.
     * @param array $mapper The mapping array that defines the headers for each worksheet identity.
     * @return array A list of headers to search for in the worksheet.
     */
    private function getHeadersInSheet(string $identity, array $mapper): array
    {
        return array_column(
            array_filter($mapper, function ($map) use ($identity) {
                return strpos($map['Identity'], $identity) !== false;
            }),
            'Header'
        );
    }

    /**
     * Finds the title of the worksheet in the mapper array.
     * This method checks if the worksheet title exists in the mapper and returns the corresponding identity.
     *
     * @param string $title The title of the worksheet being processed.
     * @param array $mapper The mapping array containing spreadsheet titles and identities.
     * @return string|null The mapper identity if found, or null if no match is found.
     */
    private function findTitleInMapper(string $title, array $mapper): ?string
    {
        foreach ($mapper as $map) {
            if (strpos($map['ExcelSheet'], $title) !== false) {
                return $map['Identity'];
            }
        }
        return null;
    }

    /**
     * Cleans the data by removing empty arrays and objects from the result array.
     * This ensures that only non-empty, valid data remains in the final result set.
     *
     * @param array $array The array to clean, potentially containing nested arrays or objects.
     * @return array The cleaned array with empty elements removed.
     */
    private function removeEmptyArraysAndObjects(array $array): array
    {
        return array_filter($array, function ($value) {
            if (is_array($value)) {
                // Recursively remove empty arrays and check if the result is empty
                $cleaned = $this->removeEmptyArraysAndObjects($value);
                return !empty($cleaned);
            }
            // Treat empty strings, NULL, and false as empty (but keep 0)
            return $value !== null && $value !== '' && $value !== false;
        });
    }

    /**
     * Extracts the data from the worksheet by matching the headers.
     * It looks for rows where headers match the expected headers, then extracts and validates row data.
     *
     * @param Worksheet $sheet The worksheet from which to extract data.
     * @param array $headersInSheet The expected headers for the worksheet.
     * @return array Extracted data from the worksheet.
     */
    private function extractData(Worksheet $sheet, array $headersInSheet): array
    {
        $data = $dataHeaderIndex = [];
        $matchFound = false;
        $matchCounter = 0;

        foreach ($sheet->getRowIterator() as $row) {
            $rowData = $this->extractRowData($row);

            // Check if the row data matches headers and contains values
            if ($matchFound) {
                $this->storeDataIfValid($data, $rowData, $dataHeaderIndex);
            } else {
                $this->matchesHeaders($matchCounter, $dataHeaderIndex, $rowData, $headersInSheet);
                if ($matchCounter === count($headersInSheet)) {
                    $matchFound = true;
                    $data[] = $headersInSheet; // Store header data once it's found
                }
            }
        }

        return $data;
    }

    /**
     * Extracts data from a specific row of the worksheet.
     * This method loops through the cells of a row and retrieves their calculated or raw values.
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Row $row The row object from which to extract cell data.
     * @return array The extracted row data as an array of cell values.
     */
    private function extractRowData($row): array
    {
        $rowData = [];

        foreach ($row->getCellIterator() as $cell) {
            $rowData[] = $this->getCellData($cell);
        }

        return $rowData;
    }

    /**
     * Retrieves the value from a cell, attempting to calculate it if necessary.
     * If the calculated value fails, the raw value of the cell is returned instead.
     *
     * @param \PhpOffice\PhpSpreadsheet\Cell\Cell $cell The cell object from which to retrieve the value.
     * @return mixed The calculated value or raw value of the cell. Expected to be a single string/int value.
     */
    private function getCellData($cell)
    {
        try {
            return $cell->getCalculatedValue();
        } catch (\PhpOffice\PhpSpreadsheet\Calculation\Exception $e) {
            return $cell->getValue();
        }
    }

    /**
     * Stores validated row data into the final data array if the row contains valid data.
     * The method only stores data that corresponds to valid header indices, now using PhpSpreadsheet's Coordinate.
     *
     * @param array &$data The data array to store valid row data.
     * @param array $rowData The row data extracted from the worksheet.
     * @param array $dataHeaderIndex The indices of valid headers in the worksheet, now using column letters (A, B, C...).
     */
    private function storeDataIfValid(array &$data, array $rowData, array $dataHeaderIndex): void
    {
        $validatedRowData = [];

        foreach ($rowData as $index => $cellData) {
            // Use PhpSpreadsheet's Coordinate class to convert column number to letter
            $columnLetter = Coordinate::stringFromColumnIndex($index + 1);

            // Only process valid column letters that are in the header indices
            if (in_array($columnLetter, $dataHeaderIndex)) {
                $validatedRowData[] = $cellData;
                // for debugging, use this instead
                // $validatedRowData[$columnLetter] = $cellData;
            }
        }

        // Add the validated row only if it's not empty, ensuring a 2-layer structure
        if (!empty($validatedRowData)) {
            $data[] = $validatedRowData;
        }
    }


    /**
     * Matches the headers in the worksheet row with the expected headers.
     * If a match is found, the corresponding column indices are stored in $dataHeaderIndex as column letters.
     *
     * @param int &$matchCounter The count of headers that have been matched.
     * @param array &$dataHeaderIndex The array of column letters where headers are found.
     * @param array $rowData The data from the current worksheet row.
     * @param array $headersInSheet The expected headers to match against.
     */
    private function matchesHeaders(int &$matchCounter, array &$dataHeaderIndex, array $rowData, array $headersInSheet): void
    {
        foreach ($rowData as $index => $cellValue) {
            foreach ($headersInSheet as $header) {
                $normalizedCellValue = strtolower(preg_replace('/\s+/', '', $cellValue));
                $normalizedHeader = strtolower(preg_replace('/\s+/', '', $header));

                // Use PhpSpreadsheet's Coordinate to get column letter and store it
                if (strpos($normalizedCellValue, $normalizedHeader) !== false) {
                    $columnLetter = Coordinate::stringFromColumnIndex($index + 1);
                    $dataHeaderIndex[] = $columnLetter;
                    $matchCounter++;
                    break; // Early exit after match
                }
            }
        }
    }
}

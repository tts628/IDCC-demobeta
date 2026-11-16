<?php
// From AS, using as a reference only
namespace DataUpload\Models\Extractor;

use DataUpload\Models\ExtractorAbstract;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class MaintenanceDataExtractor extends ExtractorAbstract
{
    /**
     * Extract data from a spreadsheet.
     *
     * @param Spreadsheet $spreadsheet The spreadsheet from which to extract data.
     * @return array The extracted data from the spreadsheet.
     */
    public function extractData(Spreadsheet $spreadsheet) : array
    {
        $foundStart = false;
        $machine = '';
        $groupedData = [];
        $currentTitle = '';

        // Get the active sheet
        $sheet = $spreadsheet->getActiveSheet();

        foreach ($sheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop through all cells, even if they're empty
            $rowData = [];
            $removeSpaceFlag = true;
            $titleFlagCounter = 0;

            foreach ($cellIterator as $cell) {
                $cellValue = $cell->getValue();

                if ($cellValue !== null) {
                    if ($foundStart) {
                        if (strpos($cellValue, '□') !== false) {
                            $titleFlagCounter = 1;
                            $removeSpaceFlag = false;
                        }

                        if ($titleFlagCounter > 0 && $titleFlagCounter < 3) {
                            $titleFlagCounter++;
                        } elseif ($titleFlagCounter === 3) {
                            // Check if the string contains a "\n" followed by a letter (A-z)
                            if (preg_match("/\n[A-Za-z]/", $cellValue)) {
                                // Find the position of the first "\n" followed by a letter
                                $pos = preg_match("/\n[A-Za-z]/", $cellValue, $matches, PREG_OFFSET_CAPTURE);
                                if ($pos !== false) {
                                    // Split the string at the position
                                    $splitPosition = $matches[0][1];
                                    $part1 = substr($cellValue, 0, $splitPosition);
                                    $part2 = substr($cellValue, $splitPosition + 1);

                                    // Cleanse both parts by removing all "\n"
                                    $part1 = str_replace("\n", "", $part1);
                                    $part2 = str_replace("\n", "", $part2);

                                    // Add both parts to the rowData
                                    $rowData[] = [$part1, $part2];
                                }
                            } else {
                                return ["extractor" => "MaintenanceData", "error" => "Data corruption"];
                            }
                        } elseif ($titleFlagCounter === 0) {
                            $currentTitle = $removeSpaceFlag ? preg_replace('/\s+/', '', $cellValue) : $cellValue;
                            if (!isset($groupedData[$currentTitle]) && $currentTitle != "客戶意見") {
                                $groupedData[$currentTitle] = [];
                            }
                            $titleFlagCounter = -1; // Ensure no further processing
                        }
                    }

                    // Check for the start of the maintenance items
                    if (!$foundStart && strpos($cellValue, "維護保養項目") !== false) {
                        $foundStart = true;
                        $machine = str_replace("維護保養項目", "", $cellValue);
                    }
                }
            }

            // Only add non-empty rows after the start has been found and a title is set
            if ($foundStart && !empty($rowData) && $currentTitle) {
                $groupedData[$currentTitle][] = $rowData;
            }
        }

        // Prepare the result structure based on the presence of machine and groupedData
        $result = [
            'identity' => '',
            'data' => []
        ];

        // If both machine and groupedData are present, populate the result
        if ($machine && $groupedData) {
            $result['identity'] = '保養維修表格';
            $result['data'] = [
                'machine' => $machine,
                'groupedData' => $groupedData
            ];
        }

        // Return the result
        return $result;
    }
}

<?php
// SheetTermFinder.php
namespace DataUpload\Models\Utils;

class SheetTermFinder
{
    /**
     * Checks if any term in the provided array exists in the given Excel sheet.
     *
     * @param object $sheet The Excel sheet to be searched.
     * @param array $terms An array of terms to search for in the sheet.
     * @return bool Returns true if any term is found, otherwise false.
     */
    public function containsAnyTerm($sheet, array $terms)
    {
        foreach ($sheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop through all cells, even if they're empty

            foreach ($cellIterator as $cell) {
                $cellValue = $cell->getValue();

                if ($cellValue !== null) {
                    foreach ($terms as $term) {
                        if (strpos($cellValue, $term) !== false) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }
}

<?php
// InsertValuePreparer.php
namespace DataUpload\Models\DatabaseOperations;

class InsertValuePreparer
{
    /**
     * Prepares the values for the insert query.
     * Converts the provided data into an array of parameter values.
     *
     * @param array $identityData The data to be inserted.
     * @param array $columnsAndTypes The columns and their data types to be inserted.
     * @param int $datetimeFkId The ID of the inserted datetime.
     * @return array The prepared values.
     * @throws \Exception if there is a failure in date normalization.
     */
    public function prepareValues(array $identityData, array $columnsAndTypes, int $datetimeFkId): array
    {
        $values = [];
        $errorReports = []; // To store error reports

        foreach ($identityData as $rowIndex => $rowData) {
            foreach ($columnsAndTypes as $colIndex => $column) {
                $columnName = $column['COLUMN_NAME'];
                $dataType = $column['DATA_TYPE'];

                $value = $rowData[$colIndex] ?? null;

                // Check if the column is of type "DATE"
                if ($dataType === 'date') {
                    $normalizedValue = $this->normalizeDate($value);
                    if ($normalizedValue === false) {
                        $errorReports[] = "Error normalizing date for row $rowIndex, column $columnName, value: $value.";
                        continue; // Skip if date normalization fails
                    }
                    $rowData[$colIndex] = $normalizedValue;
                }
            }

            // Append the DatetimeFK ID to each row's data
            $rowData[] = $datetimeFkId;

            // Flatten and append each row's values to the values array
            $values = array_merge($values, array_values($rowData));
        }

        if (!empty($errorReports)) {
            throw new \Exception("Date normalization errors: " . implode(', ', $errorReports));
        }

        return $values;
    }

    /**
     * Normalize a value to a valid SQL date format, converting Excel serial numbers if necessary.
     *
     * @param string|null $value The value to normalize.
     * @return string|false The normalized date or false if it cannot be normalized.
     */
    private function normalizeDate(?string $value)
    {
        if (!$value || trim($value) === ".") {
            return null; // Empty or invalid values should be null
        }

        // Check if the value looks like an Excel serial number
        if (is_numeric($value)) {
            $excelBaseDate = new \DateTime('1899-12-30'); // Excel serial numbers start at 1900-01-01, subtracting 2 days due to a leap year bug
            $normalizedDate = $excelBaseDate->modify("+{$value} days");

            if ($normalizedDate) {
                return $normalizedDate->format('Y-m-d'); // Return in SQL "DATE" format
            }
        }

        // Additional check: if the value is not numeric but might be a string identifier, skip it
        if (preg_match('/[A-Za-z]+/', $value)) {
            return false; // Skipping values that contain letters as they are likely not dates
        }

        // Try to convert it as a normal date
        try {
            $date = new \DateTime($value);
            return $date->format('Y-m-d'); // SQL "DATE" format (YYYY-MM-DD)
        } catch (\Exception $e) {
            return false; // Conversion failed
        }
    }

}

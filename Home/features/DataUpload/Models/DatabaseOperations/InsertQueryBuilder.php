<?php
// InsertQueryBuilder.php
namespace DataUpload\Models\DatabaseOperations;

use \Exception;

class InsertQueryBuilder
{
    protected $columnRetriever;
    protected $valuePreparer;
    protected $maxParams = 2000; // Cap at 2000 parameters for SQL Server safety

    public function __construct(ColumnRetriever $columnRetriever, InsertValuePreparer $valuePreparer)
    {
        $this->columnRetriever = $columnRetriever;
        $this->valuePreparer = $valuePreparer;
    }

    /**
     * Builds an SQL insert query for the provided identity data, with chunking for large data.
     *
     * @param array $identityData The data to be inserted.
     * @param string $tableName The table name of the data to be inserted into.
     * @param int $datetimeFkId The ID of the inserted datetime from DateTimeLookup.
     * @return array The SQL insert queries and their respective prepared values.
     */
    public function buildInsertQuery(array $identityData, string $tableName, int $datetimeFkId): array
    {
        array_shift($identityData); // Remove header if necessary

        // Retrieve column names and types using ColumnRetriever
        $columnsAndTypes = $this->columnRetriever->getColumnsAndTypes($tableName);
        $columns = array_column($columnsAndTypes, 'COLUMN_NAME');
        $numColumns = count($columns);

        // Determine how many rows fit within the 2000 parameter limit
        $rowsPerQuery = intdiv($this->maxParams, $numColumns); // Maximum rows per query

        // Prepare the column names part of the query
        $columnsList = implode(', ', $columns);

        // Prepare an array to hold all queries and their values
        $queriesAndValues = [];

        // Chunk the identity data
        $chunks = array_chunk($identityData, $rowsPerQuery);

        // Iterate over each chunk and create a query
        foreach ($chunks as $chunk) {
            $placeholders = '(' . implode(', ', array_fill(0, $numColumns, '?')) . ')';
            $allPlaceholders = implode(', ', array_fill(0, count($chunk), $placeholders));

            // Build the query string
            $query = "INSERT INTO {$tableName} ({$columnsList}) VALUES {$allPlaceholders}";

            // Prepare the values for the query, including the DatetimeFK ID
            $preparedValues = $this->valuePreparer->prepareValues($chunk, $columnsAndTypes, $datetimeFkId);

            // Add the query and values to the result
            $queriesAndValues[] = [
                'query' => $query,
                'values' => $preparedValues
            ];
        }

        return $queriesAndValues;
    }
}

<?php
// ColumnRetriever.php
namespace DataUpload\Models\DatabaseOperations;

use \PDO;

class ColumnRetriever
{
    protected $pdo;

    public function __construct(\Config\DbMapper $dbMapper)
    {
        $this->pdo = $dbMapper->getPdo(['IDCC']);
    }

    /**
     * Retrieves the column names and data types from the database for the given table.
     *
     * @param string $tableName The table name to get columns and data types for.
     * @return array The list of column names and their data types.
     */
    public function getColumnsAndTypes(string $tableName): array
    {
        $query = "SELECT COLUMN_NAME, DATA_TYPE 
                  FROM INFORMATION_SCHEMA.COLUMNS 
                  WHERE TABLE_NAME = :tableName 
                  ORDER BY ORDINAL_POSITION";
                  
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['tableName' => $tableName]);

        $columnsAndTypes = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch both column name and data type

        array_shift($columnsAndTypes);
        return $columnsAndTypes;
    }
}

<?php
// DataInserter.php
namespace DataUpload\Models\DatabaseOperations;

use \Config\DbMapper;
use \PDO;

/**
 * Class DataInserter
 * Handles inserting individual sheet data into the database.
 */
class DataInserter
{
    private $conn;
    private $insertQueryBuilder;

    public function __construct(DbMapper $dbMapper, InsertQueryBuilder $insertQueryBuilder)
    {
        $this->conn = $dbMapper->getPdo(['IDCC']);
        $this->insertQueryBuilder = $insertQueryBuilder;
    }

    /**
     * Inserts the current datetime into the DateTimeLookup table and retrieves the DatetimeFK (ID).
     *
     * @return int The ID (DatetimeFK) of the inserted datetime.
     * @throws \Exception if something goes wrong during the insertion.
     */
    public function insertCurrentDatetimeIntoLookup(): int
    {
        try {
            // Insert the current datetime into DateTimeLookup
            $datetime = date('Y-m-d H:i:s'); // Get current datetime
            $datetimeInsertQuery = "INSERT INTO DateTimeLookup (DatetimeValue) VALUES (?)"; // Replace with actual column name
            $stmt = $this->conn->prepare($datetimeInsertQuery);
            $stmt->execute([$datetime]);

            // Retrieve the DatetimeFK (ID) from DateTimeLookup based on the exact datetime inserted
            $retrieveIdQuery = "SELECT ID FROM DateTimeLookup WHERE DatetimeValue = ?"; // Assuming ID is the PK in DateTimeLookup
            $stmt = $this->conn->prepare($retrieveIdQuery);
            $stmt->execute([$datetime]);
            $datetimeFkId = $stmt->fetchColumn(); // Get the ID of the inserted datetime

            return $datetimeFkId;

        } catch (\Exception $e) {
            throw new \Exception("Error inserting datetime into DateTimeLookup: " . $e->getMessage());
        }
    }

    /**
     * Inserts data for a specific data group into the database, handling chunked queries.
     *
     * @param array $identityData The data to be inserted.
     * @param string $tableName The table name of the data to be inserted into.
     * @param int $datetimeFkId The ID of the inserted datetime from DateTimeLookup.
     * @return array The result of the insertion process, containing 'error' or 'success' keys.
     */
    public function insertData(array $identityData, string $tableName, int $datetimeFkId): array
    {
        $this->conn->beginTransaction(); // Start transaction

        try {
            // Construct the insert queries for the data (chunked), using the DatetimeFK ID
            $sqlPacks = $this->insertQueryBuilder->buildInsertQuery($identityData, $tableName, $datetimeFkId);

            // Step 4: Execute each chunked query
            foreach ($sqlPacks as $sqlPack) {
                $stmt = $this->conn->prepare($sqlPack['query']);
                $stmt->execute($sqlPack['values']);
            }

            $this->conn->commit(); // Commit transaction if all queries succeed
            return ['success' => "Inserted data into table {$tableName} successfully."];

        } catch (\Exception $e) {
            $this->conn->rollBack(); // Rollback transaction in case of error
            return ['error' => ["sheet" => $tableName, "message" => $e->getMessage()]];
        }
    }
}

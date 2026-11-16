<?php
// FileMapperFetcher.php
namespace DataUpload\Models\DatabaseOperations;

use Config\DbMapper;
use PDO;
use PDOException;

/**
 * Class FileMapperFetcher
 * Responsible for fetching the raw mapper data from the database.
 */
class FileMapperFetcher
{
    /**
     * @var PDO The PDO database connection instance.
     */
    private $conn;

    /**
     * FileMapperFetcher constructor.
     * Initializes the PDO connection using DbMapper.
     */
    public function __construct()
    {
        $dbMapper = new DbMapper();
        $this->conn = $dbMapper->getPdo(['IDCC']);
    }

    /**
     * Fetches raw mapper data from the database.
     *
     * @return array An associative array containing rows of mapper data, or an error message in case of failure.
     */
    public function fetchMapper(): array
    {
        try {
            // SQL query to fetch mapper data
            $sql = "SELECT
                        [FileName], ExcelSheet, [Identity], DBTable, Header
                    FROM UploadMappingSheetHeader umsh
                    LEFT JOIN UploadMappingSheet ums ON ums.ID = umsh.UploadMappingSheet_FK";

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            // Fetch all rows as an associative array, return an empty array if no data is found
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
        } catch (PDOException $e) {
            // Log the error and return an error response
            error_log($e->getMessage());
            return ['error' => 'Failed to fetch mapper data.'];
        }
    }
}

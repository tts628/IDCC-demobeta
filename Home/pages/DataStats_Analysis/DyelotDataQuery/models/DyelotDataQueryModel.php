<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

class DyelotDataQueryModel
{
    private $conn;
    private const FIELDS = [
        "dip.Dyelot",
        "dyp.[Order]",
        "dip.Redye",
        "dip.Machine",
        "dip.Destination",
        "dip.ProductCode",
        "dip.ProductName",
        "dip.ProductType",
        "dip.TargetGrams",
        "dip.DispenseGrams",
        "dip.StartTime",
        "dip.EndTime",
        // "StandardTime",
        // "ProcessTime",
        // "OverRunTime",
        // "MixTemperature",
        // "MixLiters",
        // "MixTime",
        // "RinseTemperature",
        // "RinseLiters",
        // "TransferTime",
        // "Note",
        // "Process"
    ];

    public function __construct()
    {
        $dbMapper = new DbMapper();
        $this->conn = $dbMapper->getLink(['IDCC', 'ReturnDatesAsStrings']);
        if (!$this->conn) {
            throw new \Exception("Database connection failed.");
        }
    }

    public function queryDyelotData($queryInput)
    {
        try {
            $params = [];
            $sql = $this->buildBaseQuery();
            $sql = $this->buildQueryWithFilters($sql, $queryInput, $params);
            $dyelotStmt = $this->prepareStatement($sql, $params);
            $data = $this->executeAndFetch($dyelotStmt);
            return json_encode($this->buildResult($data));
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            error_log('Error in queryDyelotData: ' . $e->getMessage());
            return json_encode(['error' => 'An error occurred while processing your request.']);
        } finally {
            $this->closeConnection();
        }
    }

    private function buildBaseQuery(): string
    {
        return "SELECT " . implode(", ", self::FIELDS) . " FROM DispenserProduction dip LEFT JOIN DyeProduction dyp ON dip.Dyelot = dyp.Dyelot";
    }
    private function buildQueryWithFilters(string $sql, array $queryInput, array &$params): string
    {
        $conditions = [];

        if (!empty($queryInput["start-time-selector"])) {
            $conditions[] = "(dip.StartTime >= ? OR dip.EndTime >= ?)";
            // Add two parameters for the two placeholders in the condition
            $params[] = $queryInput["start-time-selector"];
            $params[] = $queryInput["start-time-selector"];
        }
        if (!empty($queryInput["end-time-selector"])) {
            $conditions[] = "(dip.StartTime <= ? OR dip.EndTime <= ?)";
            // Add two parameters for the two placeholders in the condition
            $params[] = $queryInput["end-time-selector"];
            $params[] = $queryInput["end-time-selector"];
        }
        if (!empty($queryInput["dyelot-text-input"])) {
            $conditions[] = "dip.Dyelot LIKE ?";
            $params[] = '%' . $queryInput["dyelot-text-input"] . '%';
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        return $sql;
    }

    private function prepareStatement(string $sql, array $params)
    {
        $stmt = sqlsrv_prepare($this->conn, $sql, $params);

        if (!$stmt) {
            throw new \Exception('SQLSRV statement preparation failed: ' . print_r(sqlsrv_errors(), true));
        }

        return $stmt;
    }

    private function executeAndFetch($stmt): array
    {
        if (!sqlsrv_execute($stmt)) {
            throw new \Exception('SQLSRV execution failed: ' . print_r(sqlsrv_errors(), true));
        }

        $data = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row;
        }

        sqlsrv_free_stmt($stmt);

        return $data;
    }

    private function buildResult(array $data): array
    {
        // Define the prefixes to remove and also remove square brackets
        $prefixes = ["dip.", "dyp."];
        $fields = array_map(function ($field) use ($prefixes) {
            // Remove prefixes
            $cleanedField = str_replace($prefixes, "", $field);
            // Remove square brackets
            return str_replace(["[", "]"], "", $cleanedField);
        }, self::FIELDS);

        return [
            "field" => $fields,
            "data" => $data
        ];
    }


    private function closeConnection(): void
    {
        if ($this->conn) {
            sqlsrv_close($this->conn);
        }
    }
}

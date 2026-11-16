<?php
const DB_FILE = 'env/database_IDCC.php';
$pathSegments = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
define('CLIENT_ROOT', $pathSegments[1]);
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
class MaintenanceFormModel
{
    public function getDataByDefault()
    {
        try {
            require_once realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/' . DB_FILE);
            $maintenanceFormSql = "SELECT * 
            FROM Maintenance_Records 
            WHERE Deleted = 0";

            $maintenanceFormStmt = sqlsrv_prepare($conn, $maintenanceFormSql);

            // Execute the statement
            if (!sqlsrv_execute($maintenanceFormStmt)) {
                // Handle invalid sql execution
                sqlsrv_close($conn);
                throw new Exception('Sql error: ' . sqlsrv_errors());
            }

            $columnDefs = [
                "Machine",
                "MaintenanceType",
                "Repair",
                "Reason",
                "Description",
                "Photo",
                "TechnicianName",
                "StartTime",
                "EndTime",
            ];

            // Fetch results
            $i = 0;
            $rowData = [];
            while ($row = sqlsrv_fetch_array($maintenanceFormStmt, SQLSRV_FETCH_ASSOC)) {
                $rowData[$i] = $row;
                $rowData[$i]["insert"] = false;
                $rowData[$i]["Photo"] = base64_encode($row["Photo"]);
                $i++;
            }

            $data = [
                "columnDefs" => $columnDefs,
                "rowData" => $rowData
            ];

            return json_encode($data);
        } catch (Exception $e) {
            // Handle errors appropriately
            echo 'Error: ' . $e->getMessage();
        }
    }
}
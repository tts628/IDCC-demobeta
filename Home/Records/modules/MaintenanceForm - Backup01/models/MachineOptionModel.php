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
            // require_once realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/' . DB_FILE);
            // $maintenanceFormSql = "SELECT * 
            // FROM Maintenance_Records 
            // WHERE Deleted = 0
            // AND MachineType = ?";

            // $maintenanceFormStmt = sqlsrv_prepare($conn, $maintenanceFormSql, [$machineType]);

            // // Execute the statement
            // if (!sqlsrv_execute($maintenanceFormStmt)) {
            //     // Handle invalid sql execution
            //     sqlsrv_close($conn);
            //     throw new Exception('Sql error: ' . sqlsrv_errors());
            // }

            // // Fetch results
            // while ($row = sqlsrv_fetch_array($maintenanceFormStmt, SQLSRV_FETCH_ASSOC)) {
                
            // }

            return json_encode($machineType);
        } catch (Exception $e) {
            // Handle errors appropriately
            echo 'Error: ' . $e->getMessage();
        }
    }
}
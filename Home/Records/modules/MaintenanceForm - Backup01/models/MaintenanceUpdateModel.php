<?php
error_reporting(E_ALL);
const DB_FILE = 'env/database_IDCC.php';
$pathSegments = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
define('CLIENT_ROOT', $pathSegments[1]);
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
class MaintenanceUpdateModel
{
    public function updateData($controllerParams)
    {
        try {
            require_once realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/' . DB_FILE);
            $modifyAction = $controllerParams["userInput"]["modify-action"];
            $inputData = $controllerParams["userInput"];
            $machineType = $controllerParams["machineType"];

            if ($modifyAction === "add") {
                $maintenanceUpdateSql = "INSERT INTO Maintenance_Records (
                    Machine, 
                    MaintenanceType, 
                    Repair,
                    Reason,
                    Description,
                    Photo,
                    TechnicianName,
                    StartTime,
                    EndTime,
                    MachineType
                ) 
                VALUES (
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    ?, 
                    CONVERT(varbinary(max), ?), 
                    ?, 
                    ?, 
                    ?,
                    ?
                )";

                $params = [
                    $inputData["Machine"],
                    $inputData["MaintenanceType"],
                    $inputData["Repair"],
                    $inputData["Reason"],
                    $inputData["Description"],
                    $inputData["Photo"] === "" ? " " : [$inputData["Photo"], SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING(SQLSRV_ENC_BINARY)],
                    $inputData["TechnicianName"],
                    date("Y-m-d H:i:s", strtotime($inputData["StartTime"])),
                    date("Y-m-d H:i:s", strtotime($inputData["EndTime"])),
                    $machineType
                ];
            } else if ($modifyAction === "edit") {
                $maintenanceUpdateSql = "UPDATE 
                    Maintenance_Records 
                SET 
                    Machine = ?,
                    MaintenanceType = ?,
                    Repair = ?,
                    Reason = ?,
                    Description = ?,
                    Photo = ?,
                    TechnicianName = ?,
                    StartTime = ?,
                    EndTime = ?,
                    MachineType = ? 
                WHERE 
                    ID = ?";

                $params = [
                    $inputData["Machine"],
                    $inputData["MaintenanceType"],
                    $inputData["Repair"],
                    $inputData["Reason"],
                    $inputData["Description"],
                    $inputData["Photo"] === "" ? " " : [$inputData["Photo"], SQLSRV_PARAM_IN, SQLSRV_PHPTYPE_STRING(SQLSRV_ENC_BINARY)],
                    $inputData["TechnicianName"],
                    date("Y-m-d H:i:s", strtotime($inputData["StartTime"])),
                    date("Y-m-d H:i:s", strtotime($inputData["EndTime"])),
                    $machineType,
                    $inputData["data-id"]
                ];
            } else if ($modifyAction === "delete") {
                $maintenanceUpdateSql = "UPDATE 
                    Maintenance_Records 
                SET 
                    Deleted = 1
                WHERE 
                    ID = ?";

                $params = [
                    $inputData["data-id"]
                ];
            }

            $maintenanceUpdateStmt = sqlsrv_prepare($conn, $maintenanceUpdateSql, $params);

            if ($maintenanceUpdateStmt) {
                if (sqlsrv_execute($maintenanceUpdateStmt)) {
                    return json_encode("success");
                } else {
                    throw new Exception("Error executing statement: " . print_r(sqlsrv_errors(), true));
                }
            } else {
                throw new Exception("Error preparing statement: " . print_r(sqlsrv_errors(), true));
            }
        } catch (Exception $e) {
            // Handle errors appropriately
            echo json_encode('Error: ' . $e->getMessage());
        }
    }
}
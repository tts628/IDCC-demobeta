<?php
error_reporting(E_ALL);
const DB_FILE   = 'env/database_IDCC.php';
const UTIL_FILE = 'shared/models/utils.php';
$pathSegments = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
define('CLIENT_ROOT', $pathSegments[1]);
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);

// Use utility file
require_once realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/Home/' . UTIL_FILE);
class MaintenanceUpdateModel
{
    public function updateData($inputData)
    {
        try {
            require_once realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/' . DB_FILE);
            $modifyAction = $inputData["modify-action"];

            $uploadedFileNames = json_decode($inputData["file-names"]);
            $dbFileNames = json_decode($inputData["db-file-names"]);
            $mergedFileNames = array_merge($dbFileNames, $uploadedFileNames);

            if ($modifyAction === "add") {
                $maintenanceUpdateSql = "INSERT INTO Maintenance_Records (
                    ReferenceId, 
                    MaintenanceType, 
                    Repair,
                    Reason,
                    [Description],
                    TechnicianName,
                    StartTime,
                    EndTime,
                    MachineType,
                    [Picture],
                    MaintenanceNo
                ) 
                SELECT 
                    :ReferenceId, 
                    :MaintenanceType, 
                    :Repair,
                    :Reason,
                    :Description,
                    :TechnicianName,
                    :StartTime,
                    :EndTime,
                    :MachineType,
                    :Picture,
                    CONCAT('PMS', FORMAT(GETDATE(), 'yyMMdd'), COALESCE(MAX(id), 0) + 1)
                FROM Maintenance_Records";

                $params = [
                    "ReferenceId" => $inputData["machine-name"],
                    "MaintenanceType" => $inputData["maintenance-type"],
                    "Repair" => $inputData["repair"],
                    "Reason" => $inputData["reason"],
                    "Description" => $inputData["description"],
                    "TechnicianName" => $inputData["technician-name"],
                    "StartTime" => date("Y-m-d H:i:s", strtotime($inputData["start-time"])),
                    "EndTime" => date("Y-m-d H:i:s", strtotime($inputData["end-time"])),
                    "MachineType" => $inputData["machine-type"],
                    "Picture" => empty($mergedFileNames) ? NULL : json_encode($mergedFileNames),
                ];
            } else if ($modifyAction === "edit") {
                $maintenanceUpdateSql = "UPDATE 
                    Maintenance_Records 
                SET 
                    ReferenceId = :ReferenceId,
                    MaintenanceType = :MaintenanceType,
                    Repair = :Repair,
                    Reason = :Reason,
                    [Description] = :Description,
                    TechnicianName = :TechnicianName,
                    StartTime = :StartTime,
                    EndTime = :EndTime,
                    MachineType = :MachineType,
                    [Picture] = :Picture 
                WHERE 
                    ID = :ID";
                // Using ID instead of Maintenance Serial Number to prevent targetting deleted records

                $params = [
                    "ReferenceId" => $inputData["machine-name"],
                    "MaintenanceType" => $inputData["maintenance-type"],
                    "Repair" => $inputData["repair"],
                    "Reason" => $inputData["reason"],
                    "Description" => $inputData["description"],
                    "TechnicianName" => $inputData["technician-name"],
                    "StartTime" => date("Y-m-d H:i:s", strtotime($inputData["start-time"])),
                    "EndTime" => date("Y-m-d H:i:s", strtotime($inputData["end-time"])),
                    "MachineType" => $inputData["machine-type"],
                    "Picture" => empty($mergedFileNames) ? NULL : json_encode($mergedFileNames),
                    "ID" => $inputData["data-id"],
                ];
            } else if ($modifyAction === "delete") {
                $maintenanceUpdateSql = "UPDATE 
                    Maintenance_Records 
                SET 
                    Deleted = 1
                WHERE 
                    ID = :ID";
                // Using ID instead of Maintenance Serial Number to prevent targetting deleted records

                $params = [
                    "ID" => $inputData["data-id"]
                ];
            }

            $maintenanceUpdateStmt = $PDOconn->prepare($maintenanceUpdateSql);
            bindValues($maintenanceUpdateStmt, $params);

            // Execute the query
            $maintenanceUpdateStmt->execute();
            return "success";
        } catch (Exception $e) {
            // Handle errors appropriately
            echo json_encode('Error: ' . $e->getMessage());
        }
    }
}
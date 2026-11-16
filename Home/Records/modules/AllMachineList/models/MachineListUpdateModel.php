<?php
error_reporting(E_ALL);
const DB_FILE   = 'env/database_IDCC.php';
const UTILITIES = 'shared/models/utils.php';
$pathSegments = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
define('CLIENT_ROOT', $pathSegments[1]);
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);

require_once realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/Home/' . UTILITIES);

class MachineListUpdateModel
{
    public function updateData($controllerParams)
    {
        try {
            require_once realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/' . DB_FILE);
            $modifyAction = $controllerParams["modify-action"];
            $inputData = array_map('convertDateTimeString', $controllerParams);

            $uploadedFileNames = json_decode($inputData["file-names"]);
            $dbFileNames = json_decode($inputData["db-file-names"]);
            $mergedFileNames = array_merge($dbFileNames, $uploadedFileNames);

            // Default Machine Type update sql pair
            if ($modifyAction === "add") {
                $machineTypeUpdateSql = "INSERT INTO 
                    Maintenance_" . $inputData["machine-type"] . " (
                        MachineName,
                        MachineActualName,
                        SerialNumber
                    )
                VALUES 
                    (
                        :MachineName,
                        :MachineActualName,
                        :SerialNumber
                    )";
                // 
                $machineTypeParams = [
                    "MachineName" => $inputData["machine-number"],
                    "MachineActualName" => $inputData["machine-name"],
                    "SerialNumber" => $inputData["serial-number"],
                ];

                $machineListUpdateSql = "INSERT INTO 
                    Machine_List (
                        [ReferenceTable],
                        [Time],
                        [CustodianUnit],
                        [CustodianPersonnel],
                        [ActivationDate],
                        [DeactivationDate],
                        [CreatedDate],
                        [Picture],
                        [ReferenceId]
                    ) 
                VALUES 
                    (
                        :ReferenceTable,
                        :Time,
                        :CustodianUnit,
                        :CustodianPersonnel,
                        :ActivationDate,
                        :DeactivationDate,
                        :CreatedDate,
                        :Picture,
                        :ReferenceId
                    )";
                // 
                $machineListParams = [
                    "ReferenceTable" => $inputData["machine-type"],
                    "Time" => empty($inputData["time-input"]) ? NULL : $inputData["time-input"],
                    "CustodianUnit" => $inputData["custodian-unit"],
                    "CustodianPersonnel" => $inputData["custodian-personnel"],
                    "ActivationDate" => empty($inputData["activation-date"]) ? NULL : $inputData["activation-date"],
                    "DeactivationDate" => empty($inputData["deactivation-date"]) ? NULL : $inputData["deactivation-date"],
                    "CreatedDate" => empty($inputData["created-date"]) ? NULL : $inputData["created-date"],
                    "Picture" => empty($mergedFileNames) ? NULL : json_encode($mergedFileNames),
                ];
            } else if ($modifyAction === "edit") {
                $machineTypeUpdateSql = "UPDATE
                    Maintenance_" . $inputData["machine-type"] . "
                SET
                    SerialNumber = :SerialNumber,
                    MachineName = :MachineName,
                    MachineActualName = :MachineActualName
                WHERE
                    ID = :ReferenceId";
                // 
                $machineTypeParams = [
                    "MachineName" => $inputData["machine-number"],
                    "MachineActualName" => $inputData["machine-name"],
                    "SerialNumber" => $inputData["serial-number"],
                    "ReferenceId" => $inputData["reference-id"],
                ];

                $machineListUpdateSql = "UPDATE 
                    Machine_List
                SET 
                    [ReferenceTable] = :ReferenceTable,
                    [Time] = :Time,
                    [CustodianUnit] = :CustodianUnit,
                    [CustodianPersonnel] = :CustodianPersonnel,
                    [ActivationDate] = :ActivationDate,
                    [DeactivationDate] = :DeactivationDate,
                    [CreatedDate] = :CreatedDate,
                    [Picture] = :Picture
                WHERE 
                    ID = :ID";
                //
                $machineListParams = [
                    "ReferenceTable" => $inputData["machine-type"],
                    "Time" => empty($inputData["time-input"]) ? NULL : $inputData["time-input"],
                    "CustodianUnit" => $inputData["custodian-unit"],
                    "CustodianPersonnel" => $inputData["custodian-personnel"],
                    "ActivationDate" => empty($inputData["activation-date"]) ? NULL : $inputData["activation-date"],
                    "DeactivationDate" => empty($inputData["deactivation-date"]) ? NULL : $inputData["deactivation-date"],
                    "CreatedDate" => empty($inputData["created-date"]) ? NULL : $inputData["created-date"],
                    "Picture" => empty($mergedFileNames) ? NULL : json_encode($mergedFileNames),
                    "ID" => $inputData["data-id"]
                ];
            } else if ($modifyAction === "delete") {
                // No operation needed on sub table
                $machineTypeUpdateSql = "";

                $machineListUpdateSql = "UPDATE 
                    Machine_List 
                SET 
                    Deleted = 1
                WHERE 
                    ID = :ID";

                $machineListParams = [
                    "ID" => $inputData["data-id"]
                ];
            }
            // return;

            $PDOconn->beginTransaction();
            if (!empty($machineTypeUpdateSql)) {
                $machineTypeUpdateStmt = $PDOconn->prepare($machineTypeUpdateSql);
                bindValues($machineTypeUpdateStmt, $machineTypeParams);
                $machineTypeUpdateStmt->execute();
                $lastId = $PDOconn->lastInsertId();
                if ($modifyAction === "add") {
                    $machineListParams["ReferenceId"] = $lastId;
                }
            }

            $machineListUpdateStmt = $PDOconn->prepare($machineListUpdateSql);
            bindValues($machineListUpdateStmt, $machineListParams);
            $machineListUpdateStmt->execute();

            // Commit the transaction
            $PDOconn->commit();

            return json_encode("success");
        } catch (Exception $e) {
            // Handle errors appropriately
            $PDOconn->rollBack();
            var_dump($machineTypeUpdateSql);
            var_dump($machineTypeParams);
            var_dump($machineListUpdateSql);
            var_dump($machineListParams);
            return json_encode('Error: ' . $e->getMessage());
        }
    }
}
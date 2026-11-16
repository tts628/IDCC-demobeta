<?php
const DB_FILE   = 'env/database_IDCC.php';
const UTIL_FILE = 'shared/models/utils.php';
$pathSegments = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
define('CLIENT_ROOT', $pathSegments[1]);
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);

// Use utility file
require_once realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/Home/' . UTIL_FILE);
class IntegrityCheckModel
{
    public function checkIntegrity($inputData)
    {
        try {
            require_once realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/' . DB_FILE);

            // Prepare SQL statement
            if ($inputData["modify-action"] == "edit") {
                $sql = "SELECT
                    MR.*,
                    CONCAT(
                        MDM.MachineActualName,
                        MFM.MachineActualName,
                        MLM.MachineActualName,
                        MOM.MachineActualName,
                        MPM.MachineActualName,
                        MPtM.MachineActualName,
                        MSM.MachineActualName,
                        MStM.MachineActualName,
                        MWDM.MachineActualName
                    ) AS MachineActualName
                FROM
                    Maintenance_Records MR
                    LEFT JOIN Machine_List ML ON MR.ReferenceId = ML.ReferenceId
                    AND ML.ReferenceTable = MR.MachineType
                    LEFT JOIN Maintenance_DyeMachines MDM
                    ON ML.ReferenceId = MDM.ID AND ML.ReferenceTable = 'DyeMachines'
                    LEFT JOIN Maintenance_FinishMachines MFM
                    ON ML.ReferenceId = MFM.ID AND ML.ReferenceTable = 'FinishMachines'
                    LEFT JOIN Maintenance_LabMachines MLM
                    ON ML.ReferenceId = MLM.ID AND ML.ReferenceTable = 'LabMachines'
                    LEFT JOIN Maintenance_OthersMachines MOM
                    ON ML.ReferenceId = MOM.ID AND ML.ReferenceTable = 'OthersMachines'
                    LEFT JOIN Maintenance_PackagingMachines MPM
                    ON ML.ReferenceId = MPM.ID AND ML.ReferenceTable = 'PackagingMachines'
                    LEFT JOIN Maintenance_PrintingMachines MPtM
                    ON ML.ReferenceId = MPtM.ID AND ML.ReferenceTable = 'PrintingMachines'
                    LEFT JOIN Maintenance_SingeingMachines MSM
                    ON ML.ReferenceId = MSM.ID AND ML.ReferenceTable = 'SingeingMachines'
                    LEFT JOIN Maintenance_StenterMachines MStM
                    ON ML.ReferenceId = MStM.ID AND ML.ReferenceTable = 'StenterMachines'
                    LEFT JOIN Maintenance_WashingDryerMachines MWDM
                    ON ML.ReferenceId = MWDM.ID AND ML.ReferenceTable = 'WashingDryerMachines'
                WHERE
                    MR.Deleted = 0
                    AND ML.Deleted = 0
                    AND MR.MaintenanceNo = :MaintenanceNo
                ORDER BY
                    ML.ReferenceTable, ML.ReferenceId";
            }
            $stmt = $PDOconn->prepare($sql);
            bindValues($stmt, ["MaintenanceNo" => $inputData["maintenance-number"]]);

            // Execute the query
            $stmt->execute();

            // Set the resulting array to associative
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data[0]["Photo"] = "";

            return $data;
        } catch (Exception $e) {
            // Handle errors appropriately
            echo 'Error: ' . $e->getMessage();
        }
    }
}
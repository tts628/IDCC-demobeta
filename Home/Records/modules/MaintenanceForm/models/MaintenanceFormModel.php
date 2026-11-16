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
            $maintenanceFormSql = "SELECT
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
                AND ML.Deleted = 0";

            $maintenanceFormStmt = sqlsrv_prepare($conn, $maintenanceFormSql);

            // Execute the statement
            if (!sqlsrv_execute($maintenanceFormStmt)) {
                // Handle invalid sql execution
                sqlsrv_close($conn);
                throw new Exception('Sql error: ' . sqlsrv_errors());
            }

            $columnDefs = [
                "MaintenanceNo",
                "MachineActualName",
                "MaintenanceType",
                "Repair",
                "Reason",
                "Description",
                "Picture",
                "TechnicianName",
                "StartTime",
                "EndTime",
            ];

            // Fetch results
            $rowData = [];
            while ($row = sqlsrv_fetch_array($maintenanceFormStmt, SQLSRV_FETCH_ASSOC)) {
                $row["insert"] = false;
                $rowData[] = $row;
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
<?php
const DB_FILE = 'env/database_IDCC.php';
$pathSegments = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
define('CLIENT_ROOT', $pathSegments[1]);
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
class MachineListDataModel
{
    public function getDataByDefault()
    {
        try {
            require_once realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/' . DB_FILE);

            // Prepare SQL statement
            $sql = "SELECT
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
                ) AS MachineActualName,
                CONCAT(
                    MDM.ID,
                    MFM.ID,
                    MLM.ID,
                    MOM.ID,
                    MPM.ID,
                    MPtM.ID,
                    MSM.ID,
                    MStM.ID,
                    MWDM.ID
                ) AS ID,
                ML.ReferenceTable
            FROM
                Machine_List ML
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
                ML.Deleted = 0
            ORDER BY
                ML.ReferenceTable, ML.ReferenceId";
            $stmt = $PDOconn->prepare($sql);

            // Execute the query
            $stmt->execute();

            // Set the resulting array to associative
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $data = [];
            foreach ($rows as $rowIndex => $row) {
                $data[$row["ReferenceTable"]][] = $row;
            }

            return $data;
        } catch (Exception $e) {
            // Handle errors appropriately
            echo 'Error: ' . $e->getMessage();
        }
    }
}
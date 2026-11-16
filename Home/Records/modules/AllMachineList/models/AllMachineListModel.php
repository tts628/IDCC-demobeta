<?php
const DB_FILE = 'env/database_IDCC.php';
$pathSegments = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
define('CLIENT_ROOT', $pathSegments[1]);
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
class MachineListModel
{
    // private function machineTypeMapper($referenceTable) {
    //     switch($referenceTable) {
    //         case "DyeMachines":
    //             return "染缸";
    //         case "FinishMachines":
    //             return "後整理";
    //         case "LabMachines":
    //             return "化驗室";
    //         case "OthersMachines":
    //             return "其他";
    //         case "PackagingMachines":
    //             return "包裝";
    //         case ""
    //     }
    // }
    public function getDataByDefault()
    {
        try {
            require_once realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/' . DB_FILE);
            $machineListSql = "SELECT
                ML.[ID],
                ML.ReferenceId,
                CONCAT(
                    MDM.SerialNumber,
                    MFM.SerialNumber,
                    MLM.SerialNumber,  
                    MOM.SerialNumber,
                    MPM.SerialNumber,
                    MPtM.SerialNumber,
                    MSM.SerialNumber,
                    MStM.SerialNumber,
                    MWDM.SerialNumber
                ) AS SerialNumber,
                CONCAT(
                    MDM.MachineName,
                    MFM.MachineName,
                    MLM.MachineName,
                    MOM.MachineName,
                    MPM.MachineName,
                    MPtM.MachineName,
                    MSM.MachineName,
                    MStM.MachineName,
                    MWDM.MachineName
                ) AS MachineName,
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
                ML.[ReferenceTable],
                ML.[Time],
                ML.[CustodianUnit],
                ML.[CustodianPersonnel],
                ML.[Picture],
                ML.[ActivationDate],
                ML.[DeactivationDate],
                ML.[CreatedDate],
                ML.[UpdatedTime]
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

            $machineListStmt = sqlsrv_prepare($conn, $machineListSql);

            // Execute the statement
            if (!sqlsrv_execute($machineListStmt)) {
                // Handle invalid sql execution
                var_dump(sqlsrv_errors());
                sqlsrv_close($conn);
                throw new Exception("Error executing statement: machineListStmt");
            }

            $columnDefs = [
                "MachineActualName",
                "ReferenceTable",
                "SerialNumber",
                "Time",
                "CustodianUnit",
                "Picture",
                "CustodianPersonnel",
                "ActivationDate",
                "DeactivationDate",
                "CreatedDate",
            ];

            // Fetch results
            $rowData = [];
            while ($row = sqlsrv_fetch_array($machineListStmt, SQLSRV_FETCH_ASSOC)) {
                foreach ($row as $key => $value) {
                    if (is_object($value)) {
                        $row[$key] = $value->format("Y-m-d H:i:s");
                    }
                }
                $row["SerialNumber"] = (int) $row["SerialNumber"];
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
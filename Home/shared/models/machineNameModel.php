<?php
class MachineNameModel
{
    public function getMachineNameData($module)
    {
        $rootPath = $_SERVER['DOCUMENT_ROOT'];
        $clientRoot = explode('/', $_SERVER["REQUEST_URI"])[1];
        require_once ($rootPath . '/' . $clientRoot . '/db/database_SPC.php');

        $machineNameSql = "SELECT
            DISTINCT(Machine)
        FROM
            Dyelots
        ORDER BY
            Machine";

        $machineNameStmt = sqlsrv_prepare($conn, $machineNameSql);

        // Execute the statement
        if (!sqlsrv_execute($machineNameStmt)) {
            // echo json_encode("stmt yield execution is false");
            echo json_encode(sqlsrv_errors());
            sqlsrv_close($conn);
            exit();
        }

        $data = [];
        // Fetch results
        while ($row = sqlsrv_fetch_array($machineNameStmt, SQLSRV_FETCH_ASSOC)) {
            $data[] = $row["Machine"];
        }

        return json_encode($data);
    }
}
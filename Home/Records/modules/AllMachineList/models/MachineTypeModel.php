<?php
const DB_FILE = 'env/database_IDCC.php';
$pathSegments = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
define('CLIENT_ROOT', $pathSegments[1]);
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
class MachineTypeModel
{
    public function getDataByDefault()
    {
        try {
            require_once realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/' . DB_FILE);
            $machineTypeSql = "SELECT
                DISTINCT(ReferenceTable)
            FROM
                Machine_List
            WHERE
                Deleted = 0";

            $machineTypeStmt = sqlsrv_prepare($conn, $machineTypeSql);

            // Execute the statement
            if (!sqlsrv_execute($machineTypeStmt)) {
                // Handle invalid sql execution
                var_dump(sqlsrv_errors());
                sqlsrv_close($conn);
                throw new Exception("Error executing statement: machineTypeStmt");
            }

            // Fetch results
            $data = [];
            while ($row = sqlsrv_fetch_array($machineTypeStmt, SQLSRV_FETCH_ASSOC)) {
                $data[] = $row["ReferenceTable"];
            }

            return json_encode($data);
        } catch (Exception $e) {
            // Handle errors appropriately
            echo 'Error: ' . $e->getMessage();
        }
    }
}
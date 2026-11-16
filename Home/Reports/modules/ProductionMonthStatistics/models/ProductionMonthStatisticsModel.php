<?php
const MODULE_SETTINGS_FILE = 'models/parseModuleSettings.php';
const ENV_SETTINGS_FILE    = 'env/env.json';
const DB_FILE              = 'env/database_SPC.php';
$pathSegments = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
define('CLIENT_ROOT', $pathSegments[1]);
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
class ProductionMonthStatisticsModel
{
    public function getDataByDateRange($dateRange)
    {

        try {
            $params = $dateRange;


            require_once realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/' . DB_FILE);
            $productionStatisticsSql = "SELECT
                Month,
                ROUND(CAST(COALESCE(SUM(TotalWeight), 0) AS NUMERIC(10, 2)), 2) AS 總產量
            FROM
                (
                    SELECT
                        SUBSTRING(CAST(DATENAME(MONTH, EndTime) as Varchar), 1, 3) + ' ' + SUBSTRING(CAST(DATENAME(YEAR, EndTime) As Varchar), 3, 2) As Month,
                        EndTime,
                        TotalWeight
                    FROM
                        Dyelots
                    WHERE
                        State = 3
                        And EndTime BETWEEN ?
                        And ?
                ) Sub
            GROUP BY
                Month
            ORDER BY
                Min(EndTime)";

            $productionStatisticsStmt = sqlsrv_prepare($conn, $productionStatisticsSql, $params);

            // Execute the statement
            if (!sqlsrv_execute($productionStatisticsStmt)) {
                // Handle invalid sql execution
                sqlsrv_close($conn);
                var_dump(sqlsrv_errors());
                throw new Exception('SQL SRV ERROR');
            }

            $data[] = ["Month" => "Month", "總產量" => "總產量"];
            // Fetch results
            while ($row = sqlsrv_fetch_array($productionStatisticsStmt, SQLSRV_FETCH_ASSOC)) {
                $data[] = $row;
            }

            return json_encode($data);
        } catch (Exception $e) {
            // Handle errors appropriately
            echo 'Error: ' . $e->getMessage();
        }
    }
}
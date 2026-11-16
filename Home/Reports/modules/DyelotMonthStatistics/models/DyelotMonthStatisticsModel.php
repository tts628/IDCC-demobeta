<?php
const MODULE_SETTINGS_FILE = 'models/parseModuleSettings.php';
const ENV_SETTINGS_FILE    = 'env/env.json';
const DB_FILE              = 'env/database_SPC.php';
$pathSegments = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
define('CLIENT_ROOT', $pathSegments[1]);
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
class DyelotMonthStatisticsModel
{
    public function getDataByDateRange($dateRange)
    {

        try {
            require_once realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/bootstrap/' . MODULE_SETTINGS_FILE);
            $settingsFilePath = realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/' . ENV_SETTINGS_FILE);
            $moduleSettingsModel = new ModuleSettingsModel(); // Create an instance of the model
            $JsonSettings = $moduleSettingsModel->getModuleSettingsData($settingsFilePath);
            $settings = json_decode($JsonSettings);

            // Extract and process dedicated machine
            $dedicatedMachine = $settings->machine ?? null; // Handle potential missing key
            if ($dedicatedMachine) {
                $machines = explode(' - ', $dedicatedMachine);

                if (count($machines) !== 2) {
                    // Handle unexpected machine format
                    throw new Exception('Invalid machine format in settings: ' . $dedicatedMachine);
                }

                // [$firstMachine, $lastMachine] = $machines;
            }

            $params = array_merge($dateRange, $machines);


            require_once realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/' . DB_FILE);
            $dyelotMonthStatisticsSql = "SELECT
                Month,
                Machine,
                COUNT(*) As Dyelots
            FROM
                (
                    SELECT
                        SUBSTRING(CAST(DATENAME(MONTH, EndTime) as Varchar), 1, 3) + ' ' + SUBSTRING(CAST(DATENAME(YEAR, EndTime) As Varchar), 3, 2) As Month,
                        Machine,
                        EndTime
                    FROM
                        Dyelots
                    WHERE
                        State = 3
                        And ReDye = 0
                        And EndTime BETWEEN ?
                        And ?
                        AND (
                            Machine >= ?
                            AND Machine <= ?
                        )
                ) Sub
            GROUP BY
                Month,
                Machine
            ORDER BY
                Min(EndTime)";

            $dyelotMonthStatisticsStmt = sqlsrv_prepare($conn, $dyelotMonthStatisticsSql, $params);

            // Execute the statement
            if (!sqlsrv_execute($dyelotMonthStatisticsStmt)) {
                // Handle invalid sql execution
                sqlsrv_close($conn);
                throw new Exception('Invalid sql query execution: ' . sqlsrv_errors());
            }

            function replaceSpaces($str) {
                return str_replace(" ", "_", $str);
            }
            
            $data = $sqlData = [];
            $monthMap = ["機台\月份"];
            // Fetch results
            while ($row = sqlsrv_fetch_array($dyelotMonthStatisticsStmt, SQLSRV_FETCH_ASSOC)) {
                // $data[$row["Month"]][$row["Machine"]] = $row["Dyelots"];
                $Machine = $row["Machine"];
                $Month = $row["Month"];
                $Dyelots = $row["Dyelots"];

                $monthKey = replaceSpaces($Month);
                $sqlData[$Machine]["machine"] = $Machine;
                $sqlData[$Machine]["$monthKey"] = $Dyelots;

                foreach ($sqlData as $machineKey => $loopedData) {
                    if (!isset($loopedData["$monthKey"])) {
                        $sqlData[$machineKey]["$monthKey"] = 0;
                    }
                }

                if (!in_array($Month, $monthMap)) {
                    $monthMap[] = $Month;
                }
            }

            $fieldMap = array_map("replaceSpaces", $monthMap);
            $fieldMap[0] = "machine";

            $sqlData = array_values($sqlData);

            $data = [
                "columnDefs" => [
                    "customName" => $monthMap,
                    "field" => $fieldMap,
                ],
                "rowData" => $sqlData
            ];


            return json_encode($data);
        } catch (Exception $e) {
            // Handle errors appropriately
            echo 'Error: ' . $e->getMessage();
        }
    }
}
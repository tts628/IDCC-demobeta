<?php
const MODULE_SETTINGS_FILE = 'models/parseModuleSettings.php';
const ENV_SETTINGS_FILE    = 'env/env.json';
const DB_FILE              = 'env/database_SPC.php';
$pathSegments = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
define('CLIENT_ROOT', $pathSegments[1]);
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
class DyelotStatisticsModel
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
            $dyelotStatisticsSql = "SELECT
                Machine,
                Count(*) As Dyelots
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
            GROUP BY
                Machine
            ORDER BY
                Machine";

            $dyelotStatisticsStmt = sqlsrv_prepare($conn, $dyelotStatisticsSql, $params);

            // Execute the statement
            if (!sqlsrv_execute($dyelotStatisticsStmt)) {
                // Handle invalid sql execution
                sqlsrv_close($conn);
                throw new Exception('Invalid machine format in settings: ' . sqlsrv_errors());
            }

            $data[] = ["Machine" => "Machine", "Dyelots" => "Dyelots"];
            // Fetch results
            while ($row = sqlsrv_fetch_array($dyelotStatisticsStmt, SQLSRV_FETCH_ASSOC)) {
                $data[] = $row;
            }

            return json_encode($data);
        } catch (Exception $e) {
            // Handle errors appropriately
            echo 'Error: ' . $e->getMessage();
        }
    }
}
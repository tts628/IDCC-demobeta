<?php
const MODULE_SETTINGS_FILE = 'models/parseModuleSettings.php';
const ENV_SETTINGS_FILE    = 'env/env.json';
const DB_FILE              = 'env/database_SPC.php';
$pathSegments = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
define('CLIENT_ROOT', $pathSegments[1]);
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
class AlarmTimeStatisticsModel
{
    public function getDataByDefault()
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

            $params = array_merge($machines);


            require_once realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/' . DB_FILE);
            $alarmStatisticsSql = "SELECT
                Machine,
                Cast(Sum(Duration) * 24 As Numeric(10, 2)) As Hours
            FROM
                Alarms
            WHERE
                (
                    Machine >= ?
                    AND Machine <= ?
                )
                AND (Dyelot <> ' ')
            GROUP BY
                Machine";

            $alarmStatisticsStmt = sqlsrv_prepare($conn, $alarmStatisticsSql, $params);

            // Execute the statement
            if (!sqlsrv_execute($alarmStatisticsStmt)) {
                // Handle invalid sql execution
                sqlsrv_close($conn);
                throw new Exception('Invalid machine format in settings: ' . sqlsrv_errors());
            }

            $data[] = ["Machine" => "Machine", "Hours" => "Hours"];
            // Fetch results
            while ($row = sqlsrv_fetch_array($alarmStatisticsStmt, SQLSRV_FETCH_ASSOC)) {
                $data[] = $row;
            }

            return json_encode($data);
        } catch (Exception $e) {
            // Handle errors appropriately
            echo 'Error: ' . $e->getMessage();
        }
    }
}
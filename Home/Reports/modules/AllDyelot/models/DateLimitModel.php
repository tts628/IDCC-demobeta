<?php
const MODULE_SETTINGS_FILE = 'models/parseModuleSettings.php';
const ENV_SETTINGS_FILE    = 'env/env.json';
const DB_FILE              = 'env/database_SPC.php';
$pathSegments = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
define('CLIENT_ROOT', $pathSegments[1]);
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);

class DateLimitModel
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

            require_once realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/' . DB_FILE);

            $dateLimitSql = "SELECT
                MIN(EndTime) AS EarliestEndTime,
                MAX(EndTime) AS LatestEndTime
            FROM
				Dyelots
            WHERE 
                SheetNo IN (
                    SELECT 
                        DISTINCT(SheetNo) AS SheetNo
                    FROM 
                        Dyelots
                        -- [SheetNoDetails]
                    WHERE
                        StartTime IS NOT NULL
                        AND SheetNo <> ' '
                        And (
                            CAST(DATEDIFF(s, StartTime, EndTime) AS FLOAT) / 3600
                        ) > 1
                        AND (
                            Machine >= ?
                            AND Machine <= ?
                        )
                )
                AND StartTime IS NOT NULL";

            $dateLimitStmt = sqlsrv_prepare($conn, $dateLimitSql, $machines);

            // Execute the statement
            if (!sqlsrv_execute($dateLimitStmt)) {
                // Handle invalid sql execution
                var_dump(sqlsrv_errors());
                throw new Exception('Query error on dateLimitStmt');
            }

            $data = [];
            // Fetch results
            while ($row = sqlsrv_fetch_array($dateLimitStmt, SQLSRV_FETCH_ASSOC)) {
                $data = $row;
            }

            return json_encode($data);
        } catch (Exception $e) {
            // Handle errors appropriately
            echo 'Error: ' . $e->getMessage();
        }
    }
}
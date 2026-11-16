<?php
const MODULE_SETTINGS_FILE = 'models/parseModuleSettings.php';
const ENV_SETTINGS_FILE    = 'env/env.json';
const DB_FILE              = 'env/database_SPC.php';
$pathSegments = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
define('CLIENT_ROOT', $pathSegments[1]);
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);

function valueFormatter($targetArr) {
    $targetArr["StartTime"] = dateFormatter($targetArr["StartTime"]);
    $targetArr["EndTime"] = dateFormatter($targetArr["EndTime"]);
    $targetArr["CreationTime"] = dateFormatter($targetArr["CreationTime"]);
    $targetArr["Color"] = getColorHex($targetArr["Color"]);
    $targetArr["RunTimeMinutes"] = round($targetArr["RunTimeMinutes"], 2);
    $targetArr["AverageRunTime"] = round($targetArr["AverageRunTime"], 2);
    $targetArr["TotalWeight"] = round($targetArr["TotalWeight"], 2);
    $targetArr["WaterUsed"] = round($targetArr["WaterUsed"], 2);
    $targetArr["WaterUsedPerKg"] = round($targetArr["WaterUsedPerKg"], 2);
    $targetArr["SteamUsed"] = round($targetArr["SteamUsed"], 2);
    $targetArr["SteamUsedPerKg"] = round($targetArr["SteamUsedPerKg"], 2);
    $targetArr["PowerUsed"] = round($targetArr["PowerUsed"], 2);
    $targetArr["PowerUsedPerKg"] = round($targetArr["PowerUsedPerKg"], 2);
    return $targetArr;
}

function dateFormatter($params)
{
    // Set the default time zone
    date_default_timezone_set('Asia/Shanghai');

    $year = $params->format('Y');
    $month = $params->format('m');
    $day = $params->format('d');
    $hours = $params->format('H');
    $minutes = $params->format('i');
    $ampm = $hours >= 12 ? "下午" : "上午";
    $hour12 = ($hours % 12 != 0) ? $hours % 12 : 12;
    return "{$year}-{$month}-{$day} {$ampm} {$hour12}:{$minutes}";
}


function getColorHex($row)
{
    if ($row == '') {
        $color = '#000000';
        $b = '0';
        $g = '0';
        $r = '0';
    } else {
        $r = $row & 0xff;
        $g = ($row >> 8) & 0xff;
        $b = ($row >> 16) & 0xff;
        $r2 = dechex($r);
        $g2 = dechex($g);
        $b2 = dechex($b);
        if (strlen($r2) == 1) {
            $r3 = '0' . $r2;
        } else {
            $r3 = $r2;
        }
        if (strlen($g2) == 1) {
            $g3 = '0' . $g2;
        } else {
            $g3 = $g2;
        }
        if (strlen($b2) == 1) {
            $b3 = '0' . $b2;
        } else {
            $b3 = $b2;
        }
        $color = '#' . $r3 . $g3 . $b3;
    }
    return $color;
}

class AllDyelotModel
{
    private function extractSheetNo($sheetNo)
    {
        if (strpos($sheetNo, '-') !== false) {
            return (string) trim(strstr($sheetNo, '-', true));
        }
        return (string) $sheetNo;
    }

    public function getDataByCustomInput($preparedInput)
    {
        function roundIfNumber($value)
        {
            if (is_numeric($value)) {
                return round($value, 2);
            }
            return $value;
        }

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
            $allDyelotSql = "SELECT
                '正常' AS RedyeType,
                [SheetNo],

                [Batch],
                [FabricName],
                [ColorNo],
                [ColorName],
                [OrderNo],
                [CustomerName],
                [Machine],

                -- [StartTime],
                -- [EndTime],
                DATEADD(HOUR, 7, StartTime) AS StartTime,
                DATEADD(HOUR, 7, EndTime) AS EndTime,
                CASE
                    WHEN ISNUMERIC(RunTime) = 1 THEN ROUND(CAST(RunTime AS float) * 24 * 60, 2)
                    ELSE 0
                END AS [RunTimeMinutes],
                -- DATEDIFF(MINUTE, StartTime, EndTime) AS RunTimeMinutes,
                CASE
                    WHEN ISNUMERIC(RunTime) = 1 AND ISNUMERIC(TotalWeight) = 1 THEN 
                        ROUND(CAST(RunTime AS float) * 24 * 60 / CAST(TotalWeight AS float), 2)
                    ELSE 0
                END AS AverageRunTime,
                -- CASE
                --     WHEN ISNUMERIC(TotalWeight) = 1 THEN 
                --         ROUND(CAST(DATEDIFF(MINUTE, StartTime, EndTime) AS float) / CAST(TotalWeight AS float), 2)
                --     ELSE 0
                -- END AS AverageRunTime,
                [TotalWeight],
                ISNULL(WaterUsed, 0) AS WaterUsed,
                -- [WaterUsedPerKg],
                CASE
                    WHEN ISNUMERIC(WaterUsed) = 1 AND ISNUMERIC(TotalWeight) = 1 THEN 
                        ROUND(CAST(WaterUsed AS float) / CAST(TotalWeight AS float), 2)
                    ELSE 0
                END AS [WaterUsedPerKg],
                ISNULL(SteamUsed, 0) AS SteamUsed,
                -- [SteamUsedPerKg],
                CASE
                    WHEN ISNUMERIC(SteamUsed) = 1 AND ISNUMERIC(TotalWeight) = 1 THEN 
                        ROUND(CAST(SteamUsed AS float) / CAST(TotalWeight AS float), 2)
                    ELSE 0
                END AS [SteamUsedPerKg],
                ISNULL(PowerUsed, 0) AS PowerUsed,
                -- [PowerUsedPerKg],
                CASE
                    WHEN ISNUMERIC(PowerUsed) = 1 AND ISNUMERIC(TotalWeight) = 1 THEN 
                        ROUND(CAST(PowerUsed AS float) / CAST(TotalWeight AS float), 2)
                    ELSE 0
                END AS [PowerUsedPerKg],

                [Color],
                [CreationTime],

                Dyelot,
                COALESCE(ProgramsUsed, '') AS ProgramsUsed
            FROM
                Dyelots
            WHERE 
                SheetNo IN (
                SELECT 
                    DISTINCT(SheetNo)
                FROM 
                    Dyelots
                    -- [SheetNoDetails]
                WHERE
                    StartTime Is Not Null
                    AND SheetNo <> ' '
                    And (
                        CAST(DATEDIFF(s, StartTime, EndTime) As float) / 3600
                    ) > 1
                    AND (
                            Machine >= ?
                            AND Machine <= ?
                    )
                    AND (
                        ";
            if (!empty($preparedInput[0])) {
                $allDyelotSql .= " DATEADD(HOUR, 7, EndTime) BETWEEN ? AND ? ";
                $params = array_merge($machines, [$preparedInput[0], $preparedInput[1]]);
            } elseif (!empty($preparedInput[0]) && $preparedInput[2] !== "%%") {
                $allDyelotSql .= " OR ";
                $params = array_merge($machines, $preparedInput);
            } elseif ($preparedInput[2] !== "%%") {
                $allDyelotSql .= " SheetNo LIKE ? ";
                $params = array_merge($machines, [$preparedInput[2]]);
            }
            $allDyelotSql .= "
                    )
                )
                ORDER BY
                    SheetNo, StartTime";

            $allDyelotStmt = sqlsrv_prepare($conn, $allDyelotSql, $params);

            // Execute the statement
            if (!sqlsrv_execute($allDyelotStmt)) {
                // Handle invalid sql execution
                var_dump(sqlsrv_errors());
                throw new Exception('Query error');
            }

            $data = [];
            $tempSheetNo = "";
            $groupFlag = false;
            $tempRow = [];
            // Fetch results
            while ($row = sqlsrv_fetch_array($allDyelotStmt, SQLSRV_FETCH_ASSOC)) {
                // Compare SheetNo of current row with previous row
                // Grouped if same and continue if not

                // On initialization, assign $row to $tempRow to prepare comparison with the next data
                if (!isset($previousRow)) {
                    // $tempRow recorded with formatted $row
                    $previousRow = $row;
                    $tempRow = valueFormatter($previousRow);
                } else {
                    // Prepare raw SheetNo for comparison (to prevent case of eg. "124306052-2" with "124306052")
                    $previousSheetNo = $this->extractSheetNo($previousRow["SheetNo"]);
                    $currentSheetNo = $this->extractSheetNo($row["SheetNo"]);

                    $timeDiff = $row["StartTime"]->diff($previousRow["EndTime"]);
                    // Difference in hours, (not rounded)
                    $hoursDiff = ($timeDiff->days * 24) + $timeDiff->h;

                    if ($previousSheetNo === $currentSheetNo) {
                        // SheetNo is the same, prepare grouped data

                        // $groupFlag as indicator of start of grouped data
                        if (!$groupFlag) {
                            // Initialize $groupedData and indicator $groupFlag
                            $groupedData = $previousRow;
                            $groupedData["SheetNo"] = $previousSheetNo;
                            $rawGroupedData = [];
                            $groupFlag = true;
                        }

                        // StartTime might not arranged ascendingly
                        if ($groupedData["StartTime"] > $row["StartTime"]) {
                            $groupedData["StartTime"] = $row["StartTime"];
                        }
                        // EndTime might not arranged ascendingly
                        if ($groupedData["EndTime"] < $row["EndTime"]) {
                            $groupedData["EndTime"] = $row["EndTime"];
                        }

                        // Add every necessary data
                        $groupedData["RunTimeMinutes"] += $row["RunTimeMinutes"];
                        $groupedData["WaterUsed"] += $row["WaterUsed"];
                        $groupedData["SteamUsed"] += $row["SteamUsed"];
                        $groupedData["PowerUsed"] += $row["PowerUsed"];
                        $groupedData["AverageRunTime"] += $row["AverageRunTime"];
                        $groupedData["WaterUsedPerKg"] += $row["WaterUsedPerKg"];
                        $groupedData["SteamUsedPerKg"] += $row["SteamUsedPerKg"];
                        $groupedData["PowerUsedPerKg"] += $row["PowerUsedPerKg"];
                        $rawGroupedData[] = $tempRow;
                    } elseif ($groupFlag) {
                        // First data deviance after a data group. Record the last data and reset $groupFlag
                        // EndTime might not arranged ascendingly
                        if ($groupedData["EndTime"] < $previousRow["EndTime"]) {
                            $groupedData["EndTime"] = $previousRow["EndTime"];
                        }
                        $groupedData = array_map('roundIfNumber', $groupedData);
                        $rawGroupedData[] = $tempRow;
                        $preparedGroupedData = array_merge($groupedData, ["rawGroupedData" => $rawGroupedData]);
                        $data[] = valueFormatter($preparedGroupedData);
                        $groupFlag = false;
                    } else {
                        // No same SheetNo occurence, proceed as usual
                        $data[] = $tempRow;
                    }

                    // Update $previousRow after every necessary process is done
                    $previousRow = $row;
                    $tempRow = valueFormatter($previousRow);
                }
            }

            // End of data, process the last trailing $row (recorded as $previousRow)
            if ($groupFlag) {
                // If $groupFlag indicator is true, the last data and the second last data's SheetNo are the same
                if ($groupedData["EndTime"] < $previousRow["EndTime"]) {
                    $groupedData["EndTime"] = $previousRow["EndTime"];
                }
                $rawGroupedData[] = $tempRow;
                $preparedGroupedData = array_merge($groupedData, ["rawGroupedData" => $rawGroupedData]);
                $data[] = valueFormatter($preparedGroupedData);
                $groupFlag = false;
            } else {
                if (isset($previousRow)) {
                    $data[] = $tempRow;
                }
            }

            // // Finalize formatted values
            // foreach ($data as $row) {
            //     if (isset($row["rawGroupedData"])) {
            //         foreach ($row["rawGroupedData"] as $rawGroupedRow) {
            //             $rawGroupedRow["StartTime"] = valueFormatter($rawGroupedRow["StartTime"]);
            //             $rawGroupedRow["EndTime"] = valueFormatter($rawGroupedRow["EndTime"]);
            //             $rawGroupedRow["CreationTime"] = valueFormatter($rawGroupedRow["CreationTime"]);
            //             $rawGroupedRow["Color"] = getColorHex($rawGroupedRow["Color"]);
            //             $rawGroupedRow["RunTimeMinutes"] = round($rawGroupedRow["RunTimeMinutes"], 2);
            //             $rawGroupedRow["AverageRunTime"] = round($rawGroupedRow["AverageRunTime"], 2);
            //         }
            //     }
            //     $row["StartTime"] = valueFormatter($row["StartTime"]);
            //     $row["EndTime"] = valueFormatter($row["EndTime"]);
            //     $row["CreationTime"] = valueFormatter($row["CreationTime"]);
            //     $row["Color"] = getColorHex($row["Color"]);
            //     $row["RunTimeMinutes"] = round($row["RunTimeMinutes"], 2);
            //     $row["AverageRunTime"] = round($row["AverageRunTime"], 2);
            // }

            return json_encode($data);
        } catch (Exception $e) {
            // Handle errors appropriately
            echo 'Error: ' . $e->getMessage();
        }
    }
}
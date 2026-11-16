<?php
const MODULE_SETTINGS_FILE = 'models/parseModuleSettings.php';
const ENV_SETTINGS_FILE = 'env/env.json';
const DB_FILE = 'env/database_SPC.php';
$pathSegments = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
define('CLIENT_ROOT', $pathSegments[1]);
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);

function valueFormatter($targetArr)
{
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
    // date_default_timezone_set('Asia/Shanghai');
    return $params->format('Y-m-d H:i:s');
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
    private function updateRowData(&$data, &$groupedData, &$tempRow, &$rawGroupedData, $RedyeType)
    {
        $groupedData["RedyeType"] = $RedyeType;
        $tempRow["RedyeType"] = $RedyeType;

        $groupedData = array_map('round2Decimal', $groupedData);
        $rawGroupedData[] = $tempRow;
        $preparedGroupedData = array_merge($groupedData, ["rawGroupedData" => $rawGroupedData]);
        $data[] = valueFormatter($preparedGroupedData);
        // if ($groupedData["SheetNo"] == "124120227") {
        //     echo "RedyeType: ";
        //     var_dump($RedyeType);
        //     echo "/n/n";
        //     echo "<br><br>";
        //     echo "groupedData: ";
        //     var_dump($groupedData);
        //     echo "/n/n";
        //     echo "<br><br>";
        //     echo "rawGroupedData: ";
        //     var_dump($rawGroupedData);
        //     echo "\n\n";
        //     echo "/n/n";
        //     echo "<br><br>";
        // }
    }

    private function extractBetweenTwoHyphens($string)
    {
        if (substr_count($string, '-') === 2) {
            $parts = explode('-', $string);
            if (count($parts) === 3) {
                return (int) $parts[1]; // Return the middle part
            }
        }
        return null; // Return null if the conditions aren't met
    }

    public function getDataByCustomInput($preparedInput)
    {
        function round2Decimal($value)
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

            $erpAvailable = false;
            $remoteSrvCheckSql = "EXEC sp_linkedservers;";
            $remoteSrvCheckStmt = sqlsrv_prepare($conn, $remoteSrvCheckSql);

            // Execute the statement
            if (!sqlsrv_execute($remoteSrvCheckStmt)) {
                // Handle invalid sql execution
                var_dump(sqlsrv_errors());
                throw new Exception('Query error on remoteSrvCheckStmt');
            }

            while ($row = sqlsrv_fetch_array($remoteSrvCheckStmt, SQLSRV_FETCH_ASSOC)) {
                // Check if the ERP_DB server has been configured correctly and can be connected to.
                if ($row["SRV_NAME"] === "ERP_DB") {
                    $erpAvailable = true;
                }
            }

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
                    WHEN ISNUMERIC(RunTime) = 1 AND ISNUMERIC(TotalWeight) = 1 AND CAST(TotalWeight AS float) <> 0 THEN 
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
                    WHEN ISNUMERIC(WaterUsed) = 1 AND ISNUMERIC(TotalWeight) = 1 AND CAST(TotalWeight AS float) <> 0 THEN 
                        ROUND(CAST(WaterUsed AS float) / CAST(TotalWeight AS float), 2)
                    ELSE 0
                END AS [WaterUsedPerKg],
                ISNULL(SteamUsed, 0) AS SteamUsed,
                -- [SteamUsedPerKg],
                CASE
                    WHEN ISNUMERIC(SteamUsed) = 1 AND ISNUMERIC(TotalWeight) = 1 AND CAST(TotalWeight AS float) <> 0 THEN 
                        ROUND(CAST(SteamUsed AS float) / CAST(TotalWeight AS float), 2)
                    ELSE 0
                END AS [SteamUsedPerKg],
                ISNULL(PowerUsed, 0) AS PowerUsed,
                -- [PowerUsedPerKg],
                CASE
                    WHEN ISNUMERIC(PowerUsed) = 1 AND ISNUMERIC(TotalWeight) = 1 AND CAST(TotalWeight AS float) <> 0 THEN 
                        ROUND(CAST(PowerUsed AS float) / CAST(TotalWeight AS float), 2)
                    ELSE 0
                END AS [PowerUsedPerKg],

                [Color],
                [CreationTime],

                Dyelot,
                COALESCE(ProgramsUsed, '') AS ProgramsUsed";
            if ($erpAvailable) {
                $allDyelotSql .= ",
                B.F_PROD_ID AS FabricNo,
                B.F_TTRI_ID AS HBFabricNo";
            }
            $allDyelotSql .= "
            FROM
				Dyelots D";
            if ($erpAvailable) {
                $allDyelotSql .= "
				LEFT JOIN ERP_DB.DYEING_VHB.dbo.TDORDER3 A ON D.SheetNo = A.F_JOBCARD_ID
                LEFT JOIN ERP_DB.DYEING_VHB.dbo.TDORDER B ON A.F_DORDER_ID = B.F_DORDER_ID";
            }
            $allDyelotSql .= "
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
                        AND (
                            ";
            if (!empty($preparedInput["dateStart"])) {
                $allDyelotSql .= " DATEADD(HOUR, 7, EndTime) BETWEEN ? AND ? ";
                $params = array_merge($machines, [$preparedInput["dateStart"], $preparedInput["dateEnd"]]);
            } elseif (!empty($preparedInput["dateStart"]) && $preparedInput["dyelotSelector"] !== "%%") {
                $allDyelotSql .= " OR ";
                $params = array_merge($machines, $preparedInput);
            } elseif ($preparedInput["dyelotSelector"] !== "%%") {
                $allDyelotSql .= " SheetNo LIKE ? ";
                $params = array_merge($machines, [$preparedInput["dyelotSelector"]]);
            }
            $allDyelotSql .= "
                        )
                )
                AND StartTime IS NOT NULL
                ORDER BY
                    D.SheetNo, StartTime";

            $allDyelotStmt = sqlsrv_prepare($conn, $allDyelotSql, $params);

            // Execute the statement
            if (!sqlsrv_execute($allDyelotStmt)) {
                // Handle invalid sql execution
                var_dump(sqlsrv_errors());
                throw new Exception('Query error on allDyelotStmt');
            }

            $data = [];
            $tempSheetNo = "";
            $groupFlag = false;
            $subGroupFlag = false;
            $tempRow = [];
            $redyeCounter = 0;
            $RedyeType = "正常";
            $undefinedFabricException = false;
            $normalMaxCount = 0;
            $invalidRowFlag = true;
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
                    if (!empty($previousRow["ProgramsUsed"])) {
                        $invalidRowFlag = false;
                    }
                    $previousDyelotCount = $this->extractBetweenTwoHyphens($previousRow["Dyelot"]);
                    if (!empty($previousDyelotCount)) {
                        if ($previousDyelotCount > $normalMaxCount) {
                            $normalMaxCount = $previousDyelotCount;
                        }
                    }

                    if ($previousRow["SheetNo"] === $row["SheetNo"]) {
                        // SheetNo is the same, prepare grouped data

                        // $groupFlag as indicator of start of grouped data
                        if (!$groupFlag) {
                            // Initialize $groupedData and indicator $groupFlag
                            $groupedData = $previousRow;
                            $rawGroupedData = [];
                            $groupFlag = true;
                        }

                        $timeDiff = $row["StartTime"]->diff($previousRow["EndTime"]);

                        // Difference in hours, accumulative
                        $hoursDiff = ($timeDiff->days * 24) + $timeDiff->h;

                        if ($hoursDiff > $preparedInput["redyeInterval"]) {
                            // Redye interval exceeded, flagged as a redye data, shown as new line
                            if ($redyeCounter > 0) {
                                $RedyeType = "重修$redyeCounter";
                            } else {
                                if (!empty($normalMaxCount))
                                    $paddedMaxCount = "*" . str_pad($normalMaxCount, 2, "0", STR_PAD_LEFT);
                                else
                                    $paddedMaxCount = "";
                                $RedyeType = "正常$paddedMaxCount";
                                $normalMaxCount = 0;
                            }

                            if (!$invalidRowFlag) {
                                $this->updateRowData($data, $groupedData, $tempRow, $rawGroupedData, $RedyeType);
                                if ($redyeCounter < 10) {
                                    $redyeCounter++;
                                }
                                $RedyeType = "重修$redyeCounter";
                            }
                            $invalidRowFlag = true;

                            $rawGroupedData = [];
                            $groupedData = $row;
                        } else {
                            $groupedData["RedyeType"] = $RedyeType;
                            $tempRow["RedyeType"] = $RedyeType;

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
                        }
                    } elseif ($groupFlag) {
                        // First data deviance after a data group. Record the grouped data and reset $groupFlag
                        if (!empty($normalMaxCount) && $redyeCounter == 0) {
                            $paddedMaxCount = "*" . str_pad($normalMaxCount, 2, "0", STR_PAD_LEFT);
                            $RedyeType = "正常$paddedMaxCount";
                        }
                        $normalMaxCount = 0;
                        
                        if (!$invalidRowFlag)
                            $this->updateRowData($data, $groupedData, $tempRow, $rawGroupedData, $RedyeType);
                        $invalidRowFlag = true;

                        $redyeCounter = 0;
                        $RedyeType = "正常";
                        $groupFlag = false;
                    } else {
                        // No same SheetNo occurence, proceed as usual
                        $groupedData = $previousRow;
                        $rawGroupedData = [];

                        if (!empty($normalMaxCount) && $redyeCounter == 0) {
                            $paddedMaxCount = "*" . str_pad($normalMaxCount, 2, "0", STR_PAD_LEFT);
                        } else {
                            $paddedMaxCount = "";
                        }
                        $RedyeType = "正常$paddedMaxCount";
                        $normalMaxCount = 0;

                        if (!$invalidRowFlag)
                            $this->updateRowData($data, $groupedData, $tempRow, $rawGroupedData, $RedyeType);
                        $invalidRowFlag = true;

                        $redyeCounter = 0;
                        $RedyeType = "正常";
                    }

                    // Update $previousRow after every necessary process is done
                    $previousRow = $row;
                    $tempRow = valueFormatter($previousRow);
                }
            }

            // End of data, process the last trailing $row (recorded as $previousRow)
            if (isset($previousRow)) {
                $groupedData = $previousRow;
                if (!empty($previousRow["ProgramsUsed"])) {
                    $invalidRowFlag = false;
                }
                $previousDyelotCount = $this->extractBetweenTwoHyphens($previousRow["Dyelot"]);
                if (!empty($previousDyelotCount)) {
                    if ($previousDyelotCount > $normalMaxCount) {
                        $normalMaxCount = $previousDyelotCount;
                    }
                }
                if (!empty($normalMaxCount) && $redyeCounter == 0) {
                    $paddedMaxCount = "*" . str_pad($normalMaxCount, 2, "0", STR_PAD_LEFT);
                } else
                    $paddedMaxCount = "";
                if (!empty($paddedMaxCount))
                    $RedyeType = "正常$paddedMaxCount";

                // If $groupFlag indicator is true, the last data and the second last data's SheetNo are the same
                if (!$invalidRowFlag)
                    $this->updateRowData($data, $groupedData, $tempRow, $rawGroupedData, $RedyeType);
            }

            return json_encode($data);
        } catch (Exception $e) {
            // Handle errors appropriately
            echo 'Error: ' . $e->getMessage();
        }
    }
}
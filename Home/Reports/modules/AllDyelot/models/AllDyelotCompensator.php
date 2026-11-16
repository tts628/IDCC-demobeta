<?php
class AllDyelotModel
{
    private function updateRowData(&$data, &$groupedData, &$tempRow, &$rawGroupedData, $RedyeType)
    {
        $groupedData["RedyeType"] = $RedyeType;
        $tempRow["RedyeType"] = $RedyeType;

        // $groupedData = array_map('round2Decimal', $groupedData);
        $rawGroupedData[] = $tempRow;
        $preparedGroupedData = array_merge($groupedData, ["rawGroupedData" => $rawGroupedData]);

        // $data[] = $preparedGroupedData;
        $tempDataArr = [];
        foreach ($rawGroupedData as $rowData) {
            $tempDataArr[] = [
                "RedyeType" => $groupedData["RedyeType"],
                "SheetNo" => $groupedData["SheetNo"],
                "Dyelot" => $rowData["Dyelot"]
            ];
        }
        $data[] = $tempDataArr;
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

    public function getDataByCustomInput($machines, $conn)
    {
        try {
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
                
                DATEADD(HOUR, 7, StartTime) AS StartTime,
                DATEADD(HOUR, 7, EndTime) AS EndTime,
                Dyelot,
                COALESCE(ProgramsUsed, '') AS ProgramsUsed
            FROM
				Dyelots
            WHERE 
                SheetNo IN (
                    SELECT 
                        DISTINCT(SheetNo) AS SheetNo
                    FROM 
                        Dyelots
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
                AND StartTime IS NOT NULL
                ORDER BY
                    SheetNo, StartTime";

            $allDyelotStmt = sqlsrv_prepare($conn, $allDyelotSql, $machines);

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
                    $tempRow = $previousRow;
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

                        if ($hoursDiff > 3) {
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
                    $tempRow = $previousRow;
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
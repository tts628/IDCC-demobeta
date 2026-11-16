<?php
ini_set('error_reporting', '1');

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dateString = $_POST["date"];
    // var_dump($dateString);
    $originalDate = new DateTime($dateString);
    
    // 1. Get one month before with date "01"
    $oneMonthBefore = clone $originalDate;
    $oneMonthBefore->modify('-1 month');
    $oneMonthBefore->setDate($oneMonthBefore->format('Y'), $oneMonthBefore->format('m'), 1);
    
    // 2. Get one month after with the last date of that month
    $oneMonthAfter = clone $originalDate;
    $oneMonthAfter->modify('+1 month');
    $oneMonthAfter->setDate($oneMonthAfter->format('Y'), $oneMonthAfter->format('m'), $oneMonthAfter->format('t'));
    
    // Format the dates as strings
    $oneMonthBeforeString = $oneMonthBefore->format('Y-m-d');
    $oneMonthAfterString = $oneMonthAfter->format('Y-m-d');

    $dbMapper = new DbMapper();
    // $link = $dbMapper->getLink(['SPC']);
    $link = $dbMapper->getLink(['IDCC']);

    // $sql = "
    // WITH DateRange AS (
    //     SELECT
    //         DATEADD(DAY, number, MIN(CONVERT(DATE, Dyelots.endTime))) AS DayDate
    //     FROM
    //         master.dbo.spt_values
    //     JOIN
    //         Dyelots ON 1=1  -- Join Dyelots to access endTime column
    //     WHERE
    //         type = 'P'
    //     GROUP BY
    //         number  -- Include 'number' column in GROUP BY
    // )
    // SELECT
    //     FORMAT(DayDate, 'yyyy-MM') AS Month,
    //     FORMAT(DayDate, 'yyyy-MM-dd') AS Day,
    //     SUM(COALESCE(PowerUsed, 0)) AS Power,
    //     SUM(COALESCE(SteamUsed, 0)) AS Steam,
    //     SUM(COALESCE(WaterUsed, 0)) AS Water
    // FROM
    //     DateRange
    // LEFT JOIN
    //     Dyelots ON CONVERT(DATE, Dyelots.endTime) = DayDate
    // GROUP BY
    //     FORMAT(DayDate, 'yyyy-MM'),
    //     FORMAT(DayDate, 'yyyy-MM-dd')
    // ORDER BY
    //     FORMAT(DayDate, 'yyyy-MM'),
    //     FORMAT(DayDate, 'yyyy-MM-dd')
    // ";
    $sql = "
    	WITH DateRange AS (
        SELECT
            DATEADD(DAY, number, MIN(CONVERT(DATE, DyeEnergyUsage.endTime))) AS DayDate
        FROM
            master.dbo.spt_values
        JOIN
            DyeEnergyUsage ON 1=1  -- Join Dyelots to access endTime column
        WHERE
            type = 'P'
        GROUP BY
            number  -- Include 'number' column in GROUP BY
    )
    SELECT
        FORMAT(DayDate, 'yyyy-MM') AS Month,
        FORMAT(DayDate, 'yyyy-MM-dd') AS Day,
        SUM(COALESCE(PowerUsage, 0)) AS Power,
        SUM(COALESCE(SteamUsage, 0)) AS Steam,
        SUM(COALESCE(WaterUsage, 0)) AS Water
    FROM
        DateRange
    LEFT JOIN
        DyeEnergyUsage ON CONVERT(DATE, DyeEnergyUsage.endTime) = DayDate
    GROUP BY
        FORMAT(DayDate, 'yyyy-MM'),
        FORMAT(DayDate, 'yyyy-MM-dd')
    ORDER BY
        FORMAT(DayDate, 'yyyy-MM'),
        FORMAT(DayDate, 'yyyy-MM-dd')
    ";
    
    $query = sqlsrv_query($link, $sql);
    
    if ($query === false) {
        var_dump(sqlsrv_errors());
        die("Error executing the query: " . sqlsrv_errors());
    }
    
    $data = $monthlyData = array();
    $month = "";
    while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
        if (empty($month)) {
            $month = $row["Month"];
        }
        if ($month != $row["Month"]) {
            $month = $row["Month"];
        }
        $data["$month"]["$row[Day]"] = [
            "Power" => $row["Power"],
            "Steam" => $row["Steam"],
            "Water" => $row["Water"]
        ];
    }
    
    sqlsrv_close($link);
    
    header("Content-Type: application/json");
    echo json_encode($data);
} else {
    echo "Invalid request method.";
}
?>
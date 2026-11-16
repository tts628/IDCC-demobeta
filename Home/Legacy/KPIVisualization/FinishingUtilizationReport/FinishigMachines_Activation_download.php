<?php
date_default_timezone_set('Asia/Taipei');

ini_set('memory_limit', '5G');

//====================通用函式：找資料表 getTableName_v1()===================
function getTableName_v1($selectedOption, $selectedOption_machine_number)
{
    $selectedOption_machine_number_prefix = substr($selectedOption_machine_number, 0, 3);
    $tableName = '';
    // Debugging information
    var_dump($selectedOption);
    var_dump($selectedOption_machine_number_prefix);
    switch ($selectedOption) {
        case "定型":
            $tableName = '[FinishMachines_Stenter_Machine_' . $selectedOption_machine_number_prefix . ']';
            break;
        case "磨毛":
            $tableName = '[FinishMachines_Grinding_Machine_' . $selectedOption_machine_number_prefix . ']';
            break;
        case "刷毛":
            $tableName = '[FinishMachines_Brushing_Machine_' . $selectedOption_machine_number_prefix . ']';
            break;
        case "剪毛":
            $tableName = '[FinishMachines_Shearing_Machine_' . $selectedOption_machine_number_prefix . ']';
            break;
        case "解捻":
            $tableName = '[FinishMachines_Untwisting_Machine_D_' . $selectedOption_machine_number_prefix . ']';
            break;
        case "柔軟":
            $tableName = '[FinishMachines_Softening_Machine_D_' . $selectedOption_machine_number_prefix . ']';
            break;
        default:
            // 设置一个默认的表名
            $tableName = '[FinishMachines_Stenter_Machine_T01]';
            break;
    }
    return $tableName; // 返回表名
}
//====================通用函式：找資料表 getTableName_v2()===================
function getTableName_v2($selectedOption, $selectedOption_machine_number)
{
    $selectedOption_machine_number_prefix = substr($selectedOption_machine_number, 0, 3);
    if ($selectedOption == '定型') {
        return '[FinishMachines_Stenter_Machine_' . $selectedOption_machine_number_prefix . ']';
    } elseif ($selectedOption == '磨毛') {
        return '[FinishMachines_Grinding_Machine_' . $selectedOption_machine_number_prefix . ']';
    } elseif ($selectedOption == '刷毛') {
        return '[FinishMachines_Brushing_Machine_' . $selectedOption_machine_number_prefix . ']';
    } elseif ($selectedOption == '剪毛') {
        return '[FinishMachines_Shearing_Machine_' . $selectedOption_machine_number_prefix . ']';
    } elseif ($selectedOption == '解捻') {
        return '[FinishMachines_Untwisting_Machine_D_' . $selectedOption_machine_number_prefix . ']';
    } elseif ($selectedOption == '柔軟') {
        return '[FinishMachines_Softening_Machine_D_' . $selectedOption_machine_number_prefix . ']';
    } else {
        // 如果未匹配到任何选项，返回一个默认的表名或者触发一个错误
        return '[FinishMachines_Stenter_Machine_T01]';
    }
}
//====================通用函式：找資料表 getTableName_v3()===================
function getTableName_v3($selectedOption, $selectedOption_machine_number)
{
    $selectedOption_machine_number_prefix = substr($selectedOption_machine_number, 0, 3);

    // 表名映射
    $tableNames = [
        '定型' => '[FinishMachines_Stenter_Machine_' . $selectedOption_machine_number_prefix . ']',
        '磨毛' => '[FinishMachines_Grinding_Machine_' . $selectedOption_machine_number_prefix . ']',
        '刷毛' => '[FinishMachines_Brushing_Machine_' . $selectedOption_machine_number_prefix . ']',
        '剪毛' => '[FinishMachines_Shearing_Machine_' . $selectedOption_machine_number_prefix . ']',
        '解捻' => '[FinishMachines_Untwisting_Machine_D_' . $selectedOption_machine_number_prefix . ']',
        '柔軟' => '[FinishMachines_Softening_Machine_D_' . $selectedOption_machine_number_prefix . ']',
    ];

    // 检查是否存在对应的表名，如果不存在则使用默认表名
    if (isset($tableNames[$selectedOption])) {
        return $tableNames[$selectedOption];
    } else {
        return '[FinishMachines_Stenter_Machine_T01]'; // 设置默认表名
    }
}
//====================通用函式：返回SQL SQLQuery()===================
function SQLQuery($selectedOption, $table_name, $selectedOption_machine_number, $today_start, $today_end)
{
    $selectedOption_machine_number_prefix = substr($selectedOption_machine_number, 0, 3);
    //echo ($selectedOption_machine_number_prefix);
    //echo ($selectedOption_machine_number);
    //echo ($table_name);
    $sql = '';
    switch ($selectedOption) {
        case '定型':
            $sql = "SELECT 
                  [Machine],[Dyelot],
                  FORMAT(Max(LogTimeStamp), 'yyyy-MM-dd') AS 'FormattedUploadTime',
                  DATEDIFF(MINUTE, MIN(LogTimeStamp), MAX(LogTimeStamp)) AS 'run_time'FROM 
                  $table_name
                  WHERE LogTimeStamp > '$today_start' AND LogTimeStamp < '$today_end'
                  AND Dyelot NOT IN (
                  SELECT Dyelot
                  FROM $table_name
                  WHERE LogTimeStamp > '$today_start' AND LogTimeStamp < '$today_end'
                  GROUP BY Dyelot
                  HAVING COUNT(*) % 2 = 1 OR COUNT(*) > 2) 
                  GROUP BY [Machine], [Dyelot]
                  ORDER BY FormattedUploadTime DESC;";
            break;
        case '磨毛':
            $sql = "SELECT
                  FORMAT(CONVERT(DATETIME, SUBSTRING(IoDataUpdated, 1, 23), 121), 'yyyy-MM-dd') AS 'FormattedUploadTime',
                  SUM(CASE WHEN TRY_CONVERT(float, [VAL_VELO_MT]) > 5 THEN 1 ELSE 0 END) AS run_time
                  FROM (
                  SELECT 
                      *
                  FROM 
                      (
                          SELECT 
                              *,
                              ROW_NUMBER() OVER (
                                  PARTITION BY FORMAT(CONVERT(DATETIME, SUBSTRING(IoDataUpdated, 1, 23), 121), 'yyyy-MM-dd HH:mm')
                                  ORDER BY CONVERT(DATETIME, SUBSTRING(IoDataUpdated, 1, 23), 121) DESC
                              ) AS RowNum
                          FROM 
                              $table_name
                  			WHERE CONVERT(DATETIME, SUBSTRING(IoDataUpdated, 1, 23), 121) > '$today_start' AND CONVERT(DATETIME, SUBSTRING(IoDataUpdated, 1, 23), 121) < '$today_end'
                      ) AS SubQuery
                  WHERE 
                      RowNum = 1
                      )minute_only_1_per
                  WHERE CONVERT(DATETIME, SUBSTRING(IoDataUpdated, 1, 23), 121) > '$today_start' AND CONVERT(DATETIME, SUBSTRING(IoDataUpdated, 1, 23), 121) < '$today_end'
                  GROUP BY FORMAT(CONVERT(DATETIME, SUBSTRING(IoDataUpdated, 1, 23), 121), 'yyyy-MM-dd')
                  ORDER BY FORMAT(CONVERT(DATETIME, SUBSTRING(IoDataUpdated, 1, 23), 121), 'yyyy-MM-dd') ASC";
            break;
        case '刷毛':
            $sql = "SELECT
                  FORMAT([UploadTime], 'yyyy-MM-dd') AS 'FormattedUploadTime',
                  SUM(CASE WHEN TRY_CONVERT(float, [VAL_VELO_MT]) > 5 THEN 1 ELSE 0 END) AS run_time
                  FROM (
                  SELECT 
                      *
                  FROM 
                      (
                          SELECT 
                              *,
                              ROW_NUMBER() OVER (
                                  PARTITION BY FORMAT(CONVERT(DATETIME, SUBSTRING(IoDataUpdated, 1, 23), 121), 'yyyy-MM-dd HH:mm')
                                  ORDER BY CONVERT(DATETIME, SUBSTRING(IoDataUpdated, 1, 23), 121) DESC
                              ) AS RowNum
                          FROM 
                              $table_name
                  			WHERE CONVERT(DATETIME, SUBSTRING(IoDataUpdated, 1, 23), 121) > '$today_start' AND CONVERT(DATETIME, SUBSTRING(IoDataUpdated, 1, 23), 121) < '$today_end'
                      ) AS SubQuery
                  WHERE 
                      RowNum = 1
                      )minute_only_1_per
                  WHERE [UploadTime] > '$today_start' AND [UploadTime] < '$today_end'
                  GROUP BY FORMAT([UploadTime], 'yyyy-MM-dd')
                  ORDER BY FORMAT([UploadTime], 'yyyy-MM-dd') ASC";
            break;
        case '剪毛':
            $sql = "SELECT 
                  FORMAT([UploadTime], 'yyyy-MM-dd') AS 'FormattedUploadTime',
                  SUM(CASE WHEN TRY_CONVERT(float, [RPM_SHEARING]) > 5 THEN 1 ELSE 0 END) AS run_time
                  FROM (
                  SELECT 
                      *
                  FROM 
                      (
                          SELECT 
                              *,
                              ROW_NUMBER() OVER (
                                  PARTITION BY FORMAT(CONVERT(DATETIME, SUBSTRING(IoDataUpdated, 1, 23), 121), 'yyyy-MM-dd HH:mm')
                                  ORDER BY CONVERT(DATETIME, SUBSTRING(IoDataUpdated, 1, 23), 121) DESC
                              ) AS RowNum
                          FROM 
                              $table_name
                  			WHERE CONVERT(DATETIME, SUBSTRING(IoDataUpdated, 1, 23), 121) > '$today_start' AND CONVERT(DATETIME, SUBSTRING(IoDataUpdated, 1, 23), 121) < '$today_end'
                      ) AS SubQuery
                  WHERE 
                      RowNum = 1
                      )minute_only_1_per
                  WHERE [UploadTime] > '$today_start' AND [UploadTime] < '$today_end'
                  GROUP BY FORMAT([UploadTime], 'yyyy-MM-dd')
                  ORDER BY FORMAT([UploadTime], 'yyyy-MM-dd') ASC";
            break;
        case '解捻':
            $sql = "SELECT  
                  FORMAT([TIME], 'yyyy-MM-dd') AS 'FormattedUploadTime'
                  ,SUM(CASE WHEN TRY_CONVERT(float, [D7010]) > 5 THEN 1 ELSE 0 END) AS run_time
                  FROM $table_name
                  WHERE [TIME] > '$today_start' AND [TIME] < '$today_end'
                  GROUP BY FORMAT([TIME], 'yyyy-MM-dd')
                  order by FORMAT([TIME], 'yyyy-MM-dd') ASC";
            break;
        case '柔軟':
            $sql = "SELECT  
                  FORMAT([TIME], 'yyyy-MM-dd') AS 'FormattedUploadTime'
                  ,SUM(CASE WHEN TRY_CONVERT(float, [D7010]) > 5 THEN 1 ELSE 0 END) AS run_time
                  FROM $table_name
                  WHERE [TIME] > '$today_start' AND [TIME] < '$today_end'
                  GROUP BY FORMAT([TIME], 'yyyy-MM-dd')
                  order by FORMAT([TIME], 'yyyy-MM-dd') ASC";
            break;
        default:
            $sql = "SELECT 
                  [Machine],
                  [Dyelot],
                  FORMAT(Max(LogTimeStamp), 'yyyy-MM-dd') AS 'FormattedUploadTime',
                  DATEDIFF(MINUTE, MIN(LogTimeStamp), MAX(LogTimeStamp)) AS 'run_time'
                  FROM $table_name WHERE  
                  LogTimeStamp > '$today_start' AND LogTimeStamp < '$today_end'
                  AND Dyelot NOT IN (
                      SELECT Dyelot
                      FROM $table_name
                      WHERE LogTimeStamp > '$today_start' AND LogTimeStamp < '$today_end'
                      GROUP BY Dyelot
                      HAVING COUNT(*) % 2 = 1 OR COUNT(*) > 2
                  ) 
                  GROUP BY  
                      [Machine], [Dyelot]
                  ORDER BY 
                      FormattedUploadTime DESC;";
    }
    //echo "Generated SQL: " . $sql . "\n";

    return $sql;
}
function writeToCSV($stmt, $output)
{
    while ($row_machine = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $date = $row_machine['FormattedUploadTime'];
        $run_time = (float) $row_machine['run_time'];

        $daily_runtimes[$date] = isset($daily_runtimes[$date]) ? $daily_runtimes[$date] + $run_time : $run_time;
    }

    foreach ($daily_runtimes as $date => $total_runtime) {
        $activation = number_format(($total_runtime / (24 * 60)) * 100, 2);
        $runtime = number_format($total_runtime / 60, 2);
        fputcsv($output, [$date, $runtime, $activation]);
    }
}
//====================通用函式：寫入CSV writeToCSV_V2()===================
function writeToCSV_V2($stmt, $selectedOption_machine_number, $startDateTime, $endDateTime)
{
    // 设置为下载
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment;filename=' . $selectedOption_machine_number . '稼動率' . substr(str_replace('_', ':', $startDateTime), 0, 16) . '~' . substr(str_replace('_', ':', $endDateTime), 0, 16) . '.csv');
    $output = fopen('php://output', 'w');

    // 清理暫存區
    ob_clean();

    // 写入 BOM
    fwrite($output, "\xEF\xBB\xBF");
    fputcsv($output, ['日期', '實際運作時間_小時', '稼動率%']);

    $daily_runtimes = [];
    while ($row_machine = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $date = $row_machine['FormattedUploadTime'];
        $run_time = (float) $row_machine['run_time'];

        $daily_runtimes[$date] = isset($daily_runtimes[$date]) ? $daily_runtimes[$date] + $run_time : $run_time;
    }

    foreach ($daily_runtimes as $date => $total_runtime) {
        $activation = number_format(($total_runtime / (24 * 60)) * 100, 2);
        $runtime = number_format($total_runtime / 60, 2);
        fputcsv($output, [$date, $runtime, $activation]);
    }

    // 关闭输出
    fclose($output);
}

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

// 处理数据库连接错误
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

//GET數據
$selectedOption_Report = $_GET['selectedOption_Report'];
$selectedOption = $_GET['selectedOption'];
$selectedOption_machine_number = $_GET['selectedOption_machine_number'];
$startDateTime = $_GET['startDateTime'];
$endDateTime = $_GET['endDateTime'];
$today_start = !empty($startDateTime) ? (new DateTime($startDateTime))->format("Y-m-d H:i:s") : "";
$today_end = !empty($endDateTime) ? (new DateTime($endDateTime))->format("Y-m-d H:i:s") : "";

// 调试输出参数
//var_dump($selectedOption);
//var_dump($selectedOption_machine_number);
//var_dump($startDateTime);
//var_dump($endDateTime);


$table_name = getTableName_v3($selectedOption, $selectedOption_machine_number);

if (empty($table_name)) {
    die("無匹配的資料表");
}
//var_dump($table_name);
$sqlquery = SQLQuery($selectedOption, $table_name, $selectedOption_machine_number, $today_start, $today_end);
//var_dump($sqlquery);
//var_dump($table_name);


// sql查詢
$stmt = sqlsrv_query($conn, $sqlquery);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
//var_dump($sqlquery);

//====================執行下載CSV========================
writeToCSV_V2($stmt, $selectedOption_machine_number, $startDateTime, $endDateTime);
sqlsrv_close($conn);
?>
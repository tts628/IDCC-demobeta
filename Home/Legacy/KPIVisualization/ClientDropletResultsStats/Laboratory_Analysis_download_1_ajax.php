<?php
// <a href="#" id="downloadLink1" style="color: #00bcd4;">打色員與色號打杯數下載</a><br>
// <a href="#" id="downloadLink2" style="color: #00bcd4;">打色員打杯數下載</a><br>
// <a href="#" id="downloadLink3" style="color: #00bcd4;">色號打杯數下載</a><br>
// <a href="#" id="downloadLink4" style="color: #00bcd4;">品名用量下載</a>
// <a href="#" id="downloadLink4" style="color: #00bcd4;">機台打杯數下載</a>

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

// $MMMM=$_GET['month'];
$startDateTime = $_GET['startDateTime'] . " 00:00:00:000";
$endDateTime = $_GET['endDateTime'] . " 23:59:59:000";
// $work_no_Input = $_GET['work_no_Input'];
// $FabricName_Input = $_GET['FabricName_Input'];
$machine_title = explode(",", $_GET['machine_checkbox']);
// var_dump($machine_title);
for ($n = 0; $n < count($machine_title); $n++) {
    $machine_select .= ",CAST(sum(  CASE   WHEN Machine LIKE '" . $machine_title[$n] . "' THEN ABS(DATEDIFF(minute, [StartTime], [EndTime])) ELSE null END )/14.4/count(distinct (CONVERT(DATE, [EndTime]))) AS DECIMAL(10, 2)) AS '" . $machine_title[$n] . "'";
}

$batch_title = explode(",", $_GET['batch_checkbox']);
for ($n = 0; $n < count($batch_title); $n++) {
    // $Batch_select .= ",CAST(sum(  CASE   WHEN Batch LIKE '" . $batch_title[$n] . "' THEN ABS(DATEDIFF(minute, [StartTime], [EndTime])) ELSE null END )/14.4/count(distinct (CONVERT(DATE, [EndTime]))) AS DECIMAL(10, 2)) AS '" . $batch_title[$n] . "'";
    $Batch_select .= "";
}


$time_unit = $_GET['time_unit'];

ini_set('memory_limit', '5G');

// 檢查連接是否成功
if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}


// 設定為下載
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment;filename=染機/缸號稼動率' . substr(str_replace('_', ':', $startDateTime), 0, 16) . '~' . substr(str_replace('_', ':', $endDateTime), 0, 16) . '.csv');

// 準備 SQL 查詢
/*SELECT
日

,sum(  CASE   WHEN Machine LIKE '%H01%' THEN ABS(DATEDIFF(minute, [StartTime], [EndTime])) ELSE null END ) AS 'H01'

,sum(  CASE   WHEN Machine LIKE '%H02%' THEN ABS(DATEDIFF(minute, [StartTime], [EndTime])) ELSE null END ) AS 'H02'

,sum(  CASE   WHEN Machine LIKE '%H32%' THEN ABS(DATEDIFF(minute, [StartTime], [EndTime])) ELSE null END ) AS 'H32'

,sum(  CASE   WHEN Batch  LIKE 'C9-6' THEN ABS(DATEDIFF(minute, [StartTime], [EndTime])) ELSE null END ) AS 'C9-6'
,sum(  CASE   WHEN Batch  LIKE 'C9-6' THEN ABS(DATEDIFF(minute, [StartTime], [EndTime])) ELSE null END ) AS 'B1-14'
,sum(  CASE   WHEN Batch  LIKE 'C9-6' THEN ABS(DATEDIFF(minute, [StartTime], [EndTime])) ELSE null END ) AS 'B1-15'

FROM
(
SELECT 
    Machine,Dyelot,Batch,
    StartTime
  ,EndTime
  ,CONVERT(DATE, [EndTime]) as '日'
  ,CAST(YEAR([EndTime]) AS VARCHAR(4)) + '-' + CAST(DATEPART(ISO_WEEK, [EndTime]) AS VARCHAR(2) )+ '周' as '周'
  ,CAST(YEAR([EndTime]) AS VARCHAR(4)) + '-' + CAST(DATEPART(MONTH, [EndTime]) AS VARCHAR(2) )+ '月' as '月'

  FROM
(
SELECT Machine,Dyelot,Batch,StartTime,EndTime FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 0

UNION ALL

--橫跨一天
SELECT Machine,Dyelot,Batch,StartTime,CONVERT(DATETIME, CONVERT(VARCHAR(10), StartTime, 23) + ' 23:59:59', 120) as 'EndTime' FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 1
UNION ALL
SELECT Machine,Dyelot,Batch,CONVERT(DATETIME, CONVERT(VARCHAR(10), EndTime, 23) + ' 00:00:00', 120) as 'StartTime',EndTime FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 1
--橫跨一天完
UNION ALL
--橫跨兩天
SELECT Machine,Dyelot,Batch,StartTime,CONVERT(DATETIME, CONVERT(VARCHAR(10), StartTime, 23) + ' 23:59:59', 120) as 'EndTime' FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 2
UNION ALL
SELECT Machine,Dyelot,Batch,CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 1, StartTime), 23) + ' 00:00:00', 120) as 'StartTime',CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 1, StartTime), 23) + ' 23:59:59', 120) as 'EndTime' FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 2
UNION ALL
SELECT Machine,Dyelot,Batch,CONVERT(DATETIME, CONVERT(VARCHAR(10), EndTime, 23) + ' 00:00:00', 120) as 'StartTime',EndTime FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 2
--橫跨兩天完

UNION ALL

--橫跨三天
SELECT Machine,Dyelot,Batch,StartTime,CONVERT(DATETIME, CONVERT(VARCHAR(10), StartTime, 23) + ' 23:59:59', 120) as 'EndTime' FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 3
UNION ALL
SELECT Machine,Dyelot,Batch,CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 1, StartTime), 23) + ' 00:00:00', 120) as 'StartTime',CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 1, StartTime), 23) + ' 23:59:59', 120) as 'EndTime' FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 3
UNION ALL
SELECT Machine,Dyelot,Batch,CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 2, StartTime), 23) + ' 00:00:00', 120) as 'StartTime',CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 2, StartTime), 23) + ' 23:59:59', 120) as 'EndTime' FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 3
UNION ALL
SELECT Machine,Dyelot,Batch,CONVERT(DATETIME, CONVERT(VARCHAR(10), EndTime, 23) + ' 00:00:00', 120) as 'StartTime',EndTime FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 3
--橫跨三天完
UNION ALL
--橫跨四天
SELECT Machine,Dyelot,Batch,StartTime,CONVERT(DATETIME, CONVERT(VARCHAR(10), StartTime, 23) + ' 23:59:59', 120) as 'EndTime' FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 4
UNION ALL
SELECT Machine,Dyelot,Batch,CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 1, StartTime), 23) + ' 00:00:00', 120) as 'StartTime',CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 1, StartTime), 23) + ' 23:59:59', 120) as 'EndTime' FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 4
UNION ALL
SELECT Machine,Dyelot,Batch,CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 2, StartTime), 23) + ' 00:00:00', 120) as 'StartTime',CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 2, StartTime), 23) + ' 23:59:59', 120) as 'EndTime' FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 4
UNION ALL
SELECT Machine,Dyelot,Batch,CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 3, StartTime), 23) + ' 00:00:00', 120) as 'StartTime',CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 3, StartTime), 23) + ' 23:59:59', 120) as 'EndTime' FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 4
UNION ALL
SELECT Machine,Dyelot,Batch,CONVERT(DATETIME, CONVERT(VARCHAR(10), EndTime, 23) + ' 00:00:00', 120) as 'StartTime',EndTime FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 4
--橫跨四天完

)all_divide
WHERE StartTime >= '2024-02-01 00:00:00.000'
AND EndTime <= '2024-04-07 00:00:00.000'


)TAB
group by 日
order by 日








*/


$sql = "SELECT
$time_unit

$machine_select
$Batch_select



FROM
(
SELECT 
DAY(EOMONTH([EndTime])) as '天數',
    Machine,Dyelot,Batch,
    StartTime
	,EndTime
	,CONVERT(DATE, [EndTime]) as '日'
	,CAST(YEAR([EndTime]) AS VARCHAR(4)) + '-' + CAST(DATEPART(ISO_WEEK, [EndTime]) AS VARCHAR(2) )+ '周' as '周'
	,CAST(YEAR([EndTime]) AS VARCHAR(4)) + '-' + CAST(DATEPART(MONTH, [EndTime]) AS VARCHAR(2) )+ '月' as '月'

  FROM
(
SELECT Machine,Dyelot,Batch,StartTime,EndTime FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 0

UNION ALL

--橫跨一天
SELECT Machine,Dyelot,Batch,StartTime,CONVERT(DATETIME, CONVERT(VARCHAR(10), StartTime, 23) + ' 23:59:59', 120) as 'EndTime' FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 1
UNION ALL
SELECT Machine,Dyelot,Batch,CONVERT(DATETIME, CONVERT(VARCHAR(10), EndTime, 23) + ' 00:00:00', 120) as 'StartTime',EndTime FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 1
--橫跨一天完
UNION ALL
--橫跨兩天
SELECT Machine,Dyelot,Batch,StartTime,CONVERT(DATETIME, CONVERT(VARCHAR(10), StartTime, 23) + ' 23:59:59', 120) as 'EndTime' FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 2
UNION ALL
SELECT Machine,Dyelot,Batch,CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 1, StartTime), 23) + ' 00:00:00', 120) as 'StartTime',CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 1, StartTime), 23) + ' 23:59:59', 120) as 'EndTime' FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 2
UNION ALL
SELECT Machine,Dyelot,Batch,CONVERT(DATETIME, CONVERT(VARCHAR(10), EndTime, 23) + ' 00:00:00', 120) as 'StartTime',EndTime FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 2
--橫跨兩天完

UNION ALL

--橫跨三天
SELECT Machine,Dyelot,Batch,StartTime,CONVERT(DATETIME, CONVERT(VARCHAR(10), StartTime, 23) + ' 23:59:59', 120) as 'EndTime' FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 3
UNION ALL
SELECT Machine,Dyelot,Batch,CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 1, StartTime), 23) + ' 00:00:00', 120) as 'StartTime',CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 1, StartTime), 23) + ' 23:59:59', 120) as 'EndTime' FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 3
UNION ALL
SELECT Machine,Dyelot,Batch,CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 2, StartTime), 23) + ' 00:00:00', 120) as 'StartTime',CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 2, StartTime), 23) + ' 23:59:59', 120) as 'EndTime' FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 3
UNION ALL
SELECT Machine,Dyelot,Batch,CONVERT(DATETIME, CONVERT(VARCHAR(10), EndTime, 23) + ' 00:00:00', 120) as 'StartTime',EndTime FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 3
--橫跨三天完
UNION ALL
--橫跨四天
SELECT Machine,Dyelot,Batch,StartTime,CONVERT(DATETIME, CONVERT(VARCHAR(10), StartTime, 23) + ' 23:59:59', 120) as 'EndTime' FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 4
UNION ALL
SELECT Machine,Dyelot,Batch,CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 1, StartTime), 23) + ' 00:00:00', 120) as 'StartTime',CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 1, StartTime), 23) + ' 23:59:59', 120) as 'EndTime' FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 4
UNION ALL
SELECT Machine,Dyelot,Batch,CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 2, StartTime), 23) + ' 00:00:00', 120) as 'StartTime',CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 2, StartTime), 23) + ' 23:59:59', 120) as 'EndTime' FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 4
UNION ALL
SELECT Machine,Dyelot,Batch,CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 3, StartTime), 23) + ' 00:00:00', 120) as 'StartTime',CONVERT(DATETIME, CONVERT(VARCHAR(10), DATEADD(day, 3, StartTime), 23) + ' 23:59:59', 120) as 'EndTime' FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 4
UNION ALL
SELECT Machine,Dyelot,Batch,CONVERT(DATETIME, CONVERT(VARCHAR(10), EndTime, 23) + ' 00:00:00', 120) as 'StartTime',EndTime FROM  DyeProduction  WHERE DATEDIFF(day, CAST(StartTime AS DATE), CAST(EndTime AS DATE)) = 4
--橫跨四天完

)all_divide
WHERE StartTime >= '$startDateTime'
AND EndTime <= '$endDateTime'


)TAB
group by $time_unit
order by $time_unit





";
// var_dump($sql);
// 執行 SQL 查詢
$result = sqlsrv_query($conn, $sql);
// $stmt = sqlsrv_query($conn, $sql);

// while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//   var_dump($row);

// }
// 設定 header 以告訴瀏覽器這是一個 CSV 檔案
// var_dump($sql);
// var_dump($select);

// 打開 PHP 的輸出流，將數據寫入 CSV 文件
$output = fopen('php://output', 'w');
// 清空输出缓冲区
ob_clean();

// 写入 BOM
fwrite($output, "\xEF\xBB\xBF");

// 寫入 CSV 表頭
$column_names = sqlsrv_field_metadata($result);
$column_names = array_map(function ($column) {
    return $column['Name'];
}, $column_names);
fputcsv($output, $column_names);

// 寫入 CSV 數據
while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

    fputcsv($output, $row);
}

// 關閉資料庫連接
sqlsrv_close($conn);

// 關閉 CSV 文件流
fclose($output);
?>
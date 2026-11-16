<?php
      // <a href="#" id="downloadLink1" style="color: #00bcd4;">打色員與色號打杯數下載</a><br>
      // <a href="#" id="downloadLink2" style="color: #00bcd4;">打色員打杯數下載</a><br>
      // <a href="#" id="downloadLink3" style="color: #00bcd4;">色號打杯數下載</a><br>
      // <a href="#" id="downloadLink4" style="color: #00bcd4;">品名用量下載</a>
      // <a href="#" id="downloadLink4" style="color: #00bcd4;">機台打杯數下載</a>


// require ("connect.php");
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['SPC', "ReturnDatesAsStrings"]);

// $MMMM=$_GET['month'];
$startDateTime = $_GET['startDateTime'] . " 00:00:00:000";
$endDateTime = $_GET['endDateTime'] . " 23:59:59:000";
// $work_no_Input = $_GET['work_no_Input'];
// $FabricName_Input = $_GET['FabricName_Input'];

if ($work_no_Input == 'ALL' || $work_no_Input == '')
  $work_no_select = '';
else
  $work_no_select = "AND Dyelot ='" . $work_no_Input . "'";


if ($FabricName_Input == 'ALL' || $FabricName_Input == '')
  $FabricName_select = '';
else
  $FabricName_select = "AND FabricName ='" . $FabricName_Input . "'";

  $time_unit = $_GET['time_unit'];

ini_set('memory_limit', '5G');

// 檢查連接是否成功
if (!$conn) {
  die (print_r(sqlsrv_errors(), true));
}


// 設定為下載
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment;filename=染助劑耗時用量'.substr(str_replace('_', ':', $startDateTime), 0, 16).'~'.substr(str_replace('_', ':', $endDateTime), 0, 16).'.csv');

// 準備 SQL 查詢
$sql = "SELECT
$time_unit
,round( sum(  CASE   WHEN ProductType = '染料' THEN TotalDispenseGrams ELSE null END ) /1000,2) AS '染料TotalDispenseGrams'
,avg(  CASE   WHEN ProductType = '染料' THEN AvgDispenseSeconds ELSE null END ) AS '染料AvgDispenseSeconds'

,round( sum(  CASE   WHEN ProductType = '助劑' THEN TotalDispenseGrams ELSE null END ) /1000,2) AS '助劑TotalDispenseGrams'
,avg(  CASE   WHEN ProductType = '助劑' THEN AvgDispenseSeconds ELSE null END ) AS '助劑AvgDispenseSeconds'

,round( sum(  CASE   WHEN ProductType = '粉體助劑' THEN TotalDispenseGrams ELSE null END ) /1000,2) AS '粉體助劑TotalDispenseGrams'
,avg(  CASE   WHEN ProductType = '粉體助劑' THEN AvgDispenseSeconds ELSE null END ) AS '粉體助劑AvgDispenseSeconds'
FROM
(
  SELECT 
    CASE 
        WHEN [ProductType] = 1 THEN '染料'
        WHEN [ProductType] = 2 THEN '助劑'
        WHEN [ProductType] = 3 THEN '粉體助劑'
        ELSE '其他'
    END AS ProductType,
    (DispenseGrams) AS TotalDispenseGrams
	,(ABS(DATEDIFF(minute, [DispenseStartTime], [DispenseTime]))) AS AvgDispenseSeconds

	,CONVERT(DATE, [DispenseEndTime]) as '日'
	,CAST(YEAR([DispenseTime]) AS VARCHAR(4)) + '-' + CAST(DATEPART(ISO_WEEK, [DispenseTime]) AS VARCHAR(2) )+ '周' as '周'
	,CAST(YEAR([DispenseTime]) AS VARCHAR(4)) + '-' + CAST(DATEPART(MONTH, [DispenseTime]) AS VARCHAR(2) )+ '月' as '月'

  --FROM DispenserProduction
  FROM [DyelotsBulkedRecipe]
WHERE [DispenseTime] >= '$startDateTime'
AND [DispenseTime] <=  '$endDateTime'

)TAB
group by $time_unit
order by $time_unit






";

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
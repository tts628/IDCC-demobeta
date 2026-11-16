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
// $startDateTime = $_GET['startDateTime'] . " 00:00:00:000";
// $endDateTime = $_GET['endDateTime'] . " 23:59:59:000";
$startDateTime = $_GET['startDateTime'];
$endDateTime = $_GET['endDateTime'];
// $work_no_Input = $_GET['work_no_Input'];
// $FabricName_Input = $_GET['FabricName_Input'];
$machine_title = explode(",", $_GET['machine_checkbox']);
// var_dump($machine_title);
for ($n = 0; $n < count($machine_title); $n++) {
  // $machine_select .= ",CAST(sum(  CASE   WHEN Machine LIKE '" . $machine_title[$n] . "' THEN ABS(DATEDIFF(minute, [StartTime], [EndTime])) ELSE null END )/14.4/count(distinct (CONVERT(DATE, [EndTime]))) AS DECIMAL(10, 2)) AS '" . $machine_title[$n] . "'";
  $machine_select .= "	,CAST(sum(  CASE  WHEN Machine LIKE '" . $machine_title[$n] . "' THEN Capacity ELSE null END ) AS DECIMAL(10, 2) ) AS '" . $machine_title[$n] . "'";
if($machine_title[$n]=='定型-T05'){
  $machine_select .="	,CAST(sum(  CASE  WHEN Machine LIKE '定型-T01' or Machine LIKE '定型-T03' or Machine LIKE '定型-T05' THEN Capacity ELSE null END ) AS DECIMAL(10, 2) ) AS '定型機總產量'";
}
if($machine_title[$n]=='染房'){
  $machine_select .=",CAST(sum(  CASE  WHEN Machine LIKE '染房' THEN Rework ELSE null END ) AS DECIMAL(10, 0) ) AS '染房重修'";
	$machine_select .=",CAST(avg(  CASE  WHEN Machine LIKE '染房' THEN ReworkProportion ELSE null END ) AS DECIMAL(10, 2) ) AS '染房比率'";
}
}

$batch_title = explode(",", $_GET['batch_checkbox']);
for ($n = 0; $n < count($batch_title); $n++) {
  $Batch_select .= ",CAST(sum(  CASE   WHEN Batch LIKE '" . $batch_title[$n] . "' THEN ABS(DATEDIFF(minute, [StartTime], [EndTime])) ELSE null END )/14.4/count(distinct (CONVERT(DATE, [EndTime]))) AS DECIMAL(10, 2)) AS '" . $batch_title[$n] . "'";
}


$time_unit = $_GET['time_unit'];

ini_set('memory_limit', '5G');

// 檢查連接是否成功
if (!$conn) {
  die(print_r(sqlsrv_errors(), true));
}


// 設定為下載
header('Content-Type: text/csv; charset=utf-8');
// header('Content-Disposition: attachment;filename=D02扣帳染助劑統計' . substr(str_replace('_', ':', $startDateTime), 0, 16) . '~' . substr(str_replace('_', ':', $endDateTime), 0, 16) . '.csv');
header('Content-Disposition: attachment;filename=D02扣帳染助劑統計' .  $_GET['endDateTime']. '.csv');

// 準備 SQL 查詢
/*SELECT
日
,CAST(sum(  CASE  WHEN Machine LIKE '製程' THEN Capacity ELSE null END ) AS DECIMAL(10, 2) ) AS '製程'
,CAST(sum(  CASE  WHEN Machine LIKE '染房' THEN Capacity ELSE null END ) AS DECIMAL(10, 2) ) AS '染房'
	,CAST(sum(  CASE  WHEN Machine LIKE '染房' THEN Rework　 ELSE null END ) AS DECIMAL(10, 0) ) AS '染房重修'
	,CAST(avg(  CASE  WHEN Machine LIKE '染房' THEN ReworkProportion　 ELSE null END ) AS DECIMAL(10, 2) ) AS '染房比率'
	,CAST(sum(  CASE  WHEN Machine LIKE '定型-T01' or Machine LIKE '定型-T03' or Machine LIKE '定型-T05' THEN Capacity　 ELSE null END ) AS DECIMAL(10, 2) ) AS '定型機總產量'
FROM
(
SELECT  [ID]
      ,[Day]
      ,[Machine]
      ,CAST([Capacity] AS float)　as 'Capacity'
		  ,CAST([Rework] AS float)　as 'Rework'
		  ,CAST(ReworkProportion AS float)　as 'ReworkProportion'
      ,[Worker]
      ,[InsertTime]
      ,[Remark]
      ,[Deleted]
	  ,CONVERT(DATE, [Day]) as '日'
	,CAST(YEAR([Day]) AS VARCHAR(4)) + '-' + CAST(DATEPART(ISO_WEEK, [Day]) AS VARCHAR(2) )+ '周' as '周'
	,CAST(YEAR([Day]) AS VARCHAR(4)) + '-' + CAST(DATEPART(MONTH, [Day]) AS VARCHAR(2) )+ '月' as '月'
  FROM [DyeCapacityReport]

  WHERE [Day] >= '2024-04-05 00:00:00:000'
AND [Day] <= '2024-04-15 23:59:59:000'


)TAB
group by 日
order by 日








*/


$sql = "SELECT  [單號]
,[序號]
,[日期]
,[染助劑代碼]
,[染助劑名稱]
,[數量]
,[單位]
,[倉位代碼]
,[倉位名稱]
,[類別]
FROM [D02]
where 日期 = '$endDateTime'




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
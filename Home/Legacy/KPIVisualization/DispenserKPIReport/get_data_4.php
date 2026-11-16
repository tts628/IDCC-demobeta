<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

$today_date = date('Y-m-d');
//$today_date = '2020-10-24';

$today_start = date("Y-m-d", strtotime("-1 days")). ' 00:00:00.000';
//$today_start = date("Y-m-d H:i:s", strtotime("-24 hours"));

$today_start_minus = strtotime($today_start); //今天開始


$today_end = date("Y-m-d", strtotime("-1 days")). ' 23:59:59.999';
//$today_end = date("Y-m-d H:i:s");

$today_end_minus = strtotime($today_end);//今天結束


//echo json_encode($today_start);
//echo json_encode($today_end);

// var_dump($today_end);
// var_dump($today_start);
// $sql = "select * from DyeProduction where  EndTime < '$today_end' and EndTime > '$today_start' and CONVERT(FLOAT, TotalWeight) != '' and CONVERT(FLOAT, TotalWeight) > '0'";
$sql = "select * FROM (SELECT  CAST([重量] AS DECIMAL(18,2)) as 'TotalWeight'
,[機台代號] as 'Machine'
,DATEADD(HOUR, -7, CAST([完成時間] AS DATETIME2(7))) as 'EndTime' 
from [ERP_Production] where [製程]='染色')DyeProduction
 where  EndTime < '$today_end' and EndTime > '$today_start' and CONVERT(FLOAT, TotalWeight) != '' and CONVERT(FLOAT, TotalWeight) > '0'";

$result = sqlsrv_query($link, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

if ($result === false) {
    // 查询执行失败，打印错误信息
    die( print_r( sqlsrv_errors(), true));
}

$num = sqlsrv_num_rows($result);//生產缸數

$weight_all = 0;

$time = 0;

//抓今天有運作的資料
while ($row = sqlsrv_fetch_array($result)) {
    $weight = $row['TotalWeight'];
    $weight_all = $weight_all + $weight;//生產量
}

$sql_2 = "select * from DyeProduction where  EndTime < '$today_end' and EndTime > '$today_start' and OverRunTime > '0.04167'";

$result_2 = sqlsrv_query($link, $sql_2, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
$num_2 = sqlsrv_num_rows($result_2);//延遲缸數


$all_data_array[] = array("chart_name" => 'data4', "number1" => $weight_all, "number2" => $num, "delay" => $num_2);

echo json_encode($all_data_array);
?>
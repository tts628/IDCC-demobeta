<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

$today_date = date('Y-m-d');
//$today_date = '2019-11-25';

// $today_start = $today_date . ' 00' . ':00:00.000';
// $today_end = $today_date . ' 23' . ':59:59.999';
$today_start = date("Y-m-d H:i:s", strtotime("-24 hours"));
$today_end = date("Y-m-d H:i:s");


// $sql_quantity = "select COUNT(*) AS quantity from LabProduction where EndTime >= '$today_start' and EndTime <= '$today_end' GROUP BY Dyelot , ShotNo ";
// $sql_quantity = "select COUNT(*) AS quantity from LabProduction where EndTime >= '$today_start' and EndTime <= '$today_end' GROUP BY Dyelot  ";
$sql_quantity = "SELECT 
  COUNT(DISTINCT StartTime) AS 打杯數,
  COUNT(DISTINCT [Dyelot]) AS 打色數
FROM [LabProduction]
where EndTime >= '$today_start' and EndTime <= '$today_end'
and [Name] <> 'test'";

$result_quantity = sqlsrv_query($link, $sql_quantity) or die("sql error" . sqlsrv_errors());

//$num = sqlsrv_num_rows($result);
$quantity_total = 0;
while ($row_quantity = sqlsrv_fetch_array($result_quantity)) {
    // $quantity = $row_quantity['quantity'];
    // $quantity_total = $quantity_total + $quantity;
    // $quantity_total = $quantity_total + 1;
    $quantity_total = $row_quantity['打色數'];
    $dripping_total = $row_quantity['打杯數'];


}

//----------------------------------

$sql_reconsider = "select COUNT(*) AS reconsider from LabProduction where EndTime >= '$today_start' and EndTime <= '$today_end' and ExportCode<>'' GROUP BY Dyelot ";

$result_reconsider = sqlsrv_query($link, $sql_reconsider) or die("sql error" . sqlsrv_errors());

//$num = sqlsrv_num_rows($result);
$reconsider_total = 0;
while ($row_reconsider = sqlsrv_fetch_array($result_reconsider)) {
    $reconsider = $row_reconsider['reconsider'];
    // $reconsider_total = $reconsider_total + $reconsider;
    $reconsider_total = $reconsider_total + 1;
}

//確認樣----------------------------------

$sql_confirm = "select COUNT(*) AS confirm from LabProduction where EndTime >= '$today_start' and EndTime <= '$today_end' and ExportCode='' GROUP BY Dyelot , ShotNo , ExportCode ";

$result_confirm = sqlsrv_query($link, $sql_confirm) or die("sql error" . sqlsrv_errors());

//$num = sqlsrv_num_rows($result);
$confirm_total = 0;
while ($row_reconsider = sqlsrv_fetch_array($result_confirm)) {
    $confirm = $row_reconsider['confirm'];
    // $confirm_total = $confirm_total + $confirm;
    $confirm_total = $confirm_total + 1;

}


//確認樣----------------------------------

$sql_confirm = "SELECT
round(avg( 滴液成功率),2) as '平均成功率'

FROM
(
SELECT [Machine],
	  [EndTime],
	  
        CASE WHEN [ActualWeight] = 0 THEN 0
             ELSE ROUND((1 - ROUND(ABS([ActualWeight] - [TargetWeight]), 2)) * 100, 1)
        END
         AS 滴液成功率
  FROM [LabProduction]
  where [EndTime] <>''
  AND  EndTime >= '$today_start' and EndTime <= '$today_end'
  )TAB
  
  ";

$result_confirm = sqlsrv_query($link, $sql_confirm) or die("sql error" . sqlsrv_errors());

//$num = sqlsrv_num_rows($result);
$confirm_total = 0;
while ($row_reconsider = sqlsrv_fetch_array($result_confirm)) {
    // $confirm = $row_reconsider['confirm'];
    // $confirm_total = $confirm_total + $confirm;
    // $confirm_total = $confirm_total + 1;
    $home_index_average_success_rate = $row_reconsider['平均成功率'];

}


$all_data_array[] = array("quantity" => $quantity_total, "reconsider" => $reconsider_total, "dripping_total" => $dripping_total, "average_success_rate" => $home_index_average_success_rate, "confirm" => $confirm_total);

echo json_encode($all_data_array);
sqlsrv_close($link);

?>
<?php
// ini_set('display_errors', '1');
// error_reporting(E_ALL);
// require_once('./common/database.php');
// $today_date = date('Y-m-d');
// //$today_date = '2020-12-21';

// $today_start = $today_date . ' 00:00:00.000';
// //$today_start = date("Y-m-d H:i:s", strtotime("-24 hours"));

// $today_start_minus = strtotime($today_start); //今天開始


// $today_end = $today_date . ' 23:59:59.999';
// //$today_end = date("Y-m-d H:i:s");

// $today_end_minus = strtotime($today_end);//今天結束


// //echo json_encode($today_start);
// //echo json_encode($today_end);


// $sql = "select * from FinishProduction where  EndTime < '$today_end' and EndTime > '$today_start' and ProcessType != ''";

// $result = sqlsrv_query($link, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
// $num = sqlsrv_num_rows($result);//輸送次數

// $front_all = 0;
// $mid_all = 0;
// $finish_all = 0;

// //抓今天有運作的資料
// while ($row = sqlsrv_fetch_array($result)) {
//     if ($row['ProcessType'] == '0') {
//         $front = $row['TotalLength'];
//         $front_all = $front_all + $front;//前定
//     }
//     if ($row['ProcessType'] == '1') {
//         $mid = $row['TotalLength'];
//         $mid_all = $mid_all + $mid;//中定
//     }
//     if ($row['ProcessType'] == '2') {
//         $finish = $row['TotalLength'];
//         $finish_all = $finish_all + $finish;//成定
//     }


// }

// $all_data_array[] = array("chart_name" => 'data3', "front" => $front_all, "mid" => $mid_all, "finish" => $finish_all);

// echo json_encode($all_data_array);
?>


<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

$today_start = date("Y-m-d H:i:s", strtotime("-24 hours"));
$today_end = date("Y-m-d H:i:s");

// $sql = "  SELECT *
// FROM (
//     SELECT
//        *,
//         ROW_NUMBER() OVER (PARTITION BY Machine ORDER BY LogTimeStamp DESC) AS RowNumber
//     FROM
//          [FinishMachines_Stenter_Machine]
// ) AS LatestRecords
// WHERE RowNumber = 1";


$sql = " SELECT Machine,avg(MachineSpeed) as 'MachineSpeed'
FROM
        [FinishMachines_Stenter_Machine]
       where MachineSpeed > 0
       AND LogTimeStamp < '$today_end' and LogTimeStamp > '$today_start'
       group by Machine";
//   var_dump( $sql);
$result = sqlsrv_query($link, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));



//抓今天有運作的資料
while ($row = sqlsrv_fetch_array($result)) {
    if($row['Machine']=='MON1'){
        $T01_speed=$row['MachineSpeed']/10.0;
    }
    else if($row['Machine']=='MON2'){
        $T03_speed=$row['MachineSpeed']/10.0;
    }
    else if($row['Machine']=='MON3'){
        $T05_speed=$row['MachineSpeed']/10.0;

    }
    else{

    }

}

$all_data_array[] = array("chart_name" => 'data3', "T01_speed" => $T01_speed, "T03_speed" => $T03_speed, "T05_speed" => $T05_speed);

echo json_encode($all_data_array);
?>
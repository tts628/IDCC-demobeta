<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

$today_date = date('Y-m-d');
//$today_date = '2020-03-14';

// $today_start = $today_date . ' 00:00:00.000';
$today_start = date("Y-m-d H:i:s", strtotime("-1 days"));

$today_start_minus = strtotime($today_start); //今天開始


// $today_end = $today_date . ' 23:59:59.999';
$today_end = date("Y-m-d H:i:s", strtotime("-0 days"));

$today_end_minus = strtotime($today_end);//今天結束


//echo json_encode($today_start);
//echo json_encode($today_end);


$sql = "select DispenseSeconds,DispenseGrams from DispenserProduction where  EndTime < '$today_end' and EndTime > '$today_start' and DispenseSeconds > '0' and DispenseSeconds !=''";

$result = sqlsrv_query($link, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
$num = sqlsrv_num_rows($result);//輸送次數

$time_all = 0;
$quantity_all = 0;
//抓今天有運作的資料
while ($row = sqlsrv_fetch_array($result)) {
    $time = $row['DispenseSeconds'];
    $time_all = $time_all + $time;

    $quantity = $row['DispenseGrams'];
    $quantity_all = $quantity_all + $quantity;//輸送量Kg
}

//echo json_encode($time_all);

//echo json_encode($num);
if ($time_all > 0 && $num > 0) {
    $time_final = ($time_all / $num);//秒,輸送時間
    $time_final = ceil($time_final);
    $h = floor(($time_final % (3600 * 24)) / 3600);
    $m = floor((($time_final % (3600 * 24)) % 3600) / 60);
    $s = $time_final-$m*60-$h*3600;
    if ($h < '10') {
        $h = '0' . $h;
    }
    if ($m < '10') {
        $m = '0' . $m;
    }
    if ($s < '10') {
        $s = '0' . $s;
    }
    $time_total = $m . ':' . $s;
} else {
    $time_total = '00:00';
}



if($quantity_all  > 1000000) {
    $quantity_all= round(($quantity_all / 1000000), 2). 'T' ;
}
else if($quantity_all > 1000) {
    $quantity_all= round(($quantity_all / 1000), 2). 'KG' ;
}
else{
    $quantity_all= round($quantity_all, 2) . 'G' ;
}



//echo json_encode($time_total);
// $quantity_all = round($quantity_all, 2);

$all_data_array[] = array("chart_name" => 'data2', "time" => $time_total, "number" => $num, "quantity" => $quantity_all);

echo json_encode($all_data_array);
?>
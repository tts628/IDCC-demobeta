<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

$machine_name = $_POST['machine_name'];
$today = $_POST['day'];
$factory_name = $_POST['factory_name'];
$day_name = $_POST['dayname'];
$week_name = $_POST['weekname'];
$mon_name = $_POST['monthname'];
$month_name = $_POST['month_name'];

if ($factory_name == 'DyeMachines') {
    $factory = 'DyeProduction';
} else if ($factory_name == 'FinishMachines') {
    $factory = 'FinishProduction';
} else if ($factory_name == 'DispenserMachines') {
    $factory = 'DispenserProduction';
} else if ($factory_name == 'LabMachines') {
    $factory = 'LabProduction';
}
//抓取當周(天) 每$day.' 23:59:59.999的資料
//$day_name=['Sun','Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
$TotalWeight_day_array = array();
$day = date("Y-m-d", strtotime('sunday last week', strtotime($today)));//取得這周星期一
for ($i = 1; $i <= 7; $i++) {
    $TotalWeight_day = 0;
    $day1 = $day . ' 00:00:00.000';//設定小時
    $day2 = $day . ' 23:59:59.999';//設定小時
    if ($machine_name == 'Allmachine') {
        $sql = "select TotalWeight from $factory WHERE StartTime>='$day1' AND StartTime<='$day2'";
    } else {
        $sql = "select TotalWeight from $factory WHERE Machine='$machine_name' AND StartTime>='$day1' AND StartTime<='$day2'";
    }
    $result = sqlsrv_query($link, $sql);
    if ($result === false) {
        die("SQL error: " . print_r(sqlsrv_errors(), true));
    }

    while ($row = sqlsrv_fetch_array($result)) {
        $TotalWeight_day += $row['TotalWeight'];
    }
    $dayy = date('m-d', strtotime($day));//加一天
    $TotalWeight_day_array[] = array("name" => $dayy . '(' . $day_name[$i - 1] . ')', "y" => $TotalWeight_day);
    $day = date('Y-m-d', strtotime('+1 day', strtotime($day)));//加一天
}

//抓取當月(周) 每$month_start.' 23:59:59.999的資料
$TotalWeight_week_array = array();
$month_start = date("Y-m-d", strtotime('first day of this month', strtotime($today)));//取得這月第一天
$month_end = date("Y-m-d", strtotime('last day of this month', strtotime($today)));//取得這月最後一天
$end_date = date("d", (strtotime($month_end) - strtotime($month_start)));//這月有幾天
$time = date("Y-m", strtotime($today));
$TotalWeight_week = 0;
$j = 1;
for ($i = 1; $i <= $end_date; $i++) {
    $ff = getdate(strtotime($month_start));
    $week1 = $month_start . ' 00:00:00.000';//設定小時
    $week2 = $month_start . ' 23:59:59.999';//設定小時
    if ($machine_name == 'Allmachine') {
        $sql = "select TotalWeight from $factory WHERE StartTime>='$week1' AND StartTime<='$week2'";
    } else {
        $sql = "select TotalWeight from $factory WHERE Machine='$machine_name' AND StartTime>='$week1' AND StartTime<='$week2'";
    }
    $result = sqlsrv_query($link, $sql) or die(json_encode(sqlsrv_errors()));
    while ($row = sqlsrv_fetch_array($result)) {
        $TotalWeight_week += $row['TotalWeight'];
    }
    if ($ff['wday'] == 6) {
        $yearmonth = date('m', strtotime($month_start));
        $TotalWeight_week_array[] = array("name" => $yearmonth . $month_name . '(' . $week_name[$j - 1] . ')', "y" => $TotalWeight_week);
        $TotalWeight_week = 0;
        $j++;
    } else if ($end_date == $i) {
        $yearmonth = date('m', strtotime($month_start));
        $TotalWeight_week_array[] = array("name" => $yearmonth . $month_name . '(' . $week_name[$j - 1] . ')', "y" => $TotalWeight_week);
        $TotalWeight_week = 0;
        $j++;
    }
    $month_start = date('Y-m-d', strtotime('+1 day', strtotime($month_start)));//加一天
}

//抓取當年(月) 每$month_start.' 23:59:59.999的資料
//$mon_name=['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$TotalWeight_month_array = array();
$month = date("Y", strtotime($today));//取得這周星期一
for ($i = 1; $i < 13; $i++) {
    $TotalWeight_month = 0;
    if ($i < 10) {
        $month1 = $month . '-0' . $i . '-01' . ' 00:00:00.000';
    } else {
        $month1 = $month . '-' . $i . '-01' . ' 00:00:00.000';
    }
    $month2 = date("Y-m-d", strtotime('last day of this month', strtotime($month1)));//取得這月最後一天
    $month2 = $month2 . ' 23:59:59.999';
    if ($machine_name == 'Allmachine') {
        $sql = "select TotalWeight from $factory WHERE StartTime>='$month1' AND StartTime<='$month2'";
    } else {
        $sql = "select TotalWeight from $factory WHERE Machine='$machine_name' AND StartTime>='$month1' AND StartTime<='$month2'";
    }
    $result = sqlsrv_query($link, $sql) or die("sql error" . sqlsrv_errors());
    while ($row = sqlsrv_fetch_array($result)) {
        $TotalWeight_month += $row['TotalWeight'];
    }
    //    $month3=date("Y-m",strtotime($month2));
    $TotalWeight_month_array[] = array("name" => $mon_name[$i - 1], "y" => $TotalWeight_month);
}

$Allcapacity_array[0] = array("name" => 'TotalWeight_day', "type" => 'spline', "data" => $TotalWeight_day_array);
$Allcapacity_array[1] = array("name" => 'TotalWeight_week', "type" => 'column', "data" => $TotalWeight_week_array);
$Allcapacity_array[2] = array("name" => 'TotalWeight_month', "type" => 'column', "data" => $TotalWeight_month_array);

echo json_encode($Allcapacity_array);
?>
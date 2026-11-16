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
    //$factory='DyeEnergyUsage';
    $factory = 'DyeWaterUsage';
} else if ($factory_name == 'FinishMachines') {
    $factory = 'FinishEnergyUsage';
}
//抓取當日(小時) 每$i:59:59.999的資料
$Water1Usage_hour_array = array();
$Water2Usage_hour_array = array();
$Water3Usage_hour_array = array();
$Water4Usage_hour_array = array();
$hour_unit = array();
$hour_unit_carry = 0;
for ($i = 0; $i < 24; $i++) {
    $Water1Usage_hour = 0;
    $Water2Usage_hour = 0;
    $Water3Usage_hour = 0;
    $Water4Usage_hour = 0;
    if ($i < 10) {
        $hour1 = $today . ' 0' . $i . ':00:00.000';
        $hour2 = $today . ' 0' . $i . ':59:59.999';
    } else {
        $hour1 = $today . ' ' . $i . ':00:00.000';
        $hour2 = $today . ' ' . $i . ':59:59.999';
    }
    if ($machine_name == 'Allmachine') {
        $sql = "select Water1Usage, Water2Usage, Water3Usage, Water4Usage from $factory WHERE StartTime>='$hour1' AND StartTime<='$hour2'";
    } else {
        $sql = "select Water1Usage, Water2Usage, Water3Usage, Water4Usage from $factory WHERE Machine='$machine_name' AND StartTime>='$hour1' AND StartTime<='$hour2'";
    }
    $result = sqlsrv_query($link, $sql) or die("sql error" . sqlsrv_errors());
    while ($row = sqlsrv_fetch_array($result)) {
        $Water1Usage_hour += $row['Water1Usage'];
        $Water2Usage_hour += $row['Water2Usage'];
        $Water3Usage_hour += $row['Water3Usage'];
        $Water4Usage_hour += $row['Water4Usage'];
    }
    if ($Water1Usage_hour > 1000 || $Water2Usage_hour > 1000 || $Water3Usage_hour > 1000 || $Water4Usage_hour > 1000) {
        $hour_unit_carry = 1;
    }
    $hour3 = date('H', strtotime('+1 hour', strtotime($hour1)));
    $Water1Usage_hour_array[] = array("name" => $hour3, "y" => $Water1Usage_hour);
    $Water2Usage_hour_array[] = array("name" => $hour3, "y" => $Water2Usage_hour);
    $Water3Usage_hour_array[] = array("name" => $hour3, "y" => $Water3Usage_hour);
    $Water4Usage_hour_array[] = array("name" => $hour3, "y" => $Water4Usage_hour);

}
if ($hour_unit_carry == 1) {
    for ($i = 0; $i < 24; $i++) {
        $Water1Usage_hour_array[$i]['y'] = $Water1Usage_hour_array[$i]['y'] / 1000.0;
        $Water2Usage_hour_array[$i]['y'] = $Water2Usage_hour_array[$i]['y'] / 1000.0;
        $Water3Usage_hour_array[$i]['y'] = $Water3Usage_hour_array[$i]['y'] / 1000.0;
        $Water4Usage_hour_array[$i]['y'] = $Water4Usage_hour_array[$i]['y'] / 1000.0;
    }
    $hour_unit[0] = array("Water1Usage_hour_unit" => 'T', "Water2Usage_hour_unit" => 'T', "Water3Usage_hour_unit" => 'T', "Water4Usage_hour_unit" => 'T');
} else {
    $hour_unit[0] = array("Water1Usage_hour_unit" => 'L', "Water2Usage_hour_unit" => 'L', "Water3Usage_hour_unit" => 'L', "Water4Usage_hour_unit" => 'L');
}

//抓取當周(天) 每$day.' 23:59:59.999的資料
// $day_name=['Sun','Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
$Water1Usage_day_array = array();
$Water2Usage_day_array = array();
$Water3Usage_day_array = array();
$Water4Usage_day_array = array();
$day_unit = array();
$day_unit_carry = 0;
$day = date("Y-m-d", strtotime('sunday last week', strtotime($today)));//取得這周星期一
for ($i = 1; $i <= 7; $i++) {
    $Water1Usage_day = 0;
    $Water2Usage_day = 0;
    $Water3Usage_day = 0;
    $Water4Usage_day = 0;
    $day1 = $day . ' 00:00:00.000';//設定小時
    $day2 = $day . ' 23:59:59.999';//設定小時
    if ($machine_name == 'Allmachine') {
        $sql = "select Water1Usage, Water2Usage, Water3Usage, Water4Usage from $factory WHERE StartTime>='$day1' AND StartTime<='$day2'";
    } else {
        $sql = "select Water1Usage, Water2Usage, Water3Usage, Water4Usage from $factory WHERE Machine='$machine_name' AND StartTime>='$day1' AND StartTime<='$day2'";
    }
    $result = sqlsrv_query($link, $sql) or die("sql error" . sqlsrv_errors());
    while ($row = sqlsrv_fetch_array($result)) {
        $Water1Usage_day += $row['Water1Usage'];
        $Water2Usage_day += $row['Water2Usage'];
        $Water3Usage_day += $row['Water3Usage'];
        $Water4Usage_day += $row['Water4Usage'];
    }

    if ($Water1Usage_day > 1000 || $Water2Usage_day > 1000 || $Water3Usage_day > 1000 || $Water4Usage_day > 1000) {
        $day_unit_carry = 1;
    }
    $dayy = date('m-d', strtotime($day));//加一天
    $Water1Usage_day_array[] = array("name" => $dayy . '(' . $day_name[$i - 1] . ')', "y" => $Water1Usage_day);
    $Water2Usage_day_array[] = array("name" => $dayy . '(' . $day_name[$i - 1] . ')', "y" => $Water2Usage_day);
    $Water3Usage_day_array[] = array("name" => $dayy . '(' . $day_name[$i - 1] . ')', "y" => $Water3Usage_day);
    $Water4Usage_day_array[] = array("name" => $dayy . '(' . $day_name[$i - 1] . ')', "y" => $Water4Usage_day);
    $day = date('Y-m-d', strtotime('+1 day', strtotime($day)));//加一天
}
if ($day_unit_carry == 1) {
    for ($i = 0; $i < 7; $i++) {
        $Water1Usage_day_array[$i]['y'] = $Water1Usage_day_array[$i]['y'] / 1000.0;
        $Water2Usage_day_array[$i]['y'] = $Water2Usage_day_array[$i]['y'] / 1000.0;
        $Water3Usage_day_array[$i]['y'] = $Water3Usage_day_array[$i]['y'] / 1000.0;
        $Water4Usage_day_array[$i]['y'] = $Water4Usage_day_array[$i]['y'] / 1000.0;
    }
    $day_unit[0] = array("Water1Usage_day_unit" => 'T', "Water2Usage_day_unit" => 'T', "Water3Usage_day_unit" => 'T', "Water4Usage_day_unit" => 'T');

} else {
    $day_unit[0] = array("Water1Usage_day_unit" => 'L', "Water2Usage_day_unit" => 'L', "Water3Usage_day_unit" => 'L', "Water4Usage_day_unit" => 'L');

}

//抓取當月(周) 每$month_start.' 23:59:59.999的資料
$Water1Usage_week_array = array();
$Water2Usage_week_array = array();
$Water3Usage_week_array = array();
$Water4Usage_week_array = array();
$week_unit = array();
$week_unit_carry = 0;
$month_start = date("Y-m-d", strtotime('first day of this month', strtotime($today)));//取得這月第一天
$month_end = date("Y-m-d", strtotime('last day of this month', strtotime($today)));//取得這月最後一天
$end_date = date("d", (strtotime($month_end) - strtotime($month_start)));//這月有幾天
$Water1Usage_week = 0;
$Water2Usage_week = 0;
$Water3Usage_week = 0;
$Water4Usage_week = 0;
$j = 1;
for ($i = 1; $i <= $end_date; $i++) {
    $ff = getdate(strtotime($month_start));
    $week1 = $month_start . ' 00:00:00.000';//設定小時
    $week2 = $month_start . ' 23:59:59.999';//設定小時
    if ($machine_name == 'Allmachine') {
        $sql = "select Water1Usage, Water2Usage, Water3Usage, Water4Usage from $factory WHERE StartTime>='$week1' AND StartTime<='$week2'";
    } else {
        $sql = "select Water1Usage, Water2Usage, Water3Usage, Water4Usage from $factory WHERE Machine='$machine_name' AND StartTime>='$week1' AND StartTime<='$week2'";
    }
    $result = sqlsrv_query($link, $sql) or die("sql error" . sqlsrv_errors());
    while ($row = sqlsrv_fetch_array($result)) {
        $Water1Usage_week += $row['Water1Usage'];
        $Water2Usage_week += $row['Water2Usage'];
        $Water3Usage_week += $row['Water3Usage'];
        $Water4Usage_week += $row['Water4Usage'];
    }

    if ($ff['wday'] == 6) {
        if ($Water1Usage_week > 1000 || $Water2Usage_week > 1000 || $Water3Usage_week > 1000 || $Water4Usage_week > 1000) {
            $week_unit_carry = 1;
        }
        $yearmonth = date('m', strtotime($month_start));
        $Water1Usage_week_array[] = array("name" => $yearmonth . $month_name . '(' . $week_name[$j - 1] . ')', "y" => $Water1Usage_week);
        $Water2Usage_week_array[] = array("name" => $yearmonth . $month_name . '(' . $week_name[$j - 1] . ')', "y" => $Water2Usage_week);
        $Water3Usage_week_array[] = array("name" => $yearmonth . $month_name . '(' . $week_name[$j - 1] . ')', "y" => $Water3Usage_week);
        $Water4Usage_week_array[] = array("name" => $yearmonth . $month_name . '(' . $week_name[$j - 1] . ')', "y" => $Water4Usage_week);
        $Water1Usage_week = 0;
        $Water2Usage_week = 0;
        $Water3Usage_week = 0;
        $Water4Usage_week = 0;
        $j++;
    } else if ($end_date == $i) {
        if ($Water1Usage_week > 1000 || $Water2Usage_week > 1000 || $Water3Usage_week > 1000 || $Water4Usage_week > 1000) {
            $week_unit_carry = 1;
        }
        $yearmonth = date('m', strtotime($month_start));
        $Water1Usage_week_array[] = array("name" => $yearmonth . $month_name . '(' . $week_name[$j - 1] . ')', "y" => $Water1Usage_week);
        $Water2Usage_week_array[] = array("name" => $yearmonth . $month_name . '(' . $week_name[$j - 1] . ')', "y" => $Water2Usage_week);
        $Water3Usage_week_array[] = array("name" => $yearmonth . $month_name . '(' . $week_name[$j - 1] . ')', "y" => $Water3Usage_week);
        $Water4Usage_week_array[] = array("name" => $yearmonth . $month_name . '(' . $week_name[$j - 1] . ')', "y" => $Water4Usage_week);
        $Water1Usage_week = 0;
        $Water2Usage_week = 0;
        $Water3Usage_week = 0;
        $Water4Usage_week = 0;
        $j++;
    }
    $month_start = date('Y-m-d', strtotime('+1 day', strtotime($month_start)));//加一天
}
if ($week_unit_carry == 1) {
    for ($i = 0; $i < $j - 1; $i++) {
        $Water1Usage_week_array[$i]['y'] = $Water1Usage_week_array[$i]['y'] / 1000.0;
        $Water2Usage_week_array[$i]['y'] = $Water2Usage_week_array[$i]['y'] / 1000.0;
        $Water3Usage_week_array[$i]['y'] = $Water3Usage_week_array[$i]['y'] / 1000.0;
        $Water4Usage_week_array[$i]['y'] = $Water4Usage_week_array[$i]['y'] / 1000.0;
    }
    $week_unit[0] = array("Water1Usage_week_unit" => 'T', "Water2Usage_week_unit" => 'T', "Water3Usage_week_unit" => 'T', "Water4Usage_week_unit" => 'T');

} else {
    $week_unit[0] = array("Water1Usage_week_unit" => 'L', "Water2Usage_week_unit" => 'L', "Water3Usage_week_unit" => 'L', "Water4Usage_week_unit" => 'L');
}
// var_dump($Water4Usage_week_array);

//抓取當年(月) 每$month_start.' 23:59:59.999的資料
// $mon_name=['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$Water1Usage_month_array = array();
$Water2Usage_month_array = array();
$Water3Usage_month_array = array();
$Water4Usage_month_array = array();
$month_unit = array();
$month_unit_carry = 0;
$month = date("Y", strtotime($today));//取得這周星期一
for ($i = 1; $i < 13; $i++) {
    $Water1Usage_month = 0;
    $Water2Usage_month = 0;
    $Water3Usage_month = 0;
    $Water4Usage_month = 0;
    if ($i < 10) {
        $month1 = $month . '-0' . $i . '-01' . ' 00:00:00.000';
    } else {
        $month1 = $month . '-' . $i . '-01' . ' 00:00:00.000';
    }
    $month2 = date("Y-m-d", strtotime('last day of this month', strtotime($month1)));//取得這月最後一天
    $month2 = $month2 . ' 23:59:59.999';
    if ($machine_name == 'Allmachine') {
        $sql = "select  Water1Usage, Water2Usage, Water3Usage, Water4Usage from $factory WHERE StartTime>='$month1' AND StartTime<='$month2'";
    } else {
        $sql = "select  Water1Usage, Water2Usage, Water3Usage, Water4Usage from $factory WHERE Machine='$machine_name' AND StartTime>='$month1' AND StartTime<='$month2'";
    }
    $result = sqlsrv_query($link, $sql) or die("sql error" . sqlsrv_errors());
    while ($row = sqlsrv_fetch_array($result)) {
        $Water1Usage_month += $row['Water1Usage'];
        $Water2Usage_month += $row['Water2Usage'];
        $Water3Usage_month += $row['Water3Usage'];
        $Water2Usage_month += $row['Water4Usage'];
    }
    if ($Water1Usage_month > 1000 || $Water2Usage_month > 1000 || $Water3Usage_month > 1000 || $Water4Usage_month > 1000) {
        $month_unit_carry = 1;
    }
    // $month3=date("Y-m",strtotime($month2));
    $Water1Usage_month_array[] = array("name" => $mon_name[$i - 1], "y" => $Water1Usage_month);
    $Water2Usage_month_array[] = array("name" => $mon_name[$i - 1], "y" => $Water2Usage_month);
    $Water3Usage_month_array[] = array("name" => $mon_name[$i - 1], "y" => $Water3Usage_month);
    $Water4Usage_month_array[] = array("name" => $mon_name[$i - 1], "y" => $Water4Usage_month);

}
if ($month_unit_carry == 1) {
    for ($i = 0; $i < 12; $i++) {
        $Water1Usage_month_array[$i]['y'] = $Water1Usage_month_array[$i]['y'] / 1000.0;
        $Water2Usage_month_array[$i]['y'] = $Water2Usage_month_array[$i]['y'] / 1000.0;
        $Water3Usage_month_array[$i]['y'] = $Water3Usage_month_array[$i]['y'] / 1000.0;
        $Water4Usage_month_array[$i]['y'] = $Water4Usage_month_array[$i]['y'] / 1000.0;
    }
    $month_unit[0] = array("Water1Usage_month_unit" => 'T', "Water2Usage_month_unit" => 'T', "Water3Usage_month_unit" => 'T', "Water4Usage_month_unit" => 'T');
} else {
    $month_unit[0] = array("Water1Usage_month_unit" => 'L', "Water2Usage_month_unit" => 'L', "Water3Usage_month_unit" => 'L', "Water4Usage_month_unit" => 'L');
}

$AllUsage_array[0] = array("name" => 'Water1Usage_hour', "data" => $Water1Usage_hour_array);
$AllUsage_array[1] = array("name" => 'Water2Usage_hour', "data" => $Water2Usage_hour_array);
$AllUsage_array[2] = array("name" => 'Water3Usage_hour', "data" => $Water3Usage_hour_array);
$AllUsage_array[3] = array("name" => 'Water4Usage_hour', "data" => $Water4Usage_hour_array);

$AllUsage_array[4] = array("name" => 'Water1Usage_day', "data" => $Water1Usage_day_array);
$AllUsage_array[5] = array("name" => 'Water2Usage_day', "data" => $Water2Usage_day_array);
$AllUsage_array[6] = array("name" => 'Water3Usage_day', "data" => $Water3Usage_day_array);
$AllUsage_array[7] = array("name" => 'Water4Usage_day', "data" => $Water4Usage_day_array);

$AllUsage_array[8] = array("name" => 'Water1Usage_week', "data" => $Water1Usage_week_array);
$AllUsage_array[9] = array("name" => 'Water2Usage_week', "data" => $Water2Usage_week_array);
$AllUsage_array[10] = array("name" => 'Water3Usage_week', "data" => $Water3Usage_week_array);
$AllUsage_array[11] = array("name" => 'Water4Usage_week', "data" => $Water4Usage_week_array);

$AllUsage_array[12] = array("name" => 'Water1Usage_month', "data" => $Water1Usage_month_array);
$AllUsage_array[13] = array("name" => 'Water2Usage_month', "data" => $Water2Usage_month_array);
$AllUsage_array[14] = array("name" => 'Water3Usage_month', "data" => $Water3Usage_month_array);
$AllUsage_array[15] = array("name" => 'Water4Usage_month', "data" => $Water4Usage_month_array);

$AllUsage_array[16] = array("hour" => $hour_unit, "day" => $day_unit, "week" => $week_unit, "month" => $month_unit);
echo json_encode($AllUsage_array);
// var_dump($AllUsage_array[3]);
?>
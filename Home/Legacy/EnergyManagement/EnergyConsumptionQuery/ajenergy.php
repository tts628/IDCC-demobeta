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
    $factory = 'DyeEnergyUsage';
} else if ($factory_name == 'FinishMachines') {
    $factory = 'FinishEnergyUsage';
}
//抓取當日(小時) 每$i:59:59.999的資料
$PowerUsage_hour_array = array();
$SteamUsage_hour_array = array();
$WaterUsage_hour_array = array();
$hour_unit = array();
$hour_unit_carry = 0;
for ($i = 0; $i < 24; $i++) {
    $PowerUsage_hour = 0;
    $SteamUsage_hour = 0;
    $WaterUsage_hour = 0;
    if ($i < 10) {
        $hour1 = $today . ' 0' . $i . ':00:00.000';
        $hour2 = $today . ' 0' . $i . ':59:59.999';
    } else {
        $hour1 = $today . ' ' . $i . ':00:00.000';
        $hour2 = $today . ' ' . $i . ':59:59.999';
    }
    if ($machine_name == 'Allmachine') {
        $sql = "select PowerUsage,SteamUsage,WaterUsage from $factory WHERE StartTime>='$hour1' AND StartTime<='$hour2'";
    } else {
        $sql = "select PowerUsage,SteamUsage,WaterUsage from $factory WHERE Machine='$machine_name' AND StartTime>='$hour1' AND StartTime<='$hour2'";
    }
    $result = sqlsrv_query($link, $sql) or die("sql error" . sqlsrv_errors());
    while ($row = sqlsrv_fetch_array($result)) {
        $PowerUsage_hour += $row['PowerUsage'];
        $SteamUsage_hour += $row['SteamUsage'];
        $WaterUsage_hour += $row['WaterUsage'];
    }
    if ($PowerUsage_hour > 1000 || $SteamUsage_hour > 1000 || $WaterUsage_hour > 1000) {
        $hour_unit_carry = 1;
    }
    $hour3 = date('H', strtotime('+1 hour', strtotime($hour1)));
    $PowerUsage_hour_array[] = array("name" => $hour3, "y" => $PowerUsage_hour);
    $SteamUsage_hour_array[] = array("name" => $hour3, "y" => $SteamUsage_hour);
    $WaterUsage_hour_array[] = array("name" => $hour3, "y" => $WaterUsage_hour);
}
if ($hour_unit_carry == 1) {
    for ($i = 0; $i < 24; $i++) {
        $PowerUsage_hour_array[$i]['y'] = $PowerUsage_hour_array[$i]['y'] / 1000;
        $SteamUsage_hour_array[$i]['y'] = $SteamUsage_hour_array[$i]['y'] / 1000;
        $WaterUsage_hour_array[$i]['y'] = $WaterUsage_hour_array[$i]['y'] / 1000;
    }
    $hour_unit[0] = array("WaterUsage_hour_unit" => 'T', "SteamUsage_hour_unit" => 'T', "PowerUsage_hour_unit" => 'MW');
} else {
    $hour_unit[0] = array("WaterUsage_hour_unit" => 'L', "SteamUsage_hour_unit" => 'KG', "PowerUsage_hour_unit" => 'KW');
}

//抓取當周(天) 每$day.' 23:59:59.999的資料
// $day_name=['Sun','Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
$PowerUsage_day_array = array();
$SteamUsage_day_array = array();
$WaterUsage_day_array = array();
$day_unit = array();
$day_unit_carry = 0;
$day = date("Y-m-d", strtotime('sunday last week', strtotime($today)));//取得這周星期一
for ($i = 1; $i <= 7; $i++) {
    $PowerUsage_day = 0;
    $SteamUsage_day = 0;
    $WaterUsage_day = 0;
    $day1 = $day . ' 00:00:00.000';//設定小時
    $day2 = $day . ' 23:59:59.999';//設定小時
    if ($machine_name == 'Allmachine') {
        $sql = "select PowerUsage,SteamUsage,WaterUsage from $factory WHERE StartTime>='$day1' AND StartTime<='$day2'";
    } else {
        $sql = "select PowerUsage,SteamUsage,WaterUsage from $factory WHERE Machine='$machine_name' AND StartTime>='$day1' AND StartTime<='$day2'";
    }
    $result = sqlsrv_query($link, $sql) or die("sql error" . sqlsrv_errors());
    while ($row = sqlsrv_fetch_array($result)) {
        $PowerUsage_day += $row['PowerUsage'];
        $SteamUsage_day += $row['SteamUsage'];
        $WaterUsage_day += $row['WaterUsage'];
    }
    if ($PowerUsage_day > 1000 || $SteamUsage_day > 1000 || $WaterUsage_day > 1000) {
        $day_unit_carry = 1;
    }
    $dayy = date('m-d', strtotime($day));//加一天
    $PowerUsage_day_array[] = array("name" => $dayy . '(' . $day_name[$i - 1] . ')', "y" => $PowerUsage_day);
    $SteamUsage_day_array[] = array("name" => $dayy . '(' . $day_name[$i - 1] . ')', "y" => $SteamUsage_day);
    $WaterUsage_day_array[] = array("name" => $dayy . '(' . $day_name[$i - 1] . ')', "y" => $WaterUsage_day);
    $day = date('Y-m-d', strtotime('+1 day', strtotime($day)));//加一天
}
if ($day_unit_carry == 1) {
    for ($i = 0; $i < 7; $i++) {
        $PowerUsage_day_array[$i]['y'] = $PowerUsage_day_array[$i]['y'] / 1000;
        $SteamUsage_day_array[$i]['y'] = $SteamUsage_day_array[$i]['y'] / 1000;
        $WaterUsage_day_array[$i]['y'] = $WaterUsage_day_array[$i]['y'] / 1000;
    }
    $day_unit[0] = array("WaterUsage_day_unit" => 'T', "SteamUsage_day_unit" => 'T', "PowerUsage_day_unit" => 'MW');

} else {
    $day_unit[0] = array("WaterUsage_day_unit" => 'L', "SteamUsage_day_unit" => 'KG', "PowerUsage_day_unit" => 'KW');

}

//抓取當月(周) 每$month_start.' 23:59:59.999的資料
$PowerUsage_week_array = array();
$SteamUsage_week_array = array();
$WaterUsage_week_array = array();
$week_unit = array();
$week_unit_carry = 0;
$month_start = date("Y-m-d", strtotime('first day of this month', strtotime($today)));//取得這月第一天
$month_end = date("Y-m-d", strtotime('last day of this month', strtotime($today)));//取得這月最後一天
$end_date = date("d", (strtotime($month_end) - strtotime($month_start)));//這月有幾天
$PowerUsage_week = 0;
$SteamUsage_week = 0;
$WaterUsage_week = 0;
$j = 1;
for ($i = 1; $i <= $end_date; $i++) {
    $ff = getdate(strtotime($month_start));
    $week1 = $month_start . ' 00:00:00.000';//設定小時
    $week2 = $month_start . ' 23:59:59.999';//設定小時
    if ($machine_name == 'Allmachine') {
        $sql = "select PowerUsage,SteamUsage,WaterUsage from $factory WHERE StartTime>='$week1' AND StartTime<='$week2'";
    } else {
        $sql = "select PowerUsage,SteamUsage,WaterUsage from $factory WHERE Machine='$machine_name' AND StartTime>='$week1' AND StartTime<='$week2'";
    }
    $result = sqlsrv_query($link, $sql) or die("sql error" . sqlsrv_errors());
    while ($row = sqlsrv_fetch_array($result)) {
        $PowerUsage_week += $row['PowerUsage'];
        $SteamUsage_week += $row['SteamUsage'];
        $WaterUsage_week += $row['WaterUsage'];
    }
    if ($ff['wday'] == 6) {
        if ($PowerUsage_week > 1000 || $SteamUsage_week > 1000 || $WaterUsage_week > 1000) {
            $week_unit_carry = 1;
        }
        $yearmonth = date('m', strtotime($month_start));
        $PowerUsage_week_array[] = array("name" => $yearmonth . $month_name . '(' . $week_name[$j - 1] . ')', "y" => $PowerUsage_week);
        $SteamUsage_week_array[] = array("name" => $yearmonth . $month_name . '(' . $week_name[$j - 1] . ')', "y" => $SteamUsage_week);
        $WaterUsage_week_array[] = array("name" => $yearmonth . $month_name . '(' . $week_name[$j - 1] . ')', "y" => $WaterUsage_week);
        $PowerUsage_week = 0;
        $SteamUsage_week = 0;
        $WaterUsage_week = 0;
        $j++;
    } else if ($end_date == $i) {
        if ($PowerUsage_week > 1000 || $SteamUsage_week > 1000 || $WaterUsage_week > 1000) {
            $week_unit_carry = 1;
        }
        $yearmonth = date('m', strtotime($month_start));
        $PowerUsage_week_array[] = array("name" => $yearmonth . $month_name . '(' . $week_name[$j - 1] . ')', "y" => $PowerUsage_week);
        $SteamUsage_week_array[] = array("name" => $yearmonth . $month_name . '(' . $week_name[$j - 1] . ')', "y" => $SteamUsage_week);
        $WaterUsage_week_array[] = array("name" => $yearmonth . $month_name . '(' . $week_name[$j - 1] . ')', "y" => $WaterUsage_week);
        $PowerUsage_week = 0;
        $SteamUsage_week = 0;
        $WaterUsage_week = 0;
        $j++;
    }
    $month_start = date('Y-m-d', strtotime('+1 day', strtotime($month_start)));//加一天
}
if ($week_unit_carry == 1) {
    for ($i = 0; $i < $j - 1; $i++) {
        $PowerUsage_week_array[$i]['y'] = $PowerUsage_week_array[$i]['y'] / 1000;
        $SteamUsage_week_array[$i]['y'] = $SteamUsage_week_array[$i]['y'] / 1000;
        $WaterUsage_week_array[$i]['y'] = $WaterUsage_week_array[$i]['y'] / 1000;
    }
    $week_unit[0] = array("WaterUsage_week_unit" => 'T', "SteamUsage_week_unit" => 'T', "PowerUsage_week_unit" => 'MW');
} else {
    $week_unit[0] = array("WaterUsage_week_unit" => 'L', "SteamUsage_week_unit" => 'KG', "PowerUsage_week_unit" => 'KW');
}

//抓取當年(月) 每$month_start.' 23:59:59.999的資料
// $mon_name=['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun','Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$PowerUsage_month_array = array();
$SteamUsage_month_array = array();
$WaterUsage_month_array = array();
$month_unit = array();
$month_unit_carry = 0;
$month = date("Y", strtotime($today));//取得這周星期一
for ($i = 1; $i < 13; $i++) {
    $PowerUsage_month = 0;
    $SteamUsage_month = 0;
    $WaterUsage_month = 0;
    if ($i < 10) {
        $month1 = $month . '-0' . $i . '-01' . ' 00:00:00.000';
    } else {
        $month1 = $month . '-' . $i . '-01' . ' 00:00:00.000';
    }
    $month2 = date("Y-m-d", strtotime('last day of this month', strtotime($month1)));//取得這月最後一天
    $month2 = $month2 . ' 23:59:59.999';
    if ($machine_name == 'Allmachine') {
        $sql = "select PowerUsage,SteamUsage,WaterUsage from $factory WHERE StartTime>='$month1' AND StartTime<='$month2'";
    } else {
        $sql = "select PowerUsage,SteamUsage,WaterUsage from $factory WHERE Machine='$machine_name' AND StartTime>='$month1' AND StartTime<='$month2'";
    }
    $result = sqlsrv_query($link, $sql) or die("sql error" . sqlsrv_errors());
    while ($row = sqlsrv_fetch_array($result)) {
        $PowerUsage_month += $row['PowerUsage'];
        $SteamUsage_month += $row['SteamUsage'];
        $WaterUsage_month += $row['WaterUsage'];
    }
    if ($PowerUsage_month > 1000 || $SteamUsage_month > 1000 || $WaterUsage_month > 1000) {
        $month_unit_carry = 1;
    }
    // $month3=date("Y-m",strtotime($month2));
    $PowerUsage_month_array[] = array("name" => $mon_name[$i - 1], "y" => $PowerUsage_month);
    $SteamUsage_month_array[] = array("name" => $mon_name[$i - 1], "y" => $SteamUsage_month);
    $WaterUsage_month_array[] = array("name" => $mon_name[$i - 1], "y" => $WaterUsage_month);
}
if ($month_unit_carry == 1) {
    for ($i = 0; $i < 12; $i++) {
        $PowerUsage_month_array[$i]['y'] = $PowerUsage_month_array[$i]['y'] / 1000;
        $SteamUsage_month_array[$i]['y'] = $SteamUsage_month_array[$i]['y'] / 1000;
        $WaterUsage_month_array[$i]['y'] = $WaterUsage_month_array[$i]['y'] / 1000;
    }
    $month_unit[0] = array("WaterUsage_month_unit" => 'T', "SteamUsage_month_unit" => 'T', "PowerUsage_month_unit" => 'MW');
} else {
    $month_unit[0] = array("WaterUsage_month_unit" => 'L', "SteamUsage_month_unit" => 'KG', "PowerUsage_month_unit" => 'KW');
}


$AllUsage_array[0] = array("name" => 'PowerUsage_hour', "data" => $PowerUsage_hour_array);
$AllUsage_array[1] = array("name" => 'SteamUsage_hour', "data" => $SteamUsage_hour_array);
$AllUsage_array[2] = array("name" => 'WaterUsage_hour', "data" => $WaterUsage_hour_array);

$AllUsage_array[3] = array("name" => 'PowerUsage_day', "data" => $PowerUsage_day_array);
$AllUsage_array[4] = array("name" => 'SteamUsage_day', "data" => $SteamUsage_day_array);
$AllUsage_array[5] = array("name" => 'WaterUsage_day', "data" => $WaterUsage_day_array);
// $AllUsage_array[2]=array("name"=>'day',"data"=> $AllUsage_day_array);
$AllUsage_array[6] = array("name" => 'PowerUsage_week', "data" => $PowerUsage_week_array);
$AllUsage_array[7] = array("name" => 'SteamUsage_week', "data" => $SteamUsage_week_array);
$AllUsage_array[8] = array("name" => 'WaterUsage_week', "data" => $WaterUsage_week_array);

$AllUsage_array[9] = array("name" => 'PowerUsage_month', "data" => $PowerUsage_month_array);
$AllUsage_array[10] = array("name" => 'SteamUsage_month', "data" => $SteamUsage_month_array);
$AllUsage_array[11] = array("name" => 'WaterUsage_month', "data" => $WaterUsage_month_array);
// $AllUsage_array[5]=array("name"=>'month',"data"=>$AllUsage_month_array);
$AllUsage_array[12] = array("hour" => $hour_unit, "day" => $day_unit, "week" => $week_unit, "month" => $month_unit);
echo json_encode($AllUsage_array);
?>
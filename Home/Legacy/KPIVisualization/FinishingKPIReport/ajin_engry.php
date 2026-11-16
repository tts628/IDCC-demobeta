<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

$today = $_POST['day'];

$Dye_PowerUsage_hour_array = array();
$Dye_SteamUsage_hour_array = array();
$Dye_WaterUsage_hour_array = array();

$Finish_PowerUsage_hour_array = array();
$Finish_SteamUsage_hour_array = array();
$Finish_WaterUsage_hour_array = array();

$Dye_hour_unit = array();
$Finish_hour_unit = array();

$Dye_hour_unit_carry = 0;
$Finish_hour_unit_carry = 0;
$day = date("Y-m-d H", strtotime('last day', strtotime($today)));

for ($i = 0; $i < 24; $i++) {
    $Dye_PowerUsage_hour = 0;
    $Dye_SteamUsage_hour = 0;
    $Dye_WaterUsage_hour = 0;
    $Finish_PowerUsage_hour = 0;
    $Finish_SteamUsage_hour = 0;
    $Finish_WaterUsage_hour = 0;
    if ($i < 10) {
        $hour1 = $day . ':00:00.000';
        $hour2 = $day . ':59:59.999';
    } else {
        $hour1 = $day . ':00:00.000';
        $hour2 = $day . ':59:59.999';
    }
    $sql = "select PowerUsage,SteamUsage,WaterUsage from DyeEnergyUsage WHERE StartTime>='$hour1' AND StartTime<='$hour2'";
    $result = sqlsrv_query($link, $sql) or die("sql error" . sqlsrv_errors());
    while ($row = sqlsrv_fetch_array($result)) {
        $Dye_PowerUsage_hour += $row['PowerUsage'];
        $Dye_SteamUsage_hour += $row['SteamUsage'];
        $Dye_WaterUsage_hour += $row['WaterUsage'];
    }

    $sql1 = "select PowerUsage,SteamUsage,WaterUsage from FinishEnergyUsage WHERE StartTime>='$hour1' AND StartTime<='$hour2'";
    $result1 = sqlsrv_query($link, $sql1) or die("sql error" . sqlsrv_errors());
    while ($row1 = sqlsrv_fetch_array($result1)) {
        $Finish_PowerUsage_hour += $row1['PowerUsage'];
        $Finish_SteamUsage_hour += $row1['SteamUsage'];
        $Finish_WaterUsage_hour += $row1['WaterUsage'];
    }

    if ($Dye_PowerUsage_hour > 1000 || $Dye_SteamUsage_hour > 1000 || $Dye_WaterUsage_hour > 1000) {
        $Dye_hour_unit_carry = 1;
    }

    if ($Finish_PowerUsage_hour > 1000 || $Finish_SteamUsage_hour > 1000 || $Finish_WaterUsage_hour > 1000) {
        $Finish_hour_unit_carry = 1;
    }

    $hour3 = date('H', strtotime('+1 hour', strtotime($hour1)));
    $day = date('Y-m-d H', strtotime('+1 hour', strtotime($hour1)));
    $Dye_PowerUsage_hour_array[] = array("name" => $hour3, "y" => $Dye_PowerUsage_hour);
    $Dye_SteamUsage_hour_array[] = array("name" => $hour3, "y" => $Dye_SteamUsage_hour);
    $Dye_WaterUsage_hour_array[] = array("name" => $hour3, "y" => $Dye_WaterUsage_hour);



    $Finish_PowerUsage_hour_array[] = array("name" => $hour3, "y" => $Finish_PowerUsage_hour);
    $Finish_SteamUsage_hour_array[] = array("name" => $hour3, "y" => $Finish_SteamUsage_hour);
    $Finish_WaterUsage_hour_array[] = array("name" => $hour3, "y" => $Finish_WaterUsage_hour);
}

if ($Dye_hour_unit_carry == 1) {
    for ($i = 0; $i < 24; $i++) {
        $Dye_PowerUsage_hour_array[$i]['y'] = $Dye_PowerUsage_hour_array[$i]['y'] / 1000;
        $Dye_SteamUsage_hour_array[$i]['y'] = $Dye_SteamUsage_hour_array[$i]['y'] / 1000;
        $Dye_WaterUsage_hour_array[$i]['y'] = $Dye_WaterUsage_hour_array[$i]['y'] / 1000;
    }
    $Dye_hour_unit[0] = array("WaterUsage_hour_unit" => 'T', "SteamUsage_hour_unit" => 'T', "PowerUsage_hour_unit" => 'MW');
} else {
    $Dye_hour_unit[0] = array("WaterUsage_hour_unit" => 'L', "SteamUsage_hour_unit" => 'KG', "PowerUsage_hour_unit" => 'KW');
}

if ($Finish_hour_unit_carry == 1) {
    for ($i = 0; $i < 24; $i++) {
        $Finish_PowerUsage_hour_array[$i]['y'] = $Finish_PowerUsage_hour_array[$i]['y'] / 1000;
        $Finish_SteamUsage_hour_array[$i]['y'] = $Finish_SteamUsage_hour_array[$i]['y'] / 1000;
        $Finish_WaterUsage_hour_array[$i]['y'] = $Finish_WaterUsage_hour_array[$i]['y'] / 1000;
    }
    $Finish_hour_unit[0] = array("WaterUsage_hour_unit" => 'T', "SteamUsage_hour_unit" => 'T', "PowerUsage_hour_unit" => 'MW');
} else {
    $Finish_hour_unit[0] = array("WaterUsage_hour_unit" => 'L', "SteamUsage_hour_unit" => 'KG', "PowerUsage_hour_unit" => 'KW');
}



$AllUsage_array[0] = array("name" => 'Dye_PowerUsage_hour', "data" => $Dye_PowerUsage_hour_array);
$AllUsage_array[1] = array("name" => 'Dye_SteamUsage_hour', "data" => $Dye_SteamUsage_hour_array);
$AllUsage_array[2] = array("name" => 'Dye_WaterUsage_hour', "data" => $Dye_WaterUsage_hour_array);

$AllUsage_array[3] = array("name" => 'Finish_PowerUsage_hour', "data" => $Finish_PowerUsage_hour_array);
$AllUsage_array[4] = array("name" => 'Finish_SteamUsage_hour', "data" => $Finish_SteamUsage_hour_array);
$AllUsage_array[5] = array("name" => 'Finish_WaterUsage_hour', "data" => $Finish_WaterUsage_hour_array);

$AllUsage_array[6] = array("hour" => $Dye_hour_unit);
$AllUsage_array[7] = array("hour" => $Finish_hour_unit);
echo json_encode($AllUsage_array);

?>
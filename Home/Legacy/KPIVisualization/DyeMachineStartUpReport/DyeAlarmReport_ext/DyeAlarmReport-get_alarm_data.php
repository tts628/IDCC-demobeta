<?php
ini_set('display_errors', '1');
var_dump("???");
$time_start = microtime(true);
$execTime = 0;
function Writecsv($filename, $data){
    //Write to local csv
    $f = fopen($filename,'w');
    if($f === false){
        die('Error writing the file ' . $filename);
    }
    foreach ($data as $row) {
        fputcsv($f, $row);
    }
    fclose($f);
}
function Readcsv($filename){
    //fetching data from local csv
    $f = fopen($filename, 'r');
    if ($f === false) {
        die('Cannot raed the file ' . $filename);
    }
    while (($row = fgetcsv($f)) !== false) {
        $readData[] = $row;
    }
    fclose($f);
    if(!isset($readData)){
        $readData = [[0]];
    }
    return $readData;
}


date_default_timezone_set('UTC');

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

//get machine name
$PreData = Readcsv("DyeAlarmReport_all_data.csv");
foreach($PreData as $key => $element){
    $Machines[] = ($PreData[$key])[0];
    ($AllData[$key])[0] = ($PreData[$key])[0];
    ($AllData[$key])[1] = "";
    ($AllData[$key])[2] = ($PreData[$key])[1];
    ($AllData[$key])[3] = ($PreData[$key])[2];
    ($AllData[$key])[4] = ($PreData[$key])[3];
    ($AllData[$key])[5] = ($PreData[$key])[4];
    ($AllData[$key])[6] = "";
    ($AllData[$key])[7] = "";
    ($AllData[$key])[8] = ($PreData[$key])[5];
    ($AllData[$key])[9] = ($PreData[$key])[6];
    ($AllData[$key])[10] = "";
}
if(!file_exists("DyeAlarmReport_final_data.csv")){
    Writecsv("DyeAlarmReport_final_data.csv",$AllData);
}
$FinalData = Readcsv("DyeAlarmReport_final_data.csv");
foreach($FinalData as $key => $element){
    if(($FinalData[$key])[6] != ""){
        ($AllData[$key])[1] = ($FinalData[$key])[1];
        ($AllData[$key])[6] = ($FinalData[$key])[6];
        ($AllData[$key])[7] = ($FinalData[$key])[7];
        ($AllData[$key])[10] = ($FinalData[$key])[10];
    }
}
$time1 = microtime(true);
printf("1st database connection(csv file) execution time: %f<br><br>",$time1 - $time_start);
$execTime += $time1 - $time_start;

//get valid alarm data from database
foreach($Machines as $Machine){
    $sql = "SELECT TOP (1) * FROM dbo.DyeEvent WHERE Machine = '$Machine' AND IdccEventType = '1' OR IdccEventType = '2' ORDER BY FinishDate DESC";
    $stmt = sqlsrv_query( $link, $sql );
    while(($rows = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))){
        $AlarmDatas[] = [
            $rows["Machine"],
            $rows["Description"],
            $rows["CreateDate"]->format('Y-m-d H:i:s'),
            $rows["IdccEventType"]
        ];
    }
}
$time2 = microtime(true);
printf("2nd database connection execution time: %f<br><br>",$time2 - $time1);
$execTime += $time2 - $time1;

//data process
$AlarmCount = 0;
$i = 0;
$j = 0;
if($i < sizeof($AllData)){
    if($j < sizeof($AlarmDatas)){
        if($AllData[$i][0] == $AlarmDatas[$j][0]){
            //when no alarm data is inherited or changed, get new alarm data from database
            if($AllData[$i][1] != $AlarmDatas[$j][1]){
                $AllData[$i][1] = $AlarmDatas[$j][1];
                $AllData[$i][6] = date("m-d H:i",strtotime($AlarmDatas[$j][2]));
                $AllData[$i][7] = "1分鐘";
                $AllData[$i][10] = $AlarmDatas[$j][2];
            }
            //when alarm data is inherited and unchanged, continue the duration counter
            else{
                $AllData[$i][7] = ceil((strtotime(date("Y-m-d H:i:s")) - strtotime($AllData[$i][10]))/60);
            }
            $AlarmCount += 1;
            $i++;
            $j++;
        }
        else{
            $AllData[$i][1] = "";
            $AllData[$i][6] = "";
            $AllData[$i][7] = "";
            $AllData[$i][10] = "";
            $i++;
        }
    }
}
$AllData[0][11] = $AlarmCount;
Writecsv("DyeAlarmReport_final_data.csv",$AllData);
file_put_contents("DyeAlarmReport_final_data.json", json_encode($AllData));
$Update_flag = Readcsv("DyeAlarmReport_update_flag.csv");
($Update_flag[0])[0] = substr((string)($Update_flag[0][0]), 0, -1) . "2";
Writecsv("DyeAlarmReport_update_flag.csv",$Update_flag);
$time3 = microtime(true);
//printf("3rd database connection execution time: %f<br><br>",$time3 - $time2);
$execTime += $time3 - $time2;

//Time Log
$MaxTime = $time1 - $time_start;
$MaxPart = "Part 1";
if($MaxTime < $time2 - $time1){
    $MaxTime = $time2 - $time1;
    $MaxPart = "Part 2";
}
if($MaxTime < $time3 - $time2){
    $MaxTime = $time3 - $time2;
    $MaxPart = "Part 3";
}
$Alog = Readcsv("DyeAlarmReport_AlarmTimeLog.csv");
//for time efficient check
$time_end = microtime(true);
$Alog[0][0] = "History Max Time";
if($time_end - $time_start > ($Alog[0])[1]){
    ($Alog[0])[1] = (float)($time_end - $time_start);
}
$Alog[1] = ["Total Execution Time",$time_end - $time_start];
$Alog[2] = ["Max Execution Part","Max Time Used Part: " . $MaxPart,"Max Time Used: " . $MaxTime];
Writecsv("DyeAlarmReport_AlarmTimeLog.csv",$Alog);
file_put_contents("DyeAlarmReport_AlarmTimeLog.json", json_encode($Alog));
//printf("<br><br>Expected time consume: %f<br>",$execTime);
printf("<br><br>Took %f seconds for execution<br>", $time_end - $time_start);
?>
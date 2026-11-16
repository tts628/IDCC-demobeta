<?php
$time_start = microtime(true);
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

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

$sql = "SELECT * FROM dbo.DyeMachines ORDER BY Name ASC";
$stmt = sqlsrv_query( $link, $sql );
while(($rows = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))){
    $data[] = array(
        $rows["Name"],
        $rows["Dyelot"],
        (string)$rows["MainTemperature"],
        $rows["Program"],
        $rows["Step"],
        $rows["Capacity"],
        $rows["State"]
    );
}

/*
$alldata = Readcsv("DyeAlarmReport_all_data.csv");
foreach($data as $key => $element){
    print_r($alldata[$key]);
    echo "<br>";
    ($alldata[$key])[0] = $element[0];
    ($alldata[$key])[2] = $element[1];
    ($alldata[$key])[3] = $element[2];
    ($alldata[$key])[4] = $element[3];
    ($alldata[$key])[5] = $element[4];
    ($alldata[$key])[8] = $element[5];
    ($alldata[$key])[9] = $element[6];
}
$alldata = array();
foreach($data as $key => $element){
    ($alldata[$key])[0] = $element[0];
    ($alldata[$key])[1] = "";
    ($alldata[$key])[2] = $element[1];
    ($alldata[$key])[3] = $element[2];
    ($alldata[$key])[4] = $element[3];
    ($alldata[$key])[5] = $element[4];
    ($alldata[$key])[6] = "";
    ($alldata[$key])[7] = "";
    ($alldata[$key])[8] = $element[5];
    ($alldata[$key])[9] = $element[6];
}
($alldata[0])[10] = "";
($alldata[0])[11] = "";*/
Writecsv("DyeAlarmReport_all_data.csv",$data);
$Update_flag = Readcsv("DyeAlarmReport_update_flag.csv");
($Update_flag[0])[0] = "2" . substr((string)($Update_flag[0][0]), -1);
Writecsv("DyeAlarmReport_update_flag.csv",$Update_flag);

$time_end = microtime(true);
$execTime = $time_end - $time_start;
//for time efficient check
//printf("Took %f seconds for execution<br>", $time_end - $time_start);
Writecsv("DyeAlarmReport-TimeLog.csv",[[$execTime]]);
file_put_contents("DyeAlarmReport-TimeLog.json", json_encode($execTime));

$Maxlog = Readcsv("DyeAlarmReport-MaxTimeLog.csv");
if($execTime > (float)($Maxlog[0])[1]){
    Writecsv("DyeAlarmReport-MaxTimeLog.csv",array(["Max Execution Time",$execTime]));
    file_put_contents("DyeAlarmReport-MaxTimeLog.json", json_encode(array(["Max Execution Time",$execTime])));
}
?>
<?php
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
$flag = Readcsv("DyeAlarmReport_update_flag.csv");
if(isset($_REQUEST["reset"])){
    if($_REQUEST["reset"] == 0){
        $flag[0][0] = "1" . substr((string)$flag[0][0], -1);
    }
    else{
        $flag[0][0] = substr((string)$flag[0][0], 0, -1) . "1";
    }
    Writecsv("DyeAlarmReport_update_flag.csv",$flag);
}
echo ($flag[0])[0];
?>
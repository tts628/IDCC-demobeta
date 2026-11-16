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
        die('Cannot read the file ' . $filename);
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
$flag = Readcsv("./DyelotOverview_check_update.csv");
if(isset($_REQUEST["reset"])){
    $flag[0][0] = 0;
    Writecsv("./DyelotOverview_check_update.csv",$flag);
}
echo ($flag[0])[0];
?>
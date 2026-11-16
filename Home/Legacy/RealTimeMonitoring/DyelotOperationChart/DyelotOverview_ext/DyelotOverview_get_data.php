<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

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

$sql = "SELECT * FROM dbo.DyeMachines ORDER BY Name";
$stmt = sqlsrv_query( $link, $sql );
while(($rows = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))){
    if($rows["Dyelot"] == ""){
        $data[] = [
            $rows["Name"],
            substr((string)$rows["MainTemperature"],0,-1) . "." . substr((string)$rows["MainTemperature"],-1) . "°C",
            ""
        ];
    }
    else{
        if($rows['Color']==''){
            $color='#ffffff';
            $b = '255';
            $g = '255';
            $r = '255';
        }
        else{
            $rgb=[];
            $r = $rows['Color'] & 0xff;
            $g = ($rows['Color'] >> 8) & 0xff;
            $b = ($rows['Color'] >> 16) & 0xff;
            $rgb[0]=$r;
            $rgb[1]=$g;
            $rgb[2]=$b;
            $r1=strval($r);
            $g1=strval($g);
            $b1=strval($b);
            $r2 = dechex($r);
            $g2 = dechex($g);
            $b2 = dechex($b);
            if(strlen($r2)==1){
                $r3='0'.$r2;
            }
            else{
                $r3=$r2;
            }
            if(strlen($g2)==1){
                $g3='0'.$g2;
            }
            else{
                $g3=$g2;
            }
            if(strlen($b2)==1){
                $b3='0'.$b2;
            }
            else{
                $b3=$b2;
            }
            $color='#'.$r3.$g3.$b3;
        }
        //文字顏色
        $textrgb = $r*0.299 +$g*0.578 +$b*0.114;
        if($textrgb>=192){
            $textcolor = '#000000';
        }
        else{
            $textcolor = '#ffffff';
        }



        // 設下一工單背景顏色跟文字顏色
        if($rows['Color1']==''){
            $next_color='rgba(0, 0, 0, 0)';
            $next_b = '255';
            $next_g = '255';
            $next_r = '255';
        }
        else{
            $next_rgb=[];
            $next_r = $rows['Color1'] & 0xff;
            $next_g = ($rows['Color1'] >> 8) & 0xff;
            $next_b = ($rows['Color1'] >> 16) & 0xff;
            $next_rgb[0]=$next_r;
            $next_rgb[1]=$next_g;
            $next_rgb[2]=$next_b;
            $next_r1=strval($next_r);
            $next_g1=strval($next_g);
            $next_b1=strval($next_b);
            $next_r2 = dechex($next_r);
            $next_g2 = dechex($next_g);
            $next_b2 = dechex($next_b);
            if(strlen($next_r2)==1){
                $next_r3='0'.$next_r2;
            }
            else{
                $next_r3=$next_r2;
            }
            if(strlen($g2)==1){
                $next_g3='0'.$next_g2;
            }
            else{
                $next_g3=$next_g2;
            }
            if(strlen($b2)==1){
                $next_b3='0'.$next_b2;
            }
            else{
                $next_b3=$next_b2;
            }
            $next_color='#'.$next_r3.$next_g3.$next_b3;
        }
        //文字顏色
        $next_textrgb = $next_r*0.299 +$next_g*0.578 +$next_b*0.114;
        if($rows['Color1']==''){
            $next_textcolor = '#ffffff';
        }
        else{
            if($next_textrgb>=192){
                $next_textcolor = '#000000';
            }
            else{
                $next_textcolor = '#ffffff';
            }
        }
        
        // 下一工單背景顏色設定完成

        $rows["DyelotStartTime"]->modify('+7 hours');
        $rows["DyelotEndTime"]->modify('+7 hours');
        $rows["Dyelot1StartTime"]->modify('+7 hours');
        $rows["Dyelot1EndTime"]->modify('+7 hours');

        $data[] = [
            $rows["Name"],
            substr((string)$rows["MainTemperature"],0,-1) . "." . substr((string)$rows["MainTemperature"],-1) . "°C",
            $rows["Program"] ?? '',
            $rows["Step"] ?? '',
            $rows["ActiveAlarms"] ?? '',
            $rows["DyelotEndTime"]->format('Y-m-d H:i:s'),
            // $rows["DyelotEndTime"]->modify('+7 hours')->format('Y-m-d H:i:s'),
            $rows["Dyelot"],
            $color,
            $rows["DyelotProgramUsed"] ?? '',
            $rows["DyelotStartTime"]->format('m-d H:i'),
            // $rows["DyelotStartTime"]->modify('+7 hours')->format('Y-m-d H:i'),

            $rows["DyelotEndTime"]->format('m-d H:i'),
            // $rows["DyelotEndTime"]->modify('+7 hours')->format('Y-m-d H:i'),

            $rows["Dyelot1"] ?? '',
            $next_color ?? '',
            $rows["Dyelot1ProgramUsed"] ?? '',
            $rows["Dyelot1StartTime"]->format('m-d H:i'),
            // $rows["Dyelot1StartTime"]->modify('+7 hours')->format('Y-m-d H:i'),

            $rows["Dyelot1EndTime"]->format('m-d H:i'),
            // $rows["Dyelot1EndTime"]->modify('+7 hours')->format('Y-m-d H:i'),

            $textcolor,
            $next_textcolor
        ];
    }
}
var_dump($data);
Writecsv("DyelotOverview_data.csv",$data);
Writecsv("DyelotOverview_check_update.csv",[[1]]);
?>
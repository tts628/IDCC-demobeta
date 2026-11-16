<?php
//  ini_set('display_errors', '1');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', 'ReturnDatesAsStrings']);

// 前20天用電量
$sql = "  SELECT [Name],
[Dyelot],
[Color],
[Step],
[ActiveAlarms]

FROM [DyeMachines]
";


$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}



//  $data_Name= array();
// $data_MainSpeed= array();
// $data_Width= array();
// $data_OverFeed= array();
// $data_Job1= array();
// $data_Job2= array();
$Name = array();
$Dyelot = array();
$Color = array();
$Step = array();
$ActiveAlarms = array();
$textcolor= array();
// $data_Temperature5 = array();
// $data_Time = array();



while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {




    // $data_MainSpeed = ($row['MainSpeed']);
    // $data_Width = ($row['Width']);
    // $data_OverFeed = ($row['OverFeed']);
    // $data_Job1 = ($row['Job1']);
    // $data_Job2 = ($row['Job2']);
    // $data_Temperature1[] = array($row['Temperature1']);
    // $data_Temperature2[] = array($row['Temperature2']);
    // $data_Temperature3[] = array($row['Temperature3']);
    // $data_Temperature4[] = array($row['Temperature4']);
    // $data_Temperature5[] = array($row['Temperature5']);
    // $data_Time[] = array($row['Time']);

    $Name  [] = array($row['Name']);
    $Dyelot[] = array($row['Dyelot']);
    // $Color [] = array($row['Color']);
    $Step  [] = array($row['Step']);
    $ActiveAlarms[]=array($row["ActiveAlarms"]);
    if($row['Color']==''){
        $Color[]='#ffffff';
        $b = '255';
        $g = '255';
        $r = '255';
    }
    else{
        $rgb=[];
        $r = $row['Color'] & 0xff;
        $g = ($row['Color'] >> 8) & 0xff;
        $b = ($row['Color'] >> 16) & 0xff;
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
        $Color[]='#'.$r3.$g3.$b3;
    }
    //文字顏色
    $textrgb = $r*0.299 +$g*0.578 +$b*0.114;
    if($textrgb>=192){
        $textcolor[] = '#000000';
    }
    else{
        $textcolor[] = '#ffffff';
    }
   
    
}
// $Color[] = array($textcolor);

// $lastTemperature1 = end($data_Temperature1);
// $lastTemperature2 = end($data_Temperature2);
// $lastTemperature3 = end($data_Temperature3);
// $lastTemperature4 = end($data_Temperature4);
// $lastTemperature5 = end($data_Temperature5);


sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

// $jsonData_Temperature1 = json_encode($lastTemperature1);
// $jsonData_Temperature2 = json_encode($lastTemperature2);
// $jsonData_Temperature3 = json_encode($lastTemperature3);
// $jsonData_Temperature4 = json_encode($lastTemperature4);
// $jsonData_Temperature5 = json_encode($lastTemperature5);
// $jsonData_Time = json_encode($data_Time);


// echo "page called";
// echo $_POST["SelectedDate"];
// echo $_POST["data"];







$array = [
    // "data_MainSpeed" => $data_MainSpeed,
    // "data_Width" => $data_Width,
    // "data_OverFeed" => $data_OverFeed,
    // "data_Job1" => $data_Job1,
    // "data_Job2" => $data_Job2,
    // "data_Time" => $data_Time,
    // "lastTemperature1" => $lastTemperature1,
    // "lastTemperature2" => $lastTemperature2,
    // "lastTemperature3" => $lastTemperature3,
    // "lastTemperature4" => $lastTemperature4,
    // "lastTemperature5" => $lastTemperature5
    "Name"   =>  $Name  ,
    "Dyelot" =>  $Dyelot,
    "Color"  =>  $Color ,
    "Step"   =>  $Step  ,
    "ActiveAlarms" => $ActiveAlarms,
    "machine_num" =>count($Name),
    "textcolor"=>$textcolor
    
    

];
    echo json_encode($array);
    // var_dump($array);
    // echo "updated";
    // echo $_POST["data"];

?>


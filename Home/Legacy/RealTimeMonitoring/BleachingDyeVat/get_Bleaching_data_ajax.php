<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC']);

$MMMM=$_POST['month'];

// 前20天用電量
$sql = "  SELECT
A.Machine as 'Machine',
B.Machine as 'Machine2',
A.DaysUntilEnd,
A.EndTime,
CONVERT(VARCHAR, A.EndTime, 120) as 'lasttime',
COALESCE(B.times, 0) AS 'times'
FROM
(


SELECT 
Machine,
EndTime,
DATEDIFF(DAY, EndTime,GETDATE() ) AS DaysUntilEnd
FROM	
(SELECT
    *,
    ROW_NUMBER() OVER (PARTITION BY Machine ORDER BY EndTime DESC) AS 'machine_now'
FROM
     [BleachingDyelots]
     )TAB

WHERE
machine_now = 1
) A
left JOIN
(

SELECT
    Machine
    ,count(CONVERT(VARCHAR, EndTime, 120)) as 'times'
FROM
     [BleachingDyelots]
where CONVERT(VARCHAR, EndTime, 120) like '$MMMM%'
group by Machine
) B
ON
A.Machine = B.Machine


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
    $lasttime [] = array($row['lasttime']);
    $washtime [] = array($row['times']);
    
    $Name  [] = array($row['Machine']);
    
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
   
    


    $ccc = $row['DaysUntilEnd'];

// 根据ccc的值设置颜色
if ($ccc == 0) {
    $color = 'rgb(255, 255, 255)'; // 白色
} elseif ($ccc == $_POST['days']) {
    $color = 'rgb(0, 0, 0)'; // 黑色
} else {
    // 计算灰度值，这里假设ccc的取值范围在1到14之间
    $grayValue = round(($ccc - 1) / $_POST['days'] * 255);
    $invertedGrayValue = 255 - $grayValue; // 反转灰度值
    $color = "rgb($invertedGrayValue, $invertedGrayValue, $invertedGrayValue)";
}

$setcolor [] = array($color);

    
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
    // "Name"   =>  $Name  ,
    // "Dyelot" =>  $Dyelot,
    // "Color"  =>  $Color ,
    // "Step"   =>  $Step  ,
    // "ActiveAlarms" => $ActiveAlarms,
    // "machine_num" =>count($Name),
    // "textcolor"=>$textcolor
    "Name"   =>  $Name  ,
    "Dyelot" =>  $Dyelot,
    "Color"  =>  $Color ,
    "Step"   =>  $Step  ,
    "ActiveAlarms" => $ActiveAlarms,
    "machine_num" =>count($Name),
    "textcolor"=>$textcolor,
    "lasttime"   =>  $lasttime  ,
    "washtime"   =>  $washtime  ,
    "setcolor"   =>  $setcolor  ,
    

];
    echo json_encode($array);
    // var_dump($array);
    // echo "updated";
    // echo $_POST["data"];

?>


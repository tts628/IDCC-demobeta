<?php
ini_set('display_errors','1');
error_reporting(E_ALL);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

$machine_name=$_POST['machine_name'];
$factory_name=$_POST['factory_name'];
if($factory_name=='DyeMachines'){
    $factory='DyeEvent';
}else if($factory_name=='FinishMachines'){
    $factory='FinishEvent';
}else if($factory_name=='DispenserMachines'){
    $factory='DispenserEvent';
}else if($factory_name=='LabMachines'){
    $factory='LabEvent';
}

if(stripos($machine_name, " OR ") !== false || 
stripos($machine_name, " ") !== false || 
stripos($machine_name, "%") !== false || 
stripos($machine_name, "'") !== false || 
stripos($machine_name, "{") !== false || 
stripos($machine_name, "}") !== false || 
stripos($machine_name, "[") !== false || 
stripos($machine_name, "]") !== false || 
stripos($machine_name, "(") !== false || 
stripos($machine_name, ")") !== false || 
stripos($machine_name, "<") !== false || 
stripos($machine_name, ">") !== false || 
stripos($machine_name, "#") !== false || 
stripos($machine_name, "!") !== false || 
stripos($machine_name, "|") !== false || 
stripos($machine_name, "&") !== false || 
stripos($machine_name, ";") !== false || 
stripos($machine_name, ":") !== false || 
stripos($machine_name, "$") !== false || 
stripos($machine_name, "*") !== false || 
stripos($machine_name, "=") !== false || 
stripos($machine_name, ",") !== false || 
stripos($machine_name, ".") !== false || 
strlen($machine_name) > 15){
    echo "detected sensitive cases<br>";
    $machine_name = null;
}
if(stripos($factory_name, " OR ") !== false || 
stripos($factory_name, " ") !== false || 
stripos($factory_name, "%") !== false || 
stripos($factory_name, "'") !== false || 
stripos($factory_name, "{") !== false || 
stripos($factory_name, "}") !== false || 
stripos($factory_name, "[") !== false || 
stripos($factory_name, "]") !== false || 
stripos($factory_name, "(") !== false || 
stripos($factory_name, ")") !== false || 
stripos($factory_name, "<") !== false || 
stripos($factory_name, ">") !== false || 
stripos($factory_name, "#") !== false || 
stripos($factory_name, "!") !== false || 
stripos($factory_name, "|") !== false || 
stripos($factory_name, "&") !== false || 
stripos($factory_name, ";") !== false || 
stripos($factory_name, ":") !== false || 
stripos($factory_name, "$") !== false || 
stripos($factory_name, "*") !== false || 
stripos($factory_name, "=") !== false || 
stripos($factory_name, ",") !== false || 
stripos($factory_name, ".") !== false || 
strlen($factory_name) > 15){
    echo "detected sensitive cases<br>";
    $factory_name = null;
}
//$today=$_POST['day'];
//$hour=date('H');
//$today_H=date('Y-m-d',strtotime($today));
//$today1_H=$today_H.' '. $hour .':00:00.000';
//$today2_H=date('Y-m-d H',strtotime('+1 hour',strtotime($today1_H)));
//$today2_H=$today2_H . ':00:00.000';

////抓取當日(小時) 每$i:59:59.999的資料
//$alarm_hour_array = array();
//$alarm_hour = 0;
//$alarm_type = "";
////    for ($i = 0; $i < 24; $i++) {
//
////        if ($i < 10) {
////            $hour1 = $today . ' 0' . $i . ':00:00.000';
////            $hour2 = $today . ' 0' . $i . ':59:59.999';
////        } else {
////            $hour1 = $today . ' ' . $i . ':00:00.000';
////            $hour2 = $today . ' ' . $i . ':59:59.999';
////        }
////echo $today;
////echo $today1_H;
////echo $today2_H;
//
//        if ($machine_name == 'Allmachine') {
//            $sql = "select Top 10 Description ,count(*) as count from $factory WHERE IdccEventType=1 AND CreateDate>='$today1_H' AND CreateDate<='$today2_H' group by Description order by count DESC";
//        } else {
//            $sql = "select Top 10 Description ,count(*) as count from $factory WHERE IdccEventType=1 AND Machine='$machine_name' AND CreateDate>='$today1_H' AND CreateDate<='$today2_H' group by Description order by count DESC";
//        }
//        $result = sqlsrv_query($link, $sql) or die("sql error" . sqlsrv_errors());
//        while ($row = sqlsrv_fetch_array($result)) {
//            $alarm_hour = ($row['count']);
//            $alarm_type = $row['Description'];
//
//            $alarm_hour_array[] = array("name" => $alarm_type, "y" => $alarm_hour);
////            $hour3 = date('H', strtotime('+1 hour', strtotime($hour1)));
//        }


//抓取當周(天) 每$day.' 23:59:59.999的資料
$day_name=['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat','Sun'];
$alarm_day_array=array();
$today=$_POST['day'];
$today_D=date('Y-m-d',strtotime($today));
$today1_D=$today_D . ' 00:00:00.000';
//$today2_D=date('Y-m-d',strtotime('+0 day',strtotime($today)));
$today2_D=$today_D . ' 23:59:59.999';
//$day=date("Y-m-d", strtotime('sunday last week', strtotime($today)));//取得這周星期一
//for($i=1;$i<=7;$i++){
    $alarm_day = 0;
//    $day1=$day.' 00:00:00.000';//設定小時
//    $day2=$day.' 23:59:59.999';//設定小時
//echo $today;
//echo $today1_D;
//echo $today2_D;
    if($machine_name=='Allmachine'){
        $sql="select Top 10 Description ,count(*) as count ,sum(DATEDIFF(minute,CreateDate, FinishDate)) as time from $factory WHERE IdccEventType=1 AND CreateDate>='$today1_D' AND CreateDate<='$today2_D' group by Description order by time DESC";
    }else{
        $sql="select Top 10 Description ,count(*) as count ,sum(DATEDIFF(minute,CreateDate, FinishDate)) as time from $factory WHERE IdccEventType=1 AND Machine='$machine_name' AND CreateDate>='$today1_D' AND CreateDate<='$today2_D' group by Description order by time DESC";
    }
    $result=sqlsrv_query($link,$sql)or die("sql error".sqlsrv_errors());
    while($row=sqlsrv_fetch_array($result)){
        $alarm_day = $row['time'];
        $alarm_type = $row['Description'];
        $alarm_day_array[]=array("name"=>$alarm_type,"y"=>$alarm_day);
    }

//    $day=date('Y-m-d',strtotime('+1 day', strtotime($day)));//加一天
//}

////抓取當月(周) 每$month_start.' 23:59:59.999的資料
$alarm_week_array=array();
$today=$_POST['day'];
$month_start=date("Y-m-d", strtotime('first day of this month', strtotime($today)));//取得這月第一天
$month_end = date("Y-m-d",strtotime('last day of this month', strtotime($today)));//取得這月最後一天
$end_date=date("d",(strtotime($month_end) - strtotime($month_start)));//這月有幾天
$alarm_week = 0;
//$j=1;
//for($i=1;$i<=$end_date;$i++){
//    $ff=getdate(strtotime($month_start));
    $week1=$month_start.' 00:00:00.000';//設定小時
    $week2=$month_end.' 23:59:59.999';//設定小時
//echo $week1;
//echo $week2;

    if($machine_name=='Allmachine'){
        $sql="select Top 10 Description ,count(*) as count ,sum(DATEDIFF(minute,CreateDate, FinishDate)) as time from $factory WHERE IdccEventType=1 AND CreateDate>='$week1' AND CreateDate<='$week2' group by Description order by time DESC";
    }else{
        $sql="select Top 10 Description ,count(*) as count ,sum(DATEDIFF(minute,CreateDate, FinishDate)) as time from $factory WHERE IdccEventType=1 AND Machine='$machine_name' AND CreateDate>='$week1' AND CreateDate<='$week2' group by Description order by time DESC";
    }
    $result=sqlsrv_query($link,$sql)or die("sql error".sqlsrv_errors());
    while($row=sqlsrv_fetch_array($result)){
        $alarm_week = $row['time'];
        $alarm_type = $row['Description'];
        $alarm_week_array[]=array("name"=>$alarm_type,"y"=>$alarm_week);
    }

//
//    $month_start=date('Y-m-d',strtotime('+1 day', strtotime($month_start)));//加一天
//}
//
//抓取當年(月) 每$month_start.' 23:59:59.999的資料
$alarm_month_array=array();
$today=$_POST['day'];
$month=date("Y",strtotime($today));//取得這周星期一
$month1=$month.'-01-01 00:00:00.000';
$month2=$month.'-12-31 23:59:59.999';
//for($i=1;$i<13;$i++){
    $alarm_month = 0;
//    echo $month1;
//    echo $month2;
//    if($i<10){
//        $month1=$month.'-0'.$i.'-01'.' 00:00:00.000';
//    }else{
//        $month1=$month.'-'.$i.'-01'.' 00:00:00.000';
//    }
//    $month2=date("Y-m-d",strtotime('last day of this month', strtotime($month1)));//取得這月最後一天
//    $month2=$month2.' 23:59:59.999';
    if($machine_name=='Allmachine'){
        $sql="select Top 10 Description ,count(*) as count ,sum(DATEDIFF(minute,CreateDate, FinishDate)) as time from $factory WHERE IdccEventType=1 AND CreateDate>='$month1' AND CreateDate<='$month2'group by Description order by time DESC";
    }else{
        $sql="select Top 10 Description ,count(*) as count ,sum(DATEDIFF(minute,CreateDate, FinishDate)) as time from $factory WHERE IdccEventType=1 AND Machine='$machine_name' AND CreateDate>='$month1' AND CreateDate<='$month2' group by Description order by time DESC";
    }
    $result=sqlsrv_query($link,$sql)or die("sql error".sqlsrv_errors());
    while($row=sqlsrv_fetch_array($result)){
        $alarm_month = $row['time'];
        $alarm_type = $row['Description'];
        $alarm_month_array[]=array("name"=>$alarm_type,"y"=>$alarm_month);
    }
//    $month3=date("Y-m",strtotime($month2));

//}

//    $Allalarm_array[0]=array("name"=>'alarm_hour',"data"=> $alarm_hour_array);
    $Allalarm_array[0]=array("name"=>'alarm_day',"data"=>$alarm_day_array);
    $Allalarm_array[1]=array("name"=>'alarm_week',"data"=>$alarm_week_array);
    $Allalarm_array[2]=array("name"=>'alarm_month',"data"=>$alarm_month_array);

echo json_encode($Allalarm_array);
?>

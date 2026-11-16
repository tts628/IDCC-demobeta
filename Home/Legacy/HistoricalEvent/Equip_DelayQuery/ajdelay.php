<?php
ini_set('display_errors','1');
error_reporting(E_ALL);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

$factory='';
$machine_name=$_POST['machine_name'];
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

$today=$_POST['day'];
if(stripos($today, " OR ") !== false){
    echo "detected sensitive cases<br>";
    $today = null;
}
$hour=date('H');
$today_H=date('Y-m-d',strtotime($today));
$today1_H=$today_H.' '. $hour . ':00:00.000';
$today2_H=date('Y-m-d H',strtotime('+1 hour',strtotime($today1_H)));
$today2_H=$today2_H . ':00:00.000';
$delay_hour_array = array();
$delay_hour = 0;
$delay_type = "";
//echo $today;
//echo $hour ;
//echo $today1_H ;
//echo $today2_H ;
if ($machine_name == 'Allmachine') {
    $sql = "select Top 10 Description ,count(*) as count from $factory WHERE IdccEventType=2 AND CreateDate>='$today1_H' AND CreateDate<='$today2_H' group by Description order by count DESC";
} else {
    $sql = "select Top 10 Description ,count(*) as count from $factory WHERE IdccEventType=2 AND Machine='$machine_name' AND CreateDate>='$today1_H' AND CreateDate<='$today2_H' group by Description order by count DESC";
}
$result = sqlsrv_query($link, $sql) or die("sql error" . sqlsrv_errors());


//echo $machine_name ;
//echo $factory;
//echo $sql ;
while ($row = sqlsrv_fetch_array($result)) {
    $delay_hour = ($row['count']);
    $delay_type = $row['Description'];

    $delay_hour_array[] = array("name" => $delay_type, "y" => $delay_hour);
}

$delay_day_array=array();
$today=$_POST['day'];
$today_D=date('Y-m-d',strtotime($today));
$today1_D=$today_D . ' 00:00:00.000';
$today2_D=$today_D . ' 23:59:59.999';
$delay_day = 0;
//echo $today1_D ;
//echo $today2_D ;
if($machine_name=='Allmachine'){
    $sql="select Top 10 Description ,count(*) as count from $factory WHERE IdccEventType=2 AND CreateDate>='$today1_D' AND CreateDate<='$today2_D' group by Description order by count DESC";
}else{
    $sql="select Top 10 Description ,count(*) as count from $factory WHERE IdccEventType=2 AND Machine='$machine_name' AND CreateDate>='$today1_D' AND CreateDate<='$today2_D' group by Description order by count DESC";
}
$result=sqlsrv_query($link,$sql)or die("sql error".sqlsrv_errors());
while($row=sqlsrv_fetch_array($result)){
    $delay_day = $row['count'];
    $delay_type = $row['Description'];
    $delay_day_array[]=array("name"=>$delay_type,"y"=>$delay_day);
}

$delay_week_array=array();
$today=$_POST['day'];
$month_start=date("Y-m-d", strtotime('first day of this month', strtotime($today)));//取得這月第一天
$month_end = date("Y-m-d",strtotime('last day of this month', strtotime($today)));//取得這月最後一天
$end_date=date("d",(strtotime($month_end) - strtotime($month_start)));//這月有幾天
$delay_week = 0;
$week1=$month_start.' 00:00:00.000';//設定小時
$week2=$month_end.' 23:59:59.999';//設定小時
if($machine_name=='Allmachine'){
    $sql="select Top 10 Description ,count(*) as count, sum(DATEDIFF(minute,CreateDate, FinishDate)) as time from $factory WHERE IdccEventType=2 AND CreateDate>='$week1' AND CreateDate<='$week2' group by Description order by count DESC";
}else{
    $sql="select Top 10 Description ,count(*) as count, sum(DATEDIFF(minute,CreateDate, FinishDate)) as time from $factory WHERE IdccEventType=2 AND Machine='$machine_name' AND CreateDate>='$week1' AND CreateDate<='$week2' group by Description order by count DESC";
}
$result=sqlsrv_query($link,$sql)or die("sql error".sqlsrv_errors());
while($row=sqlsrv_fetch_array($result)){
    $delay_week = $row['count'];
    $delay_type = $row['Description'];
    $delay_week_array[]=array("name"=>$delay_type,"y"=>$delay_week);
}

$delay_month_array=array();
$today=$_POST['day'];
$month=date("Y",strtotime($today));//取得這周星期一
$month1=$month.'-01-01 00:00:00.000';
$month2=$month.'-12-31 23:59:59.999';
$delay_month = 0;
if($machine_name=='Allmachine'){
    $sql="select Top 10 Description ,count(*) as count , sum(DATEDIFF(minute,CreateDate, FinishDate)) as time from $factory WHERE IdccEventType=2 AND CreateDate>='$month1' AND CreateDate<='$month2'group by Description order by count DESC";
}else{
    $sql="select Top 10 Description ,count(*) as count , sum(DATEDIFF(minute,CreateDate, FinishDate)) as time from $factory WHERE IdccEventType=2 AND Machine='$machine_name' AND CreateDate>='$month1' AND CreateDate<='$month2' group by Description order by count DESC";
}
$result=sqlsrv_query($link,$sql)or die("sql error".sqlsrv_errors());
while($row=sqlsrv_fetch_array($result)) {
    $delay_month = $row['count'];
    $delay_type = $row['Description'];
    $delay_month_array[] = array("name" => $delay_type, "y" => $delay_month);
}


    $Alldelay_array[0]=array("name"=>'delay_day',"data"=>$delay_day_array);
    $Alldelay_array[1]=array("name"=>'delay_week',"data"=>$delay_week_array);
    $Alldelay_array[2]=array("name"=>'delay_month',"data"=>$delay_month_array);

    echo json_encode($Alldelay_array);
    ?>
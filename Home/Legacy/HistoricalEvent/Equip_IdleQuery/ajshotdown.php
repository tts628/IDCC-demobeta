<?php
ini_set('display_errors','1');
error_reporting(E_ALL);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

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

$shotdown_hour_array = array();
$shotdown_hour = 0;
$shotdown_type = "";
if ($machine_name == 'Allmachine') {
    $sql = "select Top 10 Description ,count(*) as count from $factory WHERE IdccEventType=3 AND CreateDate>='$today1_H' AND CreateDate<='$today2_H' group by Description order by count DESC";
} else {
    $sql = "select Top 10 Description ,count(*) as count from $factory WHERE IdccEventType=3 AND Machine='$machine_name' AND CreateDate>='$today1_H' AND CreateDate<='$today2_H' group by Description order by count DESC";
}
$result = sqlsrv_query($link, $sql) or die("sql error" . sqlsrv_errors());
while ($row = sqlsrv_fetch_array($result)) {
    $shotdown_hour = ($row['count']);
    $shotdown_type = $row['Description'];

    $shotdown_hour_array[] = array("name" => $shotdown_type, "y" => $shotdown_hour);
}

$shotdown_day_array=array();
$today=$_POST['day'];
$today_D=date('Y-m-d',strtotime($today));
$today1_D=$today_D . ' 00:00:00.000';
$today2_D=$today_D . ' 23:59:59.999';
$shotdown_day = 0;
if($machine_name=='Allmachine'){
    $sql="select Top 10 Description ,count(*) as count , sum(DATEDIFF(minute,CreateDate, FinishDate)) as time from $factory WHERE IdccEventType=3 AND CreateDate>='$today1_D' AND CreateDate<='$today2_D' group by Description order by time DESC";
}else{
    $sql="select Top 10 Description ,count(*) as count , sum(DATEDIFF(minute,CreateDate, FinishDate)) as time from $factory WHERE IdccEventType=3 AND Machine='$machine_name' AND CreateDate>='$today1_D' AND CreateDate<='$today2_D' group by Description order by time DESC";
}
$result=sqlsrv_query($link,$sql)or die("sql error".sqlsrv_errors());
while($row=sqlsrv_fetch_array($result)){
    $shotdown_day = $row['count'];
    $shotdown_type = $row['Description'];
    $shotdown_day_array[]=array("name"=>$shotdown_type,"y"=>$shotdown_day);
}

$shotdown_week_array=array();
$today=$_POST['day'];
$month_start=date("Y-m-d", strtotime('first day of this month', strtotime($today)));//取得這月第一天
$month_end = date("Y-m-d",strtotime('last day of this month', strtotime($today)));//取得這月最後一天
$end_date=date("d",(strtotime($month_end) - strtotime($month_start)));//這月有幾天
$shotdown_week = 0;
$week1=$month_start.' 00:00:00.000';//設定小時
$week2=$month_end.' 23:59:59.999';//設定小時
if($machine_name=='Allmachine'){
    $sql="select Top 10 Description ,count(*) as count , sum(DATEDIFF(minute,CreateDate, FinishDate)) as time from $factory WHERE IdccEventType=3 AND CreateDate>='$week1' AND CreateDate<='$week2' group by Description order by time DESC";
}else{
    $sql="select Top 10 Description ,count(*) as count , sum(DATEDIFF(minute,CreateDate, FinishDate)) as time from $factory WHERE IdccEventType=3 AND Machine='$machine_name' AND CreateDate>='$week1' AND CreateDate<='$week2' group by Description order by time DESC";
}
$result=sqlsrv_query($link,$sql)or die("sql error".sqlsrv_errors());
while($row=sqlsrv_fetch_array($result)){
    $shotdown_week = $row['count'];
    $shotdown_type = $row['Description'];
    $shotdown_week_array[]=array("name"=>$shotdown_type,"y"=>$shotdown_week);
}

$shotdown_month_array=array();
$today=$_POST['day'];
$month=date("Y",strtotime($today));//取得這周星期一
$month1=$month.'-01-01 00:00:00.000';
$month2=$month.'-12-31 23:59:59.999';
$shotdown_month = 0;
if($machine_name=='Allmachine'){
    $sql="select Top 10 Description ,count(*) as count , sum(DATEDIFF(minute,CreateDate, FinishDate)) as time from $factory WHERE IdccEventType=3 AND CreateDate>='$month1' AND CreateDate<='$month2'group by Description order by time DESC";
}else{
    $sql="select Top 10 Description ,count(*) as count , sum(DATEDIFF(minute,CreateDate, FinishDate)) as time from $factory WHERE IdccEventType=3 AND Machine='$machine_name' AND CreateDate>='$month1' AND CreateDate<='$month2' group by Description order by time DESC";
}
$result=sqlsrv_query($link,$sql)or die("sql error".sqlsrv_errors());
while($row=sqlsrv_fetch_array($result)) {
    $shotdown_month = $row['count'];
    $shotdown_type = $row['Description'];
    $shotdown_month_array[] = array("name" => $shotdown_type, "y" => $shotdown_month);
}


$Allshotdown_array[0]=array("name"=>'shotdown_day',"data"=>$shotdown_day_array);
$Allshotdown_array[1]=array("name"=>'shotdown_week',"data"=>$shotdown_week_array);
$Allshotdown_array[2]=array("name"=>'shotdown_month',"data"=>$shotdown_month_array);

echo json_encode($Allshotdown_array);
?>

<?php
$start=$_POST['start'];
$end=$_POST['end'];

$start_date = date('Y-m-d', strtotime($start . ' -10 day'));
$end_date = date('Y-m-d', strtotime($end . ' +10 day'));

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

$data[0]=array("id" => "F01","text" => "F01","start_date" => '',"type" => 'project',"render" => "split",);
$data[1]=array("id" => "F02","text" => "F02","start_date" => '',"type" => 'project',"render" => "split",);
$data[2]=array("id" => "F03","text" => "F03","start_date" => '',"type" => 'project',"render" => "split",);
$data[3]=array("id" => "F04","text" => "F04","start_date" => '',"type" => 'project',"render" => "split",);




$sql="SELECT * From [FinishProduction] WHERE StartTime BETWEEN '$start_date' AND '$end_date'";
$result=sqlsrv_query($link,$sql)or die("sql error:".sqlsrv_errors()); 
   while ($row=sqlsrv_fetch_array($result)) {
    $Date_List_1=date_format($row['StartTime'],"Y-m-d H:i:s");
    $Date_List_2=date_format($row['EndTime'],"Y-m-d H:i:s");
    $hour=floor((strtotime($Date_List_2)-strtotime($Date_List_1))%86400/3600);
    if($row['Color']===''){
        $color='#ffffff';
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
    //工單
    if($row['Notes']!=''){
        $Dyelot=$row['Notes'].'@'.$row['Dyelot'];
    }
    else{
        $Dyelot=$row['Dyelot'];
    }
    $data[]=array(
            "text" => "$Dyelot",
            "start_date" => date_format($row['StartTime'],"Y-m-d H:i"),
            "end_date" => date_format($row['EndTime'],"Y-m-d H:i"),
            "color" => "$color",          
            "Dyelot" => "$row[Dyelot]",
            "FabricName" => "$row[FabricName]",
            "ColorName" => "$row[ColorName]",
            "TotalWeight" => "$row[TotalWeight]",
            "DeliveryTime" => date_format($row['DeliveryTime'],"Y-m-d H:i"),
            "MakeDyelotTime" => date_format($row['MakeDyelotTime'],"Y-m-d H:i"),
            "textColor" => "$textcolor",
            "parent" => "$row[Machine]",
        );
    }
echo json_encode($data);
?>
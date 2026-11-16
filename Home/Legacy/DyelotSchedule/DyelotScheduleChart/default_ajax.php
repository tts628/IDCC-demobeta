<?php

$start_date=date('Y-m-d H:i:s',strtotime('-3 day'));
$end_date=date('Y-m-d H:i:s',strtotime('+4 day'));

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);


$sql1="SELECT * from DyeMachines";
$result_mach=sqlsrv_query($link,$sql1)or die("sql error:".sqlsrv_errors()); 
while ($row_mach=sqlsrv_fetch_array($result_mach)) {
$data[]=
        array(
            "id" => "$row_mach[Name]",
            "text" => "$row_mach[Name]",
            "start_date" => '',
            "type" => 'project',
            "render" => "split",
        );

}

$sql="SELECT 
 [ID]
      ,[Dyelot]
      ,[Redye]
      ,[Machine]
      ,[Program]
      ,[Color]
      ,[Batch]
      ,[FabricName]
      ,[ColorName]
      ,[Cutomer]
      ,[Order]
      ,[TotalWeight]
      ,[LiquorRatio]
      ,[TotalVolume]
      ,[BulkedRecipe]
	  ,DATEADD(HOUR, 8, [StartTime]) as 'StartTime'
	  ,DATEADD(HOUR, 8, [EndTime]) as 'EndTime'
      ,[StandardTime]
      ,[ProcessTime]
      ,[OverRunTime]
      ,[Note]
      ,[Process]
      ,[JobTicketList]
      ,[ColorNo]
      ,[Status]
      ,[ProductCode]
      ,[YarnLotNo]
      ,[AnnexDyelot01]
      ,[AnnexDyelot02]
      ,[AnnexDyelot03]
      ,[AnnexDyelot04]
      ,[AnnexDyelot05]
      ,[AnnexDyelot06]
      ,[AnnexDyelot07]
      ,[AnnexDyelot08]
      ,[AnnexDyelot09]
      ,[AnnexDyelot10]
      ,[CIE_L]
      ,[CIE_a]
      ,[CIE_b]
      ,[CIE_C]
      ,[CIE_h]
      ,[R]
      ,[G]
      ,[B]
      ,[State]
      ,[DeliveryTime]
      
       from DyeProduction WHERE StartTime BETWEEN '$start_date' AND '$end_date'";
$result=sqlsrv_query($link,$sql)or die("sql error:".sqlsrv_errors()); 
   while ($row=sqlsrv_fetch_array($result)) {
    $Date_List_1=date_format($row['StartTime'],"Y-m-d H:i:s");
    $Date_List_2=date_format($row['EndTime'],"Y-m-d H:i:s");
    $hour=floor((strtotime($Date_List_2)-strtotime($Date_List_1))%86400/3600);
    $progress=$row['Progress']/100;
    if($row['Color']==''){
        $color='#000000';
        $b = '0';
        $g = '0';
        $r = '0';
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
    //配方
    if ($row['BulkedRecipe']!='') {
        $BulkedRecipe=str_replace(",","<br>","$row[BulkedRecipe]");
    }


        $data[]=
        array(
            "id" => "$row[ID]",
            "text" => "$row[Dyelot]",
            "start_date" => date_format($row['StartTime'],"Y-m-d H:i"),
            "end_date" => date_format($row['EndTime'],"Y-m-d H:i"),
            "duration" => "$hour",
            "progress" => "$progress",
            "color" => "$color",          
            "Dyelot" => "$row[Dyelot]",
            "textColor" => "$textcolor",
            "parent" => "$row[Machine]",
            "Batch" => "$row[Batch]",
            "FabricName" => "$row[FabricName]",
            "ColorCode" => "$row[ColorCode]",
            "ColorName" => "$row[ColorName]",
            "Customer" => "$row[Customer]",
            "Order" => "$row[Order]",
            "TotalWeight" => "$row[TotalWeight]",
            "LiquorRatio" => "$row[LiquorRatio]",
            "TotalVolume" => "$row[TotalVolume]",
            "BulkedRecipe" => "$BulkedRecipe",
            "Program" => "$row[Program]",
        );
    }
echo json_encode($data);
?>
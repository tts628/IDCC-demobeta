
<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

$start=$_POST['start'];
$end=$_POST['end'];

$start_date = date('Y-m-d', strtotime($start . ' -30 day'));
$end_date = date('Y-m-d', strtotime($end . ' +10 day'));

// for($i = 1; $i <= 25; $i++) {
//     if($i % 10 != 4) {
//         $MachineName = "D".str_pad($i, 2, "0", STR_PAD_LEFT);
//         $data[] = array("id" => "$MachineName","text" => "$MachineName","start_date" => '',"type" => 'project',"render" => "split",);
//     }
// }
/*
$data[]=array("id" => $MachineName,"text" => $MachineName,"start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D02","text" => "D02","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D03","text" => "D03","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D05","text" => "D05","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D06","text" => "D06","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D07","text" => "D07","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D08","text" => "D08","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D09","text" => "D09","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D10","text" => "D10","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D11","text" => "D11","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D12","text" => "D12","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D13","text" => "D13","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D15","text" => "D15","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D16","text" => "D16","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D17","text" => "D17","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D18","text" => "D18","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D19","text" => "D19","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D20","text" => "D20","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D21","text" => "D21","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D22","text" => "D22","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D23","text" => "D23","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D25","text" => "D25","start_date" => '',"type" => 'project',"render" => "split",);
//$data[22]=array("id" => "D26","text" => "D26","start_date" => '',"type" => 'project',"render" => "split",);
//$data[23]=array("id" => "D27","text" => "D27","start_date" => '',"type" => 'project',"render" => "split",);
//$data[24]=array("id" => "D28","text" => "D28","start_date" => '',"type" => 'project',"render" => "split",);
//$data[25]=array("id" => "D29","text" => "D29","start_date" => '',"type" => 'project',"render" => "split",);
//$data[26]=array("id" => "D30","text" => "D30","start_date" => '',"type" => 'project',"render" => "split",);
*/
/*
$data[]=array("id" => "D02","text" => "D02","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D03","text" => "D03","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D05","text" => "D05","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D06","text" => "D06","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D07","text" => "D07","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D08","text" => "D08","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D09","text" => "D09","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D10","text" => "D10","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D11","text" => "D11","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D12","text" => "D12","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D13","text" => "D13","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D15","text" => "D15","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D16","text" => "D16","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D17","text" => "D17","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D18","text" => "D18","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D19","text" => "D19","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D20","text" => "D20","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D21","text" => "D21","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D22","text" => "D22","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D23","text" => "D23","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "D25","text" => "D25","start_date" => '',"type" => 'project',"render" => "split",);

// $data[]=array("id" => $MachineName,"text" => $MachineName,"start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A01","text" => "A01","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A02","text" => "A02","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A03","text" => "A03","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A04","text" => "A04","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A05","text" => "A05","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A06","text" => "A06","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A07","text" => "A07","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A08","text" => "A08","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A13","text" => "A13","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A14","text" => "A14","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A15","text" => "A15","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A16","text" => "A16","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A17","text" => "A17","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A18","text" => "A18","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A19","text" => "A19","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A20","text" => "A20","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A21","text" => "A21","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A22","text" => "A22","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A23","text" => "A23","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A24","text" => "A24","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A25","text" => "A25","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A26","text" => "A26","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A27","text" => "A27","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A28","text" => "A28","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A29","text" => "A29","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A30","text" => "A30","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A31","text" => "A31","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A32","text" => "A32","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A33","text" => "A33","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "A34","text" => "A34","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "AS01","text" => "AS01","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "AS02","text" => "AS02","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "AS03","text" => "AS03","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "AS04","text" => "AS04","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "AS05","text" => "AS05","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "AS06","text" => "AS06","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "AS07","text" => "AS07","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "AS08","text" => "AS08","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "AS09","text" => "AS09","start_date" => '',"type" => 'project',"render" => "split",);
$data[]=array("id" => "AS10","text" => "AS10","start_date" => '',"type" => 'project',"render" => "split",);




*/

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


$sql="SELECT StartTime,
EndTime,
Color,
Dyelot,
FabricName,
TotalWeight,
BulkedRecipe,
ID,
Machine,
Batch,
ColorName,
'Order',
LiquorRatio,
TotalVolume,
Program
FROM DyeProduction 
WHERE StartTime BETWEEN ? AND ? 
--AND Machine BETWEEN ? AND ?
order by [StartTime] desc";

$param = array($start_date,$end_date);
$result=sqlsrv_query($link,$sql,$param)or die("sql error:".sqlsrv_errors()); 
   while ($row=sqlsrv_fetch_array($result)) {
    $Date_List_1=date_format($row['StartTime'],"Y-m-d H:i:s");
    $Date_List_2=date_format($row['EndTime'],"Y-m-d H:i:s");
    $hour=floor((strtotime($Date_List_2)-strtotime($Date_List_1))%86400/3600);
    // $progress=$row['Progress']/100;
    

    if($row['Color']==''){
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
    
// 判断是否为红色
if ($r >= 140 && $g < 128 && $b < 128) {
    // 如果红色分量足够大，而绿色和蓝色分量较小，则颜色为红色
    $progresscolor='black';
} else {
    $progresscolor='red';
}

$progress=100/100;//調整進度

if ($progress>0.9) {
    $progresscolor=$color;
}



    //工單
    $Dyelot=$row['Dyelot'].$row['FabricName'].$row['TotalWeight'];
    //配方
    if ($row['BulkedRecipe']!='') {
        //$BulkedRecipe='配方：'.$row['BulkedRecipe'].' |';
        $BulkedRecipe=str_replace(",","<br>","$row[BulkedRecipe]");
    }

        $data[]=
        array(
            "id" => $row["ID"] ?? '',
            "text" => $row["Dyelot"] ?? '',
            "start_date" => date_format($row['StartTime'],"Y-m-d H:i"),
            "end_date" => date_format($row['EndTime'],"Y-m-d H:i"),

            "Estimate_start_date" => date_format($row['StartTime'],"Y-m-d H:i"),
            "Estimate_end_date" => date_format($row['EndTime'],"Y-m-d H:i"),

            "duration" => $hour ?? '',
            "progress" => $progress ?? '',
            // "css"=> "background-color: red",
            "color" => $progresscolor ?? '',
            "Dyelot" => $Dyelot ?? '',
            "textColor" => $textcolor ?? '',
            "parent" => $row["Machine"] ?? '',
            "Batch" => $row["Batch"] ?? '',
            "FabricName" => $row["FabricName"] ?? '',
            "ColorCode" => $row["ColorCode"] ?? '',
            "ColorName" => $row["ColorName"] ?? '',
            "Customer" => $row["Customer"] ?? '',
            "Order" => $row["Order"] ?? '',
            "TotalWeight" => $row["TotalWeight"] ?? '',
            "LiquorRatio" => $row["LiquorRatio"] ?? '',
            "TotalVolume" => $row["TotalVolume"] ?? '',
            "BulkedRecipe" => $BulkedRecipe ?? '',
            "Program" => $row["Program"] ?? '',
            "progressColor"=> $color ?? '' 
        );
        // var_dump($color);
    }
echo json_encode($data);
?>
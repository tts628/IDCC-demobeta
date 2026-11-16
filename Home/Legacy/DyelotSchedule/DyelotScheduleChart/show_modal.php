<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");

$lang = new Language();
$lang->load("order");
$start_date=$_POST['start'];
$end_date=$_POST['end'];

if(stripos($start_date, " OR ") !== false || 
stripos($start_date, " ") !== false || 
stripos($start_date, "%") !== false || 
stripos($start_date, "'") !== false || 
stripos($start_date, "{") !== false || 
stripos($start_date, "}") !== false || 
stripos($start_date, "[") !== false || 
stripos($start_date, "]") !== false || 
stripos($start_date, "(") !== false || 
stripos($start_date, ")") !== false || 
stripos($start_date, "<") !== false || 
stripos($start_date, ">") !== false || 
stripos($start_date, "#") !== false || 
stripos($start_date, "!") !== false || 
stripos($start_date, "|") !== false || 
stripos($start_date, "&") !== false || 
stripos($start_date, ";") !== false || 
stripos($start_date, ":") !== false || 
stripos($start_date, "$") !== false || 
stripos($start_date, "*") !== false || 
stripos($start_date, "=") !== false || 
stripos($start_date, ",") !== false || 
stripos($start_date, ".") !== false || 
strlen($start_date) > 15){
    echo "detected sensitive cases<br>";
    $start_date = null;
}
if(stripos($end_date, " OR ") !== false || 
stripos($end_date, " ") !== false || 
stripos($end_date, "%") !== false || 
stripos($end_date, "'") !== false || 
stripos($end_date, "{") !== false || 
stripos($end_date, "}") !== false || 
stripos($end_date, "[") !== false || 
stripos($end_date, "]") !== false || 
stripos($end_date, "(") !== false || 
stripos($end_date, ")") !== false || 
stripos($end_date, "<") !== false || 
stripos($end_date, ">") !== false || 
stripos($end_date, "#") !== false || 
stripos($end_date, "!") !== false || 
stripos($end_date, "|") !== false || 
stripos($end_date, "&") !== false || 
stripos($end_date, ";") !== false || 
stripos($end_date, ":") !== false || 
stripos($end_date, "$") !== false || 
stripos($end_date, "*") !== false || 
stripos($end_date, "=") !== false || 
stripos($end_date, ",") !== false || 
stripos($end_date, ".") !== false || 
strlen($end_date) > 15){
    echo "detected sensitive cases<br>";
    $end_date = null;
}

$sql1="SELECT * from DyeMachines";
$result_mach=sqlsrv_query($link,$sql1)or die("sql error:".sqlsrv_errors()); 
while ($row_mach=sqlsrv_fetch_array($result_mach)){
    //$today=date("2020-10-24");
    $today=date("Y_m-d");
    if($start_date!=''&&$end_date!=''){
        $sql="SELECT  [ID]
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
        ,[DeliveryTime] from DyeProduction WHERE StartTime BETWEEN '$start_date' AND '$end_date'";
    }
    else{
        $sql="SELECT  [ID]
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
        ,[DeliveryTime] from DyeProduction where '$today' between CONVERT(varchar,StartTime ,23) and CONVERT(varchar,EndTime ,23)";
    }
    $result=sqlsrv_query($link,$sql)or die("sql error:".sqlsrv_errors());
        echo "
        <div class='card-deck'>
            <div class='card'>
                <div class='card-header'><h5>$row_mach[Name]</h5></div>
        ";//機台名稱
       while ($row=sqlsrv_fetch_array($result)) {
            if ($row_mach['Name']==$row['Machine']) {
                //顏色
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
                $st_date=date_format($row['StartTime'],"Y-m-d H:i:s");
                $ed_date=date_format($row['EndTime'],"Y-m-d H:i:s");
                //工單
                if($row['Redye']!='0'){
                    $Dyelot=$row['Dyelot'].'@'.$row['Redye'];
                }
                else{
                    $Dyelot=$row['Dyelot'];
                }
                //缸號：
                if($row['Batch']!=''){
                    $Batch=$lang->line("order.order_Batch").'：'.$row['Batch'].' |';
                }
                else{
                    $Batch='';
                }
                //品名：
                if ($row['FabricName']!='') {
                    $FabricName=$lang->line("order.order_FabricName").'：'.$row['FabricName'].' |';
                }
                else{
                    $FabricName='';
                }
                //色號：
                if ($row['ColorCode']!='') {
                    $ColorCode=$lang->line("order.order_ColorCode").'：'.$row['ColorCode'].' |';
                }
                else{
                    $ColorCode='';
                }   
                //色名             
                if ($row['ColorName']!='') {
                    $ColorName=$lang->line("order.order_ColorName").'：'.$row['ColorName'].' |';
                }
                else{
                    $ColorName='';
                }
                //客戶：             
                if ($row['Customer']!='') {
                    $Customer=$lang->line("order.order_Customer").'：'.$row['Customer'].' |';
                }
                else{
                    $Customer='';
                }
                //訂單：             
                if ($row['Order']!='') {
                    $Order=$lang->line("order.order_Order").'：'.$row['Order'].' |';
                }
                else{
                    $Order='';
                }
                //布重：             
                if ($row['TotalWeight']!='') {
                    $TotalWeight=$lang->line("order.order_TotalWeight").'：'.$row['TotalWeight'].'kg |';
                }
                else{
                    $TotalWeight='';
                }
                //浴比：             
                if ($row['LiquorRatio']!='') {
                    $LiquorRatio=$lang->line("order.order_LiquorRatio").'：'.$row['LiquorRatio'].' |';
                }
                else{
                    $LiquorRatio='';
                }
                //總浴量：             
                if ($row['TotalVolume']!='') {
                    $TotalVolume=$lang->line("order.order_TotalVolume").'：'.$row['TotalVolume'].' |';
                }
                else{
                    $TotalVolume='';
                }
                //配方：             
                if ($row['BulkedRecipe']!='') {
                    //$BulkedRecipe='配方：'.$row['BulkedRecipe'].' |';
                    $BulkedRecipe=$lang->line("order.order_BulkedRecpie").'：<br>'.str_replace(",","<br>","$row[BulkedRecipe]").' |';
                }
                else{
                    $BulkedRecipe='';
                }                                
                //程式
                if ($row['Program']!='') {
                    $Program=$lang->line("order.order_Program").'：'.$row['Program'].' |';
                }
                else{
                    $Program='';
                }
                echo "<div class='card-body'>
                        <h5 class='card-title' style='color:$textcolor;background-color:$color'>$Dyelot</h5>
                        <h6 class='card-text'>".$lang->line('order.order_Startday')."：$st_date<br>".$lang->line('order.order_Endday')."：$ed_date</h6>
                        <h7 class='card-text'>$Batch$FabricName</h7>
                        <h7 class='card-text'>$ColorCode$ColorName</h7><br>
                        <h7 class='card-text'>$Customer$Order</h7><br>
                        <h7 class='card-text'>$TotalWeight$LiquorRatio$TotalVolume$Program</h7><br>
                        <h7 class='card-text'>$BulkedRecipe</h7><br>
                        
                    </div>";
            }
    }
        echo "</div>";
    echo "</div>";

}

?>
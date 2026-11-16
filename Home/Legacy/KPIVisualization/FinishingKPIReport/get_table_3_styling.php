<?php
// ini_set('display_errors', '1');
// error_reporting(E_ALL);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);



$sql = "  SELECT *
FROM (
    SELECT
       *,
        ROW_NUMBER() OVER (PARTITION BY Machine ORDER BY LogTimeStamp DESC) AS RowNumber
    FROM
        [FinishMachines_Stenter_Machine]
) AS LatestRecords
WHERE RowNumber = 1";

$result = sqlsrv_query($link, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));


//抓今天有運作的資料
while ($row = sqlsrv_fetch_array($result)) {
    // for ($i = 1; $i < 10; $i++) {
    //     $temperature[$i] = $row['TempChamber0'.$i]; // 从 $row 数组中获取值并存储到 $tension 数组中
    //     $width[$i] = $row['WidthSpindle0'.$i]; // 从 $row 数组中获取值并存储到 $tension 数组中
    // }
    if($row['Machine']=='MON1'){
        $T01_tem_01=$row['TempChamber01'];
        $T01_tem_05=$row['TempChamber05'];
        $T01_tem_09=$row['TempChamber09'];
        $T01_wid_01=$row['WidthSpindle01']/100;
        $T01_wid_05=$row['WidthSpindle05']/100;
        $T01_wid_09=$row['WidthSpindle09']/100;
    }
    else if($row['Machine']=='MON2'){
        $T03_tem_01=$row['TempChamber01'];
        $T03_tem_05=$row['TempChamber05'];
        $T03_tem_09=$row['TempChamber09'];
        $T03_wid_01=$row['WidthSpindle01']/100;
        $T03_wid_05=$row['WidthSpindle05']/100;
        $T03_wid_09=$row['WidthSpindle09']/100;
    }
    else if($row['Machine']=='MON3'){
        $T05_tem_01=$row['TempChamber01'];
        $T05_tem_05=$row['TempChamber05'];
        $T05_tem_09=$row['TempChamber09'];
        $T05_wid_01=$row['WidthSpindle01']/100;
        $T05_wid_05=$row['WidthSpindle05']/100;
        $T05_wid_09=$row['WidthSpindle09']/100;
    }
    else{

    }
}


?>




<!-- 抓各機台稼動率 從bar_3複製過來 -->
<?php

$today_start = date("Y-m-d H:i:s", strtotime("-24 hours"));
$today_start_minus = strtotime($today_start); //今天開始
$today_end=date("Y-m-d H:i:s");
$today_end_minus = strtotime($today_end);//今天結束

$sql_machine = "SELECT
case when Machine='MON1'then 'T01'
		when Machine='MON2'then 'T03'
		when Machine='MON3'then 'T05'
		else '' end  as 'Name'
,round(sum(run_time)/3600,2)as'run_time'
,0 as 'alarm'
,0 as 'fix'
FROM
(
    
--計算只有2個工單和不是基數
    
    SELECT 
[Machine]
,[Dyelot]
,DATEDIFF(SECOND, MIN(LogTimeStamp), MAX(LogTimeStamp)) AS 'run_time'
  FROM 
  (
  SELECT * FROM [FinishMachines_Stenter_Machine_T01]
  where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
  AND Dyelot NOT IN (
    SELECT Dyelot
    FROM  [FinishMachines_Stenter_Machine_T01]
    where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
    GROUP BY Dyelot
    HAVING COUNT(*) % 2 = 1 OR COUNT(*)>2
)
  UNION
  SELECT * FROM [FinishMachines_Stenter_Machine_T03]
  where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
  AND Dyelot NOT IN (
    SELECT Dyelot
    FROM  [FinishMachines_Stenter_Machine_T03]
    where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
    GROUP BY Dyelot
    HAVING COUNT(*) % 2 = 1 OR COUNT(*)>2
)
  UNION
  SELECT * FROM [FinishMachines_Stenter_Machine_T05]
  where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
  AND Dyelot NOT IN (
    SELECT Dyelot
    FROM  [FinishMachines_Stenter_Machine_T05]
    where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
    GROUP BY Dyelot
    HAVING COUNT(*) % 2 = 1 OR COUNT(*)>2
)
  )combinetable
  
  
 group by  [Machine],[Dyelot]



 ---加上T01雙數筆的 單數或跨天去掉
 UNION

SELECT 
 t1_1.Machine,
 t1_1.Dyelot,
 DATEDIFF(SECOND, t1_1.LogTimeStamp, t1_2.LogTimeStamp) AS TimeDifferenceInSeconds
FROM 
 (SELECT * FROM [FinishMachines_Stenter_Machine_T01]
where Dyelot  IN (
 SELECT Dyelot
 FROM  [FinishMachines_Stenter_Machine_T01]
 where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
 GROUP BY Dyelot
 HAVING COUNT(*) % 2 = 0 and COUNT(*)>2)) t1_1
JOIN 
 (SELECT * FROM [FinishMachines_Stenter_Machine_T01]
where Dyelot  IN (
 SELECT Dyelot
 FROM  [FinishMachines_Stenter_Machine_T01]
 where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
 GROUP BY Dyelot
 HAVING COUNT(*) % 2 = 0 and COUNT(*)>2)) t1_2 ON t1_1.ID + 1 = t1_2.ID

 ---T01雙數筆完

 ---加上T03雙數筆的
 UNION

SELECT 
 t3_1.Machine,
 t3_1.Dyelot,
 DATEDIFF(SECOND, t3_1.LogTimeStamp, t3_2.LogTimeStamp) AS TimeDifferenceInSeconds
FROM 
 (SELECT * FROM [FinishMachines_Stenter_Machine_T03]
where Dyelot  IN (
 SELECT Dyelot
 FROM  [FinishMachines_Stenter_Machine_T03]
 where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
 GROUP BY Dyelot
 HAVING COUNT(*) % 2 = 0 and COUNT(*)>2)) t3_1
JOIN 
 (SELECT * FROM [FinishMachines_Stenter_Machine_T03]
where Dyelot  IN (
 SELECT Dyelot
 FROM  [FinishMachines_Stenter_Machine_T03]
 where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
 GROUP BY Dyelot
 HAVING COUNT(*) % 2 = 0 and COUNT(*)>2)) t3_2 ON t3_1.ID + 1 = t3_2.ID

 ---T01雙數筆完


  ---加上T05雙數筆的
  UNION

SELECT 
 t5_1.Machine,
 t5_1.Dyelot,
 DATEDIFF(SECOND, t5_1.LogTimeStamp, t5_2.LogTimeStamp) AS TimeDifferenceInSeconds
FROM 
 (SELECT * FROM [FinishMachines_Stenter_Machine_T05]
where Dyelot  IN (
 SELECT Dyelot
 FROM  [FinishMachines_Stenter_Machine_T05]
 where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
 GROUP BY Dyelot
 HAVING COUNT(*) % 2 = 0 and COUNT(*)>2)) t5_1
JOIN 
 (SELECT * FROM [FinishMachines_Stenter_Machine_T05]
where Dyelot  IN (
 SELECT Dyelot
 FROM  [FinishMachines_Stenter_Machine_T05]
 where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
 GROUP BY Dyelot
 HAVING COUNT(*) % 2 = 0 and COUNT(*)>2)) t5_2 ON t5_1.ID + 1 = t5_2.ID

 ---T01雙數筆完

 )Dyelotrun
 group by [Machine]



        


";
//$sql_machine = "select * from DyeMachines ORDER BY ID ASC";

// var_dump($sql_machine);
$result_machine = sqlsrv_query($link, $sql_machine) or die("sql error" . sqlsrv_errors());

$activation=0;
$machine_num=0;
//抓取所有機台資料
while ($row_machine = sqlsrv_fetch_array($result_machine)) {
    // $name_array[] = array($row_machine['Name']);
    // $work_array[] = array((float)$row_machine['run_time']);
    // $stop_array[] = array(24-(float)$row_machine['run_time']-(float)$row_machine['fix']-(float)$row_machine['alarm']);
    // $fix_array[] = array((float)$row_machine['fix']);
    // $alarm_array[] = array((float)$row_machine['alarm']);
    // $activation+=(float)$row_machine['run_time']/24;
    // $machine_num++;


    $machine_activation[$row_machine['Name']] = number_format((float)$row_machine['run_time']*100/24,2);

}
// var_dump($machine_activation);
sqlsrv_close($link);

?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<!-- <link rel="stylesheet" href="common/bootstrap-5.0.0-dist/css/bootstrap.min.css"> -->
<style>
    .grid-container {

        font-size: 24px;
        color: white;
        top: 30px;

        display: grid;
        grid-template-columns: repeat(3, 1fr);
        /* gap: 10px; */

        padding: 20px;


        position: relative;
    }

    .grid-item {
        /* margin-bottom: 100px; */
        /* padding-bottom: -100px; */

        /* border: 1px solid #ccc; */
        border-left: 1px solid #ccc;
        /* 只设置左边框 */
        border-right: 1px solid #ccc;
        /* 只设置右边框 */
        padding: 5px;
        text-align: center;
        position: relative;
        /* height: 200px;
            width: 200px; */
    }

    .label-left {
        position: absolute;
        left: -30px;
        top: 50%;
        transform: translateY(-50%);
    }

    .label-top {
        position: absolute;
        top: -40px;
        left: 50%;
        transform: translateX(-50%);
    }
</style>




<div class="container">
    <div class="grid-container">
        <div class="grid-item">
            <div class="label-left">第<br>1<br>節</div>
            <div class="label-top">T01</div>
            <div>幅寬：<?php echo $T01_wid_01;?></div>
            <br>
            <div>溫度：<?php echo $T01_tem_01;?></div>
        </div>
        <div class="grid-item">
            <div class="label-top">T03</div>
            <div>幅寬：<?php echo $T03_wid_01;?></div>
            <br>
            <div>溫度：<?php echo $T03_tem_01;?></div>
        </div>
        <div class="grid-item">
            <div class="label-top">T05</div>
            <div>幅寬：<?php echo $T05_wid_01;?></div>
            <br>
            <div>溫度：<?php echo $T05_tem_01;?></div>
        </div>
        <div class="grid-item">
            
            <!-- <div class="label-top">T04</div> -->
            <br><br>
            <div class="label-left"><br><br>第<br>5<br>節</div>
            <div>幅寬：<?php echo $T01_wid_05;?></div>
            <br>
            <div>溫度：<?php echo $T01_tem_05;?></div>
        </div>

        <div class="grid-item">
            <!-- <div class="label-top">T05</div> -->
            <br><br>
            <div>幅寬：<?php echo $T03_wid_05;?></div>
            <br>
            <div>溫度：<?php echo $T03_tem_05;?></div>
        </div>
        <div class="grid-item">
            <!-- <div class="label-top">T06</div> -->
            <br><br>
            <div>幅寬：<?php echo $T05_wid_05;?></div>
            <br>
            <div>溫度：<?php echo $T05_tem_05;?></div>
        </div>
        <div class="grid-item">
        <br><br>

            <div class="label-left"><br><br>第<br>9<br>節</div>
            <!-- <div class="label-top">T07</div> -->
            
            <div>幅寬：<?php echo $T01_wid_09;?></div>
            <br>
            <div>溫度：<?php echo $T01_tem_09;?></div>
        </div>
        <div class="grid-item">
            <!-- <div class="label-top">T08</div> -->
            <br><br>
            <div>幅寬：<?php echo $T03_wid_09;?></div>
            <br>
            <div>溫度：<?php echo $T03_tem_09;?></div>
        </div>
        <div class="grid-item">
            <br><br>
            <div>幅寬：<?php echo $T05_wid_09;?></div>
            <br>
            <div>溫度：<?php echo $T05_tem_09;?></div>
        </div>
        <!-- <div class="label-top">T09</div> -->

        <div class="grid-item">
        <br><br>

            <div class="label-left"><br><br>稼<br>動<br>率</div>
            <!-- <div class="label-top">T07</div> -->
            

            <div style='font-size:2.5vw'><?php echo $machine_activation['T01'];?>%</div>
        </div>
        <div class="grid-item">
            <!-- <div class="label-top">T08</div> -->
            <br><br>

            <div style='font-size:2.5vw'><?php echo $machine_activation['T03'];?>%</div>

        </div>
        <div class="grid-item">
            <br><br>
    
            <div style='font-size:2.5vw'><?php echo $machine_activation['T05'];?>%</div>

        </div>
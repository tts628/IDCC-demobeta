<?php
/*
ini_set('display_errors', '1');
error_reporting(E_ALL);
require_once('./common/database.php');
$today_start = date("Y-m-d H:i:s", strtotime("-24 hours"));
$today_start_minus = strtotime($today_start); //今天開始


$today_end=date("Y-m-d H:i:s");
$today_end_minus = strtotime($today_end);//今天結束

$sql_machine = "select * from FinishMachines ORDER BY ID ASC";
//$sql_machine = "select * from DyeMachines ORDER BY ID ASC";


$result_machine = sqlsrv_query($link, $sql_machine) or die("sql error" . sqlsrv_errors());
$data_work_total = 0;
$data_stop_total = 0;
$data_fix_total = 0;
$data_alarm_total = 0;
$data_stop_all = 0;
$activation = 0;
$num_all = 0;


//抓取所有機台資料
while ($row_machine = sqlsrv_fetch_array($result_machine)) {

    $machine_name = $row_machine['Name'];
//    echo json_encode($row_machine['Name']);
//    echo '<br>';


//    抓取訂單結束時間大於今天開始的資料
    $sql = "select * from FinishProduction where Machine ='$machine_name' and EndTime > '$today_start' ";

    $result = sqlsrv_query($link, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
    $num = sqlsrv_num_rows($result);
    $num_all = $num_all + $num;
    $istart = 1;
//抓今天有運作的資料
    while ($row = sqlsrv_fetch_array($result)) {


//        echo '第' . $istart . '遍';
//        echo '<br>';


        //  機器名稱
        $machine_name = $row['Machine'];
        $Dyelot = $row['Dyelot'];
//        echo json_encode($Dyelot);
//        echo '<br>';


        //  工單開始時間
        $DyelotStartTime = ($row['StartTime']->format('Y-m-d H:i:s'));
        $DyelotStartTime_minus = strtotime($DyelotStartTime);
//        echo '開始時間';
//        echo json_encode($DyelotStartTime);
//        echo '<br>';

        //  工單結束時間
        $DyelotEndTime = ($row['EndTime']->format('Y-m-d H:i:s'));
        $DyelotEndTime_minus = strtotime($DyelotEndTime);
//        echo '結束時間';
//        echo json_encode($DyelotEndTime);
//        echo '<br>';

        // 狀態1先前開始到今天
        if ($DyelotStartTime < $today_start) {
            $data_work = 0;
//            一直運作到未來
            if ($DyelotEndTime > $today_end) {
//                echo '1-1';

                $data_work = (($today_end_minus - $today_start_minus) / 3600);
            }
//            做完了
            if ($DyelotEndTime < $today_end) {
//                echo '1-2';

                $data_work = (($DyelotEndTime_minus - $today_start_minus) / 3600);
            }


            //狀態1的故障alarm時間
            //                -----alarm start------

            $sql_alarm = "select * from FinishEvent where Machine ='$machine_name' and Dyelot = '$Dyelot' and EventType = '5' ";

            $result_alarm = sqlsrv_query($link, $sql_alarm, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

            $num_alarm = sqlsrv_num_rows($result_alarm);

            if ($num_alarm > 0) {
//                echo '狀態一有故障';
//                echo '<br>';

                $data_alarm_all = 0;
                while ($row_alarm = sqlsrv_fetch_array($result_alarm)) {
                    $create_alarm = ($row_alarm['CreateDate']->format('Y-m-d H:i:s'));
                    $create_alarm_minus = strtotime($create_alarm);
                    $finish_alarm = ($row_alarm['FinishDate']->format('Y-m-d H:i:s'));
                    $finish_alarm_minus = strtotime($finish_alarm);
                    if ($num_alarm['EndTime'] > $today_start) {
                        if ($num_alarm['EndTime'] > $today_end) {
                            $data_alarm = (($today_end_minus - $create_alarm_minus) / 3600); //60s*60min /小時
                        } else {
                            $data_alarm = (($today_end_minus - $create_alarm_minus) / 3600); //60s*60min /小時

                        }
                    }
                    $data_alarm_all = $data_alarm_all + $data_alarm;

                }
            } else {
//                echo '狀態一沒故障';
//                echo '<br>';

                $data_alarm_all = 0;
            }
//                -----alarm end------

            //狀態1的維修fix時間
            //                -----fix start------

            $sql_fix = "select * from FinishEvent where Machine ='$machine_name' and Dyelot = '$Dyelot' and EventType = '6' ";

            $result_fix = sqlsrv_query($link, $sql_fix, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

            $num_fix = sqlsrv_num_rows($result_fix);

            if ($num_fix > 0) {
//                echo '狀態一有故障';
//                echo '<br>';

                $data_fix_all = 0;
                while ($row_fix = sqlsrv_fetch_array($result_fix)) {
                    $create_fix = ($row_fix['CreateDate']->format('Y-m-d H:i:s'));
                    $create_fix_minus = strtotime($create_fix);
                    $finish_fix = ($row_fix['FinishDate']->format('Y-m-d H:i:s'));
                    $finish_fix_minus = strtotime($finish_fix);
                    if ($row_fix['EndTime'] > $today_start) {
                        if ($row_fix['EndTime'] > $today_end) {
                            $data_fix = (($today_end_minus - $create_alarm_minus) / 3600); //60s*60min /小時
                        } else {
                            $data_fix = (($today_end_minus - $create_alarm_minus) / 3600); //60s*60min /小時

                        }
                    }
                    $data_fix_all = $data_fix_all + $data_fix;

                }
            } else {
//                echo '狀態一沒故障';
//                echo '<br>';

                $data_fix_all = 0;
            }
//                -----fix end------


//          需減掉stop和fix
            $data_work = $data_work - $data_fix_all - $data_alarm_all;

//            echo '狀態一 work time';
//            echo json_encode($data_work);
//            echo '<br>';

        } else {
            $data_work = 0;
        }


        //      狀態2 今天才開始運作或是今天的第n筆工單
        if ($DyelotStartTime >= $today_start && $DyelotStartTime < $today_end) {

            $data_work = 0;
//2-1今天才開始運作,運作直到明天
            if ($DyelotEndTime > $today_end) {
//            現在時間-[StartTime]-event=work

//                echo '狀態2-1';
//                echo '<br>';

//                        echo json_encode($machine_name);

//            echo json_encode($DyelotEndTime);
//            echo json_encode($ID);

//            echo json_encode($today_end);

                $data_work = (($today_end_minus - $DyelotStartTime_minus) / 3600); //60s*60min /小時
//            if($data_work_2_1<0){
//                $data_work_2_1=0;
//            }

            } else {
//                echo '狀態2-1e';
//                echo '<br>';

                if ($data_work == 0) {
                    $data_work = 0;
                }
            }
            //2-2 今天才開始運作,運作到今天,目前還在運作
            if ($DyelotEndTime < $today_end) {
//            現在時間-今天開始-event=work
//                echo '狀態2-2';
//                echo '<br>';

                $data_work = (($DyelotEndTime_minus - $DyelotStartTime_minus) / 3600); //60s*60min /小時

//                echo json_encode($data_work);
//                echo '<br>';

            } else {
//                echo '狀態2-2e';
//                echo '<br>';

                if ($data_work == 0) {
                    $data_work = 0;
                }
            }

//            狀態二計算故障
            //                -----alarm start------

            $sql_alarm_2 = "select * from FinishEvent where Machine ='$machine_name' and Dyelot = '$Dyelot' and CreateDate > '$today_start'  and EventType = '5'";

            $result_alarm_2 = sqlsrv_query($link, $sql_alarm_2, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

            $num_alarm_2 = sqlsrv_num_rows($result_alarm_2);

            if ($num_alarm_2 > 0) {
//                echo '狀態2有故障';
//                echo '<br>';

                while ($row_alarm_2 = sqlsrv_fetch_array($result_alarm_2)) {
                    $create_alarm_2 = ($row_alarm_2['CreateDate']->format('Y-m-d H:i:s'));
                    $create_alarm_minus_2 = strtotime($create_alarm_2);

                    $finish_alarm_2 = ($row_alarm_2['FinishDate']->format('Y-m-d H:i:s'));
                    $finish_alarm_minus_2 = strtotime($finish_alarm_2);

                    $data_alarm = (($finish_alarm_minus_2 - $create_alarm_minus_2) / 3600); //60s*60min /小時
                    $data_alarm_all = $data_alarm_all + $data_alarm;
                }
            } else {
//                echo '狀態2沒故障';
//                echo '<br>';

                $data_alarm_all = 0;
            }
//                -----alarm end------

//            計算維修
            //                -----fix start------
            $sql_fix_2 = "select * from FinishEvent where Machine ='$machine_name' and Dyelot = '$Dyelot' and CreateDate > '$today_start' and EventType = '6'";

            $result_fix_2 = sqlsrv_query($link, $sql_fix_2, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

            $num_fix_2 = sqlsrv_num_rows($result_fix_2);

            if ($num_fix_2 > 0) {
//                echo '狀況2有維修';
//                echo '<br>';

                while ($row_fix_2 = sqlsrv_fetch_array($result_fix_2)) {

                    $create_fix_2 = ($row_fix_2['CreateDate']->format('Y-m-d H:i:s'));
                    $create_fix_minus_2 = strtotime($create_fix_2);

                    $finish_fix_2 = ($row_fix_2['FinishDate']->format('Y-m-d H:i:s'));
                    $finish_fix_minus_2 = strtotime($create_fix_2);

                    $data_fix = (($finish_fix_minus_2 - $create_fix_minus_2) / 3600); //60s*60min /小時
                    $data_fix_all = $data_fix_all + $data_fix;
                }
            } else {
//                echo '狀況2沒維修';
//                echo '<br>';

                $data_fix_all = 0;
            }
//                -----fix end------


//                需減掉stop和fix
            $data_work = $data_work - $data_fix_all - $data_alarm_all;


//            echo '狀態一 work time';
//            echo json_encode($data_work);
//            echo '<br>';
        }
//         狀態2 end


//        echo '第'.$istart.'遍';
//        echo '<br>';

        $istart = $istart + 1;


//        echo '這批運作時間';
//        echo json_encode($data_work);
//        echo '<br>';


        $data_work_total = $data_work_total + $data_work;
//        echo '總共運作時間';
//        echo json_encode($data_work_total);
//        echo '<br>';


        //抓取完所有有運作數值
    }


    $data_stop_total = 24 - $data_work_total - $data_fix_total - $data_alarm_total;


    $name_array[] = array($machine_name);
    $work_array[] = array($data_work_total);
    $stop_array[] = array($data_stop_total);
    $fix_array[] = array($data_fix_total);
    $alarm_array[] = array($data_alarm_total);

//    echo $istart;
//    echo '<br>';

//抓取完所有機台
    $data_stop_all = $data_stop_all + $data_stop_total;

    $data_work_total = 0;
    $data_stop_total = 0;
    $data_fix_total = 0;
    $data_alarm_total = 0;
//    echo '-------------------';
//    echo '<br>';


}
$all_hour = 24 * $num_all;
//echo json_encode($all_hour);
if ($all_hour == '0') {
    $activation = '0';
} else {
    $activation = (($all_hour - $data_stop_all) / $all_hour) * 100;
    $activation = round($activation, 1);
//echo json_encode($activation);
}


$all_data_array[] = array("chart_name" => 'bar4', "machine" => $name_array, "work" => $work_array, "stop" => $stop_array, "fix" => $fix_array, "alarm" => $alarm_array, "activation" => $activation);

echo json_encode($all_data_array);
*/
?>



<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

$today_start = date("Y-m-d H:i:s", strtotime("-24 hours"));
$today_start_minus = strtotime($today_start); //今天開始


$today_end = date("Y-m-d H:i:s");
$today_end_minus = strtotime($today_end);//今天結束
/*
	SELECT
	Machine_name as 'Name'
	,round(count(MachineSpeed)/60,2)as'run_time'
	,0 as 'alarm'
	,0 as 'fix'
	FROM
	(SELECT
		case when Machine='MON1'then 'T01'
		when Machine='MON2'then 'T03'
		when Machine='MON3'then 'T05'
		else '' end as 'Machine_name'
	,MachineSpeed
    FROM
            [FinishMachines_Stenter_Machine]
			where MachineSpeed>10
			AND LogTimeStamp >  '2023-10-10'
	)run
	group by Machine_name
*/

/*


  SELECT
  [Machine]
  ,round(sum(run_time)/60,2)as'run_time'
  ,0 as 'alarm'
  ,0 as 'fix'
  FROM
  (SELECT 
  [Machine]
  ,[Dyelot]
  ,DATEDIFF(MINUTE, MIN(LogTimeStamp), MAX(LogTimeStamp)) AS 'run_time'
    FROM 
    (
    SELECT * FROM[FinishMachines_Stenter_Machine_T01]

    UNION
    SELECT * FROM[FinishMachines_Stenter_Machine_T03]

    UNION
    SELECT * FROM[FinishMachines_Stenter_Machine_T05]

    )combinetable
   group by  [Machine],[Dyelot]

   )Dyelotrun
   group by [Machine]
    */
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
  SELECT * FROM[FinishMachines_Stenter_Machine_T01]
  where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
  AND Dyelot NOT IN (
    SELECT Dyelot
    FROM [FinishMachines_Stenter_Machine_T01]
    where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
    GROUP BY Dyelot
    HAVING COUNT(*) % 2 = 1 OR COUNT(*)>2
)
  UNION
  SELECT * FROM[FinishMachines_Stenter_Machine_T03]
  where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
  AND Dyelot NOT IN (
    SELECT Dyelot
    FROM [FinishMachines_Stenter_Machine_T03]
    where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
    GROUP BY Dyelot
    HAVING COUNT(*) % 2 = 1 OR COUNT(*)>2
)
  UNION
  SELECT * FROM[FinishMachines_Stenter_Machine_T05]
  where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
  AND Dyelot NOT IN (
    SELECT Dyelot
    FROM [FinishMachines_Stenter_Machine_T05]
    where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
    GROUP BY Dyelot
    HAVING COUNT(*) % 2 = 1 OR COUNT(*)>2
)
  )combinetable
  
  
 group by  [Machine],[Dyelot]



 ---加上T01雙數筆的
 UNION

SELECT 
 t1_1.Machine,
 t1_1.Dyelot,
 DATEDIFF(SECOND, t1_1.LogTimeStamp, t1_2.LogTimeStamp) AS TimeDifferenceInSeconds
FROM 
 (SELECT * FROM[FinishMachines_Stenter_Machine_T01]
where Dyelot  IN (
 SELECT Dyelot
 FROM [FinishMachines_Stenter_Machine_T01]
 where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
 GROUP BY Dyelot
 HAVING COUNT(*) % 2 = 1 OR COUNT(*)>2)) t1_1
JOIN 
 (SELECT * FROM[FinishMachines_Stenter_Machine_T01]
where Dyelot  IN (
 SELECT Dyelot
 FROM [FinishMachines_Stenter_Machine_T01]
 where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
 GROUP BY Dyelot
 HAVING COUNT(*) % 2 = 1 OR COUNT(*)>2)) t1_2 ON t1_1.ID + 1 = t1_2.ID

 ---T01雙數筆完

 ---加上T01雙數筆的
 UNION

SELECT 
 t3_1.Machine,
 t3_1.Dyelot,
 DATEDIFF(SECOND, t3_1.LogTimeStamp, t3_2.LogTimeStamp) AS TimeDifferenceInSeconds
FROM 
 (SELECT * FROM[FinishMachines_Stenter_Machine_T03]
where Dyelot  IN (
 SELECT Dyelot
 FROM [FinishMachines_Stenter_Machine_T03]
 where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
 GROUP BY Dyelot
 HAVING COUNT(*) % 2 = 1 OR COUNT(*)>2)) t3_1
JOIN 
 (SELECT * FROM[FinishMachines_Stenter_Machine_T03]
where Dyelot  IN (
 SELECT Dyelot
 FROM [FinishMachines_Stenter_Machine_T03]
 where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
 GROUP BY Dyelot
 HAVING COUNT(*) % 2 = 1 OR COUNT(*)>2)) t3_2 ON t3_1.ID + 1 = t3_2.ID

 ---T01雙數筆完


  ---加上T01雙數筆的
  UNION

SELECT 
 t5_1.Machine,
 t5_1.Dyelot,
 DATEDIFF(SECOND, t5_1.LogTimeStamp, t5_2.LogTimeStamp) AS TimeDifferenceInSeconds
FROM 
 (SELECT * FROM[FinishMachines_Stenter_Machine_T05]
where Dyelot  IN (
 SELECT Dyelot
 FROM [FinishMachines_Stenter_Machine_T05]
 where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
 GROUP BY Dyelot
 HAVING COUNT(*) % 2 = 1 OR COUNT(*)>2)) t5_1
JOIN 
 (SELECT * FROM[FinishMachines_Stenter_Machine_T05]
where Dyelot  IN (
 SELECT Dyelot
 FROM [FinishMachines_Stenter_Machine_T05]
 where  LogTimeStamp > '$today_start'　 AND　LogTimeStamp < '$today_end'
 GROUP BY Dyelot
 HAVING COUNT(*) % 2 = 1 OR COUNT(*)>2)) t5_2 ON t5_1.ID + 1 = t5_2.ID

 ---T01雙數筆完

 )Dyelotrun
 group by [Machine]



        


";
//$sql_machine = "select * from DyeMachines ORDER BY ID ASC";

// var_dump($sql_machine);
$result_machine = sqlsrv_query($link, $sql_machine) or die("sql error" . sqlsrv_errors());

$activation = 0;
$machine_num = 0;
//抓取所有機台資料
while ($row_machine = sqlsrv_fetch_array($result_machine)) {
    $name_array[] = array($row_machine['Name']);
    $work_array[] = array((float) $row_machine['run_time']);
    $stop_array[] = array(24 - (float) $row_machine['run_time'] - (float) $row_machine['fix'] - (float) $row_machine['alarm']);
    $fix_array[] = array((float) $row_machine['fix']);
    $alarm_array[] = array((float) $row_machine['alarm']);
    $activation += (float) $row_machine['run_time'] / 24;
    $machine_num++;
}
// $all_hour = 24 * $num_all;
//echo json_encode($all_hour);
// if ("1" == '0') {
//     $activation = '0';
// } else {
//     // $activation = (($all_hour - $data_stop_all) / $all_hour) * 100;
//     // $activation = round($activation, 1);
// //echo json_encode($activation);
// $activation = '0';
// }
$activation = round($activation / $machine_num * 100, 2);
// var_dump(($machine_num));

$all_data_array[] = array("chart_name" => 'bar4', "machine" => $name_array, "work" => $work_array, "stop" => $stop_array, "fix" => $fix_array, "alarm" => $alarm_array, "activation" => $activation);

echo json_encode($all_data_array);
?>
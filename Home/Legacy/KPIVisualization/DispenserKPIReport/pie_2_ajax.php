<?php
/*
ini_set('display_errors', '1');
error_reporting(E_ALL);
require_once('./common/database.php');
$today_start = date("Y-m-d H:i:s", strtotime("-24 hours"));
$today_start_minus = strtotime($today_start); //今天開始


$today_end=date("Y-m-d H:i:s");
$today_end_minus = strtotime($today_end);//今天結束

$sql_machine = "select * from DispenserMachines ORDER BY ID ASC";
//$sql_machine = "select * from DyeMachines ORDER BY ID ASC";


$result_machine = sqlsrv_query($link, $sql_machine) or die("sql error" . sqlsrv_errors());
$data_work_total = 0;
$data_stop_total = 0;
$data_fix_total = 0;
$data_alarm_total = 0;


//抓取所有機台資料
while ($row_machine = sqlsrv_fetch_array($result_machine)) {

    $machine_name = $row_machine['Name'];
//    echo json_encode($row_machine['Name']);
//    echo '<br>';


//    抓取訂單結束時間大於今天開始的資料
    // $sql = "select * from DispenserProduction where Dispenser LIKE '$machine_name' and EndTime > '$today_start' ";
    $sql = "select * from DispenserProduction where Machine LIKE '$machine_name' and EndTime > '$today_start' ";

    $result = sqlsrv_query($link, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

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

            $sql_alarm = "select * from DispenserEvent where Machine = '$machine_name' and Dyelot = '$Dyelot' and IdccEventType = '5' ";

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

            $sql_fix = "select * from DispenserEvent where Machine = '$machine_name' and Dyelot = '$Dyelot' and IdccEventType = '6' ";

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

            $sql_alarm_2 = "select * from DispenserEvent where Machine ='$machine_name' and Dyelot = '$Dyelot' and CreateDate > '$today_start'  and IdccEventType = '5'";

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
            $sql_fix_2 = "select * from DispenserEvent where Machine ='$machine_name' and Dyelot = '$Dyelot' and CreateDate > '$today_start' and IdccEventType = '6'";

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
    $data_work_total = 0;
    $data_stop_total = 0;
    $data_fix_total = 0;
    $data_alarm_total = 0;
//    echo '-------------------';
//    echo '<br>';
}

$all_data_array[] = array("chart_name" => 'bar4', "machine" => $name_array, "work" => $work_array, "stop" => $stop_array, "fix" => $fix_array, "alarm" => $alarm_array);

echo json_encode($all_data_array);*/
?>

<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

$today_start =  date("Y-m-d H:i:s", strtotime("-1 days"));
$today_start_minus = strtotime($today_start); //今天開始


$today_end= date("Y-m-d H:i:s", strtotime("-0 days"));
$today_end_minus = strtotime($today_end);//今天結束
/*
SELECT 
Y1.Name
,COALESCE(Y2.run_time, 0) AS 'run_time'
,COALESCE(Y2.alarm, 0) AS 'alarm'
,COALESCE(Y2.fix, 0) AS 'fix'
FROM
(select distinct(Machine) as'Name' FROM DispenserProduction)
Y1

LEFT JOIN

(
select 
Machine
,sum(run_time) as 'run_time'
,sum(alarm) as 'alarm'
,sum(fix) as 'fix'
FROM 
(
SELECT 
Machine
,Dyelot
,SUM(ROUND(DATEDIFF(MINUTE, [Start], [End])/ 60.0,2))as 'run_time'
FROM
(select Machine,Dyelot,StartTime,EndTime ,
case 
when StartTime <  '2024-03-07 15:11:07' THEN  '2024-03-07 15:11:07'
ELSE StartTime
END  as 'Start',
case 
when EndTime >  '2024-03-08 15:11:07' THEN  '2024-03-08 15:11:07'
ELSE EndTime
END  as 'End'
from DispenserProduction 
where  EndTime > '2024-03-07 15:11:07'
)tab
GROUP BY Machine,Dyelot
) t1
LEFT JOIN 
(select 
Dispenser
,sum(case 
when EventType in ('5') then ROUND(DATEDIFF(MINUTE, [Start], [End])/ 60.0,2)
else 0
END)  as 'alarm'
,sum(case 
when EventType in ('6') then ROUND(DATEDIFF(MINUTE, [Start], [End])/ 60.0,2)
else 0
END)  as 'fix'
FROM
(
select Dispenser,EventType,
case 
when CreateDate <  '2024-03-07 15:11:07' THEN  '2024-03-07 15:11:07'
ELSE CreateDate
END  as 'Start',
case 
when FinishDate >  '2024-03-08 15:11:07' THEN  '2024-03-08 15:11:07'
ELSE FinishDate
END  as 'End'
from DispenserEvent 
where  FinishDate > '2024-03-07 15:11:07'
)TTAB
group by Dispenser

)
t2 
ON t1.Machine = t2.Dispenser
 GROUP BY Machine 

 )Y2
 ON Y1.Name = Y2.Machine
ORDER BY Y1.Name ASC
*/
$sql_machine = "SELECT 
Y1.Name
,COALESCE(Y2.run_time, 0) AS 'run_time'
,COALESCE(Y2.alarm, 0) AS 'alarm'
,COALESCE(Y2.fix, 0) AS 'fix'
FROM
(select [Name] FROM [DispenserMachines])
Y1

LEFT JOIN

(
select 
Machine
,sum(run_time) as 'run_time'
,sum(alarm) as 'alarm'
,sum(fix) as 'fix'
FROM 
(
SELECT 
Machine
,Dyelot
,SUM(ROUND(DATEDIFF(MINUTE, [Start], [End])/ 60.0,2))as 'run_time'
FROM
(select Machine,Dyelot,StartTime,EndTime ,
case 
when StartTime <  '$today_start' THEN  '$today_start'
ELSE StartTime
END  as 'Start',
case 
when EndTime >  '$today_end' THEN  '$today_end'
ELSE EndTime
END  as 'End'
from DispenserProduction 
where  EndTime > '$today_start'and EndTime <'$today_end'
)tab
GROUP BY Machine,Dyelot
) t1
LEFT JOIN 
(select 
Dispenser
,sum(case 
when EventType in ('5') then ROUND(DATEDIFF(MINUTE, [Start], [End])/ 60.0,2)
else 0
END)  as 'alarm'
,sum(case 
when EventType in ('6') then ROUND(DATEDIFF(MINUTE, [Start], [End])/ 60.0,2)
else 0
END)  as 'fix'
FROM
(
select Dispenser,EventType,
case 
when CreateDate <  '$today_start' THEN  '$today_start'
ELSE CreateDate
END  as 'Start',
case 
when FinishDate >  '$today_end' THEN  '$today_end'
ELSE FinishDate
END  as 'End'
from DispenserEvent 
where  FinishDate > '$today_start'
)TTAB
group by Dispenser

)
t2 
ON t1.Machine = t2.Dispenser
 GROUP BY Machine 

 )Y2
 ON Y1.Name = Y2.Machine
 where Y1.Name not in('null','LA-301PS')
ORDER BY Y1.Name ASC";
//$sql_machine = "select * from DyeMachines ORDER BY ID ASC";


$result_machine = sqlsrv_query($link, $sql_machine) or die("sql error" . sqlsrv_errors());

$activation=0;
$machine_num=0;
//抓取所有機台資料
while ($row_machine = sqlsrv_fetch_array($result_machine)) {
    $name_array[] = array($row_machine['Name']);
    $work_array[] = array((float)$row_machine['run_time']);
    $stop_array[] = array(24-(float)$row_machine['run_time']-(float)$row_machine['fix']-(float)$row_machine['alarm']);
    $fix_array[] = array((float)$row_machine['fix']);
    $alarm_array[] = array((float)$row_machine['alarm']);
    $activation+=(float)$row_machine['run_time']/24;
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
$activation=round($activation/$machine_num*100,2);
// var_dump(($machine_num));

$all_data_array[] = array("chart_name" => 'bar4', "machine" => $name_array, "work" => $work_array, "stop" => $stop_array, "fix" => $fix_array, "alarm" => $alarm_array, "activation" => $activation);

echo json_encode($all_data_array);
?>
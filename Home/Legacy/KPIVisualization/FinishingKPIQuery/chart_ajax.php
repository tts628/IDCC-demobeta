<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

$machine_name = $_POST['machine_name'];
if (
    stripos($machine_name, " OR ") !== false ||
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
    strlen($machine_name) > 15) {
    echo "detected sensitive cases<br>";
    $machine_name = null;
}
//$machine_name = 'A01';
$today_date = $_POST['day'];
//$today_date = '2020-10-29';
if (
    stripos($today_date, " OR ") !== false ||
    stripos($today_date, " ") !== false ||
    stripos($today_date, "%") !== false ||
    stripos($today_date, "'") !== false ||
    stripos($today_date, "{") !== false ||
    stripos($today_date, "}") !== false ||
    stripos($today_date, "[") !== false ||
    stripos($today_date, "]") !== false ||
    stripos($today_date, "(") !== false ||
    stripos($today_date, ")") !== false ||
    stripos($today_date, "<") !== false ||
    stripos($today_date, ">") !== false ||
    stripos($today_date, "#") !== false ||
    stripos($today_date, "!") !== false ||
    stripos($today_date, "|") !== false ||
    stripos($today_date, "&") !== false ||
    stripos($today_date, ";") !== false ||
    stripos($today_date, ":") !== false ||
    stripos($today_date, "$") !== false ||
    stripos($today_date, "*") !== false ||
    stripos($today_date, "=") !== false ||
    stripos($today_date, ",") !== false ||
    stripos($today_date, ".") !== false ||
    strlen($today_date) > 15) {
    echo "detected sensitive cases<br>";
    $today_date = null;
}

$today = date("Y-m-d");
$today_now = date("Y-m-d H:i:s");
$today_now_minus = strtotime($today_now);


//抓取當周(天) 每$day.' 23:59:59.999的資料
$day_name = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
$day_unit = array();
$day_unit_carry = 0;
$day = date("Y-m-d", strtotime('sunday last week', strtotime($today_date)));//取得這周星期一
for ($i = 1; $i <= 7; $i++) {

    $data_work = 0;
    $data_stop = 0;
    $data_fix = 0;
    $data_alarm = 0;
    $data_work_total = 0;
    $data_stop_total = 0;
    $data_fix_total = 0;
    $data_alarm_total = 0;

    $day1 = $day . ' 00:00:00.000';//設定小時
    $day1_minus = strtotime($day1);

    $day2 = $day . ' 23:59:59.999';//設定小時
    $day2_minus = strtotime($day2);
    if ($today == $day) {
        $day2 = $today_now;
        $day2_minus = $today_now_minus;
    }
    $sql = "select * from FinishProduction WHERE Machine = '$machine_name' AND StartTime < '$day2' AND EndTime > '$day1' order by StartTime asc";

    $result = sqlsrv_query($link, $sql) or die("sql error" . sqlsrv_errors());
    while ($row = sqlsrv_fetch_array($result)) {
        $data_work = 0;

        //  機器名稱
        $machine_name = $row['Machine'];
        //        $Dyelot = $row['Job'];

        //  工單開始時間
        $DyelotStartTime = ($row['StartTime']->format('Y-m-d H:i:s'));
        $DyelotStartTime_minus = strtotime($DyelotStartTime);

        //  工單結束時間
        $DyelotEndTime = ($row['EndTime']->format('Y-m-d H:i:s'));
        $DyelotEndTime_minus = strtotime($DyelotEndTime);
        // 狀態1 這小時一開始就有運作
        if ($DyelotStartTime <= $day1) {
            $data_work = 0;

            //1-1 已經運作完
            if ($DyelotEndTime > $day1 && $DyelotEndTime < $day2) {
                //            現在時間-今天開始-event=work
//                echo '狀態1-1';
//                echo '<br>';

                $data_work = (($DyelotEndTime_minus - $day1_minus) / 3600); //60s*60min /小時

            } else {
                //                echo '狀態1-1e';
//                echo '<br>';
                if ($data_work == 0) {
                    $data_work = 0;
                }
            }
            //1-2 還沒運作完
            if ($DyelotEndTime > $day2) {
                //            endtime-今天開始-event=work
//                echo '狀態1-2';
//                echo '<br>';


                $data_work = (($day2_minus - $day1_minus) / 3600); //60s*60min /小時

                //                echo '控制時間';
//                echo '<br>';


                //                echo json_encode($day1);
//                echo '<br>';
//                echo json_encode($day2);
//                echo '<br>';

            } else {
                //                echo '狀態1-2e';
//                echo '<br>';

            }
            //            echo '狀態1 運作時間';
//            echo json_encode($data_work);
//            echo '<br>';
        }

        //      狀態2 中間才開始運作
        if ($DyelotStartTime > $day1 && $DyelotStartTime < $day2) {

            $data_work = 0;
            //2-1 還沒做完
            if ($DyelotEndTime > $day2) {
                //            現在時間-[StartTime]-event=work

                //                echo '狀態2-1';
//                echo '<br>';


                $data_work = (($day2_minus - $DyelotStartTime_minus) / 3600); //60s*60min /小時
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
            //2-2 已經做完
            if ($DyelotEndTime <= $day2) {
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


            //            echo '狀態2 運作時間';
//            echo json_encode($data_work);
//            echo '<br>';
        }
        //         狀態2 end

        $data_work_total = $data_work_total + $data_work;


        //故障alarm時間
        //                -----alarm start------
        $sql_alarm = "select * from FinishEvent where Machine ='$machine_name' and CreateDate < '$day2' and FinishDate > '$day1' and EventType = '5' ";

        $result_alarm = sqlsrv_query($link, $sql_alarm, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

        $num_alarm = sqlsrv_num_rows($result_alarm);

        if ($num_alarm > 0) {
            //            echo '有故障';
//            echo '<br>';
            while ($row_alarm = sqlsrv_fetch_array($result_alarm)) {
                //                    從之前到中間
                if ($row_alarm['CreateDate'] <= $day1 && $row_alarm['FinishDate'] < $day2) {
                    //                        $create_alarm = ($row_alarm['CreateDate']->format('Y-m-d H:i:s'));
//                        $create_alarm_minus = strtotime($create_alarm);

                    $finish_alarm = ($row_alarm['FinishDate']->format('Y-m-d H:i:s'));
                    $finish_alarm_minus = strtotime($finish_alarm);

                    $data_alarm = (($finish_alarm_minus - $day1_minus) / 3600); //60s*60min /小時
                    $data_alarm_total = $data_alarm_total + $data_alarm;
                }
                //                    從之前到之後
                if ($row_alarm['CreateDate'] <= $day1 && $row_alarm['FinishDate'] >= $day2) {

                    $data_alarm = (($day2_minus - $day1_minus) / 3600); //60s*60min /小時
                    $data_alarm_total = $data_alarm_total + $data_alarm;
                }
                //                    從中間到之後
                if ($row_alarm['CreateDate'] > $day1 && $row_alarm['CreateDate'] < $day2 && $row_alarm['FinishDate'] >= $day2) {
                    $create_alarm = ($row_alarm['CreateDate']->format('Y-m-d H:i:s'));
                    $create_alarm_minus = strtotime($create_alarm);

                    $data_alarm = (($day2_minus - $create_alarm_minus) / 3600); //60s*60min /小時
                    $data_alarm_total = $data_alarm_total + $data_alarm;
                }
                //                    從中間到中間
                if ($row_alarm['CreateDate'] > $day1 && $row_alarm['CreateDate'] < $day2 && $row_alarm['FinishDate'] < $day2) {
                    $create_alarm = ($row_alarm['CreateDate']->format('Y-m-d H:i:s'));
                    $create_alarm_minus = strtotime($create_alarm);

                    $finish_alarm = ($row_alarm['FinishDate']->format('Y-m-d H:i:s'));
                    $finish_alarm_minus = strtotime($finish_alarm);

                    $data_alarm = (($finish_alarm_minus - $day1_minus) / 3600); //60s*60min /小時
                    $data_alarm_total = $data_alarm_total + $data_alarm;
                }
            }
        } else {
            //            echo '沒故障';
//            echo '<br>';

            $data_alarm_total = 0;
        }
        //                -----alarm end------

        //維修fix時間
        //                -----fix start------

        $sql_fix = "select * from FinishEvent where Machine ='$machine_name' and CreateDate < '$day2' and FinishDate > '$day1' and EventType = '6' ";

        $result_fix = sqlsrv_query($link, $sql_fix, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

        $num_fix = sqlsrv_num_rows($result_fix);

        if ($num_fix > 0) {
            //            echo '有故障';
//            echo '<br>';
            while ($row_fix = sqlsrv_fetch_array($result_fix)) {
                //                    從之前到中間
                if ($row_fix['CreateDate'] <= $day1 && $row_fix['FinishDate'] < $day2) {
                    //                        $create_alarm = ($row_alarm['CreateDate']->format('Y-m-d H:i:s'));
//                        $create_alarm_minus = strtotime($create_alarm);

                    $finish_fix = ($row_fix['FinishDate']->format('Y-m-d H:i:s'));
                    $finish_fix_minus = strtotime($finish_fix);

                    $data_fix = (($finish_fix_minus - $day1_minus) / 3600); //60s*60min /小時
                    $data_fix_total = $data_fix_total + $data_fix;
                }
                //                    從之前到之後
                if ($row_fix['CreateDate'] <= $day1 && $row_fix['FinishDate'] >= $day2) {

                    $data_fix = (($day2_minus - $day1_minus) / 3600); //60s*60min /小時
                    $data_fix_total = $data_fix_total + $data_fix;
                }
                //                    從中間到之後
                if ($row_fix['CreateDate'] > $day1 && $row_fix['CreateDate'] < $day2 && $row_fix['FinishDate'] >= $day2) {
                    $create_fix = ($row_alarm['CreateDate']->format('Y-m-d H:i:s'));
                    $create_alarm_minus = strtotime($create_fix);

                    $data_fix = (($day2_minus - $create_alarm_minus) / 3600); //60s*60min /小時
                    $data_fix_total = $data_fix_total + $data_fix;
                }
                //                    從中間到中間
                if ($row_fix['CreateDate'] > $day1 && $row_fix['CreateDate'] < $day2 && $row_fix['FinishDate'] < $day2) {
                    $create_fix = ($row_alarm['CreateDate']->format('Y-m-d H:i:s'));
                    $create_alarm_minus = strtotime($create_fix);

                    $finish_fix = ($row_fix['FinishDate']->format('Y-m-d H:i:s'));
                    $finish_fix_minus = strtotime($finish_fix);

                    $data_fix = (($finish_fix_minus - $day1_minus) / 3600); //60s*60min /小時
                    $data_fix_total = $data_fix_total + $data_fix;
                }

            }
        } else {
            //            echo '沒維修';
//            echo '<br>';

            $data_fix_total = 0;
        }
        //                -----fix end------


        //while
    }

    //    算出最後work
    $data_work_total = $data_work_total - $data_alarm_total - $data_fix_total;

    //     算出最後stop停機時間,非閒置時間
    if ($day > $today) {
        $name_array[] = array(0);
        $work_array[] = array(0);
        $stop_array[] = array(0);
        $fix_array[] = array(0);
        $alarm_array[] = array(0);
        $activation = (0);
    }
    if ($day == $today) {
        $today_start = $today . ' 00:00:00.000';
        $today_start_minus = strtotime($today_start);

        $time_minus = (($today_now_minus - $today_start_minus) / 3600);
        $data_stop_total = $time_minus - $data_work_total + $data_alarm_total + $data_fix_total;
        $name_array[] = array($machine_name);
        $work_array[] = array($data_work_total);
        $stop_array[] = array($data_stop_total);
        $fix_array[] = array($data_fix_total);
        $alarm_array[] = array($data_alarm_total);
        $activation = ((($time_minus - $data_stop_total) / 24) * 100);
        $activation = round($activation, 2);
    }
    if ($day < $today) {

        $data_stop_total = 24 - $data_work_total + $data_alarm_total + $data_fix_total;
        $name_array[] = array($machine_name);
        $work_array[] = array($data_work_total);
        $stop_array[] = array($data_stop_total);
        $fix_array[] = array($data_fix_total);
        $alarm_array[] = array($data_alarm_total);
        $activation = (((24 - $data_stop_total) / 24) * 100);
        $activation = round($activation, 2);
    }

    //    echo json_encode($activation);
//    echo '<br>';

    //    echo '-------------------';
//    echo '<br>';

    //抓取完所有機台

    $activation_array[] = array("name" => $day . '(' . $day_name[$i - 1] . ')', "y" => $activation);
    $four_array[] = array("name" => $day . '(' . $day_name[$i - 1] . ')');
    //加一天
    $day = date('Y-m-d', strtotime('+1 day', strtotime($day)));

    $data_work_total = 0;
    $data_stop_total = 0;
    $data_fix_total = 0;
    $data_alarm_total = 0;
}
// end---------------------------------------
$four_array[] = array("work" => $work_array, "stop" => $stop_array, "fix" => $fix_array, "alarm" => $alarm_array);


$All_array[0] = array("name" => 'activation_data', "data" => $activation_array);
$All_array[1] = array("name" => 'four_data', "data" => $four_array);

echo json_encode($All_array);
?>
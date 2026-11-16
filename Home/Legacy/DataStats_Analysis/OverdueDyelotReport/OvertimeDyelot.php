<?php

if ($_GET["lang"] == 'en-US') {
    $name["title"] = 'Top 20 Overtime Work Orders of the Day';
    $name["Working_Card_Number"] = 'Working Card';
    $name["Estimated_Time"] = 'Estimated';
    $name["Actual_Time"] = 'Actual';
    $name["Exceeded_Time"] = 'Exceeded';
    $name["Machine"] = 'Machine';
    $name["Program"] = 'Program';
    $name["Start_Time"] = 'Start Time';
    $name["Alarms"] = 'Alarms';
    $name["minute"] = ' hr';

} else {
    $name["title"] = '本日TOP 20 超時工單';
    $name["Working_Card_Number"] = '工卡號';
    $name["Estimated_Time"] = '預計時間';
    $name["Actual_Time"] = '實際時間';
    $name["Exceeded_Time"] = '超出時間';
    $name["Machine"] = '機台';
    $name["Program"] = '程式';
    $name["Start_Time"] = '開始時間';
    $name["Alarms"] = '警報';
    $name["minute"] = ' 小時';

}
date_default_timezone_set('UTC+8');

// include_once("../common/database_central.php");

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);


//$params = array("2023-03-05"); //array(date("Y-m-d"));
$today = date("Y-m-d"); // 取得今天日期
$yesterday = date("Y-m-d", strtotime("-0 day", strtotime($today))); // 使用 strtotime 函數將日期減一天
$params = array($yesterday); // 將減一天後的日期設定為 $params   
// $sql = "SELECT TOP (20) Dyelot,Color,StandardTime,RunTime,Machine,ProgramsUsed,StartTime,EOCAlarms 
// FROM Dyelots 
// WHERE StartTime > ? AND (EOCAlarms IS NOT NULL) AND (RunTime > StandardTime)
// ORDER BY (RunTime - StandardTime) DESC";

$sql = "SELECT　Dyelot,Color,StandardTime,ProcessTime as 'RunTime',StartTime,Machine,Program　as 'ProgramsUsed'
    FROM [DyeProduction] 
    WHERE CAST(EndTime AS DATE)　 = ? AND (ProcessTime > StandardTime) 
    -- AND State = 3
    ORDER BY (ProcessTime - StandardTime) DESC";

$stmt = sqlsrv_query($link, $sql, $params);
?>
<html>

<head>
    <title>流亞科技</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js">
    </script>

    <style>
        .table thead th,
        .table thead tr {
            border: none;
        }

        .table tbody td,
        .table tbody th {
            max-height: 1px;
        }

        .table-wrapper {
            height: 88vh;
        }

        .table-wrapper .table {
            max-width: 100% !important;
            /* Set the table width to 100% of viewport width */
        }

        /*
            .custom-table tbody tr:nth-of-type(odd) {
                background-color: #e0d6b8;
            }
            .custom-table tbody tr:nth-of-type(even) {
                background-color: #fefefe;
            }
            */
    </style>
</head>

<body style="background-color:#272727">
    <div class="container-fluid">
        <div class="body-wrapper">
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="border-top pt-2 pb-2 mb-2">
                        <!-- <h1 class="mb-0 fw-bold" style="color:#fff"><b>本日TOP 20 超時工單</b></h1> -->
                        <h1 class="mb-0 fw-bold" style="color:#fff"><b><?php echo $name["title"]; ?></b></h1>

                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                <div class="table-wrapper table-responsive">
                    <table class="text-nowrap table-sm table table-striped m-auto font-weight-bold"
                        style="font-size: 1.6em;">
                        <thead>
                            <tr style="color:#fff">
                                <!-- <th>工卡號</th>
                                    <th>&nbsp;</th>
                                    <th>預計時間</th>
                                    <th>實際時間</th>
                                    <th>超出時間</th>
                                    <th>機台</th>
                                    <th>程式</th>
                                    <th>開始時間</th>
                                    <th>警報</th> -->
                                <th><?php echo $name["Working_Card_Number"]; ?></th>
                                <th>&nbsp;</th>
                                <th><?php echo $name["Estimated_Time"]; ?></th>
                                <th><?php echo $name["Actual_Time"]; ?></th>
                                <th><?php echo $name["Exceeded_Time"]; ?></th>
                                <th><?php echo $name["Machine"]; ?></th>
                                <th><?php echo $name["Program"]; ?></th>
                                <th><?php echo $name["Start_Time"]; ?></th>
                                <!-- <th><?php echo $name["Alarms"]; ?></th> -->
                            </tr>
                        </thead>
                        <tbody class="table-bordered table-dark">
                            <?php
                            $rows = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
                            print_r($rows);
                            while (($rows = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
                                //converting colors from 24-bit to hex
                                $color = "#" . str_pad(dechex($rows["Color"]), 6, "0", STR_PAD_LEFT);

                                //get distinct alarm values
                                $alarms = preg_replace('/\d+,\d{4}\/\d{1,2}\/\d{1,2} [^\s]+ [^\s]+,(\d{4}\/\d{1,2}\/\d{1,2} [^\s]+ [^\s]+)/', '', $rows["EOCAlarms"]);
                                // Split the input string into an array using the comma separator
                                $alarms_array = explode(',', (rtrim($alarms, ',')));
                                // Remove any duplicates from the array and trim any whitespace
                                $unique_alarms = array_unique(array_map('trim', $alarms_array));
                                $alarms_display = implode(',', $unique_alarms);

                                //convert and calculate time strings
                                $hours = floor($rows["StandardTime"] * 24); // get the number of hours
                                $minutes = round(($rows["StandardTime"] * 24 - $hours) * 60); // get the number of minutes
                                $StandardTime = sprintf("%02d:%02d", $hours, $minutes); // format the time string
                            
                                $hours = floor($rows["RunTime"] * 24); // get the number of hours
                                $minutes = round(($rows["RunTime"] * 24 - $hours) * 60); // get the number of minutes
                                $RunTime = sprintf("%02d:%02d", $hours, $minutes); // format the time string
                            
                                if ($rows["RunTime"] != "") {
                                    $OverTime = round(($rows["RunTime"] * 24) - ($rows["StandardTime"] * 24), 1) . $name["minute"];
                                } else {
                                    $OverTime = "";
                                }
                                $OverTime = ($OverTime > 0) ? $OverTime : "";

                                echo "<tr>";
                                echo "<th>" . $rows["Dyelot"] . "</th>";
                                echo "<td><div class='mx-auto mt-2' style='background-color:$color; width: 30px; height: 20px;'></div></td>";
                                echo "<td>" . $StandardTime . "</td>";
                                echo "<td>" . $RunTime . "</td>";
                                echo "<td>" . $OverTime . "</td>";
                                echo "<td>" . $rows["Machine"] . "</td>";
                                echo "<td>" . $rows["ProgramsUsed"] . "</td>";
                                echo "<td>" . $rows["StartTime"]->format("m-d H:i") . "</td>";
                                // if(strlen($alarms_display) > 50) {
                                //     echo "<td>" . mb_substr($alarms_display, 0, 50, 'UTF-8') . "...</td>";
                                // }
                                // else {
                                //     echo "<td>" . $alarms_display . "</td>";
                                // }
                                echo "</tr>";
                            }
                            if (!isset($OverTime)) {
                                echo "<tr>";
                                echo "<th colspan='9' class='text-center bg-success text-white'>本日暫無超時工單</th>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
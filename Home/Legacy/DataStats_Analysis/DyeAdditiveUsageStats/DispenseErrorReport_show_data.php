<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");
$lang = new Language();
$lang->load("general");

$time1 = -microtime(true);
function Readcsv($filename)
{
    //fetching data from local csv
    $f = fopen($filename, 'r');
    if ($f === false) {
        die('Cannot raed the file ' . $filename);
    }
    while (($row = fgetcsv($f)) !== false) {
        $readData[] = $row;
    }
    fclose($f);
    if (!isset($readData)) {
        $readData = [[0]];
    }
    return $readData;
}


//get data from csv
$data = Readcsv("DispenseErrorReport_data.csv");
//trim data
foreach ($data as $key => $element) {
    if ($key > 29) {
        break;
    }
    $Tdata[] = $data[$key];
}
//get client side time counter
if (!isset($_REQUEST["counter"])) {
    $counter = 0;
} else {
    $counter = $_REQUEST["counter"];
}
//Data process
if ($counter == 0) {
    $rangeStart = "0";
    $rangeEnd = "9";
}
if ($counter == 1) {
    $rangeStart = "10";
    $rangeEnd = "19";
}
if ($counter == 2) {
    $rangeStart = "20";
    $rangeEnd = "29";
}
$slicedata = array();
$todayCount = 0;
$todayErr = 0;
$todayN = 0;
foreach ($data as $key => $element) {
    if ($key < 30) {
        if ($key >= $rangeStart && $key <= $rangeEnd) {
            $slicedata[] = $element;
            $status[] = ($data[$key])[9];
        }
    }
    if (($data[$key])[9] != 0) {
        $todayCount += 1;
        $todayErr += 1;
    } else {
        $todayCount += 1;
        $todayN += 1;
    }
}
date_default_timezone_set('UTC+8');
$yesterday = date("Y-m-d", (strtotime(date("Y-m-d") . " 00:00:00") - 60 * 60));
?>
<div class="container-fluid" style="height:40vh;width:98vw;color:white">
    <div class="row">
        <div>
            <h2 class="p-2"><b><?php echo $lang->line("general.dye_auxiliary_metering_report"); ?> Phân chia lỗi Danh sách</b>
                <h2>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div id="error column" style="height: 30vh;"></div>
        </div>
    </div>
</div>
<!--Inidcator and table-->
<div class="container-fluid" style="height:65vh;width:98vw;font-size:large;color:white">
    <div class="row">
        <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2">
            <div style="width:22vh" class="mx-auto my-5">
                <div class="row text-center">
                    <div class="col border border-white" style="background-color:#00bcd4">
                        <h1><b><?php echo $todayCount; ?></b></h3>
                    </div>
                </div>
                <div class="row text-center mb-4">
                    <div class="col border border-white p-1" style="background-color:#0094a8"><b
                            style="font-size:large"><?php echo $lang->line("general.metering_times"); ?><br>Toàn bộ</b></div>
                </div>
                <div class="row text-center">
                    <div class="col border border-white" style="background-color:#00d45a">
                        <h1><b><?php echo $todayN; ?></b></h3>
                    </div>
                </div>
                <div class="row text-center mb-4">
                    <div class="col border border-white p-1" style="background-color:#00b04b"><b
                            style="font-size:large"><?php echo $lang->line("general.metering_normal"); ?><br>Tốt</b></div>
                </div>
                <div class="row text-center">
                    <div class="col border border-white" style="background-color:#ff5252">
                        <h1><b><?php echo $todayErr; ?></b></h3>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col border border-white p-1" style="background-color:#ff0000"><b
                            style="font-size:large"><?php echo $lang->line("general.metering_abnormal"); ?><br>lỗi</b></div>
                </div>
            </div>
        </div>
        <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10 col-xl-10">
            <div class="row mb-2 pl-3">
                <?php
                if ($counter == 0) {
                    ?>
                    <div class="col-md-1">01-10</div>
                    <?php
                } else {
                    ?>
                    <div class="col-md-1" style="color:#5e6060;">01-10</div>
                    <?php
                }
                ?>
                <?php
                if ($counter == 1) {
                    ?>
                    <div class="col-md-1">11-20</div>
                    <?php
                } else {
                    ?>
                    <div class="col-md-1" style="color:#5e6060;">11-20</div>
                    <?php
                }
                ?>
                <?php
                if ($counter == 2) {
                    ?>
                    <div class="col-md-1">21-30</div>
                    <?php
                } else {
                    ?>
                    <div class="col-md-1" style="color:#5e6060;">21-30</div>
                    <?php
                }
                ?>
            </div>
            <div class="row">
                <table
                    class="table table-sm table-bordered table-striped table-dark text-center text-white m-auto w3-animate-left"
                    style="font-size:x-large">
                    <thead style="font-size:large">
                        <tr>
                            <th><?php echo $lang->line("general.dyeing_machine"); ?><br>Máy móc</th>
                            <th><?php echo $lang->line("general.metering_machine"); ?><br>Máy pha chế</th>
                            <th><?php echo $lang->line("general.work_card_number"); ?><br>Số thứ tự làm việc</th>
                            <th><?php echo $lang->line("general.material_code"); ?><br>Mã sản phẩm</th>
                            <th><?php echo $lang->line("general.target_weight"); ?><br>mục tiêu</th>
                            <th><?php echo $lang->line("general.actual_weight"); ?><br>thực tế</th>
                            <th><?php echo $lang->line("general.metering_error"); ?><br>lỗi</th>
                            <th><?php echo $lang->line("general.metering_start_time"); ?><br>Thời gian bắt đầu</th>
                            <th><?php echo $lang->line("general.abnormal_reason"); ?><br>lý do</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($slicedata as $key => $element) {
                            ?>
                            <tr>
                                <td><?php echo $element[0]; ?></td>
                                <td><?php echo $element[1]; ?></td>
                                <td><?php echo $element[2]; ?></td>
                                <td><?php echo $element[3]; ?></td>
                                <td><?php echo $element[4]; ?></td>
                                <td><?php echo $element[5]; ?></td>
                                <td><?php echo $element[6]; ?></td>
                                <td><?php echo $element[7]; ?></td>
                                <?php
                                if ($status[$key] == 1) {
                                    ?>
                                    <td style="background-color:yellow"></td>
                                    <?php
                                } elseif ($status[$key] == 2) {
                                    ?>
                                    <td style="background-color:red"></td>
                                    <?php
                                } else {
                                    ?>
                                    <td>
                                        <div></div>
                                    </td>
                                    <?php
                                }
                                ?>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
$ChartData = Readcsv("DispenseErrorReport_yesterday_chart.csv");
$clientRoot = explode('/', $_SERVER["REQUEST_URI"])[1];
?>
<script>
    //using highcharts to generate charts with data from local server csv files
    Highcharts.chart('error column', {
        data: {
            csvURL: '<?= $clientRoot; ?>/Home/Legacy/DataStats_Analysis/DyeAdditiveUsageStats/DispenseErrorReport_yesterday_chart.csv'
        },
        legend: {
            enabled: false
        },
        series: [{
            data: [
                <?php echo ($ChartData[1])[1] ?>,
                <?php echo ($ChartData[2])[1] ?>,
                <?php echo ($ChartData[3])[1] ?>,
                <?php echo ($ChartData[4])[1] ?>,
                <?php echo ($ChartData[5])[1] ?>,
                <?php echo ($ChartData[6])[1] ?>,
                <?php echo ($ChartData[7])[1] ?>,
                <?php echo ($ChartData[8])[1] ?>,
                <?php echo ($ChartData[9])[1] ?>,
                <?php echo ($ChartData[10])[1] ?>,
                <?php echo ($ChartData[11])[1] ?>,
                <?php echo ($ChartData[12])[1] ?>,
                <?php echo ($ChartData[13])[1] ?>,
                <?php echo ($ChartData[14])[1] ?>,
                <?php echo ($ChartData[15])[1] ?>,
                <?php echo ($ChartData[16])[1] ?>,
                <?php echo ($ChartData[17])[1] ?>,
                <?php echo ($ChartData[18])[1] ?>,
                <?php echo ($ChartData[19])[1] ?>,
                <?php echo ($ChartData[20])[1] ?>,
                <?php echo ($ChartData[21])[1] ?>,
                <?php echo ($ChartData[22])[1] ?>,
                <?php echo ($ChartData[23])[1] ?>,
                <?php echo ($ChartData[24])[1] ?>,
            ],
            type: 'column',
            color: '#ff0000'
        }],
        chart: {
            animation: false,
            backgroundColor: null,
            type: 'column'
        },
        title: {
            text: "<?php echo $yesterday; ?> <?php echo $lang->line("general.abnormal_times_per_hour"); ?>",
            style: {
                color: '#FFFFFF',
                fontWeight: 'bold'
            }
        },
        yAxis: {
            labels: {
                style: {
                    color: '#ffffff'
                }
            },
            allowDecimals: false,
            title: {
                text: '<?php echo $lang->line("general.abnormal_times"); ?>',
                style: {
                    color: '#ffffff',
                    fontSize: '20px',
                    fontWeight: 'bold'
                }
            }
        },
        xAxis: {
            labels: {
                style: {
                    color: '#ffffff'
                }
            }
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            series: {
                animation: false
            },
            column: {
                dataLabels: {
                    enabled: true,
                    //distance: -80,
                    color: '#ffffff',
                    format: '<b>{point.y:.0f}</b>',
                    style: {
                        fontSize: "20px"
                    }
                }
            }
        },
        tooltip: {
            pointFormat: '<span style="color:black"><b>異常次數: {point.y}</b>',
            shared: true
        },
        noData: {
            style: {
                fontWeight: 'bold',
                color: '#ffffff'
            }
        }
    });
</script>

<?php
//for time efficient check
$time1 += microtime(true);
//echo "<h4>Took $time1 seconds for execution</h4>";
?>
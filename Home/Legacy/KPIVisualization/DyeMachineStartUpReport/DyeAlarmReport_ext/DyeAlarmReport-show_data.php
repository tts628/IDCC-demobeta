<?php
$time_start = microtime(true);
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
$data = Readcsv("./DyeAlarmReport_final_data.csv");
//get client side time counter
if (isset($_REQUEST["counter"])) {
    $counter = $_REQUEST["counter"];
} else {
    $counter = 0;
}

$offsetCount = 0;
if ($counter >= 5 && $counter <= 9) {
    $offsetCount = 10;
} elseif ($counter >= 10 && $counter <= 14) {
    $offsetCount = 20;
} elseif ($counter >= 10 && $counter <= 14) {
    $offsetCount = 30;
}

$slicedata = array();
//Data process
$BWork = 0;
$BIdle = 0;
$SWork = 0;
$SIdle = 0;
$MWork = 0;
$MIdle = 0;
$TP = array();
$flag0 = 0;
$flag1 = 0;
$flag2 = 0;
$flag3 = 0;
$flag4 = 0;
$flag5 = 0;
$flag6 = 0;
foreach ($data as $key => $row) {
    if ($key > $offsetCount && $key <= $offsetCount + 10) {
        $slicedata[] = $row;
        $T1 = substr((string) $row[3], 0, -1);
        $T2 = substr((string) $row[3], -1);
        $TP[] = $T1 . "." . $T2 . "°C";
    }

    if ($row[8] <= 100) {
        if ($row[9] == 1) {
            $SWork += 1;
        } else {
            $SIdle += 1;
        }
    } elseif ($row[8] <= 800) {
        if ($row[9] == 1) {
            $MWork += 1;
        } else {
            $MIdle += 1;
        }
    } else {
        if ($row[9] == 1) {
            $BWork += 1;
        } else {
            $BIdle += 1;
        }
    }

    if ($key < 10 && $row[6] !== "") {
        $flag0 = 1;
    }

    if ($key >= 10 && $key < 20 && $row[6] !== "") {
        $flag1 = 1;
    }

    if ($key >= 20 && $key < 30 && $row[6] !== "") {
        $flag2 = 1;
    }

    if ($key >= 30 && $key < 40 && $row[6] !== "") {
        $flag3 = 1;
    }
}

$machineGroup1 = $data[0][0] . "-" . $data[10][0];
$machineGroup2 = $data[11][0] . "-" . $data[20][0];
$machineGroup3 = $data[21][0] . "-" . $data[30][0];
?>
<div class="container-fluid" style="height:26vh;width:98vw;color:white;">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="p-2 my-2" style="font-size:2.3vw"><b>染機開缸報告 Báo Cáo Khởi Động Máy Nhuộm</b></div>
            <div class="row text-center" style="font-size:2.3vw">
                <div class="col border border-white px-2 mx-2 py-2" style="background-color:#00bcd4">
                    <b><?php echo $SWork; ?></b>
                </div>
                <div class="col border border-white px-2 mx-2 py-2" style="background-color:#ffc107">
                    <b><?php echo $SIdle; ?></b>
                </div>
                <div class="col border border-white px-2 mx-2 py-2" style="background-color:#9188FF">
                    <b><?php echo $MWork; ?></b>
                </div>
                <div class="col border border-white px-2 mx-2 py-2" style="background-color:#ffc107">
                    <b><?php echo $MIdle; ?></b>
                </div>
                <div class="col border border-white px-2 mx-2 py-2" style="background-color:#00d45a">
                    <b><?php echo $BWork; ?></b>
                </div>
                <div class="col border border-white px-2 mx-2 py-2" style="background-color:#ffc107">
                    <b><?php echo $BIdle; ?></b>
                </div>
                <div class="col border border-white px-2 mx-2 py-2" style="background-color:#ff5252">
                    <b><?php echo ($data[0])[11]; ?></b>
                </div>
            </div>
            <div class="row text-center" style="font-size:1.1vw">
                <div class="col border border-white px-2 mx-2 py-2" style="background-color:#0094a8">
                    <b>小缸運行</b><br><b>Máy nhỏ đang chạy</b>
                </div><!--Máy đang chạy-->
                <div class="col border border-white px-2 mx-2 py-2" style="background-color:#daa100">
                    <b>小缸待機</b><br><b>Dừng máy nhỏ</b>
                </div><!--Máy dừng-->
                <div class="col border border-white px-2 mx-2 py-2" style="background-color:#9188FF">
                    <b>中缸運行</b><br><b>Máy lớn đang chạy</b>
                </div><!--Máy đang chạy-->
                <div class="col border border-white px-2 mx-2 py-2" style="background-color:#daa100">
                    <b>中缸待機</b><br><b>Dừng máy lớn</b>
                </div><!--Máy dừng-->
                <div class="col border border-white px-2 mx-2 py-2" style="background-color:#00b04b">
                    <b>大缸運行</b><br><b>Máy lớn đang chạy</b>
                </div><!--Máy đang chạy-->
                <div class="col border border-white px-2 mx-2 py-2" style="background-color:#daa100">
                    <b>大缸待機</b><br><b>Dừng máy lớn</b>
                </div><!--Máy dừng-->
                <div class="col border border-white px-2 mx-2 py-2" style="background-color:#ff0000"><b>警報</b><br><b>Báo
                        thức</b></div>
            </div>
        </div><!--
        <div class="col-xs-6 col-sm-6 col-md-3 col-lg-2 col-xl-2 my-4">
            <div id="Bcontainer" style="height: 32vh;"></div>
        </div>
        <div class="col-xs-6 col-sm-6 col-md-3 col-lg-2 col-xl-2 my-4">
            <div id="Scontainer" style="height: 32vh;"></div>
        </div>-->
    </div>
</div>
<!--Alert icon-->
<div class="row text-center" style="width:99vw;color:white">
    <?php
    if ($flag0 == 1) {
        ?>
        <div class="col-md-1">
            <div><img src="./img/alert.png" alt="Alert" style="width:50%; height:50%; object-fit:content"></div>
        </div>
        <?php
    } else {
        ?>
        <div class=" col-md-1">
            <div><img src="./img/alert_negative.png" alt="Alert" style="width:50%; height:50%; object-fit:content"></div>
        </div>
        <?php
    }
    if ($flag1 == 1) {
        ?>
        <div class="col-md-1">
            <div><img src="./img/alert.png" alt="Alert" style="width:50%; height:50%; object-fit:content"></div>
        </div>
        <?php
    } else {
        ?>
        <div class=" col-md-1">
            <div><img src="./img/alert_negative.png" alt="Alert" style="width:50%; height:50%; object-fit:content"></div>
        </div>
        <?php
    }
    if ($flag2 == 1) {
        ?>
        <div class="col-md-1">
            <div><img src="./img/alert.png" alt="Alert" style="width:50%; height:50%; object-fit:content"></div>
        </div>
        <?php
    } else {
        ?>
        <div class=" col-md-1">
            <div><img src="./img/alert_negative.png" alt="Alert" style="width:50%; height:50%; object-fit:content"></div>
        </div>
        <?php
    }
    if ($flag3 == 1) {
        ?>
        <div class="col-md-1">
            <div><img src="./img/alert.png" alt="Alert" style="width:50%; height:50%; object-fit:content"></div>
        </div>
        <?php
    } else {
        ?>
        <div class=" col-md-1">
            <div><img src="./img/alert_negative.png" alt="Alert" style="width:50%; height:50%; object-fit:content"></div>
        </div>
        <?php
    }
    ?>
</div>
<!--Inidcator and table-->
<div class="container-fluid" style="width:99vw;font-size:1vw;color:white">
    <div class="row mb-3 pl-1">
        <?php
        if ($counter >= 0 && $counter <= 4) {
            ?>
            <div class="col-md-1"><?php echo $machineGroup1; ?></div>
            <?php
        } elseif ($flag0 == 1) {
            ?>
            <div class="col-md-1" style="color:#ff0000;"><?php echo $machineGroup1; ?></div>
            <?php
        } else {
            ?>
            <div class="col-md-1" style="color:#5e6060;"><?php echo $machineGroup1; ?></div>
            <?php
        }
        ?>
        <?php
        if ($counter >= 5 && $counter <= 9) {
            ?>
            <div class="col-md-1"><?php echo $machineGroup2; ?></div>
            <?php
        } elseif ($flag1 == 1) {
            ?>
            <div class="col-md-1" style="color:#ff0000;"><?php echo $machineGroup2; ?></div>
            <?php
        } else {
            ?>
            <div class="col-md-1" style="color:#5e6060;"><?php echo $machineGroup2; ?></div>
            <?php
        }
        ?>
        <?php
        if ($counter >= 10 && $counter <= 14) {
            ?>
            <div class="col-md-1"><?php echo $machineGroup3; ?></div>
            <?php
        } elseif ($flag2 == 1) {
            ?>
            <div class="col-md-1" style="color:#ff0000;"><?php echo $machineGroup3; ?></div>
            <?php
        } else {
            ?>
            <div class="col-md-1" style="color:#5e6060;"><?php echo $machineGroup3; ?></div>
            <?php
        }
        ?>
    </div>
    <div class="row">
        <?php
        if ($counter == 0 || $counter == 5 || $counter == 10 || $counter == 15) {
            ?>
            <table
                class="table table-sm table-bordered table-striped table-dark text-center text-white m-auto w3-animate-left"
                style="font-size:1.1vw">
                <?php
        } else {
            ?>
                <table class="table table-sm table-bordered table-striped table-dark text-center text-white m-auto"
                    style="font-size:1.1vw">
                    <?php
        }
        ?>
                <thead style="font-size:0.9vw">
                    <tr>
                        <th>機台<br>Máy móc</th>
                        <th>警報名稱<br>Tên báo thức</th>
                        <th>工卡號<br>ố thứ tự làm việc</th>
                        <th>主缸溫度<br>Nhiệt độ</th>
                        <th>染程<br>Quy trình nhuộm</th>
                        <th>步驟<br>Bươc</th>
                        <th>警報開始時間<br>Thời gian bắt đầu</th>
                        <th>持續時間<br>éo dài phút</th>
                    </tr>
                </thead>
                <tbody style="font-size:0.9vw">
                    <?php
                    foreach ($slicedata as $key => $element) {
                        if ($element[6] != "") {
                            ?>
                            <tr style="background-color:red">
                                <?php
                        } else {
                            ?>
                            <tr>
                                <?php
                        }
                        ?>
                            <td><?php echo $element[0]; ?></td>
                            <td><?php echo $element[1]; ?></td>
                            <td><?php echo $element[2]; ?></td>
                            <td><?php echo $TP[$key]; ?></td>
                            <td><?php echo $element[4]; ?></td>
                            <td><?php echo $element[5]; ?></td>
                            <td><?php echo $element[6]; ?></td>
                            <td><?php echo $element[7]; ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
    </div>
</div>
<!--
<script>
    //using highcharts to generate charts with data from local server csv files
    Highcharts.chart('Bcontainer',{
        series: [{
            name: "大缸",
            data:[
                {name: "運行", y: <?php //echo $BWork ?>, color: '#00d45a'},
                {name: "待機", y: <?php //echo $BIdle ?>, color: '#ffc107'}
            ],
        }],
        chart:{
            backgroundColor: null,
            type: 'pie'
        },
        title:{
            text: "大缸機台運行狀況",
            style: {
                color: '#FFFFFF',
                fontWeight: 'bold'
            }
        },
        yAxis: {
            allowDecimals: false
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            series: {
                animation: false
            },
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    distance: -80,
                    color: '#ffffff',
                    format: '<b>{point.name}</b> {point.percentage:.0f}%',
                    filter: {
                        property: 'percentage',
                        operator: '>=',
                        value: 50   
                    },
                    style: {
                        fontSize: "20px"
                    }
                }
            }
        },
        tooltip: {
            pointFormat: '<span style="color:black">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.1f}%)<br/>',
            shared: true
        },
        noData: {
            style: {
                fontWeight: 'bold',
                color: '#ffffff'
            }
        }
    });
    Highcharts.chart('Scontainer',{
        series: [{
            name: "小缸",
            data:[
                {name: "運行", y: <?php //echo $SWork ?>, color: '#00bcd4'},
                {name: "待機", y: <?php //echo $SIdle ?>, color: '#ffc107'}
            ],
        }],
        chart:{
            backgroundColor: null,
            type: 'pie'
        },
        title:{
            text: "小缸機台運行狀況",
            style: {
                color: '#FFFFFF',
                fontWeight: 'bold'
            }
        },
        yAxis: {
            allowDecimals: false
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            series: {
                animation: false
            },
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    distance: -80,
                    color: '#ffffff',
                    format: '<b>{point.name}</b> {point.percentage:.0f}%',
                    filter: {
                        property: 'percentage',
                        operator: '>=',
                        value: 50   
                    },
                    style: {
                        fontSize: "20px"
                    }
                }
            }
        },
        tooltip: {
            pointFormat: '<span style="color:black">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.1f}%)<br/>',
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
-->

<?php
$time_end = microtime(true);
//for time efficient check
//printf("Took %f seconds for execution<br>", $time_end - $time_start);
?>
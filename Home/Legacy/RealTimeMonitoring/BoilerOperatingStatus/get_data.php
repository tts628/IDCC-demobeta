<?php
// require_once('../../common/database.php');

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

$sql = "SELECT TOP (1) * FROM BoilerMachine_Steam ORDER BY Time DESC";
$stmt = sqlsrv_query($link, $sql);
while (($rows = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
    $data[] = $rows["Time"]->format('Y-m-d H:i:s');
    $waterFlag = $rows["WaterLevelLow"];
    $temperature = $rows["FurnaceTemp"];
    $time = $rows["Time"]->format("Y-m-d H:i:s");
    $flow = $rows["Flow"];
    $steamNum = $rows["SteamPressure"];
    $stoker = $rows["StokerSpeed"];
    $blower = $rows["BlowerSpeed"];
    $motorFlag = $rows["MotorOverload"];
    $ventilator = $rows["VentilatorSpeed"];
}


// 示範水位低
$waterFlag = 1;

if ($waterFlag == 0) {
    $waterLevelButton = "gray";
} else {
    $waterLevelButton = "red";
}

if ($motorFlag == 0) {
    $motorOverloadButton = "gray";
} else {
    $motorOverloadButton = "red";
}

$sql = "SELECT [FurnaceTemp], CONVERT(varchar(5), [Time], 108) AS [HourMinute]
FROM [BoilerMachine_Steam]
WHERE [Time] >= DATEADD(hour, -1, GETDATE())
ORDER BY [HourMinute]";

$stmt = sqlsrv_query($link, $sql);
$oil_line_x = array();
$oil_line_y = array();

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

    $oil_line_x[] = array($row['HourMinute']);
    $oil_line_y[] = array($row['FurnaceTemp']);
}
$jsonData_oil_line_x = json_encode($oil_line_x);
$jsonData_oil_line_y = json_encode($oil_line_y);
// var_dump($oil_line);
// var_dump($steamNum);
?>

<div class="text-light fw-bold">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" style="font-size:3vw">蒸氣鍋爐</div>
        <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 text-right text-align-bottom" style="font-size:1vw">
            最新資料時間: <?php echo date("H:i:s", strtotime($time)) ?></div>
        <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3 text-right" style="font-size:1vw">現在時間:
            <?php echo date("H:i:s") ?>
        </div>
    </div>
</div>
<div class="parent">

    <img src="img/BoilerMachine_edit4.jpg" class="main-image">
    <div class="steam-graph text-center text-light" id="steam-graph" style="background-color:black"></div>
    <div class="water-level" id="water-level">
        <img src="img/<?php echo $waterLevelButton ?>-light.png" style="height:100%;object-fit:fill">
    </div>
    <div class="water-pressure-meter text-center text-light" style="background-color:black" id="graph1"></div>
    <div class="water-pressure-numeric text-center text-light" style="background-color:black" id="graph1"></div>
    <div class="flow-numeric text-center text-light" style="background-color:black" id="flow-numeric">
        <?php echo $flow ?>
    </div>
    <div class="blower-numeric text-center text-light" style="font-size:2vw;background-color:black" id="blower-numeric">
        <?php echo $blower ?>
    </div>
    <div class="furnace-temp-numeric text-center text-light" style="font-size:2vw;background-color:black"
        id="furnace-temp-numeric">
        <?php echo $temperature / 10 . "°C" ?>
    </div>
    <div class="steam-pressure-meter text-light " style="background-color:black" id="steam-pressure-meter">
    </div>
    <div class="water-pressure-meter text-light " style="background-color:black" id="water-pressure-meter">
    </div>
    <div class="steam-pressure-numeric text-center text-light" style="font-size:2vw;background-color:black"
        id="steam-numeric">
        <?php echo $steamNum ?>
    </div>
    <div class="ventilator-numeric text-center text-light" style="font-size:2vw;background-color:black"
        id="ventilator-numeric">
        <?php echo $ventilator ?>
    </div>
    <div class="motor-overload" id="motor-overload">
        <img src="img/<?php echo $motorOverloadButton ?>-light.png" style="height:100%;object-fit:fill">
    </div>
    <div class="stoker-numeric text-center text-light" style="font-size:2vw;background-color:black" id="stoker-numeric">
        <?php echo $stoker ?>
    </div>
    <img id="water" class="water" src="img/water.gif" alt="GIF file">
    <img class="steam" src="img/steam.gif" alt="GIF file">
    <img class="steam_2-1" src="img/steam_2.gif" alt="GIF file">
    <img class="steam_2-2" src="img/steam_2.gif" alt="GIF file">
    <img class="fire" src="img/fire-yt.gif" alt="GIF file">
</div>






<script>


    Highcharts.chart('steam-graph', {
        credits: {
            enabled: false
        },
        chart: {

            backgroundColor: null, // 设置背景颜色为黑色
            style: {
                color: '#ffffff' // 设置字体颜色为白色
            }

        },
        title: {
            text: '一小時內鍋爐溫度',
            style: {
                color: '#ffffff' // 设置字体颜色为白色
            }
        },

        legend: {

            enabled: false
        },
        xAxis: {
            categories: <?php echo $jsonData_oil_line_x; ?>,
            labels: {
                style: {
                    color: "white", // 設置Y軸標籤顏色
                    // fontSize: '0.5vw'
                }
            }

        },

        yAxis: {
            title: {
                text: 'Temperature (°C)',
                style: {
                    color: '#ffffff' // 设置字体颜色为白色
                }
            },
            labels: {
                style: {
                    color: "white", // 設置Y軸標籤顏色
                    // fontSize: '300px'
                }
            }

        },
        plotOptions: {
            series: {
                animation: false, // 禁用数据系列的动画效果
                color: 'red' // 設置線的顏色為紅色
            }
        },
        series: [{ data: <?php echo $jsonData_oil_line_y; ?> }]
    });






    //using highcharts to generate charts with data from local server csv files
    // Highcharts.chart('s',{
    //     data:{
    //         csvURL: 'https://logicartidcc.website/logic_art/Demoidcc/IDCC_total%20resource%20pie.csv',
    //         enablePolling: true
    //     },
    //     chart:{
    //         backgroundColor: null,
    //         type: 'pie'
    //     },
    //     title:{
    //         text: null
    //     },
    //     yAxis: {
    //         allowDecimals: false,
    //         title: {
    //             text: 'Units'
    //         }
    //     },
    //     credits: {
    //         enabled: false
    //     },
    //     plotOptions: {
    //         series: {
    //             animation: false
    //         },
    //         pie: {
    //             allowPointSelect: true,
    //             cursor: 'pointer',
    //             dataLabels: {
    //                 enabled: true,
    //                 color: '#ffffff',
    //                 format: '<b>{point.name}</b>: {point.percentage:.0f} %'
    //             }
    //         }
    //     },
    //     tooltip: {
    //         pointFormat: '<span style="color:black">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.1f}%)<br/>',
    //         shared: true
    //     }
    // });

    $('.steam-pressure-meter').gauge({
        values: {
            0: '0',
            20: '20',
            40: '40',
            60: '60',
            80: '80',
            100: '100'
        },
        colors: {
            0: '#00d45a',
            60: '#ffa500',
            80: '#f00'
        },
        angles: [
            140,
            400
        ],
        lineWidth: 2,
        arrowWidth: 5,
        arrowColor: '#ccc',
        inset: true,
        value: <?php echo $steamNum ?>,

    });

    // 入水壓力

    $('.water-pressure-meter').gauge({
        values: {
            0: '0',
            20: '20',
            40: '40',
            60: '60',
            80: '80',
            100: '100'
        },
        colors: {
            0: '#00d45a',
            60: '#ffa500',
            80: '#f00'
        },
        angles: [
            140,
            400
        ],
        lineWidth: 2,
        arrowWidth: 5,
        arrowColor: '#ccc',
        inset: true,
        value: <?php echo $steamNum ?>,

    });



</script>

<script>

    if (<?php echo $waterFlag; ?> == 1) {
        var element = document.getElementById("water");
        element.style.transform = "scale(1,0)";
        element.style.transformOrigin = "bottom center";
        element.style.transition = " transform 2s ease";

    }


</script>
<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
require_once realpath("test.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>


    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <!-- 下載圖片用 -->
    <!-- <script src="https://code.highcharts.com/modules/exporting.js"></script> -->
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>


</head>
<style>
    body {
        background-image: url("/common/images/bg1.png");
        background-size: cover;
        background-repeat: no-repeat;
        height: 100%;
        width: 100%;
        overflow-y: hidden;
    }

    img {
        width: 100px;
        height: 100px;

    }

    .highcharts-button-box {
        display: none;
    }

    .my-div {
        color: white;
        font-size: 1.5vw;


        /* font-Weight: bold; */
        /* textShadow: '1px 1px 2px rgba(0, 0, 0, 3.5)'; */
    }


    /* .time-big-text {
  font-size: 3vh;
} */
    .my-box {
        display: grid;
        grid-template-columns: 1fr 3fr 3fr;
    }

    .my-box2 {
        display: grid;
        grid-template-columns: 1.3fr 3.5fr 3.5fr 1fr;
    }

    @media screen and (max-width: 1090px) {
        .display1 {
            display: none;
        }

        body {
            background-repeat: repeat;
            overflow-y: visible;
            height: 100%;
            width: 100%;
        }

        .change {
            display: flex;
            flex-direction: column;
            justify-content: left;
            align-items: left;
            height: 100vh;
        }

        .boxbox {
            width: 200px;
            height: 50px;
            background-color: #ccc;
            margin: 10px;
        }

        .h-100 {

            width: 900px !important;
        }

        .my-div {
            color: white;
            font-size: 2.5vw;


            /* font-Weight: bold; */
            /* textShadow: '1px 1px 2px rgba(0, 0, 0, 3.5)'; */
        }
    }
</style>

<body>

    <div class="container-fluid h-100 ">
        <div class="row h-100 change">
            <div class="col-sm-6 h-100 ">
                <!-- <h2 class="w-100 text-center text-white">用 水</h2> -->

                <div class="row h-100 ">
                    <div class="col-sm-12  h-50 my-div ">
                        <div class="my-box">
                            <div> <img src="./images/water.png" alt=""></div>
                            <div> 目前實際用量:<br><?php echo $latestUsage; ?> L</div>
                            <div> 下個小時預估用量:<br>36 L</div>
                        </div>
                    </div>
                    <div class="col-sm-6 h-50">

                        <div id="speed1"></div>
                    </div>
                    <div class="col-sm-6  h-50">
                        <div id="box1"></div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 h-100">
                <!-- <h2 class="w-100 text-center text-white">用 電</h2> -->
                <div class="row h-100  ">
                    <div class="col-sm-12  h-50 my-div ">
                        <div class="my-box">
                            <div> <img src="./images/electricity.png" alt=""> </div>
                            <div> 目前實際用量:<br>35 KWH</div>
                            <div> 下個小時預估用量:<br>45 KWH</div>
                            <!-- <div><span class ="time-big-text">4/8 15:00</span></div> -->
                        </div>
                        <div id="clock" style="position: fixed; top: 0; right: 0; font-size: 1.5vw;">4/8 15:00</div>





                    </div>
                    <div class="col-sm-6  h-50">
                        <div id="speed2"></div>
                    </div>
                    <div class="col-sm-6  h-50">
                        <div id="box2"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row h-100 ">
            <div class="col-sm-12  h-100 ">
                <div class="display1" id="barchart"></div>
            </div>
        </div>
        <div class="row h-100 change">
            <div class="col-sm-6 h-100">
                <!-- <h2 class="w-100 text-center text-white">蒸 氣</h2> -->
                <div class="row h-100 ">
                    <div class="col-sm-12  h-50 my-div">
                        <div class="my-box">
                            <div> <img src="./images/steam.png" alt=""></div>
                            <div> 目前實際用量:<br>77 Kg</div>
                            <div> 下個小時預估用量:<br>78 Kg</div>
                        </div>


                    </div>
                    <div class="col-sm-6  h-50">
                        <div id="speed3"></div>
                    </div>
                    <div class="col-sm-6  h-50">
                        <div id="box3"></div>

                    </div>
                </div>
            </div>
            <div class="col-sm-6  h-100">
                <!-- <h2 class="w-100 text-center text-white">碳 排</h2> -->
                <div class="row h-100 ">
                    <div class="col-sm-12  h-50 my-div">
                        <div class="my-box">
                            <div> <img src="./images/co2.png" alt=""></div>
                            <div> 目前實際用量:<br>44 Kg CO2e</div>
                            <div> 下個小時預估用量:<br>45 Kg CO2e</div>
                        </div>

                    </div>
                    <div class="col-sm-6  h-50">
                        <div id="speed4"></div>
                    </div>
                    <div class="col-sm-6  h-50">
                        <div id="box4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>


<script>
    if ($("#box1").length) {
        box1PieChart();
    }
    function box1PieChart() {
        Highcharts.chart('box1', {
            credits: {
                enabled: false
            },
            chart: {
                type: 'pie',
                height: 250,

                backgroundColor: 'rgba(0,0,0,0)'
            },
            title: {
                text: '機台用水量',
                style: {
                    color: '#FFFFFF',
                    fontSize: '1.3vw',
                }
            },
            tooltip: {
                pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>"
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: "pointer",
                    dataLabels: {
                        enabled: true,
                        format: "<b>{point.name}</b>: {point.percentage:.1f} %",

                    }
                }
            },
            series: [{
                name: '用水',
                colorByPoint: true,
                data: [{
                    name: 'H01',
                    y: 180,
                    sliced: true,
                    selected: true
                }, {
                    name: 'H02',
                    y: 46
                }, {
                    name: 'H03',
                    y: 15
                }, {
                    name: 'H04',
                    y: 75
                }],
                dataLabels: {
                    enabled: true,
                    format: "{point.name}",
                    style: {
                        color: "white", // 設置數據標籤顏色為淺藍色
                        fontSize: '1vw',
                        fontFamily: 'Verdana, sans-serif', // 設置數據標籤字體為Verdana
                        textOutline: 'none'
                    }
                }
            }]
        });
    }
</script>


<script>
    if ($("#box2").length) {
        box2PieChart();
    }
    function box2PieChart() {
        Highcharts.chart('box2', {
            credits: {
                enabled: false
            },
            chart: {
                type: 'pie',
                height: 250,

                backgroundColor: 'rgba(0,0,0,0)'
            },
            title: {
                text: '機台用電量',
                style: {
                    color: '#FFFFFF',
                    fontSize: '1.3vw',
                }
            },
            tooltip: {
                pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>"
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: "pointer",
                    dataLabels: {
                        enabled: true,
                        format: "<b>{point.name}</b>: {point.percentage:.1f} %"
                    }
                }
            },
            series: [{
                name: '用電',
                colorByPoint: true,
                data: [{
                    name: 'H01',
                    y: 50,
                    sliced: true,
                    selected: true
                }, {
                    name: 'H02',
                    y: 46
                }, {
                    name: 'H03',
                    y: 15
                }, {
                    name: 'H04',
                    y: 75
                }],
                dataLabels: {
                    enabled: true,
                    format: "{point.name}",
                    style: {
                        color: "white", // 設置數據標籤顏色為淺藍色
                        fontSize: '1vw',
                        fontFamily: 'Verdana, sans-serif', // 設置數據標籤字體為Verdana
                        textOutline: 'none'
                    }
                }
            }]
        });
    }
</script>

<script>
    if ($("#box3").length) {
        box3PieChart();
    }
    function box3PieChart() {
        Highcharts.chart('box3', {
            credits: {
                enabled: false
            },
            chart: {
                type: 'pie',
                height: 250,
                backgroundColor: 'rgba(0,0,0,0)'
            },
            title: {
                text: '機台蒸氣量',
                style: {
                    color: '#FFFFFF',
                    fontSize: '1.3vw',
                }
            },
            tooltip: {
                pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>"
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: "pointer",
                    dataLabels: {
                        enabled: true,
                        format: "<b>{point.name}</b>: {point.percentage:.1f} %"
                    }
                }
            },
            series: [{
                name: '蒸氣',
                colorByPoint: true,
                data: [{
                    name: 'H01',
                    y: 50,
                    sliced: true,
                    selected: true
                }, {
                    name: 'H02',
                    y: 25
                }, {
                    name: 'H03',
                    y: 80
                }, {
                    name: 'H04',
                    y: 10
                }],
                dataLabels: {
                    enabled: true,
                    format: "{point.name}",
                    style: {
                        color: "white", // 設置數據標籤顏色為淺藍色
                        fontSize: '1vw',
                        fontFamily: 'Verdana, sans-serif', // 設置數據標籤字體為Verdana
                        textOutline: 'none'
                    }
                }
            }]
        });
    }
</script>

<script>
    if ($("#box4").length) {
        box4PieChart();
    }
    function box4PieChart() {
        Highcharts.chart('box4', {
            credits: {
                enabled: false
            },
            chart: {
                type: 'pie',

                height: 250,
                backgroundColor: 'rgba(0,0,0,0)'
            },
            title: {
                text: '機台碳排量',
                style: {
                    color: '#FFFFFF',
                    fontSize: '1.3vw',
                }
            },
            tooltip: {
                pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>"
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: "pointer",
                    dataLabels: {
                        enabled: true,
                        format: "<b>{point.name}</b>: {point.percentage:.1f} %"
                    }
                }
            },
            series: [{
                name: '碳排',
                colorByPoint: true,

                data: [{
                    name: 'H01',
                    y: 70,
                    sliced: true,
                    selected: true
                }, {
                    name: 'H02',
                    y: 46
                }, {
                    name: 'H03',
                    y: 80
                }, {
                    name: 'H04',
                    y: 90
                }],
                dataLabels: {
                    enabled: true,
                    format: "{point.name}",
                    style: {
                        color: "white", // 設置數據標籤顏色為淺藍色
                        fontSize: '1vw',
                        fontFamily: 'Verdana, sans-serif', // 設置數據標籤字體為Verdana
                        textOutline: 'none'
                    }
                }
            },
            ]
        });
    }
</script>

<script>
    if ($("#linechartxxx").lenght) {
        linechartxxx();
    }
    function linechartxxx() {
        Highcharts.chart('linechartxxx', {
            credits: {
                enabled: false
            },
            chart: {
                backgroundColor: 'rgba(0,0,0,0)',
                height: "370px"
            },
            title: {
                text: '每小時能耗統計',
                style: {
                    color: '#FFFFFF',
                    fontSize: '1.3vw',

                }
            },
            xAxis: {
                categories: ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'],
                labels: {
                    style: {
                        color: "white", // 設置X軸標籤顏色
                        fontSize: '1vw',
                    }
                }
            },
            legend: {
                itemStyle: {
                    color: 'white',
                    fontSize: '1.5vw'

                }
            },
            yAxis: [{
                title: {
                    text: '用水量 (L)',
                    style: {
                        color: 'white',
                        fontSize: '1vw',
                        color: '#00bcd4' // 將顏色設置為藍色
                    }
                },
                labels: {
                    style: {
                        color: "white", // 設置Y軸標籤顏色
                        fontSize: '1.5vw',
                        color: '#00bcd4' // 將顏色設置為藍色
                    }
                }
            }, {
                title: {
                    text: '用電量 (KWH)',
                    style: {
                        color: 'white',
                        fontSize: '1vw',
                        color: '#ffff00' // 將顏色設置為紫色
                    }
                },
                opposite: true,
                max: 70,
                labels: {
                    style: {
                        color: 'white',
                        fontSize: '1.5vw',
                        color: '#ffff00' // 將顏色設置為紫色
                    }
                }

            }, {
                title: {
                    text: '蒸氣量 (Kg)',
                    style: {
                        color: 'white',
                        fontSize: '1vw',
                        color: '#ff0000'
                    }
                },
                opposite: true,
                max: 70,
                labels: {
                    style: {
                        color: 'white',
                        fontSize: '1.5vw',
                        color: '#ff0000'
                    }
                }

            }, {
                title: {
                    text: '碳排量 (Kg CO2e)',
                    style: {
                        color: 'white',
                        fontSize: '1vw',
                        color: '#00FF00' // 將顏色設置為綠色
                    }
                },
                opposite: true,
                max: 70,
                labels: {
                    style: {
                        color: 'white',
                        fontSize: '1.5vw',
                        color: '#00FF00' // 將顏色設置為綠色
                    }
                }

            }],
            series: [{
                name: '用水',
                data: [10, 12, 15, 18, 51, 22, 25, 28, 30, 32, 25, 38, 40, 42, 45, 48, 70, 52, 55, 48, 60, 62, 65, 68],
                yAxis: 0,
                color: '#00bcd4' // 將顏色設置為藍色
            }, {
                name: '用電',
                data: [60, 55, 65, 47, 57, 57, 70, 57, 55, 26, 53, 35, 60, 34, 57, 64, 66, 42, 40, 63, 55, 40, 64, 54],
                yAxis: 1,
                color: '#ffff00' // 將顏色設置為紫色
            }, {
                name: '蒸氣',
                data: [25, 47, 16, 27, 75, 22, 24, 26, 28, 30, 32, 55, 36, 43, 40, 42, 44, 46, 48, 50, 52, 68, 56, 58],
                yAxis: 2,
                color: '#ff0000'
            }, {
                name: '碳排',
                data: [42, 8, 10, 12, 8, 16, 18, 20, 42, 24, 26, 28, 30, 32, 42, 36, 38, 42, 42, 44, 46, 35, 50, 52],
                yAxis: 3,
                color: '#00FF00' // 將顏色設置為綠色
            }]
        });
    }
</script>

<script>
    if ($("#speed1").length) {
        speed1Gauge();
    }
    function speed1Gauge() {
        Highcharts.chart('speed1', {
            credits: {
                enabled: false
            },

            chart: {
                type: 'gauge',
                plotBackgroundColor: null,
                plotBackgroundImage: null,
                plotBorderWidth: 0,
                plotShadow: false,
                // height: '80%',
                backgroundColor: 'rgba(0,0,0,0)',
                height: 250,
            },

            title: {
                text: '機台使用率',
                style: {
                    color: '#FFFFFF',
                    fontSize: '1.3vw',
                }
            },

            pane: {
                startAngle: -90,
                endAngle: 89.9,
                background: null,
                center: ['50%', '75%'],
                size: '110%'
            },

            // the value axis
            yAxis: {
                min: 0,
                max: 100,
                tickPixelInterval: 72,
                tickPosition: 'inside',
                tickColor: Highcharts.defaultOptions.chart.backgroundColor || '#FFFFFF',
                tickLength: 20,
                tickWidth: 2,
                minorTickInterval: null,
                labels: {
                    distance: 20,
                    style: {
                        fontSize: '14px',
                        color: '#FFFFFF'
                    }
                },
                plotBands: [{
                    from: 0,
                    to: 50,
                    color: '#55BF3B', // green
                    thickness: 20
                }, {
                    from: 50,
                    to: 75,
                    color: '#DDDF0D', // yellow
                    thickness: 20
                }, {
                    from: 75,
                    to: 100,
                    color: '#DF5353', // red
                    thickness: 20
                }]
            },

            series: [{
                name: '比例',
                data: [50],
                tooltip: {
                    valueSuffix: ' %'
                },
                dataLabels: {
                    format: '{y} %',
                    borderWidth: 0,
                    color: (
                        Highcharts.defaultOptions.title &&
                        Highcharts.defaultOptions.title.style &&
                        Highcharts.defaultOptions.title.style.color
                    ) || '#333333',
                    style: {
                        fontSize: '16px'
                    }
                },
                dial: {
                    radius: '80%',
                    backgroundColor: 'gray',
                    baseWidth: 12,
                    baseLength: '0%',
                    rearLength: '0%'
                },
                pivot: {
                    backgroundColor: 'gray',
                    radius: 6
                }

            }]

        });
    }

    // Add some life
    // setInterval(() => {
    // const chart = Highcharts.charts[0];
    // if (chart && !chart.renderer.forExport) {
    //     const point = chart.series[0].points[0],
    //         inc = Math.round((Math.random() - 0.5) * 20);

    //     let newVal = point.y + inc;
    //     if (newVal < 0 || newVal > 200) {
    //         newVal = point.y - inc;
    //     }

    //     point.update(newVal);
    // }

    // }, 3000);
</script>

<script>
    if ($("#speed2").length) {
        speed2Gauge();
    }
    function speed2Gauge() {
        Highcharts.chart('speed2', {
            credits: {
                enabled: false
            },

            chart: {
                type: 'gauge',
                plotBackgroundColor: null,
                plotBackgroundImage: null,
                plotBorderWidth: 0,
                plotShadow: false,
                // height: '80%',
                backgroundColor: 'rgba(0,0,0,0)',
                height: 250,
            },

            title: {
                text: '機台使用率',
                style: {
                    color: '#FFFFFF',
                    fontSize: '1.3vw',
                }
            },

            pane: {
                startAngle: -90,
                endAngle: 89.9,
                background: null,
                center: ['50%', '75%'],
                size: '110%'
            },

            // the value axis
            yAxis: {
                min: 0,
                max: 100,
                tickPixelInterval: 72,
                tickPosition: 'inside',
                tickColor: Highcharts.defaultOptions.chart.backgroundColor || '#FFFFFF',
                tickLength: 20,
                tickWidth: 2,
                minorTickInterval: null,
                labels: {
                    distance: 20,
                    style: {
                        fontSize: '14px',
                        color: '#FFFFFF'
                    }
                },
                plotBands: [{
                    from: 0,
                    to: 50,
                    color: '#55BF3B', // green
                    thickness: 20
                }, {
                    from: 50,
                    to: 75,
                    color: '#DDDF0D', // yellow
                    thickness: 20
                }, {
                    from: 75,
                    to: 100,
                    color: '#DF5353', // red
                    thickness: 20
                }]
            },

            series: [{
                name: '比例',
                data: [50],
                tooltip: {
                    valueSuffix: ' %'
                },
                dataLabels: {
                    format: '{y} %',
                    borderWidth: 0,
                    color: (
                        Highcharts.defaultOptions.title &&
                        Highcharts.defaultOptions.title.style &&
                        Highcharts.defaultOptions.title.style.color
                    ) || '#333333',
                    style: {
                        fontSize: '16px'
                    }
                },
                dial: {
                    radius: '80%',
                    backgroundColor: 'gray',
                    baseWidth: 12,
                    baseLength: '0%',
                    rearLength: '0%'
                },
                pivot: {
                    backgroundColor: 'gray',
                    radius: 6
                }

            }]

        });
    }

    // Add some life
    // setInterval(() => {
    // const chart = Highcharts.charts[0];
    // if (chart && !chart.renderer.forExport) {
    //     const point = chart.series[0].points[0],
    //         inc = Math.round((Math.random() - 0.5) * 20);

    //     let newVal = point.y + inc;
    //     if (newVal < 0 || newVal > 200) {
    //         newVal = point.y - inc;
    //     }

    //     point.update(newVal);
    // }

    // }, 3000);
</script>
<script>
    if ($("#speed3").length) {
        speed3Gauge();
    }
    function speed3Gauge() {
        Highcharts.chart('speed3', {
            credits: {
                enabled: false
            },

            chart: {
                type: 'gauge',
                plotBackgroundColor: null,
                plotBackgroundImage: null,
                plotBorderWidth: 0,
                plotShadow: false,
                // height: '80%',
                backgroundColor: 'rgba(0,0,0,0)',
                height: 250,
            },

            title: {
                text: '機台使用率',
                style: {
                    color: '#FFFFFF',
                    fontSize: '1.3vw',
                }
            },

            pane: {
                startAngle: -90,
                endAngle: 89.9,
                background: null,
                center: ['50%', '75%'],
                size: '110%'
            },

            // the value axis
            yAxis: {
                min: 0,
                max: 100,
                tickPixelInterval: 72,
                tickPosition: 'inside',
                tickColor: Highcharts.defaultOptions.chart.backgroundColor || '#FFFFFF',
                tickLength: 20,
                tickWidth: 2,
                minorTickInterval: null,
                labels: {
                    distance: 20,
                    style: {
                        fontSize: '14px',
                        color: '#FFFFFF'
                    }
                },
                plotBands: [{
                    from: 0,
                    to: 50,
                    color: '#55BF3B', // green
                    thickness: 20
                }, {
                    from: 50,
                    to: 75,
                    color: '#DDDF0D', // yellow
                    thickness: 20
                }, {
                    from: 75,
                    to: 100,
                    color: '#DF5353', // red
                    thickness: 20
                }]
            },

            series: [{
                name: '比例',
                data: [50],
                tooltip: {
                    valueSuffix: ' %'
                },
                dataLabels: {
                    format: '{y} %',
                    borderWidth: 0,
                    color: (
                        Highcharts.defaultOptions.title &&
                        Highcharts.defaultOptions.title.style &&
                        Highcharts.defaultOptions.title.style.color
                    ) || '#333333',
                    style: {
                        fontSize: '16px'
                    }
                },
                dial: {
                    radius: '80%',
                    backgroundColor: 'gray',
                    baseWidth: 12,
                    baseLength: '0%',
                    rearLength: '0%'
                },
                pivot: {
                    backgroundColor: 'gray',
                    radius: 6
                }

            }]

        });
    }

    // Add some life
    // setInterval(() => {
    // const chart = Highcharts.charts[0];
    // if (chart && !chart.renderer.forExport) {
    //     const point = chart.series[0].points[0],
    //         inc = Math.round((Math.random() - 0.5) * 20);

    //     let newVal = point.y + inc;
    //     if (newVal < 0 || newVal > 200) {
    //         newVal = point.y - inc;
    //     }

    //     point.update(newVal);
    // }

    // }, 3000);
</script>
<script>
    if ($("#speed4").length) {
        speed4Gauge();
    }
    function speed4Gauge() {
        Highcharts.chart('speed4', {
            credits: {
                enabled: false
            },

            chart: {
                type: 'gauge',
                plotBackgroundColor: null,
                plotBackgroundImage: null,
                plotBorderWidth: 0,
                plotShadow: false,
                // height: '80%',
                backgroundColor: 'rgba(0,0,0,0)',
                height: 250,
            },

            title: {
                text: '機台使用率',
                style: {
                    color: '#FFFFFF',
                    fontSize: '1.3vw',
                }
            },

            pane: {
                startAngle: -90,
                endAngle: 89.9,
                background: null,
                center: ['50%', '75%'],
                size: '110%'
            },

            // the value axis
            yAxis: {
                min: 0,
                max: 100,
                tickPixelInterval: 72,
                tickPosition: 'inside',
                tickColor: Highcharts.defaultOptions.chart.backgroundColor || '#FFFFFF',
                tickLength: 20,
                tickWidth: 2,
                minorTickInterval: null,
                labels: {
                    distance: 20,
                    style: {
                        fontSize: '14px',
                        color: '#FFFFFF'
                    }
                },
                plotBands: [{
                    from: 0,
                    to: 50,
                    color: '#55BF3B', // green
                    thickness: 20
                }, {
                    from: 50,
                    to: 75,
                    color: '#DDDF0D', // yellow
                    thickness: 20
                }, {
                    from: 75,
                    to: 100,
                    color: '#DF5353', // red
                    thickness: 20
                }]
            },

            series: [{
                name: '比例',
                data: [50],
                tooltip: {
                    valueSuffix: ' %'
                },
                dataLabels: {
                    format: '{y} %',
                    borderWidth: 0,
                    color: (
                        Highcharts.defaultOptions.title &&
                        Highcharts.defaultOptions.title.style &&
                        Highcharts.defaultOptions.title.style.color
                    ) || '#333333',
                    style: {
                        fontSize: '16px'
                    }
                },
                dial: {
                    radius: '80%',
                    backgroundColor: 'gray',
                    baseWidth: 12,
                    baseLength: '0%',
                    rearLength: '0%'
                },
                pivot: {
                    backgroundColor: 'gray',
                    radius: 6
                }

            }]

        });
    }

    // Add some life
    // setInterval(() => {
    // const chart = Highcharts.charts[0];
    // if (chart && !chart.renderer.forExport) {
    //     const point = chart.series[0].points[0],
    //         inc = Math.round((Math.random() - 0.5) * 20);

    //     let newVal = point.y + inc;
    //     if (newVal < 0 || newVal > 200) {
    //         newVal = point.y - inc;
    //     }

    //     point.update(newVal);
    // }

    // }, 3000);
</script>

<script>
    var data = <?php echo $water_data ?? "''"; ?>;

    if ($("#barchart").length) {
        barchartColumn();
    }
    function barchartColumn() {
        Highcharts.chart('barchart', {
            credits: {
                enabled: false
            },
            chart: {
                type: 'column',
                backgroundColor: null,
                height: "380px"
            },
            title: {
                text: '每小時能源耗用',
                style: {
                    color: '#FFFFFF',
                    fontSize: '1.3vw',

                }
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: ['00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'],
                // categories:<?php echo $water_data3 ?>,
                crosshair: true,
                labels: {
                    style: {
                        color: "white", // 設置X軸標籤顏色
                        fontSize: '1vw',
                    }
                }
            },
            yAxis: [{
                title: {
                    text: '用水量 (L)',
                    style: {
                        color: 'white',
                        fontSize: '1vw',
                        color: '#00bcd4' // 將顏色設置為藍色
                    }
                },
                labels: {
                    style: {
                        color: "white", // 設置Y軸標籤顏色
                        fontSize: '1.5vw',
                        color: '#00bcd4' // 將顏色設置為藍色
                    }
                }
            }, {
                title: {
                    text: '用電量 (KWH)',
                    style: {
                        color: 'white',
                        fontSize: '1vw',
                        color: '#ffff00' // 將顏色設置為紫色
                    }
                },
                opposite: true,
                max: 70,
                labels: {
                    style: {
                        color: 'white',
                        fontSize: '1.5vw',
                        color: '#ffff00' // 將顏色設置為紫色
                    }
                }

            }, {
                title: {
                    text: '蒸氣量 (Kg)',
                    style: {
                        color: 'white',
                        fontSize: '1vw',
                        color: '#ff0000'
                    }
                },
                opposite: true,
                max: 70,
                labels: {
                    style: {
                        color: 'white',
                        fontSize: '1.5vw',
                        color: '#ff0000'
                    }
                }

            }, {
                title: {
                    text: '碳排量 (Kg CO2e)',
                    style: {
                        color: 'white',
                        fontSize: '1vw',
                        color: '#00FF00' // 將顏色設置為綠色
                    }
                },
                opposite: true,
                max: 70,
                labels: {
                    style: {
                        color: 'white',
                        fontSize: '1.5vw',
                        color: '#00FF00' // 將顏色設置為綠色
                    }
                }

            }],
            legend: {
                itemStyle: {
                    color: 'white',
                    fontSize: '1.2vw'

                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.0f} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointWidth: 5, // 設置每一條柱形的寬度為 10 像素
                    borderWidth: 0.5
                }
            },
            series: [{
                name: '用水',
                data: data,
                yAxis: 0,
                color: '#00bcd4' // 將顏色設置為藍色
            }, {
                name: '用電',
                data: [60, 55, 65, 47, 57, 57, 70, 57, 55, 26, 53, 35, 60, 34, 57, 64, 66, 42, 40, 63, 55, 40, 64, 54],
                yAxis: 1,
                color: '#ffff00' // 將顏色設置為紫色
            }, {
                name: '蒸氣',
                // data: [25, 47, 16, 27, 75, 22, 24, 26, 28, 30, 32, 55, 36, 43, 40, 42, 44, 46, 48, 50, 52, 68, 56, 58],
                data: data, yAxis: 2,
                color: '#ff0000'
            }, {
                name: '碳排',
                // data: [42, 8, 10, 12, 8, 16, 18, 20, 42, 24, 26, 28, 30, 32, 42, 36, 38, 42, 42, 44, 46, 35, 50, 52],
                data: data, yAxis: 3,
                color: '#00FF00' // 將顏色設置為綠色
            }]
        });
    }
    // console.log(data[0][0])
</script>

<script>


    function autoRefresh() {
        // console.log("function called");

        $.ajax({
            type: 'POST',
            url: "./get_data_ajax.php",
            data: {

            },
            success: function (result) {
                // $('#demo').html(result);
                let data = JSON.parse(result);
                // $('#data_MainSpeed').html(data.data_MainSpeed);
                // console.log(data.data_MainSpeed);
                // $('#data_Width').html(data.data_Width);
                // $('#data_OverFeed').html(data.data_OverFeed);
                // // $('#demo').html(data.data_Job1);
                // // $('#demo').html(data.data_Job2);
                // // $('#demo').html(data.data_Time);
                // $('#lastTemperature1').html(data.lastTemperature1);
                // $('#lastTemperature2').html(data.lastTemperature2);
                // $('#lastTemperature3').html(data.lastTemperature3);
                // $('#lastTemperature4').html(data.lastTemperature4);
                // $('#lastTemperature5').html(data.lastTemperature5);
                // $('#lastTemperature5').append("add");



            }
        });
        setTimeout(function () {
            autoRefresh();
            // clearInterval(timerId);
        }, 3000);
    }
    // autoRefresh();
</script>
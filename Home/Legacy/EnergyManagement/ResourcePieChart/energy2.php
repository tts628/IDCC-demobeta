<?php
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
    <!-- 換行用 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<style>
    body {
        background-image: url("/common/images/bg1.png");
        background-size: cover;
        background-repeat: no-repeat;
        height: 100%;
        width: 100%;
        /* overflow-y: hidden; */
    }

    .container-fluid {
        margin-top: 20px;
    }

    .box {
        height: 500px;
        /* background-color: #fff; */
        /* border: 1px solid #ccc; */
        /* box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3); */
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 24px;
        color: #333;
    }

    .box:nth-child(1) {
        /* background-color: #f44336; */
        color: #fff;
    }

    .box:nth-child(2) {
        /* background-color: #4caf50; */
        color: #fff;
    }

    .box:nth-child(3) {
        /* background-color: #2196f3; */
        color: #fff;
    }

    .box:nth-child(4) {
        /* background-color: #ff9800; */
        color: #fff;
    }

    .big-box {
        height: 400px;
        /* background-color: #f2f2f2; */
        /* border: 1px solid #ccc; */
        /* box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3); */
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 36px;
        color: #333;
    }
</style>

<body>
    <!-- <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6 col-md-3">
                <div class="box">Box 1</div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="box">Box 2</div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="box">Box 3</div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="box">Box 4</div>
            </div>
            <div class="col-sm-12">
                <div class="big-box">Big Box</div>
            </div>
        </div>
    </div> -->

    <div class="container-fluid" style="hieght:900px">
        <div class="row" style="hieght:450px">
            <div class="col-sm-9 col-md-6">
                <div class="box" id="box1"></div>
            </div>
            <div class="col-sm-9 col-md-6">
                <div class="box" id="box2"></div>
            </div>
        </div>
        <div class="row" style="hieght:450px">
            <div class="col-sm-9 col-md-6">
                <div class="box" id="box3"></div>
            </div>
            <div class="col-sm-9 col-md-6">
                <div class="box" id="box4"></div>
            </div>
        </div>
        <!-- <div class="container-fluid">
            <div class="col-sm-12">
                <div class="big-box" id="barchart"></div>
            </div>
        </div> -->
    </div>



</body>

</html>


<script>
</script>


<script>

</script>

<script>

</script>

<script>

</script>

<script>

</script>



</script>

<script>
    const queryString = window.location.search;
    function autoRefresh() {
        console.log("function called");

        $.ajax({
            type: 'POST',
            url: "./get_pie_data_ajax.php" + queryString,
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
                console.log(data.time_new);
                // 20230508

                // pie_1
                Highcharts.chart('box1', {
                    credits: {
                        enabled: false
                    },
                    chart: {
                        type: 'pie',
                        height: 400,

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
                        pointFormat: "{series.name}: <b>{point.y:.1f}L</b>"
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: "pointer",
                            dataLabels: {
                                enabled: true,
                                format: "<b>{point.name}</b>: {point.y:.1f} L",

                            }
                        }
                    },
                    series: [{
                        name: '用水',
                        colorByPoint: true,
                        data: data.water_pie,
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

                // pie_2

                Highcharts.chart('box2', {
                    credits: {
                        enabled: false
                    },
                    chart: {
                        type: 'pie',
                        height: 400,

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
                        pointFormat: "{series.name}: <b>{point.y:.1f}KWH</b>"
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: "pointer",
                            dataLabels: {
                                enabled: true,
                                format: "<b>{point.name}</b>: {point.y:.1f} KWH"
                            }
                        }
                    },
                    series: [{
                        name: '用電',
                        colorByPoint: true,
                        data: data.power_pie,
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



                // pie_3
                Highcharts.chart('box3', {
                    credits: {
                        enabled: false
                    },
                    chart: {
                        type: 'pie',
                        height: 400,
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
                        pointFormat: "{series.name}: <b>{point.y:.1f}Kg</b>"
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: "pointer",
                            dataLabels: {
                                enabled: true,
                                format: "<b>{point.name}</b>: {point.y:.1f} Kg"
                            }
                        }
                    },
                    series: [{
                        name: '蒸氣',
                        colorByPoint: true,
                        data: data.steam_pie,
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
                // pie_4
                Highcharts.chart('box4', {
                    credits: {
                        enabled: false
                    },
                    chart: {
                        type: 'pie',

                        height: 400,
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
                        pointFormat: "{series.name}: <b>{point.y:.1f}Kg CO2e</b>"
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: "pointer",
                            dataLabels: {
                                enabled: true,
                                format: "<b>{point.name}</b>: {point.y:.1f} Kg CO2e"
                            }
                        }
                    },
                    series: [{
                        name: '碳排',
                        colorByPoint: true,

                        data: data.carbon_pie,
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





            },
            error: function (err) {
                console.log(err)
            }
        });
        setTimeout(function () {
            autoRefresh();
            // clearInterval(timerId);
        }, 10000);
    }
    autoRefresh();
</script>
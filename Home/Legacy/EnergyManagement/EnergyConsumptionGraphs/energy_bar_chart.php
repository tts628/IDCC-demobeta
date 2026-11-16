<?php
include "test.php";




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>能耗統計</title>
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
        height: 100%;
        width: 100%;
        /* overflow-y: hidden; */
    }

    .container-fluid {
        margin-top: 100px;
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

    <!-- <div class="container-fluid" style="hieght:1200px"> -->
    <!-- <div class="row" style="hieght:450px">
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
                <div class="box" id="box4" ></div>
            </div>
        </div> -->

    <!-- <div class="col-sm-12"> -->
    <div class="container-fluid" id="barchart"></div>
    <!-- </div> -->

    <!-- </div> -->



</body>

</html>





<script>

</script>

<script>
    const queryString = window.location.search;

    function autoRefresh() {

        $.ajax({
            type: 'POST',
            url: "./get_bar_data_ajax.php" + queryString,
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

                //barchart


                Highcharts.chart('barchart', {
                    credits: {
                        enabled: false
                    },
                    chart: {
                        type: 'column',
                        backgroundColor: null,
                        height: "680px"
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
                        data: data.data_water_hour,
                        yAxis: 0,
                        color: '#00bcd4' // 將顏色設置為藍色
                    }, {
                        name: '用電',
                        data: data.data_power_hour,
                        yAxis: 1,
                        color: '#ffff00' // 將顏色設置為紫色
                    }, {
                        name: '蒸氣',
                        // data: [25, 47, 16, 27, 75, 22, 24, 26, 28, 30, 32, 55, 36, 43, 40, 42, 44, 46, 48, 50, 52, 68, 56, 58],
                        data: data.data_steam_hour, yAxis: 2,
                        color: '#ff0000'
                    }, {
                        name: '碳排',
                        // data: [42, 8, 10, 12, 8, 16, 18, 20, 42, 24, 26, 28, 30, 32, 42, 36, 38, 42, 42, 44, 46, 35, 50, 52],
                        data: data.data_carbon_hour, yAxis: 3,
                        color: '#00FF00' // 將顏色設置為綠色
                    }]
                });
                // console.log(data[0][0])
            }
        });
        setTimeout(function () {
            autoRefresh();
            // clearInterval(timerId);
        }, 30000);
    }
    autoRefresh();
</script>
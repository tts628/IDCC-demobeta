<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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



    <style>
        body {

            background-image: url("/common/images/bg1.png");
            background-size: cover;
            background-repeat: repeat;
            height: 100%;
            width: 100%;
            /* overflow-y: hidden; */

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
            font-size: 45px;


            /* font-Weight: bold; */
            /* textShadow: '1px 1px 2px rgba(0, 0, 0, 3.5)'; */
        }

        .my-unit-div {
            font-size: 30px;
        }

        .my-title-div {
            font-size: 40px;
        }

        #time {
            position: absolute;
            bottom: 0;
            right: 0;
            font-size: 1vw;
        }

        @media screen and (max-width: 1090px) {
            #time {
                position: fixed;
                bottom: 0;
                right: 0;
                margin: 20px;
                font-size: 1vw;
            }

            .my-div {
                color: white;
                font-size: 40px;
                font-weight: bold;
            }

            .my-unit-div {
                font-size: 20px;
            }

            .my-title-div {
                font-size: 35px;
            }



        }

        .container-fluid {
            margin-top: 10px;
        }

        .box {
            height: 300px;
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 24px;
            color: #333;
        }

        /* .time-big-text {
  font-size: 3vh;
} */
        /* .my-box {
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
      font-size: 2.5vw; */


        /* font-Weight: bold; */
        /* textShadow: '1px 1px 2px rgba(0, 0, 0, 3.5)'; */
        /* }
  } */

        .card {
            display: flex;
            background-color: transparent;
            border: 3px double white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            /* height:900px; */
        }
    </style>

    <!-- inject:css -->
    <link rel="stylesheet" href="/common/IDCC_plugin/legacyPlugins/lib/getmdl-select.min.css">
    <link rel="stylesheet" href="/common/IDCC_plugin/legacyPlugins/lib/nv.d3.min.css">
    <link rel="stylesheet" href="/common/IDCC_plugin/legacyPlugins/application.min.css">


</head>

<body>


    <div class="mdl-grid charts">
        <div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
            <div class="mdl-card mdl-shadow--2dp">
                <div class="mdl-card__title">
                    <h2 class="mdl-card__title-text"> <span class="my-title-div">目前實際用量: </span>

                    </h2>
                </div>
                <div class="mdl-card__supporting-text">

                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div> <img src="./images/water.png" alt="">
                                <span id="water_now" class="my-div ">&nbsp;</span>
                                <span class="my-unit-div "> &nbsp;L</span>
                            </div>

                            <div id="speed1"></div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div> <img src="./images/electricity.png" alt="">
                                <span id="power_now" class="my-div"> &nbsp; </span>
                                <span class="my-unit-div "> &nbsp;KWH</span>
                            </div>

                            <div id="speed2"></div>
                        </div>
                    </div>




                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div> <img src="./images/steam.png" alt="">

                                <span id="steam_now" class="my-div"> &nbsp; </span>
                                <span class="my-unit-div "> &nbsp;Kg</span>
                            </div>

                            <div id="speed3"></div>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <div> <img src="./images/co2.png" alt="">

                                <span id="co2_now" class="my-div"> &nbsp; </span>
                                <span class="my-unit-div "> &nbsp;Kg CO2e</span>
                            </div>

                            <div id="speed4"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
            <div class="mdl-card mdl-shadow--2dp">
                <div class="mdl-card__title">
                    <h2 class="mdl-card__title-text"><span class="my-title-div">下個小時預估用量:</span></h2>
                    <div class="my-div " id="time">最新資料時間:</div>

                </div>
                <div class="mdl-card__supporting-text">

                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div> <img src="./images/water.png" alt="">

                                <span class="my-div"> &nbsp; 53005 </span>
                                <span class="my-unit-div "> &nbsp;L</span>
                            </div>

                            <div id="speed1_1"></div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div> <img src="./images/electricity.png" alt="">

                                <span class="my-div"> &nbsp; 907 </span>
                                <span class="my-unit-div "> &nbsp;KWH</span>
                            </div>

                            <div id="speed2_1"></div>
                        </div>
                    </div>




                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <div> <img src="./images/steam.png" alt="">

                                <span class="my-div"> &nbsp; 3457 </span>
                                <span class="my-unit-div "> &nbsp;Kg</span>
                            </div>

                            <div id="speed3_1"></div>
                        </div>

                        <div class="col-sm-12 col-md-6">
                            <div> <img src="./images/co2.png" alt="">

                                <span class="my-div"> &nbsp; 4741 </span>
                                <span class="my-unit-div "> &nbsp; Kg CO2e</span>
                            </div>

                            <div id="speed4_1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>






</body>

</html>




<script>
    const queryString = window.location.search;


    function autoRefresh() {
        console.log("function called");

        $.ajax({
            type: 'POST',
            url: "./get_data_ajax.php" + queryString,
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
                $('#water_now').html("&nbsp;" + data.water_now);
                $('#power_now').html("&nbsp;" + data.power_now);
                $('#steam_now').html("&nbsp;" + data.steam_now);
                $('#co2_now').html("&nbsp;" + data.carbon_now);
                // $('#lastTemperature5').append("add");
                // 123123123123
                // console.log(data)
                // console.log("use : " + data.machine_using)
                $('#time').html("最新資料時間:" + data.time_new);






                // 畫圖


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
                            fontSize: '2vh',
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
                        name: '機台使用率',
                        // document.getElementById("power_now").textContent
                        data: [data.machine_using],
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
                            fontSize: '2vh',
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
                        name: '機台使用率',
                        // document.getElementById("power_now").textContent
                        data: [data.machine_using],
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


                // speed3
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
                            fontSize: '2vh',
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
                        name: '機台使用率',
                        // document.getElementById("power_now").textContent
                        data: [data.machine_using],
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

                // speed4
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
                            fontSize: '2vh',
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
                        name: '機台使用率',
                        // document.getElementById("power_now").textContent
                        data: [data.machine_using],
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


                // speed1_1
                Highcharts.chart('speed1_1', {
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
                            fontSize: '2vh',
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
                        name: '機台使用率',
                        // document.getElementById("power_now").textContent
                        data: [0],
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

                // speed2_1
                Highcharts.chart('speed2_1', {
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
                            fontSize: '2vh',
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
                        name: '機台使用率',
                        // document.getElementById("power_now").textContent
                        data: [0],
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

                // speed3_1
                Highcharts.chart('speed3_1', {
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
                            fontSize: '2vh',
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
                        name: '機台使用率',
                        // document.getElementById("power_now").textContent
                        data: [0],
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

                // speed4_1
                Highcharts.chart('speed4_1', {
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
                            fontSize: '2vh',
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
                        name: '機台使用率',
                        // document.getElementById("power_now").textContent
                        data: [0],
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


            },
            error: function (err) {
                console.log("error log:");
                console.log(err)
            }
        });
        setTimeout(function () {
            autoRefresh();
            // clearInterval(timerId);
        }, 30000);
    }
    autoRefresh();
</script>
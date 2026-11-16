<!DOCTYPE html>
<html lang="en">

<head>
    <!--   Core JS Files   -->
    <link href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/common/IDCC_plugin/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="/common/IDCC_plugin/bootstrap-5.0.0-dist/js/bootstrap.min.js"></script>
    <script src="/common/IDCC_plugin/apexcharts/apexcharts.js"></script>
</head>

<body>
    <div class="outside" style="height: 100%; ">
        <div class="row Machine_info_head">
            <div class="col-sm-4" style="height: 100%; text-align: center; color:#ffffff;">
                <h1>染整機台稼動率</h1>
                <p style="text-align: center; color:#01b7ff; font-weight:bold; font-size:60pt">76%</p>
            </div>
            <div class="col-sm-4" style="height: 100%; text-align: center; color:#ffffff;">
                <h1>染整機台投入量</h1>
                <p style="text-align: center; color:#01b7ff; font-weight:bold; font-size:60pt">60%</p>
            </div>
            <div class="col-sm-4" style="height: 100%; text-align: center; color:#ffffff;">
                <h1>染整機台故障率</h1>
                <p style="text-align: center; color:#01b7ff; font-weight:bold; font-size:60pt">0.06%</p>
            </div>

        </div>
        <div class="row Machine_info_body" style="color:#ffffff;">
            <div class="col-sm-2" style="height: 100%; text-align: center; color:#ffffff; background-color:none">
                <div id='Proportionchart'></div>
            </div>
            <div class="col-sm-10" style="height: 100%; text-align: center;">
                <div class="row Machine_info_block" style=" justify-content: center;">
                    <div class="col-sm-2"
                        style="margin: 0px 3rem; border-radius: 20px; height: 100%; text-align: center; color:#dfc6dc; border-left: 5px solid #dfc6dc; border-right: 5px solid #dfc6dc;">
                        <h4>機台數量</h4>
                        <p
                            style="margin-bottom: 0rem; text-align: center; color:#dfc6dc; font-weight:bold; font-size:50pt">
                            35</p>
                    </div>
                    <div class="col-sm-2"
                        style="margin: 0px 3rem; border-radius: 20px; height: 100%; text-align: center; color:#e9ed93; border-left: 5px solid #e9ed93; border-right: 5px solid #e9ed93;">
                        <h4>當前運行機台</h4>
                        <p
                            style="margin-bottom: 0rem; text-align: center; color:#e9ed93; font-weight:bold; font-size:50pt">
                            31</p>
                    </div>
                    <div class="col-sm-2"
                        style="margin: 0px 3rem; border-radius: 20px; height: 100%; text-align: center; color:#bedee5; border-left: 5px solid #bedee5; border-right: 5px solid #bedee5;">
                        <h4>故障機台數量</h4>
                        <p
                            style="margin-bottom: 0rem; text-align: center; color:#bedee5; font-weight:bold; font-size:50pt">
                            1</p>
                    </div>
                    <div class="col-sm-2"
                        style="margin: 0px 3rem; border-radius: 20px; height: 100%; text-align: center; color:#f3bb95; border-left: 5px solid #f3bb95; border-right: 5px solid #f3bb95;">
                        <h4>警告發生設備</h4>
                        <p
                            style="margin-bottom: 0rem; text-align: center; color:#f3bb95; font-weight:bold; font-size:50pt">
                            5</p>
                    </div>
                </div>
                <div class="row Machine_info_chart">
                    <div class="col-sm-5"
                        style=" height: 100%; text-align: center; color:#dfc6dc;">
                        <div id="CircleGaugechart1"></div>
                    </div>
                    <div class="col-sm-5"
                        style=" height: 100%; text-align: center; color:#dfc6dc; ">
                        <div id="CircleGaugechart2"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row Machine_info_footer_table">
            <table style="width:100%" class="dye_event"> <!--StartFragment-->
                <thead>
                    <tr height="22">
                        <td>警報原因</td>
                        <td>總次數</td>
                        <td>總時間</td>
                        <td>平均損耗</td>
                        <td>每缸發生頻率</td>
                        <td>每缸浪費時間</td>
                        <td>狀態</td>
                    </tr>
                </thead>
                <tbody>
                    <tr height="22">
                        <td>蒸氣不足</td>
                        <td>20901</td>
                        <td>72837</td>
                        <td>3.48</td>
                        <td>10.0</td>
                        <td>34.84</td>
                        <td>觸發示警</td>
                    </tr>
                    <tr height="22">
                        <td>冷卻水不足</td>
                        <td>15529</td>
                        <td>44383</td>
                        <td>2.86</td>
                        <td>7.5</td>
                        <td>21.45</td>
                        <td>觸發示警</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row Machine_info_footer_chart">

            <div class="chart" id="chart_footer" style="height:100px"></div>
        </div>


    </div>
</body>
<style>
    html,
    body {
        height: 94%;
        width:99%;
        margin: 0;
        /* zoom:90%; */
    }

    body {
        background: linear-gradient(to bottom,
                rgba(70, 70, 70, 1) 0%,
                /* 深色 */
                rgba(70, 70, 70, 1) 10%,
                /* 深色結束點 */
                rgba(100, 100, 100, 1) 50%,
                /* 淺色中間點 */
                rgba(70, 70, 70, 1) 95%,
                /* 深色開始點 */
                rgba(70, 70, 70, 1) 100%
                /* 深色 */
            );
        font-family: Arial, sans-serif;
    }



    .title {
        /* background-color: lightgrey; */
        /* 為了展示效果，添加背景顏色 */
        padding: 0px 5px;
        /* 添加一些內邊距 */
        font-weight: bold;
        /* 或者使用数值 700 */
        font-size: 24pt;
        /* 或者使用其他单位，例如 em, rem, %, vw, 等 */
        width: 100%;
        text-align: center;
    }

    .data {
        /* background-color: lightgrey; */
        /* 為了展示效果，添加背景顏色 */
        padding: 0px 5px;
        /* 添加一些內邊距 */
        font-weight: bold;
        /* 或者使用数值 700 */
        font-size: 50pt;
        /* 或者使用其他单位，例如 em, rem, %, vw, 等 */
        width: 100%;
        text-align: center;
    }

    .unit {
        /* background-color: lightgrey; */
        /* 為了展示效果，添加背景顏色 */
        padding: 0px 5px;
        /* 添加一些內邊距 */
        font-weight: bold;
        /* 或者使用数值 700 */
        font-size: 18pt;
        /* 或者使用其他单位，例如 em, rem, %, vw, 等 */
        width: 100%;
        text-align: end;
        /* // 文字水平置中 */
    }

    .outside {
        /* margin: 10px */
    }

    .Machine_info_head {
        height: 20%;
        margin: 5px 0;
    }

    .Machine_info_footer_chart {
        height: 25%;
        margin-top: 60px;
    }

    .Machine_info_footer_table {
        height: 8%;
        margin: 0px 0;
    }

    .Machine_info_body {
        height: 48%;
        margin: 5px 0;
    }

    ;

    .Machine_info_block {
        height: 25%;
        margin: 5px 0;
    }

    .Machine_info_chart {
        height: 70%;
        margin: 10px 0 0px 0;
        justify-content: center;
    }

    .dye_event {
        border-top: 5px solid #51b8cb;
        border-bottom: 5px solid #51b8cb;
        /* border-left: 5px solid #d5bbf5; */
        border-spacing: 0;
        width: 100%
    }

    .dye_event td {
        /* border-bottom: 5px solid #d5bbf5; */
        /* border-right: 5px solid #d5bbf5; */
        font-size: 16pt;
        padding: 5px;
        text-align: center;
        color: #ffffff;
        font-weight: bold;
    }

    .chart {
        /* background-color: #000000; */
        /* 正确写法，无引号 */
        width: 100%;
        margin: 0 0px;
    }
</style>

</html>


<script>

    // 改Y軸單位
    var labelFormatter = function (value) {
        var val = Math.abs(value);
        if (val >= 1000) {
            val = (val / 1000).toFixed(1) + " K";
        }
        return val;
    };

    draw_chart('染機異常時間', "#chart_footer", ['蒸氣不足', '冷卻水不足', '系統手動操作', '數字消耗異常', '低水位警示', '纏車', '主馬達異常', '溫度異常'], [10, 41, 35, 51, 49, 62, 69, 91], ['#64B5F6']);


    function draw_chart(chart_title, chartID, chart_value_X, chart_value_Y, chart_value_color) {
        var options = {
            series: [{
                name: "警報",
                data: chart_value_Y
            },
                // {
                //     name: "test",
                //     data: [2, 27, 53, 75, 49, 75, 44, 5, 86]
                // }
            ],
            chart: {

                events: {


                },

                background: 'rgba(156,191,227,0)',
                //   width: '100%',
                height: '100%',

                //   height: '100%',
                //   height: '200px',
                type: 'bar',
                dropShadow: {
                    enabled: true,
                    color: '#000',
                    top: 18,
                    left: 7,
                    blur: 10,
                    opacity: 0.2
                },
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: true, // 启用缩放功能
                }
            },
            // colors: ['#FF5252', '#FFD740', '#64B5F6', '#BA68C8', '#FFA726', '#26C6DA', '#FFCA28', '#4CAF50', '#E91E63'],
            colors: chart_value_color,

            dataLabels: {
                enabled: false,
                //rotation: -30, // 将数据标签逆时针旋转45度
                style:
                {
                    fontSize: '14px' // 将字体大小设置为8像素
                }
            },
            stroke: {
                // curve: 'smooth'
            },
            title: {
                text: chart_title,
                align: 'center',
                offsetY: 15,
                style: {
                    fontSize: '20px',
                    fontWeight: 'bold',
                    fontFamily: undefined,
                    color: 'white'
                },
            },
            grid: {
                borderColor: '#e7e7e7',
                row: {
                    colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            markers: {
                size: 1,
                shape: "square",
                //   rotation: -30 // 设置标记的旋转角度为30度

                //offsetY: -5, // 向上移动标记，避免遮挡 Y 轴
                //offsetX: 10 // 向右移动标记，与线条分开 // "circle" | "square" | "rect"
            },
            xaxis: {
                tickPlacement: 'between',
                //   categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                categories: chart_value_X,

                //   categories: <?php echo json_encode($chart_x); ?>,
                //tickAmount: 10,
                labels: {
                    show: true,
                    // rotate: -60, // 将x轴标签旋转成45度
                    // rotation: 0,
                    // rotateAlways: true, // 始终旋转标签
                    // trim: true,
                    style: {
                        fontSize: '16px' // 设置标签字体大小
                    },
                    tickAmount: 15,
                    // formatter: function(value, index) {
                    //             // 仅显示单数标签
                    //             return index % 2 === 0 ? value : '';
                    //         }
                },
                title: {
                    text: ''
                }

            },
            yaxis: [
                {

                    //左側Y軸
                    title: {
                        text: ''
                    },
                    labels: {
                        formatter: labelFormatter,
                        //   offsetX: -30,
                        offsetY: 0,
                        style: {
                            colors: [],
                            fontSize: '16px',
                            fontFamily: 'Helvetica, Arial, sans-serif',
                            fontWeight: 300,
                            cssClass: 'apexcharts-yaxis-label',
                        }
                    },

                },
                // {
                //   //右側顯示y軸刻度，讓ApexChart寬度不受限制
                //   opposite: true, // 将此属性放置在要显示在右侧的 Y 轴对象中
                //   title: {
                //     text: ''
                //   },

                //   labels: {
                //     formatter: labelFormatter,
                //     offsetX: -40,
                //     offsetY: 0,
                //     style: {
                //       colors: [],
                //       fontSize: '8px',
                //       fontFamily: 'Helvetica, Arial, sans-serif',
                //       fontWeight: 300,
                //       cssClass: 'apexcharts-yaxis-label',
                //     }
                //   }
                // }
            ],

            /*yaxis: [
            {
              title: {
                text: ''
              },
              labels: {
                formatter: labelFormatter,
                offsetX: -40,
                offsetY: 0,
                style: {
                  colors: [],
                  fontSize: '8px',
                  fontFamily: 'Helvetica, Arial, sans-serif',
                  fontWeight: 300,
                  cssClass: 'apexcharts-yaxis-label',
                },
              }
            },
            opposite: true
        
          ],*/



            legend: {
                show: false,
                showForSingleSeries: false,
                // position: 'top',
                // horizontalAlign: 'right',
                // floating: true,
                // offsetY: -25,
                // offsetX: -5,
                toggleDataSeries: false,
            },
            theme: {
                mode: 'dark',
                palette: 'palette1',
                monochrome: {
                    // enabled: true,
                    // color: '#0084ff', // 设置标签颜色
                }
            }
        };



        var chart = new ApexCharts(document.querySelector(chartID), options);
        chart.render();
    }
</script>



<script>
    draw_Proportionchart('#Proportionchart', ['蒸氣不足', '冷卻水不足', '系統手動操作', '其他'], [40, 25, 25, 10]);
    // 長條比例圖
    function draw_Proportionchart(chartID, chart_value_X, chart_value_Y) {
        var series = [];
        for (let i = 0; i < chart_value_X.length; i++) {
            series.push({
                name: chart_value_X[i],
                data: [chart_value_Y[i]],

            });
        }
        var options = {
            // 自動循環顏色
            colors: ['#5281BE', '#BA565B', '#A1BA58', '#7B60A3'],
            series: series,
            chart: {
                background: 'transparent',
                type: 'bar',
                height: '100%',
                stacked: true,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: true
                },
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    legend: {
                        position: 'bottom',
                        offsetX: -10,
                        offsetY: 0
                    }
                }
            }],
            dataLabels: {

                enabled: true,
                formatter: function (value, { seriesIndex, dataPointIndex, w }) {

                    return w.config.series[seriesIndex].name; // 返回对应的标签名称
                },
                style: {
                    fontSize: '20px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 'bold',
                    colors: ['black']
                },
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 0,
                    dataLabels: {

                        total: {
                            offsetY: -10,
                            // offsetX: 50,
                            // enabled: true,
                            enabled: false,
                            style: {
                                fontSize: '26px',
                                fontWeight: 900,
                                color: '#ffc000',


                            }
                            // offsetY: -10 ,
                        }
                    }
                },

            },

            xaxis: {

                // type: 'datetime',
                // categories: ['01/01/2011 GMT', '01/02/2011 GMT', '01/03/2011 GMT', '01/04/2011 GMT',
                //   '01/05/2011 GMT', '01/06/2011 GMT'
                // ],
                // 這是X軸
                categories: [''],
                title: {
                    text: "",
                    offsetX: -930,
                    offsetY: 135,
                    style: {
                        color: undefined,
                        fontSize: '30px',
                        fontFamily: 'Helvetica, Arial, sans-serif',
                        fontWeight: 600,
                        cssClass: 'apexcharts-xaxis-title',
                    },
                },
            },
            yaxis: {

                max: 100,
                labels: {
                    formatter: function (value) {
                        return value + '%'; // 在 y 轴标签后面加上 '%'
                    }
                },
            },
            legend: {
                show: false,
                position: 'bottom',
                fontSize: '20px',
                // offsetY: 40
            },
            fill: {
                opacity: 1
            },
            theme: {
                mode: 'dark',
                palette: 'palette5',
            }
        };

        var chart = new ApexCharts(document.querySelector(chartID), options);
        chart.render();
    }
</script>

<script>
    draw_CircleGaugechart('染缸當前稼動率', "#CircleGaugechart1", '',88.5,'#869E56');
    draw_CircleGaugechart('警報發生率', "#CircleGaugechart2", '',30.7,'#4f81bc');

    function draw_CircleGaugechart(chart_title, chartID, chart_value_X, chart_value_Y, chart_value_color) {

        var options = {
            series: [chart_value_Y],
            
            chart: {
                type: 'radialBar',
                offsetY: -20,
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {
                radialBar: {
                    startAngle: -90,
                    endAngle: 90,
                    track: {
                        background: "#fff",
                        strokeWidth: '97%',
                        margin: 5, // margin is in pixels
                        dropShadow: {
                            enabled: true,
                            top: 2,
                            left: 0,
                            color: '#999',
                            opacity: 1,
                            blur: 2
                        }
                    },
                    dataLabels: {
                        name: {
                            show: false
                        },
                        value: {
                            color: 'white',
                            offsetY: -2,
                            fontSize: '62px'
                        }
                    }
                }
            },
            grid: {
                padding: {
                    top: -10
                }
            },
            fill: {
                colors: chart_value_color,
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    shadeIntensity: 0.4,
                    inverseColors: false,
                    opacityFrom: 1,
                    opacityTo: 1,
                    stops: [0, 50, 53, 91]
                },
            },
            labels: ['Average Results'],
            title: {
                offsetY: 20,
                text: chart_title,
                align: 'center',
                style: {
                    fontSize: '40px',
                    fontWeight: 'bold',
                    fontFamily: undefined,
                    color: 'white'
                },
            },
        };

        var chart = new ApexCharts(document.querySelector(chartID), options);
        chart.render();
    }

</script>
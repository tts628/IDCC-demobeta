<?php
error_reporting(E_ALL);
ini_set('display_errors', '0');
// session_start();


require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");
$lang = new Language();
$lang->load("general");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!--   Core JS Files   -->
    <link href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/common/IDCC_plugin/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="/common/IDCC_plugin/bootstrap-5.0.0-dist/js/bootstrap.min.js"></script>
    <script src="/common/IDCC_plugin/apexcharts/apexcharts.js"></script>

    <!-- <script src="https://d3js.org/d3.v3.min.js"></script> -->
    <script src="/common/IDCC_plugin/Datamaps/d3.v3.min.js"></script>
    <!-- 引入 TopoJSON 库 -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/topojson/1.6.9/topojson.min.js"></script> -->
    <script src="/common/IDCC_plugin/Datamaps/topojson.min.js"></script>
    <!-- 引入 Datamaps 库 -->
    <!-- <script src="https://datamaps.github.io/scripts/datamaps.world.min.js"></script> -->
    <script src="/common/IDCC_plugin/Datamaps/datamaps.world.min.js"></script>

</head>
<style>
    html,
    body {
        height: 99%;
        width: 100%;
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


    .map_area {
        height: 80%;
        width: 100%;
    }

    #map1 {
        position: relative;
        width: 100%;
        /* 您可以根据需要更改宽度 */
        height: 100%;
        /* 您可以根据需要更改高度 */
    }

    .hoverinfo {
        color: black;
        /* 设置文本颜色为红色 */
        font-weight: bold;
        /* 设置文本加粗 */
        height: 100px;
        width: 300px;
        font-size: 30px !important;
    }

    .bar_area {
        height: 10%;
        width: 100%;

    }

    /* 自定义气泡标记文本的样式 */
    .bubble-label {
        fill: #FF0000;
        /* 设置文本颜色为红色 */
        font-weight: bold;
        /* 设置文本加粗 */
        font-size: 12px;
        /* 设置文本大小 */
    }


    /* .datamaps-hoverover{
        display:block!important;
    } */
</style>

<body>
    <div class="outside" style="height: 100%; ">
        <div class="row map_area" id="world_map">
            <div class="col-sm-10" style="height: 100%; text-align: center; color:#ffffff; background-color:none">
                <div id='map1'></div>
                <script>
                    map_value(110224);


                    // value:仁澤場生產幾公斤
                    function map_value(value) {
                        // Initialize the map
                        var map = new Datamap({
                            element: document.getElementById('map1'),
                            done: function (geo, data) {
                                const element = document.getElementById('world_map');
                                const newDiv = document.createElement('div');
                                newDiv.className = 'hoverinfo';
                                newDiv.id = 'Vietnam';
                                newDiv.innerHTML = '<strong>'+'<?php echo $lang->line("general.Renze_Field_Production"); ?>'+'<br>' + value + ' KG</strong>';
                                element.style.position = 'relative';
                                newDiv.style.position = 'absolute';
                                newDiv.style.left = '50%';
                                newDiv.style.top = '65%';
                                // newDiv.style.transform = 'translateY(-100%)';
                                newDiv.style.textAlign = 'center'; // 文字置中
                                element.appendChild(newDiv);

                            },
                            projection: 'mercator', // Default projection

                            geographyConfig: {
                                popupOnHover: false,
                                highlightOnHover: false

                            },

                            fills: {
                                defaultFill: '#B7B7B7', // Default fill color
                                BUBBLE: 'red', // 气泡颜色
                                // Define your custom colors
                            },
                            data: {
                                VNM: { fillKey: 'defaultFill' }

                            }
                        });

                        // Add bubbles
                        map.bubbles([
                            { name: 'Vietnam', latitude: 14.0583, longitude: 108.2772, radius: 10, fillKey: 'BUBBLE' } // 添加越南的气泡标记
                        ], {
                            popupOnHover: false, // 禁止悬停时显示信息框
                            highlightOnHover: false, // 禁止悬停高亮

                            /* 指到氣泡可以顯示當地數據
                            popupTemplate: function (geo, data) {
                                // 直接显示人口数
                                document.getElementById('Vietnam').style.display = 'none';

                                return '<div class="hoverinfo"><strong>仁澤場生產<br>' + value + ' KG</strong></div>';
                            },*/


                        },
                        );
                        // 添加文本标签
                    }
                </script>
            </div>
            <div class="col-sm-2" style="height: 100%; text-align: center; color:#ffffff; background-color:none">
                <div id='pie1' style="margin-left:-15vw;margin-top:10vh"></div>
            </div>
        </div>


        <div class="row bar_area">
            <div class="col-sm-6" style="height: 100%; text-align: center; color:#ffffff; background-color:none">
                <div id='bar1'></div>
            </div>
            <div class="col-sm-6" style="height: 100%; text-align: center; color:#ffffff; background-color:none">
                <div id='bar2'></div>
            </div>
        </div>

        <div class="row bar_area">
            <div class="col-sm-6" style="height: 100%; text-align: center; color:#ffffff; background-color:none">
                <div id='bar3'></div>
            </div>
            <div class="col-sm-6" style="height: 100%; text-align: center; color:#ffffff; background-color:none">
                <div id='bar4'></div>
            </div>
        </div>





    </div>
</body>


</html>

<!-- Y軸設定 -->
<script>

    // 改Y軸單位
    var labelFormatter = function (value) {
        var val = Math.abs(value);
        if (val >= 1000) {
            val = (val / 1000).toFixed(1) + " K";
        }
        return val;
    };

    // draw_chart('染機異常時間', "#chart_footer", ['蒸氣不足', '冷卻水不足', '系統手動操作', '數字消耗異常', '低水位警示', '纏車', '主馬達異常', '溫度異常'], [10, 41, 35, 51, 49, 62, 69, 91], ['#64B5F6']);

</script>


<!-- 長條比例圖 -->
<script>
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
                    colors: ['white']
                },
            },
            plotOptions: {
                bar: {
                    horizontal: true,
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


<!-- 畫圓餅圖 -->
<script>
    function draw_piechart(chartID, chart_value_X, chart_value_Y) {

        // var series = [];
        // for (let i = 0; i < chart_value_X.length; i++) {
        //     series.push({
        //         name: chart_value_X[i],
        //         data: [chart_value_Y[i]],

        //     });
        // }

        var options2 = {
            series: chart_value_Y,
            colors: ['#4082CE', '#D3423F'],
            chart: {
                width: '100%',
                type: 'donut',

            },
            plotOptions: {
                pie: {
                    startAngle: 0,
                    endAngle: 360,
                    donut: {
                        size: '50%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                showAlways: false,
                                fontSize: '2vw',
                                label: '<?php echo $lang->line("general.Load_factor"); ?>',

                                color: '#F1F147',
                                formatter: function (w) {
                                    return parseInt(chart_value_Y[0] * 100 / (chart_value_Y[0] + chart_value_Y[1]), 10) + '%';

                                },

                            },
                            value: {
                                show: true,
                                fontSize: '2.5vw',
                                fontFamily: 'Helvetica, Arial, sans-serif',
                                fontWeight: 400,
                                color: '#F1F147',
                                offsetY: 30,
                                formatter: function (val) {
                                    return val
                                }
                            },
                        },




                    }
                },


            },
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '1.35vw',
                },
                // formatter(val, opts) {
                //   const name = opts.w.globals.labels[opts.seriesIndex]
                //   return [name, val.toFixed(1) + '%']


                // }
                formatter(val, opts) {
                    // console.log(opts.w.globals.labels);
                    // return [opts.w.globals.labels[opts.seriesIndex],"",opts.w.globals.seriesTotals[opts.seriesIndex] + '杯']
                    // return [opts.w.globals.seriesTotals[opts.seriesIndex] + '杯']
                    return [opts.w.globals.labels[opts.seriesIndex] + '']
                },
                // value: 'raw'
            },

            fill: {
                type: 'gradient',
            },
            legend: {
                show: false,
                formatter: function (val, opts) {
                    return val + " - " + opts.w.globals.series[opts.seriesIndex]
                },
                position: 'bottom'
            },
            title: {
                text: '',
                align: 'center',
                style: {
                    fontSize: '14px',
                    fontWeight: 'bold',
                    fontFamily: undefined,
                    color: 'white'
                },
            },
            labels: chart_value_X,

            //     theme: {
            // monochrome: {
            //   enabled: true,
            //   color: '#0084ff', // 设置标签颜色
            // }

            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }]
        };

        var chart2 = new ApexCharts(document.querySelector(chartID), options2);
        chart2.render();
    }
</script>

<!-- 畫圖 -->
<script>
    draw_Proportionchart('#bar1', ['', '<?php echo $lang->line("general.Available_casting_capacity"); ?>'], [40, 60]);
    draw_Proportionchart('#bar2', ['', '<?php echo $lang->line("general.Available_molding_capacity"); ?>'], [50, 50]);
    draw_Proportionchart('#bar3', ['', '<?php echo $lang->line("general.Available_packaging_capacity"); ?>'], [25, 75]);
    draw_Proportionchart('#bar4', ['', '<?php echo $lang->line("general.Available_production_capacity"); ?>'], [21, 79]);
    draw_piechart("#pie1", ['<?php echo $lang->line("general.Load_factor"); ?>', '<?php echo $lang->line("general.Still_accepting_orders"); ?>'], [42, 60]);


    console.log("<?= $lang->line("general.production_statistics") ?>")
</script>
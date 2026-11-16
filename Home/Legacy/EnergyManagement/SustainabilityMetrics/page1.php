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
    <div class="outside" style="height: 100%;">
        <div class="row" style="height: 18vh;">
            <div class="col-sm-8" style="height: 100%;">
                <table style="width:100%" class="soft_water"> <!--StartFragment-->
                    <colgroup>
                        <col width="38">
                        <col width="125" span="4">
                    </colgroup>
                    <tbody>
                        <tr height="22">
                            <td rowspan="4" class="xl73">軟<br>水</td>
                            <td>檢測項目</td>
                            <td>數值</td>
                            <td>單位</td>
                            <td>狀態</td>
                        </tr>
                        <tr height="22">
                            <td>硬度</td>
                            <td>22</td>
                            <td>ppm</td>
                            <td class="xl7">正常</td>
                        </tr>
                        <tr height="22">
                            <td>鐵</td>
                            <td>0.02</td>
                            <td>ppm</td>
                            <td class="xl7">正常</td>
                        </tr>
                        <tr height="23">
                            <td>PH</td>
                            <td>7.24</td>
                            <td>　</td>
                            <td class="xl7">正常</td>
                        </tr> <!--EndFragment-->
                    </tbody>
                </table>
            </div>
            <div class="col-sm-4" style="border-left: 5px solid #63a1ed;  border-right: 5px solid #63a1ed;height: 95%;">
                <div class="row">
                    <div class="title col-12" style="color:#63a1ed;">水布比</div>
                    <div class="data col-12" style="color:#63a1ed;">83.1</div>
                    <div class="unit col-12" style="color:#63a1ed;">L</div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8" style="height: 100%;">
                <table style="width:100%" class="waste_water"> <!--StartFragment-->
                    <colgroup>
                        <col width="38">
                        <col width="125" span="4">
                    </colgroup>
                    <tbody>
                        <tr height="22">
                            <td rowspan="5" class="xl74">廢<br>水</td>
                            <td>檢測項目</td>
                            <td>數值</td>
                            <td>單位</td>
                            <td>狀態</td>
                        </tr>
                        <tr height="22">
                            <td>色度</td>
                            <td>85</td>
                            <td>Pt-Co</td>
                            <td class="xl7">正常</td>
                        </tr>
                        <tr height="22">
                            <td>COD</td>
                            <td>116</td>
                            <td>ppm</td>
                            <td class="xl7">正常</td>
                        </tr>
                        <tr height="22">
                            <td>氨氮</td>
                            <td>1.35</td>
                            <td>ppm</td>
                            <td class="xl7">正常</td>
                        </tr> <!--EndFragment-->
                        <tr height="23">
                            <td>PH</td>
                            <td>7.78</td>
                            <td></td>
                            <td class="xl7">正常</td>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-4" style="border-left: 5px solid #ffef18;  border-right: 5px solid #ffef18;height: 95%;">
                <div class="row">
                    <div class="title col-12" style="color:#ffef18;">電布比</div>
                    <div class="data col-12" style="color:#ffef18;">0.22</div>
                    <div class="unit col-12" style="color:#ffef18;">KWH</div>
                </div>
            </div>
        </div>
        <!-- <div class="row">


            <div class="col-sm-8" style="height: 95%;">
                <div class="row" style="height: 100%;margin:0px">
                    <div class="col-sm-3"
                        style="margin-right:20px; padding:0;  border-left: 5px solid #63a1ed;  border-right: 5px solid #63a1ed;height: 100%;">
                        <div class="row">
                            <div class="title col-12" style="color:#63a1ed;">水布比</div>

                        </div>
                    </div>
                    <div class="col-sm-3"
                        style="margin-right:20px; padding:0; border-left: 5px solid #ffef18;  border-right: 5px solid #ffef18;height: 100%;">
                        <div class="row">
                            <div class="title col-12" style="color:#ffef18;">電布比</div>

                        </div>
                    </div>
                    <div class="col-sm-5"
                        style=" padding:0; border-left: 5px solid #7e9d29;  border-right: 5px solid #7e9d29;height: 100%;">
                        <div class="row">
                            <div class="title col-12" style="color:#7e9d29;">當月碳排放量</div>
                            <div class="data col-12" style="color:#7e9d29;">118113</div>
                            <div class="unit col-12" style="color:#7e9d29;">Kg CO2e</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 "
                style="margin-left:0px;border-left: 5px solid #b8b7ac;  border-right: 5px solid #b8b7ac; height: 95%;">
                <div class="row">
                    <div class="title col-12" style="color:#b8b7ac;">當月累計瓦斯用量</div>
                    <div class="data col-12" style="color:#b8b7ac;">68278</div>
                    <div class="unit col-12" style="color:#b8b7ac;">m<sup>3</sup></div>
                </div>
            </div>
        </div> -->


        <div class="row" >
            <div class="col" style="padding:0px; margin-top: 2.5vh">
                <div class="title" style="padding:0px; height:4vh; color:#64B5F6;">當月累積用水量</div>
                <div class="Digit" id="DigitDisplay1" style="height:12vh;"></div>
                <div class="unit col-12" style="color:#64B5F6;">Ton</div>
                <div class="chart" id="chart1" style="height:4vh;"></div>
            </div>
            <div class="col" style="padding:0px; margin-top: 2.5vh">
                <div class="title" style="padding:0px; height:4vh; color:#FFD740;">當月累積用電量</div>
                <div class="Digit" id="DigitDisplay2" style="height:12vh;"></div>
                <div class="unit col-12" style="color:#FFD740;">KWH</div>
                <div class="chart" id="chart2" style="height:4vh"></div>
            </div>
            <div class="col" style="padding:0px; margin-top: 2.5vh">
                <div class="title" style="padding:0px; height:4vh; color:#FFA726;">當月累計瓦斯用量</div>
                <div class="Digit" id="DigitDisplay3" style="height:12vh;"></div>
                <div class="unit col-12" style="color:#FFA726;">m<sup>3</sup></div>
                <div class="chart" id="chart3" style="height:4vh"></div>
            </div>
            <div class="col" style="padding:0px; margin-top: 2.5vh">
                <div class="title" style="padding:0px; height:4vh; color:#BA68C8;">當月碳排放量</div>
                <div class="Digit" id="DigitDisplay4" style="height:12vh;"></div>
                <div class="unit col-12" style="color:#BA68C8;">Kg CO2e</div>
                <div class="chart" id="chart4" style="height:4vh"></div>
            </div>
            <div class="col" style="padding:0px; margin-top: 2.5vh">
                <div class="title" style="padding:0px; height:4vh; color:#459D52;">當月太陽能</div>
                <div class="Digit" id="DigitDisplay5" style="height:12vh;"></div>
                <div class="unit col-12" style="color:#459D52;">KWH</div>
                <div class="chart" id="chart5" style="height:4vh"></div>
            </div>

        </div>


        <!-- <div class="row" id="chartrow">






        </div> -->
    </div>
</body>
<style>
    html,
    body {
        height: 94%;
        /* width: 95%; */
        margin: 0;
        /* zoom: 0.9; */
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

    .row {
        /* height: 23%; */
        height:23vh;
        margin: 15px 0;
    }

    .soft_water {
        border-top: 5px solid #d5bbf5;
        border-bottom: 5px solid #d5bbf5;
        /* border-left: 5px solid #d5bbf5; */
        border-spacing: 0;
        width: 100%
    }

    .soft_water td {
        /* border-bottom: 5px solid #d5bbf5; */
        /* border-right: 5px solid #d5bbf5; */
        font-size: 16pt;
        padding: 5px;
        text-align: center;
        color: #ffffff;
        font-weight: bold;
    }



    .xl73 {
        border-right: 5px solid #d5bbf5;
    }

    .waste_water {
        border-top: 5px solid #1787ae;
        border-bottom: 5px solid #1787ae;
        /* border-left: 5px solid #d5bbf5; */
        border-spacing: 0;
        width: 100%
    }

    .waste_water td {
        /* border-bottom: 5px solid #d5bbf5; */
        /* border-right: 5px solid #d5bbf5; */
        font-size: 16pt;
        padding: 5px;
        text-align: center;
        color: #ffffff;
        font-weight: bold;
    }



    .xl74 {
        border-right: 5px solid #1787ae;
    }

    .xl7 {
        color: #7e9d29;
    }

    /* .xl66 {
        text-align: center;
    } */

    #chartrow {
        justify-content: center;
    }

    .chart,
    .Digit {

        /* background-color: #000000; */
        /* 正确写法，无引号 */
        /* width: 20%; */

        display: flex;
        justify-content: center;
        align-items: center;
        /* width: 19%; */
        /* width: 20vw; */
        height: 100%;
        /* height: 250px; */
        margin: 0px 0px;
        color: red;
    }


    .digitSize {
        position: relative;
        /* width: 50px;
        height: 100px; */
        width: 2vw;
        height: 7vh;
        margin: 0 5px;
    }

    /* 顯示器背景顏色 */
    .segment {
        position: absolute;
        /* background: white; */
        background: rgba(0, 0, 0, 0);
        border-radius: 2px;
    }

    .segment.on {
        background: black;
    }

    .segment.a {
        top: 0;
        left: 10%;
        width: 80%;
        height: 10%;
    }

    .segment.b {
        top: 10%;
        right: 0;
        width: 10%;
        height: 40%;
    }

    .segment.c {
        bottom: 10%;
        right: 0;
        width: 10%;
        height: 40%;
    }

    .segment.d {
        bottom: 0;
        left: 10%;
        width: 80%;
        height: 10%;
    }

    .segment.e {
        bottom: 10%;
        left: 0;
        width: 10%;
        height: 40%;
    }

    .segment.f {
        top: 10%;
        left: 0;
        width: 10%;
        height: 40%;
    }

    .segment.g {
        top: 50%;
        left: 10%;
        width: 80%;
        height: 10%;
        transform: translateY(-50%);
    }

    @keyframes blink {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }
    }

    .blink {
        animation: blink 0.5s linear;
    }

    #display1,
    #display2 {
        /* border: 2px solid black; */
        display: flex;
        justify-content: space-around;
        align-items: center;
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

    draw_chart('熱媒瓦斯', "#chart1", ['1', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'], [10, 41, 35, 51, 49, 62, 69, 91, 148], ['#64B5F6']);
    draw_chart('蒸氣瓦斯', "#chart2", ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'], [10, 41, 35, 51, 49, 62, 9, 91, 148], ['#FFD740']);
    draw_chart('入軟水', "#chart3", ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'], [10, 1, 35, 51, 49, 62, 9, 91, 148], ['#FFA726']);
    draw_chart('軟水用量', "#chart4", ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'], [10, 41, 5, 51, 9, 62, 69, 91, 148], ['#BA68C8']);
    draw_chart('冷卻水', "#chart5", ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'], [61, 51, 96, 16, 76, 39, 12, 13, 83, 77, 17], ['#459D52']);

    function draw_chart(chart_title, chartID, chart_value_X, chart_value_Y, chart_value_color) {
        var options = {
            series: [{
                name: "Desktops",
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
                // width: '100%',
                //   height: '100%',
                height: '210px',
                type: 'line',
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
                    rotateAlways: true, // 始终旋转标签
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

    // DigitDispaly(目標數字, ID, 顏色)
    DigitDispaly(561658, 'DigitDisplay1', '#64B5F6');
    DigitDispaly(997346, 'DigitDisplay2', '#FFD740');
    DigitDispaly(805614, 'DigitDisplay3', '#FFA726');
    DigitDispaly(997369, 'DigitDisplay4', '#BA68C8');
    DigitDispaly(991239, 'DigitDisplay5', '#459D52');
    function DigitDispaly(IdValue, IdName, IdColor) {

        const segments = {
            '0': ['a', 'b', 'c', 'd', 'e', 'f'],
            '1': ['b', 'c'],
            '2': ['a', 'b', 'd', 'e', 'g'],
            '3': ['a', 'b', 'c', 'd', 'g'],
            '4': ['b', 'c', 'f', 'g'],
            '5': ['a', 'c', 'd', 'f', 'g'],
            '6': ['a', 'c', 'd', 'e', 'f', 'g'],
            '7': ['a', 'b', 'c'],
            '8': ['a', 'b', 'c', 'd', 'e', 'f', 'g'],
            '9': ['a', 'b', 'c', 'd', 'f', 'g'],
            '': []
        };

        function createDigit() {
            const digit = document.createElement('div');
            digit.className = 'digitSize';
            ['a', 'b', 'c', 'd', 'e', 'f', 'g'].forEach(segmentClass => {
                const segment = document.createElement('div');
                segment.className = `segment ${segmentClass}`;
                digit.appendChild(segment);
            });
            return digit;
        }

        function displayNumber(number, IdName, IdColor) {
            const display = document.getElementById(IdName);
            document.getElementById(IdName).innerHTML = ''; // 清空現有的顯示


            number.padStart(6, '').split('').forEach(digit => {
                const digitElement = createDigit();
                segments[digit].forEach(segmentClass => {
                    // digitElement.querySelector(`.segment.${segmentClass}`).classList.add('on');
                    digitElement.querySelector(`.segment.${segmentClass}`).style.background = IdColor;
                });
                display.appendChild(digitElement);
            });
        }

        function animateToNumber(target, IdName, IdColor) {
            let current = 0;
            const stepTime = 50;
            const currentPlus = (target / 30).toFixed(0);
            const interval = setInterval(() => {
                displayNumber(current.toString(), IdName, IdColor);
                if (current < target) {
                    if (current + parseInt(currentPlus, 10) > target)
                        current = target;
                    else
                        current += parseInt(currentPlus, 10);
                } else {
                    clearInterval(interval); // 停止动画
                    setTimeout(() => {
                        document.getElementById(IdName).classList.add('blink'); // 添加闪烁效果
                        setTimeout(() => {
                            document.getElementById(IdName).classList.remove('blink'); // 移除闪烁效果
                        }, 500);
                    }, stepTime); // 等待上一个数字显示完成
                }
            }, stepTime);
        }

        function getRandomNumber() {
            return 6061; // 隨機生成0-999999的數字
            // return Math.floor(Math.random() * 1000000).toString(); // 隨機生成0-999999的數字
        }

        document.addEventListener('DOMContentLoaded', () => {
            const number = getRandomNumber();
            // animateToNumber(parseInt(number, 10),IdName);
            animateToNumber(IdValue, IdName, IdColor);


        });

    }
</script>
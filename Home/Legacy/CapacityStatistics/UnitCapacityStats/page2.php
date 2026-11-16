<!DOCTYPE html>
<html lang="en">

<head>
    <!--   Core JS Files   -->
    <script src="/common/IDCC_plugin/jquery/jquery.min.js"></script>
    <link href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/common/IDCC_plugin/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="/common/IDCC_plugin/bootstrap-5.0.0-dist/js/bootstrap.min.js"></script>
    <script src="/common/IDCC_plugin/apexcharts/apexcharts.js"></script>

</head>

<body>
    <div class="outside" style="height: 100%;">
        <div class="row capacity_each_section_table">
            <table style="width:100%" class="capacity_each_section"> <!--StartFragment-->
                <thead> 
                    <tr height="22">
                        <td rowspan="4" class="xl73">單位</td>
                        <td>預計產能</td>
                        <td>積站數量</td>
                        <td>實際產能</td>
                        <td>達成率</td>
                        <td>WIP天數</td>
                        <td>狀態</td>
                    </tr>
                </thead>
                <tbody class="capacity_each_section_tbody">
                    <tr height="22">
                        <td>生管開卡</td>
                        <td>24,000</td>
                        <td>18,000</td>
                        <td>18,000</td>
                        <td>75%</td>
                        <td>-</td>
                        <td>正常</td>
                    </tr>
                    <tr height="22">
                        <td>精練</td>
                        <td>24,000</td>
                        <td>36,000</td>
                        <td>25,000</td>
                        <td>104%</td>
                        <td>0.44</td>
                        <td>正常</td>
                    </tr>
                    <tr height="22">
                        <td>水洗</td>
                        <td>8,000</td>
                        <td>3,000</td>
                        <td>3,000</td>
                        <td>38%</td>
                        <td>-</td>
                        <td>正常</td>
                    </tr>
                    <tr height="23">
                        <td>漂染</td>
                        <td>8,000</td>
                        <td>2,000</td>
                        <td>2,000</td>
                        <td>25%</td>
                        <td>-</td>
                        <td>正常</td>
                    </tr> <!--EndFragment-->
                    <tr height="23">
                        <td>中檢</td>
                        <td>24,000</td>
                        <td>40,000</td>
                        <td>23,000</td>
                        <td>96%</td>
                        <td>0.74</td>
                        <td>正常</td>
                    </tr> <!--EndFragment-->
                    <tr height="23">
                        <td>定型</td>
                        <td>24,000</td>
                        <td>26,000</td>
                        <td>23,000</td>
                        <td>96%</td>
                        <td>0.13</td>
                        <td>正常</td>
                    </tr> <!--EndFragment-->
                    <tr height="23">
                        <td>起毛</td>
                        <td>24,000</td>
                        <td>36,000</td>
                        <td>20,000</td>
                        <td>83%</td>
                        <td>0.80</td>
                        <td>正常</td>
                    </tr> <!--EndFragment-->
                    <tr height="23">
                        <td>包装</td>
                        <td>5,000</td>
                        <td>40,000</td>
                        <td>5,000</td>
                        <td>100%</td>
                        <td>7.00</td>
                        <td>活載</td>
                    </tr> <!--EndFragment-->
                    <tr height="23">
                        <td>化驗室</td>
                        <td>24,000</td>
                        <td>18,000</td>
                        <td>18,000</td>
                        <td>75%</td>
                        <td>-</td>
                        <td>正常</td>
                    </tr> <!--EndFragment-->
                    <tr height="23">
                        <td>實驗室</td>
                        <td>200</td>
                        <td>231</td>
                        <td>195</td>
                        <td>98%</td>
                        <td>0.18</td>
                        <td>正常</td>
                    </tr> <!--EndFragment-->
                    <tr height="23">
                        <td>胚檢</td>
                        <td>300</td>
                        <td>432</td>
                        <td>310</td>
                        <td>103%</td>
                        <td>0.39</td>
                        <td>正常</td>
                    </tr> <!--EndFragment-->
                </tbody>
            </table>
        </div>
         
    
        <div class="row capacity_each_section_chart">
            <div class="col-12">
                <div id="chart"></div>
            </div>
            <!-- <div class="col-sm-3" style="text-align: center; margin-top:4em; color:#ffffff; font-weight:bold;">
               <h1>整廠設備效率</h1>
               <p  style="text-align: center; color:#01b7ff; font-weight:bold; font-size:60pt">76%</p>
            </div> -->
        </div>
    </div>
    

    <style>
    html,
    body {
        height: 92%;
        margin: 0;
        /* zoom:0.8; */
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


    .outside {
        margin: 10px
    }

    .capacity_each_section_table {
        height: 75%;
        margin: 10px 0;
    }
    .capacity_each_section_chart {
        height: 35%;
        margin: 10px 0;
    }

    .capacity_each_section {
        border-top: 5px solid #51b8cb;
        border-bottom: 5px solid #51b8cb;
        /* border-left: 5px solid #d5bbf5; */
        border-spacing: 0;
        width: 100%
    }

    .capacity_each_section td {
        /* border-bottom: 5px solid #d5bbf5; */
        /* border-right: 5px solid #d5bbf5; */
        font-size: 16pt;
        padding: 5px;
        text-align: center;
        color: #ffffff;
        font-weight: bold;
    }



</style>
</body>


</html>
<script>
// 數據預設 
//產能目標
var CapacityTarget ={
  '生管開卡​':24000,
  '胚檢​':24000,
  '精煉​':8000,
  '水洗​':8000,
  '漂染​':24000,
  '中檢​':24000,
  '定型​':24000,
  '起毛​':5000,
  '包裝​':24000,
  '化驗室​':200,
  '實驗室​':300
}
//產能目標實際產能
var Capacity = {
  '生管開卡​':18000,
  '胚檢​':25000,
  '精煉​':3000,
  '水洗​':2000,
  '漂染​':23000,
  '中檢​':23000,
  '定型​':20000,
  '起毛​':5000,
  '包裝​':18000,
  '化驗室​':195,
  '實驗室​':310
}

var AccumulatedSitesNo= {
  '生管開卡​':18000,
  '胚檢​':36000,
  '精煉​':3000,
  '水洗​':2000,
  '漂染​':40000,
  '中檢​':26000,
  '定型​':36000,
  '起毛​':40000,
  '包裝​':18000,
  '化驗室​':231,
  '實驗室​':432
}
</script>
<script>
//數據運算相關function
function getstate(CompletionRate){

if(CompletionRate>=100){
  return "滿載"
}else if(CompletionRate<30){
  return "過剩"
}else{
  return "正常"
}
}
function CompletionRate_Fun(real,target){
return Math.trunc(((real/target) * 100))
}
function WIP_Day_Fun(real,AccumulatedSitesNo){
var value =  (AccumulatedSitesNo/real) - 1
if(value == 0){
  return "-"
}else{
  return value.toFixed(2)
}
}
</script>
<!-- 表格自動創立 -->
<script>
function Creattabledata(){
  var mergedCapacities = [];
  for (var key in CapacityTarget) {
    if (CapacityTarget.hasOwnProperty(key)) {
      var CompletionRate = CompletionRate_Fun(Capacity[key], CapacityTarget[key])
      var WIP_Day = WIP_Day_Fun(Capacity[key], AccumulatedSitesNo[key])
      var state = getstate(WIP_Day_Fun)
      mergedCapacities.push({
        process: key,
        target: CapacityTarget[key],
        actual: Capacity[key],
        completion:  CompletionRate,  // 達成率
        WIP_Day:  WIP_Day,  // WIP天數
        state:state // 狀態
      });
    }
  }
  mergedCapacities.sort(function(a, b) {
    return b.completion - a.completion;
  });
  return mergedCapacities
}
function Creattablehtml(tabledata){
  console.log(tabledata)
  for (const data of tabledata) {
  
    
  }
}

</script>
<script>
$(document).ready(function() {
  var tabledata = Creattabledata()
  Creattablehtml(tabledata)
  draw_chart('',"#chart",['生管開卡​','胚檢​','精煉​','水洗​','漂染​','中檢​','定型​','起毛​','包裝​','化驗室​','實驗室​'],[10, 41, 35, 51, 49, 62, 69, 91, 148,15,61],['#64B5F6']);

});
</script>
<!-- 畫圖 -->
<script>

// 改Y軸單位
  var labelFormatter = function (value) {
    var val = Math.abs(value);
    if (val >= 1000) {
      val = (val / 1000).toFixed(1) + " K";
    }
    return val;
  };




function draw_chart(chart_title,chartID,chart_value_X,chart_value_Y,chart_value_color){
  var options = {
   series: [{
            name: "產能",
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

      background: 'rgba(156,191,227,0   )',
      width: '100%',
      height: '100%',
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
    annotations: {
            yaxis: [
                {
                    y: 50, // 基准线的 y 值
                    borderColor: '#FF0000', // 基准线的颜色
                    // strokeDashArray: 0, // 实线
                    // strokeWidth: 4, // 基准线的宽度
                    borderWidth: 4,
                    // width:'20px',
                    label: {
                        borderColor: '#FF0000',
                        style: {
                            color: '#fff',
                            background: '#FF0000'
                        },
                        text: '標準 : 50'
                    }
                }
            ],
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
        // rotation: 90,
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
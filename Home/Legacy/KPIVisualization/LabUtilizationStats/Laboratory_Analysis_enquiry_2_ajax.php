<script src="/common/IDCC_plugin/apexcharts/apexcharts.js"></script>
<script src="/common/IDCC_plugin/sweetalert/dist/sweetalert2.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->


<style>
  html,
  body {}

  text {
    font-size: 2vh !important;
  }

  .highcharts-xaxis-labels text {
    font-size: 1.5vh !important;
  }

  /* .highcharts-data-label text{

font-size: 20px !important;
} */

  @media screen and (max-width: 900px) {
    body {
      background-size: cover
        /* height: 6000px; */

    }

    #myGrid2 {
      /* zoom: 0.7; */

    }

    body {
      /* zoom: 0.8; */

    }
  }




  ::-webkit-scrollbar {
    width: 5px;
    height: 10px;
  }

  /* 设置滚动条的背景颜色 */
  ::-webkit-scrollbar {
    background-color: #f1f1f1;
  }

  /* 设置滚动条的边框 */
  ::-webkit-scrollbar {
    border: 1px solid #ccc;
  }

  /* 设置滚动条的圆角 */
  ::-webkit-scrollbar {
    border-radius: 5px;

  }


  /* 设置滚动条的阴影 */
  ::-webkit-scrollbar {
    box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
  }

  /* 设置滚动条滑块的背景颜色 */
  ::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.4);
  }

  /* 设置滚动条滑块的圆角 */
  ::-webkit-scrollbar-thumb {
    border-radius: 5px;
  }

  /* 设置滚动条滑块的阴影 */
  ::-webkit-scrollbar-thumb {
    box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
  }

  #big_title {
    text-align: center;
    font-size: 2vw;

    color: white;
    font-family: "Open Sans", sans-serif;
  }

  .apexcharts-legend-text {
    color: white !important;

  }

</style>



<!-- <div style="display: flex; flex-wrap: wrap; margin-top: 10vh;"> -->

<div id="big_title">
  化驗室人員打杯數統計<?php echo $_POST['startDateTime'] . "~" . $_POST['endDateTime'];//substr($_POST['month'], 0, 4);      ?>
</div>
<!-- <div style=" display: flex; justify-content: center; align-items: center;flex-wrap: wrap;"> -->
<!-- <div style="width:100%"> -->
<!-- </div> -->
<!-- <div id="container" style="height: 50vh; width: 100%"></div> -->
<!-- </div> -->
<div id='modalId'></div>


<div class="container-fluid">
  <div class="row">
    <!-- 大屏幕布局 -->
    <div class="d-flex col-lg-4 col-12 mt-5 justify-content-center" >
    <!-- <div id="chart2" style="height: 50vh; width: 50%; margin-top: 5vh; !important;margin-right: auto !important;  margin-left: auto !important;"> </div> -->
    <div id="chart2" style=" height: 50vh;"> </div>
    </div>
    <div class="col-lg-8 col-12  mt-5 px-5  ">
    <!-- <div id="myGrid2" class="ag-theme-alpine" style="height: 70vh; width: 50% ;margin-top: 5vh; !important;margin-right: auto !important;  margin-left: auto !important;"></div> -->
    <div id="myGrid2" class="ag-theme-alpine " style="height: 40vh;"></div>

    </div>
  </div>
</div>


<?php

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

// 找table標題
$data_tabletitle = array();
$data_title = array();


;
// var_dump($_POST['startDateTime']);
// var_dump($_POST['endDateTime']);
$startDateTime = $_POST['startDateTime'] . " 00:00:00:000";
$endDateTime = $_POST['endDateTime'] . " 23:59:59:000";




$work_no_Input = $_POST['work_no_Input'];
$FabricName_Input = $_POST['FabricName_Input'];

if ($work_no_Input == 'ALL' || $work_no_Input == '')
  $work_no_select = '';
else
  $work_no_select = "AND Dyelot ='" . $work_no_Input . "'";


if ($FabricName_Input == 'ALL' || $FabricName_Input == '')
  $FabricName_select = '';
else
  $FabricName_select = "AND FabricName ='" . $FabricName_Input . "'";



$data_tabletitle = [

  '打色員'
  ,
  '打杯數'
  ,
  '打色數'
];

// data_tabletitle_name要顯示的名稱
// ag grid的標頭
// $data_tabletitle_name=['Reason','Quantity_total','Quantity_count','Number_of_bolts'];
$data_tabletitle_name = [

  '打色員'
  ,
  '打杯數'
  ,
  '打色數'

];


/*SELECT 
    --[Dyelot] As 色號,
    case when [Name] <>''then [Name]
	else '打色員未輸入'
	END AS 打色員,
    COUNT(*) AS 打杯數,
    @start_time AS 起始日,
    @end_time AS 結束日
FROM [AllSeven_IDCC].[dbo].[LabProduction]
WHERE EndTime >= @start_time AND EndTime <= @end_time
GROUP BY [Name]*/

$sql = "SELECT 
--[Dyelot] As 色號,
case when [Name] <>''then [Name]
	else '打色員未輸入'
	END AS 打色員,
  COUNT(DISTINCT StartTime) AS 打杯數,
  COUNT(DISTINCT [Dyelot]) AS 打色數

FROM [AllSeven_IDCC].[dbo].[LabProduction]
WHERE EndTime >= '$startDateTime' AND EndTime <= '$endDateTime'
and [Name] <> 'test'
GROUP BY case when [Name] <>''then [Name]
	else '打色員未輸入'
	END
order by COUNT(DISTINCT StartTime) desc
  
";

$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
  die (print_r(sqlsrv_errors(), true));
}

$data_table = array();
$data_table_x = array();
$data_table_value = array();

$i = 0;
$data_tabletitle_temp = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {


  for ($j = 0; $j <= count($data_tabletitle); $j++) {


    $data_table[$i][$data_tabletitle[$j]] = $row[$data_tabletitle[$j]];
    // if ($i==0) {
    //   $data_table[$i][$data_tabletitle[$j]] = ltrim($row[$data_tabletitle[$j]], '0');
    //   }
    if ($i !== 0 && isset ($row[$data_tabletitle[$j]]) && is_numeric($row[$data_tabletitle[$j]])) {
      $data_table[$i][$data_tabletitle[$j]] = round($row[$data_tabletitle[$j]], 2);
    }


    if (isset ($row[$data_tabletitle[$j]]) && is_numeric($row[$data_tabletitle[$j]])) {
      $totalData[$data_tabletitle[$j]] += (int) $row[$data_tabletitle[$j]];
      $totalData[$data_tabletitle[0]] = '總計';
      // $totalData[$data_tabletitle[1]] = '';

    }
    // var_dump($data_table[$i]["#2 tmp(0.1 degree)"]);
    // var_dump($data_tabletitle[$j]);

  }

  if ($i <= 10 && isset ($row[$data_tabletitle[0]]) && $row[$data_tabletitle[0]] !== null && isset ($row[$data_tabletitle[1]]) && $row[$data_tabletitle[1]] !== null) {
    $chart_x[] =  $row[$data_tabletitle[0]];
    $chart_y[] = (float) $row[$data_tabletitle[1]];
  }

  $i++;



}

for ($j = 0; $j <= count($data_tabletitle); $j++) {

  if (isset ($totalData[$data_tabletitle[$j]]) && is_numeric($totalData[$data_tabletitle[$j]])) {
    $totalData_avg[$data_tabletitle[$j]] = $totalData[$data_tabletitle[$j]] / $i;
    $totalData_avg[$data_tabletitle[0]] = '平均';
    $totalData_avg[$data_tabletitle[1]] = '';

  }
}


// var_dump($totalData);

// var_dump($chart_x);
// var_dump($chart_y);

// }
// echo $totalData['price'];
$jsonData_table = json_encode($data_table);
// $jsonData_table_x = json_encode($data_table_x);
// $jsonData_table_value = json_encode($data_table_value);
// $jsonData_rowdata = json_encode($rowdata);
$jsonData_totalData = json_encode($totalData);
$jsonData_totalData2 = json_encode($totalData2);

// $jsonData_table_value1 = json_encode($data_table_value1);
// $jsonData_table_value2 = json_encode($data_table_value2);
// $jsonData_table_value3 = json_encode($data_table_value3);
// $jsonData_table_value4 = json_encode($data_table_value4);
// $jsonData_table_value5 = json_encode($data_table_value5);
// $jsonData_table_value13 = json_encode($data_table_value13);

// var_dump($data_table[2]);

// var_dump($jsonData_table_value[0]);
sqlsrv_close($conn);

?>



<script type="text/javascript">

  var rowData = <?php echo $jsonData_table; ?>;
  var rowData_total = <?php echo $jsonData_totalData; ?>;
  var rowData_total2 = <?php echo $jsonData_totalData2; ?>;

  var gridOptions = {

    columnDefs: [

      <?php

      for ($i = 0; $i < count($data_tabletitle); $i++) {
        echo "{ field: '" . $data_tabletitle[$i] . "', headerName:'" . $data_tabletitle_name[$i] . "' ,flex: 1,minWidth: 190,  cellStyle: { 'text-align': 'left' },
        valueFormatter: function(params) {
          if(typeof(params.value)=='number'){
            // return params.value.toFixed(2); 
            return params.value
           }
          else{
           return params.value;
          }
         }
        },
        ";
      }
      ?>
    ],
    // '#B tmp(0.1 degree)'
    // '".$data_tabletitle[$i]."'
    rowData: rowData,
    // defaultColDef: { sortable: false, filter: true,  cellStyle: { "text-align": "right" } ,autoSizeColumns: true,},
    defaultColDef: { filter: true, cellStyle: { "text-align": "right" }, autoSizeColumns: true, sortable: true, resizable: true, },

    rowSelection: null, // allow rows to be selected
    animateRows: true, // have rows animate to new positions when sorted

    // example event handler
    // 按下表格
    /*onCellClicked: params => {
      console.log('cell was clicked', params)



      $.ajax({
        beforeSend: function () {
          $('.loading').fadeIn(30);
        },
        url: "show_step.php",
        type: "POST",
        data: {
          dyelot: params.data.Dyelot


        },
        success: function (response) {
          if ($('#SwalContainer').length === 0) {
            $('<div>').attr('id', 'SwalContainer').appendTo('body');
          }
          $("#SwalContainer").html(response);
        },
        error: function (err) {
          $("#SwalContainer").html(err);
        },
        complete: function () {
          $('.loading').fadeOut(30);


        }
      });



    }*/
  };
  // get div to host the grid
  var eGridDiv = document.getElementById("myGrid2");
  // new grid instance, passing in the hosting DIV and Grid Options
  new agGrid.Grid(eGridDiv, gridOptions);
  // 设置顶部固定行的数据
  // gridOptions.api.setPinnedBottomRowData([rowData_total, rowData_total2]);
  gridOptions.api.setPinnedBottomRowData([<?php echo json_encode($totalData); ?>]);
  // gridOptions.api.setPinnedBottomRowData([<?php echo json_encode($totalData_avg); ?>]);

  // 刷新表格
  gridOptions.api.refreshCells();

  // 刷新表格
  // gridOptions.api.refreshCells()


  function toper_mill(params) {
    return params.value !== "NULL" && params.value !== undefined ? parseFloat(params.value).toLocaleString("en-US", { style: "decimal", minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '';
  }

  function percent_symbol(params) {
    return params.value !== "NULL" && params.value !== undefined ? parseFloat(params.value).toLocaleString("en-US", { style: "decimal", minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '%' : '';
  }
  // function (params) {
  //     return params.value !== "NULL" && params.value !== undefined ? Number(params.value).toLocaleString() : '';
  //   }


</script>


<!-- </body> -->

<!-- </html> -->



<script>
  var options2 = {
    series: <?php echo json_encode($chart_y) ?>,

    chart: {
      width: 450,
      type: 'donut',
      
    },
    plotOptions: {
      pie: {
        startAngle: -90,
        endAngle: 270,
        donut: {
          // size: '45%',
          labels: {
            show: true,
            total: {
          show: true,
          showAlways: false,
          color: '#fff',
         
        },
        value: {
          show: true,
          fontSize: '16px',
          fontFamily: 'Helvetica, Arial, sans-serif',
          fontWeight: 400,
          color: '#fff',
          offsetY: 16,
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
      // formatter(val, opts) {
      //   const name = opts.w.globals.labels[opts.seriesIndex]
      //   return [name, val.toFixed(1) + '%']


      // }
      formatter(val, opts) {
        // console.log(opts.w.globals);
        // return [opts.w.globals.labels[opts.seriesIndex],"",opts.w.globals.seriesTotals[opts.seriesIndex] + '杯']
        return [opts.w.globals.seriesTotals[opts.seriesIndex] + '杯']
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
      text: ' TOP 10 打色員打杯數',
      align: 'center',
      style: {
        fontSize: '14px',
        fontWeight: 'bold',
        fontFamily: undefined,
        color: 'white'
      },
    },
    labels: <?php echo json_encode($chart_x) ?>,

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

  var chart2 = new ApexCharts(document.querySelector("#chart2"), options2);
  chart2.render();

</script>

<script>


</script>
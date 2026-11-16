<script src="/common/IDCC_plugin/apexcharts/apexcharts.js"></script>
<link rel="stylesheet" href="/common/IDCC_plugin/sweetalert/dist/sweetalert2.min.css">
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

    #myGrid1_1 {
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
NNY/NBK/WHT 耗時與用量統計
  <?php echo $_POST['startDateTime'] . "~" . $_POST['endDateTime'];//substr($_POST['month'], 0, 4);       ?>

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
    <div class="d-flex col-lg-6 col-6 mt-5 justify-content-center">
      <div id="chart1_1" style=" height: 50vh;"> </div>
    </div>
    <div class="d-flex col-lg-6 col-6 mt-5 justify-content-center">

      <div id="chart1_2" style=" height: 50vh;"> </div>
    </div>

    <div class="col-lg-6 col-6  mt-0 px-5  ">

      <div id="myGrid1_1" class="ag-theme-alpine " style="height: 40vh;"></div>
    </div>
    <div class="col-lg-6 col-6  mt-0 px-5  ">

      <div id="myGrid1_2" class="ag-theme-alpine " style="height: 40vh;"></div>
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

$time_unit = $_POST['time_unit'];


$data_tabletitle = [

  $time_unit
  ,
  'NNY_TotalDispenseGrams'
  ,
  'NBK_TotalDispenseGrams'
  ,
  'WHT_TotalDispenseGrams'
  ,
  'NNY_AvgDispenseSeconds'
  ,
  'NBK_AvgDispenseSeconds'
  ,
  'WHT_AvgDispenseSeconds'
];
// 染料TotalDispenseGrams	染料AvgDispenseSeconds	助劑TotalDispenseGrams	助劑AvgDispenseSeconds	粉體助劑TotalDispenseGrams	粉體助劑AvgDispenseSeconds
// data_tabletitle_name要顯示的名稱
// ag grid的標頭
// $data_tabletitle_name=['Reason','Quantity_total','Quantity_count','Number_of_bolts'];
$data_tabletitle_name = [

  '時間單位('.$time_unit.')'
  ,
  'NNY公克數'
  ,
  'NBK公克數'
  ,
  'WHT公克數'
  ,
  'NNY平均耗時'
  ,
  'NBK平均耗時'
  ,
  'WHT平均耗時'
];


/*SELECT
日
,round( sum(  CASE   WHEN FabricName LIKE '%NNY%' THEN TotalWeight ELSE  null END ) /1,2) AS 'NNY_TotalDispenseGrams'
,avg(  CASE   WHEN FabricName LIKE '%NBK%' THEN ABS(DATEDIFF(minute, [StartTime], [EndTime])) ELSE null END ) AS 'NNY_AvgDispenseSeconds'

,round( sum(  CASE   WHEN FabricName LIKE '%NBK%' THEN TotalWeight ELSE  null END ) /1,2) AS 'NBK_TotalDispenseGrams'
,avg(  CASE   WHEN FabricName LIKE '%NBK%' THEN ABS(DATEDIFF(minute, [StartTime], [EndTime])) ELSE null END ) AS 'NBK_AvgDispenseSeconds'

,round( sum(  CASE   WHEN FabricName LIKE '%WHT%' THEN TotalWeight ELSE  null END ) /1,2) AS 'WHT_TotalDispenseGrams'
,avg(  CASE   WHEN FabricName LIKE '%WHT%' THEN ABS(DATEDIFF(minute, [StartTime], [EndTime])) ELSE null END ) AS 'WHT_AvgDispenseSeconds'
FROM
(
SELECT 
    FabricName,
    TotalWeight,
    StartTime
	,EndTime
	,CONVERT(DATE, [EndTime]) as '日'
	,CAST(YEAR([EndTime]) AS VARCHAR(4)) + '-' + CAST(DATEPART(ISO_WEEK, [EndTime]) AS VARCHAR(2) )+ '周' as '周'
	,CAST(YEAR([EndTime]) AS VARCHAR(4)) + '-' + CAST(DATEPART(MONTH, [EndTime]) AS VARCHAR(2) )+ '月' as '月'

  FROM [AllSeven_IDCC].[dbo].DyeProduction
WHERE EndTime >= '2024-02-01 00:00:00.000'
AND EndTime <= '2024-04-07 00:00:00.000'
)TAB
group by 日
order by 日







*/


$sql = "SELECT
$time_unit
,round( sum(  CASE   WHEN FabricName LIKE '%NNY%' THEN TotalWeight ELSE  null END ) /1,2) AS 'NNY_TotalDispenseGrams'
,avg(  CASE   WHEN FabricName LIKE '%NNY%' THEN ABS(DATEDIFF(minute, [StartTime], [EndTime])) ELSE null END ) AS 'NNY_AvgDispenseSeconds'

,round( sum(  CASE   WHEN FabricName LIKE '%NBK%' THEN TotalWeight ELSE  null END ) /1,2) AS 'NBK_TotalDispenseGrams'
,avg(  CASE   WHEN FabricName LIKE '%NBK%' THEN ABS(DATEDIFF(minute, [StartTime], [EndTime])) ELSE null END ) AS 'NBK_AvgDispenseSeconds'

,round( sum(  CASE   WHEN FabricName LIKE '%WHT%' THEN TotalWeight ELSE  null END ) /1,2) AS 'WHT_TotalDispenseGrams'
,avg(  CASE   WHEN FabricName LIKE '%WHT%' THEN ABS(DATEDIFF(minute, [StartTime], [EndTime])) ELSE null END ) AS 'WHT_AvgDispenseSeconds'
FROM
(
SELECT 
    FabricName,
    TotalWeight,
    StartTime
	,EndTime
	,CONVERT(DATE, [EndTime]) as '日'
	,CAST(YEAR([EndTime]) AS VARCHAR(4)) + '-' + CAST(DATEPART(ISO_WEEK, [EndTime]) AS VARCHAR(2) )+ '周' as '周'
	,CAST(YEAR([EndTime]) AS VARCHAR(4)) + '-' + CAST(DATEPART(MONTH, [EndTime]) AS VARCHAR(2) )+ '月' as '月'

  FROM [AllSeven_IDCC].[dbo].DyeProduction
WHERE EndTime >= '$startDateTime'
AND EndTime <= '$endDateTime'
)TAB
group by $time_unit
order by $time_unit





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

  // if ($i <= 10 && isset ($row[$data_tabletitle[0]]) && $row[$data_tabletitle[0]] !== null && isset ($row[$data_tabletitle[1]]) && $row[$data_tabletitle[1]] !== null) {

  $chart_x[] = $row[$data_tabletitle[0]];
  // $chart_y[] = (float) $row[$data_tabletitle[1]];
  $chart_y_1[] = $row[$data_tabletitle[1]] !== NULL ? (int) $row[$data_tabletitle[1]] : null;
  $chart_y_2[] = $row[$data_tabletitle[2]] !== NULL ? (int) $row[$data_tabletitle[2]] : null;
  $chart_y_3[] = $row[$data_tabletitle[3]] !== NULL ? (int) $row[$data_tabletitle[3]] : null;

  $chart_y_4[] = $row[$data_tabletitle[4]] !== NULL ? (int) $row[$data_tabletitle[4]] : null;
  $chart_y_5[] = $row[$data_tabletitle[5]] !== NULL ? (int) $row[$data_tabletitle[5]] : null;
  $chart_y_6[] = $row[$data_tabletitle[6]] !== NULL ? (int) $row[$data_tabletitle[6]] : null;

  // }

  $i++;



}

for ($j = 0; $j <= count($data_tabletitle); $j++) {

  if (isset ($totalData[$data_tabletitle[$j]]) && is_numeric($totalData[$data_tabletitle[$j]])) {
    $totalData_avg[$data_tabletitle[$j]] = (int) ($totalData[$data_tabletitle[$j]] / $i);
    $totalData_avg[$data_tabletitle[0]] = '平均';
    // $totalData_avg[$data_tabletitle[1]] = '';

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
        if($i == 0||$i == 1||$i == 2||$i == 3) {

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
  var eGridDiv = document.getElementById("myGrid1_1");
  // new grid instance, passing in the hosting DIV and Grid Options
  new agGrid.Grid(eGridDiv, gridOptions);
  // 设置顶部固定行的数据
  // gridOptions.api.setPinnedBottomRowData([rowData_total, rowData_total2]);
  // gridOptions.api.setPinnedBottomRowData([<?php echo json_encode($totalData); ?>]);
  gridOptions.api.setPinnedBottomRowData([<?php echo json_encode($totalData_avg); ?>]);

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

<script type="text/javascript">

  var rowData = <?php echo $jsonData_table; ?>;
  var rowData_total = <?php echo $jsonData_totalData; ?>;
  var rowData_total2 = <?php echo $jsonData_totalData2; ?>;

  var gridOptions = {

    columnDefs: [

      <?php

      for ($i = 0; $i < count($data_tabletitle); $i++) {
        if($i == 0||$i == 4||$i == 5||$i == 6) {
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
  var eGridDiv = document.getElementById("myGrid1_2");
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
  var options1_1 = {
    series: [
      {
        name: "NNY",
        data: <?php echo json_encode($chart_y_1); ?>
      },
      {
        name: "NBK",
        data: <?php echo json_encode($chart_y_2); ?>

      },
      {
        name: "WHT",
        data: <?php echo json_encode($chart_y_3); ?>
      },
    ],
    chart: {
      // background: 'transparent',
      // background: 'rgba(0,0,0,0.3)',
      background: 'rgba(156,191,227,0.1)',

     width: 920,
      height: 400,
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
      }
    },
    colors: ['#D7263D', '#F9C80E', '#43BCCD'],
    dataLabels: {
      enabled: true,
    },
    stroke: {
      // curve: 'smooth'
    },
    title: {
      text: '總輸送重量(g)',
      align: 'center',
      style: {
        fontSize: '14px',
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
      size: 7,
      shape: "square" // "circle" | "square" | "rect"
    },
    xaxis: {
      // categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
      categories: <?php echo json_encode($chart_x); ?>,
      title: {
        text: ''
      }
    },
    yaxis: {
      title: {
        text: ''
      },
      // min: 0,
      // max: 100
    },
    legend: {
      position: 'top',
      horizontalAlign: 'right',
      floating: true,
      offsetY: -25,
      offsetX: -5
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


  var chart1_1 = new ApexCharts(document.querySelector("#chart1_1"), options1_1);
  chart1_1.render();

</script>



<script>
  var options1_2 = {
    series: [
      {
        name: "NNY",
        data: <?php echo json_encode($chart_y_4); ?>
      },
      {
        name: "NBK",
        data: <?php echo json_encode($chart_y_5); ?>

      },
      {
        name: "WHT",
        data: <?php echo json_encode($chart_y_6); ?>
      },
    ],
    chart: {
      // background: 'transparent',
      // background: 'rgba(0,0,0,0.3)',
      background: 'rgba(156,191,227,0.1)',

     width: 920,
      height: 400,
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
      }
    },
    colors: ['#D7263D', '#F9C80E', '#43BCCD'],
    dataLabels: {
      enabled: true,
    },
    stroke: {
      // curve: 'smooth'
    },
    title: {
      text: '耗費時間(min)',
      align: 'center',
      style: {
        fontSize: '14px',
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
      size: 7,
      shape: "square" // "circle" | "square" | "rect"
    },
    xaxis: {
      // categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
      categories: <?php echo json_encode($chart_x); ?>,
      title: {
        text: ''
      }
    },
    yaxis: {
      title: {
        text: ''
      },
      // min: 0,
      // max: 100
    },
    legend: {
      position: 'top',
      horizontalAlign: 'right',
      floating: true,
      offsetY: -25,
      offsetX: -5
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


  var chart1_2 = new ApexCharts(document.querySelector("#chart1_2"), options1_2);
  chart1_2.render();

</script>


<script>


</script>
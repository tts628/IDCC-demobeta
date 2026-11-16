<script src="/common/IDCC_plugin/apexcharts/apexcharts.js"></script>
<link rel="stylesheet" href="/common/IDCC_plugin/sweetalert/dist/sweetalert2.min.css">
<script src="/common/IDCC_plugin/sweetalert/dist/sweetalert2.min.js"></script>


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
    化驗室分析表
    <?php echo $_POST['startDateTime'] . "~" . $_POST['endDateTime'];//substr($_POST['month'], 0, 4); ?>

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
        <div class="d-flex col-lg-12 col-12 mt-5 justify-content-center">
            <div id="chart1_1" style=" height: 50vh; width:99vw"> </div>
        </div>
        <div class="d-flex col-lg-6 col-6 mt-5 justify-content-center" style="display:none;">

            <div id="chart1_2" style=" height: 50vh; display:none;"> </div>
        </div>

        <div class="col-lg-12 col-12  mt-0 px-5  ">

            <div id="myGrid1_1" class="ag-theme-alpine " style="height: 40vh;"></div>
        </div>
        <div class="col-lg-6 col-6  mt-0 px-5  " style="display:none;">

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
$data_tabletitle['machine'] = array();
$data_tabletitle['batch'] = array();
$data_title = array();


;
// var_dump($_POST['startDateTime']);
// var_dump($_POST['endDateTime']);
// $startDateTime = $_POST['startDateTime'] . " 00:00:00:000";
// $endDateTime = $_POST['endDateTime'] . " 23:59:59:000";
$startDateTime = $_POST['startDateTime'];
$endDateTime = $_POST['endDateTime'];




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



// $machine_title = [
//   'H01'
//   ,
//   'H02'
//   ,
//   'H03'
//   ,
//   'H04'
//   ,
//   'H05'
//   ,
//   'H32'

// ];

// Manufacturer
// Number_of_Colors
// Completed_Quantity
// Completion_Rate
// NG_once
// NG_twice
// NG_three_times
// NG_four_times
// NG_five_times
// Incomplete	
// pproval_rate
// Upload_yearMonth

$machine_title = $_POST['machine_checkbox'];
// var_dump($machine_title);

for ($n = 0; $n < count($machine_title); $n++) {
    $machine_select .= ",CAST(sum(  CASE   WHEN [Manufacturer] LIKE '" . $machine_title[$n] . "' THEN Quantity ELSE null END ) AS DECIMAL(18, 2) ) AS '" . $machine_title[$n] . "'";
}



// $batch_title = [
//   'C9-6'
//   ,
//   'B1-14'
//   ,
//   'B1-15'
//   ,
//   'C11'
// ];
$batch_title = $_POST['batch_checkbox'];
for ($n = 0; $n < count($batch_title); $n++) {
    $Batch_select .= ",CAST(sum(  CASE   WHEN [Manufacturer] LIKE '" . $batch_title[$n] . "' THEN ABS(DATEDIFF(minute, [StartTime], [EndTime])) ELSE null END )/14.4/count(distinct (CONVERT(DATE, [EndTime]))) AS DECIMAL(10, 2)) AS '" . $batch_title[$n] . "'";
}
$data_tabletitle['machine'] = array_merge([$time_unit], $machine_title);
$data_tabletitle['batch'] = array_merge([$time_unit], $batch_title);

$data_tabletitle_name['machine'] = array_merge(['(' . $time_unit . ')'], array_map(function ($title) {
    return $title . "";
}, $machine_title));
$data_tabletitle_name['batch'] = array_merge(['(' . $time_unit . ')'], array_map(function ($title) {
    return $title . "(%)";
}, $batch_title));
// [

//   '時間單位('.$time_unit.')'
//   ,
//   'H01(%)'
//   ,
//   'H02(%)'
//   ,
//   'H03(%)'
// ];


/*
SELECT
  
月
,CAST(sum(  CASE   WHEN [Manufacturer] LIKE '710A0092  BS-1000P 微生物植種劑' THEN Quantity ELSE null END ) AS DECIMAL(18, 2) ) AS '710A0092  BS-1000P 微生物植種劑'
,CAST(sum(  CASE   WHEN [Manufacturer] LIKE '710A0093  MD-400 微生物馴養劑' THEN Quantity ELSE null END ) AS DECIMAL(18, 2) ) AS '710A0093  MD-400 微生物馴養劑'
,CAST(sum(  CASE   WHEN [Manufacturer] LIKE 'NaOH液鹼' THEN Quantity ELSE null END ) AS DECIMAL(18, 2) ) AS 'NaOH液鹼'
,CAST(sum(  CASE   WHEN [Manufacturer] LIKE 'NH3' THEN Quantity ELSE null END ) AS DECIMAL(18, 2) ) AS 'NH3'
,CAST(sum(  CASE   WHEN [Manufacturer] LIKE 'PAC' THEN Quantity ELSE null END ) AS DECIMAL(18, 2) ) AS 'PAC'
,CAST(sum(  CASE   WHEN [Manufacturer] LIKE 'PH' THEN Quantity ELSE null END ) AS DECIMAL(18, 2) ) AS 'PH'
,CAST(sum(  CASE   WHEN [Manufacturer] LIKE 'Polymer(-)' THEN Quantity ELSE null END ) AS DECIMAL(18, 2) ) AS 'Polymer(-)'
,CAST(sum(  CASE   WHEN [Manufacturer] LIKE 'Polymer(+)' THEN Quantity ELSE null END ) AS DECIMAL(18, 2) ) AS 'Polymer(+)'
,CAST(avg(  CASE   WHEN [Manufacturer] LIKE '大綸用電費' THEN Quantity ELSE null END ) AS DECIMAL(18, 2) ) AS '大綸用電費'
,CAST(sum(  CASE   WHEN [Manufacturer] LIKE '大綸用電量' THEN Quantity ELSE null END ) AS DECIMAL(18, 2) ) AS '大綸用電量'
,CAST(sum(  CASE   WHEN [Manufacturer] LIKE '大綸自來水用量' THEN Quantity ELSE null END ) AS DECIMAL(18, 2) ) AS '大綸自來水用量'

FROM
(
SELECT 
        CAST(REPLACE([Approval_rate], '%', '') AS float)　as 'Quantity'
        ,[Manufacturer]
      ,[Number_of_Colors]
      ,[Completed_Quantity]
      ,[Completion_Rate]
      ,[NG_once]
      ,[NG_twice]
      ,[NG_three_times]
      ,[NG_four_times]
      ,[NG_five_times]
      ,[Incomplete]
      ,[Approval_rate]
      ,[UploadTime]
      ,[Upload_yearMonth]
,[Upload_yearMonth]+ '月' as '月'

FROM [Daluen_Laboratory]
WHERE [Upload_yearMonth]>='2023-07'
AND [Upload_yearMonth]<='2024-05'


)TAB
group by 月
order by 月





*/


$sql = "SELECT
  
  $time_unit
$machine_select

FROM
(
SELECT 
		CAST(REPLACE([Approval_rate], '%', '') AS float)　as 'Quantity'
		,[Manufacturer]
      ,[Number_of_Colors]
      ,[Completed_Quantity]
      ,[Completion_Rate]
      ,[NG_once]
      ,[NG_twice]
      ,[NG_three_times]
      ,[NG_four_times]
      ,[NG_five_times]
      ,[Incomplete]
      ,[Approval_rate]
      ,[UploadTime]
      ,[Upload_yearMonth]
,[Upload_yearMonth]+ '月' as '月'

FROM [Daluen_Laboratory]
WHERE [Upload_yearMonth]>='$startDateTime'
AND [Upload_yearMonth]<='$endDateTime'


)TAB
group by $time_unit
order by $time_unit




";
// var_dump($sql);
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$data_table = array();
$data_table_x = array();
$data_table_value = array();

$i = 0;
$data_tabletitle_temp = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {


    for ($j = 0; $j <= count($data_tabletitle['machine']); $j++) {


        $data_table[$i][$data_tabletitle['machine'][$j]] = $row[$data_tabletitle['machine'][$j]];
        // $data_table[$i][$data_tabletitle['batch'][$j]] = $row[$data_tabletitle['batch'][$j]];

        // if ($i !== 0 && isset ($row[$data_tabletitle['machine'][$j]]) && is_numeric($row[$data_tabletitle['machine'][$j]])) {
        //   $data_table[$i][$data_tabletitle['machine'][$j]] = round($row[$data_tabletitle['machine'][$j]], 2);
        // }


        if (isset($row[$data_tabletitle['machine'][$j]]) && is_numeric($row[$data_tabletitle['machine'][$j]])) {
            $totalData[$data_tabletitle['machine'][$j]] += (int) $row[$data_tabletitle['machine'][$j]];
            $totalData[$data_tabletitle['machine'][0]] = '總計';

            // $totalData[$data_tabletitle['batch'][$j]] += (int) $row[$data_tabletitle['batch'][$j]];
            // $totalData[$data_tabletitle['batch'][0]] = '總計';
        }


    }

    for ($j = 0; $j <= count($data_tabletitle['batch']); $j++) {


        // $data_table[$i][$data_tabletitle['machine'][$j]] = $row[$data_tabletitle['machine'][$j]];
        $data_table[$i][$data_tabletitle['batch'][$j]] = $row[$data_tabletitle['batch'][$j]];


        if (isset($row[$data_tabletitle['batch'][$j]]) && is_numeric($row[$data_tabletitle['batch'][$j]])) {
            // $totalData[$data_tabletitle['machine'][$j]] += (int) $row[$data_tabletitle['machine'][$j]];
            // $totalData[$data_tabletitle['machine'][0]] = '總計';

            $totalData[$data_tabletitle['batch'][$j]] += (int) $row[$data_tabletitle['batch'][$j]];
            $totalData[$data_tabletitle['batch'][0]] = '總計';
        }


    }

    // 兩個圖一樣時間我只設一個
    $chart_x[] = $row[$data_tabletitle['machine'][0]];
    // $chart_y[] = (float) $row[$data_tabletitle['machine'][1]];

    for ($j = 1; $j < count($data_tabletitle['machine']); $j++) {
        // $chart_y['machine'][0][] = $row[$data_tabletitle['machine'][1]] !== NULL ? (int) $row[$data_tabletitle['machine'][1]] : null;
        // $chart_y['machine'][1][] = $row[$data_tabletitle['machine'][2]] !== NULL ? (int) $row[$data_tabletitle['machine'][2]] : null;
        // $chart_y['machine'][2][] = $row[$data_tabletitle['machine'][3]] !== NULL ? (int) $row[$data_tabletitle['machine'][3]] : null;
        $chart_y['machine'][$j - 1][] = $row[$data_tabletitle['machine'][$j]] !== NULL ? (int) $row[$data_tabletitle['machine'][$j]] : null;
    }

    for ($j = 1; $j < count($data_tabletitle['batch']); $j++) {

        // $chart_y['batch'][0][] = $row[$data_tabletitle['batch'][1]] !== NULL ? (int) $row[$data_tabletitle['batch'][1]] : null;
        // $chart_y['batch'][1][] = $row[$data_tabletitle['batch'][2]] !== NULL ? (int) $row[$data_tabletitle['batch'][2]] : null;
        // $chart_y['batch'][2][] = $row[$data_tabletitle['batch'][3]] !== NULL ? (int) $row[$data_tabletitle['batch'][3]] : null;
        $chart_y['batch'][$j - 1][] = $row[$data_tabletitle['batch'][$j]] !== NULL ? (int) $row[$data_tabletitle['batch'][$j]] : null;
    }
    // }

    $i++;



}

for ($j = 0; $j <= count($data_tabletitle['machine']); $j++) {

    if (isset($totalData[$data_tabletitle['machine'][$j]]) && is_numeric($totalData[$data_tabletitle['machine'][$j]])) {
        $totalData_avg['machine'][$data_tabletitle['machine'][$j]] = (int) ($totalData[$data_tabletitle['machine'][$j]] / $i);
        $totalData_avg['machine'][$data_tabletitle['machine'][0]] = '平均';

    }
}
for ($j = 0; $j <= count($data_tabletitle['batch']); $j++) {

    if (isset($totalData[$data_tabletitle['batch'][$j]]) && is_numeric($totalData[$data_tabletitle['batch'][$j]])) {

        $totalData_avg['batch'][$data_tabletitle['batch'][$j]] = (int) ($totalData[$data_tabletitle['batch'][$j]] / $i);
        $totalData_avg['batch'][$data_tabletitle['batch'][0]] = '平均';

    }
}


$jsonData_table = json_encode($data_table);
$jsonData_totalData = json_encode($totalData);
$jsonData_totalData2 = json_encode($totalData2);



// var_dump($totalData_avg);

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

            for ($i = 0; $i < count($data_tabletitle['machine']); $i++) {
                if (1) {

                    echo "{ field: '" . $data_tabletitle['machine'][$i] . "', headerName:'" . $data_tabletitle_name['machine'][$i] . "' ,flex: 1,minWidth: 190,  cellStyle: { 'text-align': 'left' },
        valueFormatter: function(params) {
          if(typeof(params.value)=='number'){
            return params.value.toFixed(2)+''; 
            // return params.value
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

        rowData: rowData,
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
    gridOptions.api.setPinnedBottomRowData([<?php echo json_encode($totalData_avg['machine']); ?>]);

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

            for ($i = 0; $i < count($data_tabletitle['batch']); $i++) {
                if (1) {
                    echo "{ field: '" . $data_tabletitle['batch'][$i] . "', headerName:'" . $data_tabletitle_name['batch'][$i] . "' ,flex: 1,minWidth: 190,  cellStyle: { 'text-align': 'left' },
        valueFormatter: function(params) {
          if(typeof(params.value)=='number'){
            return params.value.toFixed(2)+'%'; 
            // return params.value
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
        // '".$data_tabletitle['machine'][$i]."'
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
    // gridOptions.api.setPinnedBottomRowData([<?php echo json_encode($totalData); ?>]);
    gridOptions.api.setPinnedBottomRowData([<?php echo json_encode($totalData_avg['batch']); ?>]);

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
            <?php for ($n = 0; $n < count($machine_title); $n++) { ?>
                      {
                    name: "<?php echo $machine_title[$n]; ?>",
                    data: <?php echo json_encode($chart_y['machine'][$n]); ?>
                },
                // {
                //   name: "NBK",
                //   data: <?//php echo json_encode($chart_y['batch'][$n]);     ?>
                // },
                // {
                //   name: "WHT",
                //   data: <?//php echo json_encode($chart_y['batch'][$n]);     ?>
                // },
            <?php } ?>
        ],
        chart: {
            events: {
                beforeMount: function (chartContext, config) {
                    // // ...
                    // console.log(chartContext);
                    // console.log(config);
                }
            },
            // background: 'transparent',
            // background: 'rgba(0,0,0,0.3)',
            background: 'rgba(156,191,227,0.1)',

            width: "100%",
            // width: 920,
            height: 600,
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
        colors: ['#D7263D', '#F9C80E', '#43BCCD', '#6A0572', '#FF6700', '#008F7A', '#FFB900', '#58595B'],
        dataLabels: {
            enabled: true,
        },
        stroke: {
            // curve: 'smooth'
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
            tickPlacement: 'between',
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
            showForSingleSeries: true,
            position: 'top',
            horizontalAlign: 'right',
            floating: true,
            offsetY: -25,
            offsetX: -5,
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


    var chart1_1 = new ApexCharts(document.querySelector("#chart1_1"), options1_1);
    chart1_1.render();
    // chart1_1.toggleSeries("NBK");
    // chart1_1.toggleSeries("NYYYYNY");
    // chart1_1.toggleSeries("R10"); 
</script>



<script>
    var options1_2 = {
        series: [
            <?php for ($n = 0; $n < count($batch_title); $n++) { ?>
                      {
                    name: "<?php echo $batch_title[$n]; ?>",
                    data: <?php echo json_encode($chart_y['batch'][$n]); ?>
                },
                // {
                //   name: "NBK",
                //   data: <?//php echo json_encode($chart_y['batch'][$n]);     ?>
                // },
                // {
                //   name: "WHT",
                //   data: <?//php echo json_encode($chart_y['batch'][$n]);     ?>
                // },
            <?php } ?>

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
        colors: ['#D7263D', '#F9C80E', '#43BCCD', '#6A0572', '#FF6700', '#008F7A', '#FFB900', '#58595B'],
        dataLabels: {
            enabled: true,
        },
        stroke: {
            // curve: 'smooth'
        },
        title: {
            text: '缸號稼動率(%)',
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
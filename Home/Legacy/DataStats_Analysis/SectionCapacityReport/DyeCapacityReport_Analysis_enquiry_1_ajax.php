<script src="/common/IDCC_plugin/apexcharts/apexcharts.js"></script>
<link rel="stylesheet" href="/common/IDCC_plugin/sweetalert/dist/sweetalert2.min.css">
<script src="/common/IDCC_plugin/sweetalert/dist/sweetalert2.min.js"></script>
<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");
$lang = new Language();
$lang->load("general");
?>

<style>
    html,
    body {}

    /*text {
    font-size: 1vh !important;
  }/*取消設定影響Apexchart的文字顯示 */

    .highcharts-xaxis-labels text {
        font-size: 2vh !important;
    }

    /*.apexcharts-yaxis-label,
  .apexcharts-legend-text {
    font-size: 1vh !important;
  }*/
    /* 屏幕寬度大於或等於 1200px 時應用一個字體大小為 12px 的樣式*/
    @media screen and (min-width: 1200px) {

        .apexcharts-yaxis-label,
        .apexcharts-xaxis-label,
        .apexcharts-legend-text {
            font-size: 12px !important;
            /* Adjust font size for large screens */
        }
    }

    @media screen and (max-width: 768px) {

        .apexcharts-yaxis-label,
        .apexcharts-xaxis-label,
        .apexcharts-legend-text {
            font-size: 12px !important;
            /* Adjust font size for small screens */
        }
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



    <?php echo $_POST['startDateTime'] . "~" . $_POST['endDateTime'];//substr($_POST['month'], 0, 4);               ?>
    <br>
    <span id="chart_title">
        各工段產能
    </span>
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
        <!-- <div class="d-flex col-lg-6 col-6 mt-5 justify-content-center"><div id="chart1_1" style=" height: 50vh;"> </div></div> -->
        <div class="d-flex col-lg-12 col-12 mt-5 justify-content-center">
            <div id="chart1_1" style=" width:90vw; height: 75vh; overflow: visible;"> </div>
        </div>
        <!-- 隱藏 -->
        <div class="d-flex col-lg-6 col-6 mt-5 justify-content-center" style="display: none!important;">
            <div id="chart1_2" style=" height: 60vh;"></div>
        </div>

        <!-- <div class="col-lg-6 col-6  mt-0 px-5  "><div id="myGrid1_1" class="ag-theme-alpine " style="height: 40vh;"></div></div> -->

        <div class="col-lg-12 col-12  mt-5 px-5  ">
            <div id="myGrid1_1" class="ag-theme-alpine " style="height: 50vh;"></div>
        </div>
        <!-- 隱藏 -->
        <div class="col-lg-6 col-6  mt-0 px-5  " style="display: none!important;">
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
$machine_title = $_POST['machine_checkbox'];
// $machine_title = array_filter($_POST['machine_checkbox'], fn($item) => $item !== '定型機總產量');


// var_dump($machine_title);

for ($n = 0; $n < count($machine_title); $n++) {
    // $machine_select .= ",CAST(sum(  CASE   WHEN Machine LIKE '" . $machine_title[$n] . "' THEN ABS(DATEDIFF(minute, [StartTime], [EndTime])) ELSE null END )/14.4/count(distinct (CONVERT(DATE, [EndTime]))) AS DECIMAL(10, 2)) AS '" . $machine_title[$n] . "'";
    if ($machine_title[$n] == '定型機總產量') {
        $machine_select .= "	,CAST(sum(  CASE  WHEN Machine LIKE '定型-T01' or Machine LIKE '定型-T03' or Machine LIKE '定型-T05' THEN Capacity ELSE null END ) AS DECIMAL(10, 2) ) AS '定型機總產量'";
    } else {
        $machine_select .= "	,CAST(sum(  CASE  WHEN Machine LIKE '" . $machine_title[$n] . "' THEN Capacity ELSE null END ) AS DECIMAL(10, 2) ) AS '" . $machine_title[$n] . "'";

    }

    if ($machine_title[$n] == '染房') {
        $machine_select .= ",CAST(sum(  CASE  WHEN Machine LIKE '染房' THEN Rework ELSE null END ) AS DECIMAL(10, 0) ) AS '染房重修'";
        $machine_select .= ",CAST(avg(  CASE  WHEN Machine LIKE '染房' THEN ReworkProportion ELSE null END ) AS DECIMAL(10, 2) ) AS '染房比率'";
    }
}

// var_dump($machine_select);

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
    $Batch_select .= ",CAST(sum(  CASE   WHEN Batch LIKE '" . $batch_title[$n] . "' THEN ABS(DATEDIFF(minute, [StartTime], [EndTime])) ELSE null END )/14.4/count(distinct (CONVERT(DATE, [EndTime]))) AS DECIMAL(10, 2)) AS '" . $batch_title[$n] . "'";
}
$data_tabletitle['machine'] = array_merge([$time_unit], $machine_title);
$data_tabletitle['batch'] = array_merge([$time_unit], $batch_title);

$data_tabletitle_name['machine'] = array_merge(['(' . $time_unit . ')'], array_map(function ($title) {
    return $title . "(KG)";
}, $machine_title));
$data_tabletitle_name['batch'] = array_merge(['(' . $time_unit . ')'], array_map(function ($title) {
    return $title . "(KG)";
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
日
,CAST(sum(  CASE  WHEN Machine LIKE '製程' THEN Capacity ELSE null END ) AS DECIMAL(10, 2) ) AS '製程'
,CAST(sum(  CASE  WHEN Machine LIKE '染房' THEN Capacity ELSE null END ) AS DECIMAL(10, 2) ) AS '染房'
  ,CAST(sum(  CASE  WHEN Machine LIKE '染房' THEN Rework　 ELSE null END ) AS DECIMAL(10, 0) ) AS '染房重修'
  ,CAST(avg(  CASE  WHEN Machine LIKE '染房' THEN ReworkProportion　 ELSE null END ) AS DECIMAL(10, 2) ) AS '染房比率'
  ,CAST(sum(  CASE  WHEN Machine LIKE '定型-T01' or Machine LIKE '定型-T03' or Machine LIKE '定型-T05' THEN Capacity　 ELSE null END ) AS DECIMAL(10, 2) ) AS '定型機總產量'
FROM
(
SELECT  [ID]
      ,[Day]
      ,[Machine]
      ,CAST([Capacity] AS float)　as 'Capacity'
      ,CAST([Rework] AS float)　as 'Rework'
      ,CAST(ReworkProportion AS float)　as 'ReworkProportion'
      ,[Worker]
      ,[InsertTime]
      ,[Remark]
      ,[Deleted]
    ,CONVERT(DATE, [Day]) as '日'
  ,CAST(YEAR([Day]) AS VARCHAR(4)) + '-' + CAST(DATEPART(ISO_WEEK, [Day]) AS VARCHAR(2) )+ '周' as '周'
  ,CAST(YEAR([Day]) AS VARCHAR(4)) + '-' + CAST(DATEPART(MONTH, [Day]) AS VARCHAR(2) )+ '月' as '月'
  FROM [AllSeven_IDCC].[dbo].[DyeCapacityReport]

  WHERE [Day] >= '2024-04-05 00:00:00:000'
AND [Day] <= '2024-04-15 23:59:59:000'


)TAB
group by 日
order by 日
*/

$sql = "SELECT
$time_unit
$machine_select

FROM
(
SELECT  
      ---[ID]
      ---,[Day]
      [Machine]
      ,CAST([Capacity] AS float)　as 'Capacity'
		  ,CAST([Rework] AS float)　as 'Rework'
		  ,CAST(ReworkProportion AS float)　as 'ReworkProportion'
      ---,[Worker]
      ---,[InsertTime]
      ---,[Remark]
      ---,[Deleted]
	  ,CONVERT(DATE, [Day]) as '日'
	,CAST(YEAR([Day]) AS VARCHAR(4)) + '-' + CAST(DATEPART(ISO_WEEK, [Day]) AS VARCHAR(2) )+ '周' as '周'
	,CAST(YEAR([Day]) AS VARCHAR(4)) + '-' + CAST(DATEPART(MONTH, [Day]) AS VARCHAR(2) )+ '月' as '月'
  FROM [AllSeven_IDCC].[dbo].[DyeCapacityReport]
   WHERE [Day] >= '$startDateTime'
 AND [Day] <= '$endDateTime'
 and Deleted=0

-- 05/27 修改不要出現ERP_Production資料
--  union all

-- SELECT   [製程], [重量]
--    ,0　as 'Rework'
--    ,0　as 'ReworkProportion'
--  ,CONVERT(DATE, 完成時間) as '日'
-- ,CAST(YEAR([完成時間]) AS VARCHAR(4)) + '-' + CAST(DATEPART(ISO_WEEK, [完成時間]) AS VARCHAR(2) )+ '周' as '周'
-- ,CAST(YEAR([完成時間]) AS VARCHAR(4)) + '-' + CAST(DATEPART(MONTH, [完成時間]) AS VARCHAR(2) )+ '月' as '月'
-- FROM [AllSeven_IDCC].[dbo].[ERP_Production]

-- WHERE CONVERT (date, 完成時間) BETWEEN '$startDateTime' AND '$endDateTime'




)TAB
group by $time_unit
order by $time_unit";

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
            $totalData['machine'][$data_tabletitle['machine'][$j]] += (int) $row[$data_tabletitle['machine'][$j]];
            $totalData['machine'][$data_tabletitle['machine'][0]] = '總計';



            // $totalData[$data_tabletitle['batch'][$j]] += (int) $row[$data_tabletitle['batch'][$j]];
            // $totalData[$data_tabletitle['batch'][0]] = '總計';
        }


    }

    // 沒辦法寫迴圈ㄉ
    $data_table[$i]['染房重修'] = $row['染房重修'];
    $data_table[$i]['染房比率'] = $row['染房比率'];
    $data_table[$i]['定型機總產量'] = $row['定型機總產量'];
    $totalData['machine']['染房重修'] += (int) $row['染房重修'];
    // $totalData['machine']['染房比率'] += (int) $row['染房比率'];
    // $totalData['machine']['定型機總產量'] += (int) $row['定型機總產量'];




    for ($j = 0; $j <= count($data_tabletitle['batch']); $j++) {


        // $data_table[$i][$data_tabletitle['machine'][$j]] = $row[$data_tabletitle['machine'][$j]];
        $data_table[$i][$data_tabletitle['batch'][$j]] = $row[$data_tabletitle['batch'][$j]];


        if (isset($row[$data_tabletitle['batch'][$j]]) && is_numeric($row[$data_tabletitle['batch'][$j]])) {
            // $totalData[$data_tabletitle['machine'][$j]] += (int) $row[$data_tabletitle['machine'][$j]];
            // $totalData[$data_tabletitle['machine'][0]] = '總計';

            $totalData['batch'][$data_tabletitle['batch'][$j]] += (int) $row[$data_tabletitle['batch'][$j]];
            $totalData['batch'][$data_tabletitle['batch'][0]] = '總計';
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

    if (isset($totalData['machine'][$data_tabletitle['machine'][$j]]) && is_numeric($totalData['machine'][$data_tabletitle['machine'][$j]])) {
        $totalData_avg['machine'][$data_tabletitle['machine'][$j]] = (int) ($totalData['machine'][$data_tabletitle['machine'][$j]] / $i);
        $totalData_avg['machine'][$data_tabletitle['machine'][0]] = '平均';

    }
}
$totalData_avg['machine']['染房重修'] = (int) ($totalData['machine']['染房重修'] / $i);

for ($j = 0; $j <= count($data_tabletitle['batch']); $j++) {

    if (isset($totalData['batch'][$data_tabletitle['batch'][$j]]) && is_numeric($totalData['batch'][$data_tabletitle['batch'][$j]])) {

        $totalData_avg['batch'][$data_tabletitle['batch'][$j]] = (int) ($totalData['batch'][$data_tabletitle['batch'][$j]] / $i);
        $totalData_avg['batch'][$data_tabletitle['batch'][0]] = '平均';

    }
}


$jsonData_table = json_encode($data_table);
$jsonData_totalData = json_encode($totalData);
$jsonData_totalData2 = json_encode($totalData2);



// var_dump($totalData_avg);

// var_dump($jsonData_table_value[0]);
sqlsrv_close($conn);


// var_dump(array_sum(array_filter($totalData['machine'], 'is_int')));


?>



<script type="text/javascript">

    var rowData = <?php echo $jsonData_table; ?>;
    var rowData_total = <?php echo $jsonData_totalData; ?>;
    var rowData_total2 = <?php echo $jsonData_totalData2; ?>;

    var gridOptions = {

        columnDefs: [

            <?php


            $header_name = $data_tabletitle['machine'];

            foreach ($header_name as $key => $value) {
                if (strpos($value, '日') === 0) {
                    // 如果以 '定型' 开头，则将其替换为 '定型处理'
                    $header_name[$key] = '';
                } elseif (strpos($value, '染房') === 0) {
                    $header_name[$key] = '染房 NHUỘM';

                } elseif (strpos($value, '水洗') === 0) {
                    $header_name[$key] = '水洗 MÁY';

                } elseif (strpos($value, '定型') === 0) {
                    $header_name[$key] = '定型 ĐỊNH HÌNH ';

                } elseif (strpos($value, '刷毛') === 0) {
                    $header_name[$key] = '刷毛 CÀO LÔNG';

                } elseif (strpos($value, '剪毛') === 0) {
                    $header_name[$key] = '剪毛 CẮT LÔNG ';

                } elseif (strpos($value, '製程') === 0) {
                    $header_name[$key] = '製程';

                } elseif (strpos($value, '圓網印花') === 0) {
                    $header_name[$key] = '圓網印花';

                } elseif (strpos($value, '外廠') === 0) {
                    $header_name[$key] = '外廠';
                }
            }
            for ($i = 0; $i < count($data_tabletitle['machine']); $i++) {

                // if($data_tabletitle['machine'][$i]=='定型-T01')
                // echo "{ headerName: '定型',  children: [";
                //  else
                echo "{ headerName: '" . $header_name[$i] . "',  children: [";

                // 目的:為了讓T03和T05能和T01同個header 且選到T05可以出現總定型產量 以及在染房後面出現重修和比率
            
                if ($data_tabletitle['machine'][$i] != '定型-T03' && $data_tabletitle['machine'][$i] != '定型-T05' && $data_tabletitle['machine'][$i] != '定型機總產量') {
                    echo "{ field: '" . $data_tabletitle['machine'][$i] . "', headerName:'" . $data_tabletitle_name['machine'][$i] . "' ,flex: 1,minWidth: 220,  cellStyle: { 'text-align': 'left' },
                valueFormatter: function(params) {
                  if(typeof(params.value)=='number'){
                    return params.value.toFixed(0)+''; 
                    // return params.value
                    }
                  else{
                    return params.value;
                    }
                  }
                },";
                }
                if ($data_tabletitle['machine'][$i] == '染房') {
                    echo "{ field: '染房重修', headerName:'染房重修' ,flex: 1,minWidth: 220,  cellStyle: { 'text-align': 'left' },
                valueFormatter: function(params) {
                  if(typeof(params.value)=='number'){
                    return params.value.toFixed(0)+''; 
                    // return params.value
                    }
                  else{
                    return params.value;
                    }
                  }
                },";

                    echo "{ field: '染房比率', headerName:'染房比率' ,flex: 1,minWidth: 220,  cellStyle: { 'text-align': 'left' },
          valueFormatter: function(params) {
            if(typeof(params.value)=='number'){
              return params.value.toFixed(0)+''; 
              // return params.value
              }
            else{
              return params.value;
              }
            }
          },";
                }
                // if ($data_tabletitle['machine'][$i] == '定型-T01') {
            

                $first_machine_with_prefix = null;

                foreach ($data_tabletitle['machine'] as $machine) {
                    if (strpos($machine, '定型') === 0) {
                        $first_machine_with_prefix = $machine;
                        break; // 找到符合条件的元素后立即停止循环
                    }
                }

                // echo "第一个以 '定型' 开头的元素是：" . $first_machine_with_prefix;
            

                if ($data_tabletitle['machine'][$i] == $first_machine_with_prefix) {

                    if (in_array('定型-T03', $data_tabletitle['machine']))
                        echo "{ field: '定型-T03', headerName:'定型-T03(KG)' ,flex: 1,minWidth: 220,  cellStyle: { 'text-align': 'left' },
                valueFormatter: function(params) {
                  if(typeof(params.value)=='number'){
                    return params.value.toFixed(0)+''; 
                    // return params.value
                    }
                  else{
                    return params.value;
                    }
                  }
                },";

                    if (in_array('定型-T05', $data_tabletitle['machine']))
                        echo "{ field: '定型-T05', headerName:'定型-T05(KG)' ,flex: 1,minWidth: 220,  cellStyle: { 'text-align': 'left' },
                valueFormatter: function(params) {
                  if(typeof(params.value)=='number'){
                    return params.value.toFixed(0)+''; 
                    // return params.value
                    }
                  else{
                    return params.value;
                    }
                  }
                },";

                    if (in_array('定型機總產量', $data_tabletitle['machine']))
                        echo "{ field: '定型機總產量', headerName:'定型機總產量' ,flex: 1,minWidth: 220,  cellStyle: { 'text-align': 'left' },
                valueFormatter: function(params) {
                  if(typeof(params.value)=='number'){
                    return params.value.toFixed(0)+''; 
                    // return params.value
                    }
                  else{
                    return params.value;
                    }
                  }
                },";


                }


                echo "]},";

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
    // gridOptions.api.setPinnedBottomRowData([<?php echo json_encode($totalData['machine']); ?>]);
    // gridOptions.api.setPinnedBottomRowData([<?php echo json_encode($totalData_avg['machine']); ?>]);
    gridOptions.api.setPinnedBottomRowData([<?php echo json_encode($totalData_avg['machine']); ?>, <?php echo json_encode($totalData['machine']); ?>]);

    // 刷新表格
    gridOptions.api.refreshCells();

    // 刷新表格
    // gridOptions.api.refreshCells()


    function toper_mill(params) {
        return params.value !== "NULL" && params.value !== undefined ? parseFloat(params.value).toLocaleString("en-US", { style: "decimal", minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '';
    }

    function percent_symbol(params) {
        return params.value !== "NULL" && params.value !== undefined ? parseFloat(params.value).toLocaleString("en-US", { style: "decimal", minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '' : '';
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
    // gridOptions.api.setPinnedBottomRowData([<?php echo json_encode($totalData['batch']); ?>]);
    // gridOptions.api.setPinnedBottomRowData([<?php echo json_encode($totalData_avg['batch']); ?>]);
    gridOptions.api.setPinnedBottomRowData([<?php echo json_encode($totalData_avg['batch']); ?>, <?php echo json_encode($totalData['batch']); ?>]);

    // 刷新表格
    gridOptions.api.refreshCells();

    // 刷新表格
    // gridOptions.api.refreshCells()


    function toper_mill(params) {
        return params.value !== "NULL" && params.value !== undefined ? parseFloat(params.value).toLocaleString("en-US", { style: "decimal", minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '';
    }

    function percent_symbol(params) {
        return params.value !== "NULL" && params.value !== undefined ? parseFloat(params.value).toLocaleString("en-US", { style: "decimal", minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '' : '';
    }
    // function (params) {
    //     return params.value !== "NULL" && params.value !== undefined ? Number(params.value).toLocaleString() : '';
    //   }


</script>

<!-- </body> -->

<!-- </html> -->



<script>

    var labelFormatter = function (value) {
        var val = Math.abs(value);
        if (val >= 1000) {
            val = (val / 1000).toFixed(1) + " K";
        }
        return val;
    };

    // console.log(<?php echo json_encode($chart_x); ?>);
    // console.log(<?php echo json_encode($chart_y['machine']); ?>);
    // console.log(<?php echo json_encode($machine_title); ?>);
    var options1_1 = {
        series: [
            <?php for ($n = 0; $n < count($machine_title); $n++) { ?>
                                        {
                    name: "<?php echo $machine_title[$n]; ?>",
                    data: <?php echo json_encode($chart_y['machine'][$n]); ?>
                },
                // {
                //   name: "NBK",
                //   data: <?//php echo json_encode($chart_y['batch'][$n]);       ?>
                // },
                // {
                //   name: "WHT",
                //   data: <?//php echo json_encode($chart_y['batch'][$n]);       ?>
                // },
            <?php } ?>
        ],
        chart: {

            events: {
                beforeMount: function (chartContext, config) {

                    var chart_title_text = '';

                    // var seriesVisibility = chartContext.w.config.series[seriesIndex].visible;
                    // var seriesName = chartContext.w.config.series[seriesIndex].name;
                    // console.log(chartContext.w.globals.series);
                    // console.log(chartContext.w.globals);




                    setTimeout(() => {
                        var nonEmptyIndexes = chartContext.w.globals.series.map(function (array, index) {
                            return array.length > 0 ? index : null;
                        }).filter(function (index) {
                            return index !== null;
                        });

                        // console.log('Indexes with non-empty arrays:', nonEmptyIndexes);
                        // console.log(chartContext.w.globals);
                        nonEmptyIndexes.forEach(function (index) {
                            // if (index == seriesIndex) {
                            //   return;
                            // }
                            console.log('Index:', index);
                            // console.log('Index:', chartContext.w.config.series[index].name);
                            chart_title_text += chartContext.w.config.series[index].name + ' ';

                        });
                        // console.log(chart_title_text);
                        // document.getElementById('yourDivId').innerHTML = '新的內容文字';
                        
                        // document.getElementById('chart_title').innerHTML = chart_title_text + "(總計：<?php //echo array_sum(array_filter($totalData['machine'], function ($value, $key) {
                        //     return $key !== '定型機總產量' && is_int($value);
                        // }, ARRAY_FILTER_USE_BOTH)); ?>KG)";

                        document.getElementById('chart_title').innerHTML = "<?php echo $lang->line("general.Production_Capacity_Analysis_Report_by_Process_Section"); ?>";

                    }, 300);

                },



                legendClick: function (chartContext, seriesIndex, config) {

                    var chart_title_text = '';

                    // var seriesVisibility = chartContext.w.config.series[seriesIndex].visible;
                    // var seriesName = chartContext.w.config.series[seriesIndex].name;
                    // console.log(chartContext.w.globals.series);
                    // console.log(chartContext.w.globals);




                    setTimeout(() => {
                        var nonEmptyIndexes = chartContext.w.globals.series.map(function (array, index) {
                            return array.length > 0 ? index : null;
                        }).filter(function (index) {
                            return index !== null;
                        });

                        // console.log('Indexes with non-empty arrays:', nonEmptyIndexes);
                        // console.log(chartContext.w.globals);
                        nonEmptyIndexes.forEach(function (index) {
                            // if (index == seriesIndex) {
                            //   return;
                            // }
                            console.log('Index:', index);
                            // console.log('Index:', chartContext.w.config.series[index].name);
                            chart_title_text += chartContext.w.config.series[index].name + ' ';

                        });
                        // console.log(chart_title_text);
                        // document.getElementById('yourDivId').innerHTML = '新的內容文字';
                        document.getElementById('chart_title').innerHTML = chart_title_text + "(總計：<?php echo array_sum(array_filter($totalData['machine'], function ($value, $key) {
                            return $key !== '定型機總產量' && is_int($value);
                        }, ARRAY_FILTER_USE_BOTH)); ?>KG)";
                    }, 300);





                }

            },
            // background: 'transparent',
            // background: 'rgba(0,0,0,0.3)',
            background: 'rgba(156,191,227,0.1)',
            width: '100%',
            height: '100%',
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
                show: true
            },
            zoom: {
                enabled: true, // 启用缩放功能
            }
        },
        colors: ['#FF5252', '#FFD740', '#64B5F6', '#BA68C8', '#FFA726', '#26C6DA', '#FFCA28', '#4CAF50', '#E91E63'],
        dataLabels: {
            enabled: true,
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
            size: 1,
            shape: "square",
            rotation: -30 // 设置标记的旋转角度为30度

            //offsetY: -5, // 向上移动标记，避免遮挡 Y 轴
            //offsetX: 10 // 向右移动标记，与线条分开 // "circle" | "square" | "rect"
        },
        xaxis: {
            tickPlacement: 'between',
            // categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            categories: <?php echo json_encode($chart_x); ?>,
            //tickAmount: 10,
            labels: {
                rotate: -60, // 将x轴标签旋转成45度
                rotateAlways: true, // 始终旋转标签
                style: {
                    fontSize: '16px' // 设置标签字体大小
                },
                tickAmount: 15

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
                    offsetX: -30,
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
            showForSingleSeries: true,
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
    //console.log(options1_1);


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
                //   data: <?//php echo json_encode($chart_y['batch'][$n]);       ?>
                // },
                // {
                //   name: "WHT",
                //   data: <?//php echo json_encode($chart_y['batch'][$n]);       ?>
                // },
            <?php } ?>

        ],
        chart: {
            // background: 'transparent',
            // background: 'rgba(0,0,0,0.3)',
            background: 'rgba(156,191,227,0.1)',

            width: 1350,
            height: 800,
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
            rotation: -45, // 将数据标签逆时针旋转45度
            style: {
                fontSize: '8px', // 设置字体大小为16像素
                fontWeight: 'bold',
            },
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
            // categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            categories: <?php echo json_encode($chart_x); ?>,
            title: {
                text: ''
            },
            labels: {
                style: {
                    fontSize: '4px' // 将 x 轴标签的字体大小设置为8像素
                }
            },

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


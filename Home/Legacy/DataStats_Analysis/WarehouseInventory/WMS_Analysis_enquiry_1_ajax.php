
<script src="/common/IDCC_plugin/apexcharts/apexcharts.js"></script>


<style>

  html,
  body {
    /* background-image: url(bg1.png); */
    /* background-size: cover */
      /* font-weight: bold; */
      /* height: 95vh; */
      /* zoom: 0.5;  */
      /* transform: scale(0.8); 将图像放大到原始大小的 1.5 倍  */

  }

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

    /* #myGrid,#myGrid1,#myGrid3,#myGrid4,#myGrid5,#myGrid6,#myGrid7{
        zoom: 0.65;

      } */
    #myGrid1{
      zoom: 0.7;

    }

    body{
      zoom:0.8;

    }
  }
  .ag-theme-alpine {
    --ag-foreground-color: white;
    /*字*/
    --ag-background-color: rgba(26, 46, 57, 0.5);
    --ag-header-foreground-color: white;
    /*上面的字*/
    --ag-header-background-color: rgba(14, 86, 114, 0.8);
    --ag-odd-row-background-color: rgba(14, 86, 114, 0.5);
    --ag-header-column-resize-handle-color: rgba(0, 238, 238, 0.3);

    --ag-font-size: 20px;
    --ag-font-family: monospace;
  }

  .ag-cell {
    font-size: 18px;
    /* 调整数值以增加或减小字体大小 */
  }

  .ag-header-cell-label {
    font-size: 18px;
  }
  .cell-span {
  background-color: rgba(14, 86, 114, 1) ;


  
}
  .ag-header-cell-label {
    font-size: 18px;
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
  #big_title{
    text-align: center;
    font-size: 2vw;

    color: white;
    font-family: "Open Sans", sans-serif;
  }
  .apexcharts-legend-text{
    color: white!important;

  }
</style>



<!-- <div style="display: flex; flex-wrap: wrap; margin-top: 10vh;"> -->
<!-- <div id="big_title"><?php echo $_POST['month']; ?>異常<br><br><br><?php echo $_POST['DDDD']; ?><br><br><span style='font-size: 1.5vw;'>(<?php echo $_POST['UUUU']; ?>)</span></div> -->
<div id="big_title"><?php echo substr($_POST['month'], 0, 4); ?>倉庫庫存月彙整</div>

<div id="chart1"  style="height: 50vh; width: 100%; margin-top: 5vh;"></div>
  <div id="myGrid1" class="ag-theme-alpine" style="height: 70vh; width: 100% "></div>
  <!-- <div id="container" style="height: 50vh; width: 100%"></div> -->
<!-- </div> -->
<?php

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);


// 找table標題
$data_tabletitle = array();
$data_title = array();


;


$month_seclect = "";
$DDDD_seclect = "";
$UUUU_seclect = "";

if ($_POST['month'] != "") {
    $month_seclect = "AND Upload_yearMonth='" . $_POST['month'] . "'";
}
if ($_POST['DDDD'] != "") {

    if ($_POST['DDDD'] == 'D1202+D1204')
        $DDDD_seclect = "AND [Warehouse]in('D1202','D1204')";
    else
        $DDDD_seclect = "AND [Warehouse]in('" . $_POST['DDDD'] . "')";

}
if ($_POST['UUUU'] != "") {
    if ($_POST['UUUU'] == 'ALL')
        $UUUU_seclect = "";
    else
        $UUUU_seclect = " AND [Responsible_unit] like ('%" . mb_substr($_POST['UUUU'], 0, 2) . "%')";
}
// var_dump($_POST['UUUU']);
// var_dump(mb_substr($_POST['UUUU'], 0, 2));
// $YYYYear=$_POST['year'];
$YYYY = substr($_POST['month'], 0, 4);
// var_dump($YYYY);
$data_tabletitle = ['Upload_yearMonth', 'A組', 'D組', 'E組', '台中', '五綸', '開發', '總數'];

// data_tabletitle_name要顯示的名稱
// ag grid的標頭
// $data_tabletitle_name=['Reason','Quantity_total','Quantity_count','Number_of_bolts'];
$data_tabletitle_name = ['月份', 'A組', 'D組', 'E組', '台中', '五綸', '開發', '總庫存'];

/*
  SELECT
Upload_yearMonth
,ROUND(SUM([A組]) ,2)as 'A組'
,ROUND(SUM([D組]) ,2)as 'D組'
,ROUND(SUM([E組]) ,2)as 'E組'
,ROUND(SUM([台中]),2)as '台中'
,ROUND(SUM([五綸]),2)as '五綸'
,ROUND(SUM([A組]),2)+ROUND(SUM([D組]) ,2)+ROUND(SUM([E組]) ,2)+ROUND(SUM([台中]),2)+ROUND(SUM([五綸]),2)as '總數'
FROM
(
SELECT
Upload_yearMonth
, CASE WHEN [Material_No] like '11A%' 
      OR ([Material_No] like '11B%'
      AND [Material_No] not like '11B100%')
      OR [Material_No] like '11C%'
      OR [Material_No] like '12A%'
      OR [Material_No] like '12C%'
      OR [Material_No] like '12B%'
  THEN TRY_CONVERT(FLOAT, REPLACE(KG, ',', ''))
ELSE 0
END AS 'A組' 

, CASE WHEN [Material_No] like '11D%' 
      AND [Material_No] not like '11D701%'
  THEN TRY_CONVERT(FLOAT, REPLACE(KG, ',', ''))
ELSE 0
END AS 'D組' 

, CASE WHEN [Material_No] like '11E%'
  THEN TRY_CONVERT(FLOAT, REPLACE(KG, ',', ''))
ELSE 0
END AS 'E組' 

, CASE WHEN [Material_No] like '11D701%' 
      OR [Material_No] like '11B100%'
  THEN TRY_CONVERT(FLOAT, REPLACE(KG, ',', ''))
ELSE 0
END AS '台中' 


, CASE WHEN  [Material_No] like '11W%' 
      OR [Material_No] like '12W%'
  THEN TRY_CONVERT(FLOAT, REPLACE(KG, ',', ''))
ELSE 0
END AS '五綸' 

FROM
(
SELECT
TRY_CONVERT(FLOAT, REPLACE([Quantity], ',', '')) as 'KG'
,Product_Name as'品名'
,[Warehouse] as'算D1104'
,[Part_Number] as 'Material_No'
,Upload_yearMonth
  FROM [Daluen_IDCC].[dbo].[Daluen_aimr106]
where Upload_yearMonth<>''
AND Upload_yearMonth like '2023%'
AND [Warehouse] ='D1104'
AND [Lot_No] like 'D5%'

)TAB)TAB2
GROUP BY Upload_yearMonth
ORDER BY CAST(SUBSTRING(Upload_yearMonth, LEN(Upload_yearMonth) - 1, 2) AS INT);

  */
$sql = "    SELECT
Upload_yearMonth
,ROUND(SUM([A組]) ,2)as 'A組'
,ROUND(SUM([D組]) ,2)-ROUND(SUM([開發]),2)as 'D組'
,ROUND(SUM([E組]) ,2)as 'E組'
,ROUND(SUM([台中]),2)as '台中'
,ROUND(SUM([五綸]),2)as '五綸'
,ROUND(SUM([開發]),2)as '開發'
,ROUND(SUM([A組]),2)+ROUND(SUM([D組]) ,2)+ROUND(SUM([E組]) ,2)+ROUND(SUM([台中]),2)+ROUND(SUM([五綸]),2)as '總數'
FROM
(
SELECT
Upload_yearMonth
, CASE WHEN [Material_No] like '11A%' 
      OR ([Material_No] like '11B%'
      AND [Material_No] not like '11B100%')
      OR [Material_No] like '11C%'
      OR [Material_No] like '12A%'
      OR [Material_No] like '12C%'
      OR [Material_No] like '12B%'
  THEN TRY_CONVERT(FLOAT, REPLACE(KG, ',', ''))
ELSE 0
END AS 'A組' 

, CASE WHEN [Material_No] like '11D%' 
      AND [Material_No] not like '11D701%'
  THEN TRY_CONVERT(FLOAT, REPLACE(KG, ',', ''))
ELSE 0
END AS 'D組' 

, CASE WHEN [Material_No] like '11E%'
  THEN TRY_CONVERT(FLOAT, REPLACE(KG, ',', ''))
ELSE 0
END AS 'E組' 

, CASE WHEN [Material_No] like '11D701%' 
      OR [Material_No] like '11B100%'
  THEN TRY_CONVERT(FLOAT, REPLACE(KG, ',', ''))
ELSE 0
END AS '台中' 


, CASE WHEN  [Material_No] like '11W%' 
      OR [Material_No] like '12W%'
  THEN TRY_CONVERT(FLOAT, REPLACE(KG, ',', ''))
ELSE 0
END AS '五綸' 
, CASE WHEN  Lot_No like 'D516%' 
  THEN TRY_CONVERT(FLOAT, REPLACE(KG, ',', ''))
ELSE 0
END AS '開發' 
FROM
(
SELECT
TRY_CONVERT(FLOAT, REPLACE([Quantity], ',', '')) as 'KG'
,Product_Name as'品名'
,[Warehouse] as'算D1104'
,[Part_Number] as 'Material_No'
,Upload_yearMonth
,Lot_No
  FROM [Daluen_IDCC].[dbo].[Daluen_aimr106]
where Upload_yearMonth<>''
AND Upload_yearMonth like '$YYYY%'
AND [Lot_No] like 'D5%'
$DDDD_seclect 

)TAB)TAB2
GROUP BY Upload_yearMonth
ORDER BY CAST(SUBSTRING(Upload_yearMonth, LEN(Upload_yearMonth) - 1, 2) AS INT);
";
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$data_table = array();
$data_table_x = array();
$data_table_value = array();

$i = 0;
$lastmonthplus = [];
$data_tabletitle_temp = [];

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {


    for ($j = 0; $j <= count($data_tabletitle); $j++) {
        if ($j == 0) {
            $data_table[$i][$data_tabletitle[$j]] = ($row[$data_tabletitle[$j]]);
            $data_table[$i + 1][$data_tabletitle[$j]] = '增減數';

        } else {
            $data_table[$i][$data_tabletitle[$j]] = number_format($row[$data_tabletitle[$j]], 2);
            $data_table[$i + 1][$data_tabletitle[$j]] = number_format($row[$data_tabletitle[$j]] - $lastmonthplus[$data_tabletitle[$j]], 2);

        }
        $lastmonthplus[$data_tabletitle[$j]] = ($row[$data_tabletitle[$j]]);
        // $data_table[$i][$data_tabletitle[$j]] = number_format($row[$data_tabletitle[$j]],2);
        // var_dump($data_table[$i]["#2 tmp(0.1 degree)"]);
        // var_dump($data_tabletitle[$j]);


        if (isset($row[$data_tabletitle[$j]]) && is_numeric($row[$data_tabletitle[$j]])) {
            $totalData[$data_tabletitle[$j]] += round((int) $row[$data_tabletitle[$j]], 2);
            $totalData[$data_tabletitle[0]] = '總計';

        }

        if (isset($row[$data_tabletitle[$j]]) && $row[$data_tabletitle[$j]] !== null) {
            if ($j == 0)
                $chart[$data_tabletitle[$j]][] = ($row[$data_tabletitle[$j]]);
            else
                $chart[$data_tabletitle[$j]][] = round($row[$data_tabletitle[$j]], 0);

        }

    }



    if (isset($row[$data_tabletitle[0]]) && $row[$data_tabletitle[0]] !== null && isset($row[$data_tabletitle[1]])) {
        $chart_x[] = $row[$data_tabletitle[0]];
        $chart_y[] = $row[$data_tabletitle[1]];
    }

    // $i++;
    $i += 2;



}


// for($j=0;$j<=count($data_tabletitle);$j++){

//   if (isset($totalData[$data_tabletitle[$j]]) && is_numeric($totalData[$data_tabletitle[$j]])) {
//     $totalData_avg[$data_tabletitle[$j]] = number_format($totalData[$data_tabletitle[$j]]/$i,2);
//     $totalData_avg[$data_tabletitle[0]]='平均';

// }
// }


// var_dump($i);

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

      // { field: "<?php echo $data_tabletitle[0]; ?>".replace(/^\uFEFF/, ''), flex: 1,minWidth: 280,   cellStyle: { "text-align": "left" } },
      // { field: "<?php echo $data_tabletitle[14]; ?>".replace(/^\uFEFF/, ''), flex: 3, minWidth: 190, cellStyle: { "text-align": "left" } },
      // { field: "<?php echo $data_tabletitle[1]; ?>".replace(/^\uFEFF/, ''), flex: 3, minWidth: 190, cellRenderer: toper_mill },
      // { field: "<?php echo $data_tabletitle[2]; ?>".replace(/^\uFEFF/, ''), flex: 3, minWidth: 190, cellRenderer: toper_mill },
      // { field: "<?php echo $data_tabletitle[3]; ?>".replace(/^\uFEFF/, ''), flex: 3, minWidth: 190, cellRenderer: toper_mill },
      // { field: "<?php echo $data_tabletitle[4]; ?>".replace(/^\uFEFF/, ''), flex: 3, minWidth: 190, cellRenderer: toper_mill },
      // { field: "<?php echo $data_tabletitle[5]; ?>".replace(/^\uFEFF/, ''), flex: 3, minWidth: 190, cellRenderer: toper_mill },
      // { field: "<?php echo $data_tabletitle[6]; ?>".replace(/^\uFEFF/, ''), flex: 2, minWidth: 150, cellRenderer: percent_symbol },
      // { field: "<?php echo $data_tabletitle[7]; ?>".replace(/^\uFEFF/, ''), flex: 3, minWidth: 200, cellRenderer: toper_mill },
      // { field: "<?php echo $data_tabletitle[8]; ?>".replace(/^\uFEFF/, ''), flex: 2, minWidth: 150,cellRenderer: percent_symbol },
      // { field: "<?php echo $data_tabletitle[9]; ?>".replace(/^\uFEFF/, ''), flex: 3, minWidth: 200, cellRenderer: toper_mill },
      // { field: "<?php echo $data_tabletitle[10]; ?>".replace(/^\uFEFF/, ''), flex: 2, minWidth: 150,cellRenderer: percent_symbol },
      // { field: "<?php echo $data_tabletitle[11]; ?>".replace(/^\uFEFF/, ''), flex: 3, minWidth: 200,cellRenderer: toper_mill },
      // { field: "<?php echo $data_tabletitle[12]; ?>".replace(/^\uFEFF/, ''), flex: 2, minWidth: 150,cellRenderer: percent_symbol },
      // { field: "<?php echo $data_tabletitle[13]; ?>".replace(/^\uFEFF/, ''), flex: 3, minWidth: 200, cellRenderer: toper_mill }
      // { field: "<//?php echo $datatitle[3]; ?>".replace(/^\uFEFF/, ''), flex: 1 },
      <?php

      for ($i = 0; $i < count($data_tabletitle); $i++) {
          echo "{ field: '" . $data_tabletitle[$i] . "', headerName:'" . $data_tabletitle_name[$i] . "' ,flex: 1,minWidth: 190,  cellStyle: { 'text-align': 'left' } },";
      }
      ?>
        ],
        // '#B tmp(0.1 degree)'
        // '".$data_tabletitle[$i]."'
    rowData: rowData,
    // defaultColDef: { sortable: false, filter: true,  cellStyle: { "text-align": "right" } ,autoSizeColumns: true,},
    defaultColDef: {  filter: true,  cellStyle: { "text-align": "right" },autoSizeColumns: true,sortable: true,  resizable: true,},

    rowSelection: null, // allow rows to be selected
    animateRows: true, // have rows animate to new positions when sorted

    // example event handler
    onCellClicked: params => {
      console.log('cell was clicked', params)
    }
  };
  // get div to host the grid
  var eGridDiv = document.getElementById("myGrid1");
  // new grid instance, passing in the hosting DIV and Grid Options
  new agGrid.Grid(eGridDiv, gridOptions);
  // 设置顶部固定行的数据
  // gridOptions.api.setPinnedBottomRowData([rowData_total, rowData_total2]);
  // gridOptions.api.setPinnedBottomRowData([<?php echo json_encode($totalData); ?>]);
  gridOptions.api.setPinnedBottomRowData([<?php echo json_encode($totalData); ?>]);

  // 刷新表格
  gridOptions.api.refreshCells();

  // 刷新表格
  // gridOptions.api.refreshCells()


  function toper_mill(params) {
    return params.value !== "NULL" && params.value !== undefined ? parseFloat(params.value).toLocaleString("en-US", { style: "decimal", minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '';
  }

  function percent_symbol(params) {
    return params.value !== "NULL" && params.value !== undefined ? parseFloat(params.value).toLocaleString("en-US", { style: "decimal", minimumFractionDigits: 2, maximumFractionDigits: 2 }) +'%': '';
  } 
    // function (params) {
    //     return params.value !== "NULL" && params.value !== undefined ? Number(params.value).toLocaleString() : '';
    //   }


</script>


</body>

</html>



<script>
    //  var options1 = {
    //       series: <?php echo json_encode($chart_y) ?>,
    //       chart: {
    //       width: 450,
    //       type: 'donut',
    //     },
    //     plotOptions: {
    //       pie: {
    //         startAngle: -90,
    //         endAngle: 270
    //       }
    //     },
    //     dataLabels: {
    //       enabled: true,
    //       // formatter(val, opts) {
    //       //   const name = opts.w.globals.labels[opts.seriesIndex]
    //       //   return [name, val.toFixed(1) + '%']
    //       // }
    //     },
    //     fill: {
    //       type: 'gradient',
    //     },
    //     legend: {
    //       show: false,
    //       formatter: function(val, opts) {
    //         return val + " - " + opts.w.globals.series[opts.seriesIndex]
    //       },
    //       position: 'bottom'
    //     },
    //     title: {
    //       text: ''
    //     },
    //     labels: <?php echo json_encode($chart_x) ?>,
       
    // //     theme: {
    // // monochrome: {
    // //   enabled: true,
    // //   color: '#0084ff', // 设置标签颜色
    // // }

    //     responsive: [{
    //       breakpoint: 480,
    //       options: {
    //         chart: {
    //           width: 200
    //         },
    //         legend: {
    //           position: 'bottom'
    //         }
    //       }
    //     }]
    //     };

    //     var chart1 = new ApexCharts(document.querySelector("#chart8"), options1);
    //     chart1.render();

  </script>

  <script>

var options1 = {
          series: [
            <?php
            for ($i = 1; $i < 6; $i++) {
                echo "{";
                echo "name: '" . $data_tabletitle_name[$i] . "',";
                echo " data: " . json_encode($chart[$data_tabletitle[$i]]) . "";
                echo "},";
            }

            ?>
            ],
          chart: {
          type: 'bar',
          height: 350,
          background: 'transparent'   
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          // categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
          categories: <?php echo json_encode($chart[$data_tabletitle[0]]) ?>,

        },
        yaxis: {
          title: {
            text: ''
          }
        },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return " " + val + " "
            }
          }
        },
        theme: {
      mode: 'dark', 
      palette: 'palette1',
      }
      
        };

        var chart1 = new ApexCharts(document.querySelector("#chart1"), options1);
        // chart1.render();
</script>


<script>
   var options1 = {
          series: [{
          name: 'A組',
          data: <?php echo json_encode($chart[$data_tabletitle[1]]) ?>
        }, {
          name: 'D組',
          data: <?php echo json_encode($chart[$data_tabletitle[2]]) ?>
        }, {
          name: 'E組',
          data: <?php echo json_encode($chart[$data_tabletitle[3]]) ?>
        }, {
          name: '台中',
          data: <?php echo json_encode($chart[$data_tabletitle[4]]) ?>
        }, {
          name: '五綸',
          data: <?php echo json_encode($chart[$data_tabletitle[5]]) ?>
        }],
          chart: {
            background: 'transparent',
          type: 'bar',
          height: 350,
          stacked: true,
          toolbar: {
            show: true
          },
          zoom: {
            enabled: true
          }
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
        plotOptions: {
          bar: {
            horizontal: false,
            borderRadius: 10,
            dataLabels: {
              total: {
                enabled: true,
                style: {
                  fontSize: '26px',
                  fontWeight: 900,
                  color:'#ffc000'
                }
              }
            }
          },
        },
        xaxis: {
          // type: 'datetime',
          categories: ['01/01/2011 GMT', '01/02/2011 GMT', '01/03/2011 GMT', '01/04/2011 GMT',
            '01/05/2011 GMT', '01/06/2011 GMT'
          ],
          categories: <?php echo json_encode($chart[$data_tabletitle[0]]) ?>,

        },
        legend: {
          position: 'right',
          offsetY: 40
        },
        fill: {
          opacity: 1
        },
        theme: {
      mode: 'dark', 
      palette: 'palette5',
      }
        };

        var chart1 = new ApexCharts(document.querySelector("#chart1"), options1);
        chart1.render();
  </script>
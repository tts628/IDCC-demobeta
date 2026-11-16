<script src="/common/IDCC_plugin/ag-grid/ag-grid-community.min.js"></script>
<style>
  html,
  body {
    background-image: url(/common/images/bg1.png);
    background-size: cover
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

    /* #myGrid,#myGrid2,#myGrid3,#myGrid4,#myGrid5,#myGrid6,#myGrid7{
        zoom: 0.65;

      } */
    #myGrid,
    #myGrid2,
    #myGrid3,
    #myGrid4,
    #myGrid5,
    #myGrid6,
    #myGrid7 {
      zoom: 1.5;

    }

    #container,
    #container2,
    #container3,
    #container4,
    #container5,
    #container6,
    #container7 {
      zoom: 0.8;
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
  a {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    border: 1px solid #007bff;
    transition: background-color 0.3s ease, color 0.3s ease;
  }

  a:hover {
    background-color: #fff;
    color: #007bff;
  }

  /* 设置滚动条的宽度和高度 */
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


</style>


<div style="display: flex; flex-wrap: wrap; margin-top: 10px;">
  <div id="myGrid" class="ag-theme-alpine" style="height: 40vh; width: 100% "></div>
  <div id="container2" style="height: 50vh; width: 100%"></div>
</div>
<?php

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

// 找table標題
$data_tabletitle = array();
$data_title = array();


if (isset($_POST['checkbox'])) {
  foreach ($_POST['checkbox'] as $option) {
    if($option==$_POST['checkbox'][0])
      $select="WHERE [年]='$option'";
    else
      $select.=" OR [年]='$option'";
  }
}
else{
  $select="WHERE [年]=''";
  error_reporting(0); // 设置错误报告级别为 0，即不显示任何错误
ini_set('display_errors', 0); // 禁用错误显示
}


$data_tabletitle = [
  "實際訂單開卡量分析",
  "卡數",
  "卡數比例",
  "小缸占比",
  "總數KG"
];

$sql = "   SELECT    [實際訂單開卡量分析], 
sum( TRY_CAST(REPLACE([卡數], ',', '') AS FLOAT)) AS '卡數',
ROUND(SUM(TRY_CAST(REPLACE([卡數], ',', '') AS FLOAT))*100/
 (SELECT sum( TRY_CAST(REPLACE([卡數], ',', '') AS FLOAT))
 FROM [Daluen_mds_report] $select), 2) AS'卡數比例',
 ROUND(SUM(TRY_CAST(REPLACE([卡數], ',', '') AS FLOAT))*100/
 (SELECT sum( TRY_CAST(REPLACE([卡數], ',', '') AS FLOAT))
 FROM [Daluen_mds_report] $select), 2) AS'小缸占比',
 ROUND(SUM(TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT)), 2)  AS '總數KG'

FROM (
SELECT  CASE
  WHEN TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) < 10 THEN '10KG以下'
  WHEN  10<= TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) AND TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) < 30 THEN '10~30'
  WHEN  30<= TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) AND TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) < 50 THEN '30~50'
  WHEN  50<= TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) AND TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) < 120 THEN '50~120'
  WHEN  120<= TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) AND TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) < 240 THEN '120~240'
  WHEN  240<= TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) AND TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) < 320 THEN '240~320'
  WHEN  320<= TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) AND TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) < 480 THEN '320~480'
  WHEN  480<= TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) THEN '480以上'
ELSE [生產數量]
END AS '實際訂單開卡量分析',
[卡數],
[生產數量]
FROM [Daluen_mds_report]
$select
) AS your_table

GROUP BY [實際訂單開卡量分析]

ORDER BY CASE [實際訂單開卡量分析]
WHEN '10KG以下' THEN 1
WHEN '10~30' THEN 2
WHEN '30~50' THEN 3
WHEN '50~120' THEN 4
WHEN '120~240' THEN 5
WHEN '240~320' THEN 6
WHEN '320~480' THEN 7
WHEN '480以上' THEN 8

END


";
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
  die(print_r(sqlsrv_errors(), true));
}

$data_table = array();
$data_table_x = array();
$data_table_value = array(); 
$data_piechart= array(); 
$data_table_value1[0] = 0;
function compared_to_this_year($compared_year,$this_year) {
  return $this_year - $compared_year;
}

function compared_to_this_year_percent($compared_year,$this_year) {
  if($compared_year != 0)
    return 100*($this_year - $compared_year)/$compared_year;
  else
    return null;

}
$i=0;
$data_table_x = ["50以下","50~120","120~240","240~320","320~480","480以上"];

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

  // 整個table
  $data_table[] = array(
    $data_tabletitle[0] => $row[$data_tabletitle[0]],
    $data_tabletitle[1] => $row[$data_tabletitle[1]],
    $data_tabletitle[2] => $row[$data_tabletitle[2]],
    $data_tabletitle[3] => $row[$data_tabletitle[3]],
    $data_tabletitle[4] => $row[$data_tabletitle[4]]
); // 将查询结果保存到数组中

  // 每年各資料
  // var_dump( $data_table);

  // 2019
if($i<3){

    $data_table_value1[0] +=$row[$data_tabletitle[3]];
    
    if($i==2)
    $data_piechart[] = array("name"=>$data_table_x[0], "y"=>$data_table_value1[0]); // 将查询结果保存到数组中
}


  
else
  $data_piechart[] = array("name"=>$data_table_x[$i-2], "y"=> $row[$data_tabletitle[3]]); // 将查询结果保存到数组中
  
  $i++;

}
  // X資料
// var_dump($data_table_x);

// var_dump($data_gate[0]);
// var_dump($data_piechart);
$totalData = array(
  $data_tabletitle[0] => '總計',
  $data_tabletitle[1] => 0,
  $data_tabletitle[2] => 0,
  $data_tabletitle[3] => 0,
  $data_tabletitle[4] => 0,
  

  
);

$totalData2 = array(
  $data_tabletitle[0] => '月平均',
  $data_tabletitle[1] => 0,
  $data_tabletitle[2] => 0,
  $data_tabletitle[3] => 0,
  $data_tabletitle[4] => 0,

);
foreach ($data_table as $row) {
  // $totalData['make'] += $row['make'];
  $totalData[$data_tabletitle[1]] += ($row[$data_tabletitle[1]]);
  $totalData[$data_tabletitle[2]] += ($row[$data_tabletitle[2]]);
  $totalData[$data_tabletitle[3]] += ($row[$data_tabletitle[3]]);
  $totalData[$data_tabletitle[4]] += ($row[$data_tabletitle[4]]);

  


  $totalData2[$data_tabletitle[1]] += ($row[$data_tabletitle[1]]) / 12;
  $totalData2[$data_tabletitle[2]] += ($row[$data_tabletitle[2]]) / 12;
  $totalData2[$data_tabletitle[3]] += ($row[$data_tabletitle[3]]) / 12;
  $totalData2[$data_tabletitle[4]] += ($row[$data_tabletitle[4]]) / 12;



}
// echo $totalData['price'];
$jsonData_table = json_encode($data_table);
$jsonData_table_x = json_encode($data_table_x);
$jsonData_table_value = json_encode($data_table_value);
// $jsonData_rowdata = json_encode($rowdata);
$jsonData_totalData = json_encode($totalData);
$jsonData_totalData2 = json_encode($totalData2);

$jsonData_table_value1 = json_encode($data_table_value1);

$jsonData_piechart = json_encode($data_piechart);
// $jsonData_table_value2 = json_encode($data_table_value2);
// $jsonData_table_value3 = json_encode($data_table_value3);
// $jsonData_table_value4 = json_encode($data_table_value4);
// $jsonData_table_value5 = json_encode($data_table_value5);
// $jsonData_table_value13 = json_encode($data_table_value13);

// var_dump($data_table[2]);

// var_dump($jsonData_table_value[0]);
?>



<script type="text/javascript">

  // var rowData = [
  //   { make: 'Toyota', model: 'Celica', price: 35000 },
  //             { make: 'Ford', model: 'Mondeo', price: 32000 },
  //             { make: 'Porsche', model: 'Boxster', price: 72000 },
  //             { make: 'Honda', model: 'Civic', price: 25000 },{ make: 'Toyota', model: 'Celica', price: 35000 },
  //             { make: 'Ford', model: 'Mondeo', price: 32000 },
  //             { make: 'Porsche', model: 'Boxster', price: 72000 },
  //             { make: 'Honda', model: 'Civic', price: 25000 },{ make: 'Toyota', model: 'Celica', price: 35000 },
  //             { make: 'Ford', model: 'Mondeo', price: 32000 },
  //             { make: 'Porsche', model: 'Boxster', price: 72000 },
  //             { make: 'Honda', model: 'Civic', price: 25000 },{ make: 'Toyota', model: 'Celica', price: 35000 },
  //             { make: 'Ford', model: 'Mondeo', price: 32000 },
  //             { make: 'Porsche', model: 'Boxster', price: 72000 },
  //             { make: 'Honda', model: 'Civic', price: 25000 },
  //             { model: 'Civic', price: 25000 },
  //             { make: 'BMW', model: 'X5', price: 59000 }
  //         ];

  // var rowData = [
  //             { 月份: 'Ford', o: 'Mondeo', m: 32000 },
  //             { 月份: 'Porsche', o: 'Boxster', m: 72000 },
  //             { 月份: 'Honda', o: 'Civic', m: 25000 },
  //             { 月份: 'Ford', o: 'Mondeo', m: 32000 },
  //             { 月份: 'Porsche', o: 'Boxster', m: 72000 }

  //         ];
  var rowData = <?php echo $jsonData_table; ?>;
  var rowData_total = <?php echo $jsonData_totalData; ?>;
  var rowData_total2 = <?php echo $jsonData_totalData2; ?>;
  function rowSpan(params) {
  var athlete = params.data ? params.data.athlete : undefined;
  if (params.node.rowIndex === 0 && params.data.實際訂單開卡量分析=='10KG以下') {
    // have all United States column width 4
    return 3;
  } else {
    // all other rows should be just normal
    return 1;
  }
}
// class ShowCellRenderer {
//   init(params) {
//     const cellBlank = !params.value;
//     if (cellBlank) {
//       return;
//     }
//     if (params.data.實際訂單開卡量分析=='10KG以下') {
//     this.ui = document.createElement('div');
//     this.ui.innerHTML =
//     "<br>"+
//     <?php echo $data_piechart[0]['y'];?>
//     +"%";
//     }
//     else 
//     {this.ui = document.createElement('div');
//     this.ui.innerHTML =params.value !== "NULL" && params.value !== undefined ? parseFloat(params.value).toLocaleString("en-US", { style: "decimal", minimumFractionDigits: 2, maximumFractionDigits: 2 }) +'%': '';;

//     }
//   }

//   getGui() {
//     return this.ui;
//   }

//   refresh() {
//     return false;
//   }
// }

  var gridOptions = {

    columnDefs: [

      { field: "<?php echo $data_tabletitle[0]; ?>".replace(/^\uFEFF/, ''), flex: 1, minWidth: 190,  cellStyle: { "text-align": "left" } },
      { field: "<?php echo $data_tabletitle[1]; ?>".replace(/^\uFEFF/, ''), flex: 1, minWidth: 190, cellRenderer: toper_mill },
      { field: "<?php echo $data_tabletitle[2]; ?>".replace(/^\uFEFF/, ''), flex: 1, minWidth: 190, cellRenderer: percent_symbol },
      { field: "<?php echo $data_tabletitle[3]; ?>".replace(/^\uFEFF/, ''), flex: 1, 
        rowSpan: rowSpan,
        
        cellClassRules: {
      'cell-span': params => params.data.實際訂單開卡量分析=='10KG以下',
        },
    
    minWidth: 190, cellRenderer:  combine, },
      { field: "<?php echo $data_tabletitle[4]; ?>".replace(/^\uFEFF/, ''), flex: 1, minWidth: 190, cellRenderer: toper_mill },

      // { field: "<//?php echo $datatitle[3]; ?>".replace(/^\uFEFF/, ''), flex: 1 },
    ],
    rowData: rowData,
    defaultColDef: { sortable: false, filter: true,  cellStyle: { "text-align": "right" } },
    suppressRowTransform: true,
    rowSelection: null, // allow rows to be selected
    animateRows: true, // have rows animate to new positions when sorted

    // example event handler
    onCellClicked: params => {
      console.log('cell was clicked', params)
    }
  };
  // get div to host the grid
  var eGridDiv = document.getElementById("myGrid");
  // new grid instance, passing in the hosting DIV and Grid Options
  new agGrid.Grid(eGridDiv, gridOptions);
  // 设置顶部固定行的数据
  gridOptions.api.setPinnedBottomRowData([rowData_total, rowData_total2]);
  // 刷新表格
  gridOptions.api.refreshCells();


  // 刷新表格
  // gridOptions.api.refreshCells()

  function combine (params) {
    if(params.data.實際訂單開卡量分析=='10KG以下'){
      return "<br>"+
      <?php echo $data_piechart[0]['y'];?>
      +"%";;
    }
  
    else
      return params.value !== "NULL" && params.value !== undefined ? parseFloat(params.value).toLocaleString("en-US", { style: "decimal", minimumFractionDigits: 2, maximumFractionDigits: 2 }) +'%': '';

  }

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

<!-- <script>
  var categories = <?php echo $jsonData_table_x; ?>;
  var data = [





    { name: '<?php echo $jsonData_table_value1; ?>', values: <?php echo $jsonData_table_value1; ?> },



  ];

  // 创建图表
  Highcharts.chart('container', {
    credits: {
      enabled: false
    },
    chart: {
      type: "column",
      backgroundColor: "rgba(0,0,0,0)"
    },
    
    legend: {
      itemStyle: {
        color: "#FFFFFF"
      }
    },
    title: {
      text: '品名總卡量',
      style: {
        color: "#FFFFFF"
      }
    },
    xAxis: {
      labels: {
        // rotation: -90,
        style: {
          // fontSize: '5px',
          color: "#FFFFFF"
        }
      },
      categories: categories
    },
    yAxis: {
      labels: {
        // enabled: false, // 隱藏 y 軸標題
        style: {
          color: "#FFFFFF"
        }
      },
      title: {
        text: 'KG',
        style: {
          color: "#FFFFFF"
        }
      }
    },
    series: data.map(function (item, index) {
      var seriesObj = {
    type: 'column',
    name: ["485","54"],
    data: item.values,
    dataLabels: {
                enabled: true,
                format: "{point.name} : {point.y:.0f} <b>({point.percentage:.1f}%)</b>",
                style: {
                color: "white", // 設置數據標籤顏色為
                // fontSize: '200px',
                textOutline: 'none' 
            }
          },
    color: ['#529DCB', '#ECA063', '#71BF50', '#F3CC4F', '#D46934','#E6B90D'][index]
  };

  // 添加额外的线状图系列
  if (index === data.length-1) {
    seriesObj.type = 'pie';

  }

  return seriesObj;
    })
  });
</script> -->


<script>
   Highcharts.chart("container2", {
    credits: {
            enabled: false
          },
    chart: {
      type: "pie",
      backgroundColor: "rgba(0,0,0,0)", // 设置透明背景
      options3d: {
        enabled: true,
        alpha: 45, // X轴旋转角度
        beta: 0 // Y轴旋转角度
      },
      // height: height // 设置图表高度为页面高度
    },
    title: {
      text: "卡量大小分析",
      style: {
        color: '#FFFFFF', 
        // fontSize: '1.3vw',
            }
    },
    plotOptions: {
      pie: {
        depth: 50, // 3D效果的深度
        // colors: ['#AC2527', '#E4DFD9', '#F8CC5A', '#EBAD30', '#61221A','#2B4868']// 設定區塊的顏色
      }
    },
    series: [
      
      {
        name: "Category",
        data: <?php echo $jsonData_piechart; ?>,
        dataLabels: {
                enabled: true,
                format: "{point.name} : <b>{point.percentage:.1f}%</b>",
                style: {
                color: "white", // 設置數據標籤顏色為
                // fontSize: '200px',
                textOutline: 'none' 
            }
          }

      }
    ]
  });

  // console.log(<?php echo $jsonData_piechart; ?>);
  </script>
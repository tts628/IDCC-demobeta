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
<div>
    <!-- <div><a href="../index.php" style=" position:relative; left: 95%;">首頁</a></div> -->
</div>

<div style="display: flex; flex-wrap: wrap; margin-top: 10px;">
    <div id="myGrid" class="ag-theme-alpine" style="height: 40vh; width: 100% "></div>
    <div id="container" style="height: 50vh; width: 100%"></div>
</div>
<?php

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

// 找table標題
$data_tabletitle = array();
$data_title = array();

$sql = "   SELECT DISTINCT [年]
  FROM [Daluen_mds_report]
  ORDER BY [年] DESC
";
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}


while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $data_title[] = $row['年'];
}
$data_tabletitle = [
    "品牌",
    $data_title[4],
    $data_title[3],
    $data_title[2],
    $data_title[1],
    $data_title[0],
    "相比" . substr($data_title[4], 2, 2) . "%",
    "相比" . substr($data_title[4], 2, 2) . "",
    "相比" . substr($data_title[3], 2, 2) . "%",
    "相比" . substr($data_title[3], 2, 2) . "",
    "相比" . substr($data_title[2], 2, 2) . "%",
    "相比" . substr($data_title[2], 2, 2) . "",
    "相比" . substr($data_title[1], 2, 2) . "%",
    "相比" . substr($data_title[1], 2, 2) . ""
];

$sql = "   SELECT   [品牌],
  ROUND(SUM(CASE WHEN [年] = '$data_tabletitle[1]' THEN TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) ELSE 0 END), 2) AS '$data_tabletitle[1]',
  ROUND(SUM(CASE WHEN [年] = '$data_tabletitle[2]' THEN TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) ELSE 0 END), 2) AS '$data_tabletitle[2]',
  ROUND(SUM(CASE WHEN [年] = '$data_tabletitle[3]' THEN TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) ELSE 0 END), 2) AS '$data_tabletitle[3]',
  ROUND(SUM(CASE WHEN [年] = '$data_tabletitle[4]' THEN TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) ELSE 0 END), 2) AS '$data_tabletitle[4]',
  ROUND(SUM(CASE WHEN [年] = '$data_tabletitle[5]' THEN TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) ELSE 0 END), 2) AS '$data_tabletitle[5]'
  FROM [Daluen_mds_report]
GROUP BY [品牌]
ORDER BY ROUND(SUM(CASE WHEN [年] = '$data_tabletitle[5]' THEN TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) ELSE 0 END), 2) DESC



";
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$data_table = array();
$data_table_x = array();
$data_table_value = array();

function compared_to_this_year($compared_year, $this_year)
{
    return $this_year - $compared_year;
}

function compared_to_this_year_percent($compared_year, $this_year)
{
    if ($compared_year != 0)
        return 100 * ($this_year - $compared_year) / $compared_year;
    else
        return null;

}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    //$data_piechart[] = array("name"=>$row['區域別'], "num"=>$row['total']); // 将查询结果保存到数组中
    // $data_gate[] = ($row['列標籤']);
    // $data_piechart[] = array("name"=>$row['列標籤'], "y"=>$row['加總-數量']); // 将查询结果保存到数组中
    // $rowdata[]=array("make"=>$row['列標籤'], "price"=>$row['加總-數量']);
    // 整個table
    $data_table[] = array(
        $data_tabletitle[0] => $row[$data_tabletitle[0]],
        $data_tabletitle[1] => $row[$data_tabletitle[1]],
        $data_tabletitle[2] => $row[$data_tabletitle[2]],
        $data_tabletitle[3] => $row[$data_tabletitle[3]],
        $data_tabletitle[4] => $row[$data_tabletitle[4]],
        $data_tabletitle[5] => $row[$data_tabletitle[5]],
        $data_tabletitle[6] => compared_to_this_year_percent($row[$data_tabletitle[1]], $row[$data_tabletitle[5]]),
        $data_tabletitle[7] => compared_to_this_year($row[$data_tabletitle[1]], $row[$data_tabletitle[5]]),
        $data_tabletitle[8] => compared_to_this_year_percent($row[$data_tabletitle[2]], $row[$data_tabletitle[5]]),
        $data_tabletitle[9] => compared_to_this_year($row[$data_tabletitle[2]], $row[$data_tabletitle[5]]),
        $data_tabletitle[10] => compared_to_this_year_percent($row[$data_tabletitle[3]], $row[$data_tabletitle[5]]),
        $data_tabletitle[11] => compared_to_this_year($row[$data_tabletitle[3]], $row[$data_tabletitle[5]]),
        $data_tabletitle[12] => compared_to_this_year_percent($row[$data_tabletitle[4]], $row[$data_tabletitle[5]]),
        $data_tabletitle[13] => compared_to_this_year($row[$data_tabletitle[4]], $row[$data_tabletitle[5]])
    ); // 将查询结果保存到数组中
    // X資料
    $data_table_x[] = $row[$data_tabletitle[0]];
    // 每年各資料

    // 2019
    $data_table_value1[] = $row[$data_tabletitle[1]];
    // 2020
    $data_table_value2[] = $row[$data_tabletitle[2]];
    // 2021
    $data_table_value3[] = $row[$data_tabletitle[3]];
    // 2022
    $data_table_value4[] = $row[$data_tabletitle[4]];
    // 2023
    $data_table_value5[] = $row[$data_tabletitle[5]];
    // 相比22 
    $data_table_value13[] = compared_to_this_year($row[$data_tabletitle[4]], $row[$data_tabletitle[5]]);


}
// var_dump($data_table_x);

// var_dump($data_gate[0]);
// var_dump($data_piechart);
$totalData = array(
    $data_tabletitle[0] => '總計',
    $data_tabletitle[1] => 0,
    $data_tabletitle[2] => 0,
    $data_tabletitle[3] => 0,
    $data_tabletitle[4] => 0,
    $data_tabletitle[5] => 0,
    $data_tabletitle[6] => 0,
    $data_tabletitle[7] => 0,
    $data_tabletitle[8] => 0,
    $data_tabletitle[9] => 0,
    $data_tabletitle[10] => 0,
    $data_tabletitle[11] => 0,
    $data_tabletitle[12] => 0,
    $data_tabletitle[13] => 0,

);

$totalData2 = array(
    $data_tabletitle[0] => '月平均',
    $data_tabletitle[1] => 0,
    $data_tabletitle[2] => 0,
    $data_tabletitle[3] => 0,
    $data_tabletitle[4] => 0,
    $data_tabletitle[5] => 0,
);
foreach ($data_table as $row) {
    // $totalData['make'] += $row['make'];
    $totalData[$data_tabletitle[1]] += ($row[$data_tabletitle[1]]);
    $totalData[$data_tabletitle[2]] += ($row[$data_tabletitle[2]]);
    $totalData[$data_tabletitle[3]] += ($row[$data_tabletitle[3]]);
    $totalData[$data_tabletitle[4]] += ($row[$data_tabletitle[4]]);
    $totalData[$data_tabletitle[5]] += ($row[$data_tabletitle[5]]);
    $totalData[$data_tabletitle[6]] = compared_to_this_year_percent($totalData[$data_tabletitle[1]], $totalData[$data_tabletitle[5]]);
    $totalData[$data_tabletitle[7]] += ($row[$data_tabletitle[7]]);
    $totalData[$data_tabletitle[8]] = compared_to_this_year_percent($totalData[$data_tabletitle[2]], $totalData[$data_tabletitle[5]]);
    $totalData[$data_tabletitle[9]] += ($row[$data_tabletitle[9]]);
    $totalData[$data_tabletitle[10]] = compared_to_this_year_percent($totalData[$data_tabletitle[3]], $totalData[$data_tabletitle[5]]);
    $totalData[$data_tabletitle[11]] += ($row[$data_tabletitle[11]]);
    $totalData[$data_tabletitle[12]] = compared_to_this_year_percent($totalData[$data_tabletitle[4]], $totalData[$data_tabletitle[5]]);
    $totalData[$data_tabletitle[13]] += ($row[$data_tabletitle[13]]);



    $totalData2[$data_tabletitle[1]] += ($row[$data_tabletitle[1]]) / 12;
    $totalData2[$data_tabletitle[2]] += ($row[$data_tabletitle[2]]) / 12;
    $totalData2[$data_tabletitle[3]] += ($row[$data_tabletitle[3]]) / 12;
    $totalData2[$data_tabletitle[4]] += ($row[$data_tabletitle[4]]) / 12;
    $totalData2[$data_tabletitle[5]] += ($row[$data_tabletitle[5]]) / 12;


}
// echo $totalData['price'];
$jsonData_table = json_encode($data_table);
$jsonData_table_x = json_encode($data_table_x);
$jsonData_table_value = json_encode($data_table_value);
// $jsonData_rowdata = json_encode($rowdata);
$jsonData_totalData = json_encode($totalData);
$jsonData_totalData2 = json_encode($totalData2);

$jsonData_table_value1 = json_encode($data_table_value1);
$jsonData_table_value2 = json_encode($data_table_value2);
$jsonData_table_value3 = json_encode($data_table_value3);
$jsonData_table_value4 = json_encode($data_table_value4);
$jsonData_table_value5 = json_encode($data_table_value5);
$jsonData_table_value13 = json_encode($data_table_value13);

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

    var gridOptions = {

        columnDefs: [

            { field: "<?php echo $data_tabletitle[0]; ?>".replace(/^\uFEFF/, ''), flex: 4, minWidth: 270, cellStyle: { "text-align": "left" } },
            { field: "<?php echo $data_tabletitle[1]; ?>".replace(/^\uFEFF/, ''), flex: 3, minWidth: 190, cellRenderer: toper_mill },
            { field: "<?php echo $data_tabletitle[2]; ?>".replace(/^\uFEFF/, ''), flex: 3, minWidth: 190, cellRenderer: toper_mill },
            { field: "<?php echo $data_tabletitle[3]; ?>".replace(/^\uFEFF/, ''), flex: 3, minWidth: 190, cellRenderer: toper_mill },
            { field: "<?php echo $data_tabletitle[4]; ?>".replace(/^\uFEFF/, ''), flex: 3, minWidth: 190, cellRenderer: toper_mill },
            { field: "<?php echo $data_tabletitle[5]; ?>".replace(/^\uFEFF/, ''), flex: 3, minWidth: 190, cellRenderer: toper_mill },
            { field: "<?php echo $data_tabletitle[6]; ?>".replace(/^\uFEFF/, ''), flex: 2, minWidth: 150, cellRenderer: percent_symbol },
            { field: "<?php echo $data_tabletitle[7]; ?>".replace(/^\uFEFF/, ''), flex: 3, minWidth: 200, cellRenderer: toper_mill },
            { field: "<?php echo $data_tabletitle[8]; ?>".replace(/^\uFEFF/, ''), flex: 2, minWidth: 150, cellRenderer: percent_symbol },
            { field: "<?php echo $data_tabletitle[9]; ?>".replace(/^\uFEFF/, ''), flex: 3, minWidth: 200, cellRenderer: toper_mill },
            { field: "<?php echo $data_tabletitle[10]; ?>".replace(/^\uFEFF/, ''), flex: 2, minWidth: 150, cellRenderer: percent_symbol },
            { field: "<?php echo $data_tabletitle[11]; ?>".replace(/^\uFEFF/, ''), flex: 3, minWidth: 200, cellRenderer: toper_mill },
            { field: "<?php echo $data_tabletitle[12]; ?>".replace(/^\uFEFF/, ''), flex: 2, minWidth: 150, cellRenderer: percent_symbol },
            { field: "<?php echo $data_tabletitle[13]; ?>".replace(/^\uFEFF/, ''), flex: 3, minWidth: 200, cellRenderer: toper_mill }
            // { field: "<//?php echo $datatitle[3]; ?>".replace(/^\uFEFF/, ''), flex: 1 },
        ],
        rowData: rowData,
        defaultColDef: { sortable: false, filter: true, cellStyle: { "text-align": "right" } },

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


</body>

</html>

<script>
    var categories = <?php echo $jsonData_table_x; ?>;
    var data = [





        { name: '<?php echo $data_tabletitle[1]; ?>', values: <?php echo $jsonData_table_value1; ?> },
        { name: '<?php echo $data_tabletitle[2]; ?>', values: <?php echo $jsonData_table_value2; ?> },
        { name: '<?php echo $data_tabletitle[3]; ?>', values: <?php echo $jsonData_table_value3; ?> },
        { name: '<?php echo $data_tabletitle[4]; ?>', values: <?php echo $jsonData_table_value4; ?> },
        { name: '<?php echo $data_tabletitle[5]; ?>', values: <?php echo $jsonData_table_value5; ?> },
        { name: '相比去年', values: <?php echo $jsonData_table_value13; ?> }

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
            text: '品牌總卡量',
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
                name: item.name,
                data: item.values,
                color: ['#529DCB', '#ECA063', '#71BF50', '#F3CC4F', '#D46934', '#E6B90D'][index]
            };

            // 添加额外的线状图系列
            if (index === data.length - 1) {
                seriesObj.type = 'line';

            }

            return seriesObj;
        })
    });
</script>
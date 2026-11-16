<?php
ini_set('memory_limit', '-1');
// ini_set('display_errors', '1');
// error_reporting(E_ALL);
?>
<style>
    html,
    body {
        background-image: url(bg1.png);
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
        #myGrid {
            zoom: 0.7;

        }

        body {
            zoom: 0.8;

        }
    }

    .ag-theme-alpine {
        --ag-foreground-color: white;
        /*字*/
        --ag-background-color: rgba(52, 152, 219, 0.5);
        --ag-header-foreground-color: white;
        /*上面的字*/
        --ag-header-background-color: rgba(67, 114, 195, 0.8);
        --ag-odd-row-background-color: rgba(233, 236, 245, 0.5);
        --ag-header-column-resize-handle-color: rgba(0, 238, 238, 0.3);

        --ag-font-size: 20px;
        --ag-font-family: monospace;
    }

    .ag-theme-alpine {
        --ag-foreground-color: black;
        /*字*/
        --ag-background-color: #E0E0E0;
        --ag-header-foreground-color: white;
        /*上面的字*/
        --ag-header-background-color: #6C6C6C;
        --ag-odd-row-background-color: #BEBEBE;
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

    /* a {
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
  } */

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


<div style="display: flex; flex-wrap: wrap; margin-top: 10vh;">
    <div id="myGrid" class="ag-theme-alpine" style="height: 70vh; width: 100% "></div>
    <!-- <div id="container" style="height: 50vh; width: 100%"></div> -->
</div>
<?php



require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

// 找table標題
$data_tabletitle = array();
$data_title = array();
if ($_POST['selectedOption'] == "")
    $TABLE_NAME = 'allseven_alm';
else
    $TABLE_NAME = $_POST['selectedOption']
    ;


if ($_POST['startDateTime'] != "") {
    $datetime = new DateTime($_POST['startDateTime']);
    $startDateTime = $datetime->format("Y-m-d H:i:s");
} else {
    $startDateTime = "";
}
if ($_POST['endDateTime'] != "") {
    $datetime = new DateTime($_POST['endDateTime']);
    $endDateTime = $datetime->format("Y-m-d H:i:s");
} else {
    $endDateTime = "";
}

if ($startDateTime != "" && $endDateTime != "") {
    $select = "WHERE [時間] > '" . $startDateTime . "'  AND [時間] < '" . $endDateTime . "'";
} else if ($startDateTime != "" && $endDateTime == "") {
    $select = "WHERE [時間] > '" . $startDateTime . "'  ";
} else if ($startDateTime == "" && $endDateTime != "") {
    $select = "WHERE [時間] < '" . $endDateTime . "'  ";
} else {
    $select = "";
}
// $sql = "   SELECT DISTINCT [年]
//   FROM [Daluen_IDCC].[dbo].$IDCC_table
//   ORDER BY [年] DESC
// "; 
// $stmt = sqlsrv_query($conn, $sql);
// if ($stmt === false) {
//   die(print_r(sqlsrv_errors(), true));
// }
// if (isset($_POST['checkbox'])) {
//   foreach ($_POST['checkbox'] as $option) {
//     if($option==$_POST['checkbox'][0])
//       $select="WHERE [製程階段]='$option'";
//     else
//       $select.="OR [製程階段]='$option'";
//   }

// }
// else{
//   $select="WHERE [製程階段]=''";
//   error_reporting(0); // 设置错误报告级别为 0，即不显示任何错误
//   ini_set('display_errors', 0); // 禁用错误显示
// }

// while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//   $data_title[] = $row['年'];
// }
// $data_tabletitle = [
//         "時間"
//       ,"M2張力%"
//       ,"M3張力%"
//       ,"M4張力%"
//       ,"M5張力%"
// ];

// [allseven_temp]
// [時間]
//       ,[#A tmp(0.1 degree)]
//       ,[#B tmp(0.1 degree)]
//       ,[#1 tmp(0.1 degree)]
//       ,[#2 tmp(0.1 degree)]
//       ,[#3 tmp(0.1 degree)]
//       ,[#4 tmp(0.1 degree)]


// $TABLE_NAME='allseven_temp';
$sql = "SELECT COLUMN_NAME
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = '" . $TABLE_NAME . "'
  AND TABLE_SCHEMA = 'dbo'
  AND TABLE_CATALOG = 'AllSeven_IDCC'

";
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}


$columns = array();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $columns[] = $row['COLUMN_NAME'];

}
// var_dump($columns);
$data_tabletitle = $columns;
$data_tabletitle_name = $columns;

// 設例外
if ($_POST['selectedOption'] == "allseven_temp") {
    $data_tabletitle_name = ["時間", "A", "B", "1", "2", "3", "4"];
} else {
    $valueToRemove = "UploadTime";
    // 使用 array_filter 函数，回调函数会过滤掉指定的值
    $data_tabletitle_name = array_filter($data_tabletitle_name, function ($value) use ($valueToRemove) {
        return $value !== $valueToRemove;
    });
    // 重新索引数组以填补删除的空隙
    $data_tabletitle_name = array_values($data_tabletitle_name);



    $data_tabletitle = array_filter($data_tabletitle, function ($value) use ($valueToRemove) {
        return $value !== $valueToRemove;
    });
    $data_tabletitle = array_values($data_tabletitle);
}
// var_dump($data_tabletitle_name);

$sql = " SELECT *
FROM [AllSeven_IDCC].[dbo].$TABLE_NAME
$select
order by [時間]desc
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
$i = 0;
$data_tabletitle_temp = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {


    for ($j = 0; $j <= count($data_tabletitle_name); $j++) {
        $data_table[$i][$data_tabletitle_name[$j]] = $row[$data_tabletitle[$j]];
        // var_dump($data_table[$i]["#2 tmp(0.1 degree)"]);
        // var_dump($data_tabletitle[$j]);

    }
    // var_dump($data_tabletitle);

    $i++;
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



// }
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
sqlsrv_close($conn);

?>



<script type="text/javascript">

    var rowData = <?php echo $jsonData_table; ?>;
    var rowData_total = <?php echo $jsonData_totalData; ?>;
    var rowData_total2 = <?php echo $jsonData_totalData2; ?>;

    var gridOptions = {

        columnDefs: [

            { field: "<?php echo $data_tabletitle[0]; ?>".replace(/^\uFEFF/, ''), flex: 1, minWidth: 280, cellStyle: { "text-align": "left" } },
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

            for ($i = 1; $i < count($data_tabletitle_name); $i++) {
                echo "{ field: '" . $data_tabletitle_name[$i] . "', headerName:'" . $data_tabletitle[$i] . "' ,flex: 1,minWidth: 190,  cellStyle: { 'text-align': 'left' } },";
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
        onCellClicked: params => {
            console.log('cell was clicked', params)
        }
    };
    // get div to host the grid
    var eGridDiv = document.getElementById("myGrid");
    // new grid instance, passing in the hosting DIV and Grid Options
    new agGrid.Grid(eGridDiv, gridOptions);
    // 设置顶部固定行的数据
    // gridOptions.api.setPinnedBottomRowData([rowData_total, rowData_total2]);
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
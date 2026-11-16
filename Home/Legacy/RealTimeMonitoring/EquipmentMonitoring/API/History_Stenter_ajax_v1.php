<!-- <script src="apexcharts/apexcharts.js"></script> -->
<script src="/common/IDCC_plugin/apexcharts/apexcharts.js"></script>
<div style="display: flex; flex-wrap: wrap; margin-top: 20vh;">
    <div id="chart1_1" class="ag-theme-alpine" style="height: 70vh; width: 100% "></div>
</div>
<div class="grid-container">
    <div id="myGrid1_1" class="ag-theme-alpine"></div>
</div>
<style>
    .grid-container {
        display: flex;
        flex-wrap: wrap;
        margin-top: 20vh;
        width: 100%;
        height: 65vh;
        /* 太高會覆蓋*/
    }

    .ag-theme-alpine {
        margin-top: 15vh;
        height: 100%;
        width: 100%;
    }

    @media (max-width: 768px) {
        .grid-container {
            height: 30vh;
        }
    }

    @media (max-width: 480px) {
        .grid-container {
            height: 50vh;
        }
    }
</style>


<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}


// =================query定義機台要查詢欄位，由內部映射=================
$queryConfig = [
    'T01定型機' => [
        'table' => '[FinishMachines_Stenter_Machine_T01]',
        'fields' => '[Dyelot] AS 工單, [LogTimeStamp] AS 工卡開始與結束時間, [Machine] AS 機台代號, [MachineSpeed] AS 主速, [FlowRate] AS 流量, [TempChamber01] AS 烘箱溫度1, [TempChamber02] AS 烘箱溫度2, [TempChamber03] AS 烘箱溫度3, [TempChamber04] AS 烘箱溫度4, [TempChamber05] AS 烘箱溫度5, [TempChamber06] AS 烘箱溫度6, [TempChamber07] AS 烘箱溫度7, [TempChamber08] AS 烘箱溫度8, [TempChamber09] AS 烘箱溫度9'
    ],
    'T03定型機' => [
        'table' => '[FinishMachines_Stenter_Machine_T03]',
        'fields' => '[Dyelot] AS 工單, [LogTimeStamp] AS 工卡開始與結束時間, [Machine] AS 機台代號, [MachineSpeed] AS 主速, [FlowRate] AS 流量, [TempChamber01] AS 烘箱溫度1, [TempChamber02] AS 烘箱溫度2, [TempChamber03] AS 烘箱溫度3, [TempChamber04] AS 烘箱溫度4, [TempChamber05] AS 烘箱溫度5, [TempChamber06] AS 烘箱溫度6, [TempChamber07] AS 烘箱溫度7, [TempChamber08] AS 烘箱溫度8, [TempChamber09] AS 烘箱溫度9'
    ],
    'T05定型機' => [
        'table' => '[FinishMachines_Stenter_Machine_T05]',
        'fields' => '[Dyelot] AS 工單, [LogTimeStamp] AS 工卡開始與結束時間, [Machine] AS 機台代號, [MachineSpeed] AS 主速, [FlowRate] AS 流量, [TempChamber01] AS 烘箱溫度1, [TempChamber02] AS 烘箱溫度2, [TempChamber03] AS 烘箱溫度3, [TempChamber04] AS 烘箱溫度4, [TempChamber05] AS 烘箱溫度5, [TempChamber06] AS 烘箱溫度6, [TempChamber07] AS 烘箱溫度7, [TempChamber08] AS 烘箱溫度8, [TempChamber09] AS 烘箱溫度9'
    ],

    // 添加更多的配置
];
//=================查詢函數(連結字串, 資料表名稱, 資料欄位, 開始時間, 結束時間)==================
function executeQuery($conn, $table, $fields, $start, $end)
{
    $sql = "SELECT  $fields FROM $table WHERE [LogTimeStamp] > ? AND [LogTimeStamp] < ? ORDER BY [LogTimeStamp] DESC;";
    $params = [$start, $end];
    $stmt = sqlsrv_prepare($conn, $sql, $params);

    if ($stmt) {
        if (sqlsrv_execute($stmt)) {
            $data_table = [];
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $data_table[] = $row;
            }
            return json_encode($data_table);
        } else {
            handleErrors();
        }
    } else {
        handleErrors();
    }
    return null;
}
//外部參數傳入
if ($_POST['selectedOption_History'] == "歷史報表" || $_POST['selectedOption_History'] == "1") {
    $selectedOption = $_POST['selectedOption'];
    $selectedOptionMachineNumberHistory = $_POST['selectedOption_machine_number_history'];
    $startDateTime = $_POST['startDateTime'];
    $endDateTime = $_POST['endDateTime'];
    $selectedOptionHistory = $_POST['selectedOption_History'];
    $jsonData_table = "[]"; // 預設值預防報錯
//============================布林條件selectedOption||selectedOptionMachineNumberHistory是否為對應機台; startDateTime||endDateTime不為空============================
    if ($selectedOption == '定型' || $selectedOptionMachineNumberHistory || $startDateTime != "" || $endDateTime != "") {
        $today_start = !empty($_POST['startDateTime']) ? (new DateTime($_POST['startDateTime']))->format("Y-m-d H:i:s") : "";
        $today_end = !empty($_POST['endDateTime']) ? (new DateTime($_POST['endDateTime']))->format("Y-m-d H:i:s") : "";
        //============================透過queryConfig映射對應機台資料表============================
        if (isset($queryConfig[$selectedOptionMachineNumberHistory])) {
            $config = $queryConfig[$selectedOptionMachineNumberHistory];
            $jsonData_table = executeQuery($conn, $config['table'], $config['fields'], $today_start, $today_end);
        } else {
            echo json_encode(array("error" => "Invalid machine selection."));
        }
    } else {
        echo json_encode(array("error" => "Please fill in all the required fields."));

    }
}
// 錯誤處理
function handleErrors()
{
    $errors = sqlsrv_errors();
    foreach ($errors as $error) {
        echo "SQLSTATE: " . $error['SQLSTATE'] . "<br />";
        echo "Code: " . $error['code'] . "<br />";
        echo "Message: " . $error['message'] . "<br />";
    }
}


sqlsrv_close($conn);
?>


<script type="text/javascript">
    //json數據陣列
    var rowData = <?php echo $jsonData_table ?? "''"; ?>;
    //var chartX = <?php echo $chart_x_json; ?>;
    // console.log(rowData);
    //var rowData_total = <?php echo $jsonData_totalData; ?>;
    //var rowData_total2 = <?php echo $jsonData_totalData2; ?>;

    if (rowData && rowData.length > 0) {
        // 定義動態資料欄位，依據json字串做header動態改變
        var columnDefs = Object.keys(rowData[0]).map(key => {
            return { headerName: key.charAt(0).toUpperCase() + key.slice(1), field: key, flex: 1 };
        });
        // console.log(columnDefs);  // 動態資料欄位名稱

        var gridOptions = {       // 報表主要參數設定
            columnDefs: columnDefs, // 動態資料欄位
            rowData: rowData,       // Json數據
            suppressMovableColumns: true, // 禁止列移動
            defaultColDef: {
                filter: true, // 啟用預設過濾
                sortable: true, // 啟用預設排序
                resizable: true // 啟用預設調整列寬
            }
        };
        // console.log(gridOptions); //輸出Ag grid查看配置完成 


        var gridDiv = document.getElementById('myGrid1_1'); //找到DOM元素ID
        if (gridDiv) {      //判斷元素ID是否存在
            new agGrid.Grid(gridDiv, gridOptions); // 建立ag-Grid
            console.log(gridDiv);
        } else {
            console.error("Element with id 'myGrid1_1' not found.");
        }
        initializeChart();
    } else {
        console.error("rowData is empty or not properly formatted.");
    }

    // 设置顶部固定行的数据
    // gridOptions.api.setPinnedBottomRowData([rowData_total, rowData_total2]);
    // 刷新表格
    // gridOptions.api.refreshCells();

    // 刷新表格
    // gridOptions.api.refreshCells()


    /*function toper_mill(params) {
      return params.value !== "NULL" && params.value !== undefined ? parseFloat(params.value).toLocaleString("en-US", { style: "decimal", minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '';
    }
  
    function percent_symbol(params) {
      return params.value !== "NULL" && params.value !== undefined ? parseFloat(params.value).toLocaleString("en-US", { style: "decimal", minimumFractionDigits: 2, maximumFractionDigits: 2 }) +'%': '';
    } 
      // function (params) {
      //     return params.value !== "NULL" && params.value !== undefined ? Number(params.value).toLocaleString() : '';
      //   }
  */

    function initializeChart() {
        var chartData = rowData.map(function (item) {
            return {
                x: item.日期, // X 軸的值
                y: parseFloat(item.稼動率) // Y 軸的值
            };
        });

        // 定義圖表的選項
        var options = {
            series: [{
                name: '稼動率',
                data: chartData // 使用我們轉換後的資料
            }],


            chart: {
                type: 'line',
                background: 'rgba(156,191,227,0.1)',
                width: '100%',
                height: '100%',
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
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                }
            },
            colors: ['#D7263D'],
            dataLabels: {
                enabled: true,
                //rotation: -30, // 将数据标签逆时针旋转45度
                style:
                {
                    fontSize: '8px' // 将字体大小设置为8像素
                }
            },
            xaxis: {
                tickPlacement: 'on', // 将标记放在刻度上
                //tickPlacement: 'between',
                //categories: chartX,
                categories: <?php echo json_encode($chart_x); ?>,

                //type: 'datetime',
                labels: {
                    rotate: -45, // 将x轴标签旋转成45度
                    rotateAlways: true, // 始终旋转标签
                    /*formatter: function(value, timestamp) {
                  // 将 timestamp 格式化为您期望的日期格式，例如：2024-05-15
                      return new Date(timestamp).toISOString().split('T')[0];
                    },  */
                    style: {
                        colors: '#fff',
                        fontSize: '6px' // 將 x 軸日期文字顏色設置為白色
                    },
                    tickAmount: 5

                }
            },
            yaxis: {
                labels: {
                    rotateAlways: true, // 始终旋转标签
                    style: {
                        fontSize: '6px' // 设置标签字体大小
                    },
                }
            },

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
            },
            dataLabels: {
                enabled: true,
                style:
                {
                    fontSize: '8px' // 将字体大小设置为8像素
                }
            },
            grid: {
                borderColor: '#e7e7e7',
                row: {
                    colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            markers: {
                size: 1, // 設置標記的大小為1
                shape: "square", // "circle" | "square" | "rect"
                colors: ['#000'], // 設置標記顏色為黑色
                strokeColors: '#fff', // 設置標記邊框顏色為白色
                strokeWidth: 1,
                offsetY: -5 // 调整标记位置，使其不被遮挡

            },
            colors: ['#D7263D'], // 設置線條顏色為白色
            grid: {
                padding: {
                    right: 30 // 增加右边距以确保最后一个标记不被遮挡
                },
                borderColor: '#ffffff' // 設置網格線顏色為白色
            }
        };
    }
    // 創建圖表並在指定的 div 中呈現
    //   var chart = new ApexCharts(document.querySelector("#chart1_1"), options);
    //   chart.render();



</script>
</body>

</html>
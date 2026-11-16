<script src="/common/IDCC_plugin/apexcharts/apexcharts.js"></script>

<div style="display: flex; flex-wrap: wrap; margin-top: 10vh;">
    <div id="chart1_1" style=" width:90vw; height: 75vh; overflow: visible;"> </div>
    <!-- <div id="container" style="height: 50vh; width: 100%"></div> -->
</div>


<div style="display: flex; flex-wrap: wrap; margin-top: 10vh;">
    <h3 style="color: #fff;">後整理設備稼動率-定型機 <span id="machine_number_title"></span></h3>
    <div id="myGrid1_1" class="ag-theme-alpine" style="height: 70vh; width: 90vw; margin-bottom: 20px;"></div>
    <!-- <div id="container" style="height: 50vh; width: 100%"></div> -->
</div>

<style>
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
</style>
<?php

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

// 找table標題
$data_tabletitle = array();
$data_title = array();

// 稼動率報表
// 找POST.
// var_dump($_POST);
if ($_POST['selectedOption_Report'] == "稼動率報表" || $_POST['selectedOption_Report'] == "1") {
    // 提取前三个字符
    $selectedOption_machine_number_prefix = substr($_POST['selectedOption_machine_number'], 0, 3);
    // 输出结果
    //echo $selectedOption_machine_number_prefix;
// 這裡改機台table 機台
    if ($_POST['selectedOption'] == '定型') {
        $table_name = '[FinishMachines_Stenter_Machine_' . substr($_POST['selectedOption_machine_number'], 0, 3) . ']';
    }
    // var_dump($table_name);
//if($_POST['selectedOption_machine_number']=="T01定型機")
    //$table_name='[FinishMachines_Stenter_Machine_T01]';
    //$machine_select="where [Machine]<>'' ";
// else if($_POST['selectedOption_machine_number']=="B01-2刷毛機")
// $machine_select="where [Name] in ('B01','B02') ";
//else
    // $machine_select="where [Machine]<>'' ";
    //$table_name='[FinishMachines_Stenter_Machine_T05]';





    if ($_POST['startDateTime'] != "") {
        $datetime = new DateTime($_POST['startDateTime']);
        $today_start = $datetime->format("Y-m-d H:i:s");
        //$today_start = date("Y-m-d H:i:s", strtotime("-24 hours"));
        //echo ($today_start);
    } else {
        $today_start = "";
    }
    if ($_POST['endDateTime'] != "") {
        $datetime = new DateTime($_POST['endDateTime']);
        //$datetime->add(new DateInterval('P1D')); // 增加一天
        $today_end = $datetime->format("Y-m-d H:i:s");
        //echo ($today_end);

    } else {
        $today_end = "";
    }

    /*if($today_start!="" && $today_end != ""){
      $time_select=" [LogTimeStamp] >= '".$today_start."'  AND [LogTimeStamp] <= '".$today_end."'";
    }
    else if($today_start!="" && $today_end == ""){
      $time_select=" [LogTimeStamp] >= '".$today_start."'  ";
    }
    else if($today_start=="" && $today_end != ""){
      $time_select=" [LogTimeStamp] <= '".$today_end."'  ";
    }
    else{
      $time_select="";
    }*/
    // json表頭的名稱(需要與Ag-grid相同)
    $data_tabletitle = ['日期', '實際運作時間_小時', '稼動率%'];

    // data_tabletitle_name要顯示的名稱
// ag grid的標頭
    $data_tabletitle_name = ['日期', '實際運作時間_小時', '稼動率'];


    function parseDataString($dataString)
    {
        // 將字符串按逗號分割成數組
        $dataArray = explode(',', $dataString);

        // 初始化結果數組
        $resultArray = [];

        // 遍歷每一個鍵值對，進一步處理並加入結果數組
        foreach ($dataArray as $item) {
            // 將每一個鍵值對按冒號分割成鍵和值
            list($key, $value) = explode(':', $item);

            // 移除可能存在的空格
            $key = trim($key);
            $value = trim($value);

            // 將 "False" 轉換為布爾值 false，將 "True" 轉換為布爾值 true
            $value = ($value === 'False') ? false : (($value === 'True') ? true : $value);

            // 如果值是數字字符串，轉換為數字類型
            if (is_numeric($value)) {
                $value = (float) $value;
            }

            // 添加到結果數組
            $resultArray[$key] = $value;
        }

        return $resultArray;
    }

    $every_Turn_on_date = (Date($_POST['startDateTime']));


    $sql = "SELECT 
  [Machine],
  [Dyelot],
  FORMAT(Max(LogTimeStamp), 'yyyy-MM-dd') AS 'FormattedUploadTime',
  DATEDIFF(MINUTE, MIN(LogTimeStamp), MAX(LogTimeStamp)) AS 'run_time'
FROM 
  $table_name
WHERE  
  LogTimeStamp > '$today_start' AND LogTimeStamp < '$today_end'
AND Dyelot NOT IN (
    SELECT Dyelot
    FROM $table_name
    WHERE LogTimeStamp > '$today_start' AND LogTimeStamp < '$today_end'
    GROUP BY Dyelot
    HAVING COUNT(*) % 2 = 1 OR COUNT(*) > 2
) 
GROUP BY  
  [Machine], [Dyelot]
ORDER BY FormattedUploadTime ASC;";

    // var_dump($sql);
// var_dump($endDateTime);

    $stmt = sqlsrv_query($conn, $sql);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $data_table_1 = array();
    $i = 0;
    $data_tabletitle_temp = [];
    while ($row_machine = sqlsrv_fetch_array($stmt)) {
        $name_array[] = array($row_machine['Machine']);
        $Dyelot_array[] = array($row_machine['Dyelot']);
        $UploadTime_array[] = array($row_machine['FormattedUploadTime']);
        $runtime[] = array((float) $row_machine['run_time']);
        $date = $row_machine['FormattedUploadTime'];
        $run_time = (float) $row_machine['run_time'];

        // 如果日期已經在$daily_runtimes陣列中存在，則將運行時間加總到該日期中
        if (array_key_exists($date, $daily_runtimes)) {
            $daily_runtimes[$date] += $run_time;
        } else {
            // 否則，將日期作為鍵，並將運行時間設置為值
            $daily_runtimes[$date] = $run_time;
        }
        //$activation+=(float)$row_machine['run_time']/24;
        //echo($activation);
        $machine_num++;
    }
    //var_dump($daily_runtimes);

    $activation = round($activation / $machine_num * 100, 2);

    $data_table_1[] = array(
        "Machine" => $name_array,
        "work" => $Dyelot_array,
        "UploadTime" => $UploadTime_array,
        "runtime" => $runtime
    );

    // 定義一個新的空陣列，用於存儲處理後的數據
    $data_table = [];

    foreach ($daily_runtimes as $date => $total_runtime) {
        $activation = number_format(($total_runtime / (24 * 60)) * 100, 2);
        $runtime = number_format($total_runtime / 60, 2);
        $data_table[] = [
            "日期" => $date,
            "實際運作時間_小時" => $runtime,
            "稼動率" => $activation
            // 可以添加其他需要的資料
        ];
        //$data_table[] = $data_table;

        $chart_x[] = $date; // 添加日期到 x 軸數據數組

    }

    //var_dump($data_table);

    // 使用迴圈遍歷原始資料，並將運行時間按日期加總
/*foreach ($data_table as $data) {
  $date = $data["UploadTime"];
  $runtime = (float) $data["runtime"];
  
  // 如果日期已經在$daily_runtimes陣列中存在，則將運行時間加總到該日期中
  if (array_key_exists($date, $daily_runtimes)) {
      $daily_runtimes[$date] += $runtime;
  } else {
      // 否則，將日期作為鍵，並將運行時間設置為值
      $daily_runtimes[$date] = $runtime;
  }
}
// 將$daily_runtimes轉換為所需的輸出格式
$processed_data = [];

foreach ($daily_runtimes as $date => $total_runtime) {
    $processed_data[] = [
        "Date" => $date,
        "TotalRuntime" => $total_runtime
        // 可以添加其他需要的資料
    ];
}*/

    //var_dump($processed_data);
//echo($data_table);
//echo json_encode($data_table);

    /*
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

    $Machine_Data=(str_replace("None", "\"\"", str_replace("'", '"',($row['Machine_Data']))));

    $pattern = '/datetime\.datetime\((.*?)\)/';

    // 使用 preg_replace_callback 來提取匹配的內容並替換為有效的 JSON 字串
    $json_Machine_Data = preg_replace_callback($pattern, function($matches) {
        // $matches[1] 就是匹配到的 datetime.datetime(...) 內容
        $datetimeContent = $matches[1];

        // 使用 explode 來分割日期時間的內容
        $datetimeParts = explode(', ', $datetimeContent);

        // 使用 sprintf 格式化日期時間
        $formattedDatetime = sprintf('%04d-%02d-%02d %02d:%02d:%02d', $datetimeParts[0], $datetimeParts[1], $datetimeParts[2], $datetimeParts[3], $datetimeParts[4], $datetimeParts[5]);

        return '"' . $formattedDatetime . '"';
    }, $Machine_Data);

    // var_dump($jsonString);

    $json_Machine_Data = (json_decode($json_Machine_Data, true));
    // var_dump($json_Machine_Data);

    // var_dump($json_Machine_Data['IoDataUpdated']=='2023-12-08 08:49:31');
    $IoData=(parseDataString($json_Machine_Data['IoData']));


    if($row['FormattedUploadTime']!=$every_Turn_on_date)
    {
      $every_Turn_on_date=$row['FormattedUploadTime'];
      $i++;
    // var_dump($every_Turn_on_date);

      // $Turn_on_date[$i]=$row['FormattedUploadTime'];

    }
    else{
      // $Turn_on_date[$i]=$row['FormattedUploadTime'];
    }

    $Turn_on[$i]++;

    // 這裡改要判斷稼動率的欄位

    if($row['Run_time']>5)
    {
      $Real_run[$i]++;
    }
    $Turn_on_date[$i]=$row['FormattedUploadTime'];

    // $Turn_on_date=$row['FormattedUploadTime'];




    }
    */
    // var_dump($Turn_on_date);
// var_dump($Real_run);
// var_dump($Turn_on);


    for ($j = 0; $j <= $i; $j++) {

        $data_table[$j][$data_tabletitle_name[0]] = $date[$j];
        $data_table[$j][$data_tabletitle_name[1]] = number_format((float) $total_runtime[$j], 2);
        $data_table[$j][$data_tabletitle_name[2]] = number_format((float) $activation[$j], 2);



    }
    //print_r($data_table);
// var_dump($data_table);
    if ($data_table[0][0] == null) {
        array_shift($data_table);
    }

    $jsonData_table = json_encode($data_table);
    $chart_x_json = json_encode($chart_x);

    //echo($jsonData_table);
//var_dump($jsonData_table);
    $jsonData_totalData = json_encode($totalData);
    $jsonData_totalData2 = json_encode($totalData2);


}
//稼動率報表完


//var_dump($jsonData_table);
// 產能報表完
sqlsrv_close($conn);

?>



<script type="text/javascript">

    $(document).ready(function () {
        // 从PHP获取selectedOption_machine_number
        var selectedOptionMachineNumber = "<?php echo isset($_POST['selectedOption_machine_number']) ? $_POST['selectedOption_machine_number'] : ''; ?>";
        //console.log("selectedOptionMachineNumber: ", selectedOptionMachineNumber);

        $('#machine_number_title').text(selectedOptionMachineNumber);


        //console.log(selectedOptionMachineNumber);
        var rowData = <?php echo $jsonData_table; ?>;
        var chartX = <?php echo $chart_x_json; ?>;
        //console.log(chartX);
        var rowData_total = <?php echo $jsonData_totalData; ?>;
        var rowData_total2 = <?php echo $jsonData_totalData2; ?>;

        var gridOptions = {

            columnDefs: [

                // { field: "<?php echo $data_tabletitle[0]; ?>".replace(/^\uFEFF/, ''), flex: 1,minWidth: 280,   cellStyle: { "text-align": "left" } },
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

                for ($i = 0; $i < count($data_tabletitle_name); $i++) {
                    echo "{ field: '" . $data_tabletitle_name[$i] . "', headerName:'" . $data_tabletitle[$i] . "' ,flex: 1,minWidth: 190,  cellStyle: { 'text-align': 'left' } },";
                }
                ?>
            ],
            // '#B tmp(0.1 degree)'
            // '".$data_tabletitle[$i]."'
            rowData: rowData,
            // defaultColDef: { sortable: false, filter: true,  cellStyle: { "text-align": "right" } ,autoSizeColumns: true,},
            defaultColDef: {
                filter: true,
                cellStyle: { "text-align": "right" },
                autoSizeColumns: true, sortable: true,
                resizable: true,
            },
            rowSelection: null, // allow rows to be selected
            animateRows: true, // have rows animate to new positions when sorted

            // example event handler
            onCellClicked: params => {
                console.log('cell was clicked', params)
            }
        };
        // get div to host the grid
        var eGridDiv = document.getElementById("myGrid1_1");
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
                height: '95%',
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
                    enabled: false,
                    tools: {
                        reset: true
                    } // 启用缩放功能
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
                rotation: -30, // 将数据标签逆时针旋转45度
                style: {
                    fontSize: '16px' // 将字体大小设置为18像素

                }
            },
            markers: {

                offsetY: -5, // 调整标记位置，使其不被遮挡
                offsetX: 10, // 将标记向右移动10个像素
                rotation: 30 // 将标记旋转30度
            },

            xaxis: {
                tickPlacement: 'between', // 将标记放在刻度上
                //tickPlacement: 'between',
                //categories: chartX,
                // categories: <?php echo json_encode($chart_x); ?>,
                labels: {
                    rotate: -60, // 将x轴标签旋转成45度
                    rotateAlways: true, // 始终旋转标签
                    /*formatter: function(value, timestamp) {
                  // 将 timestamp 格式化为您期望的日期格式，例如：2024-05-15
                      return new Date(timestamp).toISOString().split('T')[0];
                    },  */
                    style: {
                        colors: '#fff',
                        fontSize: '12px' // 將 x 軸日期文字顏色設置為白色
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

            grid: {
                borderColor: '#e7e7e7',
                row: {
                    colors: ['transparent', 'transparent'], // takes an array which will be repeated on columns
                    opacity: 0.5
                },
            },
            /*markers: {
              size: 14, // 設置標記的大小為6
              shape: "square", // "circle" | "square" | "rect"
              strokeColors: '#fff', // 設置標記邊框顏色為白色
              strokeWidth: 1,
              offsetY: -5 // 调整标记位置，使其不被遮挡
                
            },*/
            colors: ['#D7263D'], // 設置線條顏色為白色
            grid: {
                padding: {
                    right: 30 // 增加右边距以确保最后一个标记不被遮挡
                },
                borderColor: '#ffffff' // 設置網格線顏色為白色
            }
        };

        // 創建圖表並在指定的 div 中呈現
        var chart = new ApexCharts(document.querySelector("#chart1_1"), options);
        chart.render();



    });
</script>


</body>

</html>
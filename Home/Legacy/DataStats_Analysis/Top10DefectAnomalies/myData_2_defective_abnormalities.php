<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="/common/IDCC_plugin/deprecated/highcharts/highcharts.js"></script>
    <!-- Include the JS for AG Grid -->
    <script src="/common/IDCC_plugin/ag-grid/ag-grid-community.min.js"></script>

    <script src="/common/IDCC_plugin/deprecated/highcharts/highcharts-3d.js"></script>
    <!-- 引入 Highcharts 主题 -->
    <script src="/common/IDCC_plugin/deprecated/highcharts/themes/high-contrast-light.js"></script>
    <script src="/common/IDCC_plugin/ag-grid/papaparse.min.js"></script>

    <style>
        html,
        body {
            background-image: url(/common/images/bg1.png);
            background-size: 100% 100%;
            font-weight: bold;
            height: 100%;
            /* zoom: 0.5;  */
            /* transform: scale(0.8); 将图像放大到原始大小的 1.5 倍  */

        }

        text {
            font-size: 2vh !important;
        }

        @media screen and (max-width: 900px) {
            body {
                zoom: 0.3;
                height: 6000px;

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
    <title></title>


</head>

<body>
    <div> <?php //require_once('../../../common/web_mode.php'); ?>
        <!-- <a href="../index.php" style=" position:relative; left: 85%;">首頁</a> -->
    </div>


    <div style="display: flex; flex-wrap: wrap; margin-top: 10px;">
        <div id="myGrid" class="ag-theme-alpine" style="height: 550px; width: 50% "></div>
        <div id="container" style="width: 50%"></div>
    </div>
    <?php
    $file = fopen('myData_2_defective_abnormalities.csv', 'r'); // 開啟CSV檔案
    $datatitle = [];
    $i = 0;
    while (($row = fgetcsv($file)) !== false) {
        $datatitle = $row;
        break;
    }
    fclose($file);
    // var_dump($datatitle);
    





    // require("../../../../passwd/DaluenConnectMssql_quality_audit.php");
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
    use Config\DbMapper;

    $dbMapper = new DbMapper();
    $conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

    // echo "sad";
    $sql = "   SELECT  Top 10 [異常原因] as '列標籤'
  ,ROUND(SUM(TRY_CAST(REPLACE([數量], ',', '')  AS FLOAT)),2) as '加總-數量'
  ,ROUND(SUM(TRY_CAST(REPLACE([疋數], ',', '')  AS FLOAT)),2) as '加總-疋數'
  ,ROUND(SUM(TRY_CAST(REPLACE([數量], ',', '')  AS FLOAT)) *100 / 
  (SELECT SUM(TRY_CAST(REPLACE([數量], ',', '')  AS FLOAT)) FROM [Daluen_quality_audit]), 2) AS '占比%'

FROM [Daluen_quality_audit]
GROUP BY [異常原因]
ORDER BY ROUND(SUM(TRY_CAST(REPLACE([數量], ',', '')  AS FLOAT)),2) DESC


";
    $stmt = sqlsrv_query($conn, $sql);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    $data_gate = array();
    $data_piechart = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        //$data_piechart[] = array("name"=>$row['區域別'], "num"=>$row['total']); // 将查询结果保存到数组中
        $data_gate[] = ($row['列標籤']);
        $data_piechart[] = array("name" => $row['列標籤'], "y" => $row['加總-數量']); // 将查询结果保存到数组中
    
    }
    // var_dump($data_gate[0]);
    // var_dump($data_piechart);
    
    $jsonData_piechart = json_encode($data_piechart);

    ?>




    <script type="text/javascript">


        const gridOptions = {

            columnDefs: [

                { field: "<?php echo $datatitle[0]; ?>".replace(/^\uFEFF/, ''), flex: 2, cellStyle: { "text-align": "left" } },
                { field: "<?php echo $datatitle[1]; ?>".replace(/^\uFEFF/, ''), flex: 1, cellRenderer: function (params) { return params.value !== "NULL" && params.value !== undefined ? parseFloat(params.value).toLocaleString("en-US", { style: "decimal", minimumFractionDigits: 2, maximumFractionDigits: 2 }) : ''; } },
                { field: "<?php echo $datatitle[2]; ?>".replace(/^\uFEFF/, ''), flex: 1, cellRenderer: function (params) { return params.value !== "NULL" && params.value !== undefined ? Number(params.value).toLocaleString() : ''; } },
                { field: "<?php echo $datatitle[3]; ?>".replace(/^\uFEFF/, ''), flex: 1 },
            ],

            // default col def properties get applied to all columns
            defaultColDef: { sortable: false, filter: true, minWidth: 150, cellStyle: { "text-align": "right" } },

            rowSelection: null, // allow rows to be selected
            animateRows: true, // have rows animate to new positions when sorted

            // example event handler
            onCellClicked: params => {
                console.log('cell was clicked', params)
            }
        };
        // get div to host the grid
        const eGridDiv = document.getElementById("myGrid");
        // new grid instance, passing in the hosting DIV and Grid Options
        new agGrid.Grid(eGridDiv, gridOptions);

        // Fetch data from local CSV file
        fetch("myData_2_defective_abnormalities.csv")
            .then(response => response.text())
            .then(data => {
                // convert CSV data to JSON format
                const jsonData = Papa.parse(data, { header: true }).data;
                // 計算總和
                const sumRow = {};
                jsonData.forEach(row => {
                    Object.keys(row).forEach(key => {

                        if (key !== "列標籤") {
                            var value = 0;
                            // console.log(row["部門異常"]);
                            // if(row["部門異常"]=="華綸" || row["部門異常"]=="五綸"){
                            //   value = parseFloat(row[key]);
                            // }

                            // 總和設定
                            if (<?php
                            foreach ($data_gate as $key => $gate) {
                                echo 'row["列標籤"]=="' . "$gate" . '"';
                                if ($key != count($data_gate) - 1) {
                                    echo " || ";
                                }
                            }
                            ?>) {
                                value = parseFloat(row[key]);
                            }


                            else {
                                value = parseFloat(row[key]);
                            }

                            if (!isNaN(value)) {
                                sumRow[key] = (sumRow[key] || 0) + value;
                            }

                        }

                    });
                });
                // Round or floor the sum values to integers
                Object.keys(sumRow).forEach(key => {
                    //sumRow[key] = Math.floor(sumRow[key])
                    sumRow[key] = Math.round(sumRow[key]);;
                    // sumRow[key] = parseFloat(sumRow[key].toFixed(2));
                });
                sumRow["列標籤"] = "總計";
                // Add sum row to data
                jsonData.push(sumRow);
                // 計算總和end

                // 在这里指定要删除的值
                // console.log(jsonData);
                // 顯示設定
                // const filterValueToRemove = "五";
                // const filteredData = jsonData.filter(row => !row["部門異常"].includes(filterValueToRemove));
                const filteredData = jsonData.filter(row => <?php
                // echo "console.log(".var_dump($data_gate).")";
                foreach ($data_gate as $key => $gate) {
                    echo 'row["列標籤"]===("' . "$gate" . '")';
                    // if ($key != count($data_gate) -1 ) {
                    echo " || ";
                    // }
                }
                echo 'row["列標籤"]===("總計")'
                    ?>);
                // row["部門異常"].includes("<?php echo $data_gate[0]; ?>") || 
                //                                               row["部門異常"].includes("<?php echo $data_gate[1]; ?>"));

                // gridOptions.api.setRowData(filteredData);
                gridOptions.api.setGridOption("rowData", filteredData)
                // console.log(filteredData);
                // load fetched data into grid
                // gridOptions.api.setRowData(jsonData);
                // console.log(jsonData);


            });


    </script>
    <!-- <script src="ag-grid/papaparse.min.js"></script> -->


</body>

</html>

<script>
    Highcharts.chart("container", {
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
            text: "前十大瑕疵異常",
            style: {
                color: '#FFFFFF',
                // fontSize: '1.3vw',
            }
        },
        plotOptions: {
            pie: {
                depth: 50 // 3D效果的深度
            }
        },
        series: [
            {
                name: "Category",
                data: <?php echo $jsonData_piechart; ?>,
                dataLabels: {
                    enabled: true,
                    format: "{point.name} : {point.y:.0f} <b>({point.percentage:.1f}%)</b>",
                    style: {
                        color: "white", // 設置數據標籤顏色為
                        // fontSize: '200px',
                        textOutline: 'none'
                    }
                }
            }
        ]
    });

    console.log(<?php echo $jsonData_piechart; ?>);
</script>
<script>
    // 10分鐘刷新一次页面
    setTimeout(function () {
        location.reload();
    }, 600000);
</script>
<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
require_once realpath($rootPath . "/common/language/language.php");
ini_set('memory_limit', '5G');

// 加載語言文件
$lang = new Language();
$lang->load("header");
$lang->load("menu");

if (isset($_GET["lang"]) && $_GET["lang"] == 'en-US') {
    $name["Machine_Type"] = 'Machine Type';
    $name["Machine_Number"] = 'Machine Number';
    $name["Machine_Report"] = 'Machine Report';
    $name["Search_Date"] = 'Search Date';
    $name["CSV_download"] = 'CSV';
    $name["Search"] = 'Search';


} else {
    $name["Machine_Type"] = '機台種類';
    $name["Machine_Number"] = '機台代號';
    $name["Machine_Report"] = '報表';
    $name["Search_Date"] = '查詢時間';
    $name["CSV_download"] = 'CSV匯出';
    $name["Search"] = '查詢';



}
function createMachineOptions($options, $id)
{
    echo "<select id='$id'>";
    foreach ($options as $option) {
        echo "<option value='$option'>$option</option>";
    }
    echo "</select>";
}
// 預設日期
$startDate = date("Y-m-d", strtotime("-20 days"));
$endDate = date("Y-m-d", strtotime("+1 days"));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>歷史查詢</title>
    <link rel="stylesheet" href="/common/IDCC_plugin/sweetalert/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.css">
    <script src="/common/IDCC_plugin/jquery/jquery.min.js"></script>
    <script src="/common/IDCC_plugin/bootstrap-5.0.0-dist/js/bootstrap.js"></script>
    <script src="/common/IDCC_plugin/ag-grid/ag-grid-community.min.js"></script>
    <script src="/common/IDCC_plugin/sweetalert/dist/sweetalert2.min.js"></script>
    <style>
        body {
            /* background-color: darkgrey */
            background-image: url(/common/images/bg1.png);
            background-size: cover;
            color: white;
        }

        #historicalReportForm {
            margin-top: 20px;
            margin-bottom: 20px;
            width: 50%;
            /* 設置寬度為50% */
            margin-left: 2%;
            /* 與左邊界置中 */
            width: 100%;

        }

        #AllSeven-History {
            margin-top: 20px;
            /*background-color: rgba(255, 255, 255, 0.5);*/
            padding: 20px;
            border-radius: 10px;
            color: black;
            height: 400px;
            width: 95%;
            margin: 0 auto;
            /* 居中 */
            display: flex;
            justify-content: center;
            /* 在水平方向上居中 */
            align-items: center;
            /* 在垂直方向上居中 */
        }

        .ag-theme-alpine {
            --ag-foreground-color: white;
            --ag-background-color: rgba(26, 46, 57, 0.5);
            --ag-header-foreground-color: white;
            --ag-header-background-color: rgba(14, 86, 114, 0.8);
            --ag-odd-row-background-color: rgba(14, 86, 114, 0.5);
            --ag-header-column-resize-handle-color: rgba(0, 238, 238, 0.3);
            --ag-font-size: 20px;
            --ag-font-family: monospace;
        }

        /* ag-grid單元格 */
        .ag-cell,
        .ag-header-cell-label {
            font-size: 18px;
        }

        /* 捲軸 */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
            background-color: #f1f1f1;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
        }

        /* 捲軸滑塊 */
        ::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.4);
            border-radius: 5px;
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
        }

        #AllSeven-history #myGrid1_1 {
            margin-top: 50px;
            /* 與上方區塊的間距 */
        }

        .form-inline {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            width: 100vw;
            overflow-x: hidden;
        }

        .grid-container {
            /* display: flex;
        flex-wrap: wrap; */
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

        /*form選取器水平排列 */
    </style>
</head>

<body>
    <h3>SMBloT 機聯網設備歷史資料查詢</h3>
    <form id="historicalReportForm" class="form-inline">
        <!-- 機台種類選單 -->
        <label for="selectedOption"><?php echo $name["Machine_Type"]; ?>:</label>
        <select id="selectedOption" class="form-control form-select w-auto">
            <option value="">請選擇機台種類</option>
            <option value="起毛機">起毛機</option>
            <option value="脫水機">脫水機</option>
            <option value="落布機">落布機</option>
            <option value="定型機">定型機</option>
            <option value="中檢機">中檢機</option>
            <option value="成檢機">成檢機</option>
            <option value="磨毛機">磨毛機</option>
            <option value="搌布機">搌布機</option>

        </select>

        <!-- 機台號碼選單 -->
        <label for="selectedOption_machine_number_history"><?php echo $name["Machine_Number"]; ?>:</label>
        <select id="selectedOption_machine_number_history" class="form-control form-select w-auto">
            <option value="">請選擇機台號碼</option>
        </select>

        <!-- 報表選單 -->
        <label for="selectedOption_History"><?php echo $name["Machine_Report"]; ?>:</label>
        <select id="selectedOption_History" class="form-control form-select w-auto">
            <option value="歷史報表">歷史報表</option>
        </select>

        <!-- 查詢日期 -->
        <label for="startDateTime"><?php echo $name["Search_Date"]; ?>:</label>
        <input type="date" id="startDateTime" class="form-control w-auto" value="<?= $startDate ?>">
        <label for="endDateTime">-</label>
        <input type="date" id="endDateTime" class="form-control w-auto" value="<?= $endDate ?>">

        <button type="button" class="btn btn-primary" onclick="saveTimeRange()">尋找</button>
        <a href="#" id="downloadLink" class="btn btn-success"><?php echo $name["CSV_download"]; ?></a>
    </form>


    <!-- <div id="AllSeven-History"></div> -->
    <div class="grid-container">
        <div id="myGrid" class="ag-theme-alpine"></div>
    </div>

    <script>
        $(document).ready(function () {
            // 定義機台種類與機台號碼的對應關係
            const machineMapping = {
                "起毛機": ["BR01起毛機", "BR02起毛機", "BR03起毛機"],
                "脫水機": ["CE01脫水機", "CE02脫水機", "CE03脫水機"],
                "落布機": ["GR01落布機", "GR02落布機", "GR03落布機", "GR04落布機", "GR05落布機", "GR06落布機"],
                "定型機": ["HS01定型機", "HS02定型機", "HS03定型機", "HS04定型機"],
                "中檢機": ["IP01中檢機", "IP02中檢機", "IP03中檢機", "IP04中檢機", "IP05中檢機"],
                "成檢機": ["PK01成檢機", "PK02成檢機", "PK03成檢機", "PK04成檢機", "PK05成檢機", "PK06成檢機", "PK07成檢機", "PK08成檢機"],
                "磨毛機": ["SD01磨毛機"],
                "搌布機": ["VE01搌布機", "VE02搌布機", "VE03搌布機", "VE04搌布機"]
            };

            // 當機台種類改變時，更新機台號碼選單
            $("#selectedOption").change(function () {
                const selectedType = $(this).val();
                const machineNumbers = machineMapping[selectedType] || [];
                const machineNumberSelect = $("#selectedOption_machine_number_history");

                machineNumberSelect.empty();
                machineNumberSelect.append('<option value="">請選擇機台號碼</option>');
                machineNumbers.forEach(number => {
                    const code = number.substring(0, 4); // 取前 4 碼作為 value
                    machineNumberSelect.append(`<option value="${code}">${number}</option>`);
                });
            });
        });
        let databaseData;
        // 查詢按鈕的功能
        function saveTimeRange() {
            const selectedOption = $("#selectedOption").val();
            const selectedOptionMachineNumberHistory = $("#selectedOption_machine_number_history").val();
            const startDateTime = $("#startDateTime").val();
            const endDateTime = $("#endDateTime").val();

            // if (!selectedOption || !selectedOptionMachineNumberHistory || !startDateTime || !endDateTime) {
            //     alert("請填寫完整的查詢條件！");
            //     return;
            // }


            // 發送 AJAX 請求
            fetch('./API/testAPI.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    selectedOption,
                    selectedOptionMachineNumberHistory,
                    startDateTime,
                    endDateTime
                })
            })
                .then(response => response.json())
                .then(Data => {

                    console.log('Success:', Data);
                    const databaseData = Data.data;
                    main(databaseData); // 將資料傳遞給 main 函數進行表格渲染
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function main(databaseData) {
            console.log(databaseData);
            // 將資料庫回傳的 IoData 解析為表格格式
            const rowData = databaseData.map(entry => {
                const parsedData = {};
                entry.IoData.split(/,(?![^\[]*\])/).forEach(item => {
                    const [key, value] = item.split(':').map(str => str.trim());
                    if (!/WifiStrength|WifConnectCntC|MqttConnectCntC/.test(key)) {
                        parsedData[key] = value.startsWith('[') && value.endsWith(']')
                            ? value.slice(1, -1) // 移除方括號
                            : value;
                    }
                });
                parsedData["IoDataUpdated"] = entry.IoDataUpdated; // 加入更新時間
                return parsedData;
            });
        // 動態生成表格欄位並依照字尾 S1、S2 排序
        let columnKeys = Object.keys(rowData[0]);
        columnKeys.sort((a, b) => {
        const suffixPattern = /S(\d+)$/;
        const aMatch = a.match(suffixPattern);
        const bMatch = b.match(suffixPattern);

        if (aMatch && bMatch) {
            return parseInt(aMatch[1]) - parseInt(bMatch[1]);
        } else if (aMatch) {
            return 1; // a 有 Sx 字尾但 b 沒有，b 優先
        } else if (bMatch) {
            return -1; // b 有 Sx 字尾但 a 沒有，a 優先
        } else {
            return a.localeCompare(b); // 沒有 Sx 字尾則照字母排序
        }
    });    

            // 動態生成表格欄位
            const columnDefs = columnKeys.map(key => ({
                headerName: key, // 使用鍵名作為表頭
                field: key, // 將鍵名作為欄位名稱
                sortable: true,
                filter: true,
                resizable: true
            }));

            

            // 初始化 ag-Grid
            const gridOptions = {
                columnDefs: columnDefs,
                rowData: rowData, // 每筆資料顯示為一行
                defaultColDef: {
                    flex: 1,
                    minWidth: 150,
                    resizable: true
                }
            };

            // 清空舊的表格內容
            const gridDiv = document.querySelector("#myGrid");
            gridDiv.innerHTML = ""; // 清空表格容器
            // 渲染表格
            new agGrid.Grid(gridDiv, gridOptions);
        }
    </script>
</body>

</html>
<?php
session_start();
$_SESSION['user'] = 'test';

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");

// 加載語言文件
$lang = new Language();
$lang->load("header");
$lang->load("menu");

if ($_GET["lang"] == 'en-US') {
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
// 創建機台選項的函式
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
<html lang="zh-TW">
<title></title>
<?php require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/common/legacyTemplate/head.php'); ?>
<meta charset="UTF-8">
<script src="/common/IDCC_plugin/jquery/jquery.min.js"></script>
<link href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.min.css" rel="stylesheet">
<!-- <link rel="stylesheet" href="./fontawesome-free-5.1.0-web/css/fontawesome.css"> -->
<script src="/common/IDCC_plugin/bootstrap-5.0.0-dist/js/bootstrap.min.js"></script>
<script src="/common/IDCC_plugin/ag-grid/ag-grid-community.min.js"></script>
<style>
    body {
        /* background-color: darkgrey */
        background-image: url('/common/images/bg1.png');
        background-attachment: fixed;
        background-size: cover;
        background-position: center;
        margin: 0;
        padding: 0;
        color: white;
    }

    #UpdateForm {
        margin-top: 20px;
        margin-bottom: 20px;
        width: 50%;
        /* 設置寬度為50% */
        margin-left: 2%;
        /* 與左邊界置中 */
        width: 100%;

    }

    .container-sm {
        margin-top: 3vh;
        zoom: 0.8;
    }

    #result-container {
        margin-top: 10px;
        /*background-color: rgba(255, 255, 255, 0.5);*/
        padding: 20px;
        border-radius: 10px;
        color: black;
        height: 400px;
        width: 95%;
        margin: 0 auto;
        /* 居中 */
        /*display: flex;*/
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
        --ag-font-size: 30px;
        --ag-font-family: monospace;
    }

    .flow {
        width: 100%;
        height: 50px;
        background-color: green;
        margin: 5px 2px;
        border-radius: 10px;
        border: 2px solid white;
        text-align: center;
        font-size: 18pt;
    }

    .temp {
        width: 100%;
        height: 50px;
        background-color: blue;
        margin: 5px 2px;
        border-radius: 10px;
        border: 2px solid white;
        text-align: center;
        font-size: 18pt;
    }

    .row {
        color: aliceblue;
        width: 100%;
        /* 设置表格宽度为100% */
    }

    table {
        font-size: 20pt;

    }

    .title {
        background-color: gray;
    }

    tr {
        table-layout: fixed;
        /* 固定表格佈局 */
        border: 5px solid #fff;
        /* 單元格邊框 */
        padding: 10px;
        /* 單元格內間距 */
        text-align: center;
        /* 文字居中對齊 */
    }

    td {
        background-color: rgb(169, 169, 169);

        border: 5px solid #fff;
        /* width: auto; */
        width: 20%;
        /* 使所有 td 寬度相等 */
        padding: 10px;
        /* 單元格內間距 */
        text-align: center;
        /* 文字居中對齊 */
    }

    .form-inline {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /*form選取器水平排列 */
</style>
</head>

<body>
    <div class=" mdl-js-layout  mdl-layout--fixed-header is-small-screen">

        <h3>後整理設備稼動率- <span id="machine_number_title"></span></h3>
        <form id="UpdateForm" class="form-inline">
            <!-- 機台種類選單 -->
            <label for="selectedOption"><?php echo $name["Machine_Type"]; ?>: </label>
            <select id="selectedOption" class="form-control form-select w-auto">
                <option value="定型">定型</option>
                <option value="磨毛">磨毛</option>
                <option value="刷毛">刷毛</option>
                <option value="剪毛">剪毛</option>
                <!--<option value="燒毛">燒毛</option>
                    <option value="連續水洗機">連續水洗機</option>
                    <option value="蒸化機">蒸化機</option>
                    <option value="防縮">防縮</option>
                    <option value="剖布">剖布</option>-->
                <option value="解捻">解捻</option>
                <option value="柔軟">柔軟</option>
                <option value="縮練">縮練</option>
            </select>

            <!-- 機台號碼選單 -->
            <label for="selectedOption_machine_number"><?php echo $name["Machine_Number"]; ?>: </label>
            <select id="selectedOption_machine_number" class="form-control form-select w-auto"></select>

            <!-- 報表選單 -->
            <label for="selectedOption_Report"><?php echo $name["Machine_Report"]; ?>: </label>
            <select id="selectedOption_Report" class="form-control form-select w-auto">
                <option value="稼動率報表">稼動率報表</option>
                <!-- 其他選項 -->
            </select>

            <!-- 查詢日期 -->
            <label for="startDateTime"><?php echo $name["Search_Date"]; ?>:</label>
            <input type="date" id="startDateTime" class="form-control w-auto" value="<?= $startDate ?>">
            <label for="endDateTime">-</label>
            <input type="date" id="endDateTime" class="form-control w-auto" value="<?= $endDate ?>">

            <button type="button" class="btn btn-primary"
                onclick="saveTimeRange()"><?php echo $name["Search"]; ?></button>
            <a href="#" id="downloadLink1" class="btn btn-success"><?php echo $name["CSV_download"]; ?></a>
        </form>


        <div id="result-container">
        </div>


    </div>
</body>

<script>
    const queryString = window.location.search;
    //====================使用jQuery頁面加載預設===================
    $(document).ready(function () {
        /*document.getElementById("downloadLink1").onclick = function() {
            return false;
        };*/
        createDropdownMenu(["定型", "磨毛", "刷毛", "剪毛", "解捻", "柔軟"], "#selectedOption");
        createDropdownMenu(["T01定型機", "T03定型機", "T05定型機"], "#selectedOption_machine_number");
        createDropdownMenu(['稼動率報表'], "#selectedOption_Report");

        //====================監視#selectedOption, #startDateTime, #endDateTime變化===================
        $("#selectedOption, #startDateTime, #endDateTime").change(function () {
            handleMenuChange("machinefun");
            updateMachineNumberTitle();
            ExportCSV();
            saveTimeRange();
        });
        //====================監視#selectedOption_machine_number_history的變化===================
        $("#selectedOption_machine_number").change(function () {
            updateMachineNumberTitle();
            ExportCSV();
            saveTimeRange();
        });

        updateMachineNumberTitle();
        saveTimeRange();
        ExportCSV();

    });
    //====================通用函式：更新下載鏈接 ExportCSV()===================
    /*function ExportCSV() {
        // 獲取選擇的機台編號
        var selectedOptionMachineNumber = $("#selectedOption_machine_number").val();
        var selectedOption = $("#selectedOption").val();
        var startDateTime = $("#startDateTime").val();
        var endDateTime = $("#endDateTime").val();
        var selectedOption_Report = $("#selectedOption_Report").val();
        url = 'Stenter_download_v2.php'
        //console.log(url);

        
        // 更新下載鏈接
        $("#downloadLink1").attr("href",
            url +
            "?selectedOption=" + selectedOption +
            "&selectedOption_machine_number=" + selectedOptionMachineNumber +
            "&startDateTime=" + startDateTime +
            "&endDateTime=" + endDateTime +
            "&selectedOption_Report=" + selectedOption_Report
        );
    }*/
    // 通用函式：創建下拉選單
    function createDropdownMenu(options, optionsID) {
        var selectElement = $(optionsID).empty();
        for (var i = 0; i < options.length; i++) {
            var optionElement = $("<option>").val(options[i]).text(options[i]);
            selectElement.append(optionElement);
        };
    }
    //====================通用函式：更換機台Title updateMachineNumberTitle()===================
    function updateMachineNumberTitle() {
        var selectedMachineName = $("#selectedOption_machine_number").val();
        $("#machine_number_title").text(selectedMachineName);
    }
    //====================通用函式：下拉式選單變化handleMenuChange(類型)===================
    function handleMenuChange(type) {
        var selectedOption = document.getElementById("selectedOption").value; // 直接獲取選擇選項的值
        //console.log(selectedOption);
        switch (selectedOption) {
            case "定型":
                if (type == "machinefun")
                    createDropdownMenu(["T01定型機", "T03定型機", "T05定型機"], "#selectedOption_machine_number");
                break;
            case "磨毛":
                if (type == "machinefun")
                    createDropdownMenu(["G01磨毛機"], "#selectedOption_machine_number");
                break;
            case "刷毛":
                if (type == "machinefun")
                    createDropdownMenu(["B01刷毛機", "B02刷毛機", "B03刷毛機", "B04刷毛機", "B05刷毛機", "B06刷毛機"], "#selectedOption_machine_number");
                break;
            case "剪毛":
                if (type == "machinefun")
                    createDropdownMenu(["C01剪毛機", "C02剪毛機", "C03剪毛機"], "#selectedOption_machine_number");
                break;
            case "梳毛":
                if (type == "machinefun")
                    createDropdownMenu(["CM1梳毛機"], "#selectedOption_machine_number");
                break;
            case "搖粒":
                if (type == "machinefun")
                    createDropdownMenu(["AP1搖粒機"], "#selectedOption_machine_number");
                break;
            case "燒毛":
                if (type == "machinefun")
                    createDropdownMenu(["SG01燒毛機", "SG02燒毛機"], "#selectedOption_machine_number");
                break;
            case "解捻":
                if (type == "machinefun")
                    createDropdownMenu(["RC1解捻機", "RC2解捻機"], "#selectedOption_machine_number");
                break;
            case "柔軟":
                if (type == "machinefun")
                    createDropdownMenu(["CP1柔軟機"], "#selectedOption_machine_number");
                break;

            default:
                break;
        }
    }
    // 選單變動事件監聽器
    //selectedOption.addEventListener("change", function() { handleMenuChange("machinefun") });
    // 保存時間範圍並觸發相應 AJAX 請求
    function saveTimeRange() {
        var selectedOptionValue = document.getElementById("selectedOption").value;
        var selectedOptionReportValue = document.getElementById("selectedOption_Report").value;
        var startDateTime = document.getElementById("startDateTime").value;
        var endDateTime = document.getElementById("endDateTime").value;

        // 處理不同選項的 AJAX 請求
        switch (selectedOptionValue) {
            case "磨毛":
                if (selectedOptionReportValue == '稼動率報表') {
                    ajaxCall(selectedOptionValue, document.getElementById("selectedOption_machine_number").value, selectedOptionReportValue, startDateTime, endDateTime);
                }
                break;
            case "刷毛":
                if (selectedOptionReportValue == '稼動率報表') {
                    ajaxCall(selectedOptionValue, document.getElementById("selectedOption_machine_number").value, selectedOptionReportValue, startDateTime, endDateTime);
                }
                break;
            case "剪毛":
                if (selectedOptionReportValue == '稼動率報表') {
                    ajaxCall(selectedOptionValue, document.getElementById("selectedOption_machine_number").value, selectedOptionReportValue, startDateTime, endDateTime);
                }
                break;
            case "定型":
                if (selectedOptionReportValue == '稼動率報表') {
                    ajaxCall(selectedOptionValue, document.getElementById("selectedOption_machine_number").value, selectedOptionReportValue, startDateTime, endDateTime);
                }
                break;
            case "解捻":
                if (selectedOptionReportValue == '稼動率報表') {
                    ajaxCall(selectedOptionValue, document.getElementById("selectedOption_machine_number").value, selectedOptionReportValue, startDateTime, endDateTime);
                }
                break;

            case "柔軟":
                if (selectedOptionReportValue == '稼動率報表') {
                    ajaxCall(selectedOptionValue, document.getElementById("selectedOption_machine_number").value, selectedOptionReportValue, startDateTime, endDateTime);
                }
                break;
            // Add more cases for other options if needed
            default:
                break;
        }
    }
    //====================通用函式：Ajax(機台類型, 機台編號, 報表類型, 查詢開始時間, 查詢結束時間)===================
    function ajaxCall(selectedOption, selectedOption_machine_number, selectedOption_Report, startDateTime, endDateTime) {
        var url;
        // 根據 selectedOption 的值來決定 AJAX 請求的 URL
        switch (selectedOption) {
            case "磨毛":
                url = "enquiry_Grinding_ajax_v2.php" + queryString;
                break;
            case "刷毛":
                url = "enquiry_Brushing_ajax_v2.php" + queryString;
                break;
            case "剪毛":
                url = "enquiry_Shearing_ajax_v2.php" + queryString;
                break;
            case "定型":
                url = "Stenter_enquiry_ajax.php" + queryString;
                break;
            case "解捻":
                url = "enquiry_Untwisting_Machine_ajax_v2.php" + queryString;
                break;
            case "柔軟":
                url = "enquiry_Softening_Machine_ajax_v2.php" + queryString;
                break;
            // 可以添加更多的 case，以處理其他選項
            default:
                break;
        }

        // 發送 AJAX 請求
        $.ajax({
            beforeSend: function () {
                $('.loading').fadeIn(300);
            },
            url: url, // 使用上面決定的 URL
            type: "POST",
            data: {
                selectedOption: selectedOption,
                selectedOption_machine_number: selectedOption_machine_number,
                startDateTime: startDateTime,
                endDateTime: endDateTime,
                selectedOption_Report: selectedOption_Report
            },
            success: function (response) {
                $("#result-container").html(response);
                /*console.log(response);
                console.log(selectedOption); */
                //console.log(selectedOption_machine_number); 
                /*console.log(startDateTime); 
                console.log(endDateTime); 
                console.log(selectedOption_Report); */
                // 填充數據到 AG Grid 中
                //var gridDiv = document.querySelector('#myGrid');
                //new agGrid.Grid(gridDiv, JSON.parse(response));
            },
            error: function (err) {
                $("#result-container").html(err);
            },
            complete: function () {
                $('.loading').fadeOut(300);
            }
        });
    }

</script>



<script>
    //console.log("selectedOption_machine_number value:", document.getElementById('selectedOption_machine_number').value);

    // 添加 input1 的值變化事件監聽器
    //document.getElementById('startDateTime').addEventListener('input', ExportCSV);
    // 添加 input2 的值變化事件監聽器
    //document.getElementById('endDateTime').addEventListener('input', ExportCSV);
    //document.getElementById('DDDD_1').addEventListener('input', ExportCSV);
    //document.createElement('input').addEventListener('change', ExportCSV);
    //document.getElementById('checkboxContainer1').addEventListener('change', ExportCSV);
    //document.getElementById('checkboxContainer2').addEventListener('change', ExportCSV);
    //document.getElementById('toggleButton').addEventListener('click', ExportCSV);
    var selectedOptionValue = document.getElementById("selectedOption").value;
    var selectedOptionMachineNumber = document.getElementById("selectedOption_machine_number").value;
    var startDateTime = document.getElementById("startDateTime").value;
    var endDateTime = document.getElementById("endDateTime").value;
    var selectedOptionReportValue = document.getElementById("selectedOption_Report").value;

    // 添加 input3 的值變化事件監聽器
    // document.getElementById('work_no_Input').addEventListener('input', ExportCSV);
    // document.getElementById('FabricName_Input').addEventListener('input', ExportCSV);

    //ExportCSV();
    //====================通用函式：更新下載鏈接 ExportCSV()===================

    function ExportCSV() {
        document.getElementById('downloadLink1').href
            = "FinishigMachines_Activation_download.php" + queryString
            + "&selectedOption=" + document.getElementById('selectedOption').value
            + "&selectedOption_machine_number=" + document.getElementById('selectedOption_machine_number').value
            + "&startDateTime=" + document.getElementById('startDateTime').value
            + "&endDateTime=" + document.getElementById('endDateTime').value
            + "&selectedOption_Report=" + document.getElementById('selectedOption_Report').value
            ;
        // console.log(Array.from(document.querySelectorAll('#checkboxContainer1  input[type="checkbox"]')).filter(checkbox => checkbox.checked).map(checkbox => checkbox.value))
        // document.getElementById('downloadLink2').href
        //   = "NNW_Analysis_download_2_ajax.php?"
        //   + "startDateTime=" + document.getElementById('startDateTime').value
        //   + "&endDateTime=" + document.getElementById('endDateTime').value
        //   + "&time_unit=" + document.getElementById('DDDD_1').value
        //   ;

        // document.getElementById('downloadLink3').href
        // = "NNW_Analysis_download_3_ajax.php?"
        // + "startDateTime=" + document.getElementById('startDateTime').value
        // + "&endDateTime=" + document.getElementById('endDateTime').value
        // ;

        // document.getElementById('downloadLink4').href
        // = "NNW_Analysis_download_4_ajax.php?"
        // + "startDateTime=" + document.getElementById('startDateTime').value
        // + "&endDateTime=" + document.getElementById('endDateTime').value
        // ;

        // document.getElementById('downloadLink5').href
        // = "NNW_Analysis_download_5_ajax.php?"
        // + "startDateTime=" + document.getElementById('startDateTime').value
        // + "&endDateTime=" + document.getElementById('endDateTime').value
        // ;

        // document.getElementById('downloadLink6').href
        // = "NNW_Analysis_download_6_ajax.php?"
        // + "startDateTime=" + document.getElementById('startDateTime').value
        // + "&endDateTime=" + document.getElementById('endDateTime').value
        // ;
    }
</script>

</body>

</html>
<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
$rootPath = $_SERVER['DOCUMENT_ROOT'];
require_once realpath($rootPath . "/common/language/language.php");
$lang = new Language();
$lang->load("header");
$lang->load("menu");

if (isset($_GET["lang"]) && $_GET["lang"] == 'en-US') {
    $name["Machine_Type"] = 'Machine Type';
    $name["Machine_Number"] = 'Machine Number';
} else {
    $name["Machine_Type"] = '機台種類';
    $name["Machine_Number"] = '機台代號';

}
// 預設日期
$startDate = date("Y-m-d", strtotime("-20 days"));
$endDate = date("Y-m-d", strtotime("+1 days"));
function createMachineOptions($options, $id)
{
    echo "<select id='$id'>";
    foreach ($options as $option) {
        echo "<option value='$option'>$option</option>";
    }
    echo "</select>";
}

?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <script>
        const queryString = window.location.search;
    </script>
    <?php require_once realpath($rootPath . '/common/legacyTemplate/head.php'); ?>
    <meta charset="UTF-8">
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

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            width: 100vw;
            overflow-x: hidden;
        }
    </style>
</head>

<body>
    <div class=" mdl-js-layout  mdl-layout--fixed-header is-small-screen">
        <h2>設備運轉狀態- <span id="machine_number_title"></span></h2>
        <form id="UpdateForm" class="form-inline">
            <label for="selectedOption"><?php echo $name["Machine_Type"]; ?>: </label>
            <select id="selectedOption" class="form-control form-select w-auto">
                <option value="定型">定型</option>
                <option value="磨毛">磨毛</option>
                <option value="刷毛">刷毛</option>
                <option value="剪毛">剪毛</option>
                <option value="燒毛">燒毛</option>
                <option value="連續水洗機">連續水洗機</option>
                <option value="蒸化機">蒸化機</option>
                <option value="剖布">剖布</option>
                <option value="解捻">解捻</option>
                <option value="防縮">防縮</option>
                <option value="柔軟">柔軟</option>
                <option value="縮練">縮練</option>
                <option value="落布">落布</option>
            </select>
            <label for="selectedOption_machine_number"><?php echo $name["Machine_Number"]; ?>: </label>
            <select id="selectedOption_machine_number" class="form-control form-select w-auto"></select>
        </form>
        <div style="margin-top: 50px;" id="allseven-result"></div>
        <!-- 使用 iframe 嵌入 history_query.php -->
        <iframe id="history-info-iframe" style="width: 100%; height: 100vh; border: none;"></iframe>
</body>
<script>
    $("#history-info-iframe").attr("src", "History_Information_index.php" + queryString);

    var refreshInterval;//全局變量定時器
    var Carousel_count = 0;
    //====================使用jQuery頁面加載預設===================================================
    $(document).ready(function () {
        createDropdownMenu(["定型", "磨毛", "刷毛", "剪毛", "燒毛", "連續水洗機", "蒸化機", "剖布", "解捻", "防縮", "柔軟", "縮練","落布機"], "#selectedOption");
        createDropdownMenu(["T01定型機", "T03定型機", "T05定型機"], "#selectedOption_machine_number");
        //====================監視#selectedOption的變化===================
        $("#selectedOption").change(function () {
            handleMenuChange("machinefun");
            updateMachineNumberTitle();
            saveTimeRange();
        });
        //====================監視#selectedOption_machine_number_history的變化===================
        $("#selectedOption_machine_number").change(function () {
            updateMachineNumberTitle();
            saveTimeRange();
        });
        //handleMenuChange("machinefun");
        updateMachineNumberTitle();
        saveTimeRange();



    });
    //====================通用函式：創建下拉選單createDropdownMenu(限制輸入的選項, DOM元素ID)=========
    function createDropdownMenu(options, optionsID) {
        var selectElement = $(optionsID).empty();
        for (var i = 0; i < options.length; i++) {
            var optionElement = $("<option>").val(options[i]).text(options[i]);
            selectElement.append(optionElement);
        }
    }
    //====================通用函式：更換機台Title updateMachineNumberTitle()========================
    function updateMachineNumberTitle() {
        var selectedMachineName = $("#selectedOption_machine_number").val();
        $("#machine_number_title").text(selectedMachineName);
    }
    //====================通用函式：下拉式選單變化handleMenuChange(類型)=============================
    function handleMenuChange(type) {
        var selectedOption = $("#selectedOption").val(); // 使用 jQuery 

        switch (selectedOption) {
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
            case "連續水洗機":
                if (type == "machinefun")
                    createDropdownMenu(["RW1連續水洗機"], "#selectedOption_machine_number");
                break;
            case "蒸化機":
                if (type == "machinefun")
                    createDropdownMenu(["CS1蒸化機"], "#selectedOption_machine_number");
                break;
            case "定型":
                if (type == "machinefun")
                    createDropdownMenu(["T01定型機", "T03定型機", "T05定型機"], "#selectedOption_machine_number");
                break;
            case "剖布":
                if (type == "machinefun")
                    createDropdownMenu(["RS1對線剖布機"], "#selectedOption_machine_number");
                break;
            case "防縮":
                if (type == "machinefun")
                    createDropdownMenu(["AS1防縮機", "AS2防縮機"], "#selectedOption_machine_number");
                break;
            case "解捻":
                if (type == "machinefun")
                    createDropdownMenu(["RC1開幅解捻機", "RC2開幅解捻機"], "#selectedOption_machine_number");
                break;
            case "柔軟":
                if (type == "machinefun")
                    createDropdownMenu(["CP1開幅柔軟機"], "#selectedOption_machine_number");
                break;
            case "縮練":
                if (type == "machinefun")
                    createDropdownMenu(["WH1縮練機"], "#selectedOption_machine_number");
                break;
            case "落布機":
                if (type == "machinefun")
                    createDropdownMenu(["GR01落布機","GR02落布機","GR03落布機","GR04落布機","GR05落布機","GR06落布機"], "#selectedOption_machine_number");
                break;
            default:
                break;
        }
    }
    //====================通用函式：保存時間範圍並觸發相應 AJAX 請求saveTimeRange()===================
    function saveTimeRange() {
        var selectedOptionValue = $("#selectedOption").val();
        var selectedOptionMachineNumberVizValue = $("#selectedOption_machine_number").val();
        // 清除之前的定時器
        clearTimeout(refreshInterval);
        // 處理不同選項的 AJAX 請求
        switch (selectedOptionValue) {
            case "磨毛":
                ajaxCall(selectedOptionValue, $("#selectedOption_machine_number").val());
                break;
            case "刷毛":
                ajaxCall(selectedOptionValue, $("#selectedOption_machine_number").val());
                break;
            case "剪毛":
                ajaxCall(selectedOptionValue, $("#selectedOption_machine_number").val());
                break;
            case "燒毛":
                ajaxCall(selectedOptionValue, $("#selectedOption_machine_number").val());
                break;
            case "連續水洗機":
                ajaxCall(selectedOptionValue, $("#selectedOption_machine_number").val());
                break;
            case "蒸化機":
                ajaxCall(selectedOptionValue, $("#selectedOption_machine_number").val());
                break;
            case "定型":
                ajaxCall(selectedOptionValue, $("#selectedOption_machine_number").val());
                break;
            case "剖布":
                ajaxCall(selectedOptionValue, $("#selectedOption_machine_number").val());
                break;
            case "防縮":
                ajaxCall(selectedOptionValue, $("#selectedOption_machine_number").val());
                break;
            case "解捻":
                ajaxCall(selectedOptionValue, $("#selectedOption_machine_number").val());
                break;
            case "柔軟":
                ajaxCall(selectedOptionValue, $("#selectedOption_machine_number").val());
                break;
            case "縮練":
                ajaxCall(selectedOptionValue, $("#selectedOption_machine_number").val());
                break;
            case "落布機":
                ajaxCall(selectedOptionValue, $("#selectedOption_machine_number").val());
                break;
            // Add more cases for other options if needed
            default:
                break;
        }
    }
    //====================通用函式：Ajax(機台類型, 機台編號)=========================================
    function ajaxCall(selectedOption, selectedOption_machine_number) {
        var url;
        // 根據 selectedOption 的值來決定 AJAX 請求的 URL
        switch (selectedOption) {
            case "磨毛":
                url = "information_Grinding_ajax_v2.php";
                break;
            case "刷毛":
                url = "information_Brushing_ajax_v2.php";
                break;
            case "剪毛":
                url = "information_Shearing_ajax_v2.php";
                break;
            case "燒毛":
                url = "information_Burning_ajax_v2.php";
                break;
            case "連續水洗機":
                url = "information_Washers_ajax_v2.php";
                break;
            case "蒸化機":
                url = "information_Steamer_ajax_v2.php";
                break;
            case "定型":
                url = "styling_information_ajax_v2.php";
                break;
            case "剖布":
                url = "information_Alignment_Cutting_ajax_v2.php";
                break;
            case "防縮":
                url = "information_Anti_Shrinkage_Machine_ajax_v2.php";
                break;
            case "解捻":
                url = "information_Untwisting_Machine_ajax_v2.php";
                break;
            case "柔軟":
                url = "information_Softening_Machine_ajax_v2.php";
                break;
            case "縮練":
                url = "contraction_information_ajax_v2.php";
                break;
            case "落布機":
                url = "information_Fabric_Dropper_ajax_v2.php";

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
            url: './API/' + url + queryString, // 使用上面決定的 URL
            type: "POST",
            data: {
                selectedOption_machine_number: selectedOption_machine_number
            },
            success: function (response) {
                if ((typeof response === 'object' && response.error) || response.includes("SQLSTATE")) {
                    // 警告視窗
                    Swal.fire("Error!", "error", "warning");
                    $("#allseven-result").html("<h1 class='text-center m-3'>No Data</h1>");
                    if (response.error) {
                        console.error(`Error occurred at ${new Date().toISOString()}:`, response.error);
                    }
                } else {
                    $("#allseven-result").html(response);
                    console.log(selectedOption_machine_number);
                }
            },
            error: function (xhr, status, error) {
                //$("#AllSeven-History").html(xhr.responseText);
                //console.log(error);
                Swal.fire("Error!", error, "warning");
            },
            complete: function () {
                $('.loading').fadeOut(300);

                // 設置定時器進行間隔刷新
                refreshInterval = setTimeout(function () {

                    Carousel_fun();

                    ajaxCall($("#selectedOption").val(), $("#selectedOption_machine_number").val());
                }, 5000); // 每5秒刷新一次，可以根據需要調整間隔時間
            }
        });



    }


    function Carousel_fun() {

        machine_array = [
            { selectedOption: "定型", selectedOption_machine_number: "T01定型機" },
            { selectedOption: "定型", selectedOption_machine_number: "T03定型機" },
            { selectedOption: "定型", selectedOption_machine_number: "T05定型機" },

            { selectedOption: "磨毛", selectedOption_machine_number: "G01磨毛機" },

            { selectedOption: "刷毛", selectedOption_machine_number: "B01刷毛機" },
            { selectedOption: "刷毛", selectedOption_machine_number: "B02刷毛機" },
            { selectedOption: "刷毛", selectedOption_machine_number: "B03刷毛機" },
            { selectedOption: "刷毛", selectedOption_machine_number: "B04刷毛機" },
            { selectedOption: "刷毛", selectedOption_machine_number: "B05刷毛機" },
            { selectedOption: "刷毛", selectedOption_machine_number: "B06刷毛機" },

            { selectedOption: "剪毛", selectedOption_machine_number: "C01剪毛機" },
            { selectedOption: "剪毛", selectedOption_machine_number: "C02剪毛機" },
            { selectedOption: "剪毛", selectedOption_machine_number: "C03剪毛機" },

            // { selectedOption: "梳毛", selectedOption_machine_number: "CM1梳毛機" },

            // { selectedOption: "搖粒", selectedOption_machine_number: "AP1搖粒機" },

            { selectedOption: "燒毛", selectedOption_machine_number: "SG01燒毛機" },
            { selectedOption: "燒毛", selectedOption_machine_number: "SG02燒毛機" },

            { selectedOption: "連續水洗機", selectedOption_machine_number: "RW1連續水洗機" },

            { selectedOption: "蒸化機", selectedOption_machine_number: "CS1蒸化機" },

            { selectedOption: "剖布", selectedOption_machine_number: "RS1對線剖布機" },

            { selectedOption: "防縮", selectedOption_machine_number: "AS1防縮機" },
            { selectedOption: "防縮", selectedOption_machine_number: "AS2防縮機" },

            { selectedOption: "解捻", selectedOption_machine_number: "RC1開幅解捻機" },
            { selectedOption: "解捻", selectedOption_machine_number: "RC2開幅解捻機" },

            { selectedOption: "柔軟", selectedOption_machine_number: "CP1開幅柔軟機" },

            { selectedOption: "縮練", selectedOption_machine_number: "WH1縮練機" }

        ];

        if ('<?php echo $_GET['Carousel'] ?? "" ?>' == "True") {
            createDropdownMenu([machine_array[Carousel_count]['selectedOption']], "#selectedOption");
            createDropdownMenu([machine_array[Carousel_count]['selectedOption_machine_number']], "#selectedOption_machine_number");
            updateMachineNumberTitle();
            if (Carousel_count == (machine_array.length - 1))
                Carousel_count = 0;
            else
                Carousel_count++;
        }

        // console.log(Carousel_count);
    }
</script>
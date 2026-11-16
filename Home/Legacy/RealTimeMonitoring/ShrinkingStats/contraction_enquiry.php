<?php
ini_set('memory_limit', '-1');
// ini_set('display_errors', '1');
// error_reporting(E_ALL);
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");
$lang = new Language();
$lang->load("header");
$lang->load("menu");

if ($_GET["lang"] == 'en-US') {
    $name["Report"] = 'Report';
    $name["Query_Date"] = 'Query Date';
    $name["Find"] = 'Find';
} else {
    $name["Report"] = '報表';
    $name["Query_Date"] = '查詢日期';
    $name["Find"] = '尋找';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/common/legacyTemplate/head.php'); ?>
    <meta charset="UTF-8">
    <!-- <script src="https://code.highcharts.com/highcharts.js"></script> -->
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Include the JS for AG Grid -->
    <script src="https://cdn.jsdelivr.net/npm/ag-grid-community/dist/ag-grid-community.min.noStyle.js"></script>
    <!-- Include the core CSS, this is needed by the grid -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community/styles/ag-grid.css" />
    <!-- Include the theme CSS, only need to import the theme you are going to use -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ag-grid-community/styles/ag-theme-alpine.css" />
    <!-- <script src="https://code.highcharts.com/highcharts-3d.js"></script> -->
    <!-- 引入 Highcharts 主题 -->
    <!-- <script src="https://code.highcharts.com/themes/high-contrast-light.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


    <title></title>
    <style>
        .checkbox_css {
            color: white;
            /* font-size: 1vw; */
        }

        #timeRange {
            margin-top: 2vh;
        }
    </style>

</head>

<body>
    <div class=" mdl-js-layout  mdl-layout--fixed-header is-small-screen">


        <!-- <div style="position: fixed; top: 0%; right: 10%; font-size: 45px; opacity: 0.5; color: white; pointer-events: none;">迪卡儂</div> -->
        <!-- <div id="content"> -->
        <!-- <div><a href="../index.php" style=" position:absolute; left: 95%;">首頁</a> -->
        <div id="timeRange">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label for="selectedOption"><?php echo $name["Report"]; ?>：</label>
            <select id="selectedOption">
                <option value="allseven_alm">警報</option>
                <option value="allseven_power">能源耗用</option>
                <option value="allseven_temp">溫度</option>
                <option value="allseven_tension">張力</option>
            </select>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <label for="startDateTime"><?php echo $name["Query_Date"]; ?>：</label>
            <input type="datetime-local" id="startDateTime">
            <label for="endDateTime">-</label>
            <input type="datetime-local" id="endDateTime">&nbsp;&nbsp;&nbsp;
            <button onclick="saveTimeRange()"><?php echo $name["Find"]; ?></button>


        </div>
        <script>

            const queryString = window.location.search;
            function saveTimeRange() {

                const selectedOption = document.getElementById("selectedOption").value;

                const startDateTime = document.getElementById("startDateTime").value;
                const endDateTime = document.getElementById("endDateTime").value;

                // 将选择的时间范围存储在变量中
                const timeRange = {
                    start: startDateTime,
                    end: endDateTime
                };

                // 将选择的时间范围显示在页面上
                console.log(timeRange);
                console.log(selectedOption);

                ajaxCall(selectedOption, startDateTime, endDateTime)
            }
            // {start: '2023-09-21T01:54', end: '2023-09-29T13:54'}
        </script>
        <?php
        ?>
        &nbsp;
        <!-- <button id="selectAllButton">全選</button> -->
        <!-- <button onclick="getSelectedOptions()"></button> -->
        <!-- <script>
  // const selectAllButton = document.getElementById("selectAllButton");

// 全選按鈕的點擊事件監聽器
selectAllButton.addEventListener("click", function() {
  var checkboxes = document.querySelectorAll('input[type="checkbox"]');
  
  // 將所有的 checkbox 設置為勾選狀態
  checkboxes.forEach(checkbox => {
    checkbox.checked = true;
  });
  console.log(checkboxes);
  var selectedOptions = Array.from(checkboxes)
  .filter(checkbox => checkbox.checked)
  .map(checkbox => checkbox.value);

console.log(selectedOptions);
ajaxCall(selectedOptions)
});
</script> -->
    </div>
    <!-- </div> -->
    <br></br>

    </div>
    <!-- <button onclick="getSelectedOptions()"></button> -->
    <!-- <script>
function handleCheckbox(checkbox) {
  var checkboxes = document.querySelectorAll('input[type="checkbox"]');
  var selectedOptions = Array.from(checkboxes)
  .filter(checkbox => checkbox.checked)
  .map(checkbox => checkbox.value);

console.log(selectedOptions);
ajaxCall(selectedOptions)

}
</script> -->




    <div id="Daluen-result"></div>

    <script>

        function ajaxCall(selectedOption, startDateTime, endDateTime) {
            $.ajax({
                // beforeSend: function () {
                //     $('.loading').fadeIn(300);
                // },
                url: "contraction_enquiry_ajax.php" + queryString,
                type: "POST",
                data: {
                    selectedOption: selectedOption,
                    startDateTime: startDateTime,
                    endDateTime: endDateTime,
                    lang: '<?php echo $_GET["lang"]; ?>'

                },
                success: function (response) {
                    $("#Daluen-result").html(response);
                },
                error: function (err) {
                    $("#Daluen-result").html(err);
                },
                // complete: function () {
                //     $('.loading').fadeOut(300);

                //     // //測試可利用setTimeout 讓loading效果明顯
                //     // setTimeout(function () {
                //         // $('.loading').fadeOut(300);
                //     // }, 3000);
                // }
            });

            //       setTimeout(function () {
            //   ajaxCall()
            // }, 300000);
        }

        ajaxCall("", "", "");




    </script>
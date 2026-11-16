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

    <script src="/common/IDCC_plugin/jquery/jquery.min.js"></script>

    <style>
        .checkbox_css {
            color: white;
            /* font-size: 1vw; */
        }
    </style>

</head>

<body>
    <?php // require_once('../../../common/web_mode.php'); ?>
    <!-- <div style="position: fixed; top: 0%; right: 10%; font-size: 45px; opacity: 0.5; color: white; pointer-events: none;">綿春</div> -->

    <!-- <div><?php // require_once('../../../common/web_mode.php'); ?><a href="../index.php"
            style=" position:relative; left: 85%;">首頁</a></div>

    <div> -->
    <!-- <a href="../index.php" style=" position:absolute; left: 95%;">首頁</a> -->
    <?php

    require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
    use Config\DbMapper;

    $dbMapper = new DbMapper();
    $conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

    $sql = "      SELECT DISTINCT [年]
FROM [Daluen_mds_report]
ORDER BY [年]
";
    $stmt = sqlsrv_query($conn, $sql);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $options[] = $row['年'];
    }


    foreach ($options as $index => $option) {
        if ($index % 7 == 0 && $index != 0)
            echo "<br>";
        if ($option == end($options))
            echo "<input type='checkbox'  id='$option' value='$option' checked onclick='handleCheckbox(this)'>";
        else
            echo "<input type='checkbox'  id='$option' value='$option' onclick='handleCheckbox(this)'>";

        echo "<label class='checkbox_css' for='$option'>$option</label>";
        echo "&nbsp;&nbsp;&nbsp;";



    }

    ?>
    &nbsp;
    <button id="selectAllButton">全選</button>
    <!-- <button onclick="getSelectedOptions()"></button> -->
    <script>
        const queryString = window.location.search;

        const selectAllButton = document.getElementById("selectAllButton");

        // 全選按鈕的點擊事件監聽器
        selectAllButton.addEventListener("click", function () {
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
    </script>
    <br>
    </br>

    </div>
    <!-- <button onclick="getSelectedOptions()"></button> -->
    <script>
        function handleCheckbox(checkbox) {
            var checkboxes = document.querySelectorAll('input[type="checkbox"]');
            var selectedOptions = Array.from(checkboxes)
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            console.log(selectedOptions);
            ajaxCall(selectedOptions)

        }
    </script>




    <div id="Daluen-result"></div>

    <script>


        function ajaxCall(selectedOptions) {
            $.ajax({
                // beforeSend: function () {
                //     $('.loading').fadeIn(300);
                // },
                url: "no9_ajax.php" + queryString,
                type: "POST",
                data: {
                    checkbox: selectedOptions
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
        ajaxCall(["<?php echo end($options); ?>"])




    </script>
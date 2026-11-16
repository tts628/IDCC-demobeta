<?php
session_start();

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");
$lang = new Language();
$lang->load("header");
$lang->load("menu");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/common/legacyTemplate/head.php'); ?>
    <meta charset="UTF-8">
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="/common/IDCC_plugin/ag-grid/ag-grid-community.min.js"></script>
    <script src="/common/IDCC_plugin/jquery/jquery.min.js"></script>


    <title></title>

    <style>
        html,
        body {
            background-image: url(/common/images/bg1.png);
            background-size: 100% 100%;
            /* 保持图像完全填充整个页面 */
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .loading {
            display: none;
            /* 預設隱藏 */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            font-size: 16px;
            border-radius: 5px;
            z-index: 9999;
        }

        .checkbox_css {
            color: white;
            /* font-size: 1vw; */
        }

        #timeRange {
            margin-top: 2vh;
        }

        .container {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            /* 允许自动换行 */
        }

        .column {
            flex: 1;
            padding: 20px;
            border: 1px solid #ddd;
            text-align: center;
            box-sizing: border-box;
            /* 确保 padding 不会增加元素的宽度 */
        }

        Button {
            background-color: #4372c3;
            /* 绿色背景 */
            color: white;
            /* 白色文字 */
            border: none;
            /* 无边框 */
            border-radius: 5px;
            /* 圆角 */
            cursor: pointer;
            /* 鼠标指针样式 */

            transition: background-color 0.3s;
            /* 添加背景颜色变化的过渡效果 */
        }

        Button:hover {
            background-color: #224197;
            /* 鼠标悬停时的背景颜色 */
        }

        .tog {
            display: none;
            /* 初始显示 */
        }
    </style>

</head>

<body>
    <div class=" mdl-js-layout  mdl-layout--fixed-header is-small-screen">

        <div class="loading">載入中...</div>
        <!-- <div style="position: fixed; top: 0%; right: 10%; font-size: 45px; opacity: 0.5; color: white; pointer-events: none;">迪卡儂</div> -->
        <!-- <div id="content"> -->
        <!-- <div><a href="../index.php" style=" position:absolute; left: 95%;">首頁</a> -->
        <button id='toggleButton'>設定搜尋條件</button>

        <div id="timeRange" class='tog'>

            <!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->

            <!-- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -->
            <table>

                <tr>
                    <td><span class='tog'>
                            <label for="yearMonth">月份：</label>
                            <input type="month" id="yearMonth_1" name="yearMonth">

                            </select>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </span></td>
                    <td><select id="DDDD_1"></td>
                    <!-- <td><select id="UUUU_1"></td> -->
                </tr>
                <!-- <tr>
      <td><span class='tog'>
            <label for="yearMonth">月份2：</label>
            <input type="month" id="yearMonth_2" name="yearMonth">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </span></td>
      <td><select id="DDDD_2"></td>
      <td><select id="UUUU_2"></td>
    </tr>
    <tr>
      <td><span class='tog'>
            <label for="yearMonth">月份3：</label>
            <input type="month" id="yearMonth_3" name="yearMonth">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </span></td>
      <td><select id="DDDD_3"></td>
      <td><select id="UUUU_3"></td>
    </tr>
    <tr>
      <td><span class='tog'>
            <label for="yearMonth">月份4：</label>
            <input type="month" id="yearMonth_4" name="yearMonth">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </span></td>
      <td><select id="DDDD_4"></td>
      <td><select id="UUUU_4"></td>
    </tr> -->
            </table>





            <br>
            &nbsp;&nbsp;&nbsp;<button onclick="saveTimeRange()">尋找</button>


        </div>
        &nbsp;

        <div class="container">
            <!-- 第一列 -->
            <div class="column">
                <div id="Daluen-result_1"></div>
            </div>


        </div>

        <div class="container">
            <!-- 第一列 -->
            <div class="column">
                <div id="Daluen-result_2"></div>
            </div>

            <div class="column">
                <div id="Daluen-result_3"></div>
            </div>

        </div>
        <div class="container">

            <div class="column">
                <div id="Daluen-result_4"></div>
            </div>
        </div>

    </div>
    <!-- </div> -->
    <br></br>








    <!-- <div id="Daluen-result"></div> -->


    <script>
        $("#toggleButton").click(function () {
            // $(".tog").toggle();
            $(".tog").slideToggle("slow");

        });

        function Create_menu(options, options_value, optionsID) {
            // 創建 select 元素
            $(optionsID).empty();
            var selectElement = $(optionsID);
            //   var selectElement = $("<select>").attr("id", "selectedOptionw_machine_number");
            // 定義選項
            //   var options = ["1", "2", "3"];
            // 透過迴圈創建選項並添加到 select 元素中
            for (var i = 0; i < options.length; i++) {
                var optionElement = $("<option>").val(options_value[i]).text(options[i]);
                selectElement.append(optionElement);
            }
            // 創建 label 元素
            //   var labelElement = $("<label>").attr("for", "selected_Calculation_Number").text("機台：");
            // 將 label 和 select 元素添加到現有的 div 中
            $(optionsID).append(selectElement);

        }


        function moveToFront(arr, valueToMove) {
            // 检查值是否存在于数组中
            const index = arr.indexOf(valueToMove);

            // 如果值存在于数组中，将其移动到最前面
            if (index !== -1) {
                arr.splice(index, 1);  // 移除原来的位置
                arr.unshift(valueToMove);  // 将值添加到最前面
            }

            return arr;
        }


        function Create_menu_DDDD(options, options_value, optionsID) {
            // 創建 select 元素
            $(optionsID).empty();
            var selectElement = $(optionsID);
            //   var selectElement = $("<select>").attr("id", "selectedOptionw_machine_number");
            // 定義選項
            //   var options = ["1", "2", "3"];
            // 透過迴圈創建選項並添加到 select 元素中


            <?php

            require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
            use Config\DbMapper;

            $dbMapper = new DbMapper();
            $conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

            $sql = " SELECT distinct(Warehouse) as 'Warehouse'
 FROM [Daluen_aimr106]
 where Warehouse like 'D%'
 order by Warehouse
";
            $stmt = sqlsrv_query($conn, $sql);
            if ($stmt === false) {
                die(print_r(sqlsrv_errors(), true));
            }
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $options[] = $row['Warehouse'];
            }

            sqlsrv_close($conn);
            ?>

            options = <?php echo json_encode($options); ?>;
            options.unshift("D1202+D1204");

            // console.log(optionsID);
            if (optionsID == '#DDDD_1')
                moveToFront(options, 'D1104');
            // if(optionsID=='#DDDD_2')
            //   moveToFront(options,'D1204');

            options_value = options


            for (var i = 0; i < options.length; i++) {
                var optionElement = $("<option>").val(options_value[i]).text(options[i]);
                selectElement.append(optionElement);
            }
            // 創建 label 元素
            //   var labelElement = $("<label>").attr("for", "selected_Calculation_Number").text("機台：");
            // 將 label 和 select 元素添加到現有的 div 中
            $(optionsID).append(selectElement);

        }



        const queryString = window.location.search;

        function Create_menu_UUUU(options, options_value, optionsID, DDDD_value) {
            // 創建 select 元素
            $(optionsID).empty();
            var selectElement = $(optionsID);
            //   var selectElement = $("<select>").attr("id", "selectedOptionw_machine_number");
            // 定義選項
            //   var options = ["1", "2", "3"];
            // 透過迴圈創建選項並添加到 select 元素中

            function ajaxfind_option() {
                $.ajax({

                    url: "NG_Analysis_find_option_ajax.php" + queryString,
                    type: "POST",
                    data: {
                        // selectedOption:selectedOption,
                        // startDateTime:startDateTime,
                        // endDateTime:endDateTime
                        DDDD: DDDD_value

                    },
                    success: function (response) {

                        // options=<?php echo json_encode($options); ?>;
                        // options.unshift("D1202+D1204");

                        options = JSON.parse(response);
                        // console.log(response);
                        // break;
                        // if(optionsID=='#DDDD_1')
                        //   moveToFront(options,'D1202');
                        // if(optionsID=='#DDDD_2')
                        //   moveToFront(options,'D1204');

                        options_value = options


                        for (var i = 0; i < options.length; i++) {
                            var optionElement = $("<option>").val(options_value[i]).text(options[i]);
                            selectElement.append(optionElement);
                        }
                        // 創建 label 元素
                        //   var labelElement = $("<label>").attr("for", "selected_Calculation_Number").text("機台：");
                        // 將 label 和 select 元素添加到現有的 div 中
                        $(optionsID).append(selectElement);


                    },
                    error: function (err) {
                    },
                    complete: function () {


                    }
                });

            }
            ajaxfind_option();

        }

        // function change_UUUU(){
        //   Create_menu_UUUU( [],[],"#UUUU_1",document.getElementById('DDDD_1').value);
        //   Create_menu_UUUU( [],[],"#UUUU_2",document.getElementById('DDDD_2').value);
        //   Create_menu_UUUU( [],[],"#UUUU_3",document.getElementById('DDDD_3').value);
        //   Create_menu_UUUU( [],[],"#UUUU_4",document.getElementById('DDDD_4').value);
        // }


        // 預設的
        var today = new Date();
        // 获取年月份的字符串表示，例如："2024-01"
        var lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());
        var lastlastMonth = new Date(today.getFullYear(), today.getMonth() - 2, today.getDate());
        var lastlastlastMonth = new Date(today.getFullYear(), today.getMonth() - 3, today.getDate());

        // var yearMonthString = today.toISOString().slice(0, 7);
        var lastMonthString = lastMonth.toISOString().slice(0, 7);
        // var lastlastMonthString = lastlastMonth.toISOString().slice(0, 7);
        // var lastlastlastMonthString = lastlastlastMonth.toISOString().slice(0, 7);

        // 设置输入框的默认值
        document.getElementById('yearMonth_1').value = lastMonthString;
        // document.getElementById('yearMonth_2').value = lastlastMonthString;
        // document.getElementById('yearMonth_3').value = lastMonthString;

        // document.getElementById('year_2').value = lastMonthString;
        // document.getElementById('yearMonth_2').value = lastMonthString;
        // document.getElementById('yearMonth_3').value = lastMonthString;
        // document.getElementById('yearMonth_4').value = lastMonthString;


        // Create_menu()
        // Create_menu( ['D1202 + D1204','D1202','D1204','D1101'],['D1202 + D1204','D1202','D1204','D1101'],"#DDDD_1");
        Create_menu_DDDD([], [], "#DDDD_1");
        // Create_menu_DDDD( [],[],"#DDDD_2");
        // Create_menu_DDDD( [],[],"#DDDD_3");
        // Create_menu_DDDD( [],[],"#DDDD_4");
        // change_UUUU()

        // 預設的完



        // 換DDDD的時候換UUUU
        // document.getElementById('DDDD_1').addEventListener('change', function() {change_UUUU()});
        // document.getElementById('DDDD_2').addEventListener('change', function() {change_UUUU()});
        // document.getElementById('DDDD_3').addEventListener('change', function() {change_UUUU()});
        // document.getElementById('DDDD_4').addEventListener('change', function() {change_UUUU()});




        function ajaxCall_1() {
            $.ajax({
                beforeSend: function () {
                    $('.loading').fadeIn(300);
                },
                url: "WMS_Analysis_enquiry_1_ajax.php" + queryString,
                type: "POST",
                data: {
                    // year:document.getElementById('year_2').value,
                    month: document.getElementById('yearMonth_1').value,

                    // DDDD_name:document.getElementById('yearMonth_1').textContent ,
                    DDDD: document.getElementById('DDDD_1').value,
                    // UUUU:document.getElementById('UUUU_1').value


                },
                success: function (response) {
                    $("#Daluen-result_1").html(response);
                },
                error: function (err) {
                    $("#Daluen-result_1").html(err);
                },
                complete: function () {
                    $('.loading').fadeOut(300);


                }
            });

        }


        function ajaxCall_2() {
            $.ajax({
                beforeSend: function () {
                    $('.loading').fadeIn(300);
                },
                url: "WMS_Analysis_enquiry_2_ajax.php" + queryString,
                type: "POST",
                data: {
                    // month:document.getElementById('yearMonth_2').value,
                    // DDDD_name:document.getElementById('yearMonth_1').textContent ,
                    DDDD: document.getElementById('DDDD_1').value,
                    // UUUU:document.getElementById('UUUU_2').value
                    month: document.getElementById('yearMonth_1').value,


                },
                success: function (response) {
                    $("#Daluen-result_2").html(response);
                },
                error: function (err) {
                    $("#Daluen-result_2").html(err);
                },
                complete: function () {
                    $('.loading').fadeOut(300);


                }
            });

        }


        function ajaxCall_3() {
            $.ajax({
                beforeSend: function () {
                    $('.loading').fadeIn(300);
                },
                url: "WMS_Analysis_enquiry_3_ajax.php" + queryString,
                type: "POST",
                data: {
                    // month:document.getElementById('yearMonth_3').value,
                    // DDDD_name:document.getElementById('yearMonth_1').textContent ,
                    DDDD: document.getElementById('DDDD_1').value,
                    // UUUU:document.getElementById('UUUU_3').value
                    month: document.getElementById('yearMonth_1').value,


                },
                success: function (response) {
                    $("#Daluen-result_3").html(response);
                },
                error: function (err) {
                    $("#Daluen-result_3").html(err);
                },
                complete: function () {
                    $('.loading').fadeOut(300);


                }
            });

        }

        function ajaxCall_4() {
            $.ajax({
                beforeSend: function () {
                    $('.loading').fadeIn(300);
                },
                url: "WMS_Analysis_enquiry_4_ajax.php" + queryString,
                type: "POST",
                data: {
                    // month:document.getElementById('yearMonth_3').value,
                    // DDDD_name:document.getElementById('yearMonth_1').textContent ,
                    DDDD: document.getElementById('DDDD_1').value,
                    // UUUU:document.getElementById('UUUU_3').value
                    month: document.getElementById('yearMonth_1').value,


                },
                success: function (response) {
                    $("#Daluen-result_4").html(response);
                },
                error: function (err) {
                    $("#Daluen-result_4").html(err);
                },
                complete: function () {
                    $('.loading').fadeOut(300);


                }
            });

        }


    </script>

    <script>
        function saveTimeRange() {

            ajaxCall_1();
            ajaxCall_2();
            ajaxCall_3();
            ajaxCall_4();
        }


        ajaxCall_1();
        ajaxCall_2();
        ajaxCall_3();
        ajaxCall_4();


    </script>
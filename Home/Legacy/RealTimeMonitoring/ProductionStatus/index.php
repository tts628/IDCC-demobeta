<?php
ini_set('display_errors', '1');

$rootPath = $_SERVER['DOCUMENT_ROOT'];
require_once realpath($rootPath . "/common/language/language.php");
$lang = new Language();
$lang->load("header");
$lang->load("menu");
$lang->load("manufacture");
?>
<!doctype html>
<?php
date_default_timezone_set("Asia/Taipei");
//echo date("Y-m-d H:i:s");
?>
<html>

<head>
    <style>
        .move {
            /* position: relative; */
            /* top: 50px; */
            position: absolute;
            top: 590px;

        }

        .image_LA570_1 {
            position: absolute;
            top: 20px;
            left: 70px;
            width: 1120px;
        }

        .slider img {
            width: 45px;
        }

        .alarm img {
            width: 55px;
            height: 50px;
            position: absolute;
            top: -246px;
            left: -804px;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .mdl-layout {
            height: 100%;
            overflow-y: auto !important;
        }

        .mdl-layout__content {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .page-content {
            flex-grow: 1;
        }
    </style>
    <script src="/common/IDCC_plugin/deprecated/highcharts/highcharts.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/highcharts-more.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/highcharts-3d.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/sankey.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/variable-pie.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/organization.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/cylinder.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/solid-gauge.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/accessibility.js"></script>

    <title>
        <?php echo $lang->line("header.web_title"); ?>
    </title>
    <?php require_once realpath($rootPath . '/common/legacyTemplate/head.php'); ?>
    <script src="/common/IDCC_plugin/bootstrap-5.0.0-dist/js/bootstrap.js"></script>
    <link rel="stylesheet" href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.css">
    <script src="/common/IDCC_plugin/deprecated/DataTables/DataTables/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" type="text/css"
        href="/common/IDCC_plugin/deprecated/DataTables/DataTables/css/jquery.dataTables.css">
</head>

<body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header is-small-screen">
        <main class="">

            <div class="mdl-grid charts">
                <!-- 選單 -->
                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                    <div class="mdl-card mdl-shadow--2dp line-chart">
                        <div class="mdl-card__title">
                            <h1 class="mdl-card__title-text">
                                <?php echo $lang->line("manufacture.manufacture_title"); ?>
                            </h1>
                        </div>
                        <div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--6-col-tablet mdl-cell--12-col-phone">
                            <h4 class="mdl-card__title-text" style="color:white">
                                <?php echo $lang->line("manufacture.manufacture_machineselect"); ?>
                            </h4><br>
                            <select id="machines" style="width:30%">
                                <option value="">
                                    <?php echo $lang->line("manufacture.manufacture_select"); ?>
                                </option>
                                <option value="DyeMachines"selected>
                                    <?php echo $lang->line("manufacture.manufacture_dye"); ?>
                                </option>
                                <option value="FinishMachines">
                                    <?php echo $lang->line("manufacture.manufacture_finish"); ?>
                                </option>
                                <option value="DispenserMachines">
                                    <?php echo $lang->line("manufacture.manufacture_dispenser"); ?>
                                </option>
                            </select>
                            <select id="machine_id" style="width:30%">
                                <option value="0">
                                    <?php echo $lang->line("manufacture.manufacture_select"); ?>
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <!--chart 4-->
                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone"
                    id="content"></div>



            </div>

        </main>
    </div>
    <script>
        var program = '<?php echo $lang->line("manufacture.manufacture_program"); ?>';
        var FabricName = '<?php echo $lang->line("manufacture.manufacture_FabricName"); ?>';
        var TotalWeight = '<?php echo $lang->line("manufacture.manufacture_TotalWeight"); ?>';
        var DyelotStartTime = '<?php echo $lang->line("manufacture.manufacture_DyelotStartTime"); ?>';
        var DyelotEndTime = '<?php echo $lang->line("manufacture.manufacture_DyelotEndTime"); ?>';
        var time_remaining = '<?php echo $lang->line("manufacture.manufacture_time_remaining"); ?>';
        var TotalLength = '<?php echo $lang->line("manufacture.manufacture_TotalLength"); ?>';
        var FinishStartTime = '<?php echo $lang->line("manufacture.manufacture_FinishStartTime"); ?>';
        var FinishEndTime = '<?php echo $lang->line("manufacture.manufacture_FinishEndTime"); ?>';
        var yard = '<?php echo $lang->line("manufacture.manufacture_yard"); ?>';
        var MainPumpSpeed = '<?php echo $lang->line("manufacture.manufacture_MainPumpSpeed"); ?>';
        var ReelSpeed = '<?php echo $lang->line("manufacture.manufacture_ReelSpeed"); ?>';
        var CycleTime = '<?php echo $lang->line("manufacture.manufacture_CycleTime"); ?>';
        var Progress = '<?php echo $lang->line("manufacture.manufacture_Progress"); ?>';
        var Information = '<?php echo $lang->line("manufacture.manufacture_Information"); ?>';
        var MainSpeed = '<?php echo $lang->line("manufacture.manufacture_MainSpeed"); ?>';
        var Width = '<?php echo $lang->line("manufacture.manufacture_Width"); ?>';
        var OverFeed = '<?php echo $lang->line("manufacture.manufacture_OverFeed"); ?>';

        var Measuring = '<?php echo $lang->line("manufacture.manufacture_Measuring"); ?>';
        var Step = '<?php echo $lang->line("manufacture.manufacture_Step"); ?>';
        var ProductCode = '<?php echo $lang->line("manufacture.manufacture_ProductCode"); ?>';
        var TargetGrams = '<?php echo $lang->line("manufacture.manufacture_TargetGrams"); ?>';
        var DispenseGrams = '<?php echo $lang->line("manufacture.manufacture_DispenseGrams"); ?>';
        var ProductName = '<?php echo $lang->line("manufacture.manufacture_ProductName"); ?>';
        var Machine = '<?php echo $lang->line("manufacture.manufacture_Machine"); ?>';
        var Measurement_error = '<?php echo $lang->line("manufacture.manufacture_Measurement_error"); ?>';
        var DispenseSeconds = '<?php echo $lang->line("manufacture.manufacture_DispenseSeconds"); ?>';
        var t = '<?php echo $lang->line("manufacture.manufacture_t"); ?>';
        var time = '<?php echo $lang->line("manufacture.manufacture_time"); ?>';

    </script>
    <script src="./machine2.js"></script>

    <script>
        var init = 1;
        $(document).on('change', '#machines', function () {
            var machines = $('#machines :selected').val();//注意:selected前面有個空格！f
            $.ajax({
                url: "machines_res.php" + queryString,
                method: "POST",
                data: {
                    machines: machines
                },
                success: function (res) {
                    $('#machine_id').html(res);
                    if (init === 1) {
                        $("#machine_id").trigger('change');
                        init = 0;
                    }
                    // console.log(res)
                    //處理回吐的資料
                }
            })//end ajax
        });
        $(document).ready(function () {

            $("#manufacture_order_show").addClass("sub-navigation--show");
            $("#manufacture_order_show_list").addClass("mdl-navigation__link--current");
            $("#manufacture_order_manufacture").addClass("mdl-navigation__link--current");
        });
    </script>

</body>

</html>



<script>

    var timerId2, timerId1;
    function autoRefresh() {
        // console.log("function called");
        $.ajax({
            type: 'POST',
            url: "./get_data_ajax.php" + queryString,
            data: {
                machine_id: $('#machine_id :selected').val()
            },
            success: function (result) {

                // console.log("machine: "+$('#machine_id :selected').val());

                // 獲取輪播元素和圖片元素
                var images1 = document.querySelectorAll('.slider1 img');

                var images2 = document.querySelectorAll('.slider2 img');

                var images3 = document.querySelectorAll('.slider3 img');

                var images4 = document.querySelectorAll('.slider4 img');

                var images5 = document.querySelectorAll('.slider5 img');

                var images6 = document.querySelectorAll('.slider6 img');

                var images7 = document.querySelectorAll('.slider7 img');

                var images8 = document.querySelectorAll('.slider8 img');

                var images9 = document.querySelectorAll('.slider9 img');

                var images10 = document.querySelectorAll('.slider10 img');
                var count = 0;
                // 定義輪播函數
                function slide() {
                    // 隱藏所有圖片
                    for (var i = 0; i < images1.length; i++) {
                        images1[i].style.display = 'none';
                    }
                    for (var i = 0; i < images2.length; i++) {
                        images2[i].style.display = 'none';
                    }
                    for (var i = 0; i < images3.length; i++) {
                        images3[i].style.display = 'none';
                    }
                    for (var i = 0; i < images4.length; i++) {
                        images4[i].style.display = 'none';
                    }
                    for (var i = 0; i < images5.length; i++) {
                        images5[i].style.display = 'none';
                    }
                    for (var i = 0; i < images6.length; i++) {
                        images6[i].style.display = 'none';
                    }
                    for (var i = 0; i < images7.length; i++) {
                        images7[i].style.display = 'none';
                    }
                    for (var i = 0; i < images8.length; i++) {
                        images8[i].style.display = 'none';
                    }
                    for (var i = 0; i < images9.length; i++) {
                        images9[i].style.display = 'none';
                    }
                    for (var i = 0; i < images10.length; i++) {
                        images10[i].style.display = 'none';
                    }
                    if (!images1[index]) {
                        return;
                    }

                    // 顯示當前圖片
                    images1[index].style.display = 'block';
                    images2[index].style.display = 'block';
                    images3[index].style.display = 'block';
                    images4[index].style.display = 'block';
                    images5[index].style.display = 'block';
                    images6[index].style.display = 'block';
                    images7[index].style.display = 'block';
                    images8[index].style.display = 'block';
                    images9[index].style.display = 'block';
                    images10[index].style.display = 'block';
                    // 增加索引值
                    index++;
                    // 如果索引值超出範圍，重置為0
                    if (index >= images1.length) {
                        index = 0;
                    }
                    if (index >= images2.length) {
                        index = 0;
                    }
                    if (index >= images3.length) {
                        index = 0;
                    }
                    if (index >= images4.length) {
                        index = 0;
                    }
                    if (index >= images5.length) {
                        index = 0;
                    }
                    if (index >= images6.length) {
                        index = 0;
                    }
                    if (index >= images7.length) {
                        index = 0;
                    }
                    if (index >= images8.length) {
                        index = 0;
                    }
                    if (index >= images9.length) {
                        index = 0;
                    }
                    if (index >= images10.length) {
                        index = 0;
                    }
                    // 設置定時器，每半秒執行一次輪播函數
                    // setTimeout(slide, 500);
                    count++;
                    if (count < 4) {
                        slideId = setTimeout(function () {
                            // console.log(count);
                            slide();
                        }, 250);
                    }

                }
                // 設置初始索引值
                var index = 0;
                // 隱藏所有圖片
                // for (var i = 0; i < images1.length; i++) {
                //   images1[i].style.display = 'none';
                //   images2[i].style.display = 'none';
                // }
                // // 顯示第一張圖片
                // images1[0].style.display = 'block';
                // images2[0].style.display = 'block';
                // 設置初始索引值
                // var index = 0;
                // 啟動輪播函數
                // let count1 = 0;
                // const slideid = setInterval(() => {
                //   console.log(count1);

                //   count1++;
                //   if (count1 === 3) {
                //     clearInterval(slideid);
                //     console.log("stop");

                //   }
                //   slide();
                // }, 450);

                slide()





                // $('#demo').html(result);
                let data = JSON.parse(result);
                $('#data_MainSpeed').html(data.data_MainSpeed);
                // $('#data_Width').html(data.data_Width);
                $('#data_OverFeed').html(data.data_OverFeed);
                // $('#demo').html(data.data_Job1);
                // $('#demo').html(data.data_Job2);
                // $('#demo').html(data.data_Time);
                $('#lastTemperature1').html(data.lastTemperature1);
                $('#lastTemperature2').html(data.lastTemperature2);
                $('#lastTemperature3').html(data.lastTemperature3);
                $('#lastTemperature4').html(data.lastTemperature4);
                $('#lastTemperature5').html(data.lastTemperature5);
                // $('#lastTemperature5').append("add");

                // console.log("data: "+data.lastTemperature1)
                if (parseInt(data.lastTemperature1) === 0) {



                    $('#statement').html('<div class="alarm" style="position: absolute; top: 248px; left: 917px;"><img src="images/alarm1.png" alt="圖片1"><img src="images/alarm2.png" alt="圖片2"><img src="images/alarm3.png" alt="圖片3"></div>')

                    var alarm = document.querySelectorAll('.alarm img');
                    var count2 = 0;
                    // 定義輪播函數
                    function alarm_fc() {
                        // 隱藏當前圖片之前的所有圖片
                        for (var i = 0; i < index1; i++) {
                            alarm[i].style.display = 'none';

                        }
                        // 隱藏當前圖片之後的所有圖片
                        for (var i = index1 + 1; i < alarm.length; i++) {
                            alarm[i].style.display = 'none';

                        }
                        // 顯示當前圖片
                        alarm[index1].style.display = 'block';

                        // 增加索引值
                        index1++;
                        // 如果索引值超出範圍，重置為0
                        if (index1 >= alarm.length) {
                            index1 = 0;
                        }
                        // 設置定時器，每半秒執行一次輪播函數

                        // timerId2 = setTimeout(function () {
                        //     console.log("123");
                        //     alarm_fc();
                        // }, 500);
                        // setTimeout(alarm_fc, 500);

                        count2++;
                        if (count2 < 3) {
                            alarm_fcid = setTimeout(function () {
                                //   console.log("alarm");
                                alarm_fc();
                            }, 300);
                        }
                    }

                    //                     let count2 = 0;
                    // const alarm_fcid = setInterval(() => {

                    //     // console.log("alarm_fcid" + alarm_fcid);
                    //   count2++;
                    //   if (count2 === 3) {
                    //     clearInterval(alarm_fcid);
                    //     // console.log("Hello");
                    //   }
                    //   alarm_fc();
                    // }, 600);


                    // 設置初始索引值
                    var index1 = 0;
                    // 啟動輪播函數
                    alarm_fc();

                }
                else {
                    $('#statement').html('<div class="alarm" style="display:none; position: absolute; top: 248px; left: 917px;"><img src="images/alarm3.png" alt="圖片1"><img src="images/alarm2.png" alt="圖片2"><img src="images/alarm1.png" alt="圖片3"></div>')

                }
                // 獲取輪播元素和圖片元素


            }

        });
        setTimeout(function () {
            autoRefresh();
            // clearInterval(timerId);
            //   clearTimeout(timerId1);
            //   clearTimeout(timerId2);

        }, 1000);

    }
    autoRefresh();

    $(() => {
        const urlMachines = '<?= $_GET["machines"] ?? '' ?>';
        const $machinesSelect = $('#machines');
        if ($machinesSelect.find('option[value="' + urlMachines + '"]').length > 0 && urlMachines !== '') {
            $machinesSelect.val(urlMachines).trigger('change');
        }
            $machinesSelect.trigger('change');

    })
</script>
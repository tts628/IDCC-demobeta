<script>
    var NW_dye_gantt;
    var updateFlag = true;
</script>
<?php
ini_set('display_errors', 0);
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");
$lang = new Language();
$lang->load("header");
$lang->load("Schedule");
$lang->load("order");
$today = $_GET['date'] ?? date("Y-m-d");
$start = date("Y-m-d", strtotime($today . "-1 day"));
$end = date("Y-m-d", strtotime($today . "1 day"));
$y = date("Y");
$m_1 = date("m");
$m = $m_1 - 1;
$d = date("d");
$d_old = $d - 3;
$d_new = $d + 5;
?>
<!doctype html>
<html>

<head>
<?php require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/common/legacyTemplate/head.php'); ?>

<script src="/common/IDCC_plugin/gantt_chart/codebase/dhtmlxgantt.js?v=7.0.5"></script>
    <link rel="stylesheet" href="/common/IDCC_plugin/gantt_chart/codebase/skins/dhtmlxgantt_material_old.css?v=7.0.5">
    <script src="/common/IDCC_plugin/bootstrap-5.0.0-dist/js/bootstrap.js"></script>
    <link rel="stylesheet" href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.css">
    <script src="/common/IDCC_plugin/sheetjs/xlsx.core.min.js"></script>
    <script src="/common/IDCC_plugin/sheetjs/xlsxStyle.core.min.js"></script>
    <script src="/common/IDCC_plugin/sheetjs/xlsxStyle.utils.js"></script>
    <script src="/common/IDCC_plugin/sheetjs/xlsxExport.utils.js"></script>
    
    <!-- 插件引用 -->
    <!-- <script src="../../common/js/d3.min.js"></script>
    <script src="../../common/js/getmdl-select.min.js"></script>
    <script src="../../common/js/material.min.js"></script>
    <script src="../../common/js/nv.d3.min.js"></script>
    <script src="../../common/js/layout/layout.min.js"></script>
    <script src="../../common/js/scroll/scroll.min.js"></script> -->

    <style>
        table,
        th,
        td {
            border: 1px solid black;
        }

        .mdl-layout--fixed-drawer>.mdl-layout__content {
            margin-left: 0px;
        }

        .hideTask {
            visibility: hidden;
        }

        body {
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            background-size: cover;
            zoom: 70%;
        }

        #gantt {}

        .gantt_task_line {
            border: 0px;
        }

        /* .gantt_task_progress {
    width: 100%!important;
    height: 100%!important;
    } */
        /* .gantt_task_progress {
    background-color: green!important;
    z-index: 99;
    }
    .gantt_task_content{
    background-color: red!important; 

    } */
    </style>
</head>

<body>
    <div class="mdl-card__title">

        <h1 class="mdl-card__title-text">
            <?php echo $lang->line("Schedule.title_DyeSchedule"); ?> Lịch trình đơn hàng làm việc
        </h1>
    </div>
    <button onclick="zoomOut()"
        class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect button--colored-light-blue"
        data-upgraded=",MaterialButton,MaterialRipple">
        Zoom-
        <span class="mdl-button__ripple-container"><span class="mdl-ripple is-animating"
                style="width: 262.161px; height: 262.161px; transform: translate(-50%, -50%) translate(81px, 25px);"></span></span>
    </button>
    <button onclick="zoomIn()"
        class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect button--colored-light-blue"
        data-upgraded=",MaterialButton,MaterialRipple">
        Zoom+
        <span class="mdl-button__ripple-container"><span class="mdl-ripple is-animating"
                style="width: 262.161px; height: 262.161px; transform: translate(-50%, -50%) translate(81px, 25px);"></span></span>
    </button>
    <button onclick="fun(1)"
        class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect button--colored-light-blue"
        data-upgraded=",MaterialButton,MaterialRipple">
        Time-
        <span class="mdl-button__ripple-container"><span class="mdl-ripple is-animating"
                style="width: 262.161px; height: 262.161px; transform: translate(-50%, -50%) translate(81px, 25px);"></span></span>
    </button>
    <button onclick="fun(-1)"
        class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect button--colored-light-blue"
        data-upgraded=",MaterialButton,MaterialRipple">
        Time+
        <span class="mdl-button__ripple-container"><span class="mdl-ripple is-animating"
                style="width: 262.161px; height: 262.161px; transform: translate(-50%, -50%) translate(81px, 25px);"></span></span>
    </button>
    <button id="updateToggler"
        class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect button--colored-light-blue"
        data-upgraded=",MaterialButton,MaterialRipple">
        Update Toggler
        <span class="mdl-button__ripple-container"><span class="mdl-ripple is-animating"
                style="width: 262.161px; height: 262.161px; transform: translate(-50%, -50%) translate(81px, 25px);"></span></span>
    </button>
    <span id="updateStatus"></span>



    <!-- <button onclick="location.href='https://logicartidcc.website/Daluen/manufacture_order/order/DyeScheduleManual1.php'" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect button--colored-light-blue" data-upgraded=",MaterialButton,MaterialRipple">
    D01-D25
    <span class="mdl-button__ripple-container"><span class="mdl-ripple is-animating" style="width: 262.161px; height: 262.161px; transform: translate(-50%, -50%) translate(81px, 25px);"></span></span>
    </button>
    <button onclick="location.href='https://logicartidcc.website/Daluen/manufacture_order/order/DyeScheduleManual2.php'" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect button--colored-light-blue" data-upgraded=",MaterialButton,MaterialRipple">
    D26-D49
    <span class="mdl-button__ripple-container"><span class="mdl-ripple is-animating" style="width: 262.161px; height: 262.161px; transform: translate(-50%, -50%) translate(81px, 25px);"></span></span>
    </button>
    <button onclick="location.href='https://logicartidcc.website/Daluen/manufacture_order/order/DyeScheduleManual3.php'" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect button--colored-light-blue" data-upgraded=",MaterialButton,MaterialRipple">
    D50-D72
    <span class="mdl-button__ripple-container"><span class="mdl-ripple is-animating" style="width: 262.161px; height: 262.161px; transform: translate(-50%, -50%) translate(81px, 25px);"></span></span>
    </button> -->

    <div id="gantt_here" style='padding:0;margin:0;height: 1800px;'></div>

    <script>
        var Machine_name = '<?php echo $lang->line("order.order_Machine_name"); ?>';
        var order_Batch = '<?php echo $lang->line("order.order_Batch"); ?>';//缸號
        var order_FabricName = '<?php echo $lang->line("order.order_FabricName"); ?>';//品名
        var order_ColorCode = '<?php echo $lang->line("order.order_ColorCode"); ?>';//色號
        var order_ColorName = '<?php echo $lang->line("order.order_ColorName"); ?>';//色名
        var order_Customer = '<?php echo $lang->line("order.order_Customer"); ?>';//客戶
        var order_Order = '<?php echo $lang->line("order.order_Order"); ?>';//訂單
        var order_TotalWeight = '<?php echo $lang->line("order.order_TotalWeight"); ?>';//布重
        var order_LiquorRatio = '<?php echo $lang->line("order.order_LiquorRatio"); ?>';//浴比
        var order_TotalVolume = '<?php echo $lang->line("order.order_TotalVolume"); ?>';//總浴量
        var order_BulkedRecpie = '<?php echo $lang->line("order.order_BulkedRecpie"); ?>';//配方
        var order_Program = '<?php echo $lang->line("order.order_Program"); ?>';//染程
        var order_Number = '<?php echo $lang->line("order.order_Number"); ?>';//工單編號
        var order_Startday = '<?php echo $lang->line("order.order_Startday"); ?>';//開始日期
        var order_Endday = '<?php echo $lang->line("order.order_Endday"); ?>';//結束日期
        var order_Progress = '<?php echo $lang->line("order.order_progress"); ?>';//工作進度
        var order_DeliveryTime = '<?php echo $lang->line("order.order_DeliveryTime"); ?>';//交期
        var order_MakeDyelotTime = '<?php echo $lang->line("order.order_MakeDyelotTime"); ?>';//給工單日期
        var order_ColorNo = '<?php echo $lang->line("order.order_ColorNo"); ?>';
        var order_ProductCode = '<?php echo $lang->line("order.order_ProductCode"); ?>';
        var order_YarnLotNo = '<?php echo $lang->line("order.order_YarnLotNo"); ?>';
        var order_ColorShade = '<?php echo $lang->line("order.order_ColorShade"); ?>';




    </script>

    <script src="./NW_DyeScheduleGantt_setting.js"></script>

    <script>
        const queryString = window.location.search;

        // 将时间格式化为所需的格式
        function formatDate(date) {
            var year = date.getFullYear();
            var month = ('0' + (date.getMonth() + 1)).slice(-2);
            var day = ('0' + date.getDate()).slice(-2);
            var hours = ('0' + date.getHours()).slice(-2);
            var minutes = ('0' + date.getMinutes()).slice(-2);
            var seconds = ('0' + date.getSeconds()).slice(-2);
            return year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;
        }

        $(document).ready(function () {
            $('#updateToggler').on('click', function () {
                if (updateFlag) {
                    $('#updateStatus').text("已停止自動更新");
                    clearInterval(NW_dye_gantt);
                    updateFlag = false;
                } else {
                    $('#updateStatus').text("自動更新啟用中...");
                    updateGantt();
                    NW_dye_gantt = setInterval(updateGantt, 5000);
                    updateFlag = true;
                    setTimeout(function () {
                        $('#updateStatus').text('');
                    }, 2000);
                }
            });

            setTimeout(function () {
                window.location.reload();
            }, 600000);

            function updateGantt() {
                var today_date = '<?php echo $today; ?>';
                var today = new Date(today_date);
                var today = new Date();
                // console.log(today);

                gantt.addMarker({
                    start_date: today,
                    css: "today",
                    text: "Today",
                    title: "Today: " + dateToStr(today)
                });
                //母值不顯示
                gantt.templates.task_class = function (start, end, task) {
                    if (task.type == 'project')
                        return "hideTask";
                };
                gantt.config.columns = [
                    { name: "text", label: "<?php echo $lang->line("Schedule.Machine"); ?>", tree: true, width: '90', align: "left" },
                ];
                gantt.init("gantt_here");

                // var start='<?php echo date("Y-m-d H:i:s", strtotime("-24 hours")); ?>';
                // var end='<?php echo date("Y-m-d H:i:s", strtotime("+12 hours")); ?>';
                // console.log(start);
                // console.log(end);

                var currentDate = new Date();
                // var start = formatDate(new Date(currentDate.getTime() - (36 * 60 * 60 * 1000))); // 24 小時前的時間
                // var end = formatDate(new Date(currentDate.getTime() + (18 * 60 * 60 * 1000))); // 12 小時後的時間
                var start = formatDate(new Date(new Date(currentDate.getTime() - (3 * 24 * 60 * 60 * 1000)).setHours(8, 0, 0, 0))); // 3天前的8點
                var end = formatDate(new Date(new Date(currentDate.getTime() + (1 * 24 * 60 * 60 * 1000)).setHours(8, 0, 0, 0))); // 1天後的8點



                // console.log(start);
                // console.log(end);


                // var start = start +' 07:00';
                // var end_time = end +' 24:00';

                gantt.config.start_date = new Date(start);
                gantt.config.end_date = new Date(end);
                // console.log(start)
                // gantt.clearAll();//清除表單
                // gantt.config.start_date = new Date(start);
                // gantt.config.end_date = new Date(end);

                $.ajax({
                    url: "NW_DyeSchedulePage1_ajax.php" + queryString,
                    type: "POST",
                    data: { start: start, end: end },
                    dataType: 'json',
                    success: function (date_select) {
                        gantt.clearAll(); // 清除当前的任务数据
                        gantt.parse({ data: date_select }); // 解析新的任务数据并更新Gantt图

                        gantt.addMarker({
                            start_date: today,
                            css: "today",
                            text: "Today",
                            title: "Today: " + dateToStr(today)
                        });

                        // console.log(today);
                    }
                });
            }

            // 每隔5秒调用一次updateGantt函数
            updateGantt();
            NW_dye_gantt = setInterval(updateGantt, 5000);

        });   
    </script>
    <script>
        //讓排程圖的時間軸可以透過Time+跟Time-兩個按鈕來移動
        function fun(a) {
            var min_date = gantt.getState().min_date;
            var max_date = gantt.getState().max_date;
            if (a == '+1') {
                gantt.config.start_date = min_date = gantt.date.add(min_date, -6, gantt.config.duration_unit);
                gantt.config.end_date = max_date = gantt.date.add(max_date, -6, gantt.config.duration_unit);
                gantt.render()
            }
            if (a == '-1') {
                gantt.config.start_date = min_date = gantt.date.add(min_date, 6, gantt.config.duration_unit);
                gantt.config.end_date = max_date = gantt.date.add(max_date, 6, gantt.config.duration_unit);
                gantt.render()
            }
        }
        function zoomIn() {
            var min_date = gantt.getState().min_date;
            var max_date = gantt.getState().max_date;
            gantt.config.start_date = min_date = gantt.date.add(min_date, 1, gantt.config.duration_unit);
            gantt.config.end_date = max_date = gantt.date.add(max_date, -1, gantt.config.duration_unit);
            gantt.render()
        }
        function zoomOut() {
            var min_date = gantt.getState().min_date;
            var max_date = gantt.getState().max_date;
            gantt.config.start_date = min_date = gantt.date.add(min_date, -1, gantt.config.duration_unit);
            gantt.config.end_date = max_date = gantt.date.add(max_date, 1, gantt.config.duration_unit);
            gantt.render()
        }
    </script>
</body>

</html>
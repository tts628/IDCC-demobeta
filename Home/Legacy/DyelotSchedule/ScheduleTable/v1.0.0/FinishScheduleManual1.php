<?php
session_start();
$rootPath = $_SERVER['DOCUMENT_ROOT'];
$clientRoot = explode('/', $_SERVER["REQUEST_URI"])[1];
require_once realpath($rootPath . "/common/language/language.php");

$lang = new Language();
$lang->load("header");
$lang->load("Schedule");
$lang->load("menu");
$lang->load("order");

$get_today = date("Y-m-d H:i:s");
$y = date("Y");
$m_1 = date("m");
$m = $m_1 - 1;
$d = date("d");
$d_old = $d - 1;
$d_new = $d + 1;
$g = date("G"); // 時
$i = date("i"); // 分
$s = date("s"); // 秒
?>
<!doctype html>
<html>

<head>
    <?php require_once realpath($rootPath . '/common/legacyTemplate/head.php'); ?>
    <!-- 插件引用 -->
    <script src="/common/IDCC_plugin/gantt_chart/codebase/dhtmlxgantt.js?v=7.0.5"></script>
    <link rel="stylesheet" href="/common/IDCC_plugin/gantt_chart/codebase/skins/dhtmlxgantt_material_old.css?v=7.0.5">
    <script src="/common/IDCC_plugin/bootstrap-5.0.0-dist/js/bootstrap.js"></script>
    <link rel="stylesheet" href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.css">
    <script src="/common/IDCC_plugin/sheetjs/xlsx.core.min.js"></script>
    <script src="/common/IDCC_plugin/sheetjs/xlsxStyle.core.min.js"></script>
    <script src="/common/IDCC_plugin/sheetjs/xlsxStyle.utils.js"></script>
    <script src="/common/IDCC_plugin/sheetjs/xlsxExport.utils.js"></script>
    <!-- 插件引用 -->
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        .mdl-layout {
            height: 100%;
            overflow-y: auto;
        }

        .mdl-layout__content {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .page-content {
            flex-grow: 1;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        .hideTask {
            visibility: visible;
        }

        #zoomSliderContainer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 200px;
            background-color: #ffffff;
            border: 1px solid #dddddd;
            border-radius: 4px;
            padding: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 9999;
            display: flex;
            align-items: center;
            transform-origin: bottom right;
            transition: transform 0.2s;
            display: none;

        }

        #zoomSlider {
            -webkit-appearance: none;
            appearance: none;
            height: 5px;
            border-radius: 2px;
            background: #dddddd;
            outline: none;
            opacity: 0.7;
            transition: opacity 0.2s;
            flex: 1;
        }

        @media (max-width: 1080px) {
            #zoomSliderContainer {
                display: flex;
                align-items: center;
            }
        }

        @media (min-width: 2000px) {
            #header {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header is-small-screen">
        <main class="">
            <div id="zoomSliderContainer"><input type="range" id="zoomSlider" min="20" max="120" step="10" value="100">
            </div>
            <div class="mdl-card__title">
                <!-- <img src="../../common/images/Daluen_logo.png" alt="Daluen_logo" height="60" >&nbsp;&nbsp; -->

                <h1 class="mdl-card__title-text">
                    <?php // echo $lang->line("Schedule.title_FinishSchedule"); ?>排程表 ( F01-F04 )
                </h1>
            </div>
            <div id="content">
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
                <button onclick="excelExport1()"
                    class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect button--colored-light-blue"
                    data-upgraded=",MaterialButton,MaterialRipple">
                    <?php echo $lang->line("Schedule.Completed"); ?>
                    <span class="mdl-button__ripple-container"><span class="mdl-ripple is-animating"
                            style="width: 262.161px; height: 262.161px; transform: translate(-50%, -50%) translate(81px, 25px);"></span></span>
                </button>
                <button onclick="excelExport2()"
                    class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect button--colored-light-blue"
                    data-upgraded=",MaterialButton,MaterialRipple">
                    <?php echo $lang->line("Schedule.Incomplete"); ?>
                    <span class="mdl-button__ripple-container"><span class="mdl-ripple is-animating"
                            style="width: 262.161px; height: 262.161px; transform: translate(-50%, -50%) translate(81px, 25px);"></span></span>
                </button>

                <div id="gantt_here" style='padding:0;margin:0;height: 1800px;'></div>
            </div>
        </main>
    </div>
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
    </script>
    <script src="./FinishGroupScheduleGantt_setting.js"></script>
    <script>
        $(document).ready(function () {
            $("#manufacture_order_show").addClass("sub-navigation--show");
            $("#manufacture_order_show_list").addClass("mdl-navigation__link--current");
            $("#manufacture_order_finish").addClass("mdl-navigation__link--current");
        });   
    </script>
    <script>
        var zoomSlider = document.getElementById("zoomSlider");
        var content = document.getElementById("content");
        var wid = document.getElementById('gantt_here');

        zoomSlider.addEventListener("input", function (e) {
            var zoomLevel = e.target.value / 100;
            content.style.transform = "scale(" + zoomLevel + ")";
            //   content.style.zoom = zoomLevel;
            wid.style.width = window.innerWidth / zoomLevel - 100 + 'px';
        });

        content.style.transformOrigin = "top left";



    </script>
    <script>
        const queryString = window.location.search;
        $(document).ready(function () {
            var today = new Date(<?php echo "$y"; ?>, <?php echo "$m"; ?>, <?php echo "$d"; ?>, <?php echo "$g"; ?>, <?php echo "$i"; ?>, <?php echo "$s"; ?>);
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
                { name: "text", label: "<?php echo $lang->line("order.order_Machine_name"); ?>", tree: true, width: '90', align: "left" },
            ];
            gantt.config.start_date = new Date(<?php echo "$y"; ?>, <?php echo "$m"; ?>, <?php echo "$d_old"; ?>);
            gantt.config.end_date = new Date(<?php echo "$y"; ?>, <?php echo "$m"; ?>, <?php echo "$d_new"; ?>);
            gantt.init("gantt_here");

            $.ajax({
                url: "FinishSchedulePage1_ajax.php" + queryString,
                type: "POST",
                dataType: 'json',
                success: function (date_default) {
                    //console.log(date_default)
                    gantt.parse({
                        data: date_default,
                    })
                }
            });

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
    <script>
        // 提取數據
        var ScheduledDyelotsData;
        $.ajax({
            url: "FinishScheduleExcelData_ajax.php" + queryString,
            type: "POST",
            dataType: 'json',
            success: function (ScheduledDyelots) {
                ScheduledDyelotsData = {
                    "success": true,
                    "errorCode": "-1",
                    "msg": "匯出成功",
                    "body": {
                        "title": "未包裝",
                        "excelData": ScheduledDyelots

                    }
                };
            }
        });


        //匯出excel
        function excelExport2() {
            if (ScheduledDyelotsData.success) {
                if (null != ScheduledDyelotsData.body && undefined != ScheduledDyelotsData.body) {
                    //調取封裝方法-匯出excel
                    XSExport.excelExport(
                        ScheduledDyelotsData.body.excelData,
                        ScheduledDyelotsData.body.title
                    );
                }
            }
        }
    </script>
    <script>
        // 提取數據
        var FinishDyelotsData;
        $.ajax({
            url: "FinishFinishExcelData_ajax.php" + queryString,
            type: "POST",
            dataType: 'json',
            success: function (FinishDyelots) {
                FinishDyelotsData = {
                    "success": true,
                    "errorCode": "-1",
                    "msg": "匯出成功",
                    "body": {
                        "title": "已包裝",
                        "excelData": FinishDyelots

                    }
                };
            }
        });


        //匯出excel
        function excelExport1() {
            if (FinishDyelotsData.success) {
                if (null != FinishDyelotsData.body && undefined != FinishDyelotsData.body) {
                    //調取封裝方法-匯出excel
                    XSExport.excelExport(
                        FinishDyelotsData.body.excelData,
                        FinishDyelotsData.body.title
                    );
                }
            }
        }
    </script>
</body>

</html>
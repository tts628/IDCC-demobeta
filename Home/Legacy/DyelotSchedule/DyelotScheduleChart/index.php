<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
require_once ($rootPath . "/common/language/language.php");

$lang = new Language();
$lang->load("header");
$lang->load("menu");
$lang->load("order");

$y = date("Y");
$m_1 = date("m");
$m = $m_1 - 1;
$d = date("d");
$d_old = $d - 3;
$d_new = $d + 1;
?>
<!doctype html>
<html>

<head>
    <title><?php echo $lang->line("header.web_title"); ?></title>
    <?php require_once realpath($rootPath . '/common/legacyTemplate/head.php'); ?>
    <!-- 模板預設 -->

    <!-- 插件引用 -->
    <script src="/common/IDCC_plugin/gantt_chart/codebase/dhtmlxgantt.js?v=7.0.5"></script>
    <link rel="stylesheet" href="/common/IDCC_plugin/gantt_chart/codebase/skins/dhtmlxgantt_material_old.css?v=7.0.5">
    <link rel="stylesheet" href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.css">
    <!-- 插件引用 -->
    <style>
        table,
        th,
        td {
            border: 1px solid black;
        }

        .hideTask {
            visibility: hidden;
        }

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
    </style>
</head>

<body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header is-small-screen">
        <!-- 頁面內容 -->
        <main class="">
            <div class="mdl-grid charts">
                <!-- 選單 -->
                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                    <div class="mdl-card mdl-shadow--2dp line-chart">
                        <div class="mdl-card__title">
                            <h1 class="mdl-card__title-text"><?php echo $lang->line("order.order_title"); ?></h1>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <font><?php echo $lang->line("order.order_look_for_label"); ?>：</font><br>
                            <input class="col-5 col-sm-5 col-md-5 col-lg-2" type="date" id="start_date">
                            <b class="col-1">～</b>
                            <input class="col-5 col-sm-5 col-md-5 col-lg-2" type="date" id="end_date">
                            <br><br>
                            <button
                                class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect button--colored-light-blue"
                                id="show_gantt"><?php echo $lang->line("order.order_show_gantt"); ?></button>
                            <button
                                class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect button--colored-green"
                                id="show_list"><?php echo $lang->line("order.order_show_list"); ?></button>
                            &nbsp;
                            <button
                                class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect button--colored-orange"
                                id="show_today"><?php echo $lang->line("order.order_show_today"); ?></button><br><br>
                            <input type="text" placeholder="<?php echo $lang->line("order.order_search_text"); ?>"
                                id="order_num">
                            <button
                                class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect button--colored-light-blue"
                                id="search_order"><?php echo $lang->line("order.order_look_for_button"); ?></button>
                        </div>
                        <div id="gantt_here" style='height:100vh;padding:0;margin:0;'></div>

                    </div>

                </div>
            </div>
        </main>
        <!-- 頁面內容 -->
    </div>
    <!-- Modal -->
    <div class="modal fade" id="show_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:120vh;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        <?php echo (date("Y-m-d"));
                        echo $lang->line("order.order_Order"); ?>
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="show_modal_data">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal"><?php echo $lang->line("order.order_Colse"); ?></button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal_查詢訂單 -->
    <div class="modal fade" id="show_modal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:120vh;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        <?php echo $lang->line("order.order_order_search"); ?>
                    </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="show_modal_data1">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal"><?php echo $lang->line("order.order_Colse"); ?></button>
                </div>
            </div>
        </div>
    </div>
    <script src="/common/IDCC_plugin/bootstrap-5.0.0-dist/js/bootstrap.js"></script>
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
    </script>
    <script src="./gantt_setting.js"></script>
    <script>
        $(document).ready(function () {
            $("#manufacture_order_show").addClass("sub-navigation--show");
            $("#manufacture_order_show_list").addClass("mdl-navigation__link--current");
            $("#manufacture_order_order").addClass("mdl-navigation__link--current");
        });   
    </script>

    <script>
        const queryString = window.location.search;
        $(document).ready(function () {
            var today = new Date(<?php echo "$y"; ?>, <?php echo "$m"; ?>, <?php echo "$d"; ?>);
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
            gantt.config.end_date = new Date(<?php echo "$y"; ?>, <?php echo "$m"; ?>, <?php echo "$d_new"; ?>)
            gantt.init("gantt_here");

            $.ajax({
                url: "default_ajax.php" + queryString,
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
        //區間查詢
        $("#show_gantt").click(function () {
            var start = $("#start_date").val();
            var end = $("#end_date").val();

            var start_time = start + ' 00:00';
            var end_time = end + ' 24:00';

            gantt.config.start_date = new Date(start_time);
            gantt.config.end_date = new Date(end_time);

            if (start.length == 0 || end == 0) {
                alert('請選擇時間區間!!');
            }
            else {
                gantt.clearAll();//清除表單 
                $.ajax({
                    url: "search_ajax.php" + queryString,
                    type: "POST",
                    data: { start: start, end: end },
                    dataType: 'json',
                    success: function (date_select) {
                        //console.log(date_select)
                        gantt.parse({
                            data: date_select,
                        })
                    }
                });
            }
        });   
    </script>

    <script>
        //工單清單查詢
        $("#show_list").click(function () {
            var start = $("#start_date").val();
            var end = $("#end_date").val();
            $('#show_modal').modal('show')
            $.ajax({
                url: "show_modal.php" + queryString,
                type: "POST",
                data: { start: start, end: end },
                dataType: 'html',
                success: function (data) {
                    $('#show_modal_data').html(data)
                }
            });
        });

        //顯示今天
        $("#show_today").click(function () {
            window.location.reload();
        });

        //特定工單清單查詢
        $("#search_order").click(function () {
            var order_num = $("#order_num").val();
            $('#show_modal1').modal('show')
            $.ajax({
                url: "search_modal.php" + queryString,
                type: "POST",
                data: { order_num: order_num },
                dataType: 'html',
                success: function (data) {
                    console.log(data)
                    $('#show_modal_data1').html(data)
                }
            });
        });    
    </script>
</body>

</html>
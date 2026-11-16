<?php
ini_set('display_errors', '1');
$rootPath = $_SERVER['DOCUMENT_ROOT'];
require_once($rootPath . "/common/language/language.php");
$lang = new Language();
$lang->load("header");
$lang->load("menu");
$lang->load("alarm");
?>
<!doctype html>
<html>

<head>
    <title><?php echo $lang->line("header.web_title"); ?></title>
    <?php require_once($rootPath . '/common/legacyTemplate/head.php'); ?>
    <style>
        .mdl-card__title-text {
            color: #ffffff;
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
    <!-- 插件引用 -->
    <link rel="stylesheet" href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.min.css">
    <script src="/common/IDCC_plugin/deprecated/highcharts/highcharts.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/variable-pie.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/exporting.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/export-data.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/accessibility.js"></script>
    <!-- 插件引用 -->
</head>

<body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header is-small-screen">
        <!-- 頁面內容 -->
        <main class="">
            <div class="mdl-grid charts">
                <!--內容框-->
                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                    <div class="mdl-card mdl-shadow--2dp line-chart">
                        <div class="mdl-card__title">
                            <h1 class="mdl-card__title-text"><?php echo $lang->line("alarm.alarm_title"); ?></h1>
                        </div>
                        <div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--6-col-tablet mdl-cell--12-col-phone">
                            <h4 class="mdl-card__title-text"><?php echo $lang->line("alarm.alarm_factory"); ?></h4>
                            <select id="factory_name" style="width:30%">
                                <option value="DyeMachines"><?php echo $lang->line("alarm.alarm_DyeMachines"); ?>
                                </option>
                                <option value="DispenserMachines">
                                    <?php echo $lang->line("alarm.alarm_DispenserMachines"); ?>
                                </option>
                                <option value="LabMachines"><?php echo $lang->line("alarm.alarm_LabMachines"); ?>
                                </option>
                                <option value="FinishMachines"><?php echo $lang->line("alarm.alarm_FinishMachines"); ?>
                                </option>
                            </select>
                            <h4 class="mdl-card__title-text"><?php echo $lang->line("alarm.alarm_machine"); ?></h4>
                            <select id="machine_name" style="width:30%">
                                <!--                                <option value="全部機台">全部</option>-->
                                <!--                                --><?php
                                //                                require_once('/home/www/html/logic_art/common/database.php');
//                                $sql="select Name from DyeMachines";
//                                $result=sqlsrv_query($link,$sql)or die("sql error".sqlsrv_errors());
//                                while($row=sqlsrv_fetch_array($result)){
//                                    echo "<option value=".$row["Name"].">".$row["Name"]."</option>";
//                                }
//                                ?>
                            </select>
                            <h4 class="mdl-card__title-text"><?php echo $lang->line("alarm.alarm_Date"); ?></h4>
                            <input type="date" id="day" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                </div>
                <!--內容框-->


                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                    <div class="mdl-card mdl-shadow--2dp line-chart">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="today_alarm"></h2>
                        </div>
                        <div style="overflow: auto;">
                            <div id="alarm_p_d"
                                class="mdl-cell mdl-cell--12-col mdl-cell--5-col-desktop mdl-cell--4-col-tablet mdl-cell--12-col-phone"
                                style="float:left">
                            </div>
                            <div id="alarm_b_d"
                                class="mdl-cell mdl-cell--12-col mdl-cell--6-col-desktop mdl-cell--8-col-tablet mdl-cell--12-col-phone">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                    <div class="mdl-card mdl-shadow--2dp line-chart">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="week_alarm"></h2>
                        </div>
                        <div style="overflow: auto;">
                            <div id="alarm_p_w"
                                class="mdl-cell mdl-cell--12-col mdl-cell--5-col-desktop mdl-cell--4-col-tablet mdl-cell--12-col-phone"
                                style="float:left">
                            </div>
                            <div id="alarm_b_w"
                                class="mdl-cell mdl-cell--12-col mdl-cell--6-col-desktop mdl-cell--8-col-tablet mdl-cell--12-col-phone">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                    <div class="mdl-card mdl-shadow--2dp line-chart">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="month_alarm"></h2>
                        </div>
                        <div style="overflow: auto;">
                            <div id="alarm_p_m"
                                class="mdl-cell mdl-cell--12-col mdl-cell--5-col-desktop mdl-cell--4-col-tablet mdl-cell--12-col-phone"
                                style="float:left">
                            </div>
                            <div id="alarm_b_m"
                                class="mdl-cell mdl-cell--12-col mdl-cell--6-col-desktop mdl-cell--8-col-tablet mdl-cell--12-col-phone">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <!-- 頁面內容 -->
    </div>
    <script src="alarm_cahrt.js"></script>


    <!-- Menu欄位顯示 -->
    <script>
        var Daily = '<?php echo $lang->line("alarm.alarm_Daily"); ?>';
        var Weekly = '<?php echo $lang->line("alarm.alarm_Weekly"); ?>';
        var Monthly = '<?php echo $lang->line("alarm.alarm_Monthly"); ?>';
        var machine_name_text = '<?php echo $lang->line("alarm.alarm_all_machine"); ?>';
        $(document).ready(function () {
            const urlFactory = '<?= $_GET["factory"] ?? '' ?>';
            const $factorySelect = $('#factory_name');
            if ($factorySelect.find('option[value="' + urlFactory + '"]').length > 0) {
                $factorySelect.val(urlFactory);
            }

            $("#statistics_report_show").addClass("sub-navigation--show");
            $("#statistics_report_show_list").addClass("mdl-navigation__link--current");
            $("#statistics_report_alarm").addClass("mdl-navigation__link--current");

            var factory_name = $('#factory_name :selected').val();
            var machine_name = 'Allmachine';

            $.ajax({
                url: "ajfactory.php" + queryString,
                method: "POST",
                data: {
                    factory_name: factory_name, machine_name_text: machine_name_text
                },
                success: function (res) {
                    $('#machine_name').html(res);
                    // console.log(res)
                }
            });

            var day = $('#day').val();
            $.ajax({
                url: './ajalarm.php' + queryString,
                type: 'POST',
                dataType: 'json',
                data: { factory_name: factory_name, machine_name: machine_name, day: day },
                success: function (res) {

                    alarm_day_Data(machine_name_text, res[0].data);
                    alarm_week_Data(machine_name_text, res[1].data);
                    alarm_month_Data(machine_name_text, res[2].data);

                    if (machine_name != '') {
                        $('#today_alarm').text(machine_name_text + ' - ' + Daily + '(' + day + ')');
                        $('#week_alarm').text(machine_name_text + ' - ' + Weekly + '(' + day + ')');
                        $('#month_alarm').text(machine_name_text + ' - ' + Monthly + '(' + day + ')');
                    }
                },
                error: function (err) { console.log(err) },
            });
        });
    </script>
    <!-- Menu欄位顯示 -->
</body>

</html>
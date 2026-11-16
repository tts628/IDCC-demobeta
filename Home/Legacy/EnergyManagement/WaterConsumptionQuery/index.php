<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");
$lang = new Language();
$lang->load("header");
$lang->load("menu");
$lang->load("water");
?>
<!doctype html>
<html>

<head>
    <title><?php echo $lang->line("header.web_title"); ?></title>
    <?php require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/common/legacyTemplate/head.php'); ?>
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
    <!-- 模板預設 -->

    <!-- 插件引用 -->
    <script src="/common/IDCC_plugin/deprecated/highcharts/highcharts.js"></script>
    <link href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/common/IDCC_plugin/deprecated/DataTables/DataTables/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" type="text/css"
        href="/common/IDCC_plugin/deprecated/DataTables/DataTables/css/jquery.dataTables.css">
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
                            <h1 class="mdl-card__title-text"><?php echo $lang->line("water.water_title"); ?></h1>
                        </div>
                        <div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--6-col-tablet mdl-cell--12-col-phone">
                            <h4 class="mdl-card__title-text"><?php echo $lang->line("water.water_factory"); ?></h4>
                            <select id="factory_name" style="width:30%">
                                <option value="DyeMachines"><?php echo $lang->line("water.water_DyeMachines"); ?>
                                </option>
                                <!--<option value="FinishMachines"><?php echo $lang->line("water.water_FinishMachines"); ?></option>-->
                            </select>
                            <br>
                            <h4 class="mdl-card__title-text"><?php echo $lang->line("water.water_machine"); ?></h4>
                            <select id="machine_name" style="width:30%">
                            </select>
                            <br>
                            <h4 class="mdl-card__title-text"><?php echo $lang->line("water.water_Date"); ?></h4>
                            <input type="date" id="day" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                </div>
                <!--chart 3-->
                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                    <div class="mdl-card mdl-shadow--2dp line-chart">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="hour_water"></h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div id="water_hour">
                            </div>
                        </div>
                    </div>
                </div>
                <!--chart 4-->
                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                    <div class="mdl-card mdl-shadow--2dp line-chart">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="day_water"></h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div id="water_day">
                            </div>
                        </div>
                    </div>
                </div>
                <!--chart 5-->
                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                    <div class="mdl-card mdl-shadow--2dp line-chart">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="week_water"></h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div id="water_week">
                            </div>
                        </div>
                    </div>
                </div>
                <!--chart 6-->
                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                    <div class="mdl-card mdl-shadow--2dp line-chart">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="month_water"></h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div id="water_month">
                            </div>
                        </div>
                    </div>
                </div>
                <!--內容框-->
            </div>
        </main>
        <!-- 頁面內容 -->
    </div>
    <script>
        const queryString = window.location.search;
        const hour_word = '<?= $lang->line("water.hour"); ?>';
    </script>
    <script src="/common/IDCC_plugin/bootstrap-5.0.0-dist/js/bootstrap.min.js"></script>
    <script src="./water.js"></script>


    <!-- Menu欄位顯示 -->
    <script>
        var machine_name_text = '<?php echo $lang->line("water.water_all_machine"); ?>';//需要php翻譯和修改ajfactory
        var ColdWaterUsage_word = '<?php echo $lang->line("water.ColdWaterUsage"); ?>';
        var HotWaterUsage_word = '<?php echo $lang->line("water.HotWaterUsage"); ?>';
        var RecycledWaterUsage_word = '<?php echo $lang->line("water.RecycledWaterUsage"); ?>';
        // Deprecated, not appearing anywhere on production page content
        // var Water4Usage_word = '<?php echo $lang->line("water.Water4Usage"); ?>'; 
        var Water4Usage_word = '';
        var Hourly = '<?php echo $lang->line("water.water_Hourly"); ?>';
        var Daily = '<?php echo $lang->line("water.water_Daily"); ?>';
        var Weekly = '<?php echo $lang->line("water.water_Weekly"); ?>';
        var Monthly = '<?php echo $lang->line("water.water_Monthly"); ?>';
        var day1;
        var day2;
        var dayname = ['<?php echo $lang->line("water.water_Sun"); ?>',
            '<?php echo $lang->line("water.water_Mon"); ?>',
            '<?php echo $lang->line("water.water_Tue"); ?>',
            '<?php echo $lang->line("water.water_Wed"); ?>',
            '<?php echo $lang->line("water.water_Thu"); ?>',
            '<?php echo $lang->line("water.water_Fri"); ?>',
            '<?php echo $lang->line("water.water_Sat"); ?>'];
        var weekname = ['<?php echo $lang->line("water.water_Week1"); ?>',
            '<?php echo $lang->line("water.water_Week2"); ?>',
            '<?php echo $lang->line("water.water_Week3"); ?>',
            '<?php echo $lang->line("water.water_Week4"); ?>',
            '<?php echo $lang->line("water.water_Week5"); ?>',
            '<?php echo $lang->line("water.water_Week6"); ?>',
            '<?php echo $lang->line("water.water_Week7"); ?>',
            '<?php echo $lang->line("water.water_Week8"); ?>',
            '<?php echo $lang->line("water.water_Week9"); ?>',
            '<?php echo $lang->line("water.water_Week10"); ?>'];
        var monthname = ['<?php echo $lang->line("water.water_Jan"); ?>',
            '<?php echo $lang->line("water.water_Feb"); ?>',
            '<?php echo $lang->line("water.water_Mar"); ?>',
            '<?php echo $lang->line("water.water_Apr"); ?>',
            '<?php echo $lang->line("water.water_May"); ?>',
            '<?php echo $lang->line("water.water_Jun"); ?>',
            '<?php echo $lang->line("water.water_Jul"); ?>',
            '<?php echo $lang->line("water.water_Aug"); ?>',
            '<?php echo $lang->line("water.water_Sep"); ?>',
            '<?php echo $lang->line("water.water_Oct"); ?>',
            '<?php echo $lang->line("water.water_Nov"); ?>',
            '<?php echo $lang->line("water.water_Dec"); ?>'];
        var month_name = '<?php echo $lang->line("water.water_month"); ?>';
        $(document).ready(function () {
            $("#factory_show").addClass("sub-navigation--show");
            $("#factory_show_list").addClass("mdl-navigation__link--current");
            $("#factory_water").addClass("mdl-navigation__link--current");

            var factory_name = $('#factory_name :selected').val();
            $.ajax({
                url: "ajfactory.php" + queryString,
                method: "POST",
                data: {
                    factory_name: factory_name, machine_name_text: machine_name_text
                },
                success: function (res) {
                    $('#machine_name').html(res);
                }
            });

            var machine_name = 'Allmachine';
            // var machine_name_text = '全部機台';
            // var machine_name = $('#machine_name').val();
            // var day = $('#day').val();
            var day = $('#day').val();
            $.ajax({
                url: './ajwater.php' + queryString,
                type: 'POST',
                dataType: 'json',
                data: { factory_name: factory_name, machine_name: machine_name, day: day, dayname: dayname, weekname: weekname, monthname: monthname, month_name: month_name },
                success: function (res) {
                    // console.log(res[0].data);
                    day1 = res[4].data[0]['name'];
                    day2 = res[4].data[6]['name'];
                    day1 = day1.substring(0, day1.length - 5);
                    day2 = day2.substring(0, day2.length - 5);
                    // console.log(res[3].data);
                    water_hour_Data(machine_name_text, res[0].data, res[1].data, res[2].data, res[3], res[16].hour, ColdWaterUsage_word, HotWaterUsage_word, RecycledWaterUsage_word, Water4Usage_word);
                    water_day_Data(machine_name_text, res[4].data, res[5].data, res[6].data, res[7], res[16].day, ColdWaterUsage_word, HotWaterUsage_word, RecycledWaterUsage_word, Water4Usage_word);
                    water_week_Data(machine_name_text, res[8].data, res[9].data, res[10].data, res[11], res[16].week, ColdWaterUsage_word, HotWaterUsage_word, RecycledWaterUsage_word, Water4Usage_word);
                    water_month_Data(machine_name_text, res[12].data, res[13].data, res[14].data, res[15], res[16].month, ColdWaterUsage_word, HotWaterUsage_word, RecycledWaterUsage_word, Water4Usage_word);
                    if (machine_name != '') {
                        $('#hour_water').text(machine_name_text + ' - ' + '<?php echo $lang->line("water.water_Hourly"); ?>' + '(' + day + ')');
                        $('#day_water').text(machine_name_text + ' - ' + '<?php echo $lang->line("water.water_Daily"); ?>' + '(' + day1 + ':' + day2 + ')');
                        day = day.substring(0, day.length - 3);
                        $('#week_water').text(machine_name_text + ' - ' + '<?php echo $lang->line("water.water_Weekly"); ?>' + '(' + day + ')');
                        day = day.substring(0, day.length - 3);
                        $('#month_water').text(machine_name_text + ' - ' + '<?php echo $lang->line("water.water_Monthly"); ?>' + '(' + day + ')');
                    }
                },
                error: function (err) {
                    console.log(err)
                },
            });
            var machine_name = $('#machine_name').val();

        });
    </script>
    <!-- Menu欄位顯示 -->
</body>

</html>
<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");
$lang = new Language();
$lang->load("header");
$lang->load("menu");
$lang->load("energy");
// require_once('/home/www/html/logic_art/common/database.php');
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
                            <h1 class="mdl-card__title-text"><?php echo $lang->line("energy.energy_title"); ?></h1>
                        </div>
                        <div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--6-col-tablet mdl-cell--12-col-phone">
                            <h4 class="mdl-card__title-text"><?php echo $lang->line("energy.energy_factory"); ?></h4>
                            <select id="factory_name" style="width:30%">
                                <option value="DyeMachines"><?php echo $lang->line("energy.energy_DyeMachines"); ?>
                                </option>
                                <!-- <option value="FinishMachines"><?php echo $lang->line("energy.energy_FinishMachines"); ?></option> -->
                            </select>
                            <br>
                            <h4 class="mdl-card__title-text"><?php echo $lang->line("energy.energy_machine"); ?></h4>
                            <select id="machine_name" style="width:30%">
                                <!-- <option value="Allmachine">全部機台</option> -->
                            </select>
                            <br>
                            <h4 class="mdl-card__title-text"><?php echo $lang->line("energy.energy_Date"); ?></h4>
                            <input type="date" id="day" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                </div>
                <!--chart 3-->
                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                    <div class="mdl-card mdl-shadow--2dp line-chart">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="hour_energy"></h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div id="energy_hour">
                            </div>
                        </div>
                    </div>
                </div>
                <!--chart 4-->
                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                    <div class="mdl-card mdl-shadow--2dp line-chart">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="day_energy"></h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div id="energy_day">
                            </div>
                        </div>
                    </div>
                </div>
                <!--chart 5-->
                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                    <div class="mdl-card mdl-shadow--2dp line-chart">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="week_energy"></h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div id="energy_week">
                            </div>
                        </div>
                    </div>
                </div>
                <!--chart 6-->
                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                    <div class="mdl-card mdl-shadow--2dp line-chart">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="month_energy"></h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div id="energy_month">
                            </div>
                        </div>
                    </div>
                </div>
                <!--內容框-->
            </div>
        </main>
        <!-- 頁面內容 -->
    </div>
    <script src="/common/IDCC_plugin/bootstrap-5.0.0-dist/js/bootstrap.min.js"></script>
    <script>
        const queryString = window.location.search;
    </script>
    <!-- Menu欄位顯示 -->
    <script>
        //一開始讀取廠區,ajax廠區機台,連動到select去,但抓不到連動的機台,因此寫死machine_name
        //原因$( document ).ready(function()=>跑完後才把Ajax添加到select,因此無法抓取

        //讀取廠區顯示長條圖
        var machine_name_text = '<?php echo $lang->line("energy.energy_all_machine"); ?>';//需要php翻譯和修改ajfactory
        var Water_word = '<?php echo $lang->line("energy.energy_Water"); ?>';
        var Steam_word = '<?php echo $lang->line("energy.energy_Steam"); ?>';
        var Power_word = '<?php echo $lang->line("energy.energy_Power"); ?>';
        var Hourly = '<?php echo $lang->line("energy.energy_Hourly"); ?>';
        var Daily = '<?php echo $lang->line("energy.energy_Daily"); ?>';
        var Weekly = '<?php echo $lang->line("energy.energy_Weekly"); ?>';
        var Monthly = '<?php echo $lang->line("energy.energy_Monthly"); ?>';
        var day1;
        var day2;
        var dayname = ['<?php echo $lang->line("energy.energy_Sun"); ?>',
            '<?php echo $lang->line("energy.energy_Mon"); ?>',
            '<?php echo $lang->line("energy.energy_Tue"); ?>',
            '<?php echo $lang->line("energy.energy_Wed"); ?>',
            '<?php echo $lang->line("energy.energy_Thu"); ?>',
            '<?php echo $lang->line("energy.energy_Fri"); ?>',
            '<?php echo $lang->line("energy.energy_Sat"); ?>'];
        var weekname = ['<?php echo $lang->line("energy.energy_Week1"); ?>',
            '<?php echo $lang->line("energy.energy_Week2"); ?>',
            '<?php echo $lang->line("energy.energy_Week3"); ?>',
            '<?php echo $lang->line("energy.energy_Week4"); ?>',
            '<?php echo $lang->line("energy.energy_Week5"); ?>',
            '<?php echo $lang->line("energy.energy_Week6"); ?>',
            '<?php echo $lang->line("energy.energy_Week7"); ?>',
            '<?php echo $lang->line("energy.energy_Week8"); ?>',
            '<?php echo $lang->line("energy.energy_Week9"); ?>',
            '<?php echo $lang->line("energy.energy_Week10"); ?>'];
        var monthname = ['<?php echo $lang->line("energy.energy_Jan"); ?>',
            '<?php echo $lang->line("energy.energy_Feb"); ?>',
            '<?php echo $lang->line("energy.energy_Mar"); ?>',
            '<?php echo $lang->line("energy.energy_Apr"); ?>',
            '<?php echo $lang->line("energy.energy_May"); ?>',
            '<?php echo $lang->line("energy.energy_Jun"); ?>',
            '<?php echo $lang->line("energy.energy_Jul"); ?>',
            '<?php echo $lang->line("energy.energy_Aug"); ?>',
            '<?php echo $lang->line("energy.energy_Sep"); ?>',
            '<?php echo $lang->line("energy.energy_Oct"); ?>',
            '<?php echo $lang->line("energy.energy_Nov"); ?>',
            '<?php echo $lang->line("energy.energy_Dec"); ?>'];
        var month_name = '<?php echo $lang->line("energy.energy_month"); ?>';
        $(document).ready(function () {
            $("#factory_show").addClass("sub-navigation--show");
            $("#factory_show_list").addClass("mdl-navigation__link--current");
            $("#factory_energy").addClass("mdl-navigation__link--current");

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

            // var machine_name = $('#machine_name option:first').val();
            // var machine_name_text = $('#machine_name').text();
            var day = $('#day').val();
            $.ajax({
                url: './ajenergy.php' + queryString,
                type: 'POST',
                dataType: 'json',
                data: { factory_name: factory_name, machine_name: machine_name, day: day, dayname: dayname, weekname: weekname, monthname: monthname, month_name: month_name },
                success: function (res) {
                    // console.log(res);
                    day1 = res[3].data[0]['name'];
                    day2 = res[3].data[6]['name'];
                    day1 = day1.substring(0, day1.length - 5);
                    day2 = day2.substring(0, day2.length - 5);
                    energy_hour_Data(machine_name_text, res[0].data, res[1].data, res[2].data, res[12].hour, Water_word, Steam_word, Power_word);
                    energy_day_Data(machine_name_text, res[3].data, res[4].data, res[5].data, res[12].day, Water_word, Steam_word, Power_word);
                    energy_week_Data(machine_name_text, res[6].data, res[7].data, res[8].data, res[12].week, Water_word, Steam_word, Power_word);
                    energy_month_Data(machine_name_text, res[9].data, res[10].data, res[11].data, res[12].month, Water_word, Steam_word, Power_word);
                    if (machine_name != '') {
                        $('#hour_energy').text(machine_name_text + ' - ' + '<?php echo $lang->line("energy.energy_Hourly"); ?>' + '(' + day + ')');
                        $('#day_energy').text(machine_name_text + ' - ' + '<?php echo $lang->line("energy.energy_Daily"); ?>' + '(' + day1 + ':' + day2 + ')');
                        day = day.substring(0, day.length - 3);
                        $('#week_energy').text(machine_name_text + ' - ' + '<?php echo $lang->line("energy.energy_Weekly"); ?>' + '(' + day + ')');
                        day = day.substring(0, day.length - 3);
                        $('#month_energy').text(machine_name_text + ' - ' + '<?php echo $lang->line("energy.energy_Monthly"); ?>' + '(' + day + ')');
                    }
                },
                error: function (err) { console.log(err) },
            });
        });
        //讀取廠區顯示長條圖
    </script>
    <script src="./energy.js"></script>
    <!-- Menu欄位顯示 -->
</body>

</html>
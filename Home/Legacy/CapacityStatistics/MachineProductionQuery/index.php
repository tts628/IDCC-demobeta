<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
require_once realpath($rootPath . "/common/language/language.php");
$lang = new Language();
$lang->load("header");
$lang->load("menu");
$lang->load("production");
//ini_set('display_errors','1');
//error_reporting(E_ALL);

?>
<!doctype html>
<html>

<head>
    <title><?php echo $lang->line("header.web_title"); ?></title>
    <?php require_once realpath($rootPath . '/common/legacyTemplate/head.php'); ?>
    <link rel="stylesheet" href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.css">
    <script src="/common/IDCC_plugin/deprecated/highcharts/highcharts.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/exporting.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/export-data.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/accessibility.js"></script>
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
        <main class="">
            <div class="mdl-grid ui-tables">
                <!--            <div class="mdl-grid charts">-->

                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--4-col-phone">
                    <div class="mdl-card mdl-shadow--2dp line-chart"">
                    <div class=" mdl-card__title">
                        <h1 class="mdl-card__title-text"><?php echo $lang->line("production.production_statistics"); ?>
                        </h1>
                    </div>
                    <div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--6-col-tablet mdl-cell--12-col-phone">
                        <h4 class="mdl-card__title-text"><?php echo $lang->line("production.production_factory"); ?>
                        </h4>
                        <select id="factory_name" style="width:30%">
                            <option value="DyeMachines"><?php echo $lang->line("production.production_DyeMachines"); ?>
                            </option>
                            <!--<option value="DispenserMachines"><?php echo $lang->line("production.production_DispenserMachines"); ?></option>
                        <option value="LabMachines"><?php echo $lang->line("production.production_LabMachines"); ?></option>-->
                            <!--供料系統與化驗無產量-->
                            <option value="FinishMachines">
                                <?php echo $lang->line("production.production_FinishMachines"); ?>
                            </option>
                        </select>
                        <h4 class="mdl-card__title-text"><?php echo $lang->line("production.production_machine"); ?>
                        </h4>
                        <select id="machine_name" style="width:30%">
                            <option value="全部機台">全部</option>
                            <!--                        --><?php
                            //                        $sql="select Name from DyeMachines";
//                        $result=sqlsrv_query($link,$sql)or die("sql error".sqlsrv_errors());
//                        while($row=sqlsrv_fetch_array($result)){
//                            echo "<option value=".$row["Name"].">".$row["Name"]."</option>";
//                        }
//                        ?>
                        </select>
                        <h4 class="mdl-card__title-text"><?php echo $lang->line("production.production_Date"); ?></h4>
                        <input type="date" id="day" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
            </div>
            <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                <div class="mdl-card mdl-shadow--2dp line-chart">
                    <div class="mdl-card__title">
                        <h2 class="mdl-card__title-text" id="today_capacity"></h2>
                    </div>
                    <div class="mdl-card__supporting-text">
                        <div id="capacity_D">
                        </div>
                    </div>
                </div>
            </div>
            <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                <div class="mdl-card mdl-shadow--2dp line-chart">
                    <div class="mdl-card__title">
                        <h2 class="mdl-card__title-text" id="week_capacity"></h2>
                    </div>
                    <div class="mdl-card__supporting-text">
                        <div id="capacity_week">
                        </div>
                    </div>
                </div>
            </div>
            <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                <div class="mdl-card mdl-shadow--2dp line-chart">
                    <div class="mdl-card__title">
                        <h2 class="mdl-card__title-text" id="month_capacity"></h2>
                    </div>
                    <div class="mdl-card__supporting-text">
                        <div id="capacity_month">
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </main>
    </div>
    <script src="./capacity_chart.js"></script><!-- 
<script src="/logic_art/statistics_report/maintenance/table.min.js"></script> -->
    <script>
        $(() => {
            const urlFactory = '<?= $_GET["factory"] ?? '' ?>';
            const $factorySelect = $('#factory_name');
            if ($factorySelect.find('option[value="' + urlFactory + '"]').length > 0) {
                $factorySelect.val(urlFactory);
            }
        })

        if (queryString.includes("stage=finishing")) {
            $("#factory_name").val("FinishMachines");
        }

        var machine_name_text = '<?php echo $lang->line("production.production_all_machine"); ?>';
        var Daily = '<?php echo $lang->line("production.production_Daily"); ?>';
        var Weekly = '<?php echo $lang->line("production.production_Weekly"); ?>';
        var Monthly = '<?php echo $lang->line("production.production_Monthly"); ?>';
        var dayname = ['<?php echo $lang->line("production.production_Sun"); ?>',
            '<?php echo $lang->line("production.production_Mon"); ?>',
            '<?php echo $lang->line("production.production_Tue"); ?>',
            '<?php echo $lang->line("production.production_Wed"); ?>',
            '<?php echo $lang->line("production.production_Thu"); ?>',
            '<?php echo $lang->line("production.production_Fri"); ?>',
            '<?php echo $lang->line("production.production_Sat"); ?>'];
        var weekname = ['<?php echo $lang->line("production.production_Week1"); ?>',
            '<?php echo $lang->line("production.production_Week2"); ?>',
            '<?php echo $lang->line("production.production_Week3"); ?>',
            '<?php echo $lang->line("production.production_Week4"); ?>',
            '<?php echo $lang->line("production.production_Week5"); ?>',
            '<?php echo $lang->line("production.production_Week6"); ?>',
            '<?php echo $lang->line("production.production_Week7"); ?>',
            '<?php echo $lang->line("production.production_Week8"); ?>',
            '<?php echo $lang->line("production.production_Week9"); ?>',
            '<?php echo $lang->line("production.production_Week10"); ?>'];
        var monthname = ['<?php echo $lang->line("production.production_Jan"); ?>',
            '<?php echo $lang->line("production.production_Feb"); ?>',
            '<?php echo $lang->line("production.production_Mar"); ?>',
            '<?php echo $lang->line("production.production_Apr"); ?>',
            '<?php echo $lang->line("production.production_May"); ?>',
            '<?php echo $lang->line("production.production_Jun"); ?>',
            '<?php echo $lang->line("production.production_Jul"); ?>',
            '<?php echo $lang->line("production.production_Aug"); ?>',
            '<?php echo $lang->line("production.production_Sep"); ?>',
            '<?php echo $lang->line("production.production_Oct"); ?>',
            '<?php echo $lang->line("production.production_Nov"); ?>',
            '<?php echo $lang->line("production.production_Dec"); ?>'];
        var month_name = '<?php echo $lang->line("production.production_month"); ?>';
        $(document).ready(function () {
            $("#statistics_report_show").addClass("sub-navigation--show");
            $("#statistics_report_show_list").addClass("mdl-navigation__link--current");
            $("#statistics_report_capacity").addClass("mdl-navigation__link--current");

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
                url: './ajcapacity.php' + queryString,
                type: 'POST',
                dataType: 'json',
                data: { factory_name: factory_name, machine_name: machine_name, day: day, dayname: dayname, weekname: weekname, monthname: monthname, month_name: month_name },
                success: function (res) {
                    // console.log(res[0].data);

                    capacity_day_Data(machine_name_text, res[0].data);
                    capacity_week_Data(machine_name_text, res[1].data);
                    capacity_month_Data(machine_name_text, res[2].data);

                    if (machine_name != '') {
                        $('#today_capacity').text(machine_name_text + ' - ' + Daily + '(' + day + ')');
                        $('#week_capacity').text(machine_name_text + ' - ' + Weekly + '(' + day + ')');
                        $('#month_capacity').text(machine_name_text + ' - ' + Monthly + '(' + day + ')');
                    }
                },
                error: function (err) { console.log(err) },
            });

        });
    </script>

</body>

</html>
<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");
$lang = new Language();
$lang->load("header");
$lang->load("menu");
$lang->load("color");


ini_set('display_errors', '1');
error_reporting(E_ALL);
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
    <script src="/common/IDCC_plugin/bootstrap-5.0.0-dist/js/bootstrap.min.js"></script>
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
                            <h1 class="mdl-card__title-text"><?php echo $lang->line("color.color_title"); ?></h1>
                        </div>
                        <div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--6-col-tablet mdl-cell--12-col-phone">
                            <h4 class="mdl-card__title-text"><?php echo $lang->line("color.color_machine"); ?></h4>
                            <select id="machine_name" style="width:30%">
                                <?php
                                require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
                                use Config\DbMapper;

                                $dbMapper = new DbMapper();
                                $link = $dbMapper->getLink(['IDCC']);

                                $sql_top = "select TOP 1 Name from DyeMachines  order by Name ASC ";
                                $result_top = sqlsrv_query($link, $sql_top) or die("sql error" . sqlsrv_errors());
                                $row_top = sqlsrv_fetch_array($result_top);
                                $first_machine = $row_top['Name'];
                                echo ' <option value=' . $first_machine . '>' . $first_machine . '</option>';

                                $sql = "select Name from DyeMachines where Name !='$first_machine' order by Name ASC ";
                                $result = sqlsrv_query($link, $sql) or die("sql error" . sqlsrv_errors());
                                while ($row = sqlsrv_fetch_array($result)) {
                                    echo "<option value=" . $row["Name"] . ">" . $row["Name"] . "</option>";
                                }
                                ?>
                            </select>
                            <br>
                            <h4 class="mdl-card__title-text"><?php echo $lang->line("color.color_date"); ?></h4>
                            <input type="date" id="day" value="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                </div>
                <!--內容框-->


                <!--            bar1 start-->
                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                    <div class="mdl-card mdl-shadow--2dp line-chart">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="week_text"></h2>
                        </div>
                        <div id="bar1"></div>

                    </div>
                </div>
                <!--            bar1 end-->

                <!--            bar2 start-->
                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                    <div class="mdl-card mdl-shadow--2dp line-chart">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="week_text_2"></h2>
                        </div>
                        <div id="bar2"></div>

                    </div>
                </div>
                <!--            bar2 end-->


                <!--            table start-->


                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                    <div class="mdl-card mdl-shadow--2dp line-chart">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text"><?php echo $lang->line("color.color_data"); ?></h2>
                        </div>

                        <!--            table start-->
                        <div class="row">
                            <div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--6-col-tablet mdl-cell--12-col-phone">
                                <div class="card_change">
                                    <div class="card">
                                        <ul class="list-group">
                                            <li id="table1" class="list-group-item list-group-item-success"
                                                style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff">
                                            </li>
                                            <li id="table2" class="list-group-item list-group-item-success"
                                                style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff">
                                            </li>
                                            <li id="table3" class="list-group-item list-group-item-success"
                                                style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff">
                                            </li>
                                            <li id="table4" class="list-group-item list-group-item-success"
                                                style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff">
                                            </li>
                                            <li id="table5" class="list-group-item list-group-item-success"
                                                style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff">
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!--            table end-->
                            <div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--6-col-tablet mdl-cell--12-col-phone">
                                <div class="card_change">
                                    <div class="card">
                                        <ul class="list-group">
                                            <li id="table6" class="list-group-item list-group-item-success"
                                                style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff">
                                            </li>
                                            <li id="table7" class="list-group-item list-group-item-success"
                                                style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff">
                                            </li>
                                            <li id="table8" class="list-group-item list-group-item-success"
                                                style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff">
                                            </li>
                                            <li id="table9" class="list-group-item list-group-item-success"
                                                style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff">
                                            </li>
                                            <li id="table10" class="list-group-item list-group-item-success"
                                                style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff">
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <!--            table end-->

            </div>
        </main>
        <!-- 頁面內容 -->
    </div>

    <!-- Menu欄位顯示 -->
    <script>
        const queryString = window.location.search;

        //翻譯
        var activation_daily_text = '<?php echo $lang->line("color.color_activation_daily"); ?>';
        var activation_text = '<?php echo $lang->line("color.color_activation"); ?>';

        var daily_text = '<?php echo $lang->line("color.color_data_daily"); ?>';

        var work_text = '<?php echo $lang->line("color.color_work"); ?>';
        var stop_text = '<?php echo $lang->line("color.color_stop"); ?>';
        var alarm_text = '<?php echo $lang->line("color.color_alarm"); ?>';
        var fix_text = '<?php echo $lang->line("color.color_fix"); ?>';


        $(document).ready(function () {
            //顯示清單
            $("#equipment_show").addClass("sub-navigation--show");
            $("#equipment_show_list").addClass("mdl-navigation__link--current");
            $("#equipment_coloring").addClass("mdl-navigation__link--current");

            $("#machine_name").change(function () {
                var machine_name = $('#machine_name').val();
                var machine_name_text = $('#machine_name').find("option:selected").text();
                var day = $('#day').val();
                $.ajax({
                    url: './chart_ajax.php' + queryString,
                    type: 'POST',
                    dataType: 'json',
                    data: { machine_name: machine_name, day: day },
                    success: function (res) {
                        // console.log(res[1].data);
                        activation_data(machine_name_text, res[0].data, activation_text);

                        var day = [res[1].data[0].name, res[1].data[1].name, res[1].data[2].name, res[1].data[3].name, res[1].data[4].name, res[1].data[5].name, res[1].data[6].name];
                        four_data(machine_name_text, day, res[1].data[7].work, res[1].data[7].stop, res[1].data[7].fix, res[1].data[7].alarm, work_text, stop_text, fix_text, alarm_text);

                    },
                    error: function (err) {
                        console.log(err)
                    },
                });
                var machine_name = $('#machine_name').val();
                if (machine_name != '') {
                    $('#week_text').text(machine_name_text + ' - ' + activation_daily_text);
                    $('#week_text_2').text(machine_name_text + ' - ' + daily_text);
                }
                // -------------------------------------------------------------------------------------------
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: "./get_table.php" + queryString,
                    data: { machine_name: machine_name },
                    async: false,
                    cache: false,

                    success: function (res_table) {
                        // console.log(res_table);

                        show_table(res_table);

                        // console.log(res_table);

                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(xhr.responseText);
                    }
                });
            });

            $("#day").change(function () {
                var machine_name = $('#machine_name').val();
                var machine_name_text = $('#machine_name').find("option:selected").text();
                var day = $('#day').val();
                $.ajax({
                    url: './chart_ajax.php' + queryString,
                    type: 'POST',
                    dataType: 'json',
                    data: { machine_name: machine_name, day: day },
                    success: function (res) {
                        activation_data(machine_name_text, res[0].data, activation_text);

                        var day = [res[1].data[0].name, res[1].data[1].name, res[1].data[2].name, res[1].data[3].name, res[1].data[4].name, res[1].data[5].name, res[1].data[6].name];
                        four_data(machine_name_text, day, res[1].data[7].work, res[1].data[7].stop, res[1].data[7].fix, res[1].data[7].alarm, work_text, stop_text, fix_text, alarm_text);
                    },
                    error: function (err) {
                        console.log(err)
                    },
                });
                var machine_name = $('#machine_name').val();
                if (machine_name != '') {
                    $('#week_text').text(machine_name_text + ' - ' + activation_daily_text);
                    $('#week_text_2').text(machine_name_text + ' - ' + daily_text);

                    // -------------------------------------------------------------------------------------------
                }
            });


            var machine_name = $('#machine_name').val();
            var machine_name_text = $('#machine_name').find("option:selected").text();
            var day = $('#day').val();
            $.ajax({
                url: './chart_ajax.php' + queryString,
                type: 'POST',
                dataType: 'json',
                data: { machine_name: machine_name, day: day },
                success: function (res) {
                    // console.log(res[1].data[7].work);
                    activation_data(machine_name_text, res[0].data, activation_text);

                    var day = [res[1].data[0].name, res[1].data[1].name, res[1].data[2].name, res[1].data[3].name, res[1].data[4].name, res[1].data[5].name, res[1].data[6].name];
                    four_data(machine_name_text, day, res[1].data[7].work, res[1].data[7].stop, res[1].data[7].fix, res[1].data[7].alarm, work_text, stop_text, fix_text, alarm_text);
                },
                error: function (err) {
                    console.log(err);
                },
            });
            var machine_name = $('#machine_name').val();
            if (machine_name != '') {
                $('#week_text').text(machine_name_text + ' - ' + activation_daily_text);
                $('#week_text_2').text(machine_name_text + ' - ' + daily_text);

            }

            // TABLE START-------------------------------


            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: "./get_table.php" + queryString,
                data: { machine_name: machine_name },
                async: false,
                cache: false,

                success: function (res_table) {
                    // console.log(res_table);

                    show_table(res_table);

                    // console.log(res_table);

                    setInterval(function () {

                        show_table(res_table);

                    }, 5000);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.responseText);
                }
            });


            // TABLE1 END-------------------------------
        });

        function show_table(res_table) {
            var page = 1;
            for (i = 0; i < 20; i++) {
                var name = (res_table[i].name);
                var date = (res_table[i].date);
                var description = (res_table[i].description);
                var note = (res_table[i].note);

                var content = name + '：' + description + note + date;
                var state = (res_table[i].state);

                if (state == '3') {//停機
                    $('#table' + page).css('backgroundColor', '#00BCD4');
                }
                if (state == '4') {//運行
                    $('#table' + page).css('backgroundColor', '#00D659');
                }
                if (state == '6') {//維修
                    $('#table' + page).css('backgroundColor', '#FFC107');
                }
                if (state == '5' || state == '1' || state == '2') {//5故障,1警報,2延遲
                    $('#table' + page).css('backgroundColor', '#FF5252');
                }
                $('#table' + page).empty().append(content);
                page = page + 1;
            }

        };

        function activation_data(machine_name_text, data, activation_text) {

            Highcharts.chart('bar1', {
                chart: {
                    zoomType: 'xy',
                    backgroundColor: "rgba(0,0,0,0)"
                },
                title: {
                    text: machine_name_text,
                    align: 'center',
                    style: {
                        color: '#FFFFFF'
                    }
                },
                // subtitle: {},
                xAxis: {
                    type: 'category',
                    crosshair: true,
                    labels: {
                        style: {
                            color: '#FFFFFF'
                        }
                    }
                },

                yAxis: {
                    min: 0,
                    max: 100,
                    title: {
                        text: ''
                    },
                    labels: {
                        format: '{value} %',
                        style: {
                            color: '#FFFFFF'
                        }
                    }
                },
                legend: {
                    itemStyle: {
                        color: '#FFFFFF',
                        fontSize: '16px'
                    }
                },
                tooltip: {
                    // head + 每个 point + footer 拼接成完整的 table
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f} %</b></td></tr>',
                    shared: true
                },
                series: [{
                    type: 'line',
                    name: activation_text,
                    data: data,
                    color: '#00D659'

                }],
                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                floating: false,
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom',
                                x: 0,
                                y: 0
                            },
                            yAxis: [{
                                labels: {
                                    align: 'right',
                                    x: 0,
                                    y: -6
                                },
                                showLastLabel: false
                            }, {
                                labels: {
                                    align: 'left',
                                    x: 0,
                                    y: -6
                                },
                                showLastLabel: false
                            }, {
                                visible: false
                            }]
                        }
                    }]
                },
                credits: { enabled: false }
            });
        }

        function four_data(machine_name_text, day, work, stop, fix, alarm, work_text, stop_text, fix_text, alarm_text) {

            Highcharts.chart('bar2', {
                chart: {
                    type: 'column',
                    backgroundColor: 'rgba(0,0,0,0)',
                    height: '500'
                },
                plotOptions: {
                    series: {
                        pointWidth: 18
                    }
                },
                title: {
                    text: machine_name_text,
                    style: {
                        color: '#FFFFFF',
                        fontSize: '18px',
                    }
                },
                subtitle: {
                    text: ''
                },
                xAxis: {
                    // categories: ["A01","A02","A03","A04","A05","A06"],

                    // type: 'category',
                    categories: day,
                    crosshair: true,
                    // categories: machine_arr,

                    labels: {
                        style: {
                            color: '#FFFFFF'
                            // fontSize: '12px',
                        }
                    },
                },
                yAxis: {
                    tickInterval: 4,
                    endOnTick: false,
                    min: 0,
                    max: 24,
                    title: {
                        text: '',
                    },
                    labels: {
                        format: '{value} hr',

                        style: {
                            overflow: 'justify',
                            color: '#FFFFFF'
                        }
                    }

                },
                tooltip: {
                    valueDecimals: 1,
                    valueSuffix: ' hr'
                },
                legend: {
                    itemStyle: {
                        color: '#FFFFFF',
                        fontSize: '16px'
                    }
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: work_text,
                    // data: [24, 18, 18, 9, 18, 19],

                    // data: [24],

                    data: work,

                    color: '#00D659'
                }, {
                    name: stop_text,
                    // data: [0, 6, 6, 0, 6, 3],
                    data: stop,

                    // data: [jdata.stop[i].y],


                    color: '#00BCD4'
                }, {
                    name: fix_text,
                    // data: [0, 0, 0, 12, 0, 0],
                    data: fix,

                    // data: [fix_arr],


                    color: '#FFC107'
                }, {
                    name: alarm_text,
                    // data: [0, 0, 0, 3, 0, 2],
                    data: alarm,

                    // data: [jdata.alarm[i].y],


                    color: '#FF5252'
                }]
            });

        }
    </script>
</body>

</html>
<?php
ini_set('display_errors', '1');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");
$lang = new Language();
$lang->load("header");
$lang->load("menu");
$lang->load("home_index");
?>
<!doctype html>
<html>

<head>
    <title>
        <?php echo $lang->line("header.web_title"); ?>
    </title>

    <!-- 插件引用 -->
    <?php require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/common/legacyTemplate/head.php'); ?>
    <!--    <script src="./common/highcharts/modules/series-label.js"></script>-->
    <script src="/common/IDCC_plugin/deprecated/highcharts/highcharts.js"></script>
    <link rel="stylesheet" href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.css">

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

        /* div.change_area {
            height: 800px;
            width: 100%;
        } */

        div.change_area {
            height: 30px;
            width: 100%;
        }

        div.card_change {
            height: 50px;
            width: 100%;
        }

        .mdl-card {
            height: 550px;
        }
    </style>
</head>

<body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header is-small-screen">
        <!-- 頁面內容 -->
        <main class="">

            <div class="mdl-grid charts">
                <!--4-->
                <!-- <div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--6-col-tablet mdl-cell--12-col-phone" style="height:1350px"> -->
                <div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--6-col-tablet mdl-cell--12-col-phone"
                    style="height:550px">

                    <div class="mdl-card mdl-shadow--2dp">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">
                                <?php echo $lang->line("home_index.home_index_dyeing"); ?>
                            </h2>
                        </div>
                        <div class="mdl-card__supporting-text chart-container" style="width: 100%">
                            <div class="row justify-content-around">
                                <div id="4-1"
                                    style="background-color:#272727;height: 40px;text-align:center;color:#FFFFFF;line-height:40px;width: 25%">
                                </div>
                                <div id="4-2"
                                    style="background-color:#272727;height: 40px;text-align:center;color:#FFFFFF;line-height:40px;width: 25%">
                                </div>
                                <div id="4-3"
                                    style="background-color:#272727;height: 40px;text-align:center;color:#FFFFFF;line-height:40px;width: 25%">
                                </div>
                                <div id="4-4"
                                    style="background-color:#272727;height: 40px;text-align:center;color:#FFFFFF;line-height:40px;width: 25%">
                                </div>
                            </div>
                            <div class="change_area">
                                <div class="change4">
                                    <div id="pie4"></div>
                                    <div id="bar4"></div>
                                </div>
                            </div>
                            <div class="card-header" style="background-color:#272727;color:#FFFFFF;font-size: 18px">
                                <?php echo $lang->line("home_index.home_machine_status"); ?>
                            </div>
                            <div class="card_change">
                                <div class="card4">
                                    <ul class="list-group">
                                        <li id="table4-1" class="list-group-item "
                                            style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff;">
                                        </li>
                                        <li id="table4-2" class="list-group-item "
                                            style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff;">
                                        </li>
                                        <li id="table4-3" class="list-group-item "
                                            style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff;">
                                        </li>
                                        <li id="table4-4" class="list-group-item"
                                            style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff;">
                                        </li>
                                        <li id="table4-5" class="list-group-item "
                                            style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff;">
                                        </li>
                                        <li id="table4-6" class="list-group-item"
                                            style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff;">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!--2-->
                <!-- <div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--6-col-tablet mdl-cell--12-col-phone" style="height:1350px"> -->
                <div class="mdl-cell mdl-cell--6-col-desktop mdl-cell--6-col-tablet mdl-cell--12-col-phone"
                    style="height:550px">

                    <div class="mdl-card mdl-shadow--2dp">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">
                                <?php echo $lang->line("home_index.home_index_feeding_system"); ?>
                            </h2>
                        </div>
                        <div class="mdl-card__supporting-text chart-container" style="width: 100%">
                            <div class="row justify-content-around">

                                <div id="2-1"
                                    style="background-color:#272727;height: 40px;text-align:center;color:#FFFFFF;line-height:40px;width: 25%">
                                </div>
                                <div id="2-2"
                                    style="background-color:#272727;height: 40px;text-align:center;color:#FFFFFF;line-height:40px;width: 25%">
                                </div>
                                <div id="2-3"
                                    style="background-color:#272727;height: 40px;text-align:center;color:#FFFFFF;line-height:40px;width: 25%">
                                </div>
                                <div id="2-4"
                                    style="background-color:#272727;height: 40px;text-align:center;color:#FFFFFF;line-height:40px;width: 25%">
                                </div>
                            </div>
                            <div class="change_area">
                                <div class="change2">
                                    <div id="pie2"></div>
                                    <div id="bar2"></div>
                                </div>
                            </div>
                            <div class="card-header" style="background-color:#272727;color:#FFFFFF;font-size: 18px">
                                <?php echo $lang->line("home_index.home_machine_status"); ?>
                            </div>
                            <div class="card_change">
                                <div class="card2">
                                    <ul class="list-group">
                                        <li id="table2-1" class="list-group-item"
                                            style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff;">
                                        </li>
                                        <li id="table2-2" class="list-group-item"
                                            style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff;">
                                        </li>
                                        <li id="table2-3" class="list-group-item"
                                            style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff;">
                                        </li>
                                        <li id="table2-4" class="list-group-item"
                                            style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff;">
                                        </li>
                                        <li id="table2-5" class="list-group-item"
                                            style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff;">
                                        </li>
                                        <li id="table2-6" class="list-group-item"
                                            style="height: 40px;font-size: 120%;font-weight:bold;display:flex;align-items: center;color:#ffffff;">
                                        </li>


                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--5-->
                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                    <div class="mdl-card mdl-shadow--2dp line-chart">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="Dye_hour_energy">
                                <?php echo $lang->line("home_index.home_index_DyeMachines_24Hourly"); ?>
                            </h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div id="Dye_energy"></div>
                        </div>
                    </div>
                </div>
                <!--6-->
                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                    <div class="mdl-card mdl-shadow--2dp line-chart">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text" id="Finish_hour_energy">
                                <?php echo $lang->line("home_index.home_index_FinishMachines_24Hourly"); ?>
                            </h2>
                        </div>
                        <div class="mdl-card__supporting-text">
                            <div id="Finish_energy"></div>
                        </div>
                    </div>
                </div>

            </div>

        </main>
        <!-- 頁面內容 -->
    </div>
    <script>
        function random(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }
    </script>

    <script src="./in_engry.js"></script>

    <!-- Menu欄位顯示 -->
    <script>
        const queryString = window.location.search;
        $(document).ready(function () {
            var day = "<?php echo date('Y-m-d H:i:s'); ?>";
            var machine_name_text = "<?php echo $lang->line("home_index.home_index_machine_name"); ?>";
            var Water_word = '<?php echo $lang->line("home_index.home_index_Water"); ?>';
            var Steam_word = '<?php echo $lang->line("home_index.home_index_Steam"); ?>';
            var Power_word = '<?php echo $lang->line("home_index.home_index_Power"); ?>';
            // console.log(day);
            $.ajax({
                url: "ajin_engry.php" + queryString,
                type: "POST",
                dataType: 'json',
                data: {
                    day: day
                },
                success: function (res) {
                    // console.log(res);
                    Dye_energy_24hour(machine_name_text, res[0].data, res[1].data, res[2].data, res[6].hour, Water_word, Steam_word, Power_word);
                    Finish_energy_24hour(machine_name_text, res[3].data, res[4].data, res[5].data, res[7].hour, Water_word, Steam_word, Power_word);
                },
                error: function (err) {
                    console.log(err)
                },
            });

            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: "./get_data_2.php" + queryString,
                async: false,
                cache: false,

                success: function (res) {
                    // console.log(res);
                    var content_1 = '<?php echo $lang->line("home_index.home_index_delivery_time"); ?>' + '：' + res[0].time;
                    var content_2 = '<?php echo $lang->line("home_index.home_index_delivery_count"); ?>' + '：' + Math.round(res[0].number) + '<?php echo $lang->line("home_index.home_index_time"); ?>';
                    var content_3 = '<?php echo $lang->line("home_index.home_index_throughput"); ?>' + '：' + res[0].quantity;

                    // console.log(content);

                    $('#2-1').empty().append(content_1);
                    $('#2-2').empty().append(content_2);
                    $('#2-3').empty().append(content_3);
                },
            });

            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: "./get_data_4.php" + queryString,
                async: false,
                cache: false,

                success: function (res) {
                    // console.log(res);
                    var content_1 = '<?php echo $lang->line("home_index.home_index_production"); ?>' + '：' + Math.round(res[0].number1) + 'Kg';
                    var content_2 = '<?php echo $lang->line("home_index.home_index_production_cylinders"); ?>' + '：' + Math.round(res[0].number2);
                    var content_3 = '<?php echo $lang->line("home_index.home_index_delay_cylinder_number"); ?>' + '：' + Math.round(res[0].delay);

                    // console.log(content);

                    $('#4-1').empty().append(content_1);
                    $('#4-2').empty().append(content_2);
                    $('#4-3').empty().append(content_3);
                },
            });

            // ------------------------刷新table

            setInterval(function () {
                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: "./get_data_2.php" + queryString,
                    async: false,
                    cache: false,

                    success: function (res) {
                        // console.log(res);
                        var content_1 = '<?php echo $lang->line("home_index.home_index_delivery_time"); ?>' + '：' + res[0].time;
                        var content_2 = '<?php echo $lang->line("home_index.home_index_delivery_count"); ?>' + '：' + Math.round(res[0].number) + '<?php echo $lang->line("home_index.home_index_time"); ?>';
                        var content_3 = '<?php echo $lang->line("home_index.home_index_throughput"); ?>' + '：' + res[0].quantity;

                        // console.log(content);

                        $('#2-1').empty().append(content_1);
                        $('#2-2').empty().append(content_2);
                        $('#2-3').empty().append(content_3);
                    },
                });

                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: "./get_data_4.php" + queryString,
                    async: false,
                    cache: false,

                    success: function (res) {
                        // console.log(res);

                        var content_1 = '<?php echo $lang->line("home_index.home_index_production"); ?>' + '：' + Math.round(res[0].number1) + 'Kg';
                        var content_2 = '<?php echo $lang->line("home_index.home_index_production_cylinders"); ?>' + '：' + Math.round(res[0].number2);
                        var content_3 = '<?php echo $lang->line("home_index.home_index_delay_cylinder_number"); ?>' + '：' + Math.round(res[0].delay);

                        // console.log(content);

                        $('#4-1').empty().append(content_1);
                        $('#4-2').empty().append(content_2);
                        $('#4-3').empty().append(content_3);
                    },
                });


            }, 50000);

            // TABLE2 START-------------------------------
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: "./get_table_2.php" + queryString,
                async: false,
                cache: false,

                success: function (res_table) {
                    // console.log(res_table);
                    var total_page = Math.ceil(res_table.length / 6);

                    var others_data = (res_table.length % 6);

                    var start = 0;
                    var end = 6;
                    if (res_table.length / 6 < 1) {
                        end = res_table.length % 6;
                    }
                    show_table_2(res_table, start, end);
                    // start = start + 6;
                    // end = end + 6;
                    // if (res_table.length / 6 < 1) {
                    //     end = end + res_table.length % 6;
                    // }
                    // console.log(res_table);

                    setInterval(function () {

                        if ((res_table.length - end) / 6 >= 1 && start != 0) {
                            show_table_2(res_table, start, end);
                            start = start + 6;
                            end = end + 6;
                            if (res_table.length / 6 < 1) {
                                end = end + res_table.length % 6;
                            }
                        }
                        else if (res_table.length <= 6) {
                            show_table_2(res_table, start, end);
                        }
                        else if (start == 0 && end <= 6) {
                            show_table_2(res_table, start, end);
                            start = start + 6;
                            end = end + 6;
                            if (res_table.length / 6 < 1) {
                                end = res_table.length % 6;
                            }
                        }
                        else {
                            show_table_2(res_table, start, end);


                            start = 0;
                            end = 6;
                            if (res_table.length / 6 < 1) {
                                end = res_table.length % 6;
                            }
                        }
                    }, 5000);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.responseText);
                }
            });

            function show_table_2(res_table, start, end) {
                var page = 1
                for (i = start; i < end; i++) {

                    var name = (res_table[i].name);
                    var date = (res_table[i].date);
                    var description = (res_table[i].description);
                    var note = (res_table[i].note);

                    var content = name + '：' + description + note + date;
                    var state = (res_table[i].state);

                    // if (state == '3') {//停機
                    //     $('#table2-' + page).css('backgroundColor', '#00BCD4');
                    // }
                    // if (state == '4') {//運行
                    //     $('#table2-' + page).css('backgroundColor', '#00D659');
                    // }
                    // if (state == '6') {//維修
                    //     $('#table2-' + page).css('backgroundColor', '#FFC107');
                    // }
                    // if (state == '5' || state == '1' || state == '2') {//5故障,1警報,2延遲
                    //     $('#table2-' + page).css('backgroundColor', '#FF5252');
                    // }
                    // if (state == '') {//5故障,1警報,2延遲
                    //     $('#table2-' + page).css('backgroundColor', '#272727');
                    // }
                    // $('#table2-' + page).empty().append(content);

                    // 事件類型 1:一般 2:提醒 3:警報 4:錯誤

                    $('#table2-' + page).css('backgroundColor', '#00D659');
                    if (state == '1') {
                        $('#table2-' + page).css('backgroundColor', '#00D659');
                    }
                    if (state == '2') {
                        $('#table2-' + page).css('backgroundColor', '#FFC107');
                    }
                    if (state == '3' || state == '4') {
                        $('#table2-' + page).css('backgroundColor', '#FF5252');
                    }
                    $('#table2-' + page).empty().append(content);
                    page = page + 1;

                }

            };
            // TABLE2 END-------------------------------
            // TABLE4 START-------------------------------
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: "./get_table_4.php" + queryString,
                async: false,
                cache: false,

                success: function (res_table) {
                    // console.log(res_table);
                    var total_page = Math.ceil(res_table.length / 6);

                    var others_data = (res_table.length % 6);

                    var start = 0;
                    var end = 6;
                    if (res_table.length / 6 < 1) {
                        end = res_table.length % 6;
                    }
                    show_table_4(res_table, start, end);
                    // start = start + 6;
                    // end = end + 6;
                    // if (res_table.length / 6 < 1) {
                    //     end = end + res_table.length % 6;
                    // }
                    // console.log(res_table);

                    setInterval(function () {

                        if ((res_table.length - end) / 6 >= 1 && start != 0) {
                            show_table_4(res_table, start, end);
                            start = start + 6;
                            end = end + 6;
                            if (res_table.length / 6 < 1) {
                                end = end + res_table.length % 6;
                            }
                        }
                        else if (res_table.length <= 6) {
                            show_table_4(res_table, start, end);
                        }
                        else if (start == 0 && end <= 6) {
                            show_table_4(res_table, start, end);
                            start = start + 6;
                            end = end + 6;
                            if (res_table.length / 6 < 1) {
                                end = res_table.length % 6;
                            }
                        }
                        else {
                            show_table_4(res_table, start, end);


                            start = 0;
                            end = 6;
                            if (res_table.length / 6 < 1) {
                                end = res_table.length % 6;
                            }
                        }
                    }, 5000);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.responseText);
                }
            });

            function show_table_4(res_table, start, end) {
                var page = 1
                for (i = start; i < end; i++) {

                    var name = (res_table[i].name);
                    var date = (res_table[i].date);
                    var description = (res_table[i].description);
                    var note = (res_table[i].note);

                    var content = name + '：' + description + note + date;
                    var state = (res_table[i].state);

                    if (state == '3') {//停機
                        $('#table4-' + page).css('backgroundColor', '#00BCD4');
                    }
                    if (state == '4') {//運行
                        $('#table4-' + page).css('backgroundColor', '#00D659');
                    }
                    if (state == '6') {//維修
                        $('#table4-' + page).css('backgroundColor', '#FFC107');
                    }
                    if (state == '5' || state == '1' || state == '2') {//5故障,1警報,2延遲
                        $('#table4-' + page).css('backgroundColor', '#FF5252');
                    }
                    if (state == '') {//5故障,1警報,2延遲
                        $('#table4-' + page).css('backgroundColor', '#272727');
                    }
                    $('#table4-' + page).empty().append(content);
                    page = page + 1;

                }
            };
            // TABLE4 END-------------------------------


            // ---------------------------------1-------------------------------------

/*
            // ---------------------------------2-------------------------------------
            // bar2 start
            $.ajax({
                url: "bar_2_ajax.php",
                type: "POST",
                dataType: 'json',
                success: function (res) {
                    var content_4 = '<?php echo $lang->line("home_index.home_index_uti_rate"); ?>' + '：' + res[0].activation + ' % ';


            var start = 0;
            var end = 6;

            if (res[0].work.length / 6 < 1) {
                end = res[0].work.length % 6;
            }
            show_bar_2(res, start, end, content_4);
            // start = start + 6;
            // end = end + 6;
            // if (res[0].work.length / 6 < 1) {
            //     end = end + res[0].work.length % 6;
            // }
            setInterval(function () {

                if ((res[0].work.length - end) / 6 >= 1 && start != 0) {
                    show_bar_2(res, start, end, content_4);
                    start = start + 6;
                    end = end + 6;
                    if (res[0].work.length / 6 < 1) {
                        end = end + res[0].work.length % 6;
                    }
                }
                else if (res[0].work.length <= 6) {
                    show_bar_2(res, start, end, content_4);
                }
                else if (start == 0 && end <= 6) {
                    show_bar_2(res, start, end, content_4);
                    start = start + 6;
                    end = end + 6;
                    if (res[0].work.length / 6 < 1) {
                        end = res[0].work.length % 6;
                    }
                }
                else {
                    show_bar_2(res, start, end, content_4);


                    start = 0;
                    end = 6;
                    if (res[0].work.length / 6 < 1) {
                        end = res[0].work.length % 6;
                    }
                }

            }, 5000);

        },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
            }
            });

        function show_bar_2(res, start, end, content_4) {
            var content_4 = '<?php echo $lang->line("home_index.home_index_uti_rate"); ?>' + '：' + res[0].activation + '%';

            $('#2-4').empty().append(content_4);

            var machine_arr = (res[0].machine).slice(start, end);

            var work_arr = (res[0].work).slice(start, end);

            var stop_arr = (res[0].stop).slice(start, end);

            var fix_arr = (res[0].fix).slice(start, end);

            var alarm_arr = (res[0].alarm).slice(start, end);

            dye_bar_2(machine_arr, work_arr, stop_arr, fix_arr, alarm_arr);

        };
        // bar2 end

        // pie2 start
        $.ajax({
            url: "pie_2_ajax.php",
            type: "POST",
            dataType: 'json',
            success: function (res) {
                // console.log(res[0].work.length);

                var total_page = Math.ceil(res[0].work.length / 6);

                var start = 0;
                var end = 6;
                if (res[0].work.length / 6 < 1) {
                    end = res[0].work.length % 6;
                }


                // console.log(res);
                show_pie_2(res, start, end);
                // start = start + 6;
                // end = end + 6;
                // if (res[0].work.length / 6 < 1) {
                //     end = end + res[0].work.length % 6;
                // }
                setInterval(function () {

                    if ((res[0].work.length - end) / 6 >= 1 && start != 0) {
                        show_pie_2(res, start, end);
                        start = start + 6;
                        end = end + 6;
                        if (res[0].work.length / 6 < 1) {
                            end = end + res[0].work.length % 6;
                        }
                    }
                    else if (res[0].work.length <= 6) {
                        show_pie_2(res, start, end);
                    }
                    else if (start == 0 && end <= 6) {
                        show_pie_2(res, start, end);
                        start = start + 6;
                        end = end + 6;
                        if (res[0].work.length / 6 < 1) {
                            end = res[0].work.length % 6;
                        }
                    }
                    else {
                        show_pie_2(res, start, end);


                        start = 0;
                        end = 6;
                        if (res[0].work.length / 6 < 1) {
                            end = res[0].work.length % 6;
                        }
                    }

                }, 5000);

            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
            }
        });

        function show_pie_2(res, start, end) {
            var work_arr_total = 0;
            var stop_arr_total = 0;
            var fix_arr_total = 0;
            var alarm_arr_total = 0;


            // var work_arr = (res[0].work).slice(start, end);
            //
            // var stop_arr = (res[0].stop).slice(start, end);
            //
            // var fix_arr = (res[0].fix).slice(start, end);
            //
            // var alarm_arr = (res[0].alarm).slice(start, end);


            while (start < end) {
                var work_arr = (res[0].work[start]);
                work_arr = Math.ceil(work_arr);

                var stop_arr = (res[0].stop[start]);
                stop_arr = Math.ceil(stop_arr);

                // console.log(stop_arr);


                var fix_arr = (res[0].fix[start]);
                fix_arr = Math.ceil(fix_arr);

                var alarm_arr = (res[0].alarm[start]);
                alarm_arr = Math.ceil(alarm_arr);

                start = start + 1;
                work_arr_total = work_arr_total + work_arr;
                stop_arr_total = stop_arr_total + stop_arr;
                fix_arr_total = fix_arr_total + fix_arr;
                alarm_arr_total = alarm_arr_total + alarm_arr;
            }

            // console.log(work_arr_total);
            // console.log(stop_arr_total);
            // console.log(fix_arr_total);
            // console.log(alarm_arr_total);
            dye_pie_2(work_arr_total, stop_arr_total, fix_arr_total, alarm_arr_total);
        };
        // pie2 end
        // -------------------------4---------------------------------
        // bar4 start
        $.ajax({
            url: "bar_4_ajax.php",
            type: "POST",
            dataType: 'json',
            success: function (res) {
                $rate = Math.round(res[0].activation * Math.pow(10, 1)) / Math.pow(10, 1);
                if ($rate > 100) {
                    $rate = 100
                }
                if ($rate < 0) {
                    $rate = 0
                }
                var content_4 = '<?php echo $lang->line("home_index.home_index_uti_rate"); ?>' + '：' + $rate + '%';
                $('#4-4').empty().append(content_4);
                var start = 0;
                var end = 6;

                if (res[0].work.length / 6 < 1) {
                    end = res[0].work.length % 6;
                }
                // console.log(res);
                show_bar_4(res, start, end, content_4);
                // start = start + 6;
                // end = end + 6;
                // if (res[0].work.length / 6 < 1) {
                //     end = end + res[0].work.length % 6;
                // }
                setInterval(function () {

                    if ((res[0].work.length - end) / 6 >= 1 && start != 0) {
                        show_bar_4(res, start, end, content_4);
                        start = start + 6;
                        end = end + 6;
                        if (res[0].work.length / 6 < 1) {
                            end = end + res[0].work.length % 6;
                        }
                    }
                    else if (res[0].work.length <= 6) {
                        show_bar_4(res, start, end, content_4);
                    }
                    else if (start == 0 && end <= 6) {
                        show_bar_4(res, start, end, content_4);
                        start = start + 6;
                        end = end + 6;
                        if (res[0].work.length / 6 < 1) {
                            end = res[0].work.length % 6;
                        }
                    }
                    else {
                        show_bar_4(res, start, end, content_4);


                        start = 0;
                        end = 6;
                        if (res[0].work.length / 6 < 1) {
                            end = res[0].work.length % 6;
                        }
                    }


                }, 5000);

            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
            }
        });

        function show_bar_4(res, start, end, content_4) {
            var content_4 = '<?php echo $lang->line("home_index.home_index_uti_rate"); ?>' + '：' + Math.round(res[0].activation * Math.pow(10, 1)) / Math.pow(10, 1) + '%';

            var machine_arr = (res[0].machine).slice(start, end);

            var work_arr = (res[0].work).slice(start, end);

            var stop_arr = (res[0].stop).slice(start, end);

            var fix_arr = (res[0].fix).slice(start, end);

            var alarm_arr = (res[0].alarm).slice(start, end);

            dye_bar_4(machine_arr, work_arr, stop_arr, fix_arr, alarm_arr);

        };
        // bar4 end

        // pie4 start
        $.ajax({
            url: "pie_4_ajax.php",
            type: "POST",
            dataType: 'json',
            success: function (res) {
                var total_page = Math.ceil(res[0].work.length / 6);

                var start = 0;
                var end = 6;
                if (res[0].work.length / 6 < 1) {
                    end = res[0].work.length % 6;
                }
                // console.log(res);
                show_pie_4(res, start, end);
                // start = start + 6;
                // end = end + 6;
                // if (res[0].work.length / 6 < 1) {
                //     end = end + res[0].work.length % 6;
                // }
                setInterval(function () {

                    if ((res[0].work.length - end) / 6 >= 1 && start != 0) {
                        show_pie_4(res, start, end);
                        start = start + 6;
                        end = end + 6;
                        if (res[0].work.length / 6 < 1) {
                            end = end + res[0].work.length % 6;
                        }
                    }
                    else if (res[0].work.length <= 6) {
                        show_pie_4(res, start, end);
                    }
                    else if (start == 0 && end <= 6) {
                        show_pie_4(res, start, end);
                        start = start + 6;
                        end = end + 6;
                        if (res[0].work.length / 6 < 1) {
                            end = res[0].work.length % 6;
                        }
                    }
                    else {
                        show_pie_4(res, start, end);


                        start = 0;
                        end = 6;
                        if (res[0].work.length / 6 < 1) {
                            end = res[0].work.length % 6;
                        }
                    }

                }, 5000);

            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.responseText);
            }
        });

        function show_pie_4(res, start, end) {
            var work_arr_total = 0;
            var stop_arr_total = 0;
            var fix_arr_total = 0;
            var alarm_arr_total = 0;


            // var work_arr = (res[0].work).slice(start, end);
            //
            // var stop_arr = (res[0].stop).slice(start, end);
            //
            // var fix_arr = (res[0].fix).slice(start, end);
            //
            // var alarm_arr = (res[0].alarm).slice(start, end);
            console.log(res, start, end);


            while (start < end) {
                var work_arr = (res[0].work[start]);
                work_arr = Math.ceil(work_arr);

                var stop_arr = (res[0].stop[start]);
                stop_arr = Math.ceil(stop_arr);


                var fix_arr = (res[0].fix[start]);
                fix_arr = Math.ceil(fix_arr);

                var alarm_arr = (res[0].alarm[start]);
                alarm_arr = Math.ceil(alarm_arr);

                start = start + 1;
                work_arr_total = work_arr_total + work_arr;
                stop_arr_total = stop_arr_total + stop_arr;
                fix_arr_total = fix_arr_total + fix_arr;
                alarm_arr_total = alarm_arr_total + alarm_arr;
            }

            dye_pie_4(work_arr_total, stop_arr_total, fix_arr_total, alarm_arr_total);

        };
            // pie4 end
*/
            //ready end
        })
        ;


        // --------------------------------------1-------------------------------------------

        // --------------------------------------2-------------------------------------------
        // bar2 start
        //抓取圖表值及繪畫圖表函式
        function dye_bar_2(machine_arr, work_arr, stop_arr, fix_arr, alarm_arr) {
            Highcharts.chart('bar2', {
                chart: {
                    type: 'bar',
                    backgroundColor: 'rgba(0,0,0,0)',
                    height: '500'
                },
                plotOptions: {
                    series: {
                        pointWidth: 8
                    }
                },
                title: {
                    text: '<?php echo $lang->line("home_index.home_machine_status"); ?>',
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

                    // categories: ['A01'],

                    categories: machine_arr,

                    labels: {
                        style: {
                            color: '#FFFFFF',
                            fontSize: '18px',
                        }
                    },
                },
                yAxis: {
                    tickInterval: 4,
                    endOnTick: false,
                    min: 0,
                    max: 24,
                    title: {
                        text: '小時(hr)',
                        align: 'high',
                        style: {
                            color: '#FFFFFF',
                            fontSize: '18px',
                        }
                    },
                    labels: {
                        overflow: 'justify'

                    }
                },
                tooltip: {
                    valueDecimals: 1,
                    valueSuffix: ' hr'
                },
                legend: {
                    itemStyle: {
                        color: '#FFFFFF',
                        fontSize: '15px'

                    }
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: '<?php echo $lang->line("home_index.home_index_operation_time"); ?>',
                    // data: [24, 18, 18, 9, 18, 19],

                    // data: [24],

                    data: work_arr,

                    color: '#00D659'
                }, {
                    name: '<?php echo $lang->line("home_index.home_index_idle_time"); ?>',
                    // data: [0, 6, 6, 0, 6, 3],
                    data: stop_arr,

                    // data: [jdata.stop[i].y],


                    color: '#00BCD4'
                }, {
                    name: '<?php echo $lang->line("home_index.home_index_service_time"); ?>',
                    // data: [0, 0, 0, 12, 0, 0],
                    data: fix_arr,

                    // data: [fix_arr],


                    color: '#FFC107'
                }, {
                    name: '<?php echo $lang->line("home_index.home_index_malfunction_time"); ?>',
                    // data: [0, 0, 0, 3, 0, 2],
                    data: alarm_arr,

                    // data: [jdata.alarm[i].y],


                    color: '#FF5252'
                }]
            });
        };

        // bar2 end


        // pie2 start
        function dye_pie_2(work_arr, stop_arr, fix_arr, alarm_arr) {

            Highcharts.chart('pie2', {
                chart: {
                    spacing: [40, 0, 40, 0],
                    backgroundColor: 'rgba(0,0,0,0)',
                    height: '300',
                },
                title: {
                    // floating: true,
                    // // text: '圓心顯示標題'
                    text: ''
                },
                credits: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                                fontSize: '15px',
                                color: '#ffffff'
                            }
                        },
                    }
                },
                series: [{
                    type: 'pie',
                    innerSize: '0%',
                    name: '<?php echo $lang->line("home_index.home_index_proportion"); ?>',
                    data: [
                        { name: '<?php echo $lang->line("home_index.home_index_operation"); ?>', y: work_arr, color: '#00D659' },
                        { name: '<?php echo $lang->line("home_index.home_index_idle"); ?>', y: stop_arr, color: '#00BCD4' },
                        { name: '<?php echo $lang->line("home_index.home_index_service"); ?>', y: fix_arr, color: '#FFC107' },
                        { name: '<?php echo $lang->line("home_index.home_index_malfunction"); ?>', y: alarm_arr, color: '#FF5252' }
                    ]
                }]
            },

                function (c) { // 图表初始化完毕后的会掉函数
                    // 环形图圆心
                    var centerY = c.series[0].center[1],
                        titleHeight = parseInt(c.title.styles.fontSize);
                    // 动态设置标题位置
                    c.setTitle({
                        y: centerY + titleHeight / 2
                    });
                }
            );
        }

        // pie2 end

        // ---------------------------------4-----------------------------
        // bar4 start
        //抓取圖表值及繪畫圖表函式
        function dye_bar_4(machine_arr, work_arr, stop_arr, fix_arr, alarm_arr) {
            Highcharts.chart('bar4', {
                chart: {
                    type: 'bar',
                    backgroundColor: 'rgba(0,0,0,0)',
                    height: '500'
                },
                plotOptions: {
                    series: {
                        pointWidth: 8
                    }
                },
                title: {
                    text: '<?php echo $lang->line("home_index.home_machine_status"); ?>',
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

                    // categories: ['A01'],

                    categories: machine_arr,

                    labels: {
                        style: {
                            color: '#FFFFFF',
                            fontSize: '18px',
                        }
                    },
                },
                yAxis: {
                    tickInterval: 4,
                    endOnTick: false,
                    min: 0,
                    max: 24,
                    title: {
                        text: '小時(hr)',
                        align: 'high',
                        style: {
                            color: '#FFFFFF',
                            fontSize: '18px',
                        }
                    },
                    labels: {
                        overflow: 'justify'

                    }
                },
                tooltip: {
                    valueDecimals: 1,
                    valueSuffix: ' hr'
                },
                legend: {
                    itemStyle: {
                        color: '#FFFFFF',
                        fontSize: '15px'

                    }
                },
                credits: {
                    enabled: false
                },
                series: [{
                    name: '<?php echo $lang->line("home_index.home_index_operation_time"); ?>',
                    // data: [24, 18, 18, 9, 18, 19],

                    // data: [24],

                    data: work_arr,

                    color: '#00D659'
                }, {
                    name: '<?php echo $lang->line("home_index.home_index_idle_time"); ?>',
                    // data: [0, 6, 6, 0, 6, 3],
                    data: stop_arr,

                    // data: [jdata.stop[i].y],


                    color: '#00BCD4'
                }, {
                    name: '<?php echo $lang->line("home_index.home_index_service_time"); ?>',
                    // data: [0, 0, 0, 12, 0, 0],
                    data: fix_arr,

                    // data: [fix_arr],


                    color: '#FFC107'
                }, {
                    name: '<?php echo $lang->line("home_index.home_index_malfunction_time"); ?>',
                    // data: [0, 0, 0, 3, 0, 2],
                    data: alarm_arr,

                    // data: [jdata.alarm[i].y],


                    color: '#FF5252'
                }]
            });
        };

        // bar4 end


        // pie4 start
        function dye_pie_4(work_arr, stop_arr, fix_arr, alarm_arr) {

            Highcharts.chart('pie4', {
                chart: {
                    spacing: [40, 0, 40, 0],
                    backgroundColor: 'rgba(0,0,0,0)',
                    height: '300',
                },
                title: {
                    // floating: true,
                    // // text: '圓心顯示標題'
                    text: ''
                },
                credits: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                            style: {
                                fontSize: '15px',
                                color: '#ffffff'
                            }
                        },
                    }
                },
                series: [{
                    type: 'pie',
                    innerSize: '0%',
                    name: '<?php echo $lang->line("home_index.home_index_proportion"); ?>',
                    data: [
                        { name: '<?php echo $lang->line("home_index.home_index_operation"); ?>', y: work_arr, color: '#00D659' },
                        { name: '<?php echo $lang->line("home_index.home_index_idle"); ?>', y: stop_arr, color: '#00BCD4' },
                        { name: '<?php echo $lang->line("home_index.home_index_service"); ?>', y: fix_arr, color: '#FFC107' },
                        { name: '<?php echo $lang->line("home_index.home_index_malfunction"); ?>', y: alarm_arr, color: '#FF5252' }
                    ]
                }]
            },

                function (c) { // 图表初始化完毕后的会掉函数
                    // 环形图圆心
                    var centerY = c.series[0].center[1],
                        titleHeight = parseInt(c.title.styles.fontSize);
                    // 动态设置标题位置
                    c.setTitle({
                        y: centerY + titleHeight / 2
                    });
                }
            );
        }

        // pie4 end


    </script>
</body>

</html>
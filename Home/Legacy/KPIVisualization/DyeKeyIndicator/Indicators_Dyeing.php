<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];
require_once realpath($rootPath . "/common/language/language.php");
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
    <?php require_once realpath($rootPath . '/common/legacyTemplate/head.php'); ?>
    <!-- 模板預設 -->

    <!-- 插件引用 -->
    <script src="/common/IDCC_plugin/deprecated/highcharts/highcharts.js"></script>
    <!--    <script src="./common/highcharts/modules/series-label.js"></script>-->
    <link rel="stylesheet" href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.css">

    <!-- 插件引用 -->
    <style>
        html, body {
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
        div.change_area {
            height: 800px;
            width: 100%;
        }

        div.card_change {
            height: 50px;
            width: 100%;
        }

        @media(max-width: 900px) {
            .small {
                transform: scale(1);
                display: block;
                margin-top: 100px;
            }
        }

        .left-table {
            background-color: #272727;
            width: 150px;
            height: 200px;
            text-align: center;
            color: #FFFFFF;
            margin-bottom: 10px;
        }

        @media(min-width: 1680px) {
            .left-table {
                /* background-color:#272727; */
                width: 200px;
                height: 300px;
                /* text-align:center; */
                /* color:#FFFFFF; */
                /* margin-bottom:10px;" */
            }
        }

        .mdl-card {
            background-color: #2b2b2b !important;
        }
    </style>
</head>

<body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
        <!-- 頁面內容 -->
        <main class="">

            <div class="mdl-grid charts">
                <!--4-->
                <div class="mdl-cell mdl-cell--4-col-desktop mdl-cell--4-col-tablet mdl-cell--8-col-phone"
                    style="height:1000px">
                    <div class="mdl-card mdl-shadow--2dp">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">
                                <?php echo $lang->line("home_index.home_index_dyeing"); ?>
                            </h2>
                        </div>
                        <div class="mdl-card__supporting-text chart-container" style="width: 100%">
                            <div class="row justify-content-around">
                                <div class=" small"
                                    style="display:flex;  flex-wrap: wrap;justify-content: space-around;">
                                    <div id="4-1" class="left-table"></div>
                                    <div id="4-2" class="left-table"></div>

                                    <div id="4-3" class="left-table"></div>
                                    <div id="4-4" class="left-table"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div></div>
                </div>

                <!--1-->
                <div class="mdl-cell mdl-cell--3-col-desktop mdl-cell--3-col-tablet mdl-cell--6-col-phone"
                    style="height:1000px">
                    <div class="mdl-card mdl-shadow--2dp">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">
                                <?php echo $lang->line("home_index.home_machine_status"); ?>
                            </h2>

                        </div>
                        <div class="card-header" style="background-color:#272727;color:#FFFFFF;font-size: 18px"></div>
                        <div class="card_change">
                            <div class="card4">
                                <ul class="list-group">
                                    <li id="table4-1" class="list-group-item "
                                        style="height: 80px;font-size: 140%;font-weight:bold;display:flex;align-items: center;color:#ffffff;">
                                    </li>
                                    <li id="table4-2" class="list-group-item "
                                        style="height: 80px;font-size: 140%;font-weight:bold;display:flex;align-items: center;color:#ffffff;">
                                    </li>
                                    <li id="table4-3" class="list-group-item "
                                        style="height: 80px;font-size: 140%;font-weight:bold;display:flex;align-items: center;color:#ffffff;">
                                    </li>
                                    <li id="table4-4" class="list-group-item"
                                        style="height: 80px;font-size: 140%;font-weight:bold;display:flex;align-items: center;color:#ffffff;">
                                    </li>
                                    <li id="table4-5" class="list-group-item "
                                        style="height: 80px;font-size: 140%;font-weight:bold;display:flex;align-items: center;color:#ffffff;">
                                    </li>
                                    <li id="table4-6" class="list-group-item"
                                        style="height: 80px;font-size: 140%;font-weight:bold;display:flex;align-items: center;color:#ffffff;">
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="mdl-card__supporting-text chart-container" style="width: 100%">


                            <div class="change_area">

                            </div>

                        </div>
                    </div>
                </div>




                <div class="mdl-cell mdl-cell--5-col-desktop mdl-cell--5-col-tablet mdl-cell--10-col-phone"
                    style="height:1000px">
                    <div class="mdl-card mdl-shadow--2dp">
                        <div class="mdl-card__title">
                            <h2 class="mdl-card__title-text">
                                <?php echo $lang->line("home_index.home_machine_status"); ?>
                            </h2>
                        </div>
                        <div class="mdl-card__supporting-text chart-container" style="width: 100%">

                            <div class="change_area">
                                <div class="change1">
                                    <div id="pie4"></div>
                                    <div id="bar4"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!--5-->
                <!-- <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">
                <div class="mdl-card mdl-shadow--2dp line-chart">
                    <div class="mdl-card__title">
                        <h2 class="mdl-card__title-text" id="Dye_hour_energy"><?php echo $lang->line("home_index.home_index_DyeMachines_24Hourly"); ?></h2>
                    </div>
                    <div class="mdl-card__supporting-text">
                        <div id="Dye_energy"></div>
                    </div>
                </div>
            </div> -->

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
            // $.ajax({
            //     url: "ajin_engry.php",
            //     type: "POST",
            //     dataType: 'json',
            //     data: {
            //         day: day
            //     },
            //     success: function (res) {
            //         // console.log(res);
            //         Dye_energy_24hour(machine_name_text, res[0].data, res[1].data, res[2].data, res[6].hour, Water_word, Steam_word, Power_word);
            //         Finish_energy_24hour(machine_name_text, res[3].data, res[4].data, res[5].data, res[7].hour, Water_word, Steam_word, Power_word);
            //     },
            //     error: function (err) {
            //         // console.log(err)
            //     },
            // });


            // $.ajax({
            //     type: "POST",
            //     dataType: "JSON",
            //     url: "./get_data_1.php",
            //     async: false,
            //     cache: false,

            //     success: function (res) {
            //         // console.log(res);
            //         var content_1 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_color_amount"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + res[0].quantity + '<?php echo $lang->line("home_index.home_index_time"); ?>' + '</span>';
            //         var content_2 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_reconsider"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + res[0].reconsider + '<?php echo $lang->line("home_index.home_index_time"); ?>' + '</span>';
            //         var content_3 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_con_amount"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + res[0].confirm + '<?php echo $lang->line("home_index.home_index_time"); ?>' + '</span>';

            //         // console.log(content);

            //         $('#1-1').empty().append(content_1);
            //         $('#1-2').empty().append(content_2);
            //         $('#1-3').empty().append(content_3);
            //     },
            // });
            // $.ajax({
            //     type: "POST",
            //     dataType: "JSON",
            //     url: "./get_data_2.php",
            //     async: false,
            //     cache: false,

            //     success: function (res) {
            //         // console.log(res);
            //         var content_1 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_delivery_time"); ?>'+ '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + res[0].time+ '</span>';
            //         var content_2 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_delivery_count"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + res[0].number + '<?php echo $lang->line("home_index.home_index_time"); ?>'+ '</span>';
            //         var content_3 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_throughput"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + Math.round(res[0].quantity) + 'Kg'+ '</span>';


            //         // console.log(content);

            //         $('#2-1').empty().append(content_1);
            //         $('#2-2').empty().append(content_2);
            //         $('#2-3').empty().append(content_3);
            //     },
            // });

            // $.ajax({
            //     type: "POST",
            //     dataType: "JSON",
            //     url: "./get_data_3.php",
            //     async: false,
            //     cache: false,

            //     success: function (res) {
            //         // console.log(res);
            //         var content_1 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_pre_determined"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + res[0].front + '<?php echo $lang->line("home_index.home_index_yard"); ?>' + '</span>';
            //         var content_2 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_mid_set"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + res[0].mid + '<?php echo $lang->line("home_index.home_index_yard"); ?>' + '</span>';
            //         var content_3 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_chengding"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + res[0].finish + '<?php echo $lang->line("home_index.home_index_yard"); ?>' + '</span>';

            //         // console.log(content);

            //         $('#3-1').empty().append(content_1);
            //         $('#3-2').empty().append(content_2);
            //         $('#3-3').empty().append(content_3);
            //     },
            // });
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: "./get_data_4.php" + queryString,
                async: false,
                cache: false,

                success: function (res) {
                    //  console.log(res);
                    //var content_1 = '<?php echo $lang->line("home_index.home_index_production"); ?>' + '：' + res[0].number1 + 'Kg';
                    var content_1 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_production"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + Math.round(res[0].number1) + 'Kg</span>';
                    var content_2 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_production_cylinders"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + Math.round(res[0].number2) + '</span>';
                    var content_3 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_delay_cylinder_number"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + Math.round(res[0].delay) + '</span>';
                    // console.log(content);

                    $('#4-1').empty().append(content_1);
                    $('#4-2').empty().append(content_2);
                    $('#4-3').empty().append(content_3);
                },
            });

            // ------------------------刷新table

            setInterval(function () {
                // $.ajax({
                //     type: "POST",
                //     dataType: "JSON",
                //     url: "./get_data_1.php",
                //     async: false,
                //     cache: false,

                //     success: function (res) {
                //         // console.log(res);
                //         var content_1 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_color_amount"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + '<?php echo $lang->line("home_index.home_index_time"); ?>' + '</span>';
                //         var content_2 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_reconsider"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + '<?php echo $lang->line("home_index.home_index_time"); ?>' + '</span>';
                //         var content_3 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_con_amount"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + res[0].confirm + '<?php echo $lang->line("home_index.home_index_time"); ?>' + '</span>';


                //         // console.log(content);

                //         $('#1-1').empty().append(content_1);
                //         $('#1-2').empty().append(content_2);
                //         $('#1-3').empty().append(content_3);


                //     },
                // });

                // $.ajax({
                //     type: "POST",
                //     dataType: "JSON",
                //     url: "./get_data_2.php",
                //     async: false,
                //     cache: false,

                //     success: function (res) {
                //         // console.log(res);
                //         var content_1 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' +'<?php echo $lang->line("home_index.home_index_delivery_time"); ?>'  + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + res[0].time + '</span>';
                //         var content_2 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' +'<?php echo $lang->line("home_index.home_index_delivery_count"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + res[0].number + '<?php echo $lang->line("home_index.home_index_time"); ?>'+ '</span>';
                //         var content_3 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' +'<?php echo $lang->line("home_index.home_index_throughput"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + res[0].quantity + 'Kg</span>';

                //         // console.log(content);

                //         $('#2-1').empty().append(content_1);
                //         $('#2-2').empty().append(content_2);
                //         $('#2-3').empty().append(content_3);
                //     },
                // });

                // $.ajax({
                //     type: "POST",
                //     dataType: "JSON",
                //     url: "./get_data_3.php",
                //     async: false,
                //     cache: false,

                //     success: function (res) {
                //         // console.log(res);
                //         var content_1 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_pre_determined"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + res[0].front + '碼</span>';
                //         var content_2 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_mid_set"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + res[0].mid + '碼</span>';
                //         var content_3 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_chengding"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + res[0].finish + '碼</span>';

                //         // console.log(content);

                //         $('#3-1').empty().append(content_1);
                //         $('#3-2').empty().append(content_2);
                //         $('#3-3').empty().append(content_3);
                //     },
                // });

                $.ajax({
                    type: "POST",
                    dataType: "JSON",
                    url: "./get_data_4.php" + queryString,
                    async: false,
                    cache: false,

                    success: function (res) {
                        // console.log(res);
                        var content_1 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_production"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + Math.round(res[0].number1) + 'Kg</span>';
                        var content_2 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_production_cylinders"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + Math.round(res[0].number2) + '</span>';
                        var content_3 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_delay_cylinder_number"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + Math.round(res[0].delay) + '</span>';
                        // console.log(content);
                        // console.log(content);

                        $('#4-1').empty().append(content_1);
                        $('#4-2').empty().append(content_2);
                        $('#4-3').empty().append(content_3);
                    },
                });


            }, 300000);

            // TABLE4 START-------------------------------
            $.ajax({
                type: "POST",
                dataType: "JSON",
                url: "./get_table_4.php" + queryString,
                async: false,
                cache: false,

                success: function (res_table) {
                    // console.log(res_table);
                    // var total_page = Math.ceil(res_table.length / 6);

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

                    setInterval(function () {

                        if ((res_table.length - end) / 6 >= 1 && start != 0) {
                            show_table_4(res_table, start, end);
                            start = start + 6;
                            end = end + 6;
                            if (res_table.length / 6 < 1) {
                                end = end + res_table.length % 6;
                            }
                        }
                        else if (res_table.length < 6) {
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


                        // 如果這批結束的最後數量<所有的數值&&所有數值-現在的數量/一頁顯示的數量沒有餘數

                        // if (end < res_table.length && (res_table.length - end) / 6 > 1) {

                        //     start = start + 6;
                        //         end = end + 6;

                        // }
                        // if ((res_table.length - end) / 6 > 1 && (res_table.length - end) / 6 < 2) {
                        //     start = start + others_data;
                        //     end = end + others_data;
                        // }
                        // show_table_4(res_table, start, end);

                        // if (end == others_data && res_table.length < 6) {
                        //     start = 0;
                        //     end = others_data;
                        // }
                        // // 最後一頁
                        // if (res_table.length == end && res_table.length > 5) {
                        //     start = -6;
                        //     end = 0;
                        // }
                    }, 5000);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log(xhr.responseText);
                }
            });

            function show_table_4(res_table, start, end) {
                var page = 1
                $('#table4-1').empty().css('backgroundColor', 'white');
                $('#table4-2').empty().css('backgroundColor', 'white');
                $('#table4-3').empty().css('backgroundColor', 'white');
                $('#table4-4').empty().css('backgroundColor', 'white');
                $('#table4-5').empty().css('backgroundColor', 'white');
                $('#table4-6').empty().css('backgroundColor', 'white');
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
                    else {
                        $('#table4-' + page).css('backgroundColor', 'gray');
                    }
                    $('#table4-' + page).empty().append(content);
                    page = page + 1;

                }
            };
            // TABLE4 END-------------------------------











            // -------------------------4---------------------------------
            // bar4 start
            $.ajax({
                url: "bar_4_ajax.php" + queryString,
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
                    var total_page = Math.ceil(res[0].work.length / 6);

                    var others_data = (res[0].work.length % 6);

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
                var content_4 = '<div style="font-size:24px;color:#FFFFFF;margin-top:50px;">' + '<?php echo $lang->line("home_index.home_index_uti_rate"); ?>' + '：<br><br></div>' + '<span style="font-size:50px;color:#FFFFFF  ;">' + res[0].activation + '%</span>';
                $('#4-4').empty().append(content_4);


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
                url: "pie_4_ajax.php" + queryString,
                type: "POST",
                dataType: 'json',
                success: function (res) {
                    var total_page = Math.ceil(res[0].work.length / 6);

                    var start = 0;
                    var end = 6;
                    if (res[0].work.length / 6 < 1) {
                        end = res[0].work.length % 6;
                    }
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
                        else if (res[0].work.length < 6) {
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

            //ready end
        })
            ;







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
<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");
$lang = new Language();
$lang->load("header");
$lang->load("menu");
?>
<!doctype html>
<html>

<head>
    <!-- <title><?php //echo $lang->line("header.web_title"); ?></title> -->
    <?php require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/common/legacyTemplate/head.php'); ?>
    <style>
        .mdl-card__title-text {
            color: #ffffff;
        }

        .mdl-data-table {
            background-color: rgba(0, 0, 0, 0);
        }

        .parent {
            position: relative;
            top: 10px;
            left: 0;
        }

        .main-image {
            position: relative;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .water-level {
            position: absolute;
            top: 22%;
            left: 4%;
            width: 4.4%;
            height: 10%;
            z-index: 2;
        }

        .steam-graph {
            position: absolute;
            top: 1%;
            left: 20%;
            width: 34.5%;
            height: 30%;
            z-index: 2;
        }

        .ventilator-numeric {
            position: absolute;
            top: 9%;
            left: 70%;
            width: 7%;
            height: 8%;
            z-index: 2;
        }

        .water-pressure-meter {
            position: absolute;
            top: 46%;
            left: 20%;
            width: 7%;
            height: 16%;
            z-index: 2;
        }

        .water-pressure-numeric {
            position: absolute;
            top: 44%;
            left: 28%;
            width: 4%;
            height: 5%;
            z-index: 2;
        }

        .flow-numeric {
            position: absolute;
            top: 46%;
            left: 40%;
            width: 6%;
            height: 8%;
            z-index: 2;
            font-size: 2vw;
        }

        .blower-numeric {
            position: absolute;
            top: 72.6%;
            left: 16.2%;
            width: 8%;
            height: 8%;
            z-index: 2;
        }

        .steam-pressure-numeric {
            position: absolute;
            top: 58%;
            left: 83%;
            width: 10%;
            height: 8%;
            z-index: 2;
        }

        .steam-pressure-meter {
            position: absolute;
            top: 46%;
            left: 73.4%;
            width: 7%;
            height: 16%;
            z-index: 2;
        }

        .furnace-temp-numeric {
            position: absolute;
            top: 87%;
            left: 48%;
            width: 8%;
            height: 8%;
            z-index: 2;
        }

        .motor-overload {
            position: absolute;
            top: 81.5%;
            left: 73.7%;
            width: 5%;
            height: 11%;
            z-index: 2;
        }

        .stoker-numeric {
            position: absolute;
            top: 92.2%;
            left: 87.5%;
            width: 9%;
            height: 7.8%;
            z-index: 2;
        }

        .water {
            position: absolute;
            top: 10%;
            left: 10%;
            width: 8.2%;
            height: 27%;
            z-index: 2;

        }

        .steam {
            position: absolute;
            top: 35%;
            left: 48.56%;
            width: 15.2%;
            height: 15%;
            z-index: 2;
        }

        .steam_2-1 {
            position: absolute;
            top: 0%;
            left: 91.5%;
            width: 7%;
            height: 48%;
            z-index: 2;
        }

        .steam_2-2 {
            position: absolute;
            top: 0%;
            left: 86%;
            width: 6.4%;
            height: 17%;
            z-index: 2;
        }

        .fire {
            position: absolute;
            top: 52%;
            left: 36%;
            width: 28.4%;
            height: 30%;
            z-index: 2;
        }



        @media (max-width: 1080px) {

            .steam-pressure-meter {
                position: absolute;
                top: 23%;
                left: 63%;
                width: 28%;
                height: 64%;
                z-index: 2;
                transform: scale(0.3);
            }



            .water-pressure-meter {
                position: absolute;
                top: 23%;
                left: 10%;
                width: 28%;
                height: 64%;
                z-index: 2;
                transform: scale(0.3);
            }


            .steam-graph {
                /* position: absolute;
            top: -66%;
            left: -48%;
            width: 650px;
            height: 280px;
            z-index: 2;
            transform: scale(0.2); */
                position: absolute;
                top: -33.5%;
                left: -22.5%;
                width: 120%;
                height: 100%;
                z-index: 2;
                transform: scale(0.3);

            }

            .flow-numeric {
                line-height: 1.1;
            }

            .furnace-temp-numeric {
                line-height: 1.1;
            }

            .stoker-numeric {
                line-height: 1.1;
            }

            .blower-numeric {
                line-height: 1.1;
            }

            .water-level {
                line-height: 1.1;
            }
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
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/data.js"></script>

    <link rel="stylesheet" type="text/css" href="/common/IDCC_plugin/gauge/jquery-gauge.css">
    <script src="/common/IDCC_plugin/gauge/jquery-gauge.min.js"></script>
    <!-- 插件引用 -->
</head>

<body>
    <div class="mdl-layout mdl-js-layout  mdl-layout--fixed-header is-small-screen">
        <main class="mdl-layout__content">
            <div class="mdl-grid ui-tables">
                <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--4-col-phone "
                    id="holder">
                </div>
            </div>
        </main>
    </div>
</body>
<script src="/common/IDCC_plugin/bootstrap-5.0.0-dist/js/bootstrap.min.js"></script>
<!-- <script src="./table.min.js"></script>
<script src="./table.js"></script> -->

</html>
<script>
    const queryString = window.location.search;

    var timer = 0
    function counter() {
        timer += 1
        if (timer > 300) {
            location.reload();
        }
        $.ajax({
            type: 'POST',
            url: 'get_data.php' + queryString,
            success: function (show) {
                $('#holder').html(show);


            },
            error: function (err) {
                console.log(err);
            }
        })
        setTimeout(counter, 3000)
    }
    counter()
</script>
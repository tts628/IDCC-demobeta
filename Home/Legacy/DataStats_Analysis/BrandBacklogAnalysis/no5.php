<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="/common/IDCC_plugin/deprecated/highcharts/highcharts.js"></script>
    <!-- Include the JS for AG Grid -->
    <script src="/common/IDCC_plugin/ag-grid/ag-grid-community.min.js"></script>

    <script src="/common/IDCC_plugin/deprecated/highcharts/highcharts-3d.js"></script>
    <!-- 引入 Highcharts 主题 -->
    <script src="/common/IDCC_plugin/deprecated/highcharts/themes/high-contrast-light.js"></script>
    <script src="/common/IDCC_plugin/ag-grid/papaparse.min.js"></script>

    <script src="/common/IDCC_plugin/jquery/jquery.min.js"></script>

</head>

<body>
    <?php // require_once('../../../common/web_mode.php'); ?>
    <!-- <div style="position: fixed; top: 0%; right: 10%; font-size: 45px; opacity: 0.5; color: white;">綿春</div> -->

    <!-- <div><?php // require_once('../../../common/web_mode.php'); ?><a href="../index.php"
            style=" position:relative; left: 85%;">首頁</a></div> -->

    <div id="Daluen-result"></div>

    <script>
        const queryString = window.location.search;

        function ajaxCall() {
            $.ajax({
                // beforeSend: function () {
                //     $('.loading').fadeIn(300);
                // },
                url: "no5_ajax.php" + queryString,
                type: "POST",
                data: {

                },
                success: function (response) {
                    $("#Daluen-result").html(response);
                },
                error: function (err) {
                    $("#Daluen-result").html(err);
                },
                // complete: function () {
                //     $('.loading').fadeOut(300);

                //     // //測試可利用setTimeout 讓loading效果明顯
                //     // setTimeout(function () {
                //         // $('.loading').fadeOut(300);
                //     // }, 3000);
                // }
            });

            setTimeout(function () {
                ajaxCall()
            }, 300000);
        }
        ajaxCall()




    </script>
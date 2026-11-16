<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);
$rootPath = $_SERVER['DOCUMENT_ROOT'];
require_once realpath($rootPath . "/common/language/language.php");
$lang = new Language();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once realpath($rootPath . '/common/legacyTemplate/head.php'); ?>
    <?php $lang->load("excel_Lang"); ?>
    <!-- jquery -->
    <script src="/common/IDCC_plugin/jquery/jquery.min.js"></script>
    <input id="num" value="3" type="hidden">
</head>

<body class="g-sidenav-show bg-gray-100">
    <main class="main-content position-relative border-radius-lg ">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="border-bottom pb-1 mb-3">
                        <h3 class="mb-0 text-white"><?php echo $lang->line("excel_Lang.Data_Report_index"); ?></h3>
                    </div>
                    <div class="mdl-card__supporting-text mb-5 ">

                        <h4 class="mb-0 text-white"><?php echo $lang->line("excel_Lang.date_range"); ?></h4>

                        <input class="col-5 col-sm-5 col-md-3 col-lg-2" type="date" id="start_date">
                        <b class="col-1">
                            <font color="white" size="5">～</font>

                            <input class="col-5 col-sm-5 col-md-3 col-lg-2" type="date" id="end_date">
                            </br></br>
                            <button type="button" id="select" style="font-size:25px;text-align:center;"
                                class="col-4 col-md-4 btn btn-success mb-3 pb-3 mr-3">
                                <?php echo $lang->line("excel_Lang.query_interval"); ?></button>
                        </b> </b>
                        <button type="button" id="select_data" style="font-size:25px;text-align:center;"
                            class="col-5 col-md-4 btn btn-success mb-3 pb-3">
                            <?php echo $lang->line("excel_Lang.save_report"); ?></button>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 mb-lg-7 mb-5" style="width:100%;">
                <div class="card z-index-2 h-100 form-control ">

                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h3 class="text-left">
                            <font color="white">蒸氣耗用統計</font>
                        </h3>
                        <div class="col-15 style=width:100%">

                            <div class="table-responsive ">
                                <table id="energy" class="table  table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">
                                                <font color="black" size="5">
                                                    <?php echo $lang->line("excel_Lang.machine"); ?>
                                                </font>
                                            </th>
                                            <th scope="col">
                                                <font color="black" size="5">
                                                    <?php echo $lang->line("excel_Lang.production_order"); ?>
                                                </font>
                                            </th>
                                            <th scope="col">
                                                <font color="black" size="5">
                                                    <?php echo $lang->line("excel_Lang.Complete time"); ?>
                                                </font>
                                            </th>
                                            <th scope="col">
                                                <font color="black" size="5">
                                                    <?php echo $lang->line("excel_Lang.water"); ?>
                                                </font>
                                            </th>
                                            <th scope="col">
                                                <font color="black" size="5">
                                                    <?php echo $lang->line("excel_Lang.power"); ?>
                                                </font>
                                            </th>
                                            <th scope="col">
                                                <font color="black" size="5">
                                                    <?php echo $lang->line("excel_Lang.steam"); ?>
                                                </font>
                                            </th>
                                            <th scope="col">
                                                <font color="black" size="5">
                                                    <?php echo $lang->line("excel_Lang.carbonemission"); ?>
                                                </font>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody align=" center " id="table2excel">
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            function download_csv(csv, filename) {
                var csvFile;
                var downloadLink;

                // CSV FILE
                csvFile = new Blob(['\uFEFF' + csv], {
                    type: "text/csv;charset=utf-8"
                });

                // Download link
                downloadLink = document.createElement("a");

                // File name
                downloadLink.download = filename;

                // We have to create a link to the file
                downloadLink.href = window.URL.createObjectURL(csvFile);

                // Make sure that the link is not displayed
                downloadLink.style.display = "none";

                // Add the link to your DOM
                document.body.appendChild(downloadLink);

                // Lanzamos
                downloadLink.click();
            }

            function export_table_to_csv(html, filename) {


                var csv = [];
                var rows = document.querySelectorAll("table tr");

                for (var i = 0; i < rows.length; i++) {
                    var row = [],
                        cols = rows[i].querySelectorAll("td, th");

                    for (var j = 0; j < cols.length; j++)
                        row.push(cols[j].innerText);

                    csv.push(row.join(","));
                }

                // Download CSV
                download_csv(csv.join("\n"), filename);
            }
            let date = new Date();
            let output = String(date.getDate()).padStart(2, '0'); + '/' + String(date.getMonth() + 1).padStart(2, '0') + '/' + date.getFullYear();;

            document.querySelector("#select_data").addEventListener("click", function () {
                var html = document.querySelector("table").outerHTML;
                export_table_to_csv(html, "能源耗用資料報告.csv");
            });
        </script>


        <!-- 下方空格一 -->


    </main>
    <footer class="footer pt-3  ">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-lg-between">

            </div>
        </div>
    </footer>

    <!--   Core JS Files   -->
    <script src="/common/IDCC_plugin/bootstrap-5.0.0-dist/js/bootstrap.js"></script>
    <script src="/common/IDCC_plugin/@popperjs/core/dist/umd/popper.js"></script>
    <script src="/common/SaaS_plugin/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="/common/SaaS_plugin/smooth-scrollbar/smooth-scrollbar.min.js"></script>
    <script src="/common/SaaS_plugin/chartjs/chartjs.min.js"></script>
    <!-- Github buttons -->
    <script async defer src="/common/SaaS_plugin/github-btn/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="/common/SaaS_plugin/argon-dashboard/argon-dashboard.min.js?v=2.0.2"></script>
    <!-- sweetalert -->
<script src="/common/IDCC_plugin/sweetalert/dist/sweetalert2.min.js"></script>
    <!-- 首頁js -->
    <script src="./energy/js/export_excel.js"></script>
    <script src="./account/js/timesup.js"></script>
    <script src="./assets/js/oncontextmenu.js"></script>
</body>

</html>
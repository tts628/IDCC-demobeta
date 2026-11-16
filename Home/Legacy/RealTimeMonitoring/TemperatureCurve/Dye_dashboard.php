<?php
$rootPath = $_SERVER['DOCUMENT_ROOT'];

require_once realpath($rootPath . "/common/language/language.php");
$lang = new Language();
$lang->load("header");
$lang->load("menu");
$lang->load("manufacture");

date_default_timezone_set("Asia/Taipei");
?>
<!doctype html>
<html>
<head>
    <script src="/common/IDCC_plugin/deprecated/highcharts/highcharts.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/highcharts-more.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/highcharts-3d.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/sankey.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/variable-pie.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/organization.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/cylinder.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/solid-gauge.js"></script>
    <!-- <script src="/common/IDCC_plugin/deprecated/highcharts/highcharts.src.js"></script> -->
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/accessibility.js"></script>
    <title>染機總覽</title>
    <?php require_once($rootPath . '/common/legacyTemplate/head.php');?>
    <script src="/common/IDCC_plugin/bootstrap-5.0.0-dist/js/bootstrap.js"></script>
    <link rel="stylesheet" href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.css" crossorigin="anonymous">
    <script src="/common/IDCC_plugin/deprecated/DataTables/DataTables/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="/common/IDCC_plugin/deprecated/DataTables/DataTables/css/jquery.dataTables.css">

   <style>
    .mdl-layout--fixed-drawer>.mdl-layout__content {
        margin-left: 0px;
    }
    body{
        background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            background-size: cover;
            zoom:40%;
    }
   </style>
</head>
<body style="background-color:rgb(39, 39, 39);">
                <div class="mdl-card mdl-shadow--2dp">
                    <div class="mdl-card__title">
                        <h1 class="mdl-card__title-text" style="font-size:80px">染機運行總覽 Sơ lược về máy nhuộm</h1>
                    </div>
                </div>

        <div class="row" style="margin-top:2%">
            <div class="col-6 content" id="content0"></div>
            <div class="col-6 content" id="content1"></div>
        </div>
        <div class="row" style="margin-top:2%">
            <div class="col-6 content" id="content2"></div>
            <div class="col-6 content" id="content3"></div>
        </div>
       
<script>
    var machine_num = '<?php echo $_GET['dye_num'] ?? '';?>';
    if (machine_num=='') {machine_num=1;}
    else{machine_num='<?php echo $_GET['dye_num'] ?? '';?>';}
</script>
<script src="./DyeMachines_dashboard_ajax.js"></script>
<script>

    var program = '<?php echo $lang->line("manufacture.manufacture_program");?>';
    var FabricName = '<?php echo $lang->line("manufacture.manufacture_FabricName");?>';
    var TotalWeight = '<?php echo $lang->line("manufacture.manufacture_TotalWeight");?>';
    var DyelotStartTime = '<?php echo $lang->line("manufacture.manufacture_DyelotStartTime");?>';
    var DyelotEndTime = '<?php echo $lang->line("manufacture.manufacture_DyelotEndTime");?>';
    var time_remaining = '<?php echo $lang->line("manufacture.manufacture_time_remaining");?>';
    var TotalLength = '<?php echo $lang->line("manufacture.manufacture_TotalLength");?>';
    var FinishStartTime = '<?php echo $lang->line("manufacture.manufacture_FinishStartTime");?>';
    var FinishEndTime = '<?php echo $lang->line("manufacture.manufacture_FinishEndTime");?>';
    var yard = '<?php echo $lang->line("manufacture.manufacture_yard");?>';
    var MainPumpSpeed = '<?php echo $lang->line("manufacture.manufacture_MainPumpSpeed");?>';
    var ReelSpeed = '<?php echo $lang->line("manufacture.manufacture_ReelSpeed");?>';
    var CycleTime = '<?php echo $lang->line("manufacture.manufacture_CycleTime");?>';
    var Progress = '<?php echo $lang->line("manufacture.manufacture_Progress");?>';
    var Information = '<?php echo $lang->line("manufacture.manufacture_Information");?>';
    var MainSpeed = '<?php echo $lang->line("manufacture.manufacture_MainSpeed");?>';
    var Width = '<?php echo $lang->line("manufacture.manufacture_Width");?>';
    var OverFeed = '<?php echo $lang->line("manufacture.manufacture_OverFeed");?>';

    var Measuring = '<?php echo $lang->line("manufacture.manufacture_Measuring");?>';
    var Step = '<?php echo $lang->line("manufacture.manufacture_Step");?>';
    var ProductCode = '<?php echo $lang->line("manufacture.manufacture_ProductCode");?>';
    var TargetGrams = '<?php echo $lang->line("manufacture.manufacture_TargetGrams");?>';
    var DispenseGrams = '<?php echo $lang->line("manufacture.manufacture_DispenseGrams");?>';
    var ProductName = '<?php echo $lang->line("manufacture.manufacture_ProductName");?>';
    var Machine = '<?php echo $lang->line("manufacture.manufacture_Machine");?>';
    var Measurement_error = '<?php echo $lang->line("manufacture.manufacture_Measurement_error");?>';
    var DispenseSeconds = '<?php echo $lang->line("manufacture.manufacture_DispenseSeconds");?>';

</script>
</body>
</html>

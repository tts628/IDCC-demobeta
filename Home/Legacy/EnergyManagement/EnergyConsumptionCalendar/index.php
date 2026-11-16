<!DOCTYPE html>
<html>

<head>
    <!-- community plugins -->

    <!-- custom plugins -->
    <link rel="stylesheet" href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.css">
    <link href="./View/energyConsumptionCalendar.css" rel="stylesheet">
    <script src="/common/IDCC_plugin/jquery/jquery.min.js"></script>
    <script src="/common/IDCC_plugin/jquery-ui-1.13.2.custom/jquery-ui.min.js"></script>
    <script src="/common/IDCC_plugin/ag-grid/ag-grid-community.min.js"></script>
    <script src="./Controller/calendarGrid.js"></script>
</head>

<body>
    <?php require_once realpath(__DIR__ . '/View/energyConsumptionCalendar.php') ?>
</body>

</html>

<!-- ! important: if query time gets too long, go to Controller/calendarGrid.js and set isDataTooBig = true -->
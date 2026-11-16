<?php
date_default_timezone_set('UTC+8');

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
// $link = $dbMapper->getLink(['SPC']);
$link = $dbMapper->getLink(['IDCC']);

//get month data
$today_class = new DateTime();
$days_to_subtract = 30;
$this_month = $today_class->modify("-{$days_to_subtract} day")->format('Y-m-d');
$sql_month = "SELECT 
    COALESCE(SUM(WaterUsage),0) as Total_Water, 
    COALESCE(SUM(SteamUsage),0) as Total_Steam, 
    COALESCE(SUM(PowerUsage),0) as Total_Power
    FROM DyeEnergyUsage 
    WHERE StartTime >= ?";
$stmt_month = sqlsrv_query($link, $sql_month, array($this_month));

//get date data
$this_day = array(date("Y-m-d"));
$sql_day = "SELECT 
    COALESCE(SUM(WaterUsage),0) as Total_Water, 
    COALESCE(SUM(SteamUsage),0) as Total_Steam, 
    COALESCE(SUM(PowerUsage),0) as Total_Power
    FROM DyeEnergyUsage 
    WHERE StartTime >= ?";
$stmt_day = sqlsrv_query($link, $sql_month, $this_day);

//get machines resource usage
$sql = "SELECT 
        Machine,
        COALESCE(SUM(WaterUsage),0) as Total_Water, 
        COALESCE(SUM(SteamUsage),0) as Total_Steam, 
        COALESCE(SUM(PowerUsage),0) as Total_Power
    FROM (
        SELECT 
            ROW_NUMBER() OVER (ORDER BY Machine) as row_num,
            Machine,
            WaterUsage,
            SteamUsage,
            PowerUsage
        FROM DyeEnergyUsage
    ) as t
    WHERE Machine >= 'M01' AND Machine <= 'M38' AND LEN(Machine) < 4
    GROUP BY Machine
    ORDER BY Machine";
$stmt = sqlsrv_query($link, $sql);
?>
<html>

<head>
    <title>流亞科技</title <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="stylesheet" href=
        "https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.css">

    <!-- <script src=
        "https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js">
        </script> -->
    <!-- <script src=
        "https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js">
        </script> -->
    <!-- <script src=
        "https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js">
        </script> -->

    <style>
        .table thead th,
        .table thead tr {
            border: none;
        }

        .table tbody td,
        .table tbody th {
            max-height: 1px;
        }

        .table-wrapper {
            height: 88vh;
        }

        .table-wrapper .table {
            max-width: 100% !important;
            /* Set the table width to 100% of viewport width */
        }

        /*
            .custom-table tbody tr:nth-of-type(odd) {
                background-color: #e0d6b8;
            }
            .custom-table tbody tr:nth-of-type(even) {
                background-color: #fefefe;
            }
            */
    </style>
</head>

<body style="background-color:#272727" class="text-white">
    <div class="container-fluid">
        <div class="body-wrapper mt-sm-1 mt-lg-1 mt-xl-5 mt-xxl-5">
            <div class="row align-items-center">
                <div class="col-3">
                    <div class="row ml-2">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body font-weight-bold" style="background-color:#8c8c8c">
                                    <div class="row d-flex align-items-center justify-content-center card-title"
                                        style="font-size:1.4vw">
                                        本月
                                    </div>
                                    <?php
                                    while (($rows = sqlsrv_fetch_array($stmt_month, SQLSRV_FETCH_ASSOC))) {
                                        ?>
                                        <div class="row mx-5">
                                            <div class="col-12 d-flex align-items-center justify-content-center"
                                                style="height:5vh;background-color:#00bcd4">
                                                <span style="font-size:1.5vw" class="count"
                                                    data-value="<?php echo $rows["Total_Water"] ?>">0</span>
                                            </div>
                                        </div>
                                        <div class="row mx-5">
                                            <div class="col-12 d-flex align-items-center justify-content-center"
                                                style="height:5vh;background-color:#0094a8">
                                                <span style="font-size:1.5vw">用水量</span>
                                            </div>
                                        </div>
                                        <div class="row mx-5 mt-2">
                                            <div class="col-12 d-flex align-items-center justify-content-center"
                                                style="height:5vh;background-color:#ff5252">
                                                <span style="font-size:1.5vw" class="count"
                                                    data-value="<?php echo $rows["Total_Steam"] ?>">0</span>
                                            </div>
                                        </div>
                                        <div class="row mx-5">
                                            <div class="col-12 d-flex align-items-center justify-content-center"
                                                style="height:5vh;background-color:#ff0000">
                                                <span style="font-size:1.5vw">用蒸氣量</span>
                                            </div>
                                        </div>
                                        <div class="row mx-5 mt-2">
                                            <div class="col-12 d-flex align-items-center justify-content-center"
                                                style="height:5vh;background-color:#00d45a">
                                                <span style="font-size:1.5vw" class="count"
                                                    data-value="<?php echo $rows["Total_Power"] ?>">0</span>
                                            </div>
                                        </div>
                                        <div class="row mx-5">
                                            <div class="col-12 d-flex align-items-center justify-content-center"
                                                style="height:5vh;background-color:#00b04b">
                                                <span style="font-size:1.5vw">用電量</span>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4 ml-2" style="display:none">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body font-weight-bold" style="background-color:#8c8c8c">
                                    <div class="row d-flex align-items-center justify-content-center card-title"
                                        style="font-size:1.4vw">
                                        本日
                                    </div>
                                    <?php
                                    while (($rows = sqlsrv_fetch_array($stmt_day, SQLSRV_FETCH_ASSOC))) {
                                        ?>
                                        <div class="row mx-5">
                                            <div class="col-12 d-flex align-items-center justify-content-center"
                                                style="height:5vh;background-color:#00bcd4">
                                                <span style="font-size:1.5vw" class="count"
                                                    data-value="<?php echo $rows["Total_Water"] ?>">0</span>
                                            </div>
                                        </div>
                                        <div class="row mx-5">
                                            <div class="col-12 d-flex align-items-center justify-content-center"
                                                style="height:5vh;background-color:#0094a8">
                                                <span style="font-size:1.5vw">用水量</span>
                                            </div>
                                        </div>
                                        <div class="row mx-5 mt-2">
                                            <div class="col-12 d-flex align-items-center justify-content-center"
                                                style="height:5vh;background-color:#ff5252">
                                                <span style="font-size:1.5vw" class="count"
                                                    data-value="<?php echo $rows["Total_Steam"] ?>">0</span>
                                            </div>
                                        </div>
                                        <div class="row mx-5">
                                            <div class="col-12 d-flex align-items-center justify-content-center"
                                                style="height:5vh;background-color:#ff0000">
                                                <span style="font-size:1.5vw">用蒸氣量</span>
                                            </div>
                                        </div>
                                        <div class="row mx-5 mt-2">
                                            <div class="col-12 d-flex align-items-center justify-content-center"
                                                style="height:5vh;background-color:#00d45a">
                                                <span style="font-size:1.5vw" class="count"
                                                    data-value="<?php echo $rows["Total_Power"] ?>">0</span>
                                            </div>
                                        </div>
                                        <div class="row mx-5">
                                            <div class="col-12 d-flex align-items-center justify-content-center"
                                                style="height:5vh;background-color:#00b04b">
                                                <span style="font-size:1.5vw">用電量</span>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="row mx-2">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body" style="background-color:#8c8c8c;height:90vh">
                                    <div class="pl-5 ml-3 row card-title font-weight-bold" style="font-size:2vw">
                                        能源耗用統計
                                    </div>
                                    <div class="row">
                                        <div class="col mx-1">
                                            <table class="table table-sm table-striped table-dark table-bordered"
                                                style="font-size:1.18vw">
                                                <thead class="text-center">
                                                    <tr>
                                                        <th>機台</th>
                                                        <th>用水量(L)</th>
                                                        <th>用蒸氣量(Kg)</th>
                                                        <th>用電量(KWH)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $i = 0;
                                                    while (($rows = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
                                                        if ($i < 18) {
                                                            $i++;
                                                            echo "
                                                                    <tr>
                                                                        <td>" . $rows["Machine"] . "</td>
                                                                        <td class='count' data-value=" . $rows["Total_Water"] . ">0</td>
                                                                        <td class='count' data-value=" . $rows["Total_Steam"] . ">0</td>
                                                                        <td class='count' data-value=" . $rows["Total_Power"] . ">0</td>
                                                                    </tr>";
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="col mx-1">
                                            <table class="table table-sm table-striped table-dark table-bordered"
                                                style="font-size:1.18vw">
                                                <thead class="text-center">
                                                    <tr>
                                                        <th>機台</th>
                                                        <th>用水量(L)</th>
                                                        <th>用蒸氣量(Kg)</th>
                                                        <th>用電量(KWH)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $stmt = sqlsrv_query($link, $sql);
                                                    $i = 0;
                                                    while (($rows = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
                                                        $i++;
                                                        if ($i > 18) {
                                                            echo "
                                                                    <tr>
                                                                        <td>" . $rows["Machine"] . "</td>
                                                                        <td class='count' data-value=" . $rows["Total_Water"] . ">0</td>
                                                                        <td class='count' data-value=" . $rows["Total_Steam"] . ">0</td>
                                                                        <td class='count' data-value=" . $rows["Total_Power"] . ">0</td>
                                                                    </tr>";
                                                        }
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    // Get all the elements with class "count"
    const elements = document.getElementsByClassName("count");

    // Define the function to increment the value of an element
    function incrementValue(element, originalValue, targetValue, duration) {
        let currentValue = originalValue;
        const increment = (targetValue - originalValue) / (duration / 10); // Calculate the increment amount per step
        const interval = setInterval(() => {
            currentValue += increment;
            if (currentValue >= targetValue) {
                currentValue = targetValue; // Set the currentValue to the targetValue when we reach or exceed it
                clearInterval(interval); // Stop the interval when we reach or exceed the target value
            }
            element.textContent = Math.floor(currentValue);
        }, 60); // Run the interval every 10 milliseconds
    }

    // Call the function to increment the value of all elements with class "count" to their respective target values in 2.5 seconds
    for (let i = 0; i < elements.length; i++) {
        const originalValue = parseInt(elements[i].textContent); // Get the original value of the element
        const targetValue = parseInt(elements[i].dataset.value); // Get the target value from the data-value attribute
        incrementValue(elements[i], originalValue, targetValue, 150);
    }
</script>
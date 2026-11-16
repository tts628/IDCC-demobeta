<?php
function format_number($var)
{
    $formatted_number = str_replace(",", "", number_format($var, 2));
    return $formatted_number;
}
date_default_timezone_set('UTC+8');

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

//get month data
$today_class = new DateTime();
$days_to_subtract = 30;
$this_month = $today_class->modify("-{$days_to_subtract} day")->format('Y-m-d');

$sql_month = "SELECT 
    COALESCE(SUM(TotalWeight),0) as Summed_Weight
    FROM DyeProduction 
    WHERE EndTime >= ?";


//get today data
$today = date("Y-m-d");

$sql_today = "SELECT 
    COALESCE(SUM(TotalWeight),0) as Summed_Weight
    FROM DyeProduction 
    WHERE EndTime >= ?";


//get yesterday data
$today_class = new DateTime();
$days_to_subtract = 1;
$yesterday = $today_class->modify("-{$days_to_subtract} day")->format('Y-m-d');

$sql_yesterday = "SELECT 
    COALESCE(SUM(TotalWeight),0) as Summed_Weight
    FROM DyeProduction 
    WHERE EndTime >= ? AND EndTime < ?";


//get all results
$sql_1 = "SELECT DyeProduction.Machine, 
    COALESCE(CAST(SUM(CASE WHEN EndTime >= ? THEN DyeProduction.TotalWeight ELSE 0 END) AS DECIMAL(10,2)), '0.00') as Month_Summed,
    COALESCE(CAST(SUM(CASE WHEN EndTime >= ? THEN DyeProduction.TotalWeight ELSE 0 END) AS DECIMAL(10,2)), '0.00') as Today_Summed,
    COALESCE(CAST(SUM(CASE WHEN EndTime >= ? AND EndTime < ? THEN DyeProduction.TotalWeight ELSE 0 END) AS DECIMAL(10,2)), '0.00') as Yesterday_Summed
FROM DyeProduction
LEFT JOIN (
    SELECT DISTINCT Machine
    FROM DyeProduction
) classes ON DyeProduction.Machine = classes.Machine
WHERE classes.Machine <> '' AND classes.Machine <> 'NC'
GROUP BY DyeProduction.Machine
ORDER BY Machine
OFFSET 0 ROWS FETCH NEXT 18 ROWS ONLY";

$sql_2 = "SELECT DyeProduction.Machine, 
    COALESCE(CAST(SUM(CASE WHEN EndTime >= ? THEN DyeProduction.TotalWeight ELSE 0 END) AS DECIMAL(10,2)), '0.00') as Month_Summed,
    COALESCE(CAST(SUM(CASE WHEN EndTime >= ? THEN DyeProduction.TotalWeight ELSE 0 END) AS DECIMAL(10,2)), '0.00') as Today_Summed,
    COALESCE(CAST(SUM(CASE WHEN EndTime >= ? AND EndTime < ? THEN DyeProduction.TotalWeight ELSE 0 END) AS DECIMAL(10,2)), '0.00') as Yesterday_Summed
FROM DyeProduction
LEFT JOIN (
    SELECT DISTINCT Machine
    FROM DyeProduction
) classes ON DyeProduction.Machine = classes.Machine
WHERE classes.Machine <> '' AND classes.Machine <> 'NC'
GROUP BY DyeProduction.Machine
ORDER BY Machine
OFFSET 18 ROWS FETCH NEXT 18 ROWS ONLY";

$params = array($this_month, $today, $yesterday, $today);
$stmt_1 = sqlsrv_prepare($link, $sql_1, $params);
if (!$stmt_1) {
    var_dump(sqlsrv_errors());
    die();
}
if (!sqlsrv_execute($stmt_1)) {
    var_dump(sqlsrv_errors());
    die();
}

$stmt_2 = sqlsrv_prepare($link, $sql_2, $params);
if (!$stmt_2) {
    var_dump(sqlsrv_errors());
    die();
}
if (!sqlsrv_execute($stmt_2)) {
    var_dump(sqlsrv_errors());
    die();
}
?>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.css">
    <script src="/common/IDCC_plugin/jquery/jquery.min.js"></script>

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
        <div class="body-wrapper mt-sm-1 mt-lg-1 mt-xl-3 mt-xxl-5">
            <div class="row font-weight-bold">
                <div class="col-3">
                    <div class="row mx-5 mt-5">
                        <div class="col-12 d-flex align-items-center justify-content-center count"
                            style="height:5vh;font-size:1.5vw;background-color:#00bcd4" data-value="
                                <?php
                                $stmt_month = sqlsrv_query($link, $sql_month, array($this_month));
                                while (($rows = sqlsrv_fetch_array($stmt_month, SQLSRV_FETCH_ASSOC))) {
                                    echo format_number($rows["Summed_Weight"]);
                                }
                                ?>
                                ">0
                        </div>
                    </div>
                    <div class="row mx-5">
                        <div class="col-12 d-flex align-items-center justify-content-center"
                            style="height:5vh;font-size:1.5vw;background-color:#0094a8">
                            本月生產量(Kg)
                        </div>
                    </div>
                    <div class="row mx-5 mt-5">
                        <div class="col-12 d-flex align-items-center justify-content-center count"
                            style="height:5vh;font-size:1.5vw;background-color:#00d45a" data-value="
                                <?php
                                $stmt_today = sqlsrv_query($link, $sql_today, array($today));
                                while (($rows = sqlsrv_fetch_array($stmt_today, SQLSRV_FETCH_ASSOC))) {
                                    echo format_number($rows["Summed_Weight"]);
                                }
                                ?>
                                ">0
                        </div>
                    </div>
                    <div class="row mx-5">
                        <div class="col-12 d-flex align-items-center justify-content-center"
                            style="height:5vh;font-size:1.5vw;background-color:#00b04b">
                            本日生產量(Kg)
                        </div>
                    </div>
                    <div class="row mx-5 mt-5">
                        <div class="col-12 d-flex align-items-center justify-content-center count"
                            style="height:5vh;font-size:1.5vw;background-color:#ffc107" data-value="
                                <?php
                                $stmt_yesterday = sqlsrv_query($link, $sql_yesterday, array(&$yesterday, &$today));
                                while (($rows = sqlsrv_fetch_array($stmt_yesterday, SQLSRV_FETCH_ASSOC))) {
                                    echo format_number($rows["Summed_Weight"]);
                                }
                                ?>
                                ">0
                        </div>
                    </div>
                    <div class="row mx-5">
                        <div class="col-12 d-flex align-items-center justify-content-center"
                            style="height:5vh;font-size:1.5vw;background-color:#daa100">
                            昨日生產量(Kg)
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="row mx-2">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body" style="background-color:#8c8c8c;height:90vh">
                                    <div class="pl-5 ml-3 row font-weight-bold">
                                        <div class="col-5 card-title" style="font-size:2vw">
                                            機台產量統計
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col mx-1">
                                            <table class="table table-sm table-striped table-dark table-bordered"
                                                style="font-size:1.18vw">
                                                <thead class="text-center">
                                                    <tr>
                                                        <th>機台</th>
                                                        <th>本月產量(Kg)</th>
                                                        <th>本日產量(Kg)</th>
                                                        <th>昨日產量(Kg)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    while ($rows = sqlsrv_fetch_array($stmt_1, SQLSRV_FETCH_ASSOC)) {
                                                        echo "
                                                                <tr>
                                                                    <td>" . $rows["Machine"] . "</td>
                                                                    <td class='count' data-value='" . format_number($rows["Month_Summed"]) . "'>0</td>
                                                                    <td class='count' data-value='" . format_number($rows["Today_Summed"]) . "'>0</td>
                                                                    <td class='count' data-value='" . format_number($rows["Yesterday_Summed"]) . "'>0</td>
                                                                </tr>
                                                                ";
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
                                                        <th>本月產量(Kg)</th>
                                                        <th>本日產量(Kg)</th>
                                                        <th>昨日產量(Kg)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    while ($rows = sqlsrv_fetch_array($stmt_2, SQLSRV_FETCH_ASSOC)) {
                                                        echo "
                                                                <tr>
                                                                    <td>" . $rows["Machine"] . "</td>
                                                                    <td class='count' data-value='" . format_number($rows["Month_Summed"]) . "'>0</td>
                                                                    <td class='count' data-value='" . format_number($rows["Today_Summed"]) . "'>0</td>
                                                                    <td class='count' data-value='" . format_number($rows["Yesterday_Summed"]) . "'>0</td>
                                                                </tr>
                                                                ";
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
    $(function () {
        // Get all the elements with class "count"
        const elements = document.getElementsByClassName("count");

        // Define the function to increment the value of an element
        function incrementValue(element, originalValue, targetValue, duration) {
            let currentValue = originalValue;
            const decimalPlaces = (originalValue.toString().split('.')[1] || '').length; // Calculate the number of decimal places in the original value
            const increment = (targetValue - originalValue) / (duration / 10); // Calculate the increment amount per step
            const interval = setInterval(() => {
                currentValue += increment;
                if (currentValue >= targetValue) {
                    currentValue = targetValue; // Set the currentValue to the targetValue when we reach or exceed it
                    clearInterval(interval); // Stop the interval when we reach or exceed the target value
                }
                element.textContent = currentValue.toFixed(2); // Display the value with the same number of decimal places as the original value
            }, 60); // Run the interval every 10 milliseconds
        }


        // Call the function to increment the value of all elements with class "count" to their respective target values in 2.5 seconds
        for (let i = 0; i < elements.length; i++) {
            const originalValue = parseFloat(elements[i].textContent); // Get the original value of the element
            const targetValue = parseFloat(elements[i].dataset.value); // Get the target value from the data-value attribute
            incrementValue(elements[i], originalValue, targetValue, 150);
        }
    })

</script>
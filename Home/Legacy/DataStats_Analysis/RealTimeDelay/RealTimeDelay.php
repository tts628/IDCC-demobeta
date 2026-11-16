<?php
date_default_timezone_set('UTC+8');

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['SPC']);

$sql = "SELECT TOP(20) StartTime,Machine,Dyelot,Name,Duration 
    FROM (
        SELECT StartTime,Machine,Dyelot,Name,Duration,
               ROW_NUMBER() OVER (PARTITION BY Machine ORDER BY StartTime DESC) AS row_num
        FROM Delays
		-- WHERE Name <> 'Normal Running'
    ) AS subquery
    WHERE row_num = 1
    AND Name<>'Normal Running'
    ORDER BY Duration DESC";
$stmt = sqlsrv_query($link, $sql);
?>
<html>

<head>
    <title>流亞科技</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js">
    </script>

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

<body style="background-color:#272727">
    <div class="container-fluid">
        <div class="body-wrapper">
            <div class="row">
                &nbsp;
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="border-top pt-2 pb-2 mb-2">
                        <h1 class="mb-0 fw-bold text-white"><b>即時延遲清單</b></h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                <div class="table-wrapper table-responsive">
                    <table class="text-nowrap table-sm table table-striped text-white m-auto font-weight-bold"
                        style="font-size: 1.6em;">
                        <thead>
                            <tr>
                                <th>開始時間</th>
                                <th>機台</th>
                                <th>工卡號</th>
                                <th>警報名稱</th>
                                <th>持續時間</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody class="table-bordered table-dark">
                            <?php
                            $hasData = false;
                            while (($rows = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
                                $hasData = true;
                                $Duration = round($rows["Duration"] * 86400);
                                $Percentage = round($Duration / 600 * 100);

                                echo "
                                        <tr>
                                            <th>" . $rows["StartTime"]->format("Y/m/d H:i:s") . "</th>
                                            <td>" . $rows["Machine"] . "</td>
                                            <td>" . $rows["Dyelot"] . "</td>
                                            <td>" . $rows["Name"] . "</td>
                                            <td>$Duration 秒</td>
                                            <td style='width:30vw'>
                                                <div class='pt-2'>
                                                    <div class='progress'>
                                                        <div class='progress-bar ";
                                if ($Duration < 60) {
                                    echo "bg-success";
                                } elseif ($Duration >= 60 && $Duration <= 300) {
                                    echo "bg-warning";
                                } else {
                                    echo "bg-danger";
                                }
                                echo "' role='progressbar' aria-valuenow='$Duration' aria-valuemin='0' aria-valuemax='600' style='width: $Percentage%;'></div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <?php
                    if (!$stmt || !$hasData) {
                        echo "<h1 class='border mt-5 py-5 text-center w-100 text-white'>No Data</h1>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
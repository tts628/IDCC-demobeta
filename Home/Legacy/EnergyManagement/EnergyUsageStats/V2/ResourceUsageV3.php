<?php
date_default_timezone_set('Asia/Taipei'); // 設定時區

$_GET["client"] = "AllSeven";

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

// 計算起始與結束時間
// $yesterdayStartTime = date('Y-m-d 00:00:00', strtotime('-1 day'));
$yesterdayStartTime = date('Y-m-d 00:00:00');
$todayStartTime = date('Y-m-d 23:59:59');

// 定義機台清單
$machines = [
    'H01',
    'H02',
    'H03',
    'H04',
    'H05',
    'H06',
    'H07',
    'H08',
    'H09',
    'H10',
    'H11',
    'H12',
    'H13',
    'H14',
    'H15',
    'H16',
    'H18',
    'H19',
    'H20',
    'H21',
    'H22',
    'H24',
    'H25',
    'H27',
    'H28',
    'H29',
    'H30',
    'H31',
    'H32',
    'H33',
    'H34'
];

// Get month data
$today_class = new DateTime();
$days_to_subtract = 30;
$this_month = $today_class->modify("-{$days_to_subtract} day")->format('Y-m-d');
$sql_month = "SELECT 
    COALESCE(SUM(WaterUsage),0)/1000 as Total_Water, 
    COALESCE(SUM(SteamUsage),0)/1000 as Total_Steam, 
    COALESCE(SUM(PowerUsage),0) as Total_Power
    FROM DyeEnergyUsage 
    WHERE StartTime >= ?";
$stmt_month = sqlsrv_query($link, $sql_month, array($this_month));
$month_data = sqlsrv_fetch_array($stmt_month, SQLSRV_FETCH_ASSOC);

// Get day data
$sql_day = "SELECT 
    COALESCE(SUM(WaterUsage),0)/1000 as Total_Water, 
    COALESCE(SUM(SteamUsage),0)/1000 as Total_Steam, 
    COALESCE(SUM(PowerUsage),0) as Total_Power
    FROM DyeEnergyUsage 
    WHERE StartTime >= ? AND StartTime < ?";
$params_day = array($yesterdayStartTime, $todayStartTime);
$stmt_day = sqlsrv_query($link, $sql_day, $params_day);
$day_data = sqlsrv_fetch_array($stmt_day, SQLSRV_FETCH_ASSOC);

// Get machines resource usage from yesterday 8:00 AM to today 8:00 AM
$sql = "SELECT 
        Machine,
        COALESCE(SUM(WaterUsage),0) as Total_Water, 
        COALESCE(SUM(SteamUsage),0) as Total_Steam, 
        COALESCE(SUM(PowerUsage),0) as Total_Power
    FROM DyeEnergyUsage
    WHERE Machine >= 'H01' AND Machine <= 'H38' AND LEN(Machine) < 4 AND StartTime >= ? AND StartTime < ?
    GROUP BY Machine
    ORDER BY Machine";
$params = array($yesterdayStartTime, $todayStartTime);
$stmt = sqlsrv_query($link, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$usage_data = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $usage_data[$row['Machine']] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>能源耗用統計</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            background-color: #2c3e50;
            color: #ecf0f1;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            width: 250px;
            margin: 20px;
            padding: 20px;
            background-color: #34495e;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .sidebar h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .sidebar div {
            margin: 10px 0;
            padding: 15px;
            text-align: center;
            color: white;
            font-weight: bold;
            font-size: 20px;
            border-radius: 8px;
        }

        .sidebar .water {
            background-color: #3498db;
        }

        .sidebar .steam {
            background-color: #e74c3c;
        }

        .sidebar .electricity {
            background-color: #2ecc71;
        }

        .sidebar span {
            display: block;
            margin-top: 5px;
            font-size: 20px;
        }

        .content {
            flex-grow: 1;
            margin: 20px;
            background-color: #34495e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #2c3e50;
            border-radius: 10px;
            overflow: hidden;
        }

        th,
        td {
            border: 2px solid #7f8c8d;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #34495e;
            font-size: 22px;
        }

        td {
            font-size: 20px;
        }

        .highlight {
            background-color: #3b5998;
        }

        .machine-code {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div>
            <h2>近30天</h2>
            <div class="water">用水量(L)<span data-target="<?= $month_data['Total_Water'] ?>">0</span></div>
            <div class="steam">用蒸氣量(Kg)<span data-target="<?= $month_data['Total_Steam'] ?>">0</span></div>
            <div class="electricity">用電量(KHW)<span data-target="<?= $month_data['Total_Power'] ?>">0</span></div>
        </div>
        <div>
            <h2>本日</h2>
            <div class="water">用水量(L)<span data-target="<?= $day_data['Total_Water'] ?>">0</span></div>
            <div class="steam">用蒸氣量(Kg)<span data-target="<?= $day_data['Total_Steam'] ?>">0</span></div>
            <div class="electricity">用電量(KHW)<span data-target="<?= $day_data['Total_Power'] ?>">0</span></div>
        </div>
    </div>
    <div class="content">
        <table>
            <thead>
                <tr>
                    <th>機台</th>
                    <th>用水量(L)</th>
                    <th>用電量(KHW)</th>
                    <th>用蒸氣量(Kg)</th>
                    <th>機台</th>
                    <th>用水量(L)</th>
                    <th>用電量(KHW)</th>
                    <th>用蒸氣量(Kg)</th>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 0; $i < count($machines); $i += 2): ?>
                    <tr>
                        <td class="machine-code"><?= $machines[$i] ?></td>
                        <td
                            data-target="<?= isset($usage_data[$machines[$i]]) ? $usage_data[$machines[$i]]['Total_Water'] : 0 ?>">
                            0</td>
                        <td
                            data-target="<?= isset($usage_data[$machines[$i]]) ? $usage_data[$machines[$i]]['Total_Power'] : 0 ?>">
                            0</td>
                        <td
                            data-target="<?= isset($usage_data[$machines[$i]]) ? $usage_data[$machines[$i]]['Total_Steam'] : 0 ?>">
                            0</td>
                        <?php if ($i + 1 < count($machines)): ?>
                            <td class="machine-code"><?= $machines[$i + 1] ?></td>
                            <td
                                data-target="<?= isset($usage_data[$machines[$i + 1]]) ? $usage_data[$machines[$i + 1]]['Total_Water'] : 0 ?>">
                                0</td>
                            <td
                                data-target="<?= isset($usage_data[$machines[$i + 1]]) ? $usage_data[$machines[$i + 1]]['Total_Power'] : 0 ?>">
                                0</td>
                            <td
                                data-target="<?= isset($usage_data[$machines[$i + 1]]) ? $usage_data[$machines[$i + 1]]['Total_Steam'] : 0 ?>">
                                0</td>
                        <?php else: ?>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        <?php endif; ?>
                    </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const animateValue = (el, target) => {
                const formatNumber = (num) => {
                    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                };

                const increment = target / 200;
                let currentValue = 0;

                const updateCounter = () => {
                    currentValue += increment;
                    if (currentValue < target) {
                        el.innerText = formatNumber(Math.ceil(currentValue));
                        requestAnimationFrame(updateCounter);
                    } else {
                        el.innerText = formatNumber(target);
                    }
                };

                updateCounter();
            };

            const elements = document.querySelectorAll("[data-target]");
            elements.forEach(el => {
                const target = +el.getAttribute("data-target");
                animateValue(el, target);
            });
        });
    </script>
</body>

</html>
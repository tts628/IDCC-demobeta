<?php
date_default_timezone_set('UTC+8');

$_GET["client"] = "AllSeven";

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

// 計算起始與結束時間
// $yesterdayStartTime = date('Y-m-d 00:00:00', strtotime('-1 day'));
$yesterdayStartTime = date('Y-m-d 00:00:00');
$todayStartTime = date('Y-m-d 23:59:59');

// 定义固定的机器台列表
$machines = ['H01', 'H02', 'H03', 'H04', 'H05', 'H06', 'H07', 'H08', 'H09', 'H10', 'H11', 'H12', 'H13', 'H14', 'H15', 'H16', 'H18', 'H19', 'H20', 'H21', 'H22', 'H24', 'H25', 'H27', 'H28', 'H29', 'H30', 'H31', 'H32', 'H33', 'H34'];

// 初始化能耗数据
$waterUsage = array_fill(0, count($machines), 0);
$steamUsage = array_fill(0, count($machines), 0);
$powerUsage = array_fill(0, count($machines), 0);

// Get machines resource usage from yesterday 8:00 AM to today 8:00 AM
$sql = "SELECT 
    Machine,
    COALESCE(SUM(WaterUsage),0) as Total_Water, 
    COALESCE(SUM(SteamUsage),0) as Total_Steam, 
    COALESCE(SUM(PowerUsage),0) as Total_Power
FROM DyeEnergyUsage
WHERE Machine IN ('" . implode("','", $machines) . "') AND StartTime >= ? AND StartTime < ?
GROUP BY Machine
ORDER BY Machine";
$params = array($yesterdayStartTime, $todayStartTime);
$stmt = sqlsrv_query($link, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// 将查询结果填充到固定的能耗数据中
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $index = array_search($row['Machine'], $machines);
    if ($index !== false) {
        $waterUsage[$index] = $row['Total_Water'];
        $steamUsage[$index] = $row['Total_Steam'];
        $powerUsage[$index] = $row['Total_Power'];
    }
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
            zoom: 1.2;
        }

        .content {
            flex-grow: 1;
            margin: 20px;
            background-color: #34495e;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .chart-container {
            width: 100%;
            height: 600px;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="content">
        <div class="chart-container">
            本日能耗統計
            <canvas id="energyChart"></canvas>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const labels = <?php echo json_encode($machines); ?>;
            const waterUsage = <?php echo json_encode($waterUsage); ?>;
            const steamUsage = <?php echo json_encode($steamUsage); ?>;
            const powerUsage = <?php echo json_encode($powerUsage); ?>;

            const ctx = document.getElementById('energyChart').getContext('2d');
            const energyChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: '用水量(L)',
                            data: waterUsage,
                            backgroundColor: '#3498db',
                            borderWidth: 1,
                            yAxisID: 'y-water'
                        },
                        {
                            label: '用電量(KHW)',
                            data: powerUsage,
                            backgroundColor: '#2ecc71',
                            borderWidth: 1,
                            yAxisID: 'y-electricity'
                        },
                        {
                            label: '用蒸氣量(Kg)',
                            data: steamUsage,
                            backgroundColor: '#e74c3c',
                            borderWidth: 1,
                            yAxisID: 'y-steam'
                        }
                    ]
                },
                options: {
                    animation: {
                        duration: 5000 // 动画时间为5秒
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                color: '#ecf0f1' // White labels
                            },
                            grid: {
                                color: '#ecf0f1' // White grid lines
                            },
                            categoryPercentage: 0.5,
                            barPercentage: 0.5
                        },
                        'y-water': {
                            beginAtZero: true,
                            position: 'left',
                            ticks: {
                                color: '#ecf0f1' // White labels
                            },
                            grid: {
                                color: '#ecf0f1' // White grid lines
                            },
                            title: {
                                display: true,
                                text: '用水量(L)',
                                color: '#ecf0f1'
                            }
                        },
                        'y-electricity': {
                            beginAtZero: true,
                            position: 'right',
                            ticks: {
                                color: '#ecf0f1' // White labels
                            },
                            grid: {
                                color: '#ecf0f1', // White grid lines
                                drawOnChartArea: false // only want the grid lines for one axis to show up
                            },
                            title: {
                                display: true,
                                text: '用電量(KHW)',
                                color: '#ecf0f1'
                            }
                        },
                        'y-steam': {
                            beginAtZero: true,
                            position: 'right',
                            ticks: {
                                color: '#ecf0f1' // White labels
                            },
                            grid: {
                                color: '#ecf0f1', // White grid lines
                                drawOnChartArea: false // only want the grid lines for one axis to show up
                            },
                            title: {
                                display: true,
                                text: '用蒸氣量(Kg)',
                                color: '#ecf0f1'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                color: '#ecf0f1' // White labels
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>
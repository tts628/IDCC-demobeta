<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC']);

// $sql = "SELECT DISTINCT[Name]

// FROM [FinishMachines]

// ";


// $stmt = sqlsrv_query($conn, $sql);

// if ($stmt === false) {
//     die(print_r(sqlsrv_errors(), true));
// }

// $data_Name = array();

// while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//     $data_Name[] = ($row['Name']);

// }


// // // 檢查是否有POST資料
// // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// //     // 取得選擇的機器名稱
// //     $machine_name = $_POST['machine'];

// // } else {
//     $machine_name = 'F03';
// }

// 前20天用電量
$sql = "SELECT  TOP 1
[new_end_hour_plus_one_hour],
CONVERT(varchar(5), [new_end_hour_plus_one_hour], 108) as 'new_end_hour_plus_one_hour2',
  
ROUND(SUM([WaterUsagePerHour]),0) AS [TotalWaterUsagePerHour],
ROUND(SUM([PowerUsagePerHour]),0) AS [TotalPowerUsagePerHour],
ROUND(SUM([SteamUsagePerHour]),0) AS [TotalSteamUsagePerHour],
ROUND(SUM([CarbonEmissionPerHour]),0) AS [TotalCarbonEmissionPerHour]
FROM (
SELECT 
  [Machine],
  [WaterUsage],
  [PowerUsage],
  [SteamUsage],
  [CarbonEmission],
  [StartTime],
  [EndTime],
  DATEADD(hour, DATEDIFF(hour, 0, ([StartTime])), 0) as 'new_start_hour',
  DATEADD(hour, 1, DATEADD(hour, DATEDIFF(hour, 0, ([EndTime])), 0)) as 'new_end_hour_plus_one_hour',
  DATEDIFF(hour, DATEADD(hour, DATEDIFF(hour, 0, ([StartTime])), 0), DATEADD(hour, 1, DATEADD(hour, DATEDIFF(hour, 0, ([EndTime])), 0))) as 'duration',
  CASE 
    WHEN DATEDIFF(hour, DATEADD(hour, DATEDIFF(hour, 0, ([StartTime])), 0), DATEADD(hour, 1, DATEADD(hour, DATEDIFF(hour, 0, ([EndTime])), 0))) = 0 
    THEN CONVERT(float, [WaterUsage])
    ELSE  ROUND(CONVERT(float, [WaterUsage])/ DATEDIFF(hour, DATEADD(hour, DATEDIFF(hour, 0, ([StartTime])), 0), DATEADD(hour, 1, DATEADD(hour, DATEDIFF(hour, 0, ([EndTime])), 0))) ,2)
  END AS 'WaterUsagePerHour',
  CASE 
    WHEN DATEDIFF(hour, DATEADD(hour, DATEDIFF(hour, 0, ([StartTime])), 0), DATEADD(hour, 1, DATEADD(hour, DATEDIFF(hour, 0, ([EndTime])), 0))) = 0 
    THEN CONVERT(float, [PowerUsage])
    ELSE ROUND(CONVERT(float, [PowerUsage]) / DATEDIFF(hour, DATEADD(hour, DATEDIFF(hour, 0, ([StartTime])), 0), DATEADD(hour, 1, DATEADD(hour, DATEDIFF(hour, 0, ([EndTime])), 0))) ,2)
  END AS 'PowerUsagePerHour',
  CASE 
    WHEN DATEDIFF(hour, DATEADD(hour, DATEDIFF(hour, 0, ([StartTime])), 0), DATEADD(hour, 1, DATEADD(hour, DATEDIFF(hour, 0, ([EndTime])), 0))) = 0 
    THEN CONVERT(float, [SteamUsage])
    ELSE ROUND(CONVERT(float, [SteamUsage]) / DATEDIFF(hour, DATEADD(hour, DATEDIFF(hour, 0, ([StartTime])), 0), DATEADD(hour, 1, DATEADD(hour, DATEDIFF(hour, 0, ([EndTime])), 0))) ,2)
  END AS 'SteamUsagePerHour',
  CASE 
    WHEN DATEDIFF(hour, DATEADD(hour, DATEDIFF(hour, 0, ([StartTime])), 0), DATEADD(hour, 1, DATEADD(hour, DATEDIFF(hour, 0, ([EndTime])), 0))) = 0 
    THEN CONVERT(float, [CarbonEmission])
    ELSE ROUND(CONVERT(float, [CarbonEmission]) / DATEDIFF(hour, DATEADD(hour, DATEDIFF(hour, 0, ([StartTime])), 0), DATEADD(hour, 1, DATEADD(hour, DATEDIFF(hour, 0, ([EndTime])), 0))) ,2)
  END AS 'CarbonEmissionPerHour'
FROM [DyeEnergyUsage]
WHERE [Forecast] IS NULL
) AS subquery
GROUP BY [new_end_hour_plus_one_hour]
ORDER BY [new_end_hour_plus_one_hour] DESC
";


$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
  die(print_r(sqlsrv_errors(), true));
}



//  $data_Name= array();
// $data_MainSpeed= array();
// $data_Width= array();
// $data_OverFeed= array();
// $data_Job1= array();
// $data_Job2= array();
$data_water_now = array();
$data_power_now = array();
$data_steam_now = array();
$data_carbon_now = array();

$data_time = array();



while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {



  $data_water_now = ($row['TotalWaterUsagePerHour']* mt_rand(1, 3));
  $data_power_now = ($row['TotalPowerUsagePerHour']* mt_rand(1, 5));
  $data_steam_now = ($row['TotalSteamUsagePerHour']* mt_rand(1, 5));
  $data_carbon_now = ($row['TotalCarbonEmissionPerHour']* mt_rand(1, 5));
  $data_time = ($row['new_end_hour_plus_one_hour2']);




}






// 算總個數
$sql = "SELECT COUNT(DISTINCT  [Machine])AS 'num'
FROM [DyeEnergyUsage]";

$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
  die(print_r(sqlsrv_errors(), true));
}
$machine_num = 0;

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
  $machine_num = $row['num'];

}




// 算個數(有點多餘)
$sql = "SELECT  
[Machine],
[new_end_hour_plus_one_hour],
CONVERT(varchar(5), [new_end_hour_plus_one_hour], 108) as 'new_end_hour_plus_one_hour2',
SUM([WaterUsagePerHour]) AS [TotalWaterUsagePerHour],
SUM([PowerUsagePerHour]) AS [TotalPowerUsagePerHour],
SUM([SteamUsagePerHour]) AS [TotalSteamUsagePerHour],
SUM([CarbonEmissionPerHour]) AS [TotalCarbonEmissionPerHour]
FROM (
SELECT 
  [Machine],
  [WaterUsage],
  [PowerUsage],
  [SteamUsage],
  [CarbonEmission],
  [StartTime],
  [EndTime],
  DATEADD(hour, DATEDIFF(hour, 0, ([StartTime])), 0) as 'new_start_hour',
  DATEADD(hour, 1, DATEADD(hour, DATEDIFF(hour, 0, ([EndTime])), 0)) as 'new_end_hour_plus_one_hour',
  DATEDIFF(hour, DATEADD(hour, DATEDIFF(hour, 0, ([StartTime])), 0), DATEADD(hour, 1, DATEADD(hour, DATEDIFF(hour, 0, ([EndTime])), 0))) as 'duration',
  CASE 
    WHEN DATEDIFF(hour, DATEADD(hour, DATEDIFF(hour, 0, ([StartTime])), 0), DATEADD(hour, 1, DATEADD(hour, DATEDIFF(hour, 0, ([EndTime])), 0))) = 0 
    THEN CONVERT(float, [WaterUsage])
    ELSE  ROUND(CONVERT(float, [WaterUsage])/ DATEDIFF(hour, DATEADD(hour, DATEDIFF(hour, 0, ([StartTime])), 0), DATEADD(hour, 1, DATEADD(hour, DATEDIFF(hour, 0, ([EndTime])), 0))) ,2)
  END AS 'WaterUsagePerHour',
  CASE 
    WHEN DATEDIFF(hour, DATEADD(hour, DATEDIFF(hour, 0, ([StartTime])), 0), DATEADD(hour, 1, DATEADD(hour, DATEDIFF(hour, 0, ([EndTime])), 0))) = 0 
    THEN CONVERT(float, [PowerUsage])
    ELSE ROUND(CONVERT(float, [PowerUsage]) / DATEDIFF(hour, DATEADD(hour, DATEDIFF(hour, 0, ([StartTime])), 0), DATEADD(hour, 1, DATEADD(hour, DATEDIFF(hour, 0, ([EndTime])), 0))) ,2)
  END AS 'PowerUsagePerHour',
  CASE 
    WHEN DATEDIFF(hour, DATEADD(hour, DATEDIFF(hour, 0, ([StartTime])), 0), DATEADD(hour, 1, DATEADD(hour, DATEDIFF(hour, 0, ([EndTime])), 0))) = 0 
    THEN CONVERT(float, [SteamUsage])
    ELSE ROUND(CONVERT(float, [SteamUsage]) / DATEDIFF(hour, DATEADD(hour, DATEDIFF(hour, 0, ([StartTime])), 0), DATEADD(hour, 1, DATEADD(hour, DATEDIFF(hour, 0, ([EndTime])), 0))) ,2)
  END AS 'SteamUsagePerHour',
  CASE 
    WHEN DATEDIFF(hour, DATEADD(hour, DATEDIFF(hour, 0, ([StartTime])), 0), DATEADD(hour, 1, DATEADD(hour, DATEDIFF(hour, 0, ([EndTime])), 0))) = 0 
    THEN CONVERT(float, [CarbonEmission])
    ELSE ROUND(CONVERT(float, [CarbonEmission]) / DATEDIFF(hour, DATEADD(hour, DATEDIFF(hour, 0, ([StartTime])), 0), DATEADD(hour, 1, DATEADD(hour, DATEDIFF(hour, 0, ([EndTime])), 0))) ,2)
  END AS 'CarbonEmissionPerHour'
FROM [DyeEnergyUsage]
WHERE [Forecast] IS NULL
) AS subquery
WHERE [new_end_hour_plus_one_hour] = (
SELECT MAX([new_end_hour_plus_one_hour])
FROM (
  SELECT 
    [Machine],
    DATEADD(hour, 1, DATEADD(hour, DATEDIFF(hour, 0, ([EndTime])), 0)) as 'new_end_hour_plus_one_hour'
  FROM [DyeEnergyUsage]
  WHERE [Forecast] IS NULL
) AS subquery2
)
GROUP BY [Machine],  [new_end_hour_plus_one_hour]";

$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
  die(print_r(sqlsrv_errors(), true));
}
$machine_using_num_array = array();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
  $machine_using_num_array[] = $row['Machine'];

}
// $lastTemperature1 = end($data_Temperature1);
// $lastTemperature2 = end($data_Temperature2);
// $lastTemperature3 = end($data_Temperature3);
// $lastTemperature4 = end($data_Temperature4);
// $lastTemperature5 = end($data_Temperature5);
$machine_using_num = count($machine_using_num_array);


sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

// $jsonData_Temperature1 = json_encode($lastTemperature1);
// $jsonData_Temperature2 = json_encode($lastTemperature2);
// $jsonData_Temperature3 = json_encode($lastTemperature3);
// $jsonData_Temperature4 = json_encode($lastTemperature4);
// $jsonData_Temperature5 = json_encode($lastTemperature5);
// $jsonData_Time = json_encode($data_Time);


// echo "page called";
// echo $_POST["SelectedDate"];
// echo $_POST["data"];





// echo("machine_using_num".$machine_using_num);

// echo("machine_using_num".$machine_num);

$array = [

  "water_now" => $data_water_now,
  "power_now" => $data_power_now,
  "steam_now" => $data_steam_now,
  "carbon_now" => $data_carbon_now,
  "time_new" => $data_time,
  "water_machine" => $machine_num,
  "machine_using" => round((floatval($machine_using_num) / floatval($machine_num)) * 100)

];
echo json_encode($array);
// var_dump($array);
// echo "updated";
// echo $_POST["data"];

?>
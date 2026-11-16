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
$sql = "SELECT [new_start_hour],
CONVERT(varchar(5), [new_start_hour], 108) as 'new_start_hour2',
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
--WHERE  [StartTime] >= CONVERT(date, GETDATE()) AND [EndTime] < DATEADD(day, 1, CONVERT(date, GETDATE()))
GROUP BY [new_end_hour_plus_one_hour],[new_start_hour]
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



$data_today_start = array();
$data_today_end = array();


while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {



  $data_water_now[] = ($row['TotalWaterUsagePerHour']);
  $data_power_now[] = ($row['TotalPowerUsagePerHour']);
  $data_steam_now[] = ($row['TotalSteamUsagePerHour']);
  $data_carbon_now[] = ($row['TotalCarbonEmissionPerHour']);
  $data_time = ($row['new_end_hour_plus_one_hour2']);
  $data_today_start[] = ($row['new_start_hour2']);
  $data_today_end[] = ($row['new_end_hour_plus_one_hour2']);

}
// echo((int)$data_today_start[0]);
// echo((int)$data_today_start[1]);

// echo((int)$data_today_end[0]);
// echo((int)$data_today_end[1]);


$data_water_hour = array();
$data_power_hour = array();
$data_steam_hour = array();
$data_carbon_hour = array();

for ($i = 0; $i < 24; $i++) {
  $data_water_hour[$i] = 0; // 將每個元素初始化為 0
  $data_power_hour[$i] = 0;
  $data_steam_hour[$i] = 0;
  $data_carbon_hour[$i] = 0;
}

for ($i = 0; $i < count($data_today_start); $i++) {
  for ($j = 0; $j < 24; $j++) {
    if ((int) $data_today_start[$i] <= $j && (int) $data_today_end[$i] >= $j) {
      $data_water_hour[$j] += $data_water_now[$i];
      $data_power_hour[$j] += $data_power_now[$i];
      $data_steam_hour[$j] += $data_steam_now[$i];
      $data_carbon_hour[$j] += $data_carbon_now[$i];
    } else {
      // $data_water_hour [$j]+=0;
      // $data_power_hour [$j]+=0;
      // $data_steam_hour [$j]+=0;
      // $data_carbon_hour[$j]+=0;
    }
    // echo($data_water_hour[$j].",");
  }
  // echo("間隔");
}

// var_dump($data_water_hour);
// var_dump($data_power_hour);
// var_dump($data_steam_hour);
// var_dump($data_carbon_hour);
// for($i=0;$i<24;$i++)
// {
//   if((int)$data_today_start[$i]==$i){
//     $data_water_hour[$i] += 1000000;
//   }
//   else{
//     $data_water_hour[$i] += 0;
//   }

//   echo("data_water_hour : " . $data_water_hour[$i]);
// }


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







$array = [

  // "water_now"=> $data_water_now,
// "power_now"=> $data_power_now,
// "steam_now"=> $data_steam_now ,
// "carbon_now"=> $data_carbon_now,
// "time_new"=> $data_time,
// "water_machine"=>$machine_num,
// "machine_using"=>round((floatval($machine_using_num)/floatval($machine_num))*100)
  "data_water_hour" => $data_water_hour,
  "data_power_hour" => $data_power_hour,
  "data_steam_hour" => $data_steam_hour,
  "data_carbon_hour" => $data_carbon_hour
];
echo json_encode($array);
// var_dump($array);
// echo "updated";
// echo $_POST["data"];

?>
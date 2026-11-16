<?php

require_once('./database.php');
// $host= "118.163.114.237";
// $post="1433";
// // $serverName ="LAPTOP-FCVBPGI5\\SQLEXPRESS";
// $Database="Demo_IDCC";
// $UID="SA";
// $PWD="La84160206";

// $serverName=$host."\\sqlexpress,".$post;
// //$serverName = ".\SQLEXPRESS"; // SQL Server 伺服器名稱
// $connectionInfo=array("Database"=>$Database,"UID"=>$UID,"PWD"=>$PWD,"CharacterSet"=>"UTF-8","ReturnDatesAsStrings"=>true);
// $conn=sqlsrv_connect($serverName,$connectionInfo);


$sql = "SELECT DISTINCT[Name]
                
FROM [Demo_IDCC].[dbo].[FinishMachines]

";


$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$data_Name = array();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $data_Name[] = ($row['Name']);

}


// // 檢查是否有POST資料
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // 取得選擇的機器名稱
//     $machine_name = $_POST['machine'];

// } else {
    $machine_name = 'F03';
// }

// 前20天用電量
$sql = "SELECT [Name],
                [MainSpeed],
                [Width],
                [OverFeed],
                [Job1],
                [Job2],
                COALESCE([Temperature1],0) as 'Temperature1',
                [Temperature2],
                [Temperature3],
                [Temperature4],
                [Temperature5],
                CONVERT(varchar, [JobStartTime], 102) AS 'Date',
                CONVERT(varchar, [JobStartTime], 108) AS 'Time'
FROM [Demo_IDCC].[dbo].[FinishMachines]
WHERE [Name] = '$machine_name'
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
$data_Temperature1 = array();
$data_Temperature2 = array();
$data_Temperature3 = array();
$data_Temperature4 = array();
$data_Temperature5 = array();
$data_Time = array();



while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {




    $data_MainSpeed = ($row['MainSpeed']);
    $data_Width = ($row['Width']);
    $data_OverFeed = ($row['OverFeed']);
    $data_Job1 = ($row['Job1']);
    $data_Job2 = ($row['Job2']);
    $data_Temperature1[] = array($row['Temperature1']);
    $data_Temperature2[] = array($row['Temperature2']);
    $data_Temperature3[] = array($row['Temperature3']);
    $data_Temperature4[] = array($row['Temperature4']);
    $data_Temperature5[] = array($row['Temperature5']);
    $data_Time[] = array($row['Time']);


}

$lastTemperature1 = end($data_Temperature1);
$lastTemperature2 = end($data_Temperature2);
$lastTemperature3 = end($data_Temperature3);
$lastTemperature4 = end($data_Temperature4);
$lastTemperature5 = end($data_Temperature5);


sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

$jsonData_Temperature1 = json_encode($lastTemperature1);
$jsonData_Temperature2 = json_encode($lastTemperature2);
$jsonData_Temperature3 = json_encode($lastTemperature3);
$jsonData_Temperature4 = json_encode($lastTemperature4);
$jsonData_Temperature5 = json_encode($lastTemperature5);
$jsonData_Time = json_encode($data_Time);


// echo "page called";
// echo $_POST["SelectedDate"];
// echo $_POST["data"];







$array = [
    "data_MainSpeed" => $data_MainSpeed,
    "data_Width" => $data_Width,
    "data_OverFeed" => $data_OverFeed,
    "data_Job1" => $data_Job1,
    "data_Job2" => $data_Job2,
    "data_Time" => $data_Time,
    "lastTemperature1" => $lastTemperature1,
    "lastTemperature2" => $lastTemperature2,
    "lastTemperature3" => $lastTemperature3,
    "lastTemperature4" => $lastTemperature4,
    "lastTemperature5" => $lastTemperature5
    
    

];
    echo json_encode($array);
    // var_dump($array);
    // echo "updated";
    // echo $_POST["data"];

?>
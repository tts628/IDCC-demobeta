<?php
date_default_timezone_set('UTC+8');

$today = new DateTime();
$interval = new DateInterval('P30D');
$thirty_days_ago = $today->sub($interval)->format('Y-m-d');

//$this_month = date("Y-m-01");

$params = array($thirty_days_ago);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['SPC']);

$sql = "SELECT TOP 10 Name,SUM(Duration) as Total_Duration
    FROM Delays
    WHERE StartTime >= ?
    AND Name<> 'Normal Running'
    GROUP BY Name
    ORDER BY Total_Duration DESC";

$stmt = sqlsrv_query($link, $sql, $params);
while (($rows = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
    $data[] = [
        $rows["Name"],
        $rows["Total_Duration"] * 1440
    ];
    $sqldata[] = $rows;
}

$filename = "delay_reasons_data.json"; // Change this to the JSON file you want to work with
// Check if the JSON file exists
if (file_exists($filename)) {
    file_put_contents($filename, json_encode($sqldata));
}
$jsonData = json_encode($data);

echo $jsonData;
?>
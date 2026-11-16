<?php
function Writecsv($filename, $data)
{
    //Write to local csv
    $f = fopen($filename, 'w');
    if ($f === false) {
        die('Error writing the file ' . $filename);
    }
    foreach ($data as $row) {
        fputcsv($f, $row);
    }
    fclose($f);
}
function Readcsv($filename)
{
    //fetching data from local csv
    $f = fopen($filename, 'r');
    if ($f === false) {
        die('Cannot raed the file ' . $filename);
    }
    while (($row = fgetcsv($f)) !== false) {
        $readData[] = $row;
    }
    fclose($f);
    if (!isset($readData)) {
        $readData = [[0]];
    }
    return $readData;
}


if (isset($_POST["time"])) {
    $quarterDayBefore = $_POST["time"];
    echo $time;
} else {
    echo "Uncaught error, using auto-assign value<br>";
    date_default_timezone_set('UTC+8');
    $date = new DateTime();
    $date->modify('-48 hours');
    $quarterDayBefore = $date->format('Y-m-d H:i:s');
}

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);
$pdo = $dbMapper->getPdo(['IDCC']);

$sql_count = "SELECT COUNT(Job) AS Count FROM JobEvent";
$stmt_count = sqlsrv_query($link, $sql_count);
while (($rows = sqlsrv_fetch_array($stmt_count, SQLSRV_FETCH_ASSOC))) {
    $count = $rows["Count"];
}

$sql = "SELECT What,Job 
    FROM JobEvent 
    WHERE What IS NOT NULL
    ORDER BY [When] DESC";

$stmt = sqlsrv_query($link, $sql);
while (($rows = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
    $data[] = [
        $rows["Job"],
        $rows["What"]
    ];
}

Writecsv("UrgentEvent.csv", $data);
echo "Data wrote to csv";
?>
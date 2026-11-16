
<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

$startDateTime = $_POST['startDateTime'] . " 00:00:00:000";
$endDateTime = $_POST['endDateTime'] . " 23:59:59:000";

/*

SELECT distinct(Batch) FROM  DyeProduction 
order by Batch

*/


$sql = "SELECT distinct(Batch) FROM  DyeProduction 
WHERE StartTime >= '$startDateTime'
AND EndTime <= '$endDateTime'
AND Batch <>''
AND Batch not like '%測試%'

order by Batch


";

$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
  die(print_r(sqlsrv_errors(), true));
}


while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
  $options[]=$row["Batch"];
}

   

    sqlsrv_close($conn);
    echo json_encode($options);

  ?>  
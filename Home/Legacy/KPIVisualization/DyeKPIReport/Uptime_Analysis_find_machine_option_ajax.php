
<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

$startDateTime = $_POST['startDateTime'] . " 00:00:00:000";
$endDateTime = $_POST['endDateTime'] . " 23:59:59:000";

/*


SELECT distinct(Machine) FROM  DyeProduction 
order by Machine

*/


$sql = "SELECT distinct(Machine) FROM  DyeProduction 
WHERE (StartTime >= '$startDateTime'
AND StartTime <= '$endDateTime')
or
(EndTime >= '$startDateTime'
AND EndTime <= '$endDateTime')
order by Machine


";

$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
  die(print_r(sqlsrv_errors(), true));
}


while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
  $options[]=$row["Machine"];
}

   

    sqlsrv_close($conn);
    echo json_encode($options);

  ?>  
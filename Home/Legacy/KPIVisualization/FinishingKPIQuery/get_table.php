<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

$machine_name = $_POST['machine_name'];
if (
    stripos($machine_name, " OR ") !== false ||
    stripos($machine_name, " ") !== false ||
    stripos($machine_name, "%") !== false ||
    stripos($machine_name, "'") !== false ||
    stripos($machine_name, "{") !== false ||
    stripos($machine_name, "}") !== false ||
    stripos($machine_name, "[") !== false ||
    stripos($machine_name, "]") !== false ||
    stripos($machine_name, "(") !== false ||
    stripos($machine_name, ")") !== false ||
    stripos($machine_name, "<") !== false ||
    stripos($machine_name, ">") !== false ||
    stripos($machine_name, "#") !== false ||
    stripos($machine_name, "!") !== false ||
    stripos($machine_name, "|") !== false ||
    stripos($machine_name, "&") !== false ||
    stripos($machine_name, ";") !== false ||
    stripos($machine_name, ":") !== false ||
    stripos($machine_name, "$") !== false ||
    stripos($machine_name, "*") !== false ||
    stripos($machine_name, "=") !== false ||
    stripos($machine_name, ",") !== false ||
    stripos($machine_name, ".") !== false ||
    strlen($machine_name) > 15) {
    echo "detected sensitive cases<br>";
    $machine_name = null;
}
//$machine_name = 'A01';

$sql_machine = "select * from FinishMachines where Name='$machine_name' ORDER BY ID ASC";
$result_machine = sqlsrv_query($link, $sql_machine) or die("sql error" . sqlsrv_errors());
while ($row_machine = sqlsrv_fetch_array($result_machine)) {

    $note = $row_machine['Note'];
    if ($note == '') {
        $note = '';
    }

    $sql = "select TOP 10 * from FinishEvent where Machine ='$machine_name' order by CreateDate desc";
    $result = sqlsrv_query($link, $sql, array(), array("Scrollable" => 'static'));

    $num = sqlsrv_num_rows($result);
    //    echo json_encode($num);

    //    echo $num;

    while ($row = sqlsrv_fetch_array($result)) {
        $description = $row['Description'];
        $create_date = ($row['CreateDate']->format('Y-m-d H:i:s'));
        $state = $row['IdccEventType'];
        $table_arr[] = array('name' => $machine_name, 'description' => $description, 'note' => $note, 'date' => $create_date, 'state' => $state);

    }



}


//echo $table_arr;
echo json_encode($table_arr);

?>
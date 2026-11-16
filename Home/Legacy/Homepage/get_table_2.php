<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

// $sql_machine = "select * from DispenserMachines ORDER BY ID ASC";
$sql_machine = "select distinct([Name]) FROM [DispenserMachines]";
$result_machine = sqlsrv_query($link, $sql_machine) or die("sql error" . sqlsrv_errors());
while ($row_machine = sqlsrv_fetch_array($result_machine)) {
    $machine = $row_machine['Name'];

    // $note = $row_machine['Note'];
    $note = '';

    if ($note == '') {
        $note = '';
    }

    $sql = "select TOP 1 * from DispenserEvent where Machine   ='$machine' order by CreateDate desc";
    $result = sqlsrv_query($link, $sql, array(), array( "Scrollable" => 'static' ));

    $num = sqlsrv_num_rows($result);
//    echo json_encode($num);

//    echo $num;
    if ($num > 0) {
        while ($row = sqlsrv_fetch_array($result)) {
            $description = $row['Description'];
            $create_date = ($row['CreateDate']->format('Y-m-d H:i:s'));
            // $state = $row['IdccEventType'];
            $state = $row['EventType'];
            

        }
    } else {
        $description = '無異狀';
        $create_date = '';
        $state = '';

    }

    $table_arr[] = array('name' => $machine, 'description' => $description, 'note' => $note, 'date' => $create_date, 'state' => $state);

}


//echo $table_arr;
echo json_encode($table_arr);

?>
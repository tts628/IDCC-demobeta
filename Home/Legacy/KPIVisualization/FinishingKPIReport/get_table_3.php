<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

// $sql_machine = "select * from FinishMachines ORDER BY ID ASC";
// $result_machine = sqlsrv_query($link, $sql_machine) or die("sql error" . sqlsrv_errors());
// while ($row_machine = sqlsrv_fetch_array($result_machine)) {
//     $machine = $row_machine['Name'];

//     $note = $row_machine['Note'];
//     if ($note == '') {
//         $note = '';
//     }

//     $sql = "select TOP 1 * from FinishEvent where Machine ='$machine' order by CreateDate desc";
//     $result = sqlsrv_query($link, $sql, array(), array( "Scrollable" => 'static' ));

//     $num = sqlsrv_num_rows($result);
// //    echo json_encode($num);

// //    echo $num;
//     if ($num > 0) {
//         while ($row = sqlsrv_fetch_array($result)) {
//             $description = $row['Description'];
//             $create_date = ($row['CreateDate']->format('Y-m-d H:i:s'));
//             $state = $row['IdccEventType'];

//         }
//     } else {
//         $description = '無異狀';
//         $create_date = '';
//         $state = '';

//     }

//     $table_arr[] = array('name' => $machine, 'description' => $description, 'note' => $note, 'date' => $create_date, 'state' => $state);

// }




$sql_machine = "SELECT
case when Machine='MON1'then 'T01'
    when Machine='MON2'then 'T03'
    when Machine='MON3'then 'T05    '
    else '' end as 'Machine_name'
,CONVERT(varchar, LogTimeStamp, 120)AS TimeStampStr
-- ,CONVERT(VARCHAR(10), LogTimeStamp, 108) AS TimeStampStr
,
*
FROM
(
    SELECT
        *
        ,ROW_NUMBER() OVER (PARTITION BY Machine ORDER BY LogTimeStamp DESC) AS RowNumber
    FROM
        [FinishMachines_Stenter_Machine]
) AS T
WHERE
RowNumber = 1
ORDER BY
Machine";

$result_machine = sqlsrv_query($link, $sql_machine) or die ("sql error" . sqlsrv_errors());
while ($row_machine = sqlsrv_fetch_array($result_machine)) {
    if ($row_machine['MachineSpeed'] >= 10)
        $state = 4;
    else
        $state = 3;
    $table_arr[] = array('name' => $row_machine['Machine_name'], 'description' => 'MachineSpeed：' . $row_machine['MachineSpeed'], 'note' => '', 'date' => '　' . $row_machine['TimeStampStr'], 'state' => $state);
}
//echo $table_arr;
echo json_encode($table_arr);

?>
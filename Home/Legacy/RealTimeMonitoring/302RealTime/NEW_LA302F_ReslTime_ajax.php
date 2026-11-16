<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', 'ReturnDatesAsStrings']);

$sql = " SELECT * FROM [Dispenser_LA302F_RealtimeFlie] ";

$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
} else {
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        // 处理每一行数据
        $allRows[] = $row;
    }
    // print_r($allRows);
    // $Dyelotarray = array_column($allRows, 'Dyelot', 'Filename');
    // $DyeMachinearray = array_column($allRows, 'DyeMachine', 'Filename');
    // $Tankarray = array_column($allRows, 'Tank', 'Filename');
    // $ProductCodearray = array_column($allRows, 'ProductCode', 'Filename');
    // $ProductNamearray = array_column($allRows, 'ProductName', 'ProductCode');
    // $TargetGramsarray = array_column($allRows, 'TargetGrams', 'Filename');
}

$sql2 = " SELECT [ProductCode] ,[ProductName] FROM [DispenserProduction] GROUP BY  [ProductCode] ,[ProductName]";

$stmt2 = sqlsrv_query($conn, $sql2);

if ($stmt2 === false) {
    die(print_r(sqlsrv_errors(), true));
} else {
    while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
        // 处理每一行数据
        $allRows2[] = $row2;
    }
    // print_r($allRows2);
    $ProductNamearray2 = array_column($allRows2, 'ProductName', 'ProductCode');
}
// print_r($ProductName2);

$dbMapper3 = new DbMapper();
$conn2 = $dbMapper3->getLink(['SPC', 'ReturnDatesAsStrings']);
// print_r($conn2);
$sql3 = " SELECT [Dyelot] ,[OrderNo] FROM [Dyelots] ";

$stmt3 = sqlsrv_query($conn2, $sql3);

if ($stmt3 === false) {
    die(print_r(sqlsrv_errors(), true));
} else {
    while ($row3 = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)) {
        // 处理每一行数据
        $allRows3[] = $row3;
    }
    // print_r($allRows3);
    $OrderNoarray = array_column($allRows3, 'OrderNo', 'Dyelot');
}
$info = [
    'Dyelot' => null,
    'DyeMachine' => null,
    'ProductCode' => null,
    'ProductName' => null,
    'TargetGrams' => null,
    'OrderNo' => null,
];
$showdata = [];
foreach ($allRows as $key => $data) {
    $Filename = $data['Filename'];
    $info['Dyelot'] = $data['Dyelot'];
    $info['DyeMachine'] = $data['DyeMachine'];
    $info['ProductCode'] = $data['ProductCode'];
    $info['Tank'] = $data['ProductCode'];
    $ProductCode = $data['ProductCode'];
    if (array_key_exists($ProductCode, $ProductNamearray2)) {
        $info['ProductName'] = $ProductNamearray2[$ProductCode];

    } else {
        $info['ProductName'] = $data['ProductName'];
    }
    $info['TargetGrams'] = $data['TargetGrams'];

    if (array_key_exists($Dyelot, $OrderNoarray)) {
        $info['OrderNo'] = $OrderNoarray[$Dyelot];
    }

    if (array_key_exists($Filename, $showdata)) {
        $showdata[$Filename][] = $info;
    } else {
        $showdata[$Filename] = [];
        $showdata[$Filename][] = $info;
    }





}
echo json_encode($showdata);

?>
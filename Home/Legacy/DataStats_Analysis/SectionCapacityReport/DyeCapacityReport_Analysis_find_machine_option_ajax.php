<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

$startDateTime = $_POST['startDateTime'] . " 00:00:00:000";
$endDateTime = $_POST['endDateTime'] . " 23:59:59:000";

/*


SELECT Machine
FROM (
    SELECT DISTINCT Machine
    FROM [DyeCapacityReport]
) AS Machines
ORDER BY CASE 
    WHEN Machine LIKE '水洗' THEN 2
    WHEN Machine LIKE '外廠' THEN 11
    WHEN Machine LIKE '刷毛' THEN 6
    WHEN Machine LIKE '定型-T01' THEN 3
    WHEN Machine LIKE '定型-T03' THEN 4
    WHEN Machine LIKE '定型-T05' THEN 5
    WHEN Machine LIKE '染房' THEN 1
    WHEN Machine LIKE '剪毛' THEN 7
    WHEN Machine LIKE '圓網印花' THEN 10
    WHEN Machine LIKE '製程' THEN 9
    WHEN Machine LIKE '燒毛' THEN 8
    ELSE 12
END;


*/


$sql = "SELECT Machine
FROM (
    SELECT DISTINCT Machine
    FROM [DyeCapacityReport]
    WHERE [Day] >= '$startDateTime'
    AND [Day] <= '$endDateTime'
    and Deleted=0

-- 05/27 修改不要出現ERP_Production資料
--     union

-- SELECT DISTINCT [製程]
--  FROM [AllSeven_IDCC].[dbo].[ERP_Production]
--  WHERE CONVERT (date, 完成時間) BETWEEN '$startDateTime' AND '$endDateTime'


) AS Machines
ORDER BY CASE 
    WHEN Machine LIKE '水洗' THEN 2
    WHEN Machine LIKE '外廠' THEN 11
    WHEN Machine LIKE '刷毛' THEN 6
    WHEN Machine LIKE '定型-T01' THEN 3
    WHEN Machine LIKE '定型-T03' THEN 4
    WHEN Machine LIKE '定型-T05' THEN 5
    WHEN Machine LIKE '染房' THEN 1
    WHEN Machine LIKE '剪毛' THEN 7
    WHEN Machine LIKE '圓網印花' THEN 10
    WHEN Machine LIKE '製程' THEN 9
    WHEN Machine LIKE '燒毛' THEN 8
    ELSE 12
END







";

$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}


while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $options[] = $row["Machine"];

    if ($row["Machine"] == '定型-T05') {
        $options[] = '定型機總產量';
    }
}



sqlsrv_close($conn);
echo json_encode($options);

?>
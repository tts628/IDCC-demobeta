<?php
$start = $_POST['start'];
$end = $_POST['end'];

$start_date = date('Y-m-d', strtotime($start . ' -10 day'));
$end_date = date('Y-m-d', strtotime($end . ' +10 day'));


require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

$data = [];
$data[0] = ['工單1', '染機', '色名', '品名', '布重', '交貨日', '工單2', '工單3', '工單4', '工單5', '工單6', '工單7', '工單8', '工單9', '工單10', '工單11', '工單12', '工單13', '工單14', '工單15', '工單16', '工單17', '工單18', '工單19', '工單20'];
$sql = "SELECT * from FinishProduction WHERE StartTime-0.04167<getdate() And convert(char(1),Dyelot) <> 'B'";
$result = sqlsrv_query($link, $sql) or die("sql error:" . sqlsrv_errors());
while ($row = sqlsrv_fetch_array($result)) {
    $data[] = [$row['Dyelot'], $row['Machine'], $row['ColorName'], $row['FabricName'], $row['TotalWeight'], date_format($row['DeliveryTime'], "Y-m-d H:i:s"), $row['AnnexDyelot1'], $row['AnnexDyelot2'], $row['AnnexDyelot3'], $row['AnnexDyelot4'], $row['AnnexDyelot5'], $row['AnnexDyelot6'], $row['AnnexDyelot7'], $row['AnnexDyelot8'], $row['AnnexDyelot9'], $row['AnnexDyelot10'], $row['AnnexDyelot11'], $row['AnnexDyelot12'], $row['AnnexDyelot13'], $row['AnnexDyelot14'], $row['AnnexDyelot15'], $row['AnnexDyelot16'], $row['AnnexDyelot17'], $row['AnnexDyelot18'], $row['AnnexDyelot19']];
}
echo json_encode($data);
?>
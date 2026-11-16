<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

$factory_name = $_POST['factory_name'];
$machine_name_text = $_POST['machine_name_text'];
// echo $factory_name;
$sql = "select Name from $factory_name";
$result = sqlsrv_query($link, $sql) or die("sql error" . sqlsrv_errors());
// $res = '';
$res = '<option value="Allmachine">' . $machine_name_text . '</option>';
while ($row = sqlsrv_fetch_array($result)) {
    $res .= "<option value='{$row['Name']}'>{$row['Name']}</option>";//將對應的型號項目遞迴列出
}
;
echo $res
    ?>
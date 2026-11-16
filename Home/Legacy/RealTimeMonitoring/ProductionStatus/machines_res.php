<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");

$lang = new Language();
$lang->load("manufacture");
$machines = $_POST['machines'];
//echo $machines;
if($machines=='DyeMachines'){
    $sql = "SELECT * FROM DyeMachines";
}
if($machines=='FinishMachines'){
    $sql = "SELECT * FROM FinishMachines";
}
if($machines=='DispenserMachines'){
    $sql = "SELECT * FROM DispenserMachines";
}
//$sql = "SELECT * FROM '$machines'";
$result = sqlsrv_query($link, $sql);
$res = "";
while($rows=sqlsrv_fetch_array($result)){
    // $res1="<option value=''>{$lang->line('manufacture.manufacture_select')}</option>";
    $res .= "<option value='{$rows['Name']}'>{$rows['Name']}</option>";
    $res2=$res1.$res;
};
echo $res2;//將型號項目丟回給ajax
?>

    


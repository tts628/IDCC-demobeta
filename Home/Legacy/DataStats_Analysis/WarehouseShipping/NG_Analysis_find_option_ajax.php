
<?php
$DDDD='';
if($_POST['DDDD']=='D1202+D1204'){
  $DDDD="and Warehouse in ('D1202','D1204')";
}
else{
  $DDDD="and Warehouse in ('".$_POST['DDDD']."')";
}
// $options[]="D1202+D1204";

$options[]="ALL";

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

$sql = "SELECT distinct([Responsible_unit])
FROM [Daluen_cqcr900]
WHERE LTRIM(RTRIM([Responsible_unit])) <> '' AND PATINDEX('%[^0-9]%', LTRIM(RTRIM([Responsible_unit]))) > 0
AND [Responsible_unit]<>'.'
AND [Responsible_unit]<>'54.6'
order by [Responsible_unit] desc

";

// 第四張圖例外

if($_POST['optionsID']=='#UUUU_4')
{
  $sql = "SELECT distinct([Responsible_unit])
  FROM [Daluen_cqcr901]
  WHERE LTRIM(RTRIM([Responsible_unit])) <> '' AND PATINDEX('%[^0-9]%', LTRIM(RTRIM([Responsible_unit]))) > 0
  AND [Responsible_unit]<>'.'
  AND [Responsible_unit]<>'54.6'
  
  order by [Responsible_unit] desc
  ";
}




$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
  die(print_r(sqlsrv_errors(), true));
}
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
  $options[] = $row['Responsible_unit'];
}

  sqlsrv_close($conn);


  echo json_encode($options);

  ?>

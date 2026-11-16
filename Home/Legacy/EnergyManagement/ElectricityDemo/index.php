<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");
$lang = new Language();
$lang->load("header");
$lang->load("menu");
$lang->load("electricity");
$lang->load("pre_maintenance");
// require_once('/home/www/html/logic_art/common/database.php');
// $sql="select Name from DyeMachines";
// $result=sqlsrv_query($link,$sql)or die("sql error".sqlsrv_errors());
?>


<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', 'ReturnDatesAsStrings']);
// require_once('./database.php');



// 
// $connectionInfo = array( "Database"=>"BatchDyeingCentral","UID"=>"sa","PWD"=>"1234","CharacterSet" => "UTF-8", "ReturnDatesAsStrings"=>true); // SQL Server 資料庫登入資訊

// // 連接 SQL Server 資料庫
// $conn = sqlsrv_connect( $serverName, $connectionInfo);

// if( $conn === false ) {
//      die( print_r( sqlsrv_errors(), true));
// }
$dateselect = date("Y-m-d");

//連接機器數量
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     if (isset($_POST["submit"])) {
//       // 确定提交的是button
//     $dateselect = $_POST["dateselect"];
//       echo "日期是：" . $dateselect;
//     }
// }
$wastewater_area = $lang->line("electricity.wastewater_area");
$dyeing_area = $lang->line("electricity.dyeing_area");
$setting_area = $lang->line("electricity.setting_area");
$raising_area = $lang->line("electricity.raising_area");
$packaging_area = $lang->line("electricity.packaging_area");
$dehydration_area = $lang->line("electricity.dehydration_area");
$refining_area = $lang->line("electricity.refining_area");
$preparation_area = $lang->line("electricity.preparation_area");
$compressed_air_area = $lang->line("electricity.compressed_air_area");
$soft_water_area = $lang->line("electricity.soft_water_area");
$boiler_area = $lang->line("electricity.boiler_area");
$laboratory_area = $lang->line("electricity.laboratory_area");
$automatic_weighing_area = $lang->line("electricity.automatic_weighing_area");


//最近一日區域用電佔比
// $sql = "SELECT CASE
//     WHEN [Field] LIKE '%廢水%' THEN '$wastewater_area'
// 	WHEN [Field] LIKE '%染機%' THEN '$dyeing_area'
// 	WHEN [Field] LIKE '%定型%' THEN '$setting_area'
// 	WHEN [Field] LIKE '%起毛%' THEN '$raising_area'
//     WHEN [Field] LIKE '%包裝%' THEN '$packaging_area'
// 	WHEN [Field] LIKE '%脫水%' THEN '$dehydration_area'
// 	WHEN [Field] LIKE '%精煉%' THEN '$refining_area'
// 	WHEN [Field] LIKE '%準備區%' THEN  '$preparation_area'
// 	WHEN [Field] LIKE '%空壓機%' THEN  '$compressed_air_area'
// 	WHEN [Field] LIKE '%軟水%' THEN    '$soft_water_area'
// 	WHEN [Field] LIKE '%鍋爐%' THEN    '$boiler_area'
// 	WHEN [Field] LIKE '%化驗室%' THEN  '$laboratory_area'
// 	WHEN [Field] LIKE '%自動秤料%' THEN'$automatic_weighing_area'

// ELSE [Field]
//  END AS '區域',

//  SUM(CAST([Active(Imp)(kWh)Total] AS float) + CAST([Active(Exp)(kWh)Total] AS float)) AS '實際功率',
//  SUM(CAST([Reactive(Imp)(kvarh)Total] AS float) + CAST([Reactive(Exp)(kvarh)Total] AS float)) AS '虛功率',
//  SUM(CAST([Consumption] AS float)) AS '度'


// FROM [Field]
// WHERE [Time] IS NOT NULL AND TRIM([Time]) <> ''
// AND TRY_CONVERT(datetime, [Time]) IS NOT NULL
// AND [Time] = (SELECT MAX(CAST([Time] AS date)) FROM [Field])
// -- AND CAST([Time] AS date) = ?
// AND [Field] NOT LIKE '%照明%'
// AND [Field] NOT LIKE '%辦公室%'
// AND [Field] NOT LIKE '%空調冰水機%'
// AND [Field] NOT LIKE '%F棟二樓倉庫%'
// GROUP BY CASE
// 	WHEN [Field] LIKE '%廢水%' THEN '$wastewater_area'
// 	WHEN [Field] LIKE '%染機%' THEN '$dyeing_area'
// 	WHEN [Field] LIKE '%定型%' THEN '$setting_area'
// 	WHEN [Field] LIKE '%起毛%' THEN '$raising_area'
// 	WHEN [Field] LIKE '%包裝%' THEN '$packaging_area'
// 	WHEN [Field] LIKE '%脫水%' THEN '$dehydration_area'
// 	WHEN [Field] LIKE '%精煉%' THEN '$refining_area'
// 	WHEN [Field] LIKE '%準備區%' THEN  '$preparation_area'
// 	WHEN [Field] LIKE '%空壓機%' THEN  '$compressed_air_area'
// 	WHEN [Field] LIKE '%軟水%' THEN    '$soft_water_area'
// 	WHEN [Field] LIKE '%鍋爐%' THEN    '$boiler_area'
// 	WHEN [Field] LIKE '%化驗室%' THEN  '$laboratory_area'
// 	WHEN [Field] LIKE '%自動秤料%' THEN'$automatic_weighing_area'
	
// ELSE [Field]
// 	END
// ORDER BY CASE
// 	WHEN [Field] LIKE '%廢水%' THEN '$wastewater_area'
// 	WHEN [Field] LIKE '%染機%' THEN '$dyeing_area'
// 	WHEN [Field] LIKE '%定型%' THEN '$setting_area'
// 	WHEN [Field] LIKE '%起毛%' THEN '$raising_area'
// 	WHEN [Field] LIKE '%包裝%' THEN '$packaging_area'
// 	WHEN [Field] LIKE '%脫水%' THEN '$dehydration_area'
// 	WHEN [Field] LIKE '%精煉%' THEN '$refining_area'
// 	WHEN [Field] LIKE '%準備區%' THEN  '$preparation_area'
// 	WHEN [Field] LIKE '%空壓機%' THEN  '$compressed_air_area'
// 	WHEN [Field] LIKE '%軟水%' THEN    '$soft_water_area'
// 	WHEN [Field] LIKE '%鍋爐%' THEN    '$boiler_area'
// 	WHEN [Field] LIKE '%化驗室%' THEN  '$laboratory_area'
// 	WHEN [Field] LIKE '%自動秤料%' THEN'$automatic_weighing_area'

// ELSE [Field]
// 	END";
$sql="  SELECT CASE
WHEN [Field] LIKE '%廢水%' THEN '$wastewater_area'
WHEN [Field] LIKE '%染機%' THEN '$dyeing_area'
WHEN [Field] LIKE '%定型%' THEN '$setting_area'
WHEN [Field] LIKE '%起毛%' THEN '$raising_area'
WHEN [Field] LIKE '%包裝%' THEN '$packaging_area'
WHEN [Field] LIKE '%脫水%' THEN '$dehydration_area'
WHEN [Field] LIKE '%精煉%' THEN '$refining_area'
WHEN [Field] LIKE '%準備區%' THEN  '$preparation_area'
WHEN [Field] LIKE '%空壓機%' THEN  '$compressed_air_area'
WHEN [Field] LIKE '%軟水%' THEN    '$soft_water_area'
WHEN [Field] LIKE '%鍋爐%' THEN    '$boiler_area'
WHEN [Field] LIKE '%化驗室%' THEN  '$laboratory_area'
WHEN [Field] LIKE '%自動秤料%' THEN'$automatic_weighing_area'

ELSE [Field]
END AS '區域',

SUM(CAST([Consumption] AS float)) AS '度'


FROM [Consumption]
WHERE [Time] IS NOT NULL AND TRIM([Time]) <> ''
AND TRY_CONVERT(datetime, [Time]) IS NOT NULL
AND [Time] = (SELECT MAX(CAST([Time] AS date)) FROM [Consumption])
-- AND CAST([Time] AS date) = ?
AND [Field] NOT LIKE '%照明%'
AND [Field] NOT LIKE '%辦公室%'
AND [Field] NOT LIKE '%空調冰水機%'
AND [Field] NOT LIKE '%F棟二樓倉庫%'
GROUP BY CASE
WHEN [Field] LIKE '%廢水%' THEN '$wastewater_area'
WHEN [Field] LIKE '%染機%' THEN '$dyeing_area'
WHEN [Field] LIKE '%定型%' THEN '$setting_area'
WHEN [Field] LIKE '%起毛%' THEN '$raising_area'
WHEN [Field] LIKE '%包裝%' THEN '$packaging_area'
WHEN [Field] LIKE '%脫水%' THEN '$dehydration_area'
WHEN [Field] LIKE '%精煉%' THEN '$refining_area'
WHEN [Field] LIKE '%準備區%' THEN  '$preparation_area'
WHEN [Field] LIKE '%空壓機%' THEN  '$compressed_air_area'
WHEN [Field] LIKE '%軟水%' THEN    '$soft_water_area'
WHEN [Field] LIKE '%鍋爐%' THEN    '$boiler_area'
WHEN [Field] LIKE '%化驗室%' THEN  '$laboratory_area'
WHEN [Field] LIKE '%自動秤料%' THEN'$automatic_weighing_area'

ELSE [Field]
END
ORDER BY CASE
WHEN [Field] LIKE '%廢水%' THEN '$wastewater_area'
WHEN [Field] LIKE '%染機%' THEN '$dyeing_area'
WHEN [Field] LIKE '%定型%' THEN '$setting_area'
WHEN [Field] LIKE '%起毛%' THEN '$raising_area'
WHEN [Field] LIKE '%包裝%' THEN '$packaging_area'
WHEN [Field] LIKE '%脫水%' THEN '$dehydration_area'
WHEN [Field] LIKE '%精煉%' THEN '$refining_area'
WHEN [Field] LIKE '%準備區%' THEN  '$preparation_area'
WHEN [Field] LIKE '%空壓機%' THEN  '$compressed_air_area'
WHEN [Field] LIKE '%軟水%' THEN    '$soft_water_area'
WHEN [Field] LIKE '%鍋爐%' THEN    '$boiler_area'
WHEN [Field] LIKE '%化驗室%' THEN  '$laboratory_area'
WHEN [Field] LIKE '%自動秤料%' THEN'$automatic_weighing_area'

ELSE [Field]
END
";
$piechart_sql = [$dateselect];
$stmt = sqlsrv_query($conn, $sql, $piechart_sql);

if ($stmt === false) {
  die(print_r(sqlsrv_errors(), true));
}

$data_piechart = array();
$data_barchart = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

  //$data_piechart[] = array("name"=>$row['區域別'], "num"=>$row['total']); // 将查询结果保存到数组中

  $data_piechart[] = array($row['區域'], $row['度']);
}
if (isset($data_piechart[0])) {
  $data_barchart[] = array("", 0);
  foreach ($data_piechart as $data) {
    $data_barchart[] = [
      $data[0],
      $data[1]
    ];
  }
}

// // 最近一日細部區域用電量長條圖
// $sql =" SELECT [Field] AS '區域',
// 			ROUND(SUM(CAST([Active(Imp)(kWh)Total] AS float) + CAST([Active(Exp)(kWh)Total] AS float)),0) AS '實際功率',
// 			ROUND(SUM(CAST([Reactive(Imp)(kvarh)Total] AS float) + CAST([Reactive(Exp)(kvarh)Total] AS float)),0) AS '虛功率'	
// 		FROM [Field]
// 		WHERE [Time] IS NOT NULL AND TRIM([Time]) <> ''
// 		AND TRY_CONVERT(datetime, [Time]) IS NOT NULL
// 		AND CAST([Time] AS date) = '$dateselect'

// 		AND [Field] NOT LIKE '%照明%'
// 		AND [Field] NOT LIKE '%辦公室%'
// 		AND [Field] NOT LIKE '%空調冰水機%'
// 		AND [Field] NOT LIKE '%F棟二樓倉庫%'

// GROUP BY [Field]
// ORDER BY [Field]
// ";

// $stmt = sqlsrv_query( $conn, $sql );

// if( $stmt === false ) {
//     die( print_r( sqlsrv_errors(), true));
// }
//     $data_barchart = array();
//     while ( $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

//          //$data_piechart[] = array("name"=>$row['區域別'], "num"=>$row['total']); // 将查询结果保存到数组中
//         $data_barchart[] = array($row['區域'],$row['實際功率']);
//     }



// 前20天用電量
// $sql = "   SELECT FORMAT(CONVERT(datetime, [Time]), 'MM/dd') AS '日期',
		
// ROUND(SUM(CAST([Active(Imp)(kWh)Total] AS float) + CAST([Active(Exp)(kWh)Total] AS float) ) ,2)AS '當日總用電量'

// FROM [Field]
// WHERE [Time] IS NOT NULL AND TRIM([Time]) <> ''
// AND CAST([Time] AS date)  BETWEEN DATEADD(day, -20, (SELECT MAX(CAST([Time] AS date)) FROM [Field])) AND DATEADD(day, 0, (SELECT MAX(CAST([Time] AS date)) FROM [Field]))
// AND TRY_CONVERT(datetime, [Time]) IS NOT NULL
// AND [Field] NOT LIKE '%照明%'
// AND [Field] NOT LIKE '%辦公室%'
// AND [Field] NOT LIKE '%空調冰水機%'
// AND [Field] NOT LIKE '%F棟二樓倉庫%'
// GROUP BY FORMAT(CONVERT(datetime, [Time]), 'MM/dd') 
// ORDER BY FORMAT(CONVERT(datetime, [Time]), 'MM/dd')
// ";
$sql = "  SELECT FORMAT(CONVERT(datetime, [Time]), 'MM/dd') AS '日期',
		
ROUND(SUM(CAST([Consumption] AS float)),2) AS '當日總用電量'

FROM [Consumption]
WHERE [Time] IS NOT NULL AND TRIM([Time]) <> ''
AND CAST([Time] AS date)  BETWEEN DATEADD(day, -20, (SELECT MAX(CAST([Time] AS date)) FROM [Consumption])) AND DATEADD(day, 0, (SELECT MAX(CAST([Time] AS date)) FROM [Consumption]))
AND TRY_CONVERT(datetime, [Time]) IS NOT NULL
AND [Field] NOT LIKE '%照明%'
AND [Field] NOT LIKE '%辦公室%'
AND [Field] NOT LIKE '%空調冰水機%'
AND [Field] NOT LIKE '%F棟二樓倉庫%'
GROUP BY FORMAT(CONVERT(datetime, [Time]), 'MM/dd') 
ORDER BY FORMAT(CONVERT(datetime, [Time]), 'MM/dd')
";





$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
  die(print_r(sqlsrv_errors(), true));
}

$data_date = array();
$data_total_power_consumption = array();
$data_none = array();
$data_total_power_consumption_linechart = array();
// $datadeadsum = 0;
// $datahurtsum = 0;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

  //$data_piechart[] = array("name"=>$row['區域別'], "num"=>$row['total']); // 将查询结果保存到数组中
  $data_date[] = array($row['日期']);
  $data_total_power_consumption[] = array($row['當日總用電量']);
  //  $data_none[] = array($row['最小用電量']);
  $data_total_power_consumption_linechart[] = array($row['當日總用電量']);
  //  $datadeadsum+=$row['dead'];
  //  $datahurtsum+=$row['hurt'];
// var_dump($row['當日總用電量']);
  $total_power_consumption = intval($row['當日總用電量']);
}

//連接機器數量


$sql = " SELECT COUNT(*) AS '總數量',
COUNT(CASE WHEN [Time] IS NULL THEN 1 END) AS '未連接數量',
COUNT(CASE WHEN [Time] IS NOT NULL THEN 1 END) AS '連接數量',
COUNT(CASE [Time] WHEN  '' THEN 1 END)AS '有連接但未傳值',
ROUND(SUM(CAST([Active(Imp)(kWh)Total] AS float) ),0) AS '目前總用電量',
(SELECT MAX(CAST([Time] AS date)) FROM [Consumption]) AS '最新時間'
FROM [Consumption]

WHERE [Field] NOT LIKE '%照明%'
-- AND CAST([Time] AS date) = '$dateselect'
AND [Time] = (SELECT MAX(CAST([Time] AS date)) FROM [Consumption])
AND [Field] NOT LIKE '%辦公室%'
AND [Field] NOT LIKE '%空調冰水機%'
AND [Field] NOT LIKE '%F棟二樓倉庫%'
AND [Time] IS NOT NULL AND TRIM([Time]) <> ''
AND TRY_CONVERT(datetime, [Time]) IS NOT NULL


";

$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
  die(print_r(sqlsrv_errors(), true));
}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

  //$data_piechart[] = array("name"=>$row['區域別'], "num"=>$row['total']); // 将查询结果保存到数组中

  $dataNumber_of_connected_machines = $row['總數量'];
  $dataNumber_of_connected_machines_success = $row['連接數量'];
  $dataNumber_of_connected_machines_false = $row['未連接數量'];
  $dataNumber_of_connected_machines_withoutdata = $row['有連接但未傳值'];
  // $total_power_consumption = $row['目前總用電量'];
  $today = $row['最新時間'];
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

// 生成 JSON 数据
$jsonData_piechart = json_encode($data_piechart);
$jsonData_barchart = json_encode($data_barchart);
$jsonData_date = json_encode($data_date);
$jsonData_total_power_consumption = json_encode($data_total_power_consumption);
$jsonData_none = json_encode($data_none);
$jsonData_total_power_consumption_linechart = json_encode($data_total_power_consumption_linechart);


?>





<!doctype html>
<html>

<head>
  <?php require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/common/legacyTemplate/head.php'); ?>
  <style>
    .mdl-card__title-text {
      color: #ffffff;
    }

    .mdl-data-table {
      background-color: rgba(0, 0, 0, 0);
    }
  </style>


  <style>
    #myForm {
      position: absolute;
      top: 100px;
      right: 50px;
    }

    .input-wrapper {
      display: flex;
      align-items: center;
    }

    .input-label {
      margin-right: 10px;
      font-weight: bold;

      font-size: 20px;
      width: 100px;

    }

    .input-field {
      padding: 5px 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 16px;
      outline: none;
      width: 150px;
      color: black;
    }

    .highcharts-button-box {
      display: none;
    }

    body {
      background-image: url(images/bg1.png);
      background-size: cover;
      width: 100%;
      height: 100%;
      font-weight: bold;
    }

    .main {
      width: 100%;
      height: auto;
      max-width: 100%;
      max-height: 100%;
      margin: auto;
    }

    .layer {
      position: relative;
      width: 100%;
    }

    .layer2 {
      position: relative;
      width: 50%;
      height: 25%
    }

    @media screen and (max-width: 768px) {


      .layer2 {
        position: relative;
        width: 100%;
        height: 100%
      }

      #combochart,
      #barchart,
      #piechart {
        zoom: 0.5;
      }

      .title_zoom {
        zoom: 0.6;
      }

      #myForm {
        position: absolute;
        top: 200px;
        right: 10px;
        transform: scale(0.7);
      }
    }

    #layer01 {}

    #layer01 img {
      text-align: center;
      display: block;
      height: 35px;
      padding-top: 35px;
      margin: auto;
    }

    #layer02>div {
      height: 100%;
      float: left;
      position: relative;
    }

    .layer02-data {
      position: absolute;
      width: auto;
      height: 100px;
      color: white;
      top: -5px;
      left: 55px;
    }

    /* @media screen and (max-width: 768px) {
    .layer02-data {
    position: relative;
    top: auto;
    left: auto;
    margin-top: 10px;
    margin-bottom: 10px;
    text-align: center;
  }
} */
    .cont {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      grid-gap: 20px;

    }

    .wid {
      grid-column: span 2;
    }

    @media screen and (max-width: 768px) {
      .cont {
        grid-template-columns: repeat(auto-fit, minmax(100%, 1fr));
      }

      .wid {
        grid-column: span 1;
      }
    }


    .cont2 {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
    }

    .item2 {
      width: calc(50% - 10px);
      margin-bottom: 20px;
    }

    @media screen and (max-width: 768px) {
      .item2 {
        width: calc(100%);
      }
    }


    .widwid {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;

    }

    @media screen and (max-width: 768px) {
      .widwid {

        grid-template-columns: repeat(auto-fit, minmax(100%, 1fr));

      }
    }


    .layer03 {
      display: flex;
      flex-direction: row;
      height: 80%;
    }

    .layer03-panel {
      height: 100%;
    }

    @media screen and (max-width: 768px) {
      .layer03 {
        flex-direction: column;
        height: auto;
      }

      .layer03-panel {
        width: 100%;
        height: 50%;
      }
    }

    @media screen and (min-width: 1440px) {
      .addmenu {
        margin-left: 15vw;
      }
    }

    @media screen and (min-width: 1680px) {
      .addmenu {
        margin-left: 13vw;
      }
    }

    .layer03-left-label {
      position: absolute;
    }

    #layer03_left_label01 {
      top: 10px;
      left: 10px;
      color: white;
      height: 20px;
      width: 200px;
      font-weight: bold;
    }

    #layer03_left_label02 {
      right: 10px;
      top: 10px;
      color: #036769;
      height: 20px;
      width: 200px;
    }

    .layer03-left-chart {
      position: relative;
      float: left;
      height: 100%;
    }

    #layer03_right_label {
      position: absolute;
      top: 10px;
      left: 10px;
      color: white;
      height: 20px;
      width: 100px;
    }

    .layer03-right-chart {
      position: relative;
      float: left;
      height: 100%;
      width: 32%;
    }

    .layer03-right-chart-label {
      color: white;
      text-align: center;
      position: absolute;
      bottom: 60px;
      width: 100%;
    }

    .layer04-panel {
      position: relative;
      float: left;
      height: 100%;
      width: 48%;
    }

    .layer04-panel-label {
      width: 100%;
      height: 15%;
      color: white;
      padding-top: 5px;
    }

    .layer04-panel-chart {
      width: 100%;
      height: 85%;
    }
  </style>
  <!-- 插件引用 -->
  <!-- <script src="../../common/highcharts/highcharts.js"></script> -->
  <link rel="stylesheet" href="../../common/css/bootstrap.min.css">
  <script src="../../common/js/bootstrap.min.js"></script>
  <script src="../../common/DataTables/DataTables/js/jquery.dataTables.js"></script>
  <link rel="stylesheet" type="text/css" href="../../common/DataTables/DataTables/css/jquery.dataTables.css">
  <!-- 插件引用 -->

  <script src="https://cdn.bootcss.com/echarts/4.1.0.rc2/echarts.min.js"></script>
  <script src="monitor.js"></script>

  <!-- 外部JS CSS資料 -->
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/no-data-to-display.js"></script>

  <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <script src="http://www.jq22.com/jquery/jquery-1.10.2.js"></script>

  <link rel="stylesheet" type="Wtext/css" href="chart.css">
  <script type="text/javascript" src="piechart.js"></script>
  <script type="text/javascript" src="barchart.js"></script>
  <script type="text/javascript" src="combochart.js"></script>

  <!--時間表單 -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>


  <!-- ajax -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <style>
    .form-container {
      width: 150px;
      /* 设置输入框的宽度 */
      height: 34px;
      /* 设置输入框的高度 */
      font-size: 14px;
      /* 设置输入框的字体大小 */
    }

    html,
    body {
      height: 100%;
      margin: 0;
      padding: 0;
    }
  </style>

  <script type="text/javascript">
    $(function () {
      drawLayer02Label($("#layer02_01 canvas").get(0), "<?php echo $lang->line("electricity.title_actual_power_used"); ?>(kWh)", 80, 800);
      drawLayer02Label($("#layer02_02 canvas").get(0), "<?php echo $lang->line("electricity.title_total_machines"); ?>", 80, 800);
      drawLayer02Label($("#layer02_03 canvas").get(0), "<?php echo $lang->line("electricity.title_connected_quantity"); ?>", 80, 400);
      drawLayer02Label($("#layer02_04 canvas").get(0), "<?php echo $lang->line("electricity.title_disconnected_quantity"); ?>", 80, 400);
      drawLayer02Label($("#layer02_05 canvas").get(0), "<?php echo $lang->line("electricity.title_connected_but_no_value"); ?>", 80, 300);
      // drawLayer02Label($("#layer02_06 canvas").get(0),"<?php echo $lang->line("electricity.title_connected_but_no_value"); ?>",80,300);

      // drawLayer02Label($("#layer02_06 canvas").get(0),"",50,200);

      // renderLegend();

      drawpiechart(<?php echo $jsonData_piechart; ?>,
        '<?php echo $lang->line("electricity.power_consumption_ratio_by_region"); ?>',
        '<?php echo $lang->line("electricity.power_consumption_ratio_label"); ?>',
        '<?php echo $lang->line("electricity.nodata"); ?>', '',
        '<?php echo $lang->line("carbon_footprint_by_region");?>');

      drawbarchart(<?php echo $jsonData_barchart; ?>,
        '<?php echo $lang->line("electricity.power_consumption_by_region_on_the_day"); ?>',
        '<?php echo $lang->line("electricity.nodata"); ?>');

      drawcombochart(<?php echo $jsonData_date; ?>,
        <?php echo $jsonData_total_power_consumption; ?>,
        <?php echo $jsonData_none; ?>,
        <?php echo $jsonData_total_power_consumption_linechart; ?>,
        '<?php echo $lang->line("electricity.power_consumption_per_day_first_and_last_10_days"); ?>',
        '<?php echo $lang->line("electricity.power_consumption_per_day_first_and_last_10_days_label"); ?>',
        '<?php echo $lang->line("electricity.nodata"); ?>', '',
        '<?php echo $lang->line("carbon_footprint");?>');
    });
  </script>

  <title>用電量表</title>
</head>

<body>
  <div class=" mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header is-small-screen">
    <main class="">

      <div class="mdl-grid ui-tables">
        <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--4-col-phone">
          <!-- <div class="mdl-card mdl-shadow--2dp"> -->
          <div class="mdl-card__title title_zoom">
            <h1 class="mdl-card__title-text">
              <?php echo $lang->line("electricity.title_power_consumption_statistics"); ?>
            </h1>
            <h5 class="mdl-card__title-text">&nbsp;&nbsp;&nbsp;
              <?php echo "(" . $lang->line("electricity.title_last_update_time") . ":" . $today . ")"; ?>
            </h5>
            <!-- <a href='index.php?lang=zh-TW' style="float: right; margin-left: 10px;">中文</a>&nbsp;|&nbsp;<a href='index.php?lang=en-US'>English</a> -->

            <form id="myForm">
              <div class="input-wrapper">
                <label for="inputValue" class="input-label">係數值：</label>
                <input type="text" id="inputValue" name="inputValue" class="input-field">
              </div>
              <!-- <button type="button" onclick="submitForm()">提交</button> -->
            </form>
            <script>
              // 监听键盘按下事件
              document.getElementById('inputValue').addEventListener('keydown', function (event) {
                // 按下 Enter 键时阻止表单提交
                if (event.keyCode === 13) {
                  event.preventDefault();
                  return false;
                }
              });
            </script>
          </div>

          <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone">

          </div>
        </div>
      </div>

      <div>
        <div id="layer02" class="layer" style="height:15%;">
          <div class="cont">
            <div id="layer02_01" style="width:20%;">
              <div class="layer02-data">
                <span style="font-size:45px;font-weight: bold;text-shadow: 1px 1px 2px rgba(0, 0, 0, 3.5);">&nbsp;&nbsp;
                  <?php echo $total_power_consumption; ?>
                </span>
                <!-- <span style="font-size:16px;"></span> -->
              </div>
              <canvas width="290" height="80"></canvas>
            </div>
          </div>
          <div class="cont">
            <div id="layer02_02" style="width:20%;">
              <div class="layer02-data">
                <span style="font-size:45px;font-weight: bold;text-shadow: 1px 1px 2px rgba(0, 0, 0, 3.5);">&nbsp;&nbsp;
                  <?php echo $dataNumber_of_connected_machines; ?>
                </span>
                <!-- <span style="font-size:16px;"></span> -->
              </div>
              <canvas width="220" height="80"></canvas>
            </div>
          </div>
          <div class="cont">
            <div id="layer02_03" style="width:20%;">
              <div class="layer02-data">
                <span style="font-size:45px;font-weight: bold;text-shadow: 1px 1px 2px rgba(0, 0, 0, 3.5);">&nbsp;&nbsp;
                  <?php echo $dataNumber_of_connected_machines_success; ?>
                </span>
                <!-- <span style="font-size:16px;"></span> -->
              </div>
              <canvas width="220" height="80"></canvas>
            </div>
          </div>
          <div class="cont">
            <div id="layer02_04" style="width:20%;">
              <div class="layer02-data">
                <span style="font-size:45px;font-weight: bold;text-shadow: 1px 1px 2px rgba(0, 0, 0, 3.5);">&nbsp;&nbsp;
                  <?php echo $dataNumber_of_connected_machines_false; ?>
                </span>
                <!-- <span style="font-size:16px;"></span> -->
              </div>
              <canvas width="220" height="80"></canvas>
            </div>
          </div>
          <div class="cont">
            <div id="layer02_05" style="width:20%;">
              <div class="layer02-data">
                <span style="font-size:45px;font-weight: bold;text-shadow: 1px 1px 2px rgba(0, 0, 0, 3.5);">&nbsp;&nbsp;
                  <?php echo $dataNumber_of_connected_machines_withoutdata; ?>
                </span>
                <!-- <span style="font-size:16px;"></span> -->
              </div>
              <canvas width="280" height="80"></canvas>
            </div>
          </div>




          <div id="piechart" class="layer2" style="height:40vh; "></div>
          <div id="barchart" class="layer2" style="height:40vh; "></div>


          <div id="combochart" class="layer" style="height:40vh;"></div>
        </div>
      </div>
    </main>
  </div>




</body>

</html>

<script>

  function submitForm() {
    // 获取表单元素的值
    var inputElement = document.getElementById("inputValue");
    var inputValue = inputElement.value;

    // 执行需要的操作，例如显示结果
    // var resultElement = document.getElementById("result");
    // resultElement.innerHTML = "您输入的值是：" + inputValue;

    // 清空输入框
    // inputElement.value = "";
    // console.log(inputValue.value);
  }



  function autoRefresh() {
    console.log("更新");

    $.ajax({
      type: 'POST',
      url: "./piechart.js",
      data: {


      },
      success: function (result) {

        drawpiechart(<?php echo $jsonData_piechart; ?>,
          '<?php echo $lang->line("electricity.power_consumption_ratio_by_region"); ?>',
          '<?php echo $lang->line("electricity.power_consumption_ratio_label"); ?>',
          '<?php echo $lang->line("electricity.nodata"); ?>',
          inputValue.value,
          '<?php echo $lang->line("carbon_footprint_by_region");?>');




      }




    });


    $.ajax({
      type: 'POST',
      url: "./combochart.js",
      data: {


      },
      success: function (result) {




        drawcombochart(<?php echo $jsonData_date; ?>,
          <?php echo $jsonData_total_power_consumption; ?>,
          <?php echo $jsonData_none; ?>,
          <?php echo $jsonData_total_power_consumption_linechart; ?>,
          '<?php echo $lang->line("electricity.power_consumption_per_day_first_and_last_10_days"); ?>',
          '<?php echo $lang->line("electricity.power_consumption_per_day_first_and_last_10_days_label"); ?>',
          '<?php echo $lang->line("electricity.nodata"); ?>',
          inputValue.value,
        '<?php echo $lang->line("carbon_footprint");?>');

      }


    });
    setTimeout(function () {
      autoRefresh();
      // clearInterval(timerId);

      // clearAll();


    }, 10000);
  }
  autoRefresh();
</script>
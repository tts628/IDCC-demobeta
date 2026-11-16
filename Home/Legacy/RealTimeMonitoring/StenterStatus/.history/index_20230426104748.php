<?php
// require_once("../../common/language/language.php");
// $lang = new Language();
// $lang->load("header");
// $lang->load("menu");
// $lang->load("electricity");
// $lang->load("pre_maintenance");
// require_once('/home/www/html/logic_art/common/database.php');
// $sql="select Name from DyeMachines";
// $result=sqlsrv_query($link,$sql)or die("sql error".sqlsrv_errors());
?>


<?php
require_once('./database.php');





// // 前20天用電量
// $sql ="   SELECT FORMAT(CONVERT(datetime, [Time]), 'MM/dd') AS '日期',
		
// ROUND(SUM(CAST([Active(Imp)(kWh)Total] AS float) + CAST([Active(Exp)(kWh)Total] AS float) ) ,2)AS '當日總用電量'

// FROM [Daluen_IDCC].[dbo].[Energyconsumption]
// WHERE [Time] IS NOT NULL AND TRIM([Time]) <> ''
// AND CAST([Time] AS date)  BETWEEN DATEADD(day, -20, (SELECT MAX(CAST([Time] AS date)) FROM [Daluen_IDCC].[dbo].[Energyconsumption])) AND DATEADD(day, 0, (SELECT MAX(CAST([Time] AS date)) FROM [Daluen_IDCC].[dbo].[Energyconsumption]))
// AND TRY_CONVERT(datetime, [Time]) IS NOT NULL
// AND [Substation Name] NOT LIKE '%照明%'
// AND [Substation Name] NOT LIKE '%辦公室%'
// AND [Substation Name] NOT LIKE '%空調冰水機%'
// AND [Substation Name] NOT LIKE '%F棟二樓倉庫%'
// GROUP BY FORMAT(CONVERT(datetime, [Time]), 'MM/dd') 
// ORDER BY FORMAT(CONVERT(datetime, [Time]), 'MM/dd')
// ";


// $stmt = sqlsrv_query( $conn, $sql );

// if( $stmt === false ) {
//     die( print_r( sqlsrv_errors(), true));
// }

//     $data_date = array();
//     $data_total_power_consumption = array();
//     $data_none = array();
//     $data_total_power_consumption_linechart = array();

//     while ( $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

//         $data_date[] = array($row['日期']);
//         $data_total_power_consumption[] = array($row['當日總用電量']);

//         $data_total_power_consumption_linechart[] = array($row['當日總用電量']);

//     }


// sqlsrv_free_stmt($stmt);
// sqlsrv_close($conn);

// // 生成 JSON 数据
// $jsonData_piechart = json_encode($data_piechart);
// $jsonData_barchart = json_encode($data_barchart);
// $jsonData_date = json_encode($data_date);
// $jsonData_total_power_consumption = json_encode($data_total_power_consumption);
// $jsonData_none = json_encode($data_none);
// $jsonData_total_power_consumption_linechart = json_encode($data_total_power_consumption_linechart);
// $machine_name = 'F01';
// $sql ="SELECT DISTINCT[Name]
                
// FROM [Demo_IDCC].[dbo].[FinishMachines]

// ";


// $stmt = sqlsrv_query( $conn, $sql );

// if( $stmt === false ) {
//     die( print_r( sqlsrv_errors(), true));
// }
    
//     $data_Name= array();
    
//     while ( $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
// $data_Name[] = ($row['Name']);
  
//     }


//   // 檢查是否有POST資料
//   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // 取得選擇的機器名稱
//     $machine_name = $_POST['machine'];
    
//   }
//   else 
//   {$machine_name = 'F01';}

// 前20天用電量
// $sql ="SELECT [Name],
//                 [MainSpeed],
//                 [Width],
//                 [OverFeed],
//                 [Job1],
//                 [Job2],
//                 [Temperature1],
//                 [Temperature2],
//                 [Temperature3],
//                 [Temperature4],
//                 [Temperature5],
//                 CONVERT(varchar, [JobStartTime], 102) AS 'Date',
//                 CONVERT(varchar, [JobStartTime], 108) AS 'Time'
// FROM [Demo_IDCC].[dbo].[FinishMachines]
// WHERE [Name] = '$machine_name'
// ";


// $stmt = sqlsrv_query( $conn, $sql );

// if( $stmt === false ) {
//     die( print_r( sqlsrv_errors(), true));
// }

    
    
//         //  $data_Name= array();
//         // $data_MainSpeed= array();
//         // $data_Width= array();
//         // $data_OverFeed= array();
//         // $data_Job1= array();
//         // $data_Job2= array();
//         $data_Temperature1= array();
//         $data_Temperature2= array();
//         $data_Temperature3= array();
//         $data_Temperature4= array();
//         $data_Temperature5= array();
//         $data_Time= array();
        
    
        
//     while ( $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {



        
//         $data_MainSpeed= ($row['MainSpeed']);
//         $data_Width = ($row['Width']);
//         $data_OverFeed = ($row['OverFeed']);
//         $data_Job1 = ($row['Job1']);
//         $data_Job2 = ($row['Job2']);
//         $data_Temperature1[] = array($row['Temperature1']);
//         $data_Temperature2[] = array($row['Temperature2']);
//         $data_Temperature3[] = array($row['Temperature3']);
//         $data_Temperature4[] = array($row['Temperature4']);
//         $data_Temperature5[] = array($row['Temperature5']);
//         $data_Time[] = array($row['Time']);
        

//     }

//     $lastTemperature1 = end($data_Temperature1);
//     $lastTemperature2 = end($data_Temperature2);
//     $lastTemperature3 = end($data_Temperature3);
//     $lastTemperature4 = end($data_Temperature4);
//     $lastTemperature5 = end($data_Temperature5);


// sqlsrv_free_stmt($stmt);
// sqlsrv_close($conn);

// $jsonData_Temperature1 = json_encode($data_Temperature1);
// $jsonData_Temperature2 = json_encode($data_Temperature2);
// $jsonData_Temperature3 = json_encode($data_Temperature3);
// $jsonData_Temperature4 = json_encode($data_Temperature4);
// $jsonData_Temperature5 = json_encode($data_Temperature5);
// $jsonData_Time = json_encode($data_Time);

// // var_dump($jsonData_Temperature1);
// ?>





<!doctype html>
<html>
<head>
<style>
        body {
            background-image: url("./images/bg1.png");
            background-size: cover;
            background-repeat: no-repeat;
            height: 100%;
            
            
        
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
	<!-- <script src="https://code.highcharts.com/highcharts.js"></script> -->
    <!-- <script src="https://code.highcharts.com/modules/no-data-to-display.js"></script> -->
    
    <!-- <script src="https://code.highcharts.com/modules/exporting.js"></script> -->
    <script src="http://www.jq22.com/jquery/jquery-1.10.2.js"></script>
    
    <link rel="stylesheet" type="Wtext/css" href="chart.css">
    <script type="text/javascript" src="piechart.js"></script>
    <script type="text/javascript" src="barchart.js"></script>
	<script type="text/javascript" src="combochart.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/heatmap.js"></script>
<!-- <script src="https://code.highcharts.com/modules/exporting.js"></script> -->
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<!--時間表單 -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<title></title>
</head>
<!-- <body> -->
<!-- <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header is-small-screen"> -->
    <?php //require_once('../../common/layout__header.php');?>
    <?php //require_once('../../common/menu.php');?>
<!-- </body> -->

<body>
<div class="container">
  <div class="item">1</div>
  <div class="item">2</div>
  <div class="item">3</div>
  <div class="item">4</div>
  <div class="item">5</div>
  <div class="item">6</div>
  <div class="item">7</div>
  <div class="item">8</div>
  <div class="item">9</div>
  <div class="item">10</div>
  <div class="item">11</div>
</div>
  <style>
    

  .container {
  display: grid;
  grid-gap: 20px;
  grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
  grid-template-rows: repeat(5, 100px);
}

.item {
  background-color: #ccc;
  padding: 20px;
  font-size: 24px;
  text-align: center;
}

  </style>
</body>

</html>

<script>
<script>
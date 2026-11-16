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

/*
$sql = "SELECT DISTINCT[Name]

FROM [Demo_IDCC].[dbo].[FinishMachines]
";
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
die(print_r(sqlsrv_errors(), true));
}
$data_Name = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
$data_Name[] = ($row['Name']);
}
// 檢查是否有POST資料
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// 取得選擇的機器名稱
$machine_name = $_POST['machine'];
} else {
$machine_name = 'F01';
}
// 前20天用電量
$sql = "SELECT [Name],
[MainSpeed],
[Width],
[OverFeed],
[Job1],
[Job2],
[Temperature1],
[Temperature2],
[Temperature3],
[Temperature4],
[Temperature5],
CONVERT(varchar, [JobStartTime], 102) AS 'Date',
CONVERT(varchar, [JobStartTime], 108) AS 'Time'
FROM [Demo_IDCC].[dbo].[FinishMachines]
WHERE [Name] = '$machine_name'
";
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
die(print_r(sqlsrv_errors(), true));
}
//  $data_Name= array();
// $data_MainSpeed= array();
// $data_Width= array();
// $data_OverFeed= array();
// $data_Job1= array();
// $data_Job2= array();
$data_Temperature1 = array();
$data_Temperature2 = array();
$data_Temperature3 = array();
$data_Temperature4 = array();
$data_Temperature5 = array();
$data_Time = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
$data_MainSpeed = ($row['MainSpeed']);
$data_Width = ($row['Width']);
$data_OverFeed = ($row['OverFeed']);
$data_Job1 = ($row['Job1']);
$data_Job2 = ($row['Job2']);
$data_Temperature1[] = array($row['Temperature1']);
$data_Temperature2[] = array($row['Temperature2']);
$data_Temperature3[] = array($row['Temperature3']);
$data_Temperature4[] = array($row['Temperature4']);
$data_Temperature5[] = array($row['Temperature5']);
$data_Time[] = array($row['Time']);
}
$lastTemperature1 = end($data_Temperature1);
$lastTemperature2 = end($data_Temperature2);
$lastTemperature3 = end($data_Temperature3);
$lastTemperature4 = end($data_Temperature4);
$lastTemperature5 = end($data_Temperature5);
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
$jsonData_Temperature1 = json_encode($data_Temperature1);
$jsonData_Temperature2 = json_encode($data_Temperature2);
$jsonData_Temperature3 = json_encode($data_Temperature3);
$jsonData_Temperature4 = json_encode($data_Temperature4);
$jsonData_Temperature5 = json_encode($data_Temperature5);
$jsonData_Time = json_encode($data_Time);
*/
?>





<!doctype html>
<html>

<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-image: url("./images/bg1.png");
            background-size: cover;
            background-repeat: no-repeat;
            height: auto;


            /* transform: scale(0.8); */
            /* zoom: 0.4; */

        }

        @media (min-width: 3500px) {
            body {

                transform: scale(1.8);
                /* zoom: 0.4; */

            }

            .back {
                position: absolute;
                /* 設置絕對定位 */
                /* vertical-align: top; */
                margin: 0 auto;
                /* 將元素水平置中 */

            }
        }

        @media (max-width: 900px) {
            body {

                transform: scale(0.6);
                /* zoom: 0.4; */

            }

            .back {
                position: absolute;
                /* 設置絕對定位 */
                /* vertical-align: top; */
                margin: 0 auto;
                /* 將元素水平置中 */
                left: -140px;

            }
        }


        .container {
            position: relative;

        }

        .back {

            width: 1200px;
            /* 設置寬度 */
            height: 1080px;
            margin: 0 auto;
            /* 將元素置中 */
            background-color: #fff;
            /* 設置背景顏色 */
            padding: 20px;
            /* 設置內邊距 */
            background-image: linear-gradient(to top, #5ee7df 0%, #b490ca 100%);
        }

        .image_LA570_1 {
            position: absolute;
            top: 20px;
            left: 70px;
            width: 1120px;

        }

        .image_LA570_2 {
            position: absolute;
            top: 350px;
            left: 50px;
            width: 1100px;
            height: auto;
        }

        .slider img {
            width: 45px;
        }

        .alarm img {
            width: 55px;
            height: 50px;
            position: absolute;
            top: -246px;
            left: -804px;
        }
    </style>
    <!-- 插件引用 -->
    <!-- <script src="../../common/highcharts/highcharts.js"></script> -->
    <link rel="stylesheet" href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.css">
    <script src="/common/IDCC_plugin/deprecated/DataTables/DataTables/js/jquery.dataTables.js"></script>
    <link rel="stylesheet" type="text/css"
        href="/common/IDCC_plugin/deprecated/DataTables/DataTables/css/jquery.dataTables.css">
    <!-- 插件引用 -->

    <script src="https://cdn.bootcss.com/echarts/4.1.0.rc2/echarts.min.js"></script>
    <!-- <script src="monitor.js"></script> -->

    <!-- 外部JS CSS資料 -->

    <!-- <script src="https://code.highcharts.com/modules/exporting.js"></script> -->

    <link rel="stylesheet" type="Wtext/css" href="chart.css">
    <!-- <script type="text/javascript" src="piechart.js"></script>
  <script type="text/javascript" src="barchart.js"></script>
  <script type="text/javascript" src="combochart.js"></script> -->

    <!--時間表單 -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
    <script src="/common/IDCC_plugin/jquery/jquery.min.js"></script>
    <script src="/common/IDCC_plugin/bootstrap-5.0.0-dist/js/bootstrap.js"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
</head>
<!-- <body> -->
<!-- <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header is-small-screen"> -->
<?php //require_once('../../common/layout__header.php'); ?>
<?php //require_once('../../common/menu.php'); ?>
<!-- </body> -->

<body>
    <!-- <div class="container">
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
  padding-top: 10px;
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

  </style> -->

    <div class="container ">
        <div class="back">

            <!-- 平面570 -->
            <div>
                <img class="image_LA570_1" src="images/LA570A.png" alt="LA570A Background">

                <span
                    style="position: absolute; top: 300px; left: 95px; font-size: 38px;font-weight: bold;color: #fff;text-shadow: 2px 2px 0 #000;background-color: #000;padding: 5px;">&nbsp;&nbsp;###.#</span>
                <span style="position: absolute; top: 158px; left: 165px;">#</span>
                <!-- 編號 -->
                <span style="position: absolute; top: 60px; left: 290px;">&nbsp;&nbsp;1</span>
                <span style="position: absolute; top: 60px; left: 370px;">&nbsp;&nbsp;2</span>
                <span style="position: absolute; top: 60px; left: 450px;">&nbsp;&nbsp;3</span>
                <span style="position: absolute; top: 60px; left: 530px;">&nbsp;&nbsp;4</span>
                <span style="position: absolute; top: 60px; left: 610px;">&nbsp;&nbsp;5</span>
                <span style="position: absolute; top: 60px; left: 690px;">&nbsp;&nbsp;6</span>
                <span style="position: absolute; top: 60px; left: 770px;">&nbsp;&nbsp;7</span>
                <span style="position: absolute; top: 60px; left: 850px;">&nbsp;&nbsp;8</span>
                <span style="position: absolute; top: 60px; left: 930px;">&nbsp;&nbsp;9</span>
                <span style="position: absolute; top: 60px; left: 1010px;">10</span>

                <!-- 上PV -->

                <span style="position: absolute; top: 150px; left: 290px;"><span id="lastTemperature1"></span></span>
                <span style="position: absolute; top: 150px; left: 370px;"><span id="lastTemperature2"></span></span>
                <span style="position: absolute; top: 150px; left: 450px;"><span id="lastTemperature3"></span></span>
                <span style="position: absolute; top: 150px; left: 530px;"><span id="lastTemperature4"></span></span>
                <span style="position: absolute; top: 150px; left: 610px;"><span id="lastTemperature5"></span></span>
                <span style="position: absolute; top: 150px; left: 690px;">###.#</span>
                <span style="position: absolute; top: 150px; left: 770px;">###.#</span>
                <span style="position: absolute; top: 150px; left: 850px;">###.#</span>
                <span style="position: absolute; top: 150px; left: 930px;">###.#</span>
                <span style="position: absolute; top: 150px; left: 1010px;">###.#</span>
                <!-- 下PV -->

                <span style="position: absolute; top: 310px; left: 290px;">###.#</span>
                <span style="position: absolute; top: 310px; left: 370px;">###.#</span>
                <span style="position: absolute; top: 310px; left: 450px;">###.#</span>
                <span style="position: absolute; top: 310px; left: 530px;">###.#</span>
                <span style="position: absolute; top: 310px; left: 610px;">###.#</span>
                <span style="position: absolute; top: 310px; left: 690px;">###.#</span>
                <span style="position: absolute; top: 310px; left: 770px;">###.#</span>
                <span style="position: absolute; top: 310px; left: 850px;">###.#</span>
                <span style="position: absolute; top: 310px; left: 930px;">###.#</span>
                <span style="position: absolute; top: 310px; left: 1010px;">###.#</span>


                <div class="slider1 slider" style="position: absolute; top: 248px; left: 282px;">
                    <img src="images/Fan1 36x36.png" alt="圖片1">
                    <img src="images/Fan2 36x36.png" alt="圖片2">
                    <img src="images/Fan3 36x36.png" alt="圖片3">
                    <img src="images/Fan4 36x36.png" alt="圖片4">
                </div>

                <div class="slider2 slider" style="position: absolute; top: 248px; left: 362px;">
                    <img src="images/Fan1 36x36.png" alt="圖片1">
                    <img src="images/Fan2 36x36.png" alt="圖片2">
                    <img src="images/Fan3 36x36.png" alt="圖片3">
                    <img src="images/Fan4 36x36.png" alt="圖片4">
                </div>


                <div class="slider3 slider" style="position: absolute; top: 248px; left: 442px;">
                    <img src="images/Fan1 36x36.png" alt="圖片1">
                    <img src="images/Fan2 36x36.png" alt="圖片2">
                    <img src="images/Fan3 36x36.png" alt="圖片3">
                    <img src="images/Fan4 36x36.png" alt="圖片4">
                </div>


                <div class="slider4 slider" style="position: absolute; top: 248px; left: 522px;">
                    <img src="images/Fan1 36x36.png" alt="圖片1">
                    <img src="images/Fan2 36x36.png" alt="圖片2">
                    <img src="images/Fan3 36x36.png" alt="圖片3">
                    <img src="images/Fan4 36x36.png" alt="圖片4">
                </div>


                <div class="slider5 slider" style="position: absolute; top: 248px; left: 602px;">
                    <img src="images/Fan1 36x36.png" alt="圖片1">
                    <img src="images/Fan2 36x36.png" alt="圖片2">
                    <img src="images/Fan3 36x36.png" alt="圖片3">
                    <img src="images/Fan4 36x36.png" alt="圖片4">
                </div>


                <div class="slider6 slider" style="position: absolute; top: 248px; left: 680px;">
                    <img src="images/Fan1 36x36.png" alt="圖片1">
                    <img src="images/Fan2 36x36.png" alt="圖片2">
                    <img src="images/Fan3 36x36.png" alt="圖片3">
                    <img src="images/Fan4 36x36.png" alt="圖片4">
                </div>


                <div class="slider7 slider" style="position: absolute; top: 248px; left: 760px;">
                    <img src="images/Fan1 36x36.png" alt="圖片1">
                    <img src="images/Fan2 36x36.png" alt="圖片2">
                    <img src="images/Fan3 36x36.png" alt="圖片3">
                    <img src="images/Fan4 36x36.png" alt="圖片4">
                </div>


                <div class="slider8 slider" style="position: absolute; top: 248px; left: 837px;">
                    <img src="images/Fan1 36x36.png" alt="圖片1">
                    <img src="images/Fan2 36x36.png" alt="圖片2">
                    <img src="images/Fan3 36x36.png" alt="圖片3">
                    <img src="images/Fan4 36x36.png" alt="圖片4">
                </div>

                <div class="slider9 slider" style="position: absolute; top: 248px; left: 917px;">
                    <img src="images/Fan1 36x36.png" alt="圖片1">
                    <img src="images/Fan2 36x36.png" alt="圖片2">
                    <img src="images/Fan3 36x36.png" alt="圖片3">
                    <img src="images/Fan4 36x36.png" alt="圖片4">
                </div>

                <div class="slider10 slider" style="position: absolute; top: 248px; left: 997px;">
                    <img src="images/Fan1 36x36.png" alt="圖片1">
                    <img src="images/Fan2 36x36.png" alt="圖片2">
                    <img src="images/Fan3 36x36.png" alt="圖片3">
                    <img src="images/Fan4 36x36.png" alt="圖片4">
                </div>
                <div id="statement"></div>





                <?php

                // 定義 $data_MainSpeed 變量
                

                // 如果 $data_MainSpeed 有值，則顯示輪播元素
                // if ( == 60) {
                //   echo '  <div class="alarm" style="position: absolute; top: 248px; left: 917px;">';
                //   echo '  <img src="images/alarm1.png" alt="圖片1">';
                //   echo '  <img src="images/alarm2.png" alt="圖片2">';
                //   echo '  <img src="images/alarm3.png" alt="圖片3">';
                //   echo '</div>';
                
                // }
                ?>

                <!-- 俯瞰570 -->
                <div>
                    <img class="image_LA570_2" src="images/GlenRavenFrameMimic1.png" alt="GlenRavenFrameMimic">
                    <span style="position: absolute; top: 410px; left: 30px;">主機速度: <span id="data_MainSpeed"></span>
                        ypm<br>布速下限:
                        #.# ypm<br>布速上限: #.# ypm
                    </span>
                    <span style="position: absolute; top: 760px; left: 30px;">PT: Off</span>
                    <span style="position: absolute; top: 920px; left: 30px;">壓吸速度: #.# ypm</span>
                    <span style="position: absolute; top: 520px; left: 220px;">左落針: #.# ypm</span>
                    <span style="position: absolute; top: 640px; left: 220px;">右落針: #.# ypm</span>
                    <span style="position: absolute; top: 840px; left: 250px;">實際速度: #.# %</span>
                    <span style="position: absolute; top: 860px; left: 250px;">目標速度: #.# %</span>
                    <span style="position: absolute; top: 900px; left: 250px;">張力: #.# psi</span>
                    <span style="position: absolute; top: 965px; left: 290px;">超喂速度: <span id="data_OverFeed"></span>
                        ypm</span>
                    <span style="position: absolute; top: 720px; left: 350px;">PH: Off</span>
                    <span style="position: absolute; top: 740px; left: 350px;">目標濕度: #.# g/kg</span>
                    <span style="position: absolute; top: 760px; left: 350px;">實際濕度: #.# g/kg</span>
                    <span style="position: absolute; top: 410px; left: 440px;">Gas Used - Cubic Feet: #<br>DecaTherms: #
                        Dth<br>Gas
                        Flow Rate: # cfm</span>


                    <span style=" width: 50px; background-color: #fff; position: absolute; top: 580px; left: 415px;">#.#
                        in</span>
                    <span style=" width: 50px; background-color: #fff; position: absolute; top: 580px; left: 515px;">#.#
                        in</span>
                    <span style=" width: 50px; background-color: #fff; position: absolute; top: 580px; left: 660px;">#.#
                        in</span>
                    <span style=" width: 50px; background-color: #fff; position: absolute; top: 580px; left: 800px;">#.#
                        in</span>
                    <span style=" width: 50px; background-color: #fff; position: absolute; top: 580px; left: 930px;">#.#
                        in</span>
                    <span
                        style=" width: 50px; background-color: #fff; position: absolute; top: 580px; left: 1030px;">#.#
                        in</span>

                    <span style="position: absolute; top: 650px; left: 615px;">風箱 1</span>
                    <span style="position: absolute; top: 705px; left: 605px;">上: #.# %<br>下: #.# %</span>

                    <span style="position: absolute; top: 510px; left: 665px;">風箱 2</span>
                    <span style="position: absolute; top: 430px; left: 655px;">上: #.# %<br>下: #.# %</span>

                    <span style="position: absolute; top: 650px; left: 750px;">風箱 3</span>
                    <span style="position: absolute; top: 705px; left: 740px;">上: #.# %<br>下: #.# %</span>

                    <span style="position: absolute; top: 510px; left: 800px;">風箱 4</span>
                    <span style="position: absolute; top: 430px; left: 790px;">上: #.# %<br>下: #.# %</span>

                    <span style="position: absolute; top: 650px; left: 890px;">風箱 5</span>
                    <span style="position: absolute; top: 705px; left: 880px;">上: #.# %<br>下: #.# %</span>

                    <span style="position: absolute; top: 510px; left: 935px;">風箱 6</span>
                    <span style="position: absolute; top: 430px; left: 925px;">上: #.# %<br>下: #.# %</span>


                    <span style="position: absolute; top: 795px; left: 645px;">#.# RPM</span>
                    <span style="position: absolute; top: 795px; left: 905px;">#.# RPM</span>

                    <!-- 上溫度 -->
                    <span style="position: absolute; top: 895px; left: 596px;">###.#C</span>
                    <span style="position: absolute; top: 895px; left: 654px;">###.#C</span>
                    <span style="position: absolute; top: 895px; left: 713px;">###.#C</span>
                    <span style="position: absolute; top: 895px; left: 770px;">###.#C</span>
                    <span style="position: absolute; top: 895px; left: 828px;">###.#C</span>
                    <span style="position: absolute; top: 895px; left: 887px;">###.#C</span>

                    <!-- 下溫度 -->

                    <span style="position: absolute; top: 925px; left: 596px;">###.#C</span>
                    <span style="position: absolute; top: 925px; left: 654px;">###.#C</span>
                    <span style="position: absolute; top: 925px; left: 713px;">###.#C</span>
                    <span style="position: absolute; top: 925px; left: 770px;">###.#C</span>
                    <span style="position: absolute; top: 925px; left: 828px;">###.#C</span>
                    <span style="position: absolute; top: 925px; left: 887px;">###.#C</span>
                </div>

            </div>
        </div>
    </div>
</body>

</html>

<script>
    // setTimeout(function() {
    //   location.reload();
    // }, 2000); // 5秒後重新加載頁面
</script>

<script>
    // 獲取輪播元素和圖片元素

    var images1 = document.querySelectorAll('.slider1 img');

    var images2 = document.querySelectorAll('.slider2 img');

    var images3 = document.querySelectorAll('.slider3 img');

    var images4 = document.querySelectorAll('.slider4 img');

    var images5 = document.querySelectorAll('.slider5 img');

    var images6 = document.querySelectorAll('.slider6 img');

    var images7 = document.querySelectorAll('.slider7 img');

    var images8 = document.querySelectorAll('.slider8 img');

    var images9 = document.querySelectorAll('.slider9 img');

    var images10 = document.querySelectorAll('.slider10 img');

    var count = 0;
    // 定義輪播函數
    function slide() {
        // 隱藏所有圖片
        for (var i = 0; i < images1.length; i++) {
            images1[i].style.display = 'none';
        }
        for (var i = 0; i < images2.length; i++) {
            images2[i].style.display = 'none';
        }
        for (var i = 0; i < images3.length; i++) {
            images3[i].style.display = 'none';
        }
        for (var i = 0; i < images4.length; i++) {
            images4[i].style.display = 'none';
        }
        for (var i = 0; i < images5.length; i++) {
            images5[i].style.display = 'none';
        }
        for (var i = 0; i < images6.length; i++) {
            images6[i].style.display = 'none';
        }
        for (var i = 0; i < images7.length; i++) {
            images7[i].style.display = 'none';
        }
        for (var i = 0; i < images8.length; i++) {
            images8[i].style.display = 'none';
        }
        for (var i = 0; i < images9.length; i++) {
            images9[i].style.display = 'none';
        }
        for (var i = 0; i < images10.length; i++) {
            images10[i].style.display = 'none';
        }

        // 顯示當前圖片
        images1[index].style.display = 'block';
        images2[index].style.display = 'block';
        images3[index].style.display = 'block';
        images4[index].style.display = 'block';
        images5[index].style.display = 'block';
        images6[index].style.display = 'block';
        images7[index].style.display = 'block';
        images8[index].style.display = 'block';
        images9[index].style.display = 'block';
        images10[index].style.display = 'block';
        // 增加索引值
        index++;
        // 如果索引值超出範圍，重置為0
        if (index >= images1.length) {
            index = 0;
        }
        if (index >= images2.length) {
            index = 0;
        }
        if (index >= images3.length) {
            index = 0;
        }
        if (index >= images4.length) {
            index = 0;
        }
        if (index >= images5.length) {
            index = 0;
        }
        if (index >= images6.length) {
            index = 0;
        }
        if (index >= images7.length) {
            index = 0;
        }
        if (index >= images8.length) {
            index = 0;
        }
        if (index >= images9.length) {
            index = 0;
        }
        if (index >= images10.length) {
            index = 0;
        }
        // 設置定時器，每半秒執行一次輪播函數
        setTimeout(slide, 500);
        // console.log("555");
        // count++;
        // if (count < 3){
        // timerId = setTimeout(function () {
        //   console.log("555");
        //   slide();
        // }, 200);
        // }

    }
    // 設置初始索引值
    var index = 0;
    // 隱藏所有圖片
    // for (var i = 0; i < images1.length; i++) {
    //   images1[i].style.display = 'none';
    //   images2[i].style.display = 'none';
    // }
    // // 顯示第一張圖片
    // images1[0].style.display = 'block';
    // images2[0].style.display = 'block';
    // 設置初始索引值
    // var index = 0;
    // 啟動輪播函數
    slide();
</script>

<script>

</script>

<!-- ajax獲取數值 -->


<script>


    function autoRefresh() {
        console.log("function called");

        $.ajax({
            type: 'POST',
            url: "./get_data_ajax.php",
            data: {

            },
            success: function (result) {
                // $('#demo').html(result);
                let data = JSON.parse(result);
                $('#data_MainSpeed').html(data.data_MainSpeed);
                // $('#data_Width').html(data.data_Width);
                $('#data_OverFeed').html(data.data_OverFeed);
                // $('#demo').html(data.data_Job1);
                // $('#demo').html(data.data_Job2);
                // $('#demo').html(data.data_Time);
                $('#lastTemperature1').html(data.lastTemperature1);
                $('#lastTemperature2').html(data.lastTemperature2);
                $('#lastTemperature3').html(data.lastTemperature3);
                $('#lastTemperature4').html(data.lastTemperature4);
                $('#lastTemperature5').html(data.lastTemperature5);
                // $('#lastTemperature5').append("add");
                if (data.lastTemperature1 == 0) {
                    $('#statement').html('<div class="alarm" style="position: absolute; top: 248px; left: 917px;"><img src="images/alarm1.png" alt="圖片1"><img src="images/alarm2.png" alt="圖片2"><img src="images/alarm3.png" alt="圖片3"></div>')

                    var alarm = document.querySelectorAll('.alarm img');
                    // 定義輪播函數
                    var count2 = 0;
                    function slide1() {
                        // 隱藏當前圖片之前的所有圖片
                        for (var i = 0; i < index1; i++) {
                            alarm[i].style.display = 'none';

                        }
                        // 隱藏當前圖片之後的所有圖片
                        for (var i = index1 + 1; i < alarm.length; i++) {
                            alarm[i].style.display = 'none';

                        }
                        // 顯示當前圖片
                        alarm[index1].style.display = 'block';

                        // 增加索引值
                        index1++;
                        // 如果索引值超出範圍，重置為0
                        if (index1 >= alarm.length) {
                            index1 = 0;
                        }
                        // 設置定時器，每半秒執行一次輪播函數
                        count2++;
                        if (count2 < 3) {
                            timerId = setTimeout(function () {
                                // console.log("alarm");
                                slide1();
                            }, 300);
                        }




                        // setTimeout(slide1, 500);
                    }

                    // 設置初始索引值
                    var index1 = 0;
                    // 啟動輪播函數
                    slide1();



                    // setTimeout(doSomething, 1000);
                }
                else {
                    $('#statement').html('<div class="alarm" style="display:none; position: absolute; top: 248px; left: 917px;"><img src="images/alarm1.png" alt="圖片1"><img src="images/alarm2.png" alt="圖片2"><img src="images/alarm3.png" alt="圖片3"></div>')

                }
                // 獲取輪播元素和圖片元素


            }
        });
        setTimeout(function () {
            autoRefresh();
            // clearInterval(timerId);
        }, 3000);
    }
    autoRefresh();
</script>
<!-- 先找機台編號
SQL找符合的那筆
符合第一列resultArray
第二列resultArray2
-->
<?php //echo getValueOrDefault($resultArray['VAL_BOTTLE_SUP']); ?>

<?php
// if($_POST["machine_id"]=="B01-2刷毛機")
// {
//     $machineid="('B01','B02')";
// }
// else if($_POST["machine_id"]=="B03-4刷毛機")
// {
//     $machineid="('B03','B04')";
// }
// else if($_POST["machine_id"]=="B05-6刷毛機")
// {
//     $machineid="('B05','B06')";
// }
// else{
//     $machineid="('XXXX')";

// }

if ($_POST['selectedOption_machine_number'] != '') {
    $table_name = '[FinishMachines_Shrunking_Machine_' . substr($_POST['selectedOption_machine_number'], 0, 3) . ']';
}

// var_dump($table_name);
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

$sql = "SELECT top 1 *
  FROM $table_name
  order by [UploadTime] desc
";


$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$temperature = array();
$rpm = array();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    // $machine[]= $row["IoData"];
    $row_data=$row;
}

// $asdasd=$_POST["machine_id"];
// var_dump($machine[0]);
// var_dump($machine[1]);

// VAL_BOTTLE_INF:100,
// VAL_BOTTLE_SUP:100, 實際
// VAL_CONTRO_I:25,
// VAL_CONTRO_S:3,
// VAL_FOLDER:0.9999998,
// VAL_PELO_I:22,
// VAL_PELO_S:4,
// VAL_SVOLGITORE:5.999999,
// VAL_TENS_INF:14,
// VAL_TENS_SUP:13.4, 實際
// VAL_VELO_MT:10.5 

$dataString = $machine[0];
$dataArray = explode(",", $dataString);

// 初始化关联数组
$resultArray = array();

// 循环处理每个键值对
foreach ($dataArray as $item) {
    // 用冒号分割键和值
    list($key, $value) = explode(":", $item);

    // 移除可能存在的空格
    $key = trim($key);
    $value = trim($value);
    // 将键值对添加到关联数组
    $resultArray[$key] = $value;
}
// var_dump( $resultArray);

$dataString = $machine[1];
$dataArray2 = explode(",", $dataString);

// 初始化关联数组
$resultArray2 = array();

// 循环处理每个键值对
foreach ($dataArray2 as $item) {
    // 用冒号分割键和值
    list($key, $value) = explode(":", $item);

    // 移除可能存在的空格
    $key = trim($key);
    $value = trim($value);
    // 将键值对添加到关联数组
    $resultArray2[$key] = $value;
}
// var_dump( $resultArray2);


sqlsrv_close($conn);
function getValueOrDefault($defaultValue)
{
    return isset($defaultValue) ? round($defaultValue, 1) : '&nbsp;';
}
?>




<style>
    body {
        /* background-color: darkgrey */

    }

    #PRM_DRUM_1 {
        /* position: absolute; */
        /* font-size: 1vw; */
        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 6vw;
        background-color: white;
        border: 0.1vw solid black;
        /* border-radius: 0.4vw; */
        /* color: black; */
        position: absolute;
        top: 12.9%;
        left: 26.7%;
        color: red;
        font-size: 2vw;


    }

    #PRM_DRUM_2 {
        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 6vw;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
        top: 65.2%;
        left: 27.0%;
        color: red;
        font-size: 2vw;

    }

    #TENSION_1 {
        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 6vw;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
        top: 32.3%;
        left: 26.9%;
        color: red;
        font-size: 2vw;

    }

    #TENSION_2 {
        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 6vw;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
        top: 84.6%;
        left: 26.95%;
        color: red;
        font-size: 2vw;

    }

    #PROGRAM {
        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 14.4vw;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
        top: 37.1%;
        left: 79%;
        color: blue;
        font-size: 2vw;
    }

    #METERS {
        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 14.4vw;
        ;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
        top: 45.1%;
        left: 79%;
        color: blue;
        font-size: 2vw;
    }

    #SPEED {
        padding: 0.7vw;
        padding-top: 0.35vw;
        padding-bottom: 0.35vw;
        width: 12vw;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
        top: 86.7%;
        left: 73.6%;
        color: red;
        font-size: 3vw;
    }


    /*右上
     P_Cella_Gomma_4
P_Cella_Gomma_6
Perc_ball_cil_stacca_tessuto
Velocita_macchina_impostata
T_Misuara_catena_LET
F8_2
P_Cella_Introduzione_4
P_Cella_Introduzione_6
Perc_ball_cil_trascinamento */

    #P_Cella_Gomma_4 {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #E9E8E6;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 15.1%;
        left: 52.5%;
        color: black;
        font-size: 0.8vw;
        white-space: nowrap;
    }

    #P_Cella_Gomma_6 {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #E9E8E6;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 16.95%;
        left: 52.5%;
        color: black;
        font-size: 0.8vw;
        white-space: nowrap;
    }

    #Perc_ball_cil_stacca_tessuto {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #E9E8E6;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 12.3%;
        left: 60.9%;
        color: black;
        font-size: 0.8vw;
        white-space: nowrap;
    }

    #Velocita_macchina_impostata {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #4F4C4F;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 5.5%;
        left: 76.9%;
        color: #F2EC50;
        font-size: 1vw;
        white-space: nowrap;
    }

    #T_Misuara_catena_LET {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #E9E8E6;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 21.9%;
        left: 72.9%;
        color: black;
        font-size: 0.8vw;
        white-space: nowrap;
    }

    #F8_2 {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #E9E8E6;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 21.6%;
        left: 57.9%;
        color: black;
        font-size: 0.8vw;
        white-space: nowrap;
    }

    #P_Cella_Introduzione_4 {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #E9E8E6;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 17.95%;
        left: 95.4%;
        color: black;
        font-size: 0.8vw;
        white-space: nowrap;
    }

    #P_Cella_Introduzione_6 {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #E9E8E6;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 19.85%;
        left: 95.4%;
        color: black;
        font-size: 0.8vw;
        white-space: nowrap;
    }

    #Perc_ball_cil_trascinamento {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #E9E8E6;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 40.25%;
        left: 88.2%;
        color: black;
        font-size: 0.8vw;
        white-space: nowrap;
    }



    /* 右下
F8_2
T_Percentuale_generale
T_Percentuale_SX
T_Percentuale_DX
T_Percentuale_cil_alimentazione
T_Percentuale_cil_trascinamento

    
    */
    #F8_2_no2 {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #FFFFFF;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 59.25%;
        left: 64.2%;
        color: black;
        font-size: 0.9vw;
        white-space: nowrap;
    }

    #T_Percentuale_generale {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #FFFFFF;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 62.7%;
        left: 73.2%;
        color: black;
        font-size: 0.9vw;
        white-space: nowrap;
    }

    #T_Percentuale_SX {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #FFFFFF;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 71.1%;
        left: 69.1%;
        color: black;
        font-size: 0.9vw;
        white-space: nowrap;
    }

    #T_Percentuale_DX {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #FFFFFF;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 71.1%;
        left: 76.45%;
        color: black;
        font-size: 0.9vw;
        white-space: nowrap;
    }

    #T_Percentuale_cil_alimentazione {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #FFFFFF;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 83.1%;
        left: 72.45%;
        color: black;
        font-size: 0.9vw;
        white-space: nowrap;
    }

    #T_Percentuale_cil_trascinamento {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #FFFFFF;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 93.5%;
        left: 72.45%;
        color: black;
        font-size: 0.9vw;
        white-space: nowrap;
    }






    /*左上
T_bar_vapore_Letta
F8_11
N11_60
P_Cella_Gomma_4
P_Cella_Gomma_6
Temperatura_letta_gomma
T_Pot_perc_cil_estrazione
Press_acqua_gomma 

*/

    #T_bar_vapore_Letta {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #E9E8E6;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 1.7%;
        left: 27.9%;
        color: black;
        font-size: 1vw;
        white-space: nowrap;
    }

    #F8_11 {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #E9E8E6;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 21.5%;
        left: 2.9%;
        color: black;
        font-size: 1vw;
        white-space: nowrap;
    }

    #N11_60 {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #E9E8E6;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 24.35%;
        left: 2.9%;
        color: black;
        font-size: 1vw;
        white-space: nowrap;
    }


    #P_Cella_Gomma_4_no2 {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #FFFFFF;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 32.4%;
        left: 1.7%;
        color: black;
        font-size: 0.7vw;
        white-space: nowrap;
    }

    #P_Cella_Gomma_6_no2 {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #FFFFFF;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 34.4%;
        left: 1.7%;
        color: black;
        font-size: 0.7vw;
        white-space: nowrap;
    }


    #Temperatura_letta_gomma {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #E9E8E6;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 29.9%;
        left: 29.9%;
        color: black;
        font-size: 1vw;
        white-space: nowrap;
    }

    #T_Pot_perc_cil_estrazione {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #E9E8E6;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 12.9%;
        left: 41.5%;
        color: black;
        font-size: 1vw;
        white-space: nowrap;
    }

    #Press_acqua_gomma {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #FFFFFF;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 35.15%;
        left: 44.2%;
        color: black;
        font-size: 1vw;
        white-space: nowrap;
    }




    /* 左下
Compattazione_letta
P_Cella_Gomma_4
P_Cella_Gomma_6
P_Cella_IN_Comptex_4
P_Cella_IN_Comptex_6
P_Cella_I_II_Comptex_4
P_Cella_I_II_Comptex_6
P_Cella_Nastro_Comptex_4
P_Cella_Nastro_Comptex_6 

*/
    #Compattazione_letta {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #FFFFFF;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 71.35%;
        left: 4.2%;
        color: black;
        font-size: 1vw;
        white-space: nowrap;
    }

    #P_Cella_Gomma_4_no3 {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #FFFFFF;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 79.35%;
        left: 2.9%;
        color: black;
        font-size: 0.75vw;
        white-space: nowrap;
    }

    #P_Cella_Gomma_6_no3 {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #FFFFFF;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 81.65%;
        left: 2.9%;
        color: black;
        font-size: 0.75vw;
        white-space: nowrap;
    }

    #LC3 {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #FFFFFF;
        border: 0.2vw solid #75D638;
        position: absolute;
        top: 77.25%;
        left: 19.3%;
        color: black;
        font-size: 0.75vw;
        white-space: nowrap;
        font-weight: bold;
    }

    #P_Cella_IN_Comptex_4 {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 3.3vw;
        background-color: #FFFFFF;
        border: 0.2vw solid #DEBB92;
        position: absolute;
        top: 79.35%;
        left: 18.9%;
        color: black;
        font-size: 0.75vw;
        white-space: nowrap;
    }

    #P_Cella_IN_Comptex_4_kg {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 1.6vw;
        background-color: #FBFFFF;
        border: 0.1vw solid black;
        position: absolute;
        top: 79.38%;
        left: 22.2%;
        color: black;
        font-size: 0.8vw;
        white-space: nowrap;
        font-weight: bold;
    }

    #P_Cella_IN_Comptex_6 {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 3.3vw;
        background-color: #FBFFFF;
        border: 0.2vw solid #7BB6A5;
        position: absolute;
        top: 81.65%;
        left: 18.9%;
        color: black;
        font-size: 0.75vw;
        white-space: nowrap;
    }

    #P_Cella_IN_Comptex_6_kg {

        /* padding: 0.3vw; */
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 1.6vw;
        background-color: #FFFFFF;
        border: 0.1vw solid black;
        position: absolute;
        top: 81.69%;
        left: 22.2%;
        color: black;
        font-size: 0.8vw;
        white-space: nowrap;
        font-weight: bold;
    }

    #P_Cella_I_II_Comptex_4 {

        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #FFFFFF;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 79.75%;
        left: 29.3%;
        color: black;
        font-size: 0.75vw;
        white-space: nowrap;

    }


    #P_Cella_I_II_Comptex_6 {

        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #E4EAEA;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 81.95%;
        left: 29.3%;
        color: black;
        font-size: 0.75vw;
        white-space: nowrap;

    }


    #P_Cella_Nastro_Comptex_4 {

        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #FFFFFF;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 82.55%;
        left: 43.35%;
        color: black;
        font-size: 0.75vw;
        white-space: nowrap;

    }

    #P_Cella_Nastro_Comptex_6 {

        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 2.3vw;
        background-color: #E4EAEA;
        /* border: 0.2vw solid yellow; */
        position: absolute;
        top: 84.65%;
        left: 43.35%;
        color: black;
        font-size: 0.75vw;
        white-space: nowrap;

    }

    /* 左下
Compattazione_letta
P_Cella_Gomma_4
P_Cella_Gomma_6
P_Cella_IN_Comptex_4
P_Cella_IN_Comptex_6
P_Cella_I_II_Comptex_4
P_Cella_I_II_Comptex_6
P_Cella_Nastro_Comptex_4
P_Cella_Nastro_Comptex_6 

*/








    @media (max-width: 992px) {

        #PRM_DRUM_1,
        #PRM_DRUM_2,
        #TENSION_1,
        #TENSION_2,
        #PROGRAM,
        #METERS,
        #SPEED {
            font-size: 3vw;

        }

        #PRM_DRUM_1,
        #PRM_DRUM_2,
        #TENSION_1,
        #TENSION_2 {
            width: 9vw;

        }

        #PROGRAM,
        #METERS {
            width: 22vw;
        }

        #SPEED {
            width: 18vw;
            font-size: 4vw;

        }
    }
</style>

<div class="row justify-content-center " style="margin:0vw; zoom:0.9; margin-top:-2vw;">
    <div class="col-12 col-lg-12" style="position:relative; text-align: center; padding:0vw;">
        <img src="./images/防縮機2.png" style="width: 100%; " alt="图片描述">

        <!-- 右上 -->
        <div id="P_Cella_Gomma_4"><?php echo getValueOrDefault($row_data['RubberCellPressure_Rkg']);?></div>
        <div id="P_Cella_Gomma_6"><?php echo getValueOrDefault($row_data['RubberCellPressure_Wkg']);?></div>
        <div id="Perc_ball_cil_stacca_tessuto"><?php echo getValueOrDefault($row_data['BallCylinderStaccare_per']);?></div>
        <div id="Velocita_macchina_impostata"><?php echo getValueOrDefault($row_data['MainSpeed_Mt_min']);?></div>
        <div id="T_Misuara_catena_LET"><?php echo getValueOrDefault($row_data['Chian_mm']);?></div>
        <div id="F8_2"><?php echo getValueOrDefault($row_data['F8_per']);?></div>
        <div id="P_Cella_Introduzione_4"><?php echo getValueOrDefault($row_data['CellPressure_Rkg']);?></div>
        <div id="P_Cella_Introduzione_6"><?php echo getValueOrDefault($row_data['CellPressure_Wkg']);?></div>
        <div id="Perc_ball_cil_trascinamento"><?php echo getValueOrDefault($row_data['BallCylinderDragging_per']);?></div>



        <!-- 右下 -->
        <div id="F8_2_no2"><?php echo getValueOrDefault($row_data['F8_per']);?></div>
        <div id="T_Percentuale_generale"><?php echo getValueOrDefault($row_data['OverallPercentage_per']);?></div>
        <div id="T_Percentuale_SX"><?php echo getValueOrDefault($row_data['Overfeeding_L_per']);?></div>
        <div id="T_Percentuale_DX"><?php echo getValueOrDefault($row_data['Overfeeding_R_per']);?></div>
        <div id="T_Percentuale_cil_alimentazione"><?php echo getValueOrDefault($row_data['CylinderFeeding_per']);?></div>
        <div id="T_Percentuale_cil_trascinamento"><?php echo getValueOrDefault($row_data['CylinderDragging_per']);?></div>

        <!-- 左上 -->
        <div id="T_bar_vapore_Letta"><?php echo getValueOrDefault($row_data['SteamLettaBar']);?></div>
        <div id="F8_11"><?php echo getValueOrDefault($row_data['RubberCompression_mm']);?></div>
        <div id="N11_60"><?php echo getValueOrDefault($row_data['RubberCompression_per']);?></div>
        <div id="P_Cella_Gomma_4_no2"><?php echo getValueOrDefault($row_data['RubberCellPressure_Rkg']);?></div>
        <div id="P_Cella_Gomma_6_no2"><?php echo getValueOrDefault($row_data['RubberCellPressure_Wkg']);?></div>
        <div id="Temperatura_letta_gomma"><?php echo getValueOrDefault($row_data['RubberTemp']);?></div>
        <div id="T_Pot_perc_cil_estrazione"><?php echo getValueOrDefault($row_data['CylinderExtractionPower_per']);?></div>
        <div id="Press_acqua_gomma"><?php echo getValueOrDefault($row_data['WaterPressureRubberBar']);?></div>

        <!-- 左下 -->
        <div id="Compattazione_letta"><?php echo getValueOrDefault($row_data['Compaction_per']);?></div>
        <div id="P_Cella_Gomma_4_no3"><?php echo getValueOrDefault($row_data['RubberCell_Rkg']);?></div>
        <div id="P_Cella_Gomma_6_no3"><?php echo getValueOrDefault($row_data['RubberCell_Wkg']);?></div>
        <div id="LC3">LC3</div>
        <div id="P_Cella_IN_Comptex_4"><?php echo getValueOrDefault($row_data['Cell_IN_ComptexPressure_Rkg']);?></div>
        <div id="P_Cella_IN_Comptex_6"><?php echo getValueOrDefault($row_data['Cell_IN_ComptexPressure_Wkg']);?></div>
        <div id="P_Cella_IN_Comptex_4_kg">Kg</div>
        <div id="P_Cella_IN_Comptex_6_kg">Kg</div>
        <div id="P_Cella_I_II_Comptex_4"><?php echo getValueOrDefault($row_data['Cell_I_II_ComptexPressure_Rkg']);?></div>
        <div id="P_Cella_I_II_Comptex_6"><?php echo getValueOrDefault($row_data['Cell_I_II_ComptexPressure_Wkg']);?></div>
        <div id="P_Cella_Nastro_Comptex_4"><?php echo getValueOrDefault($row_data['Belt_Comptex_CellPressure_Rkg']);?></div>
        <div id="P_Cella_Nastro_Comptex_6"><?php echo getValueOrDefault($row_data['Belt_Comptex_CellPressure_Wkg']);?></div>


        <!-- <div id="PRM_DRUM_1"><?php echo getValueOrDefault($resultArray['VAL_BOTTLE_SUP']); ?></div>
                <div id="PRM_DRUM_2"><?php echo getValueOrDefault($resultArray2['VAL_BOTTLE_SUP']); ?></div>
                <div id="TENSION_1"><?php echo getValueOrDefault($resultArray['VAL_TENS_SUP']); ?></div>
                <div id="TENSION_2"><?php echo getValueOrDefault($resultArray2['VAL_TENS_SUP']); ?></div>
                <div id="PROGRAM"><?php echo getValueOrDefault($resultArray['']); ?></div>
                <div id="METERS"><?php echo getValueOrDefault($resultArray['VAL_VELO_MT']); ?></div>
                <div id="SPEED"><?php echo getValueOrDefault($resultArray['456']); ?></div>     -->

    </div>
</div>




<script>

    /*
    
    
    
    
    
    function Flash_section(section,command) {
        // console.log(section);
      const targetElement = document.getElementById(section); // 替换为你的目标元素的 ID
    
      let isFlashing = 0;
      function flashBackground() {
        isFlashing++;
        if (isFlashing%2==0) {
        //   targetElement
            targetElement.innerHTML="<img class='light' src='green-light.png'>"
        } else {
            targetElement.innerHTML="<img class='light' src='red-light.png'>"
        }
        if(isFlashing<10){
            setTimeout(flashBackground, 400);
        }
        else{
            return;
        }
    
      }
      if(command=="flash"){
    
    
        flashBackground();
        
      }
    
    
    
    
      else if(command=="stop"){
    
        targetElement.innerHTML="<img class='light' src='green-light.png'>"
    
    
      }
    
    }
    
    
    for(i=1;i<=10;i++){
        var id='temperature_led_'+i;
        Flash_section(id,'stop')
    
    }
    
    for(i=1;i<=10;i++){
        var id='rpm_led_'+i;
        Flash_section(id,'stop')
    
    }
    
    for(i=1;i<=4;i++){
        var id='wide_led_'+i;
        // console.log(id);
        Flash_section(id,'stop')
    
    }
    
    for(i=1;i<=2;i++){
        var id='feed_led_'+i;
        // console.log(id);
        Flash_section(id,'stop')
    
    }
    
    for(i=1;i<=1;i++){
        var id='needle_led_'+i;
        // console.log(id);
        Flash_section(id,'stop')
    
    }
    */
</script>
<!-- 先找機台編號
SQL找符合的那筆
符合第一列resultArray
第二列resultArray2
-->
<?php //echo getValueOrDefault($resultArray['VAL_BOTTLE_SUP']);        ?>

<?php
if ($_POST["selectedOption_machine_number"] == "RC1開幅解捻機") {
    $machineid = 'RC1';
} else if ($_POST["selectedOption_machine_number"] == "RC2開幅解捻機") {
    $machineid = 'RC2';

}
// else if($_POST["machine_id"]=="B05-6刷毛機")
// {
//     $machineid="('B05','B06')";
// }
else {
    $machineid = "('XXXX')";

}
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

$sql = "SELECT top 1 *
FROM FinishMachines_Untwisting_Machine_D_$machineid
order by TIME desc
";


$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$temperature = array();
$rpm = array();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    // $machine[]= $row["IoData"];
    $M2 = $row["D7100"];
    $M3 = $row["D7102"];
    $M4 = $row["D7104"];
    $M5 = $row["D7106"];
    $M6 = $row["D7108"];
    $M7 = $row["D7110"];

    $line_speed = $row["D7010"];




}
// var_dump($M2);
// var_dump($M3);
// var_dump($M4);
// var_dump($M5);
// var_dump($M6);
// var_dump($M7);
/*
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

$dataString=$machine[0];
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

$dataString=$machine[1];
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

*/
sqlsrv_close($conn);
function getValueOrDefault($defaultValue)
{
    return isset($defaultValue) ? $defaultValue : '&nbsp;';
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

    #M2 {

        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 6.5vw;
        background-color: black;
        border: 0.3vw solid yellow;
        position: absolute;
        top: 7.1%;
        left: 63.2%;
        color: white;
        font-size: 2vw;
        white-space: nowrap;
    }

    #M3 {

        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 6.5vw;
        background-color: black;
        border: 0.3vw solid yellow;
        position: absolute;
        top: 88.8%;
        left: 65.9%;
        color: white;
        font-size: 2vw;
        white-space: nowrap;
    }

    #M4 {

        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 6.5vw;
        background-color: black;
        border: 0.3vw solid yellow;
        position: absolute;
        top: 72.8%;
        left: 65.9%;
        color: white;
        font-size: 2vw;
        white-space: nowrap;
    }

    #M5 {

        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 6.5vw;
        background-color: black;
        border: 0.3vw solid yellow;
        position: absolute;
        top: 48.7%;
        left: 78.5%;
        color: white;
        font-size: 2vw;
        white-space: nowrap;
    }

    #M6 {

        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 6.5vw;
        background-color: black;
        border: 0.3vw solid yellow;
        position: absolute;
        top: 43.8%;
        left: 59.9%;
        color: white;
        font-size: 2vw;
        white-space: nowrap;
    }

    #M7 {

        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 6.5vw;
        background-color: black;
        border: 0.3vw solid yellow;
        position: absolute;
        top: 9.3%;
        left: 34.0%;
        color: white;
        font-size: 2vw;
        white-space: nowrap;
    }

    #line_speed {
        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 13vw;
        background-color: #ED7D31;
        border: 0 vw solid yellow;
        position: absolute;
        top: 29.3%;
        left: 6.0%;
        color: black;
        font-size: 2vw;
        white-space: nowrap;
    }

    @media (max-width: 992px) {

        /* #PRM_DRUM_1,
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
            width: 13vw;
            font-size: 4vw;

        } */

        #M2,
        #M3,
        #M4,
        #M5,
        #M6,
        #M7,
        #line_speed {
            width: 18vw;
            font-size: 3vw;

        }
    }
</style>

<div class="row justify-content-center " style="margin:0vw; zoom:0.9">
    <div class="col-12 col-lg-8" style="position:relative; text-align: center; padding:0vw;">
        <img src="./images/解捻機.png" style="width: 100%" alt="图片描述">
        <!-- <div id="PRM_DRUM_1">101</div>
                <div id="PRM_DRUM_2">100</div>
                <div id="TENSION_1">+6.4</div>
                <div id="TENSION_2">+7.0</div>
                <div id="PROGRAM">tm92sqd</div>
                <div id="METERS">6449</div>
                <div id="SPEED">11.0</div> -->

        <!-- // VAL_BOTTLE_INF:100,
// VAL_BOTTLE_SUP:100, 實際
// VAL_CONTRO_I:25,
// VAL_CONTRO_S:3,
// VAL_FOLDER:0.9999998,
// VAL_PELO_I:22,
// VAL_PELO_S:4,
// VAL_SVOLGITORE:5.999999,
// VAL_TENS_INF:14,
// VAL_TENS_SUP:13.4, 實際
// VAL_VELO_MT:10.5  -->
        <!-- <div id="PRM_DRUM_1"><?php echo getValueOrDefault($resultArray['VAL_BOTTLE_SUP']); ?></div>
                <div id="PRM_DRUM_2"><?php echo getValueOrDefault($resultArray2['VAL_BOTTLE_SUP']); ?></div>
                <div id="TENSION_1"><?php echo getValueOrDefault($resultArray['VAL_TENS_SUP']); ?></div>
                <div id="TENSION_2"><?php echo getValueOrDefault($resultArray2['VAL_TENS_SUP']); ?></div>
                <div id="PROGRAM"><?php echo getValueOrDefault($resultArray['']); ?></div>
                <div id="METERS"><?php echo getValueOrDefault($resultArray['VAL_VELO_MT']); ?></div>
                <div id="SPEED"><?php echo getValueOrDefault($resultArray['456']); ?></div>     -->

        <div id="M2"><?php echo getValueOrDefault($M2); ?></div>
        <div id="M3"><?php echo getValueOrDefault($M3); ?></div>
        <div id="M4"><?php echo getValueOrDefault($M4); ?></div>
        <div id="M5"><?php echo getValueOrDefault($M5); ?></div>
        <div id="M6"><?php echo getValueOrDefault($M6); ?></div>
        <div id="M7"><?php echo getValueOrDefault($M7); ?></div>
        <div id="line_speed"><?php echo getValueOrDefault($line_speed); ?> Min/M</div>


        <!-- <div id="SPEED">11.0</div> -->
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
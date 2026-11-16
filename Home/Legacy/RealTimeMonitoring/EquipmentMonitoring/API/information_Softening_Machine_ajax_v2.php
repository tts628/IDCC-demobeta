<!-- 先找機台編號
SQL找符合的那筆
符合第一列resultArray
第二列resultArray2
-->
<?php //echo getValueOrDefault($resultArray['VAL_BOTTLE_SUP']);    ?>

<?php
if ($_POST["selectedOption_machine_number"] == "CP1開幅柔軟機") {
    $machineid = 'CP1';
} else {
    $machineid = "('XXXX')";

}
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

$sql = "SELECT top 1 *
FROM FinishMachines_Softening_Machine_D_$machineid
order by TIME desc
";


$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$temperature = array();
$rpm = array();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $M2 = $row["D7100"];
    $M3 = $row["D7102"];
    $M4 = $row["D7104"];
    $M5 = $row["D7106"];
    $M6 = $row["D7108"];
    $M7 = $row["D7110"];
    $M8 = $row["D7112"];
    $M9 = $row["D7114"];
    $M10 = $row["D7116"];
    $M11 = $row["D7118"];
    $M12 = $row["D7120"];
    $M13 = $row["D7122"];
    $M14 = $row["D7124"];

    $line_speed = $row["D7010"];


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
/*
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

    #M2 {

        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 4.9vw;
        background-color: black;
        border: 0.2vw solid yellow;
        position: absolute;
        top: 28.1%;
        left: 68.4%;
        color: white;
        font-size: 1.25vw;
        white-space: nowrap;
    }

    #M3 {

        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 4.9vw;
        background-color: black;
        border: 0.2vw solid yellow;
        position: absolute;
        top: 74.6%;
        left: 75.0%;
        color: white;
        font-size: 1.25vw;
        white-space: nowrap;
    }

    #M4 {

        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 4.9vw;
        background-color: black;
        border: 0.2vw solid yellow;
        position: absolute;
        top: 48.1%;
        left: 61.8%;
        color: white;
        font-size: 1.25vw;
        white-space: nowrap;
    }

    #M6 {

padding: 0.7vw;
padding-top: 0vw;
padding-bottom: 0vw;
width: 4.9vw;
background-color: black;
border: 0.2vw solid yellow;
position: absolute;
top: 48.1%;
left: 53.8%;
color: white;
font-size: 1.25vw;
white-space: nowrap;
}


#M8 {

padding: 0.7vw;
padding-top: 0vw;
padding-bottom: 0vw;
width: 4.9vw;
background-color: black;
border: 0.2vw solid yellow;
position: absolute;
top: 47.7%;
left: 45.0%;
color: white;
font-size: 1.25vw;
white-space: nowrap;
}

#M5 {

padding: 0.7vw;
padding-top: 0vw;
padding-bottom: 0vw;
width: 4.9vw;
background-color: black;
border: 0.2vw solid yellow;
position: absolute;
top: 39.55%;
left: 58.0%;
color: white;
font-size: 1.25vw;
white-space: nowrap;
}

#M7 {

padding: 0.7vw;
padding-top: 0vw;
padding-bottom: 0vw;
width: 4.9vw;
background-color: black;
border: 0.2vw solid yellow;
position: absolute;
top: 39.3%;
left: 49.3%;
color: white;
font-size: 1.25vw;
white-space: nowrap;
}

#M9 {

padding: 0.7vw;
padding-top: 0vw;
padding-bottom: 0vw;
width: 4.9vw;
background-color: black;
border: 0.2vw solid yellow;
position: absolute;
top: 47.0%;
left: 35.0%;
color: white;
font-size: 1.25vw;
white-space: nowrap;
}


#M10 {

padding: 0.7vw;
padding-top: 0vw;
padding-bottom: 0vw;
width: 4.9vw;
background-color: black;
border: 0.2vw solid yellow;
position: absolute;
top: 63.4%;
left: 16.2%;
color: white;
font-size: 1.25vw;
white-space: nowrap;
}
#M11 {

padding: 0.7vw;
padding-top: 0vw;
padding-bottom: 0vw;
width: 4.9vw;
background-color: black;
border: 0.2vw solid yellow;
position: absolute;
top: 13.8%;
left: 16.3%;
color: white;
font-size: 1.25vw;
white-space: nowrap;
}

#M12 {

padding: 0.7vw;
padding-top: 0vw;
padding-bottom: 0vw;
width: 4.9vw;
background-color: black;
border: 0.2vw solid yellow;
position: absolute;
top: 15.9%;
left: 0%;
color: white;
font-size: 1.25vw;
white-space: nowrap;
}

#M13 {

padding: 0.7vw;
padding-top: 0vw;
padding-bottom: 0vw;
width: 4.9vw;
background-color: black;
border: 0.2vw solid yellow;
position: absolute;
top: 19.7%;
left: 46%;
color: white;
font-size: 1.25vw;
white-space: nowrap;
}


#M14 {

padding: 0.7vw;
padding-top: 0vw;
padding-bottom: 0vw;
width: 4.9vw;
background-color: black;
border: 0.2vw solid yellow;
position: absolute;
top: 11.8%;
left: 35%;
color: white;
font-size: 1.25vw;
white-space: nowrap;
}

#line_speed {

padding: 0.7vw;
padding-top: 0vw;
padding-bottom: 0vw;
width: 10.5vw;
background-color: #843C0C;
/* background-color: red; */
/* border: 0.2vw solid yellow; */
position: absolute;
top: 1.1%;
left: 78%;
color: white;
font-size: 1.6vw;
white-space: nowrap;
}

    @media (max-width: 992px) {

        #M2,
        #M3,
        #M4,
        #M5,
        #M6,
        #M7,
        #M8,
        #M9,
        #M10,
        #M11,
        #M12,
        #M13,
        #M14,
        #line_speed {
            width: 18vw;
            font-size: 3vw;
        }
    }
</style>

<div class="row justify-content-center " style="margin:0vw; zoom:0.9">
    <div class="col-12 col-lg-8" style="position:relative; text-align: center; padding:0vw;">
        <img src="./images/柔軟機.png" style="width: 100%" alt="图片描述">
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
        <div id="M8"><?php echo getValueOrDefault($M8); ?></div>
        <div id="M9"><?php echo getValueOrDefault($M9); ?></div>
        <div id="M10"><?php echo getValueOrDefault($M10); ?></div>
        <div id="M11"><?php echo getValueOrDefault($M11); ?></div>
        <div id="M12"><?php echo getValueOrDefault($M12); ?></div>
        <div id="M13"><?php echo getValueOrDefault($M13); ?></div>
        <div id="M14"><?php echo getValueOrDefault($M14); ?></div>
        <div id="line_speed"><?php echo getValueOrDefault($line_speed); ?> Min/M</div>
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
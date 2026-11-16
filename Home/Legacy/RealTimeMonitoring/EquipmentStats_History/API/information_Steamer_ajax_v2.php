<!-- 刷毛 -->
<?php
if($_POST["selectedOption_machine_number"]=="CS1蒸化機")
{
    $machineid="('CS1')";
}
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

$sql = "SELECT *
FROM [FinishMachines]
where [Name] in $machineid
";


$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
  die(print_r(sqlsrv_errors(), true));
}

$temperature = array();
$rpm = array();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
$machine[]= $row["IoData"];

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


sqlsrv_close($conn);
function getValueOrDefault($defaultValue) {
    return isset($defaultValue) ? $defaultValue : '&nbsp;';
}
?>




<style>
    body {
        /* background-color: darkgrey */
       
    }
    .value_box{
        position: absolute;
        font-size: 3.5vw;
        padding: 0.7vw ;
        padding-top: 0.2vw ;
        padding-bottom: 0.2vw ;
        /* background-color: #70AD47; */
        border: 0.2vw solid red;
        border-radius: 0.4vw;
        

    }
    #run_mode{
        padding-top: 0.3vw ;
        padding-bottom: 0.3vw ;
        font-size: 3vw;
        color: #4472C4;
        top: 1.2%;
        left: 6.0%;
        background-color: #8ED600;
        border: 0.2vw solid black;
        width: 28vw;
    }
    #value_downword_time_nodify{
        color: yellow;
        top: 27.8%;
        left: 0%;
        background-color: #00BCD4;
        border: 0.2vw solid black;
        width: 20vw;
    }
    #value_downword_time_display{
        color: yellow;
        top: 27.8%;
        left: 21.5%;
        background-color: #00BCD4;
        border: 0.2vw solid black;
        width: 20vw;

    }
    #fixation_min{
        color: yellow;
        top: 52.5%;
        left: 5.3%;
        background-color: #00BCD4;
        border: 0.2vw solid black;
        width: 10vw;
    }
    #fixation_sec{
        color: yellow;
        top: 52.5%;
        left: 23.5%;
        background-color: #00BCD4;
        border: 0.2vw solid black;
        width: 10vw;
    }
    #cloth_length{
        color: white;
        top: 80.5%;
        left: 11.8%;
        background-color: #843C0C;
        border: 0.2vw solid black;
        width: 25vw;
    }
    #cloth_captcity{
        color: #4472C4;
        top: 1.1%;
        left: 74%;
        background-color: white;
        border: 0.2vw solid black;
        width: 17vw;
    }
    #cloth_inlet_speed_set_hz{
        color: yellow;
        top: 27.8%;
        left: 64.9%;
        background-color: #00BCD4;
        border: 0.2vw solid black;
        width: 17vw;
    }
    #cloth_outlet_speed_set_hz{
        color: yellow;
        top: 27.8%;
        left: 84%;
        background-color: #00BCD4;
        border: 0.2vw solid black;
        width: 17vw;
    }
    #cloth_inlet_speed_display{
        color: #4472C4;
        top: 50.8%;
        left: 64.8%;
        background-color: white;
        border: 0.2vw solid black;
        width: 17vw;
    }
    #cloth_outlet_speed_display{
        color: #4472C4;
        top: 50.8%;
        left: 84.1%;
        background-color: white;
        border: 0.2vw solid black;
        width: 17vw;
    }
    #alarm{
        color: yellow;
        top: 80.8%;
        left: 72.5%;
        background-color: red;
        border: 0.2vw solid black;
        width: 20vw;
        padding-top: 0.5vw ;
        padding-bottom: 0.5vw ;
        font-size: 4vw;

    }
    </style>

<div class="row justify-content-center "style="margin:0vw; zoom:0.9">
            <div class="col-11" style="position:relative; text-align: center; padding:0vw;">
                <img src="./images/蒸化.png" style="width: 100%" alt="图片描述"> 
                <!-- <div id="PRM_DRUM_1">101</div>
                <div id="PRM_DRUM_2">100</div>
                <div id="TENSION_1">+6.4</div>
                <div id="TENSION_2">+7.0</div>
                <div id="PROGRAM">tm92sqd</div>
                <div id="METERS">6449</div>
                <div id="SPEED">11.0</div> -->
                <div id="run_mode" class="value_box">NORMAL MODE</div>
                <div id="value_downword_time_nodify" class="value_box">-</div>
                <div id="value_downword_time_display" class="value_box"><?php echo getValueOrDefault($resultArray['DownSecondSV']);?></div>
                <div id="fixation_min" class="value_box">0</div>
                <div id="fixation_sec" class="value_box"><?php echo getValueOrDefault($resultArray['DownSecondSV']);?></div>
                <div id="cloth_length" class="value_box"><?php echo getValueOrDefault($resultArray['ClothLengthM']);?></div>
                <div id="cloth_captcity" class="value_box"><?php echo getValueOrDefault($resultArray['Yard']);?></div>
                <div id="cloth_inlet_speed_set_hz" class="value_box"><?php echo getValueOrDefault($resultArray['ClothInletSpeedSHz']);?></div>
                <div id="cloth_outlet_speed_set_hz" class="value_box"><?php echo getValueOrDefault($resultArray['ClothOutletSpeedSHz']);?></div>
                <div id="cloth_inlet_speed_display" class="value_box"><?php echo getValueOrDefault($resultArray['ClothInletSpeedPHz']);?></div>
                <div id="cloth_outlet_speed_display" class="value_box"><?php echo getValueOrDefault($resultArray['ClothOutletSpeedPHz']);?></div>
                <div id="alarm" class="value_box">出布正常</div>
                
       <!-- <div id="PRM_DRUM_1"><?php echo getValueOrDefault($resultArray['VAL_BOTTLE_SUP']);?></div>
                <div id="PRM_DRUM_2"><?php echo getValueOrDefault($resultArray2['VAL_BOTTLE_SUP']);?></div>
                <div id="TENSION_1"><?php echo getValueOrDefault($resultArray['VAL_TENS_SUP']);?></div>
                <div id="TENSION_2"><?php echo getValueOrDefault($resultArray2['VAL_TENS_SUP']);?></div>
                <div id="PROGRAM"><?php echo getValueOrDefault($resultArray['']);?></div>
                <div id="METERS"><?php echo getValueOrDefault($resultArray['VAL_VELO_MT']);?></div>
                <div id="SPEED"><?php echo getValueOrDefault($resultArray['456']);?></div>     -->
                
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

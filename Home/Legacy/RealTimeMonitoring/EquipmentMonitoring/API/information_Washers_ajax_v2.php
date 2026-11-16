<!-- 磨毛 -->
<?php
if($_POST["selectedOption_machine_number"]=="RW1連續水洗機")
{
    $machineid="('RW1')";
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
//var_dump( $resultArray);

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
    #Grinding_1 {
            position: absolute;
            top: 8.8%;
            left: 26.7%;
            color: red;
            font-size: 2.5vw;

    }
    #Grinding_2 {
            position: absolute;
            top: 35.5%;
            left: 26.7%;
            color: red;
            font-size: 2.5vw;

    }
    #Grinding_3 {
            position: absolute;
            top: 62%;
            left: 26.7%;
            color: red;
            font-size: 2.5vw;

    }
    #Grinding_4 {
            position: absolute;
            top: 8.8%;
            left: 65%;
            color: red;
            font-size: 2.5vw;

    }
    #Grinding_5 {
            position: absolute;
            top: 35.5%;
            left: 65%;
            color: red;
            font-size: 2.5vw;

    }
    #Grinding_6 {
            position: absolute;
            top: 62%;
            left: 65%;
            color: red;
            font-size: 2.5vw;

    }
    #SEAM{
            position: absolute;
            top: 36.5%;
            left: 49%;
            color: blue;
            font-size: 2vw;
            text-align: center;
    }
    #UNDER_NUM{
            position: absolute;
            top: 86.8%;
            left: 64%;
            color: red;
            font-size: 3vw;
    }


    .Operating_status_stop{
        position: absolute;
        font-size: 1vw;
        padding: 0.7vw ;
        padding-top: 0.2vw ;
        padding-bottom: 0.2vw ;
        width: 6vw;
        background-color: #FFC000;
        border: 0.2vw solid red;
        border-radius: 0.4vw;
        color: black;

    }
    .green_value{
        position: absolute;
        font-size: 1vw;
        padding: 0.7vw ;
        padding-top: 0.2vw ;
        padding-bottom: 0.2vw ;
        width: 6vw;
        background-color: #70AD47;
        border: 0.2vw solid red;
        border-radius: 0.4vw;
        color: black;
    }
    .yellow_value{
        position: absolute;
        font-size: 1vw;
        padding: 0.7vw ;
        padding-top: 0.2vw ;
        padding-bottom: 0.2vw ;
        width: 6vw;
        background-color: #FFC000;
        border: 0.2vw solid red;
        border-radius: 0.4vw;
        color: black;
    }
    #value_steam{
        top: 1.8%;
        left: 17.8%;
        background-color: white;
        border: 0.2vw solid #4472C4;
        width: 8.4vw;

    }
    #value_water{
        top: 7.7%;
        left: 17.8%;
        background-color: white;
        border: 0.2vw solid #4472C4;
        width: 8.4vw;
    }
    #value_electricity{
        top: 1.8%;
        left: 44.7%;
        background-color: white;
        border: 0.2vw solid #4472C4;
        width: 8.4vw;
    }
    #value_Voltage{
        top: 7.6%;
        left: 37.6%;
        /* background-color: #FFC000; */

    }
    #value_Current{
        top: 7.6%;
        left: 49.8%;
        
    }
    #status_stainless_steel_wheel_1{ 
        top: 23.5%;
        left: 11.3%;
    }
    #status_Pressure_wheel_1{
        top: 23.5%;
        left: 26.3%;
    }
    #status_With_cloth_wheel_1{
        top: 23.1%;
        left: 42.0%;
    }

    #value_frequency_stainless_steel_wheel_1{
        top: 29.5%;
        left: 11.3%;
    }
    #value_frequency_Pressure_wheel_1{
        top: 29.5%;
        left: 26.3%;
    }
    #value_frequency_With_cloth_wheel_1{
        top: 29.5%;
        left: 42.0%;
    }
    #status_No_1{
        top: 43.5%;
        left: 30.0%;
    }
    #status_No_2{
        top: 43.5%;
        left: 44.5%;
    }
    #status_No_3{
        top: 43.5%;
        left: 59.0%;
    }
    #status_No_4{
        top: 43.5%;
        left: 73.6%;
    }
    #status_No_5{
        top: 84.5%;
        left: 89.4%;
    }
    #status_No_6{
        top: 93.9%;
        left: 73.6%;
    }
    #status_No_7{
        top: 93.9%;
        left: 59.2%;
    }
    #status_No_8{
        top: 93.9%;
        left: 44.8%;
    }
    #status_No_9{
        top: 93.9%;
        left: 30.1%;
    }
    #status_No_10{
        top: 93.9%;
        left: 15.5%;
    }


    #value_frequency_No_1{
        top: 43.5%;
        left: 36.5%;
    }
    #value_frequency_No_2{
        top: 43.5%;
        left: 51.0%;
    }
    #value_frequency_No_3{
        top: 43.5%;
        left: 65.5%;
    }
    #value_frequency_No_4{
        top: 43.5%;
        left: 80.1%;
    }
    #value_frequency_No_5{
        top: 90.4%;
        left: 89.4%;
    }
    #value_frequency_No_6{
        top: 93.9%;
        left: 80.1%
    }
    #value_frequency_No_7{
        top: 93.9%;
        left: 65.7%;
    }
    #value_frequency_No_8{
        top: 93.9%;
        left: 51.3%;
    }
    #value_frequency_No_9{
        top: 93.9%;
        left: 36.6%;
    }
 
    #value_frequency_No_10{
        top: 93.9%;
        left: 22.0%;
    }

    #value_PV_No_1{
        top: 50.8%;
        left: 33.3%;
    }
    #value_PV_No_2{
        top: 50.8%;
        left: 47.8%;
    }
    #value_PV_No_3{
        top: 50.8%;
        left: 61.8%;
    }
    #value_PV_No_4{
        top: 50.8%;
        left: 76.5%;
    }
    #value_PV_No_5{
        top: 64.4%;
        left: 89.3%;
    }
    #value_PV_No_6{
        top: 80.1%;
        left: 76.5%;
    }
    #value_PV_No_7{
        top: 80.1%;
        left: 61.8%;
    }
    #value_PV_No_8{
        top: 80.1%;
        left: 47.8%
    }
    #value_PV_No_9{
        top: 80.1%;
        left: 33.3%;
    }
    #value_PV_No_10{
        top: 80.1%;
        left: 18.8%;
    }

    #value_SV_No_1{
        top: 57.4%;
        left: 33.3%;
    }
    #value_SV_No_2{
        top: 57.4%;
        left: 47.8%;
    }
    #value_SV_No_3{
        top: 57.4%;
        left: 61.8%;
    }
    #value_SV_No_4{
        top: 57.4%;
        left: 76.5%;
    }
    #value_SV_No_5{
        top: 71.0%;
        left: 89.3%;
    }
    #value_SV_No_6{
        top: 86.7%;
        left: 76.5%;
    }
    #value_SV_No_7{
        top: 86.7%;
        left: 61.8%;
    }
    #value_SV_No_8{
        top: 86.7%;
        left: 47.8%
    }
    #value_SV_No_9{
        top: 86.7%;
        left: 33.3%;
    }
    #value_SV_No_10{
        top: 86.7%;
        left: 18.8%;
    }

    #value_PV_No_tension{
        top: 51.6%;
        left: 4.4%;
    }
    #value_SV_No_tension{
        top: 57.2%;
        left: 4.4%;
    }

    #status_No_out_of_cloth{
        top: 80.4%;
        left: 6.5%;
    }
    #value_frequency_No_out_of_cloth{
        top: 86.6%;
        left: 6.5%;
    }
    /* @media (max-width: 992px) {
    #Grinding_1,#Grinding_2,#Grinding_3,#Grinding_4 ,#Grinding_5 ,#Grinding_6 ,#SEAM,#UNDER_NUM{
            font-size: 4vw;
    }
    }  */
    </style>

<div class="row justify-content-center "style="margin:0vw; zoom:1">
            <div class="col-11 " style="position:relative; padding:0vw; text-align: center; ">
                <img src="./images/連續水洗.png" style="width: 100%; " alt="图片描述"> 

                <div id="value_steam" class="green_value" ><?php echo getValueOrDefault($resultArray['SteamerUsed']);?></div> 
                <div id="value_water" class="green_value" ><?php echo getValueOrDefault($resultArray['WaterUsed']);?></div> 
                <div id="value_electricity" class="green_value" ><?php echo getValueOrDefault($resultArray['PowerUsedKW']);?></div> 
                <div id="value_Current" class="green_value" ><?php echo getValueOrDefault($resultArray['CurrentRS']);?></div> 

                <div id="value_Voltage" class="yellow_value" ><?php echo getValueOrDefault($resultArray['VoltRS']);?></div> 
                <div id="value_Current" class="yellow_value" ><?php echo getValueOrDefault($resultArray['CurrentRS']);?></div> 

                
                <div id="status_stainless_steel_wheel_1" class="Operating_status_stop"><?php echo getValueOrDefault($resultArray['StainlessPrewashStatus']);?></div> 
                <div id="status_Pressure_wheel_1" class="Operating_status_stop"><?php echo getValueOrDefault($resultArray['RollerPrewashStatus']);?></div> 
                <div id="status_With_cloth_wheel_1" class="Operating_status_stop"><?php echo getValueOrDefault($resultArray['StainlessBeltPrewashStatus']);?></div> 
                <div id="value_frequency_stainless_steel_wheel_1" class="green_value"><?php echo getValueOrDefault($resultArray['StainlessPrewashHz']);?></div> 
                <div id="value_frequency_Pressure_wheel_1" class="green_value"><?php echo getValueOrDefault($resultArray['RollerPrewashHz']);?></div> 
                <div id="value_frequency_With_cloth_wheel_1" class="green_value"><?php echo getValueOrDefault($resultArray['StainlessBeltPrewashHz']);?></div> 

                <div id="status_No_1" class="Operating_status_stop"><?php echo getValueOrDefault($resultArray['MotorStatus01']);?></div> 
                <div id="status_No_2" class="Operating_status_stop"><?php echo getValueOrDefault($resultArray['MotorStatus02']);?></div> 
                <div id="status_No_3" class="Operating_status_stop"><?php echo getValueOrDefault($resultArray['MotorStatus03']);?></div> 
                <div id="status_No_4" class="Operating_status_stop"><?php echo getValueOrDefault($resultArray['MotorStatus04']);?></div> 
                <div id="status_No_5" class="Operating_status_stop"><?php echo getValueOrDefault($resultArray['MotorStatus05']);?></div> 
                <div id="status_No_6" class="Operating_status_stop"><?php echo getValueOrDefault($resultArray['MotorStatus06']);?></div> 
                <div id="status_No_7" class="Operating_status_stop"><?php echo getValueOrDefault($resultArray['MotorStatus07']);?></div> 
                <div id="status_No_8" class="Operating_status_stop"><?php echo getValueOrDefault($resultArray['MotorStatus08']);?></div> 
                <div id="status_No_9" class="Operating_status_stop"><?php echo getValueOrDefault($resultArray['MotorStatus09']);?></div> 
                <div id="status_No_10" class="Operating_status_stop"><?php echo getValueOrDefault($resultArray['MotorStatus10']);?></div> 

                <div id="value_frequency_No_1" class="green_value">-</div> 
                <div id="value_frequency_No_2" class="green_value">-</div> 
                <div id="value_frequency_No_3" class="green_value">-</div> 
                <div id="value_frequency_No_4" class="green_value">-</div> 
                <div id="value_frequency_No_5" class="green_value">-</div> 
                <div id="value_frequency_No_6" class="green_value">-</div> 
                <div id="value_frequency_No_7" class="green_value">-</div> 
                <div id="value_frequency_No_8" class="green_value">-</div> 
                <div id="value_frequency_No_9" class="green_value">-</div> 
                <div id="value_frequency_No_10" class="green_value">-</div> 

                <div id="value_PV_No_1" class="green_value"><?php echo getValueOrDefault($resultArray['MotorPV01']);?></div> 
                <div id="value_PV_No_2" class="green_value"><?php echo getValueOrDefault($resultArray['MotorPV02']);?></div> 
                <div id="value_PV_No_3" class="green_value"><?php echo getValueOrDefault($resultArray['MotorPV03']);?></div> 
                <div id="value_PV_No_4" class="green_value"><?php echo getValueOrDefault($resultArray['MotorPV04']);?></div> 
                <div id="value_PV_No_5" class="green_value"><?php echo getValueOrDefault($resultArray['MotorPV05']);?></div> 
                <div id="value_PV_No_6" class="green_value"><?php echo getValueOrDefault($resultArray['MotorPV06']);?></div> 
                <div id="value_PV_No_7" class="green_value"><?php echo getValueOrDefault($resultArray['MotorPV07']);?></div> 
                <div id="value_PV_No_8" class="green_value"><?php echo getValueOrDefault($resultArray['MotorPV08']);?></div> 
                <div id="value_PV_No_9" class="green_value"><?php echo getValueOrDefault($resultArray['MotorPV09']);?></div> 
                <div id="value_PV_No_10" class="green_value"><?php echo getValueOrDefault($resultArray['MotorPV10']);?></div> 

                <div id="value_SV_No_1" class="yellow_value"><?php echo getValueOrDefault($resultArray['MotorSV01']);?></div> 
                <div id="value_SV_No_2" class="yellow_value"><?php echo getValueOrDefault($resultArray['MotorSV02']);?></div> 
                <div id="value_SV_No_3" class="yellow_value"><?php echo getValueOrDefault($resultArray['MotorSV03']);?></div> 
                <div id="value_SV_No_4" class="yellow_value"><?php echo getValueOrDefault($resultArray['MotorSV04']);?></div> 
                <div id="value_SV_No_5" class="yellow_value"><?php echo getValueOrDefault($resultArray['MotorSV05']);?></div> 
                <div id="value_SV_No_6" class="yellow_value"><?php echo getValueOrDefault($resultArray['MotorSV06']);?></div> 
                <div id="value_SV_No_7" class="yellow_value"><?php echo getValueOrDefault($resultArray['MotorSV07']);?></div> 
                <div id="value_SV_No_8" class="yellow_value"><?php echo getValueOrDefault($resultArray['MotorSV08']);?></div> 
                <div id="value_SV_No_9" class="yellow_value"><?php echo getValueOrDefault($resultArray['MotorSV09']);?></div> 
                <div id="value_SV_No_10" class="yellow_value"><?php echo getValueOrDefault($resultArray['MotorSV10']);?></div> 

                <div id="value_PV_No_tension" class="green_value"><?php echo getValueOrDefault($resultArray['TensionPVkg']);?></div> 
                <div id="value_SV_No_tension" class="yellow_value"><?php echo getValueOrDefault($resultArray['TensionSVkg']);?></div> 
                <div id="status_No_out_of_cloth" class="Operating_status_stop"><?php echo getValueOrDefault($resultArray['FabricFeedMotorStatus']);?></div> 
                <div id="value_frequency_No_out_of_cloth" class="green_value"><?php echo getValueOrDefault($resultArray['FabricFeedMotorPV']);?></div> 

                
                
                
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

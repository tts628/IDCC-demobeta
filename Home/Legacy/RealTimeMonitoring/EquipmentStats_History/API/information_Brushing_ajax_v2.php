<!-- 刷毛 -->
<?php
if($_POST["selectedOption_machine_number"]=="B01刷毛機")
{
    $machineid="('B01')";
}
else if($_POST["selectedOption_machine_number"]=="B02刷毛機")
{
    $machineid="('B02')";
}
else if($_POST["selectedOption_machine_number"]=="B03刷毛機")
{
    $machineid="('B03')";
}
else if($_POST["selectedOption_machine_number"]=="B04刷毛機")
{
    $machineid="('B04')";
}
else if($_POST["selectedOption_machine_number"]=="B05刷毛機")
{
    $machineid="('B05')";
}else if($_POST["selectedOption_machine_number"]=="B06刷毛機")
{
    $machineid="('B06')";
}
else{
    $machineid="('XXXX')";

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
//var_dump($resultArray);

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
//var_dump( $resultArray2);


sqlsrv_close($conn);
function getValueOrDefault($defaultValue) {
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
        padding: 0.7vw ;
        padding-top: 0vw ;
        padding-bottom: 0vw ;
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
        animation: blink 1.5s infinite; 
            

    }
    #PRM_DRUM_2 {
        padding: 0.7vw ;
        padding-top: 0vw ;
        padding-bottom: 0vw ;
        width: 6vw;
        background-color: white;
        border: 0.1vw solid black;
            position: absolute;
            top: 65.2%;
            left: 27.0%;
            color: red;
            font-size: 2vw;
        animation: blink 1.5s infinite; 


    }
    #TENSION_1 {
        padding: 0.7vw ;
        padding-top: 0vw ;
        padding-bottom: 0vw ;
        width: 6vw;
        background-color: white;
        border: 0.1vw solid black;
            position: absolute;
            top: 32.3%;
            left: 26.9%;
            color: red;
            font-size: 2vw;
        animation: blink 1.5s infinite; 


    }
    #TENSION_2 {
        padding: 0.7vw ;
        padding-top: 0vw ;
        padding-bottom: 0vw ;
        width: 6vw;
        background-color: white;
        border: 0.1vw solid black;
            position: absolute;
            top: 84.6%;
            left: 26.95%;
            color: red;
            font-size: 2vw;
        animation: blink 1.5s infinite; 


    }
    #PROGRAM{
        padding: 0.7vw ;
        padding-top: 0vw ;
        padding-bottom: 0vw ;
        width: 14.4vw;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
            top: 37.1%;
            left: 79%;  
            color: blue;
            font-size: 2vw;
        animation: blink 1.5s infinite; 

    }
    #METERS{
        padding: 0.7vw ;
        padding-top: 0vw ;
        padding-bottom: 0vw ;
        width: 14.4vw;;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
            top: 45.1%;
            left: 79%; 
            color: blue;
            font-size: 2vw;
        animation: blink 1.5s infinite; 

    }
    #SPEED{
        padding: 0.7vw ;
        padding-top: 0.35vw ;
        padding-bottom: 0.35vw ;
        width: 12vw;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
            top: 86.7%;
            left: 73.6%;
            color: red;
            font-size: 3vw;
        animation: blink 1.5s infinite; 

    }
    #VAL_PELO_SUP{
        padding: 0.7vw ;
        padding-top: 0.35vw ;
        padding-bottom: 0.35vw ;
        width: 6vw;
        height: 7vh;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
            top: 20.6%;
            left: 3.9%;
            color: red;
            font-size: 2vw;
        animation: blink 1.5s infinite; 

    }
    #VAL_PELO_INF{
        padding: 0.7vw ;
        padding-top: 0.35vw ;
        padding-bottom: 0.35vw ;
        width: 6vw;
        height: 7vh;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
            top: 72.8%;
            left: 3.9%;
            color: red;
            font-size: 2vw;
        animation: blink 1.5s infinite; 

    }
    #VAL_CPELO_INF{
        padding: 0.7vw ;
        padding-top: 0.35vw ;
        padding-bottom: 0.35vw ;
        width: 6vw;
        height: 7vh;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
            top: 72.8%;
            left: 49.8%;
            color: red;
            font-size: 2vw;
        animation: blink 1.5s infinite; 

    }
    #VAL_CPELO_SUP{
        padding: 0.7vw ;
        padding-top: 0.35vw ;
        padding-bottom: 0.35vw ;
        width: 6vw;
        height: 7vh;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
            top: 20.6%;
            left: 49.8%;
            color: red;
            font-size: 2vw;
        animation: blink 1.5s infinite; 

    }
    #DR_MACCHINA{
        padding: 0.7vw ;
        padding-top: 0.35vw ;
        padding-bottom: 0.35vw ;
        width: 5vw;
        height: 7.8vh;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
            top: 46%;
            left: 49.8%;
            color: red;
            font-size: 2vw;
        animation: blink 1.5s infinite; 

    }
    #VAL_SVOLGITORE{
        padding: 0.7vw ;
        padding-top: 0.35vw ;
        padding-bottom: 0.35vw ;
        width: 6vw;
        height: 7vh;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
            top: 11%;
            left: 78.5%;
            color: red;
            font-size: 2vw;
        animation: blink 1.5s infinite; 

    }
    #VAL_FOLDER{
        padding: 0.7vw ;
        padding-top: 0.35vw ;
        padding-bottom: 0.35vw ;
        width: 6vw;
        height: 7vh;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
            top: 27.7%;
            left: 79%;
            color: red;
            font-size: 2vw;
        animation: blink 1.5s infinite; 

    }
    #VAL_FOLDER{
        padding: 0.7vw ;
        padding-top: 0.35vw ;
        padding-bottom: 0.35vw ;
        width: 6vw;
        height: 7vh;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
            top: 27.7%;
            left: 79%;
            color: red;
            font-size: 2vw;
        animation: blink 1.5s infinite; 

    }
    @keyframes blink {
        0% { background-color: #FFFFFF; }
        50% { background-color: #CCCCCC; }
        100% { background-color: #FFFFFF; }
    }

  
  

        /*透過格線限制div位置 */
   /* #overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        background-image: linear-gradient(to right, rgba(255, 255, 255, 0.5) 1px, transparent 1px), 
                        linear-gradient(to bottom, rgba(255, 255, 255, 0.5) 1px, transparent 1px);
        background-size: 20px 20px; 
    }*/



    @media (max-width: 992px) {
    #PRM_DRUM_1,#PRM_DRUM_2,#TENSION_1,#TENSION_2 ,#PROGRAM ,#METERS ,#SPEED, #VAL_PELO_SUP, #VAL_PELO_INF, #VAL_CPELO_SUP,#VAL_CPELO_INF{
            font-size: 3vw;
            
    }
    #PRM_DRUM_1,#PRM_DRUM_2,#TENSION_1,#TENSION_2,#VAL_PELO_SUP,#VAL_PELO_INF,#VAL_CPELO_SUP,#VAL_CPELO_INF{
        width: 9vw;

    }
    #PROGRAM ,#METERS{
        width: 22vw;
    }
    #SPEED{
        width: 18vw;
        font-size: 4vw;

    }
    }  
    </style>

<div class="row justify-content-center "style="margin:0vw; zoom:0.9">
            <div class="col-12 col-lg-8" style="position:relative; text-align: center; padding:0vw;">
                <img src="./images/刷毛.png" style="width: 100%" alt="图片描述"> 
                <div id="overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none;">
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
                <div id="PRM_DRUM_1"><?php echo getValueOrDefault($resultArray['VAL_BOTTLE_SUP']);?></div>
                <div id="PRM_DRUM_2"><?php echo getValueOrDefault($resultArray['VAL_BOTTLE_INF']);?></div>
                <div id="TENSION_1"><?php echo getValueOrDefault(number_format($resultArray['VAL_TENS_SUP'],1));?></div>
                <div id="TENSION_2"><?php echo getValueOrDefault(number_format($resultArray['VAL_TENS_INF'],1));?></div>
                <div id="PROGRAM"><?php echo getValueOrDefault($resultArray['PRO_NAME_MAC']);?></div>
                <div id="METERS"><?php echo getValueOrDefault($resultArray['MT_PARTIAL']);?></div>
                <div id="SPEED"><?php echo getValueOrDefault($resultArray['VAL_VELO_MT']);?></div>      
                <div id="VAL_PELO_SUP"><?php echo getValueOrDefault($resultArray['VAL_PELO_SUP']);?></div>  
                <div id="VAL_CPELO_SUP"><?php echo getValueOrDefault($resultArray['VAL_CPELO_SUP']);?></div>  
                <div id="VAL_PELO_INF"><?php echo getValueOrDefault($resultArray['VAL_PELO_INF']);?></div>  
                <div id="VAL_CPELO_INF"><?php echo getValueOrDefault($resultArray['VAL_CPELO_INF']);?></div>  
                <div id="DR_MACCHINA"><?php echo getValueOrDefault($resultArray['DR_MACCHINA']);?></div>  
                <div id="VAL_SVOLGITORE"><?php echo getValueOrDefault(number_format(floatval($resultArray['VAL_SVOLGITORE']), 1)); ?></div>  
                <div id="VAL_FOLDER"><?php echo getValueOrDefault(number_format(floatval($resultArray['VAL_FOLDER']), 1)); ?></div>  

                </div>
            </div>
</div>




<script>
document.addEventListener("DOMContentLoaded", function() {
    const elements = document.querySelectorAll('#overlay > div');

    elements.forEach(element => {
        const rect = element.getBoundingClientRect();
        console.log(element.id, `Top: ${rect.top}, Left: ${rect.left}`);
    });
});

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

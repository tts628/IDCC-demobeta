<!-- 磨毛 -->
<?php
if($_POST["selectedOption_machine_number"]=="G01磨毛機")
{
    $selectedOption_machine_number="('G01')";
}

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

$sql = "SELECT *
FROM [FinishMachines]
where [Name] in $selectedOption_machine_number

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


// $dataString="Temperature1:100.3, Temperature2:120.5, Speed1:1200, Speed2:1600";
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
        /* padding: 0.7vw ; */
        padding-top: 0vw ;
        padding-bottom: 0vw ;
        width: 6.5vw;
        background-color: white;
        border: 0.2vw solid black;
            position: absolute;
            top: 8.8%;
            left: 24.7%;
            color: red;
            font-size: 2.5vw;
        animation: blink 2.5s infinite; 


    }
    #Grinding_2 {
        /* padding: 0.7vw ; */
        padding-top: 0vw ;
        padding-bottom: 0vw ;
        width: 6.5vw;
        background-color: white;
        border: 0.2vw solid black;
            position: absolute;
            top: 35.5%;
            left: 24.7%;
            color: red;
            font-size: 2.5vw;
        animation: blink 2.5s infinite; 


    }
    #Grinding_3 {
        /* padding: 0.7vw ; */
        padding-top: 0vw ;
        padding-bottom: 0vw ;
        width: 6.5vw;
        background-color: white;
        border: 0.2vw solid black;
            position: absolute;
            top: 62%;
            left: 24.7%;
            color: red;
            font-size: 2.5vw;
        animation: blink 2.5s infinite; 


    }
    #Grinding_4 {
        /* padding: 0.7vw ; */
        padding-top: 0vw ;
        padding-bottom: 0vw ;
        width: 6.5vw;
        background-color: white;
        border: 0.2vw solid black;
            position: absolute;
            top: 8.8%;
            left: 63.5%;
            color: red;
            font-size: 2.5vw;
        animation: blink 2.5s infinite; 


    }
    #Grinding_5 {
        /* padding: 0.7vw ; */
        padding-top: 0vw ;
        padding-bottom: 0vw ;
        width: 6.5vw;
        background-color: white;
        border: 0.2vw solid black;
            position: absolute;
            top: 35.5%;
            left: 63.5%;
            color: red;
            font-size: 2.5vw;
        animation: blink 2.5s infinite; 


    }
    #Grinding_6 {
        /* padding: 0.7vw ; */
        padding-top: 0vw ;
        padding-bottom: 0vw ;
        width: 6.5vw;
        background-color: white;
        border: 0.2vw solid black;
            position: absolute;
            top: 62%;
            left: 63.5%;
            color: red;
            font-size: 2.5vw;
        animation: blink 2.5s infinite; 


    }
    #SEAM{
        padding: 0.7vw ;
        padding-top: 0.2vw ;
        padding-bottom: 0.2vw ;
        width: 5vw;
        background-color: white;
        border: 0.2vw solid black;
            position: absolute;
            top: 35.5%;
            left: 45.5%;
            color: blue;
            font-size: 2vw;
            text-align: center;
        animation: blink 2.5s infinite; 

    }
    @keyframes blink {
        0% { background-color: #FFFFFF; }
        50% { background-color: #CCCCCC; }
        100% { background-color: #FFFFFF; }
    }
    #UNDER_NUM{
        padding: 0.7vw ;
        padding-top: 0.3vw ;
        padding-bottom: 0.3vw ;
        width: 16vw;
        background-color: white;
        border: 0.2vw solid black;
            position: absolute;
            top: 85.2%;
            left: 56.4%;
            color: red;
            font-size: 3vw;
        animation: blink 2.5s infinite; 

    }
    @media (max-width: 992px) {
    #Grinding_1,#Grinding_2,#Grinding_3,#Grinding_4 ,#Grinding_5 ,#Grinding_6 ,#SEAM,#UNDER_NUM{
            font-size: 4vw;
    }
    #Grinding_1,#Grinding_2,#Grinding_3,#Grinding_4 ,#Grinding_5 ,#Grinding_6{
        padding-top: 0.5vw ;
        padding-bottom: 0.5vw ;
        width: 13vw;
    }
    #SEAM{
        width: 10vw;
        padding-top: 0.5vw ;
        padding-bottom: 0.5vw ;
        animation: blink 2.5s infinite; 

    }
    #UNDER_NUM{
        width: 32vw;
        padding-top: 2vw ;
        padding-bottom: 2vw ;
        font-size: 5vw;
        animation: blink 2.5s infinite; 


    }

    } 
    </style>

<div class="row justify-content-center "style="margin:0vw; zoom:0.9">
            <div class="col-12 col-lg-6 " style="position:relative; text-align: center; padding:0vw;">
                <img src="./images/磨毛.png" style="width: 70%; " alt="图片描述"> 
                <!-- <div id="Grinding_1">100</div>
                <div id="Grinding_2">100</div>
                <div id="Grinding_3">100</div>
                <div id="Grinding_4">100</div>
                <div id="Grinding_5">100</div>
                <div id="Grinding_6">100</div>
                <div id="SEAM">0</div>
                <div id="UNDER_NUM">13.5</div> -->

                <!-- ALL_ARIA:False,
                ALL_DR1_HIGH:False,
                ALL_DR1_LOW:False,
                ALL_DR2_HIGH:False,
                ALL_DR2_LOW:False,
                ALL_DRS_HIGH:False,
                ALL_DRS_LOW:False,
                ALL_EMERGENZA:True,
                ALL_INVERTER:False,
                ALL_INV_MONO:False,
                ALL_INV_SPAZZOLA_1:False,
                ALL_INV_SPAZZOLA_2:False,
                ALL_INV_SPAZZOLA_3:False,
                ALL_INV_SPAZZOLA_4:False,
                ALL_INV_SPAZZOLA_5:False,
                ALL_INV_SPAZZOLA_6:False,
                ALL_INV_SVOLGI:False,
                ALL_INV_TRAINO_ENT:False,
                ALL_INV_TRAINO_INT:False,
                ALL_INV_TRAINO_USCITA:False,
                ALL_PORTE:False,
                ALL_SCORREVOLI:False,
                ALL_SERVICE:False,
                ALL_TEMPERATURA:False,
                ALL_TERMICO:False,
                APPRO_SPAZZ_1:6,
                APPRO_SPAZZ_2:6,
                APPRO_SPAZZ_3:6,
                APPRO_SPAZZ_4:1,
                APPRO_SPAZZ_5:1,
                APPRO_SPAZZ_6:1,
                DR_CENTRALE:2,
                DR_ENTRATA:-10,
                DR_SVOLGITORE:-25,
                FABRIC_HEIGHT:10,
                HOURS:687,
                LED_ROT_1:False,
                LED_ROT_2:False,
                LED_ROT_3:False,
                LED_ROT_4:False,
                LED_ROT_5:False,
                LED_ROT_6:False,
                LED_RUN:False,
                MINUTES:6,
                MT_PARTIAL:722369,
                MT_TOTAL:722369,
                POS_SPAZ_AB:-12141,
                PRO_NAME_MAC:ALL ZERO,
                SEAM_NR:0,
                VAL_SPAZZOLA_1:1300,
                VAL_SPAZZOLA_2:1300,
                VAL_SPAZZOLA_3:1200,
                VAL_SPAZZOLA_4:1200,
                VAL_SPAZZOLA_5:1200,
                VAL_SPAZZOLA_6:1200,
                VAL_VELO_MT:13.5 -->

                <div id="Grinding_1"><?php echo getValueOrDefault($resultArray['VAL_SPAZZOLA_1']);?></div>
                <div id="Grinding_2"><?php echo getValueOrDefault($resultArray['VAL_SPAZZOLA_2']);?></div>
                <div id="Grinding_3"><?php echo getValueOrDefault($resultArray['VAL_SPAZZOLA_3']);?></div>
                <div id="Grinding_4"><?php echo getValueOrDefault($resultArray['VAL_SPAZZOLA_4']);?></div>
                <div id="Grinding_5"><?php echo getValueOrDefault($resultArray['VAL_SPAZZOLA_5']);?></div>
                <div id="Grinding_6"><?php echo getValueOrDefault($resultArray['VAL_SPAZZOLA_6']);?></div>
                <div id="SEAM"><?php echo getValueOrDefault($resultArray['SEAM_NR']);?></div>
                <div id="UNDER_NUM">主速<?php echo getValueOrDefault($resultArray['VAL_VELO_MT']);?></div>


                
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

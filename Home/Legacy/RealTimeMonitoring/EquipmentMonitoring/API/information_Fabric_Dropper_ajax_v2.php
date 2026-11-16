<!-- 磨毛 -->
<?php
$selectedOption = $_POST["selectedOption_machine_number"];

switch ($selectedOption) {
    case "GR01落布機":
        $selectedOption_machine_number = "('GR01')";
        break;
    case "GR02落布機":
        $selectedOption_machine_number = "('GR02')";
        break;
    case "GR03落布機":
        $selectedOption_machine_number = "('GR03')";
        break;
    case "GR04落布機":
        $selectedOption_machine_number = "('GR04')";
        break;
    case "GR05落布機":
        $selectedOption_machine_number = "('GR05')";
        break;
    case "GR06落布機":
        $selectedOption_machine_number = "('GR06')";
        break;
    // 可以繼續添加更多的case來處理其他選項
    default:
        $selectedOption_machine_number = "('未知機器')";
        break;
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
    var_dump($key);
    var_dump($value);
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

<style>
        /* body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #282c34;
            font-family: Arial, sans-serif;
            overflow: hidden;
        } */

        .wave-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin-bottom: 20px;
        }

        .wave {
            position: absolute;
            border-radius: 50%;
            width: 100%;
            height: 100%;
            /* background: radial-gradient(circle, rgba(0, 255, 0, 0.6) 0%, rgba(0, 255, 0, 0) 70%); */
            /* background:  linear-gradient(to bottom right, rgba(0, 255, 0, 0.6) 0%, rgba(0, 255, 0, 0) 100%); */
            background: radial-gradient(circle, rgba(0, 255, 0, 0.2) 0%, rgba(0, 255, 0, 0.4) 70%);
            /* background:  linear-gradient(to bottom right, rgba(0, 255, 0, 0) 0%, rgba(0, 255, 0, 0.6) 100%); */
            
            /* background: linear-gradient(to bottom right, var(--greyLight-2) 0%, var(--white) 100%); */
            /* box-shadow: 0.4rem 0.4rem 0.8rem var(--greyLight-2), -0.4rem -0.4rem 0.8rem var(--white); */
            animation: ripple 2s linear  infinite;
            pointer-events: none;
        }

        .wave:nth-child(1) {
            animation-delay: 0s;
            animation-duration: 2s;
        }

        .wave:nth-child(2) {
            animation-delay: -1s;
            animation-duration: 2.5s;
        }

        .wave:nth-child(3) {
            animation-delay: -0.5s;
            animation-duration: 3s;
        }

        @keyframes ripple {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                /* transform: scale(1.1); */
                opacity: 1;
            }
            100% {
                transform: scale(1.5);
                opacity: 0;
            }
        }
        .wave-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-35%, -40%);
            color: #00FF00;
            /* font-size: 1.5em; */
            font-size: 1.5em;

            font-weight: bold;
            text-shadow: 0 0 12px rgba(0, 0, 0, 0.8);
        }

       
    </style>


<div class="row justify-content-center "style="margin:0vw; height:85vh;">
    <div class="wave-container">
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave"></div>
        <div class="wave-text">運行中</div>
    </div>
    <script>
            // document.querySelector('.wave').style.background = 'radial-gradient(circle, rgba(255, 0, 0, 0.2) 0%, rgba(255,0,  0, 0.4) 70%)';
            // document.querySelector('.wave:nth-child(1)').style.background = 'radial-gradient(circle, rgba(255, 0, 0, 0.2) 0%, rgba(255,0,  0, 0.4) 70%)';
            // document.querySelector('.wave:nth-child(2)').style.background = 'radial-gradient(circle, rgba(255, 0, 0, 0.2) 0%, rgba(255,0,  0, 0.4) 70%)';
            // document.querySelector('.wave:nth-child(3)').style.background = 'radial-gradient(circle, rgba(255, 0, 0, 0.2) 0%, rgba(255,0,  0, 0.4) 70%)';
            // document.querySelector('.wave-text').innerHTML  = '未運行';
            // document.querySelector('.wave-text').style.color = 'red';
            // document.querySelector('.wave-text').style.textShadow = 'red';

    </script>
            <!-- <div class="col-12 col-lg-6 " style="position:relative; text-align: center; padding:0vw;">
                <img src="./images/磨毛.png" style="width: 70%; " alt="圖片"> 
                

                <div id="Grinding_1"><?php echo getValueOrDefault($resultArray['VAL_SPAZZOLA_1']);?></div>
                <div id="Grinding_2"><?php echo getValueOrDefault($resultArray['VAL_SPAZZOLA_2']);?></div>
                <div id="Grinding_3"><?php echo getValueOrDefault($resultArray['VAL_SPAZZOLA_3']);?></div>
                <div id="Grinding_4"><?php echo getValueOrDefault($resultArray['VAL_SPAZZOLA_4']);?></div>
                <div id="Grinding_5"><?php echo getValueOrDefault($resultArray['VAL_SPAZZOLA_5']);?></div>
                <div id="Grinding_6"><?php echo getValueOrDefault($resultArray['VAL_SPAZZOLA_6']);?></div>
                <div id="SEAM"><?php echo getValueOrDefault($resultArray['SEAM_NR']);?></div>
                <div id="UNDER_NUM">主速<?php echo getValueOrDefault($resultArray['VAL_VELO_MT']);?></div>


                
            </div> -->
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

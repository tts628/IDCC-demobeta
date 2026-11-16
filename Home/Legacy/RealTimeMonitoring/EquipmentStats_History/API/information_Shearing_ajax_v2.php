<!-- 剪毛 -->
<?php
if($_POST["selectedOption_machine_number"]=="C01剪毛機")
{
    $machineid="('C01')";
}
else if($_POST["selectedOption_machine_number"]=="C02剪毛機")
{
    $machineid="('C02')";

}
else if($_POST["selectedOption_machine_number"]=="C03剪毛機")
{
    $machineid="('C03')";

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
where [Name]=$machineid
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

//var_dump($machineid);
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


$resultArray['TEN_FOLDER']=number_format((float)$resultArray['TEN_FOLDER'], 1);
$resultArray['TEN_INLET']=number_format((float)$resultArray['TEN_INLET'], 1);

$resultArray['TEN_FOLDER']=  ($resultArray['TEN_FOLDER'] >= 0) ? "+".$resultArray['TEN_FOLDER'] : $resultArray['TEN_FOLDER'];
$resultArray['TEN_INLET']=  ($resultArray['TEN_INLET'] >= 0) ? "+".$resultArray['TEN_INLET'] : $resultArray['TEN_INLET'];


function getValueOrDefault($defaultValue) {
    return isset($defaultValue) ? $defaultValue : '&nbsp;';
}
//var_dump($resultArray);
sqlsrv_close($conn);
?>

<style>
    body {
        /* background-color: darkgrey */
       
    }
    #OIL_1{
        padding: 0.7vw ;
        padding-top: 0.1vw ;
        padding-bottom: 0.1vw ;
        width: 12vw;
        background-color: white;
        border: 0.1vw solid black;
            position: absolute;
            top: 37.5%;
            left: 6.2%;
            color: red;
            font-size: 3vw;
        animation: blink 2.5s infinite; 

    }
    #OIL_2{
        padding: 0.7vw ;
        padding-top: 0.1vw ;
        padding-bottom: 0.1vw ;
        width: 12vw;
        background-color: white;
        border: 0.1vw solid black;
            position: absolute;
            top: 56.1%;
            left: 6.2%;
            color: red;
            font-size: 3vw;
        animation: blink 2.5s infinite; 

    }
    #SEAM{
        padding: 0.7vw ;
        padding-top: 0vw ;
        padding-bottom: 0vw ;
        width: 8vw;
        background-color: white;
        border: 0.1vw solid black;
            position: absolute;
            top: 85.7%;
            left: 1.5%;
            color: red;
            font-size: 3vw;
        animation: blink 2.5s infinite; 

    }
    #MEMO{
        padding: 0.7vw ;
        padding-top: 0vw ;
        padding-bottom: 0vw ;
        width: 8vw;
        background-color: white;
        border: 0.1vw solid black;
            position: absolute;
            top: 85.7%;
            left: 14.8%;
            color: BLUE;
            font-size: 3vw;
        animation: blink 2.5s infinite; 

    }
    #RESET{
        padding: 0.7vw ;
        padding-top: 0.35vw ;
        padding-bottom: 0.35vw ;
        width: 14vw;
        background-color: white;
        border: 0.1vw solid black;
            position: absolute;
            top: 1.7%;
            left: 75.5%;
            color: black;
            font-size: 3vw;
        animation: blink 2.5s infinite; 

    }
    #Work_number{
        padding: 0.7vw ;
        padding-top: 0.35vw ;
        padding-bottom: 0.35vw ;
        width: 23vw;
        background-color: white;
        border: 0.1vw solid black;
            position: absolute;
            top: 1.7%;
            left: 33.1%;
            color: black;
            font-size: 3vw;
        animation: blink 2.5s infinite; 

        
    }
    #OUTLET_TENSION{
        padding: 0.7vw ;
        padding-top: 0.3vw ;
        padding-bottom: 0.3vw ;
        width: 12vw;
        background-color: white;
        border: 0.1vw solid black;
            position: absolute;
            top: 43.5%;
            left: 77.0%;
            color: red;
            font-size: 2.5vw;
        animation: blink 2.5s infinite; 

    }
    #SPEED{
        padding: 0.7vw ;
        padding-top: 0.3vw ;
        padding-bottom: 0.3vw ;
        width: 12vw;
        background-color: white;
        border: 0.1vw solid black;
            position: absolute;
            top: 64.1%;
            left: 77.0%;
            color: red;
            font-size: 2.5vw;
        animation: blink 2.5s infinite; 

    }
    #INLET_TENSION{
        padding: 0.7vw ;
        padding-top: 0.3vw ;
        padding-bottom: 0.3vw ;
        width: 12vw;
        background-color: white;
        border: 0.1vw solid black;
            position: absolute;
            top: 83.2%;
            left: 77.0%;
            color: red;
            font-size: 2.5vw;
        animation: blink 2.5s infinite; 

    }
    @keyframes blink {
        0% { background-color: #FFFFFF; }
        50% { background-color: #CCCCCC; }
        100% { background-color: #FFFFFF; }
    }


    @media (max-width: 992px) {
    #OIL_1,#OIL_2,#SEAM,#MEMO ,#RESET ,#Work_number ,#OUTLET_TENSION,#SPEED,#INLET_TENSION{
            font-size: 4vw;
    }
    #OIL_1,#OIL_2{
        width: 24vw;

    }
    #SEAM,#MEMO{
        width: 13.2vw;
        padding-top: 0.35vw ;
        padding-bottom: 0.35vw ;
    }
    #RESET{
        width: 25vw;
        padding-top: 1vw ;
        padding-bottom: 1vw ;
    }
    #Work_number{
        width: 42vw;
        padding-top: 1vw ;
        padding-bottom: 1vw ;
    }
    #OUTLET_TENSION,#SPEED,#INLET_TENSION{
        width: 20vw;

    }
    } 
    </style>

<div class="row justify-content-center "style="margin:0vw; zoom:0.9; ">
            <div class="col-12 col-lg-7" style="position:relative; text-align: center; padding:0vw;">
                <img src="./images/剪毛.png" style="width: 100%" alt="图片描述"> 
                <!-- <div id="OIL_1">930</div>
                <div id="OIL_2">930</div>
                 <div id="SEAM">0</div>
                <div id="MEMO">14</div>
                 <div id="RESET">14894</div>
                <div id="Work_number">KFB287SGZ-1</div>
                <div id="OUTLET_TENSION">-3.6</div>
                 <div id="SPEED">11.0</div>
                <div id="INLET_TENSION">+7.8</div> -->
                
                <!-- MAN_APP_SHE:8,
                memo   MAN_APP_VEL:13,
                PARTIAL_COUNTER_MT:7568,
                Work_number    RECIPE_NUMBER:0,
                 RPM_SHEARING:920,
                 RPM_VELVET:810,
                RUN_MAC:True,
                 TEN_FOLDER:-3.200001,
                 TEN_INLET:7.999998,
                TOTAL_COUNTER_MT:4069665,
                 speed VEL_TESS_MTM:14  -->

                <div id="OIL_1"><?php echo getValueOrDefault($resultArray['RPM_SHEARING']);?></div>
                <div id="OIL_2"><?php echo getValueOrDefault($resultArray['RPM_VELVET']);?></div>
                <!-- <div id="SEAM"><?php echo  getValueOrDefault($resultArray['']);?></div> -->
                <!-- <div id="MEMO"><?php echo getValueOrDefault($resultArray['MAN_APP_VEL']);?></div> -->
                <!-- <div id="RESET"><?php echo getValueOrDefault($resultArray['']);?></div> -->
                <!-- <div id="Work_number"><?php echo getValueOrDefault($resultArray['RECIPE_NUMBER']);?></div> -->
                
                <div id="OUTLET_TENSION"><?php echo getValueOrDefault($resultArray['TEN_FOLDER']);?></div>
                <div id="SPEED"><?php echo getValueOrDefault($resultArray['VEL_TESS_MTM']);?></div>                
                <div id="INLET_TENSION"><?php echo getValueOrDefault($resultArray['TEN_INLET']);?></div>
                
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

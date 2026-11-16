<!-- 剪毛 -->
<?php
if ($_POST["selectedOption_machine_number"] == "SG01燒毛機") {
    $machineid = "SG1";
} else if ($_POST["selectedOption_machine_number"] == "SG02燒毛機") {
    $machineid = "SG2";
} else {
    $machineid = "('XXXX')";

}

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

$sql = "SELECT top 1 *
FROM FinishMachines_Burning_Machine_D_$machineid
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


    $line_speed = $row["D7010"];


}


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

sqlsrv_close($conn);
$resultArray['TEN_FOLDER'] = number_format((float) $resultArray['TEN_FOLDER'], 1);
$resultArray['TEN_INLET'] = number_format((float) $resultArray['TEN_INLET'], 1);

$resultArray['TEN_FOLDER'] = ($resultArray['TEN_FOLDER'] >= 0) ? "+" . $resultArray['TEN_FOLDER'] : $resultArray['TEN_FOLDER'];
$resultArray['TEN_INLET'] = ($resultArray['TEN_INLET'] >= 0) ? "+" . $resultArray['TEN_INLET'] : $resultArray['TEN_INLET'];


function getValueOrDefault($defaultValue)
{
    return isset($defaultValue) ? $defaultValue : '&nbsp;';
}
?>




<style>
    body {
        /* background-color: darkgrey */

    }

    #OIL_1 {
        padding: 0.7vw;
        padding-top: 0.3vw;
        padding-bottom: 0.3vw;
        width: 12vw;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
        top: 38.5%;
        left: 6.5%;
        color: red;
        font-size: 3vw;
    }

    #OIL_2 {
        padding: 0.7vw;
        padding-top: 0.3vw;
        padding-bottom: 0.3vw;
        width: 12vw;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
        top: 56.5%;
        left: 6.5%;
        color: red;
        font-size: 3vw;
    }

    #SEAM {
        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 8vw;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
        top: 85.7%;
        left: 1.5%;
        color: red;
        font-size: 3vw;
    }

    #MEMO {
        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 8vw;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
        top: 85.7%;
        left: 14.8%;
        color: BLUE;
        font-size: 3vw;
    }

    #RESET {
        padding: 0.7vw;
        padding-top: 0.35vw;
        padding-bottom: 0.35vw;
        width: 14vw;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
        top: 1.7%;
        left: 75.5%;
        color: black;
        font-size: 3vw;

    }

    #Work_number {
        padding: 0.7vw;
        padding-top: 0.35vw;
        padding-bottom: 0.35vw;
        width: 23vw;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
        top: 1.7%;
        left: 33.1%;
        color: black;
        font-size: 3vw;

    }

    #OUTLET_TENSION {
        padding: 0.7vw;
        padding-top: 0.3vw;
        padding-bottom: 0.3vw;
        width: 12vw;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
        top: 44.5%;
        left: 77.0%;
        color: red;
        font-size: 2.5vw;

    }

    #SPEED {
        padding: 0.7vw;
        padding-top: 0.3vw;
        padding-bottom: 0.3vw;
        width: 12vw;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
        top: 64.1%;
        left: 77.0%;
        color: red;
        font-size: 2.5vw;

    }

    #INLET_TENSION {
        padding: 0.7vw;
        padding-top: 0.3vw;
        padding-bottom: 0.3vw;
        width: 12vw;
        background-color: white;
        border: 0.1vw solid black;
        position: absolute;
        top: 83.2%;
        left: 77.0%;
        color: red;
        font-size: 2.5vw;

    }

    #D7100 {

        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 6.2vw;
        background-color: black;
        border: 0.2vw solid white;
        position: absolute;
        top: 11.8%;
        left: 20.9%;
        color: white;
        font-size: 1.6vw;
        white-space: nowrap;
        animation: blink 2.5s infinite; 

    }

    #D7102 {

        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 6.2vw;
        background-color: black;
        border: 0.2vw solid white;
        position: absolute;
        top: 70.8%;
        left: 15.3%;
        color: white;
        font-size: 1.6vw;
        white-space: nowrap;
        animation: blink 2.5s infinite; 

    }

    #D7104 {

        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 6.2vw;
        background-color: black;
        border: 0.2vw solid white;
        position: absolute;
        top: 93.8%;
        left: 22.3%;
        color: white;
        font-size: 1.6vw;
        white-space: nowrap;
        animation: blink 2.5s infinite; 

    }

    #D7106 {

        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 6.2vw;
        background-color: black;
        border: 0.2vw solid white;
        position: absolute;
        top: 81.5%;
        left: 35.3%;
        color: white;
        font-size: 1.6vw;
        white-space: nowrap;
        animation: blink 2.5s infinite; 

    }

    #D7108 {

        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 6.2vw;
        background-color: black;
        border: 0.2vw solid white;
        position: absolute;
        top: 59.8%;
        left: 32.5%;
        color: white;
        font-size: 1.6vw;
        white-space: nowrap;
        animation: blink 2.5s infinite; 

    }

    #D7110 {
        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 6.2vw;
        background-color: black;
        border: 0.2vw solid white;
        position: absolute;
        top: 56.8%;
        left: 46.5%;
        color: white;
        font-size: 1.6vw;
        white-space: nowrap;
        animation: blink 2.5s infinite; 


    }

    #D7112 {

        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 6.2vw;
        background-color: black;
        border: 0.2vw solid white;
        position: absolute;
        top: 44.8%;
        left: 31.5%;
        color: white;
        font-size: 1.6vw;
        white-space: nowrap;
        animation: blink 2.5s infinite; 

    }

    #D7114 {

        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 6.2vw;
        background-color: black;
        border: 0.2vw solid white;
        position: absolute;
        top: 25.8%;
        left: 54.5%;
        color: white;
        font-size: 1.6vw;
        white-space: nowrap;
        animation: blink 2.5s infinite; 

    }

    #D7116 {

        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 6.2vw;
        background-color: black;
        border: 0.2vw solid white;
        position: absolute;
        top: 54.2%;
        left: 59.5%;
        color: white;
        font-size: 1.6vw;
        white-space: nowrap;
        animation: blink 2.5s infinite; 

    }

    #D7118 {

        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 6.2vw;
        background-color: black;
        border: 0.2vw solid white;
        position: absolute;
        top: 68.2%;
        left: 76.3%;
        color: white;
        font-size: 1.6vw;
        white-space: nowrap;
        animation: blink 2.5s infinite; 

    }

    #D7120 {

        padding: 0.7vw;
        padding-top: 0vw;
        padding-bottom: 0vw;
        width: 6.2vw;
        background-color: black;
        border: 0.2vw solid white;
        position: absolute;
        top: 7.2%;
        left: 89.7%;
        color: white;
        font-size: 1.6vw;
        white-space: nowrap;
        animation: blink 2.5s infinite; 

    }

    @keyframes blink {
        0% { background-color: #000000; }
        50% { background-color: #333333; }
        100% { background-color: #000000; }
    }

    

    @media (max-width: 992px) {




        #D7100,
        #D7102,
        #D7104,
        #D7106,
        #D7108,
        #D7110,
        #D7112,
        #D7114,
        #D7116,
        #D7118,
        #D7120 {
            width: 7.4vw;
            font-size: 2.5vw;
        }

        #OIL_1,
        #OIL_2 {
            width: 24vw;

        }

        #SEAM,
        #MEMO {
            width: 13.2vw;
            padding-top: 0.35vw;
            padding-bottom: 0.35vw;
        }

        #RESET {
            width: 25vw;
            padding-top: 1vw;
            padding-bottom: 1vw;
        }

        #Work_number {
            width: 42vw;
            padding-top: 1vw;
            padding-bottom: 1vw;
        }

        #OUTLET_TENSION,
        #SPEED,
        #INLET_TENSION {
            width: 20vw;

        }
    }
</style>

<div class="row justify-content-center " style="margin:0vw; zoom:0.9; ">
    <div class="col-12 col-lg-9" style="position:relative; text-align: center; padding:0vw;">
        <img src="./images/燒毛機.png" style="width: 100%" alt="图片描述">



        <!-- <div id="OIL_1"><?php echo getValueOrDefault($resultArray['RPM_SHEARING']); ?></div>
                <div id="OIL_2"><?php echo getValueOrDefault($resultArray['RPM_VELVET']); ?></div>
                <div id="SEAM"><?php echo getValueOrDefault($resultArray['']); ?></div>
                <div id="MEMO"><?php echo getValueOrDefault($resultArray['MAN_APP_VEL']); ?></div>
                <div id="RESET"><?php echo getValueOrDefault($resultArray['']); ?></div>
                <div id="Work_number"><?php echo getValueOrDefault($resultArray['RECIPE_NUMBER']); ?></div>
                
                <div id="OUTLET_TENSION"><?php echo getValueOrDefault($resultArray['TEN_FOLDER']); ?></div>
                <div id="SPEED"><?php echo getValueOrDefault($resultArray['']); ?></div>
                <div id="INLET_TENSION"><?php echo getValueOrDefault($resultArray['TEN_INLET']); ?></div> -->

        <div id='D7100'><?php echo getValueOrDefault($M2); ?></div>
        <div id="D7102"><?php echo getValueOrDefault($M3); ?></div>
        <div id="D7104"><?php echo getValueOrDefault($M4); ?></div>
        <div id="D7106"><?php echo getValueOrDefault($M5); ?></div>
        <div id="D7108"><?php echo getValueOrDefault($M6); ?></div>
        <div id="D7110"><?php echo getValueOrDefault($M7); ?></div>
        <div id="D7112"><?php echo getValueOrDefault($M8); ?></div>
        <div id="D7114"><?php echo getValueOrDefault($M9); ?></div>
        <div id="D7116"><?php echo getValueOrDefault($M10); ?></div>
        <div id="D7118"><?php echo getValueOrDefault($M11); ?></div>
        <div id="D7120"><?php echo getValueOrDefault($M12); ?></div>
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
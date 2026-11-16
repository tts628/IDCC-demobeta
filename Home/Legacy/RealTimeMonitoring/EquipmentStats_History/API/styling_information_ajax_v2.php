<?php
// echo ($_POST["machine"]);
if($_POST["selectedOption_machine_number"]=="T01定型機")
{
    $machine = "T01";
}
else if($_POST["selectedOption_machine_number"]=="T03定型機")
{
    $machine = "T03";
}
else if($_POST["selectedOption_machine_number"]=="T05定型機")
{
    $machine = "T05";
}
else{
    $machine="('XXXX')";

}
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

// $sql = "SELECT [ID]
// ,[Name]
// ,[Description]
// ,[Device]
// ,[Job]
// ,[Color]
// ,[ColorName]
// ,[TotalLength]
// ,[FabricName]
// ,[ProcessType]
// ,[State]
// ,[Status]
// ,[Temperature1]
// ,[Temperature2]
// ,[Temperature3]
// ,[Temperature4]
// ,[Temperature5]
// ,[Temperature6]
// ,[Temperature7]
// ,[Temperature8]
// ,[Temperature9]
// ,[Temperature10]
// ,[FanSpeed1]
// ,[FanSpeed2]
// ,[FanSpeed3]
// ,[FanSpeed4]
// ,[FanSpeed5]
// ,[FanSpeed6]
// ,[FanSpeed7]
// ,[FanSpeed8]
// ,[FanSpeed9]
// ,[FanSpeed10]
// ,[Program]
// ,[MainSpeed]
// ,[Width]
// ,[OverFeed]
// ,[JobStartTime]
// ,[JobEndTime]
// ,[Job1]
// ,[Job2]
// ,[IpcHealth]
// ,[MotorHealth]
// ,[ValveHealth]
// ,[BatteryHealth]
// ,[LubricatingHealth]
// ,[SensorHealth]
// ,[LampHealth]
// ,[Note]
// ,[LastMaintenanceTime]
// FROM [FinishMachines]
// where [Name]='$machine'
// ";



$sql = "SELECT Top(1) case when Machine='MON1'then 'T01'
when Machine='MON2'then 'T02'
when Machine='MON3'then 'T03'
else '' end  as 'Name'
,[ID]
,[Dyelot]
,[LogType_ID]
,[LogTimeStamp]
,[Machine]
,[CoolingSpeed01]
,[CoolingSpeed02]
,[ElConsumption]
,[ElConsumptionNow]
,[OilConsumption]
,[ExhaustSpeed01]
,[ExhaustSpeed02]
,[FlowRate]
,[MachineSpeed]
,[PadderRollersPressureMiddle]
,[TempForerun]
,[TempReverse]
,[TractiveForcePadder]
,[TroughLevelpadder]
,[TroughTempPadder]
,[LowerFanSpeed0102]
,[LowerFanSpeed0304]
,[LowerFanSpeed0506]
,[LowerFanSpeed0708]
,[LowerFanSpeed09]
,[UpperFanSpeed0102]
,[UpperFanSpeed0304]
,[UpperFanSpeed0506]
,[UpperFanSpeed0708]
,[UpperFanSpeed09]
,[CosinusPhi]
,[FrequencySprayerHz]
,[LeftShrinkingOverfeed]
,[GuidanceOverfeed]
,[DeliveryOverfeed]
,[InfeedOverfeed01]
,[InfeedOverfeed02]
,[PlaiterOverfeed]
,[SupportRopeOverfeed]
,[PlaiterLength1]
,[RightShrinkingOverfeed]
,[RightShrinkingTorque]
,[LeftShrinkingTorque]
,[SprayAmount]
,[StretchingPadderSpeed]
,[TempChamber01]
,[TempChamber02]
,[TempChamber03]
,[TempChamber04]
,[TempChamber05]
,[TempChamber06]
,[TempChamber07]
,[TempChamber08]
,[TempChamber09]
,[YotalOperatingHoursCounter]
,[WaterConsumption]
,[WidthSpindle01]
,[WidthSpindle02]
,[WidthSpindle03]
,[WidthSpindle04]
,[WidthSpindle05]
,[WidthSpindle06]
,[WidthSpindle07]
,[WidthSpindle08]
,[WidthSpindle09]
,[MetercounterKM]
,[Metercounterm]
FROM [FinishMachines_Stenter_Machine]
where case when Machine='MON1'then 'T01'
when Machine='MON2'then 'T03'
when Machine='MON3'then 'T05'
else '' end ='$machine'
order by LogTimeStamp desc
";
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
  die(print_r(sqlsrv_errors(), true));
}

$temperature = array();
$rpm = array();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    for ($i = 1; $i < 10; $i++) {
        $TemperatureName = 'TempChamber0'.$i; // 构建列名
        $temperature[$i] = $row[$TemperatureName]; // 从 $row 数组中获取值并存储到 $tension 数组中
        // $temperature_default[$i] = $row[$TemperatureName]; // 从 $row 数组中获取值并存储到 $tension 数组中
    }


    for ($i = 1; $i < 10; $i++) {
        $width[$i] = $row['WidthSpindle0'.$i]/100; // 从 $row 数组中获取值并存储到 $tension 数组中
        // $width_default[$i] = $row['WidthSpindle0'.$i]; // 从 $row 数组中获取值并存储到 $tension 数组中
    }


    // for ($j = 1; $j < 10; $j++) {
    //     $rpmName = 'FanSpeed'.$j; // 构建列名
    //     $rpm[$j] = $row[$rpmName]; // 从 $row 数组中获取值并存储到 $tension 数组中
    // }
    $rpm[1]= $row['LowerFanSpeed0102'];
    $rpm[2]= $row['LowerFanSpeed0102'];
    $rpm[3]= $row['LowerFanSpeed0304'];
    $rpm[4]= $row['LowerFanSpeed0304'];
    $rpm[5]= $row['LowerFanSpeed0506'];
    $rpm[6]= $row['LowerFanSpeed0506'];
    $rpm[7]= $row['LowerFanSpeed0708'];
    $rpm[8]= $row['LowerFanSpeed0708'];
    $rpm[9]= $row['LowerFanSpeed09'];


    $rpm_default[1]= $row['LowerFanSpeed0102'];
    $rpm_default[2]= $row['LowerFanSpeed0102'];
    $rpm_default[3]= $row['LowerFanSpeed0304'];
    $rpm_default[4]= $row['LowerFanSpeed0304'];
    $rpm_default[5]= $row['LowerFanSpeed0506'];
    $rpm_default[6]= $row['LowerFanSpeed0506'];
    $rpm_default[7]= $row['LowerFanSpeed0708'];
    $rpm_default[8]= $row['LowerFanSpeed0708'];
    $rpm_default[9]= $row['LowerFanSpeed09'];
    
    $MainSpeed =  $row['MachineSpeed']/10.0;
    $Width =  $row['Width'];
    // $OverFeed =  $row['OverFeed'];

    // 實際值 右邊超喂装置 超喂
    $OverFeed_right =  $row['RightShrinkingOverfeed']/10;

    // 實際值 左邊超喂裝置超喂
    $OverFeed_left =  $row['LeftShrinkingOverfeed']/10;

    // 實際值 對中裝置超喂
    $OverFeed_Guidance =  $row['GuidanceOverfeed']/10;
    
    // 實際值 出布輪超喂
    $OverFeed_Delivery =  $row['GuidanceOverfeed']/10;

}

//var_dump($temperature);

$sql = "SELECT TOP 1  [ID]
,[Dyelot]
,[LogType_ID]
,[LogTimeStamp]
,[Machine]
,[LowerFanSpeed0102_S]
,[LowerFanSpeed0304_S]
,[LowerFanSpeed0506_S]
,[LowerFanSpeed0708_S]
,[LowerFanSpeed09_S]
,[UpperFanSpeed0102_S]
,[UpperFanSpeed0304_S]
,[UpperFanSpeed0506_S]
,[UpperFanSpeed0708_S]
,[UpperFanSpeed09_S]
,[CoolingSpeed01_S]
,[CoolingSpeed02_S]
,[ExhaustSpeed01_S]
,[ExhaustSpeed02_S]
,[LeftShrinkingTorque_S]
,[LeftShrinkingOverfeed_S]
,[MachineSpeed_S]
,[GuidanceOverfeed_S]
,[DeliveryOverfeed_S]
,[InfeedOverfeed01_S]
,[InfeedOverfeed02_S]
,[PlaiterOverfeed_S]
,[SupportRopeOverfeed_S]
,[PadderRollersPressureMiddle_S]
,[PlaiterLength1_S]
,[RightShrinkingOverfeed_S]
,[RightShrinkingTorque_S]
,[SprayAmount_S]
,[StretchingPadderSpeed_S]
,[TempChamber01_S]
,[TempChamber02_S]
,[TempChamber03_S]
,[TempChamber04_S]
,[TempChamber05_S]
,[TempChamber06_S]
,[TempChamber07_S]
,[TempChamber08_S]
,[TempChamber09_S]
,[TractiveForcePadder_S]
,[TroughLevelpadder_S]
,[TroughTempPadder_S]
,[WidthSpindle01_S]
,[WidthSpindle02_S]
,[WidthSpindle03_S]
,[WidthSpindle04_S]
,[WidthSpindle05_S]
,[WidthSpindle06_S]
,[WidthSpindle07_S]
,[WidthSpindle08_S]
,[WidthSpindle09_S]
,[WaterConsumption]
FROM [FinishMachines_Stenter_Machine_Default".
$machine.
"]
order by LogTimeStamp desc
-- else '' end ='$machine'
";
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
  die(print_r(sqlsrv_errors(), true));
}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    for ($i = 1; $i < 10; $i++) {
        // $TemperatureName = ; // 构建列名
        // $temperature[$i] = $row[$TemperatureName]; // 从 $row 数组中获取值并存储到 $tension 数组中
        $temperature_default[$i] = $row['TempChamber0'.$i.'_S']; // 从 $row 数组中获取值并存储到 $tension 数组中
    }


    for ($i = 1; $i < 10; $i++) {
        // $width[$i] = $row['WidthSpindle0'.$i]; // 从 $row 数组中获取值并存储到 $tension 数组中
        $width_default[$i] = $row['WidthSpindle0'.$i.'_S']/100; // 从 $row 数组中获取值并存储到 $tension 数组中
    }
}
sqlsrv_close($conn);

?>




<style>
    body {
        /* background-color: darkgrey */
        background-image: url(/common/images/bg1.png);
        background-size: cover;
        color: white;
    }
    .light{
        height: 1.5vw;
    }
    .row{
        font-size: 1.5vw;

    }
    .data_grid {
    /* padding: 10px; */
    /* margin-top: 1vw; */
    background-color: black;
    width: 5vw;
    /* color: white; */
    }
    .ordinary_grid {
    /* padding: 10px; */
    margin-top: 1.5vw;
    /* background-color: black; */
    /* color: white; */
    }
    .title_grid{
        

        background-color: #333333;
        /* padding: 0vw; */
        font-size: 2vw;
        margin-left: 0vw;
        margin-right: 0vw;
        
        /* margin-right: 5vw; */


    }
    .divnomargin{
        margin:0vw
    }
    .back_grid{
        /* margin-top: 2vw; */
        background-color: gray;
        border: 0.1vw solid white;
        /* padding: 2vw; */
        padding:0vw;
        padding-bottom: 0.5vw;

        /* padding-top: 1vw; */
        margin: 0.5vw;
    
    }
    </style>

<div class="row justify-content-center "style="margin:0vw">
            <div class="col-10 " style="position:relative">
                    <div class="row justify-content-center ">
                        <div class="col-12 col-lg-7 back_grid ">
                            <!-- <div class="row">&nbsp;</div> -->
                            <div class="row">
                                <!-- <div class="col-4">1</div><div class="col-4">2</div><div class="col-4">3</div> -->
                            </div>
                            <div class="row text-center title_grid ">
                                    
                                <div class="col-1"></div>
                                <div class="col-1"></div>
                                <div class="col-1"><span style="font-size: 1.5vw; white-space: nowrap;">溫度</span></div>
                                <div class="col-1"><span style="font-size: 1.5vw; white-space: nowrap;">設定<span style="font-size: 0.8vw;">(°C)</span></span></div>
                                <div class="col-2" style="margin-right: -1.8vw;"></div>

                                <div class="col-1"><span style="font-size: 1.5vw; white-space: nowrap;">風速</span></div>
                                <div class="col-1"><span style="font-size: 1.5vw; white-space: nowrap;">設定<span style="font-size: 0.8vw;">(RPM)</span></span></div>
                                
                                <div class="col-2" style="margin-right: -1.8vw;"></div>

                                <div class="col-1"><span style="font-size: 1.5vw; white-space: nowrap;">幅寬</span></div>
                                <div class="col-1"><span style="font-size: 1.5vw; white-space: nowrap;">偏差<span style="font-size: 0.8vw;">(in)</span></span></div>
                            </div>
                            <?php for( $i = 1; $i < 10; $i++ ) {?>
                            <div class="row text-center ordinary_grid">
                                    
                                <div class="col-1 "><?php echo $i;?></div>
                                <div class="col-1 " id=<?php echo "temperature_led_".$i;?>>O</div>
                                <div class="col-1 "><div class="data_grid"><?php echo $temperature[$i];?></div></div>
                                <div class="col-1 "><div class="data_grid"><?php echo $temperature_default[$i];?></div></div>
                                <div class="col-2 " style="margin-right: -1.7vw;" id=<?php echo "rpm_led_".$i;?>>O</div>

                                <div class="col-1 "><div class="data_grid" ><?php echo $rpm[$i];?></div></div>
                                <div class="col-1 "><div class="data_grid" ><?php echo $rpm_default[$i];?></div></div>
                                
                                <div class="col-2 " style="margin-right: -1.8vw;" id=<?php echo "width_led_".$i;?>>O</div>

                                <div class="col-1 "><div class="data_grid" ><?php echo $width[$i];?></div></div>
                                <div class="col-1 "><div class="data_grid" ><?php echo $width_default[$i];?></div></div>
                            </div>

                            <?php }?>

                            <!-- <div class="row text-center">
                                    
                                <div class="col-1 ">1</div>
                                <div class="col-1 ">O</div>
                                <div class="col-4 "><div class="data_grid">2</div></div>
                                <div class="col-4 "><div class="data_grid">2</div></div>
                                <div class="col-1 ">O</div>
                                
                            </div> -->
                            
                        </div>
                        <div class="col-12 col-lg-4 ">
                            <div class="row">
                                <!-- Operating hours total -->
                            </div>
                            <div class="back_grid text-center">
                            <!-- <div class="row text-center back_grid ordinary_grid"> -->
                                <div class="col-12 title_grid divnomargin">Machine Speed</div>
                                <div class="col-12" style='font-size: 4vw;'><?php echo $MainSpeed;?> <span style='font-size: 2vw;'>m/min</span></div>

                            <!-- </div> -->
                            </div>

                                <div class="back_grid ">
                                    <div class="row text-center title_grid divnomargin" >
                                            <div class="col-5">超喂</div>
                                            <div class="col-2"></div>
                                            <div class="col-4">數值</div>
                                    </div>
                                    <div class="row text-center ordinary_grid">
                                            <div class="col-5">左超喂</div>
                                            <div class="col-2" id="wide_led_1">>O</div>
                                            <div class="col-4"><div class="data_grid"><?php echo $OverFeed_left;?></div></div>
                                    </div>
                                    <div class="row text-center ordinary_grid">
                                            <div class="col-5">右超喂</div>
                                            <div class="col-2" id="wide_led_2">O</div>
                                            <div class="col-4"><div class="data_grid"><?php echo $OverFeed_right;?></div></div>
                                    </div>
                                    <div class="row text-center ordinary_grid">
                                            <div class="col-5">對中裝置超喂</div>
                                            <div class="col-2" id="wide_led_3">O</div>
                                            <div class="col-4"><div class="data_grid"><?php echo $OverFeed_Guidance;?></div></div>
                                    </div>
                                    <div class="row text-center ordinary_grid">
                                            <div class="col-5">出布輪超喂</div>
                                            <div class="col-2" id="wide_led_4">O</div>
                                            <div class="col-4"><div class="data_grid"><?php echo $OverFeed_Delivery;?></div></div>
                                    </div>
                                </div>


                            <!-- <div class="row text-center">&nbsp;</div> -->
                            <!-- 上下喂 -->
                            <div class="back_grid ">
                                <div class="row text-center title_grid divnomargin">
                                        <div class="col-5"></div>
                                        <div class="col-2"></div>
                                        <div class="col-4">數值</div>
                                </div>
                                <div class="row text-center ordinary_grid ">
                                        <div class="col-5">第一節幅寬</div>
                                        <div class="col-2" id="feed_led_1">O</div>
                                        <div class="col-4"><div class="data_grid"><?php echo $width[1];?></div></div>
                                </div>
                                <div class="row text-center ordinary_grid">
                                        <div class="col-5">第九節幅寬</div>
                                        <div class="col-2" id="feed_led_2">O</div>
                                        <div class="col-4"><div class="data_grid"><?php echo $width[9];?></div></div>
                                </div>
                                <div class="row text-center ordinary_grid ">
                                        <!-- <div class="col-4" id="feed_led_1">O</div> -->
                                        <div class="col-5">落針</div>
                                        <div class="col-2" id="needle_led_1"></div>
                                        <div class="col-4"></div>
                                </div>
                            </div>
                            
                            
                        </div>
                        <!-- <div class="col-12 col-lg-3">
                            11X3
                        </div>
                        <div class="col-12 col-lg-3">
                            <div class="row">
                                5X1
                            </div>
                            <div class="row">
                                3X1
                            </div>
                        </div> -->
                </div>
            </div>
        </div>
</div>




<script>







function Flash_section(section,command) {
    // console.log(section);
  const targetElement = document.getElementById(section); // 替换为你的目标元素的 ID

  let isFlashing = 0;
  function flashBackground() {
    isFlashing++;
    if (isFlashing%2==0) {
    //   targetElement
        targetElement.innerHTML="<img class='light' src='./images/green-light.png'>"



    } else {
        targetElement.innerHTML="<img class='light' src='./images/red-light.png'>"

    }
    if(isFlashing<10){
        setTimeout(flashBackground, 400);
    }
    else{
        return;
    }
        
    // console.log(isFlashing);
  }
  if(command=="flash"){
    // var intervalId = setInterval(flashBackground, 1000); // 1秒切换一次颜色
    
    // clearInterval(intervalId);

    flashBackground();
    
  }
//   else if(command=="click"){
    // clearInterval(intervalId);
    // targetElement.style.backgroundColor = 'red';
    // targetElement_name.style.color  = 'red';


//   }
  else if(command=="stop"){
    // clearInterval(intervalId);
    // targetElement.style.backgroundColor = ' #485464';

    targetElement.innerHTML="<img class='light' src='./images/green-light.png'>"

    // targetElement_name.style.color  = 'white';

  }

  // 在需要的时候停止定时器，例如：
//   clearInterval(intervalId);
}


for(i=1;i<10;i++){
    var id='temperature_led_'+i;
    // console.log(id);
    Flash_section(id,'stop')

}

for(i=1;i<10;i++){
    var id='rpm_led_'+i;
    // console.log(id);
    Flash_section(id,'stop')

}

for(i=1;i<10;i++){
    var id='width_led_'+i;
    // console.log(id);
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
</script>

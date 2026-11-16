
<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', 'ReturnDatesAsStrings']);

$machine = $_POST["machine"];

// 找table標題
$data_tabletitle = array();
$data_title = array();
// [Name]機台名稱,[Dyelot]領料單,[FabricName]布種,[MainTemperature]主缸溫度,[Program]染程,[Step],[TotalWeight]進缸量
// [ActiveAlarms]警報
$sql = "   SELECT *
  FROM [DyeMachines]
  WHERE [Name]='$machine'
"; 
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
  die(print_r(sqlsrv_errors(), true));
}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {


  // 手動預設
  // $rope ='rope1:060, rope2:050,rope3:050,rope4:050,rope5:050';
  // $ropeArray = stringToAssociativeArray($rope);
  // $cycle ='cycle1:060, cycle2:050,cycle3:050,cycle4:050,cycle5:050';
  // $cycleArray = stringToAssociativeArray($cycle);

  // if($row['Description']=='DB838'){
  //   $Description='dongbao';
  // }
  // else if($row['Description']=='SH838'||$row['Description']=='AK838'){
  //   $Description='danny';
    
  // }
  // $row['ActiveAlarms']="延遲加料,蒸氣不足";
  // $row['Description'] = $Description;
  $Description= 'danny';



$row['DyeCallOff']='1';
$row['DyeState']='202';
$row['ChemicalCallOff']='1';
$row['ChemicalState']='202';
$row['PowderCallOff']='1';
$row['PowderState']='202';


  // 手動預設

  if($row['ActiveAlarms']==null){
    $row['ActiveAlarms']='';
  }

  if($row['Dyelot']==""){
    $row['ActiveAlarms']="";
  }

  $data = array(
  'Name' => $row['Name'],
  'MainTemperature' => $row['MainTemperature']/10,
  'Dyelot' =>$row['Dyelot'],
  'Program' => $row['Program'],
  'TotalWeight' => $row['TotalWeight'],
  'FabricName' => $row['FabricName'],

  
  'ActiveAlarms' => $row['ActiveAlarms'],
  // 'ActiveAlarms' =>"延遲加料,蒸氣不足",

  // 'Description' => $row['Description'],
  'Description' => $Description,
  // 'Description' => 'dongbao',
  
  'RopeSpeed1' =>  $row['RopeSpeed1'],
  'RopeSpeed2' =>  $row['RopeSpeed2'],
  'RopeSpeed3' =>  $row['RopeSpeed3'],
  'RopeSpeed4' =>  $row['RopeSpeed4'],
  'CycleTime1' =>  $row['CycleTime1'],
  'CycleTime2' =>  $row['CycleTime2'],
  'CycleTime3' =>  $row['CycleTime3'],
  'CycleTime4' =>  $row['CycleTime4'],
  
  // 'ColorName' => $cycleArray['ColorName'],

  'Dyelot1StartTime' => $row['Dyelot1StartTime'],
  'Dyelot1' => $row['Dyelot1'],

  // '出布開始' => '2024-06-07 08:05:14.000',
  // '出布結束' => '2024-06-07 12:04:00.000',
  // '備藥開始' => '2024-06-07 08:05:14.000',
  // '備藥結束' => '2024-06-07 12:00:00.000',
  // '化料開始' => '2024-06-07 08:05:14.000',
  // '化料結束' => '2024-06-07 15:16:00.000',

  
  '出布開始' => convertToVietnamTime($row['UnloadStartTime']),
  '出布結束' => convertToVietnamTime($row['UnloadEndTime']),
  '備藥開始' => convertToVietnamTime($row['PrepareStartTime']),
  '備藥結束' => convertToVietnamTime($row['PrepareEndTime']),
  '化料開始' => convertToVietnamTime($row['DissolveStartTime']),
  '化料結束' => convertToVietnamTime($row['DissolveEndTime']),
  					

  'DyeCallOff'	=> $row['DyeCallOff'],
  'DyeState'	=> $row['DyeState'],
  // DyeTank	

  'ChemicalCallOff'	=> $row['ChemicalCallOff'],
  'ChemicalState'	=> $row['ChemicalState'],
  // ChemicalTank	
 
  'PowderCallOff'	=> $row['PowderCallOff'],
  'PowderState'	=> $row['PowderState'],
  // PowderTank

  'Pump_Speed'=> $row['MainPumpSpeed'],
  'Reel_Speed'=> $row['ReelSpeed'],
  // 缺少
  // 'Jet_Setting'=> $row[''],
  // 'Jet_Pressure'=> $row[''],
  // 'Lap_Time'=> $row[''],
  // 'pH_Reading'=> $row[''],
  // 'Conductivity'=> $row[''],
  // 'C1'=> $row[''],
  // 'C2'=> $row[''],
  // 'Diff_C1/C2'=> $row[''],
  // 'IWS'=> $row[''],
  // 'Dark'=> $row[''],
    'Jet_Setting'=> 'XXX',
'Jet_Pressure'=> 'XXX',
'Lap_Time'=> 'XXX',
'pH_Reading'=> 'XXX',
'Conductivity'=> 'XXX',
'C1'=> 'XXX',
'C2'=> 'XXX',
'Diff_C1/C2'=> 'XXX',
'IWS'=> 'XXX',
'Dark'=> 'XXX',

);
}

sqlsrv_close($conn);


function stringToAssociativeArray($inputString) {
  // 分割字符串以逗号为分隔符
  $parts = explode(',', $inputString);

  // 初始化一个空数组，用于存储键值对
  $result = [];

  foreach ($parts as $part) {
      // 再次分割每个部分，以冒号为分隔符
      $keyValue = explode(':', $part);

      // 如果成功分割为两部分，将其添加到结果数组
      if (count($keyValue) === 2) {
          $key = trim($keyValue[0]);
          $value = trim($keyValue[1]);
          $result[$key] = (int)$value;
      }
  }

  return $result;
}

// 輸入剩餘分秒可以轉換成結束的越南當地時間
function Calculation_Time($Min_Sec)
{
  

  if ($Min_Sec == 'Finish')
    return '';
  else {
    $timeString = $Min_Sec;

    // 拆分字符串为分钟和秒数
    list($minutes, $seconds) = explode(':', $timeString);

    // 将分钟和秒数转换为整数
    $minutes = (int) $minutes;
    $seconds = (int) $seconds;

    // 计算总秒数
    $totalSeconds = $minutes * 60 + $seconds;



    // 指定时区
    // $timezone = new DateTimeZone('Asia/Taipei');
    $timezone = new DateTimeZone('Asia/Bangkok');
    
    // 获取当前时间并设置时区
    $currentDate = new DateTime('now', $timezone);

    // 增加N秒
    $currentDate->modify('+' . $totalSeconds . ' second');

    // 格式化时间
    $formattedDate = $currentDate->format('Y-m-d H:i:s.u');

    // 将毫秒部分限制为三位数
// $milliseconds = substr($formattedDate, -6, 3);

    // 拼接成最终格式
// $formattedDate = $currentDate->format('Y-m-d H:i:s') . '.' . $milliseconds;

    return $formattedDate;
  }
}

// 台北時區轉換成越南時區
function convertToVietnamTime($taipeiTime) {
  // 创建 DateTime 对象，指定台北时区
  $datetime = new DateTime($taipeiTime, new DateTimeZone('Asia/Taipei'));

  // 将时区设置为越南时区
  $datetime->setTimezone(new DateTimeZone('Asia/Ho_Chi_Minh'));

  // 格式化日期时间为字符串
  return $datetime->format('Y-m-d H:i:s.u');
}
$jsonData = json_encode($data);

// 沒有機台
if($data['Name']== null )
{
  $no_data = array(
    'Name' => $machine,
    'MainTemperature' =>"",
    'Dyelot' =>"",
    'Program' => "",
    'TotalWeight' => "",
    'FabricName' => "",
  
    
    'ActiveAlarms' => "",
    // 'ActiveAlarms' =>"延遲加料,蒸氣不足",
  
    // 'Description' => $row['Description'],
    'Description' => "danny",
    // 'Description' => 'dongbao',
    
    'RopeSpeed1' =>  "",
    'RopeSpeed2' =>  "",
    'RopeSpeed3' =>  "",
    'RopeSpeed4' =>  "",
    'CycleTime1' =>  "",
    'CycleTime2' =>  "",
    'CycleTime3' =>  "",
    'CycleTime4' =>  "",
    
    'ColorName' => "",
  
    'Dyelot1StartTime' => "",
    'Dyelot1' => "",
  
    '出布開始' => '2023-11-07 14:24:14.000',
    '出布結束' => '2023-11-07 15:30:14.000',
    '備藥開始' => '2023-11-08 13:05:14.000',
    '備藥結束' => '2023-11-08 13:31:12.000',
    '化料開始' => '2023-11-07 14:24:14.000',
    '化料結束' => '2023-11-07 14:45:14.000',
    
  
    'DyeCallOff'	=> "",
    'DyeState'	=>"",
    // DyeTank	
  
    'ChemicalCallOff'	=> "",
    'ChemicalState'	=> "",
    // ChemicalTank	
   
    'PowderCallOff'	=> "",
    'PowderState'	=> "",
    // PowderTank
  
    'Pump_Speed'=> "",
    'Reel_Speed'=>"",
      'Jet_Setting'=> 'XXX',
  'Jet_Pressure'=> 'XXX',
  'Lap_Time'=> 'XXX',
  'pH_Reading'=> 'XXX',
  'Conductivity'=> 'XXX',
  'C1'=> 'XXX',
  'C2'=> 'XXX',
  'Diff_C1/C2'=> 'XXX',
  'IWS'=> 'XXX',
  'Dark'=> 'XXX',
  
  );

  echo json_encode($no_data);
}


else{
echo $jsonData;
}
?>



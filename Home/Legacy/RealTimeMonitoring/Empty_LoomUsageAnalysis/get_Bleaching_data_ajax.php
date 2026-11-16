<?php

// require_once('./database.php');

// // //$serverName = ".\SQLEXPRESS"; // SQL Server 伺服器名稱
// $connectionInfo = array(
//     "Driver" => "ODBC Driver 17 for SQL Server"
//     ,
//     "Database" => $Database,
//     "UID" => $UID,
//     "PWD" => $PWD
//     ,
//     "CharacterSet" => "UTF-8",
//     "ReturnDatesAsStrings" => true,
//     "Encrypt" => false,
//     "TrustServerCertificate" => true
// );
// $conn = sqlsrv_connect($serverName, $connectionInfo);


// if ($conn === false) {
//     echo "NONONONO Connected!";
//     die(print_r(sqlsrv_errors(), true));

// }
// // echo "Connected successfully!";

//  ini_set('display_errors', '1');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC']);

$MMMM = $_POST['month'];
// 前20天用電量
// $sql = "  SELECT
// A.Machine as 'Machine',
// B.Machine as 'Machine2',
// A.DaysUntilEnd,
// A.EndTime,
// CONVERT(VARCHAR, A.EndTime, 120) as 'lasttime',
// COALESCE(B.times, 0) AS 'times'
// FROM
// (


// SELECT 
// Machine,
// EndTime,
// DATEDIFF(DAY, EndTime,GETDATE() ) AS DaysUntilEnd
// FROM	
// (SELECT
//     *,
//     ROW_NUMBER() OVER (PARTITION BY Machine ORDER BY EndTime DESC) AS 'machine_now'
// FROM
//      [DyeProduction]
// 	 -- where [Order] like N'VE%' 
//      )TAB

// WHERE
// machine_now = 1
// ) A
// left JOIN
// (

//   SELECT
//     Machine
//     ,count(CONVERT(VARCHAR, EndTime, 120)) as 'times'
//  FROM [DyeProduction]
// where CONVERT(VARCHAR, EndTime, 120) like '$MMMM%'
// AND [Order] like N'VE%'　
// group by Machine
// ) B
// ON
// A.Machine = B.Machine
// where A.Machine　in (select Name from [DyeMachines])


// ";

$sql = " --步驟
--停滯時間定義  現在時間減去掉報工時間
--先選取工單、目前工作站、下個工作站、報工時間
--排序各桶車和各工單的順序給Order數字，同時去掉檢布跟無車號的
--判斷每筆工單最新資料的那台車才是使用中
--只看每台車最新的一筆
--合併桶車號，方能知道沒出現過的空車在哪
SELECT 
*
from
(
SELECT 
*
--這台車的最新一筆是1
,ROW_NUMBER() OVER(PARTITION BY Cart ORDER BY Report_time desc) AS Cart_Order
--這工單的最新一筆是1
,ROW_NUMBER() OVER(PARTITION BY WO ORDER BY Report_time desc) AS WO_Order
--每筆工單最新資料的那台車才是使用中
,case when ROW_NUMBER() OVER(PARTITION BY WO ORDER BY Report_time desc)<>1 then N'空車'
when NextOperation = '' then N'空車'
else N'使用中' end as 'status'
--滯留時間
,DATEDIFF(DAY, Report_time, GETDATE()) as 'Residence_days'
,case 
when Operation in ('D1','R1','R2','R3','R4','P101','T101') then '染房' 
when Operation in ('A1','P1','T2') then '檢布'
when Operation in ('LA1','LA2') then '化驗室'
when Operation in ('J2') then '染色前剖布'
when Operation in ('C1','S1') then '脫水' 
when Operation in ('O1','Q1') then '剖布' 
when Operation in ('J1','T1') then '展布'
when Operation in ('B1','N1') then '精煉' 
when Operation in ('K1','L1') then '前定' 
when Operation in ('M1','M2') then '烘乾' 
when Operation in ('H1','Y1') then '機漂'
when Operation in ('E1','U1') then '定型' 
when Operation in ('I1','V1','MC1') then '起毛' 
when Operation in ('ISO1','V2') then '磨毛' 
when Operation in ('X1') then '中檢' 
when Operation in ('X2','DECTECT') then '成檢' 
when Operation in ('F1','G1') then '包裝'
else '其他' end as 'Operation_ch'

,case 
when NextOperation in ('D1','R1','R2','R3','R4','P101','T101') then '染房'
when NextOperation in ('A1','P1','T2') then '檢布'
when NextOperation in ('LA1','LA2') then '化驗室'
when NextOperation in ('J2') then '染色前剖布'
when NextOperation in ('C1','S1') then '脫水'
when NextOperation in ('O1','Q1') then '剖布'
when NextOperation in ('J1','T1') then '展布'
when NextOperation in ('B1','N1') then '精煉'
when NextOperation in ('K1','L1') then '前定'
when NextOperation in ('M1','M2') then '烘乾'
when NextOperation in ('H1','Y1') then '機漂'
when NextOperation in ('E1','U1') then '定型'
when NextOperation in ('I1','V1','MC1') then '起毛' 
when NextOperation in ('ISO1','V2') then '磨毛'
when NextOperation in ('X1') then '中檢'
when NextOperation in ('X2','DECTECT') then '成檢'
when NextOperation in ('F1','G1') then '包裝'
else '其他' end as 'NextOperation_ch'
from
(

SELECT 
[WO]
,[Operation]
,[OperationName]
,[NextOperation]
,[NextOperationName]
,[ItemDescription]
,[GoodsTransferOut]
,[TransferOutRework]
,trim([Cart]) as 'Cart'
,TRY_CONVERT(DATETIME, '20' + ReportDate + ' ' + ReportTime, 120) as 'Report_time'
FROM [Daluen_ERPAuto_RFID]
  
union  

SELECT  
[工單編號] as 'WO'
,'' as 'Operation'
,'' as 'OperationName'
,[作業編號] as 'NextOperation'
,[作業名稱] as 'NextOperationName'
,[品名]
,[良品轉入]
,[重工轉入]
,trim([車號]) as 'Cart'
,TRY_CONVERT(DATETIME, [報工日期], 120) as 'Report_time'
 FROM [temporary_asfr717]
  WHERE [作業名稱] not in ('A1','P1') and [工單編號]<>''
)base
where NextOperationName not like N'檢布%'  and WO <>'' 

 
)lack_ALLTruck_NoList

FULL JOIN [ALLTruck_NoList] ALLTruck_NoList

ON trim(lack_ALLTruck_NoList.Cart) = trim(ALLTruck_NoList.Name)

where 
--Cart_Order='1' 
--只找桶車清單裡面有的
--and [Name] is not null
 NextOperation_ch in (" . quoteAndJoinArray($_POST['depart']) . ")
--and [status] <>'使用中'
--and NextOperation =''
--and OperationName like '%包裝%'
--and WO like '%241212305%'

order by [Name]




";
function quoteAndJoinArray($array) {
    // 使用 array_map() 函數將每個元素加上雙引號
    $quotedArray = array_map(function($val) {
        return "'" . $val . "'";
    }, $array);

    // 使用 implode() 函數將處理後的元素用逗號連接成字串
    $resultString = implode(', ', $quotedArray);

    return $resultString;
}

// var_dump(quoteAndJoinArray($_POST['depart']));
$stmt = sqlsrv_query($conn, $sql);




if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}



//  $data_Name= array();
// $data_MainSpeed= array();
// $data_Width= array();
// $data_OverFeed= array();
// $data_Job1= array();
// $data_Job2= array();
$Name = array();
$Dyelot = array();
$Color = array();
$Step = array();
$ActiveAlarms = array();
$textcolor = array();
// $data_Temperature5 = array();
// $data_Time = array();
$ClothCarStatus = array();
$LastRecordedSection = array();
$NextSection = array();
$StandstillTime = array();
$for_count_carttitle = array();
$FabricName = array();
$WorkNo = array();
// $Total_KG = array();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

    // 抓工單最後一站的總量
    if($row['WO_Order']=='1' )
    // 算公斤 良品+重工
    $Total_KG[$row['NextOperation_ch']] += floatval($row['GoodsTransferOut'])+floatval($row['TransferOutRework']);
    
    // 我只要顯示各個桶車最後出線資訊
    if($row['Cart_Order']!='1' )
    continue;
    
    // 我只要空車或是使用中
    if($row['status']!=$_POST['status'])
    continue;

    // var_dump($row['NextOperation_ch']);
    // 奇怪的空桶車號跳過
    if ($row['Name'] == "")
        continue;


    // $data_MainSpeed = ($row['MainSpeed']);
    // $data_Width = ($row['Width']);
    // $data_OverFeed = ($row['OverFeed']);
    // $data_Job1 = ($row['Job1']);
    // $data_Job2 = ($row['Job2']);
    // $data_Temperature1[] = array($row['Temperature1']);
    // $data_Temperature2[] = array($row['Temperature2']);
    // $data_Temperature3[] = array($row['Temperature3']);
    // $data_Temperature4[] = array($row['Temperature4']);
    // $data_Temperature5[] = array($row['Temperature5']);
    // $data_Time[] = array($row['Time']);
    $lasttime[] = array(substr($row['Report_time'], 0, 19) ?? "");
    $washtime[] = array($row['times'] ?? "");
    $FabricName[] = array($row['ItemDescription'] ?? "");
    $WorkNo[] = array($row['WO'] ?? "");
    // $Name  [] = array($row['Machine'] ?? "");
    $Name[] = array($row['Name'] ?? "");
    $for_count_carttitle[] = ($row['Name'] ?? "");

    $Dyelot[] = array($row['Dyelot'] ?? "");
    // $Color [] = array($row['Color']);
    $Step[] = array($row['Step'] ?? "");


    $ClothCarStatus[] = array($row['status'] ?? "");
    $LastRecordedSection[] = array($row['Operation_ch'] ?? "");
    $NextSection[] = array($row['NextOperation_ch'] ?? "");
    $StandstillTime[] = array($row['Residence_days'] . '天' ?? "");


    $ActiveAlarms[] = array($row["ActiveAlarms"] ?? "");
    if (empty($row['Color'])) {
        $Color[] = '#ffffff';
        $b = '255';
        $g = '255';
        $r = '255';
    } else {
        $rgb = [];
        $r = $row['Color'] & 0xff;
        $g = ($row['Color'] >> 8) & 0xff;
        $b = ($row['Color'] >> 16) & 0xff;
        $rgb[0] = $r;
        $rgb[1] = $g;
        $rgb[2] = $b;
        $r1 = strval($r);
        $g1 = strval($g);
        $b1 = strval($b);
        $r2 = dechex($r);
        $g2 = dechex($g);
        $b2 = dechex($b);
        if (strlen($r2) == 1) {
            $r3 = '0' . $r2;
        } else {
            $r3 = $r2;
        }
        if (strlen($g2) == 1) {
            $g3 = '0' . $g2;
        } else {
            $g3 = $g2;
        }
        if (strlen($b2) == 1) {
            $b3 = '0' . $b2;
        } else {
            $b3 = $b2;
        }
        $Color[] = '#' . $r3 . $g3 . $b3;
    }
    //文字顏色
    $textrgb = $r * 0.299 + $g * 0.578 + $b * 0.114;
    if ($textrgb >= 192) {
        $textcolor[] = '#000000';
    } else {
        $textcolor[] = '#ffffff';
    }




    $ccc = $row['DaysUntilEnd']?? "";

    // 根据ccc的值设置颜色
    if ($ccc == 0) {
        $color = 'rgb(255, 255, 255)'; // 白色
    } elseif ($ccc == $_POST['days']) {
        $color = 'rgb(0, 0, 0)'; // 黑色
    } else {
        // 计算灰度值，这里假设ccc的取值范围在1到14之间
        $grayValue = round(($ccc - 1) / $_POST['days'] * 255);
        $invertedGrayValue = 255 - $grayValue; // 反转灰度值
        $color = "rgb($invertedGrayValue, $invertedGrayValue, $invertedGrayValue)";
    }

    $setcolor[] = array($color);


}
// $Color[] = array($textcolor);

// $lastTemperature1 = end($data_Temperature1);
// $lastTemperature2 = end($data_Temperature2);
// $lastTemperature3 = end($data_Temperature3);
// $lastTemperature4 = end($data_Temperature4);
// $lastTemperature5 = end($data_Temperature5);


sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

// $jsonData_Temperature1 = json_encode($lastTemperature1);
// $jsonData_Temperature2 = json_encode($lastTemperature2);
// $jsonData_Temperature3 = json_encode($lastTemperature3);
// $jsonData_Temperature4 = json_encode($lastTemperature4);
// $jsonData_Temperature5 = json_encode($lastTemperature5);
// $jsonData_Time = json_encode($data_Time);


// echo "page called";
// echo $_POST["SelectedDate"];
// echo $_POST["data"];
$letter_count = []; // 存储英文字母的台数
$number_count = 0; // 存储数字的台数
$carttitle = '(' . $_POST['status'] . ') ';
foreach ($for_count_carttitle as $item) {
    $first_letter = substr($item, 0, 1);  // 提取每个元素的第一个字母
    if (preg_match('/[A-Z]/', $first_letter)) {
        if (isset($letter_count[$first_letter])) {
            $letter_count[$first_letter]++;
        } else {
            $letter_count[$first_letter] = 1;
        }
    } else {
        $number_count++;
    }
}

// 输出每个英文字母的台数
foreach ($letter_count as $letter => $count) {
    $carttitle .= $letter . "車" . $count . "台 / ";
}

// 输出数字的台数
$carttitle .= "大車" . $number_count . "台\n";
$carttitle .="(";
foreach ($Total_KG as $letter => $count) {
    $carttitle .= $letter . ":" . number_format($count) . "KG ";
    // $carttitle .= $letter ;
}
$carttitle .=")";

// $carttitle .= " (" . number_format($Total_KG) . " KG)";
// $carttitle .= " (84984984";

// var_dump($Total_KG);




$array = [
    // "data_MainSpeed" => $data_MainSpeed,
    // "data_Width" => $data_Width,
    // "data_OverFeed" => $data_OverFeed,
    // "data_Job1" => $data_Job1,
    // "data_Job2" => $data_Job2,
    // "data_Time" => $data_Time,
    // "lastTemperature1" => $lastTemperature1,
    // "lastTemperature2" => $lastTemperature2,
    // "lastTemperature3" => $lastTemperature3,
    // "lastTemperature4" => $lastTemperature4,
    // "lastTemperature5" => $lastTemperature5
    // "Name"   =>  $Name  ,
    // "Dyelot" =>  $Dyelot,
    // "Color"  =>  $Color ,
    // "Step"   =>  $Step  ,
    // "ActiveAlarms" => $ActiveAlarms,
    // "machine_num" =>count($Name),
    // "textcolor"=>$textcolor
    "Name" => $Name,
    "Dyelot" => $Dyelot,
    "Color" => $Color,
    "Step" => $Step,
    "ActiveAlarms" => $ActiveAlarms,
    "machine_num" => count($Name),
    "textcolor" => $textcolor,
    "lasttime" => $lasttime,
    "washtime" => $washtime,
    "setcolor" => $setcolor,
    'ClothCarStatus' => $ClothCarStatus,
    'LastRecordedSection' => $LastRecordedSection,
    'NextSection' => $NextSection,
    'StandstillTime' => $StandstillTime,
    'carttitle' => $carttitle,
    'FabricName' => $FabricName,
    'WorkNo' => $WorkNo,
    // 'Total_KG' => $Total_KG

];
echo json_encode($array);
// var_dump($array);
// echo "updated";
// echo $_POST["data"];

?>
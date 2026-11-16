<?php
$time1 = -microtime(true);
function Writecsv($filename, $data){
    //Write to local csv
    $f = fopen($filename,'w');
    if($f === false){
        die('Error writing the file ' . $filename);
    }
    foreach ($data as $row) {
        fputcsv($f, $row);
    }
    fclose($f);
}
function Readcsv($filename){
    //fetching data from local csv
    $f = fopen($filename, 'r');
    if ($f === false) {
        die('Cannot raed the file ' . $filename);
    }
    while (($row = fgetcsv($f)) !== false) {
        $readData[] = $row;
    }
    fclose($f);
    if(!isset($readData)){
        $readData = [[0]];
    }
    return $readData;
}

date_default_timezone_set('Asia/Taipei');

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

//sql connect
$today = date("Y-m-d",(strtotime(date("Y-m-d H:i:s")) - 60*60)) . " 00:00:00";
$i = 0;
$sql = "SELECT Destination,Machine,Dyelot,ProductCode,TargetGrams,DispenseGrams,DispenseError,StartTime,ProductType,EndTime 
FROM dbo.DispenserProduction 
WHERE DispenseGrams > '0' 
AND EndTime > '$today'  
ORDER BY StartTime DESC";
$stmt = sqlsrv_query( $link, $sql );
while(($rows = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))){
    if($rows["StartTime"] == NULL){
        $StartTime = $rows["EndTime"] -> format("Y-m-d H:i:s");
    }
    else{
        $StartTime = $rows["StartTime"] -> format("Y-m-d H:i:s");
    }
    $data[] = [
        $rows["Destination"],
        $rows["Machine"],
        $rows["Dyelot"],
        $rows["ProductCode"],
        $rows["TargetGrams"],
        $rows["DispenseGrams"],
        abs($rows["DispenseError"]),
        $StartTime,
        $rows["ProductType"]
    ];
    $i++;
}
if($i < 30){
    $j = 30 - $i;
    $sql = "SELECT TOP ($j) Destination,Machine,Dyelot,ProductCode,TargetGrams,DispenseGrams,DispenseError,StartTime,ProductType,EndTime 
    FROM dbo.DispenserProduction 
    WHERE DispenseGrams > '0' 
    AND EndTime < '$today' 
    ORDER BY StartTime DESC";
    $stmt = sqlsrv_query( $link, $sql );
    while(($rows = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))){
        if($rows["StartTime"] -> format("Y-m-d H:i:s") == "NULL"){
            $StartTime = $rows["EndTime"] -> format("Y-m-d H:i:s");
        }
        else{
            $StartTime = $rows["StartTime"] -> format("Y-m-d H:i:s");
        }
        $data[] = [
            $rows["Destination"],
            $rows["Machine"],
            $rows["Dyelot"],
            $rows["ProductCode"],
            $rows["TargetGrams"],
            $rows["DispenseGrams"],
            abs($rows["DispenseError"]),
            $StartTime,
            $rows["ProductType"]
        ];
    }
}

//data process
$todayErr = 0;
foreach($data as $key => $element){

    // 異常統計 染料0.03%  助劑3% 粉體5% (其他預設3%)
    if(($data[$key])[7] > $today){
        if(($data[$key])[8] == 1){
            if(($data[$key])[6] > ($data[$key])[4] * 0.0003){
                $todayErr += 1;
                if(($data[$key])[6] > ($data[$key])[4] * 0.1){
                    ($data[$key])[9] = 2;
                }
                else{
                    ($data[$key])[9] = 1;
                }
            }
            else{
                ($data[$key])[9] = 0;
            }
        }
        else if(($data[$key])[8] == 2){
            if(($data[$key])[6] > ($data[$key])[4] * 0.03){
                $todayErr += 1;
                if(($data[$key])[6] > ($data[$key])[4] * 0.1){
                    ($data[$key])[9] = 2;
                }
                else{
                    ($data[$key])[9] = 1;
                }
            }
            else{
                ($data[$key])[9] = 0;
            }
        }
        else if(($data[$key])[8] == 3){
            if(($data[$key])[6] > ($data[$key])[4] * 0.05){
                $todayErr += 1;
                if(($data[$key])[6] > ($data[$key])[4] * 0.1){
                    ($data[$key])[9] = 2;
                }
                else{
                    ($data[$key])[9] = 1;
                }
            }
            else{
                ($data[$key])[9] = 0;
            }
        }
        else{
            if(($data[$key])[6] > ($data[$key])[4] * 0.03){
                $todayErr += 1;
                if(($data[$key])[6] > ($data[$key])[4] * 0.1){
                    ($data[$key])[9] = 2;
                }
                else{
                    ($data[$key])[9] = 1;
                }
            }
            else{
                ($data[$key])[9] = 0;
            }
        }
        //print_r(($data[$key]));
        //echo "<br><br>";
    }
}

//save processed data
Writecsv("DispenseErrorReport_data.csv",$data);
file_put_contents("DispenseErrorReport_data.json", json_encode($data));

//for local only-create csv
if(!file_exists("DispenseErrorReport_date.csv")){
    Writecsv("DispenseErrorReport_date.csv",[[date("Y-m-d H:i:s",strtotime($today) - 24*60*60)]]);
}

//check current time
$lastDate = Readcsv("DispenseErrorReport_date.csv");

if(($lastDate[0])[0] <= $today){
    //sql connect
    $yesterday = [date("Y-m-d H:i:s",strtotime($today) - 24*60*60)][0];
    $sql = "SELECT TargetGrams,DispenseError,ProductType,EndTime 
    FROM dbo.DispenserProduction 
    WHERE DispenseGrams > '0' 
    AND EndTime > '$yesterday'
    AND EndTime < '$today' 
    ORDER BY StartTime DESC";
    // var_dump($yesterday);
    // var_dump($today);
    $stmt = sqlsrv_query( $link, $sql );
    while(($rows = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))){
        $Ydata[] = [
            $rows["TargetGrams"],
            abs($rows["DispenseError"]),
            $rows["ProductType"],
            $rows["EndTime"] -> format("Y-m-d H:i:s")
        ];
    }

    //data process
    $yesterdayErr = array();
    $Ystamp = strtotime($yesterday);
    $Ychart[] = ["異常次數",""];
    for($k = 1; $k <= 24; $k++){
        if($k == 1){
            $Ytime2 = $yesterday;
        }
        $Ytime1 = $Ytime2;
        $Ytime2 = date("Y-m-d H:i:s",(strtotime($Ytime1) + 60*60));
        if (isset($Ydata))
        foreach($Ydata as $key => $element){
            if(!isset($yesterdayErr[$k - 1])){
                $yesterdayErr[$k - 1] = 0;
            }
            if(($Ydata[$key])[3] >= $Ytime1 && ($Ydata[$key])[3] <= $Ytime2){
                if(($Ydata[$key])[2] == 1){
                    if(($Ydata[$key])[1] > ($Ydata[$key])[0] * 0.002){
                        $yesterdayErr[$k - 1] += 1;
                    }
                }
                else{
                    if(($Ydata[$key])[1] > ($Ydata[$key])[0] * 0.03){
                        $yesterdayErr[$k - 1] += 1;
                    }
                }
            }


        }
        $Ychart[] = [$k,$yesterdayErr[$k - 1] ?? ""];

    }

    
    ($lastDate[0])[0] = $today;

    //save processed data
    Writecsv("DispenseErrorReport_date.csv",$lastDate);
    Writecsv("DispenseErrorReport_yesterday_chart.csv",$Ychart);
}

$time1 += microtime(true);
// echo "<h4>Took $time1 seconds for execution</h4>";


?>
<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

$today_date = date('Y-m-d');
//$today_date = '2020-10-24';

$today_start = date("Y-m-d", strtotime("-1 days")) . ' 00:00:00.000';
//$today_start = date("Y-m-d H:i:s", strtotime("-24 hours"));

$today_start_minus = strtotime($today_start); //今天開始


$today_end = date("Y-m-d", strtotime("-1 days")) . ' 23:59:59.999';
//$today_end = date("Y-m-d H:i:s");

$today_end_minus = strtotime($today_end);//今天結束


//echo json_encode($today_start);
//echo json_encode($today_end);

// var_dump($today_end);
// var_dump($today_start);
// $sql = "select * from DyeProduction where  EndTime < '$today_end' and EndTime > '$today_start' and CONVERT(FLOAT, TotalWeight) != '' and CONVERT(FLOAT, TotalWeight) > '0'";
$sql = "select * FROM (SELECT  CAST([重量] AS DECIMAL(18,2)) as 'TotalWeight'
,[機台代號] as 'Machine'
,DATEADD(HOUR, -7, CAST([完成時間] AS DATETIME2(7))) as 'EndTime' 
from [ERP_Production] where [製程]='染色')DyeProduction
 where  EndTime < '$today_end' and EndTime > '$today_start' and CONVERT(FLOAT, TotalWeight) != '' and CONVERT(FLOAT, TotalWeight) > '0'";

$result = sqlsrv_query($link, $sql, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

if ($result === false) {
    // 查询执行失败，打印错误信息
    die(print_r(sqlsrv_errors(), true));
}

$num = sqlsrv_num_rows($result);//生產缸數

$weight_all = 0;

$time = 0;

//抓今天有運作的資料
while ($row = sqlsrv_fetch_array($result)) {
    $weight = $row['TotalWeight'];
    $weight_all = $weight_all + $weight;//生產量
}

$sql_2 = "select * from DyeProduction where  EndTime < '$today_end' and EndTime > '$today_start' and OverRunTime > '0.04167'";

$result_2 = sqlsrv_query($link, $sql_2, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
$num_2 = sqlsrv_num_rows($result_2);//延遲缸數


// require_once('./common/database_central.php');
$sql_success_rate_of_dyeing_T = "SELECT
COUNT(CASE WHEN [一次成功判斷] = '一次成功' THEN 1 END) as '一次成功數'
,COUNT(*) as '總數'
,ROUND(COUNT(CASE WHEN [一次成功判斷] = '一次成功' THEN 1 END) * 100.0 / NULLIF(COUNT([一次成功判斷]), 0),2) as '一次成功率'

FROM
(
SELECT
[Dyelot]
,case when MAX([執行次數]) <= 1 then '一次成功'
when MAX([執行次數]) > 1 then '未一次成功'
end as '一次成功判斷'
FROM
(
SELECT
[Dyelot]
,[程序]
,case WHEN SUM(CASE WHEN [Program] = '600' THEN 1 ELSE 0 END) > 0 
    THEN COUNT([程序]) - 1 
else COUNT([程序]) 
end as '執行次數'
FROM
(
SELECT  [ID]
      ,[Dyelot]
      ,[ReDye]
      ,[Program]
	  ,CASE WHEN [Program] IN (101,102,103,7101,7102,7103,7105,7106,6101,6102,6103,6105,6106) THEN  '前處理'
         WHEN [Program] IN (104,7104,6104) THEN '除氧'
         WHEN [Program] IN (201,202,203,204,205,7201,7202,7203,7204,7205,122,6201,6202,6203,6204,6205) THEN  '染T'
         WHEN [Program] IN (301,302,303,304,305,7301,7302,7303,7304,7305,7306,6301,6302,6303,6304,6305,6306) THEN 'RC'
         WHEN [Program] IN (401,402,403,404,405,406,407,408,409,7401,7402,7403,7404,7405,7406,7407,7408,7409,7410,7411,7412,7413,6401,6402,6403,6404,6405,6406,6407,6408,6409,6410,6411,6412,6413,600,602) THEN '染C'
         WHEN [Program] IN (500,501,502,503,7500,7501,7502,7503,6500,6501,6502,6503,605) THEN  '後處理'
         WHEN [Program] IN (603,601) THEN  '剝色'
         WHEN [Program] IN (105) THEN  '煮淺'
         WHEN [Program] IN (7504) THEN  '皂洗加色'
       else null
	   END AS '程序'
	  ,[Name]
      ,[Steps]
      ,[StepsNoBpCrc]
      ,[ComputedTime]
      ,[MaxTemperature]
      ,[Machine]
      ,[StartTime]
  FROM [AllSevenBatchDyeingCentral].[dbo].[ProgramsUsed]
  where StartTime < '$today_end' and StartTime > '$today_start'
  and Dyelot not in (SELECT Dyelot
    FROM [DyeProduction] where ColorNo is null
 )
  )base
  where [程序] is not null
    and [程序] = '染T'
  group by [Dyelot],[程序]
  )base2
  group by [Dyelot]
  )base3

	   ";

$result = sqlsrv_query($link, $sql_success_rate_of_dyeing_T, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

if ($result === false) {
    // 查询执行失败，打印错误信息
    die(print_r(sqlsrv_errors(), true));
}

//抓今天有運作的資料
while ($row = sqlsrv_fetch_array($result)) {
    $success_rate_of_dyeing_T = $row['一次成功率'];

}


$sql_success_rate_of_dyeing_C = "SELECT
COUNT(CASE WHEN [一次成功判斷] = '一次成功' THEN 1 END) as '一次成功數'
,COUNT(*) as '總數'
,ROUND(COUNT(CASE WHEN [一次成功判斷] = '一次成功' THEN 1 END) * 100.0 / NULLIF(COUNT([一次成功判斷]), 0),2) as '一次成功率'

FROM
(
SELECT
[Dyelot]
,case when MAX([執行次數]) <= 1 then '一次成功'
when MAX([執行次數]) > 1 then '未一次成功'
end as '一次成功判斷'
FROM
(
SELECT
[Dyelot]
,[程序]
,case WHEN SUM(CASE WHEN [Program] = '600' THEN 1 ELSE 0 END) > 0 
    THEN COUNT([程序]) - 1 
else COUNT([程序]) 
end as '執行次數'
FROM
(
SELECT  [ID]
      ,[Dyelot]
      ,[ReDye]
      ,[Program]
	  ,CASE WHEN [Program] IN (101,102,103,7101,7102,7103,7105,7106,6101,6102,6103,6105,6106) THEN  '前處理'
         WHEN [Program] IN (104,7104,6104) THEN '除氧'
         WHEN [Program] IN (201,202,203,204,205,7201,7202,7203,7204,7205,122,6201,6202,6203,6204,6205) THEN  '染T'
         WHEN [Program] IN (301,302,303,304,305,7301,7302,7303,7304,7305,7306,6301,6302,6303,6304,6305,6306) THEN 'RC'
         WHEN [Program] IN (401,402,403,404,405,406,407,408,409,7401,7402,7403,7404,7405,7406,7407,7408,7409,7410,7411,7412,7413,6401,6402,6403,6404,6405,6406,6407,6408,6409,6410,6411,6412,6413,600,602) THEN '染C'
         WHEN [Program] IN (500,501,502,503,7500,7501,7502,7503,6500,6501,6502,6503,605) THEN  '後處理'
         WHEN [Program] IN (603,601) THEN  '剝色'
         WHEN [Program] IN (105) THEN  '煮淺'
         WHEN [Program] IN (7504) THEN  '皂洗加色'
       else null
	   END AS '程序'
	  ,[Name]
      ,[Steps]
      ,[StepsNoBpCrc]
      ,[ComputedTime]
      ,[MaxTemperature]
      ,[Machine]
      ,[StartTime]
  FROM [AllSevenBatchDyeingCentral].[dbo].[ProgramsUsed]
  where StartTime < '$today_end' and StartTime > '$today_start'
    and Dyelot not in (SELECT Dyelot
    FROM [DyeProduction] where ColorNo is null
 )
  )base
  where [程序] is not null
  and [程序] = '染C'
  group by [Dyelot],[程序]
  )base2
  group by [Dyelot]
  )base3

	   ";

$result = sqlsrv_query($link, $sql_success_rate_of_dyeing_C, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));

if ($result === false) {
    // 查询执行失败，打印错误信息
    die(print_r(sqlsrv_errors(), true));
}

//抓今天有運作的資料
while ($row = sqlsrv_fetch_array($result)) {
    $success_rate_of_dyeing_C = $row['一次成功率'];

}

$all_data_array[] = array(
    "chart_name" => 'data4',
    "number1" => $weight_all,
    "number2" => $num,
    "delay" => $num_2,
    "success_rate_of_dyeing_T" => $success_rate_of_dyeing_T ?? 0,
    "success_rate_of_dyeing_C" => $success_rate_of_dyeing_C ?? 0
);

echo json_encode($all_data_array);
?>
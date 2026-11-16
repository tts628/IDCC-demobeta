<?php 
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");
$lang = new Language();

if($_POST["lang"]=='en-US'){
     $name["Cumulative_flow_water"]='Cumulative flow (water)';
     $name["Cumulative_flow_medicine"]='Cumulative flow (medicine)';
     $name["Cumulative_Vapor"]='Cumulative Vapor';
     $name["Total_Power"]='Total Power';
     $name["Temperature"]="Temperature";
     $name["Tension"]='Tension';
}
else{
    $name["Cumulative_flow_water"]='累積流量(水)';
    $name["Cumulative_flow_medicine"]='累積流量(藥)';
    $name["Cumulative_Vapor"]='累積蒸氣量 ';
    $name["Total_Power"]='總功率';
    $name["Temperature"]="溫度";
    $name["Tension"]='張力';
}

if ($_POST["selectedOption_machine_number"] == "WH1縮練機") {
    $machineid = 'WH1';
} else {
    $machineid = "('XXXX')";

}

?>

<div class="container-sm">

<div class="row">
    <div class="col-sm-3">
        <h3><?php echo $name["Cumulative_flow_water"]?></h3>
        <div class="flow" id="water">13003</div>
    </div>
    <div class="col-sm-3">
        <h3><?php echo $name["Cumulative_flow_medicine"]?></h3>
        <div class="flow" id="medicine">61883</div>
    </div>
    <div class="col-sm-3">
        <h3><?php echo $name["Cumulative_Vapor"]?></h3>
        <div class="flow" id="steam">15224</div>
    </div>
    <div class="col-sm-3">
        <h3><?php echo $name["Total_Power"]?></h3>
        <div class="flow" id="total_power">996.0</div>
    </div>
</div><br><br>
<div class="row">
    <div class="col-sm-2 ">
        <h3><?php echo $name["Temperature"]?>1</h3>
        <div class="temp" id="temp1">701</div>
    </div>
    <div class="col-sm-2">
        <h3><?php echo $name["Temperature"]?>2</h3>
        <div class="temp" id="temp2">612</div>
    </div>
    <div class="col-sm-2">
        <h3><?php echo $name["Temperature"]?>3</h3>
        <div class="temp" id="temp3">785</div>
    </div>
    <div class="col-sm-2">
        <h3><?php echo $name["Temperature"]?>4</h3>
        <div class="temp" id="temp4">797</div>
    </div>
    <div class="col-sm-2">
        <h3><?php echo $name["Temperature"]?>5</h3>
        <div class="temp" id="temp5">933</div>
    </div>
    <div class="col-sm-2">
        <h3><?php echo $name["Temperature"]?>6</h3>
        <div class="temp" id="temp6">841</div>
    </div>
</div><br><br>
<div class="row">
    <table>
        <tr class="title">
            <th><?php echo $name["Tension"]?> 1</th>
            <th><?php echo $name["Tension"]?> 2</th>
            <th><?php echo $name["Tension"]?> 3</th>
            <th><?php echo $name["Tension"]?> 4</th>
            <th><?php echo $name["Tension"]?> 5</th>
        </tr>
        <tr>
            <td id="tension1">0</td>
            <td id="tension2">2</td>
            <td id="tension3">0</td>
            <td id="tension4">0</td>
            <td id="tension5">1</td>
        </tr>

    </table>
</div><br><br>
<div class="row">
    <table>
        <tr class="title">
            <th><?php echo $name["Tension"]?> 6</th>
            <th><?php echo $name["Tension"]?> 7</th>
            <th><?php echo $name["Tension"]?> 8</th>
            <th><?php echo $name["Tension"]?> 9</th>
            <th><?php echo $name["Tension"]?> 10</th>
        </tr>
        <tr>
            <td id="tension6">0</td>
            <td id="tension7">0</td>
            <td id="tension8">0</td>
            <td id="tension9">0</td>
            <td id="tension10">0</td>
        </tr>

    </table>
</div><br><br>
<div class="row">
    <table>
        <tr class="title">
            <th><?php echo $name["Tension"]?> 11</th>
            <th><?php echo $name["Tension"]?> 12</th>
            <th><?php echo $name["Tension"]?> 13</th>
            <th><?php echo $name["Tension"]?> 14</th>
            <th><?php echo $name["Tension"]?> 15</th>
        </tr>
        <tr>
            <td id="tension11">0</td>
            <td id="tension12">0</td>
            <td id="tension13">0</td>
            <td id="tension14">0</td>
            <td id="tension15">1</td>
        </tr>

    </table>
</div><br><br>
<div class="row">
    <table>
        <tr class="title">
            <th><?php echo $name["Tension"]?> 16</th>
            <th><?php echo $name["Tension"]?> 17</th>
            <th><?php echo $name["Tension"]?> 18</th>
            <th><?php echo $name["Tension"]?> 19</th>
            <th><?php echo $name["Tension"]?> 20</th>
        </tr>
        <tr>
            <td id="tension16">0</td>
            <td id="tension17">0</td>
            <td id="tension18">0</td>
            <td id="tension19">0</td>
            <td id="tension20">1</td>
        </tr>
    </table>
</div>

</div>
</div>

<script>


<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

$sql = "SELECT TOP (1) [時間]
,[累計流量(水)]
,[累計流量(葯)]
,[累計蒸氣量]
,[總有效功率]
FROM [allseven_power]
order by [時間] desc
";
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
  die(print_r(sqlsrv_errors(), true));
}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $water = $row['累計流量(水)'];
    $medicine = $row['累計流量(葯)'];
    $steam = $row['累計蒸氣量'];
    $total_power = $row['總有效功率'];

}



$sql = "SELECT TOP 1 [時間]
,[#A tmp(0.1 degree)]
,[#B tmp(0.1 degree)]
,[#1 tmp(0.1 degree)]
,[#2 tmp(0.1 degree)]
,[#3 tmp(0.1 degree)]
,[#4 tmp(0.1 degree)]
FROM [allseven_temp]
order by [時間] desc
";
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
  die(print_r(sqlsrv_errors(), true));
}

$temp = array();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $temp[1] = $row['#A tmp(0.1 degree)'];
    $temp[2] = $row['#B tmp(0.1 degree)'];
    $temp[3] = $row['#1 tmp(0.1 degree)'];
    $temp[4] = $row['#2 tmp(0.1 degree)'];
    $temp[5] = $row['#3 tmp(0.1 degree)'];
    $temp[6] = $row['#4 tmp(0.1 degree)'];
}




$sql = "SELECT TOP (1) [時間]
      ,[M2張力%]
      ,[M3張力%]
      ,[M4張力%]
      ,[M5張力%]
      ,[M6張力%]
      ,[M7張力%]
      ,[M8張力%]
      ,[M10張力%]
      ,[M11張力%]
      ,[M12張力%]
      ,[M13張力%]
      ,[M14張力%]
      ,[M15張力%]
      ,[M17張力%]
      ,[M18張力%]
      ,[M19張力%]
      ,[M20張力%]

  FROM [allseven_tension]
      order by [時間] desc
";
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
  die(print_r(sqlsrv_errors(), true));
}
$tension = array();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    // $tension[2] = $row['M2張力%'];
    // $tension[3] = $row['M3張力%'];
    // $tension[4] = $row['M4張力%'];
    for ($i = 2; $i <= 20; $i++) {
        $columnName = 'M' . $i . '張力%'; // 构建列名
        $tension[$i] = $row[$columnName]; // 从 $row 数组中获取值并存储到 $tension 数组中
    }

}
sqlsrv_close($conn);

?>
// console.log("<?php echo $tension[0] ;?>")
var water = document.getElementById("water");
var medicine = document.getElementById("medicine");
var steam = document.getElementById("steam");
var total_power = document.getElementById("total_power");
var temp=[];


for (var i = 1; i <= 6; i++) {
    var tempId = "temp" + i;  // 创建元素的 ID
    temp[i] = document.getElementById(tempId);
}
var tension=[];

for (var i = 1; i <= 20; i++) {
    var tensionid = "tension" + i;  // 创建元素的 ID
    tension[i] = document.getElementById(tensionid);
}

water.innerHTML = <?php echo $water;?>;
medicine.innerHTML = <?php echo $medicine;?>;
steam.innerHTML = <?php echo $steam;?>;
total_power.innerHTML = <?php echo $total_power;?>;

<?php
for ($i = 1; $i <= 6; $i++) {
    echo "temp[$i].innerHTML = '{$temp[$i]}';";
}


for ($i = 1; $i <= 20; $i++) {
    echo "tension[$i].innerHTML = '{$tension[$i]}';";
    // var_dump($tension[$i]);
}
?>


</script>
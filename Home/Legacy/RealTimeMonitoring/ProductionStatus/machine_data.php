<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

date_default_timezone_set("Asia/Taipei");
$name = $_POST['machine_id'];
$machines = $_POST['machines'];
//echo $name;
//echo $machines;
$show_data = array();
if($machines=='DyeMachines'){
    $sql777 = "SELECT * FROM DyeMachines WHERE Name=? ORDER BY DyelotStartTime DESC";
    $params = array($name);
    $result777 = sqlsrv_query($link, $sql777,$params);
    while ($all_data = sqlsrv_fetch_array($result777)) {
        $program = $all_data['Program'];
        $faname = $all_data['FabricName'];
        $totalw = $all_data['TotalWeight'];
        $dyelot = $all_data['Dyelot'];
        $dyelot1 = $all_data['Dyelot1'];
        $dyelot2 = $all_data['Dyelot2'];
        $state = $all_data['State'];
        $stime = $all_data['DyelotStartTime'];
        $etime = $all_data['DyelotEndTime'];
        $endtime = $etime->format('Y-m-d H:i:s');
        $newtime = $stime->format('Y-m-d H:i:s');
        $speed1 = $all_data['MainPumpSpeed'];
        $speed2 = $all_data['ReelSpeed'];
        $speed3 = $all_data['CycleTime'];
        $speed4 = $all_data['Progress'];
        $color1 = $all_data['Color'];
        $endtime1 = $etime->format('U');
        $nowtime1 = date("Y-m-d H:i:s");
        $nowtime = strtotime("$nowtime1,now");
        $lesstime1 = $endtime1-$nowtime;
        if($dyelot==null){
            $dyelot='無工單';
        }
        else{
            $dyelot=$dyelot;
        }
        if($dyelot1==null){
            $dyelot1='無工單';
        }
        else{
            $dyelot1=$dyelot1;
        }
        if($dyelot2==null){
            $dyelot2='無工單';
        }
        else{
            $dyelot2=$dyelot2;
        }
        if($lesstime1>'0'){
            if ($lesstime1 > 3600){
                $hours = intval($lesstime1/3600);
                $minutes = $lesstime1 % 3600;
                $time = $hours.":".gmstrftime('%M:%S', $minutes);
            }else{
                $time = gmstrftime('%H:%M:%S', $lesstime1);
            }
        }
        else{
            $time='0';
        }
        if($all_data['Color']!= Null){
            $color1=$all_data['Color'];
            $rgb=[];
            $b = ($color1 & 0x0000ff);
            $g = ($color1 & 0x00ff00) >> 8;
            $r = ($color1 & 0xff0000) >> 16;
            $rgb[0]=$r;
            $rgb[1]=$g;
            $rgb[2]=$b;
            $r1=strval($r);
            $g1=strval($g);
            $b1=strval($b);
            $r2 = dechex($r);
            $g2 = dechex($g);
            $b2 = dechex($b);
            if(strlen($r2)==1){
                $r3='0'.$r2;
            }
            else{
                $r3=$r2;
            }
            if(strlen($g2)==1){
                $g3='0'.$g2;
            }
            else{
                $g3=$g2;
            }
            if(strlen($b2)==1){
                $b3='0'.$b2;
            }
            else{
                $b3=$b2;
            }
            $color=$b3.$g3.$r3;
        }
        elseif($all_data['Color']== '0'){
            $color='000000';
        }
        else{
            $color='005562';
        }
        if($all_data['Color1']!=Null){
            $color11 = $all_data['Color1'];
            $rgb1=[];
            $b11 = ($color11 & 0x0000ff);
            $g11 = ($color11 & 0x00ff00) >> 8;
            $r11 = ($color11 & 0xff0000) >> 16;
            $rgb[0]=$r11;
            $rgb[1]=$g11;
            $rgb[2]=$b11;
            $r10=strval($r11);
            $g10=strval($g11);
            $b10=strval($b11);
            $r12 = dechex($r10);
            $g12 = dechex($g10);
            $b12 = dechex($b10);
            if(strlen($r12)==1){
                $r13='0'.$r12;
            }
            else{
                $r13=$r12;
            }
            if(strlen($g12)==1){
                $g13='0'.$g12;
            }
            else{
                $g13=$g12;
            }
            if(strlen($b12)==1){
                $b13='0'.$b12;
            }
            else{
                $b13=$b12;
            }
            $color13=$b13.$g13.$r13;
        }
        elseif($all_data['Color1']== '0'){
            $color='000000';
        }
        else{
            $color13='005562';
        }
        if($all_data['Color2']!=Null){
            $color111 = $all_data['Color2'];
            $rgb2=[];
            $b111 = ($color111 & 0x0000ff);
            $g111 = ($color111 & 0x00ff00) >> 8;
            $r111 = ($color111 & 0xff0000) >> 16;
            $rgb[0]=$r111;
            $rgb[1]=$g111;
            $rgb[2]=$b111;
            $r112=strval($r111);
            $g112=strval($g111);
            $b112=strval($b111);
            $r122 = dechex($r112);
            $g122 = dechex($g112);
            $b122 = dechex($b112);
            if(strlen($r122)==1){
                $r113='0'.$r122;
            }
            else{
                $r113=$r122;
            }
            if(strlen($g122)==1){
                $g113='0'.$g122;
            }
            else{
                $g113=$g122;
            }
            if(strlen($b122)==1){
                $b113='0'.$b122;
            }
            else{
                $b113=$b122;
            }
            $color113=$b113.$g113.$r113;
        }
        elseif($all_data['Color2']== '0'){
            $color='000000';
        }
        else{
            $color113='005562';
        }
    }
    $sql888 = "SELECT TOP 1 * FROM DyeStepVisits WHERE Machine=? ORDER BY StartTime DESC";
    $params1 = array($name);
    $result888 = sqlsrv_query($link, $sql888,$params1);
    while ($data = sqlsrv_fetch_array($result888)){
        $t10 = $data['StartTemperature'];
        $a='.';
        $t= $t10/10;
    }
    $line_array = array();
    $t1=0;
    $time1=0;
    $sql999 = "SELECT TOP 1440 * FROM DyeStepVisits WHERE Machine=? ORDER BY StartTime DESC";
    $params2 = array($name);
    $result999 = sqlsrv_query($link, $sql999,$params2);
    while ($data1 = sqlsrv_fetch_array($result999)){
        $dye=$data1['Dyelot'];
        $note= $data1['Note'];
        $t5 = $data1['StartTemperature']/10;
        $t1 = sprintf('%.1f', (float)$t5);
        $time1 = $data1['StartTime']->format('U');
        $time2=$time1.'000';
        $line_array[] = array("x"=>(int)$time2,"y"=>floatval($t5),"z"=>$note,"dye"=>$dye);
    }
    $day_array[]=array("type" => 'area', "name" => '機台溫度', "data" => array_reverse($line_array),"color" => '#FF8A21');//,"note"=>array_reverse($note)
    $note_array[]=array("name" => $note);
    $show_data[] = array(
        'name' => $name,
        'program' => $program,
        'faname' => $faname,
        'totalw' => $totalw,
        'dyelot' => $dyelot,
        'color' => $color,
        'newtime' => $newtime,
        'endtime' => $endtime,
        'dyelot1' => $dyelot1,
        'dyelot2' => $dyelot2,
        'color1' => $color13,
        'color2' => $color113,
        'speed1' => $speed1,
        'speed2' => $speed2,
        'speed3' => $speed3,
        'speed4' => $speed4,
        'state' => $state,
        't' => $t,
        'ok_data' => $day_array,
        'lesstime' =>$time,
        'note'=>$note_array,
    );
}
if($machines=='FinishMachines'){
    $sql777 = "SELECT * FROM FinishMachines WHERE Name=? ORDER BY JobStartTime DESC";
    $params = array($name);
    $result777 = sqlsrv_query($link, $sql777,$params);
    while ($all_data = sqlsrv_fetch_array($result777)) {
        $program = $all_data['Program'];
        $faname = $all_data['FabricName'];
        $totall = $all_data['TotalLength'];
        $state = $all_data['State'];
        $stime = $all_data['JobStartTime'];
        $etime = $all_data['JobEndTime'];
        $job = trim($all_data['Job']);
        $job1 = $all_data['Job1'];
        $job2 = $all_data['Job2'];
        $endtime = $etime->format('Y-m-d H:i:s');
        $newtime = $stime->format('Y-m-d H:i:s');
        $speed1 = $all_data['MainSpeed'];
        $speed2 = $all_data['Width'];
        $endtime1 = $etime->format('U');
        $nowtime1 = date("Y-m-d H:i:s");
        $nowtime = strtotime("$nowtime1,now");
        $lesstime1 = $endtime1-$nowtime;
        if($lesstime1>'0'){
            if ($lesstime1 > 3600){
                $hours = intval($lesstime1/3600);
                $minutes = $lesstime1 % 3600;
                $time = $hours.":".gmstrftime('%M:%S', $minutes);
            }else{
                $time = gmstrftime('%H:%M:%S', $lesstime1);
            }
        }
        else{
            $time='0';
        }
    }
    $lobt1=0;
    $time3=0;
    $sql888 = "SELECT TOP 80 * FROM FinishStepVisits WHERE Machine=? AND Job='$job' ORDER BY StartTime DESC";
    $params2 = array($name);
    $result888 = sqlsrv_query($link, $sql888,$params2);
    while ($data5 = sqlsrv_fetch_array($result888)) {
        $job11=$data5['Job'];
        $note1= $data5['Note'];
        $t11=$data5['StartTemperature1'];
        $t12=$data5['StartTemperature2'];
        $t13=$data5['StartTemperature3'];
        $t14=$data5['StartTemperature4'];
        $t15=$data5['StartTemperature5'];
        $t16=$data5['StartTemperature6'];
        $time4 = $data5['StartTime']->format('U');
        $time3=$time4.'000';
        $lobt1_array[]=array("x" =>(int)$time3,"y" =>floatval($t11),"job"=>$job11,"note1"=>$note1);
        $lobt2_array[]=array("x" =>(int)$time3,"y" =>floatval($t12),"job"=>$job11,"note1"=>$note1);
        $lobt3_array[]=array("x" =>(int)$time3,"y" =>floatval($t13),"job"=>$job11,"note1"=>$note1);
        $lobt4_array[]=array("x" =>(int)$time3,"y" =>floatval($t14),"job"=>$job11,"note1"=>$note1);
        $lobt5_array[]=array("x" =>(int)$time3,"y" =>floatval($t15),"job"=>$job11,"note1"=>$note1);
        $lobt6_array[]=array("x" =>(int)$time3,"y" =>floatval($t16),"job"=>$job11,"note1"=>$note1);
    }
    $lobt1_array1[]=array("name" => 'Temperature1', "data" => array_reverse($lobt1_array));
    $lobt2_array2[]=array("name" => 'Temperature2', "data" => array_reverse($lobt2_array));
    $lobt3_array3[]=array("name" => 'Temperature3', "data" => array_reverse($lobt3_array));
    $lobt4_array4[]=array("name" => 'Temperature4', "data" => array_reverse($lobt4_array));
    $lobt5_array5[]=array("name" => 'Temperature5', "data" => array_reverse($lobt5_array));
    $lobt6_array6[]=array("name" => 'Temperature6', "data" => array_reverse($lobt6_array));
    $sql999 = "SELECT * FROM FinishProduction WHERE Machine=? AND Job='$job' ORDER BY StartTime DESC";
    $params3 = array($name);
    $result999 = sqlsrv_query($link, $sql999,$params3);

    while ($data7 = sqlsrv_fetch_array($result999)) {
        $speed3 = $data7['OverFeed'];
    }
    $show_data[] = array(
        'name' => $name,
        'program' => $program,
        'faname' => $faname,
        'totall' => $totall,
        'job' => $job,
        'job1' => $job1,
        'job2' => $job2,
        'newtime' => $newtime,
        'endtime' => $endtime,
        'speed1' => $speed1,
        'speed2' => $speed2,
        'speed3' => $speed3,
        'state' => $state,
        'lesstime' =>$time,
        'lobt1' =>$lobt1_array1,
        'lobt2' =>$lobt2_array2,
        'lobt3' =>$lobt3_array3,
        'lobt4' =>$lobt4_array4,
        'lobt5' =>$lobt5_array5,
        'lobt6' =>$lobt6_array6,
    );
}
if($machines=='DispenserMachines') {
    if($name=='LA-201'){
        $img='./img/LA-201.png';
    }
    elseif($name=='LA-252'){
        $img='./img/LA-252.png';
    }
    elseif($name=='LA-302F'){
        $img='./img/LA-302F.png';
    }
    elseif($name=='LA-302FECO'){
        $img='./img/LA-302FECO.png';
    }
    $sql777 = "SELECT * FROM DispenserMachines WHERE Name=? ORDER BY DyelotStartTime DESC";
    $params = array($name);
    $result777 = sqlsrv_query($link, $sql777,$params);
    while ($all_data = sqlsrv_fetch_array($result777)) {
        $step = $all_data['Step'];
        $dyelot = $all_data['Dyelot'];
        $state = $all_data['State'];
        $stime = $all_data['DyelotStartTime'];
        $etime = $all_data['DyelotEndTime'];
    }
    $nowtime1 = date("Y-m-d H:i:s").'.000';
    $nowtime = strtotime("$nowtime1,now");
//    $product = 0;
//    $type =0;
//    $tgrams = 0;
//    $dgrams = 0;
    $sql888 = "SELECT TOP 6 * FROM DispenserProduction WHERE Dyelot='$dyelot' ORDER BY StartTime DESC";//AND StepNumber ='$step'
    $result888 = sqlsrv_query($link, $sql888);
    while ($data3 = sqlsrv_fetch_array($result888)) {
//        array_push($result_array, $data3);
//        $abc='123';
        $product[] = $data3['ProductCode'];
//        $product_array[]=array($product);
        $type[] = $data3['StepNumber'];
        $tgrams[] = $data3['TargetGrams'];
        $dgrams[] = $data3['DispenseGrams'];
//        $product_array[]=array("name"=> "data"=>$product);
    }
    $sql999 = "SELECT TOP 15 * FROM DispenserProduction WHERE Dispenser LIKE ? AND (DispenseSeconds > 0) ORDER BY StartTime DESC";
    $params1 = array("%${name}%");
    $result999 = sqlsrv_query($link, $sql999,$params1);
    while ($data4 = sqlsrv_fetch_array($result999)) {
//        array_push($result_array, $data3);
        $product1[] = $data4['ProductCode'];
        $pname[] = $data4['ProductName'];
        $dest[] = $data4['Destination'];
        $tgrams1[] = $data4['TargetGrams'];
        $dgrams1[] = $data4['DispenseGrams'];
        $error[] = number_format(strval(($data4['TargetGrams']-$data4['DispenseGrams'])/100), 2);
        $starttime[] = date_format($data4['StartTime'],"Y-m-d H:i:s");
        $endtime2[] = date_format($data4['EndTime'],"Y-m-d H:i:s");
        $transfertime[] = $data4['TransferTime'];
        $dsec[] = $data4['DispenseSeconds']/60;

//        $starttime1[]=$starttime;
//        $endtime3[]=$endtime2;
    }

    for($i=0;$i<24;$i++){
        $hour = 0;
        $sql00 = "SELECT TOP 1 * FROM DispenserProduction WHERE (DispenseSeconds > 0) ORDER BY StartTime DESC";
        $result00 = sqlsrv_query($link, $sql00);
        while ($data8 = sqlsrv_fetch_array($result00)) {
            $day1=$data8['StartTime']->format('U');
            $day=date("Y-m-d",$day1);
        }
        if($i<10){
            $hour1=$day.' 0'.$i.':00:00.000';
            $hour2=$day.' 0'.$i.':59:59.999';
        }else{
            $hour1=$day.' '.$i.':00:00.000';
            $hour2=$day.' '.$i.':59:59.999';
        }
        $sql0="select * from DispenserProduction WHERE Dispenser LIKE ? AND (DispenseSeconds > 0) AND StartTime>='$hour1' AND StartTime<='$hour2'";
        $params5 = array("%${name}%");
        $result0=sqlsrv_query($link,$sql0,$params5, array(), array("Scrollable" => 'static'))or die("sql error".sqlsrv_errors());
        $data7=sqlsrv_num_rows($result0);
        $hour += $data7;
        $hour3=date('H',strtotime('+1 hour', strtotime($hour1)));
        $hour_array[]=array("name"=>$hour3,"y"=>$hour);
    }
//    $sql000 = "SELECT  TOP (100) * FROM DispenserProduction WHERE  Dispenser LIKE '%$name%' AND (DispenseSeconds > 0) ORDER BY StartTime DESC";
//    $result000 = sqlsrv_query($link, $sql000);
//    while ($data6 = sqlsrv_fetch_array($result000)) {
//        $ds[]=$data6['DispenseSeconds']/60;
//    }
//    $ds_array[]=array("name" => 'DispenseSeconds', "data" => $ds);
    $show_data[] = array(
        'name' => $name,
        'dyelot' => $dyelot,
        'dyelot1' => $dyelot1,
        'dyelot2' => $dyelot2,
        'step' => $step,
        'state' => $state,
        'img' => $img,
        'product' => $product,
        'tgrams' =>$tgrams,
        'dgrams' =>$dgrams,
        'type' =>$type,
        'product1' =>$product1,
        'pname' =>$pname,
        'dest' =>$dest,
        'tgrams1' =>$tgrams1,
        'dgrams1' =>$dgrams1,
        'error' =>$error,
        'starttime' =>$starttime,
        'endtime2' =>$endtime2,
        'transfertime' =>$transfertime,
        'ds' =>$hour_array,
        'dsec' =>$dsec,
//        'abc'=>$product_array,
    );
}

echo json_encode($show_data);
?>

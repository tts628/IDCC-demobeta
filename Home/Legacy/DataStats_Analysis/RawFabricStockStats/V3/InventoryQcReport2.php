<!DOCTYPE html>
<?php
function Writecsv($filename, $data)
{
    //Write to local csv
    $f = fopen($filename, 'w');
    if ($f === false) {
        die('Error writing the file ' . $filename);
    }
    foreach ($data as $row) {
        fputcsv($f, $row);
    }
    fclose($f);
}
function Readcsv($filename)
{
    //fetching data from local csv
    $f = fopen($filename, 'r');
    if ($f === false) {
        die('Cannot read the file ' . $filename);
    }
    while (($row = fgetcsv($f)) !== false) {
        $readData[] = $row;
    }
    fclose($f);
    if (!isset($readData)) {
        $readData = [[0]];
    }
    return $readData;
}
?>
<html>

<head>
    <style>
        html,
        body {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
        }
    </style>
    <link href="/common/IDCC_plugin/bootstrap-5.0.0-dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="/common/IDCC_plugin/jquery/jquery.min.js"></script>
    <script src="/common/IDCC_plugin/@popperjs/core/dist/umd/popper.min.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/highcharts.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/data.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/accessibility.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/no-data-to-display.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body style="background-color:#323232;" class="mx-auto container-fluid text-white text-center">
    <?php
    //Part 1
    $dir1 = __DIR__ . "/../InventoryData";
    $flag1 = 0;
    $flag01 = 0;
    $i = 0;
    $k = 0;
    $p = 0;
    $q = 0;
    $scanned_directory = array_diff(scandir($dir1), array('..', '.'));
    foreach ($scanned_directory as $element) {
        if (stripos($element, ".csv") !== false && $element != "開缸報告.csv") {
            $csv1List[] = $element;
            if (stripos($element, "01-") !== false) {
                $flag01++;
            }
            if (stripos($element, "12-") !== false && $flag01 > 0) {
                $LeapKey[] = $i;
            }
            $flag1 = 1;
            $i++;
        }
    }
    if (isset($csv1List[1])) {
        if (isset($LeapKey)) {
            foreach ($LeapKey as $key1) {
                unlink($dir1 . "/" . $csv1List[$key1]);
            }
            foreach ($csv1List as $key2 => $element) {
                if ($flag01 > 1) {
                    unlink($dir1 . "/" . $csv1List[$key2]);
                    $flag01--;
                }
            }
        } else {
            foreach ($csv1List as $key => $element) {
                if ($key > 0) {
                    unlink($dir1 . "/" . $csv1List[$key - 1]);
                }
            }
        }
        $scanned_directory = array_diff(scandir($dir1), array('..', '.'));
    }
    $flag = 0;
    $Tflag = 0;
    $ColData1[] = ["倉", "數量"];
    $ColData2[] = ["倉", "數量"];
    $ColData3[] = ["倉", "數量"];
    $ColData4[] = ["倉", "數量"];
    if ($flag1 == 1) {
        $csv1 = Readcsv(__DIR__ . "/../InventoryData/" . $scanned_directory[2]);
        foreach ($csv1 as $row) {
            foreach ($row as $key => $element) {
                if ($flag == 1) {
                    $ColData1[] = [$tmp, (float) (str_replace(array(','), '', $element))];
                    $flag = 0;
                }
                if ($flag == 2) {
                    $ColData2[] = [$tmp, (float) (str_replace(array(','), '', $element))];
                    $flag = 0;
                }
                if ($flag == 3) {
                    $ColData3[] = [$tmp, (float) (str_replace(array(','), '', $element))];
                    $flag = 0;
                }
                if ($flag == 4) {
                    $ColData4[] = [$tmp, (float) (str_replace(array(','), '', $element))];
                    $flag = 0;
                }
                if ($Tflag == 2) {
                    $ColTotal2 = "$tmp($element)";
                    $Tflag = 0;
                }
                if ($Tflag == 3) {
                    $ColTotal3 = "$tmp($element)";
                    $Tflag = 0;
                }
                if ($element == "DX102 染料倉KG") {
                    $flag = 1;
                    $tmp = $element;
                }
                if ($element == "DX103 助劑倉KG") {
                    $flag = 1;
                    $tmp = $element;
                }
                if ($element == "DX301 線邊染料助劑倉KG") {
                    $flag = 1;
                    $tmp = $element;
                }
                if ($element == "D1106 委外紗倉") {
                    $flag = 1;
                    $tmp = $element;
                }
                if ($element == "D1102 自製胚倉KG") {
                    $flag = 2;
                    $tmp = $element;
                }
                if ($element == "D1103 委外胚倉KG") {
                    $flag = 2;
                    $tmp = $element;
                }
                if ($element == "D1108 進口胚倉KG") {
                    $flag = 2;
                    $tmp = $element;
                }
                if ($element == "DX101 客供胚倉KG") {
                    $flag = 2;
                    $tmp = $element;
                }
                if ($element == "胚布總庫存數") {
                    $Tflag = 2;
                    $tmp = $element;
                }
                if ($element == "D1104 良品倉 KG") {
                    $flag = 3;
                    $tmp = $element;
                }
                if ($element == "D1107委外加工成品倉(非保稅)KG") {
                    $flag = 3;
                    $tmp = $element;
                }
                if ($element == "D1202 待重修倉 KG") {
                    $flag = 3;
                    $tmp = $element;
                }
                if ($element == "D1203 成品退貨倉(非保稅) KG") {
                    $flag = 3;
                    $tmp = $element;
                }
                if ($element == "D1204不良品倉KG") {
                    $flag = 3;
                    $tmp = $element;
                }
                if ($element == "D1206 待退客供成品倉KG") {
                    $flag = 3;
                    $tmp = $element;
                }
                if ($element == "D1207 待客訴倉(非保稅)KG") {
                    $flag = 3;
                    $tmp = $element;
                }
                if ($element == "成品庫存總数量 ：KG") {
                    $Tflag = 3;
                    $tmp = $element;
                }
                if ($element == "當日入庫數 KG") {
                    $flag = 4;
                    $tmp = $element;
                }
                if ($element == "當日發料數重修數 KG") {
                    $flag = 4;
                    $tmp = $element;
                }
                if ($element == "當日出貨數 KG") {
                    $flag = 4;
                    $tmp = $element;
                }
                if ($element == "累積出貨數 KG") {
                    $flag = 4;
                    $tmp = $element;
                }
                if ($element == "當日退貨數 YD") {
                    $flag = 4;
                    $tmp = $element;
                }
            }
        }
        Writecsv("../InventoryQcColData1.csv", $ColData1);
        Writecsv("../InventoryQcColData2.csv", $ColData2);
        Writecsv("../InventoryQcColData3.csv", $ColData3);
        Writecsv("../InventoryQcColData4.csv", $ColData4);
        ?>
        <div class="row mx-auto container-fluid text-white text-center">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 my-3">
                <!-- <h1 style="font-size:3vw"><b><?php echo $csv1[0][0]; ?></b></h1> -->
            </div>
        </div>
        <!-- <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" id="Col2"></div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" id="Col3"></div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" id="Col1"></div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" id="Col4"></div>
        </div> -->
        <?php
    } else {
        ?>
        <h1 style="font-size:2vw">Part 1 no data to display</h1>
        <?php
    }
    //Part 2
    $dir2 = __DIR__ . "/../QcData";
    $ii = 0;
    $tempFname;
    $scanned_directory2 = array_diff(scandir($dir2), array('..', '.'));
    foreach ($scanned_directory as $element) {
        if (stripos($element, ".csv") !== false) {
            if ($ii > 0) {
                unlink($dir2 . "/" . $tempFname);
            }
            $tempFname = $element;
            $ii++;
        }
    }
    $scanned_directory2 = array_diff(scandir($dir2), array('..', '.'));
    $csv2 = Readcsv(__DIR__ . "/../QcData/" . $scanned_directory2[2]);
    if ($ii > 0) {
        $TotalDyeProductSum = 0;
        $ColorReworkSum = 0;
        $DyeExtReworkSum = 0;
        $WarehousingSum = 0;
        $PackReworkSum = 0;
        $FixReworkSum = 0;
        $SpreadReworkSum = 0;
        $PercSum = 0;
        foreach ($csv2 as $key => $row) {
            if ($key > 1 && $row[1] != "") {
                $ValidData2 = array();
                foreach ($row as $key2 => $element) {
                    if ($key2 != 0 && $element != "") {
                        $ValidData2[] = $element;
                    }
                }
            }
        }
        ?>
        <div class="row mx-auto container-fluid text-white text-center">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <h1 style="font-size:2vw"><b><?php echo $csv2[0][0]; ?></b></h1>
            </div>
        </div>
        <?php
    } else {
        ?>
        <h1 style="font-size:2vw">Part 2 no data to display</h1>
        <?php
    }
    ?>
    <div class="row" style="height:28vh; margin-top: 100px;">
        <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3" id="Pie1"></div>
        <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3" id="Pie2"></div>
        <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3" id="Pie3"></div>
        <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3" id="Pie4"></div>
    </div>
</body>

<script src="/common/IDCC_plugin/bootstrap-5.0.0-dist/js/bootstrap.min.js"></script>

</html>
<script>
    const url = window.location.href;
    const parts = url.split('/');
    const firstPart = parts[2];
    const secondPart = parts[3];
    const urlPrefix = "https://" + firstPart + '/' + secondPart + "/Home/Legacy/DataStats_Analysis/RawFabricStockStats/";

    Highcharts.chart('Pie1', {
        series: [{
            name: "Amount",
            data: [
                { name: "染色總生產量(KG) Tổng sản lượng Nhuộm", y: <?php echo $ValidData2[0] ?>, color: '#ff9900' },
                { name: "顏色重修量(KG) Tổng sản lượng màu sửa", y: <?php echo $ValidData2[1] ?>, color: '#808080' },
                { name: "染色外觀重修量(KG) Tổng sản lượng sửa ngoại quan", y: <?php echo $ValidData2[2] ?> }
            ]
        }],
        chart: {
            backgroundColor: null,
            type: 'pie'
        },
        title: {
            text: null
        },
        yAxis: {
            allowDecimals: false,
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                showInLegend: true,
                dataLabels: {
                    enabled: true,
                    color: '#ffffff',
                    format: '<b>{point.y}</b> ({point.percentage:.1f}%)'
                }
            }
        },
        legend: {
            itemStyle: {
                color: '#ffffff',
                fontSize: "0.7vw"
            }
        },
        tooltip: {
            pointFormat: '<span style="color:black">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.1f}%)<br/>',
            shared: true
        }
    });
    Highcharts.chart('Pie2', {
        series: [{
            name: "Amount",
            data: [
                { name: "包裝入庫產量(KG) Số lượng đóng gói nhập kho", y: <?php echo $ValidData2[4] ?>, color: '#ff9900' },
                { name: "染色包裝重修量(KG) Số lượng đã đóng gói bị quay lại Nhuộm", y: <?php echo $ValidData2[5] ?>, color: '#808080' }
            ]
        }],
        chart: {
            backgroundColor: null,
            type: 'pie'
        },
        title: {
            text: null
        },
        yAxis: {
            allowDecimals: false,
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                showInLegend: true,
                dataLabels: {
                    enabled: true,
                    color: '#ffffff',
                    format: '<b>{point.y}</b> ({point.percentage:.1f}%)'
                }
            }
        },
        legend: {
            itemStyle: {
                color: '#ffffff',
                fontSize: "0.7vw"
            }
        },
        tooltip: {
            pointFormat: '<span style="color:black">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.1f}%)<br/>',
            shared: true
        }
    });
    Highcharts.chart('Pie3', {
        series: [{
            name: "Percentage",
            data: [
                { name: "總重修率(%)", y: <?php echo $ValidData2[11] ?>, color: '#ff9900' },
                { name: "定型重修率(%) Tỷ lệ hàng sửa Định hình", y: <?php echo $ValidData2[8] ?>, color: '#808080' }
            ]
        }],
        chart: {
            backgroundColor: null,
            type: 'pie'
        },
        title: {
            text: null
        },
        yAxis: {
            allowDecimals: false,
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                showInLegend: true,
                dataLabels: {
                    enabled: true,
                    color: '#ffffff',
                    format: '<b>{point.y}</b> ({point.percentage:.1f}%)'
                }
            }
        },
        legend: {
            itemStyle: {
                color: '#ffffff',
                fontSize: "0.9vw"
            }
        },
        tooltip: {
            pointFormat: '<span style="color:black">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.1f}%)<br/>',
            shared: true
        }
    });
    Highcharts.chart('Pie4', {
        series: [{
            name: "Percentage",
            data: [
                { name: "總重修率(%)", y: <?php echo $ValidData2[11] ?>, color: '#ff9900' },
                { name: "展布重修率(%) Tỷ lệ hàng sửa CV", y: <?php echo $ValidData2[10] ?>, color: '#808080' }
            ]
        }],
        chart: {
            backgroundColor: null,
            type: 'pie'
        },
        title: {
            text: null
        },
        yAxis: {
            allowDecimals: false,
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                showInLegend: true,
                dataLabels: {
                    enabled: true,
                    color: '#ffffff',
                    format: '<b>{point.y}</b> ({point.percentage:.1f}%)'
                }
            }
        },
        legend: {
            itemStyle: {
                color: '#ffffff',
                fontSize: "0.9vw"
            }
        },
        tooltip: {
            pointFormat: '<span style="color:black">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.1f}%)<br/>',
            shared: true
        }
    });
    Highcharts.chart('Col1', {
        title: {
            text: null
        },
        chart: {
            backgroundColor: null,
            type: 'bar'
        },
        xAxis: {
            title: {
                style: {
                    color: '#ffffff'
                }
            },
            labels: {
                style: {
                    color: '#ffffff'
                }
            }
        },
        yAxis: {
            title: {
                text: null,
                style: {
                    color: '#ffffff'
                }
            },
            labels: {
                style: {
                    color: '#ffffff'
                }
            }
        },
        plotOptions: {
            series: {
                color: '#529dcb'
            },
            bar: {
                dataLabels: {
                    enabled: true,
                    color: '#ffffff',
                    format: '<b>{point.y:.2f}</b>',
                    style: {
                        fontSize: "0.8vw"
                    }
                }
            }
        },
        legend: {
            enabled: false
        },
        data: {
            csvURL: urlPrefix + 'InventoryQcColData1.csv'
        },
        credits: {
            enabled: false
        }
    })
    Highcharts.chart('Col2', {
        title: {
            text: "<?php echo $ColTotal2 ?>",
            style: {
                color: '#ffffff',
                fontWeight: 'bold',
                fontSize: '1vw'
            }
        },
        chart: {
            backgroundColor: null,
            type: 'bar'
        },
        xAxis: {
            title: {
                style: {
                    color: '#ffffff'
                }
            },
            labels: {
                style: {
                    color: '#ffffff'
                }
            }
        },
        yAxis: {
            title: {
                text: null,
                style: {
                    color: '#ffffff'
                }
            },
            labels: {
                style: {
                    color: '#ffffff'
                }
            }
        },
        plotOptions: {
            series: {
                color: '#eca063'
            },
            bar: {
                dataLabels: {
                    enabled: true,
                    color: '#ffffff',
                    format: '<b>{point.y:.2f}</b>',
                    style: {
                        fontSize: "0.8vw"
                    }
                }
            }
        },
        legend: {
            enabled: false
        },
        data: {
            csvURL: urlPrefix + 'InventoryQcColData2.csv'
        },
        credits: {
            enabled: false
        }
    })
    Highcharts.chart('Col3', {
        title: {
            text: "<?php echo $ColTotal3 ?>",
            style: {
                color: '#ffffff',
                fontWeight: 'bold',
                fontSize: '1vw'
            }
        },
        chart: {
            backgroundColor: null,
            type: 'bar'
        },
        xAxis: {
            title: {
                style: {
                    color: '#ffffff'
                }
            },
            labels: {
                style: {
                    color: '#ffffff'
                }
            }
        },
        yAxis: {
            title: {
                text: null,
                style: {
                    color: '#ffffff'
                }
            },
            labels: {
                style: {
                    color: '#ffffff'
                }
            }
        },
        plotOptions: {
            series: {
                color: '#71bf50'
            },
            bar: {
                dataLabels: {
                    enabled: true,
                    color: '#ffffff',
                    format: '<b>{point.y:.2f}</b>',
                    style: {
                        fontSize: "0.8vw"
                    }
                }
            }
        },
        legend: {
            enabled: false
        },
        data: {
            csvURL: urlPrefix + 'InventoryQcColData3.csv'
        },
        credits: {
            enabled: false
        }
    })
    Highcharts.chart('Col4', {
        title: {
            text: null
        },
        chart: {
            backgroundColor: null,
            type: 'bar'
        },
        xAxis: {
            title: {
                style: {
                    color: '#ffffff'
                }
            },
            labels: {
                style: {
                    color: '#ffffff'
                }
            }
        },
        yAxis: {
            title: {
                text: null,
                style: {
                    color: '#ffffff'
                }
            },
            labels: {
                style: {
                    color: '#ffffff'
                }
            }
        },
        plotOptions: {
            series: {
                color: '#f3cc4f'
            },
            bar: {
                dataLabels: {
                    enabled: true,
                    color: '#ffffff',
                    format: '<b>{point.y:.2f}</b>',
                    style: {
                        fontSize: "0.8vw"
                    }
                }
            }
        },
        legend: {
            enabled: false
        },
        data: {
            csvURL: urlPrefix + 'InventoryQcColData4.csv'
        },
        credits: {
            enabled: false
        }
    })
</script>
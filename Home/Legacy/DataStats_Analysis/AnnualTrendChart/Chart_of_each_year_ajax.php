<?php
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");
$lang = new Language();
$lang->load("general");

?>
<style>
    html,
    body {
        background-image: url(/common/images/bg1.png);
        background-size: cover
    }

    text {
        font-size: 1.8vh !important;
    }

    .highcharts-title {
        font-size: 3vh !important;
        /* 設定字體大小 */
    }

    @media screen and (max-width: 900px) {
        body {
            background-size: cover;
        }

        #myGrid,
        #myGrid2,
        #myGrid3,
        #myGrid4,
        #myGrid5,
        #myGrid6,
        #myGrid7 {
            zoom: 1.5;

        }

        #container,
        #container2,
        #container3,
        #container4,
        #container5,
        #container6,
        #container7 {
            zoom: 0.8;
        }
    }

    .ag-theme-alpine {
        --ag-foreground-color: white;
        /*字*/
        --ag-background-color: rgba(26, 46, 57, 0.5);
        --ag-header-foreground-color: white;
        /*上面的字*/
        --ag-header-background-color: rgba(14, 86, 114, 0.8);
        --ag-odd-row-background-color: rgba(14, 86, 114, 0.5);
        --ag-header-column-resize-handle-color: rgba(0, 238, 238, 0.3);

        --ag-font-size: 20px;
        --ag-font-family: monospace;
    }

    .ag-cell {
        font-size: 18px;
        /* 调整数值以增加或减小字体大小 */
    }

    .ag-header-cell-label {
        font-size: 18px;
    }

    a {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        border: 1px solid #007bff;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    a:hover {
        background-color: #fff;
        color: #007bff;
    }

    /* 设置滚动条的宽度和高度 */
    ::-webkit-scrollbar {
        width: 5px;
        height: 10px;
    }

    /* 设置滚动条的背景颜色 */
    ::-webkit-scrollbar {
        background-color: #f1f1f1;
    }

    /* 设置滚动条的边框 */
    ::-webkit-scrollbar {
        border: 1px solid #ccc;
    }

    /* 设置滚动条的圆角 */
    ::-webkit-scrollbar {
        border-radius: 5px;

    }


    /* 设置滚动条的阴影 */
    ::-webkit-scrollbar {
        box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
    }

    /* 设置滚动条滑块的背景颜色 */
    ::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.4);
    }

    /* 设置滚动条滑块的圆角 */
    ::-webkit-scrollbar-thumb {
        border-radius: 5px;
    }

    /* 设置滚动条滑块的阴影 */
    ::-webkit-scrollbar-thumb {
        box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.2);
    }
</style>
<div>
    <!-- <div><a href="../index.php" style=" position:relative; left: 95%;">首頁</a></div> -->
</div>

<div style="display: flex; flex-wrap: wrap; margin-top: 10px;">
    <!-- <div id="myGrid" class="ag-theme-alpine" style="height: 450px; width: 100% "></div> -->
    <div id="container4" style="width: 100% ;height: 35vh"></div>
    <div id="container3" style="width: 50%  ;height: 30vh"></div>
    <div id="container2" style="width: 50%  ;height: 30vh"></div>
    <div id="container1" style="width: 50%  ;height: 30vh"></div>
    <div id="container0" style="width: 50%  ;height: 30vh"></div>
</div>
<?php

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

// 找table標題
$data_tabletitle = array();
$data_title = array();

$sql = "   SELECT DISTINCT [年]
  FROM Daluen_mds_report
  ORDER BY [年] DESC
";
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}


while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $data_title[] = $row['年'];
}
$data_tabletitle = [
    "月份",
    $data_title[4],
    $data_title[3],
    $data_title[2],
    $data_title[1],
    $data_title[0],
    "相比19%",
    "相比19",
    "相比20%",
    "相比20",
    "相比21%",
    "相比21",
    "相比22%",
    "相比22"
];
// var_dump($data_tabletitle);

$M1=$lang->line("general.January");
$M2=$lang->line("general.February");
$M3=$lang->line("general.March");
$M4=$lang->line("general.April");
$M5=$lang->line("general.May");
$M6=$lang->line("general.June");
$M7=$lang->line("general.July");
$M8=$lang->line("general.August");
$M9=$lang->line("general.September");
$M10=$lang->line("general.October");
$M11=$lang->line("general.November");
$M12=$lang->line("general.December");



// echo "sad";
$sql = "   SELECT   
-- [月份],
case 
when [月份]='一月' then '$M1'
when [月份]='二月' then '$M2'
when [月份]='三月' then '$M3'
when [月份]='四月' then '$M4'
when [月份]='五月' then '$M5'
when [月份]='六月' then '$M6'
when [月份]='七月' then '$M7'
when [月份]='八月' then '$M8'
when [月份]='九月' then '$M9'
when [月份]='十月' then '$M10'
when [月份]='十一月' then '$M11'
when [月份]='十二月' then '$M12'
 
end as '月份',
  ROUND(SUM(CASE WHEN [年] = '$data_tabletitle[1]' THEN TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) ELSE 0 END), 2) AS '$data_tabletitle[1]',
  ROUND(SUM(CASE WHEN [年] = '$data_tabletitle[2]' THEN TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) ELSE 0 END), 2) AS '$data_tabletitle[2]',
  ROUND(SUM(CASE WHEN [年] = '$data_tabletitle[3]' THEN TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) ELSE 0 END), 2) AS '$data_tabletitle[3]',
  ROUND(SUM(CASE WHEN [年] = '$data_tabletitle[4]' THEN TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) ELSE 0 END), 2) AS '$data_tabletitle[4]',
  ROUND(SUM(CASE WHEN [年] = '$data_tabletitle[5]' THEN TRY_CAST(REPLACE([生產數量], ',', '') AS FLOAT) ELSE 0 END), 2) AS '$data_tabletitle[5]'
  FROM Daluen_mds_report
GROUP BY [月份]
ORDER BY CASE [月份]
  WHEN '一月' THEN 1
  WHEN '二月' THEN 2
  WHEN '三月' THEN 3
  WHEN '四月' THEN 4
　WHEN '五月' THEN 5
　WHEN '六月' THEN 6
　WHEN '七月' THEN 7
　WHEN '八月' THEN 8
　WHEN '九月' THEN 9
　WHEN '十月' THEN 10
　WHEN '十一月' THEN 11
　WHEN '十二月' THEN 12

END


";
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
// $data_gate = array();
// $data_piechart= array();
$data_table = array();
$data_table_month = array();
$data_table_value = array();
// $data_tabletitle= array();
// $data_tabletitle= ["月份","2019","2020","2021","2022"];
// $data_tabletitle1= array();
function compared_to_this_year($compared_year, $this_year)
{
    return $this_year - $compared_year;
}

function compared_to_this_year_percent($compared_year, $this_year)
{
    if ($compared_year != 0)
        return 100 * ($this_year - $compared_year) / $compared_year;
    else
        return null;

}

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

    // $data_table[] = array(
    //   $data_tabletitle[0] => $row[$data_tabletitle[0]],
    //   $data_tabletitle[1] => $row[$data_tabletitle[1]],
    //   $data_tabletitle[2] => $row[$data_tabletitle[2]],
    //   $data_tabletitle[3] => $row[$data_tabletitle[3]],
    //   $data_tabletitle[4] => $row[$data_tabletitle[4]],
    //   $data_tabletitle[5] => $row[$data_tabletitle[5]],
    //   $data_tabletitle[6] => compared_to_this_year_percent($row[$data_tabletitle[1]],$row[$data_tabletitle[5]]),
    //   $data_tabletitle[7] => compared_to_this_year($row[$data_tabletitle[1]],$row[$data_tabletitle[5]]),
    //   $data_tabletitle[8] => compared_to_this_year_percent($row[$data_tabletitle[2]],$row[$data_tabletitle[5]]),
    //   $data_tabletitle[9] => compared_to_this_year($row[$data_tabletitle[2]],$row[$data_tabletitle[5]]),
    //   $data_tabletitle[10] => compared_to_this_year_percent($row[$data_tabletitle[3]],$row[$data_tabletitle[5]]),
    //   $data_tabletitle[11] => compared_to_this_year($row[$data_tabletitle[3]],$row[$data_tabletitle[5]]),
    //   $data_tabletitle[12] => compared_to_this_year_percent($row[$data_tabletitle[4]],$row[$data_tabletitle[5]]),
    //   $data_tabletitle[13] => compared_to_this_year($row[$data_tabletitle[4]],$row[$data_tabletitle[5]])
    // ); // 将查询结果保存到数组中
    // 月份資料
    $data_table_month[] = $row[$data_tabletitle[0]];
    // 每年各月資料

    $data_table_value1[] = $row[$data_tabletitle[1]];
    $data_table_value2[] = $row[$data_tabletitle[2]];
    $data_table_value3[] = $row[$data_tabletitle[3]];
    $data_table_value4[] = $row[$data_tabletitle[4]];
    $data_table_value5[] = $row[$data_tabletitle[5]];

    $data_table2_value1[] = $row[$data_tabletitle[1]] - (int) $_POST['base'][0];
    $data_table2_value2[] = $row[$data_tabletitle[2]] - (int) $_POST['base'][1];
    $data_table2_value3[] = $row[$data_tabletitle[3]] - (int) $_POST['base'][2];
    $data_table2_value4[] = $row[$data_tabletitle[4]] - (int) $_POST['base'][3];
    $data_table2_value5[] = $row[$data_tabletitle[5]] - (int) $_POST['base'][4];
}
// var_dump($data_table_month);

// var_dump($data_gate[0]);
// var_dump($data_piechart);
// $totalData = array(
//   $data_tabletitle[0] => '總計',
//   $data_tabletitle[1] => 0,
//   $data_tabletitle[2] => 0,
//   $data_tabletitle[3] => 0,
//   $data_tabletitle[4] => 0,
//   $data_tabletitle[5] => 0,
//   $data_tabletitle[6] => 0,
//   $data_tabletitle[7] => 0,
//   $data_tabletitle[8] => 0,
//   $data_tabletitle[9] => 0,
//   $data_tabletitle[10]=> 0,
//   $data_tabletitle[11]=> 0,
//   $data_tabletitle[12]=> 0,
//   $data_tabletitle[13]=> 0,

// );

// $totalData2 = array(
//   $data_tabletitle[0] => '月平均',
//   $data_tabletitle[1] => 0,
//   $data_tabletitle[2] => 0,
//   $data_tabletitle[3] => 0,
//   $data_tabletitle[4] => 0,
//   $data_tabletitle[5] => 0,
// );
// foreach ($data_table as $row) {
//   // $totalData['make'] += $row['make'];
//   $totalData[$data_tabletitle[1]] += ($row[$data_tabletitle[1]]);
//   $totalData[$data_tabletitle[2]] += ($row[$data_tabletitle[2]]);
//   $totalData[$data_tabletitle[3]] += ($row[$data_tabletitle[3]]);
//   $totalData[$data_tabletitle[4]] += ($row[$data_tabletitle[4]]);
//   $totalData[$data_tabletitle[5]] += ($row[$data_tabletitle[5]]);
//   $totalData[$data_tabletitle[6] ] =   compared_to_this_year_percent($totalData[$data_tabletitle[1]] ,$totalData[$data_tabletitle[5]] );
//   $totalData[$data_tabletitle[7] ] += ($row[$data_tabletitle[7] ]);
//   $totalData[$data_tabletitle[8] ] = compared_to_this_year_percent($totalData[$data_tabletitle[2]] ,$totalData[$data_tabletitle[5]] );
//   $totalData[$data_tabletitle[9] ] += ($row[$data_tabletitle[9] ]);
//   $totalData[$data_tabletitle[10]] = compared_to_this_year_percent($totalData[$data_tabletitle[3]] ,$totalData[$data_tabletitle[5]] );
//   $totalData[$data_tabletitle[11]] += ($row[$data_tabletitle[11]]);
//   $totalData[$data_tabletitle[12]] = compared_to_this_year_percent($totalData[$data_tabletitle[4]] ,$totalData[$data_tabletitle[5]] );
//   $totalData[$data_tabletitle[13]] += ($row[$data_tabletitle[13]]);



//   $totalData2[$data_tabletitle[1]] += ($row[$data_tabletitle[1]]) / 12;
//   $totalData2[$data_tabletitle[2]] += ($row[$data_tabletitle[2]]) / 12;
//   $totalData2[$data_tabletitle[3]] += ($row[$data_tabletitle[3]]) / 12;
//   $totalData2[$data_tabletitle[4]] += ($row[$data_tabletitle[4]]) / 12;
//   $totalData2[$data_tabletitle[5]] += ($row[$data_tabletitle[5]]) / 12;


// }
// echo $totalData['price'];
$jsonData_table = json_encode($data_table);
$jsonData_table_month = json_encode($data_table_month);
$jsonData_table_value = json_encode($data_table_value);
// $jsonData_rowdata = json_encode($rowdata);
// $jsonData_totalData = json_encode($totalData);
// $jsonData_totalData2 = json_encode($totalData2);

$jsonData_table_value1[0] = json_encode($data_table_value1);
$jsonData_table_value1[1] = json_encode($data_table_value2);
$jsonData_table_value1[2] = json_encode($data_table_value3);
$jsonData_table_value1[3] = json_encode($data_table_value4);
$jsonData_table_value1[4] = json_encode($data_table_value5);

$jsonData_table2_value1[0] = json_encode($data_table2_value1);
$jsonData_table2_value1[1] = json_encode($data_table2_value2);
$jsonData_table2_value1[2] = json_encode($data_table2_value3);
$jsonData_table2_value1[3] = json_encode($data_table2_value4);
$jsonData_table2_value1[4] = json_encode($data_table2_value5);

// $jsonData_table_value2 = json_encode($data_table_value2);
// $jsonData_table_value3 = json_encode($data_table_value3);
// $jsonData_table_value4 = json_encode($data_table_value4);
// $jsonData_table_value5 = json_encode($data_table_value5);

// var_dump($data_table[2]);

// var_dump($jsonData_table_value[0]);
?>



<script type="text/javascript">



</script>


</body>

</html>



<script>
    for (var drawchart = 0; drawchart < 5; drawchart++) {



        var categories = <?php echo $jsonData_table_month; ?>;
        var year = ['<?php echo $data_tabletitle[1]; ?>',
            '<?php echo $data_tabletitle[2]; ?>',
            '<?php echo $data_tabletitle[3]; ?>',
            '<?php echo $data_tabletitle[4]; ?>',
            '<?php echo $data_tabletitle[5]; ?>']
        if (drawchart == 0) {
            var data = [
                { name: '<?php echo $data_tabletitle[1]; ?>', values: <?php echo $jsonData_table_value1[0]; ?> },
                { name: '<?php echo $data_tabletitle[1]; ?>', values: <?php echo $jsonData_table2_value1[0]; ?> }
            ];
        }
        else if (drawchart == 1) {
            var data = [
                { name: '<?php echo $data_tabletitle[2]; ?>', values: <?php echo $jsonData_table_value1[1]; ?> },
                { name: '<?php echo $lang->line("general.Actual_reduction_minus_estimated"); ?>', values: <?php echo $jsonData_table2_value1[1]; ?> }
            ];
        } else if (drawchart == 2) {
            var data = [
                { name: '<?php echo $data_tabletitle[3]; ?>', values: <?php echo $jsonData_table_value1[2]; ?> },
                { name: '<?php echo $lang->line("general.Actual_reduction_minus_estimated"); ?>', values: <?php echo $jsonData_table2_value1[2]; ?> }
            ];
        } else if (drawchart == 3) {
            var data = [
                { name: '<?php echo $data_tabletitle[4]; ?>', values: <?php echo $jsonData_table_value1[3]; ?> },
                { name: '<?php echo $lang->line("general.Actual_reduction_minus_estimated"); ?>', values: <?php echo $jsonData_table2_value1[3]; ?> }
            ];
        } else if (drawchart == 4) {
            var data = [
                { name: '<?php echo $data_tabletitle[5]; ?>', values: <?php echo $jsonData_table_value1[4]; ?> },
                { name: '<?php echo $lang->line("general.Actual_reduction_minus_estimated"); ?>', values: <?php echo $jsonData_table2_value1[4]; ?> },
            ];
        }
        var base = [];
        base[0] = <?php echo (int) $_POST['base'][0]; ?>;
        base[1] = <?php echo (int) $_POST['base'][1]; ?>;
        base[2] = <?php echo (int) $_POST['base'][2]; ?>;
        base[3] = <?php echo (int) $_POST['base'][3]; ?>;
        base[4] = <?php echo (int) $_POST['base'][4]; ?>;
        base[5] = <?php echo (int) $_POST['base'][5]; ?>;
        base[6] = <?php echo (int) $_POST['base'][6]; ?>;
        // console.log(base);
        var bbase = parseInt(base[drawchart]);
        data.push({
            name: '<?php echo $lang->line("general.Estimated_quantity"); ?>',
            values: [bbase, bbase, bbase, bbase, bbase, bbase, bbase, bbase, bbase, bbase, bbase, bbase]
        })

        data.push({
            name: '<?php echo $lang->line("general.Pre_staining_Plan"); ?>',
            values: [base[5], base[5], base[5], base[5], base[5], base[5], base[5], base[5], base[5], base[5], base[5], base[5]]
        })

        if (drawchart == 4) {
            base6 = [];
            const currentMonth = new Date().getMonth() + 1;
            for (let i = 1; i <= 12; i++) {
                if (i >= currentMonth) {
                    base6.push(base[6] / (12 - currentMonth + 1));
                } else {
                    base6.push(null);
                }
            }
            // console.log(currentMonth);
            data.push({
                name: 'Forecasts',
                values: base6
            })

        }
        var color_num = [];
        color = ['#ECA063', '#529DCB', '#71BF50', '#D46934', '#F3CC4F']
        color_num[drawchart] = [color[drawchart], 'white', "red"];
        var chartname = "container" + drawchart;
        // 创建图表
        Highcharts.chart(chartname, {
            credits: {
                enabled: false
            },
            chart: {
                type: "areaspline",
                backgroundColor: "rgba(0,0,0,0)",

            },
            legend: {
                itemStyle: {
                    color: "#FFFFFF"
                }
            },
            title: {
                text: year[drawchart],
                style: {
                    color: "#FFFFFF",

                }
            },
            xAxis: {
                labels: {

                    style: {
                        color: "#FFFFFF"
                    }
                },
                categories: categories
            },
            yAxis: {
                labels: {
                    // enabled: false, // 隱藏 y 軸標題
                    style: {
                        color: "#FFFFFF"
                    }
                },
                title: {
                    text: '',
                    style: {
                        color: "#FFFFFF"
                    }
                }
            },
            plotOptions: {
                areaspline: {
                    fillOpacity: 0.3
                }
            },
            series: data.map(function (item, index) {
                var seriesObj = {
                    name: item.name,
                    data: item.values,
                    color: color_num[drawchart][index] // 使用索引来获取对应的颜色
                };
                if (index === 0) {
                    seriesObj.fillOpacity = 0.7;
                    seriesObj.zIndex = 2;
                    // seriesObj.marker =  false;

                }
                if (index === 1) {
                    seriesObj.fillOpacity = 0.5;
                    // seriesObj.type='line';
                    seriesObj.zIndex = 1;
                    seriesObj.marker = false;

                }
                if (index === 2) {
                    seriesObj.fillOpacity = 0.5;
                    seriesObj.type = 'line';
                    seriesObj.zIndex = 1;
                    seriesObj.marker = false;

                }
                if (index === 3) {
                    seriesObj.marker = false;
                    seriesObj.type = 'line';
                    seriesObj.fillColor = '#00ff00';
                    seriesObj.color = '#00ff00';
                    seriesObj.dashStyle = 'Dash' // 设置为虚线样式
                }
                if (index === 4) {
                    seriesObj.fillOpacity = 0.7;
                    seriesObj.color = 'rgba(49,234,206,0.3)';
                    seriesObj.fillColor = 'rgba(49,234,206,0.3)';
                    seriesObj.zIndex = 2;
                    // seriesObj.marker =  false;
                }
                return seriesObj;
            })
        });


    }
</script>
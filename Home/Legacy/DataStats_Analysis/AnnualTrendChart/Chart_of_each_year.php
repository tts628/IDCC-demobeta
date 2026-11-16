<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="/common/IDCC_plugin/deprecated/highcharts/highcharts.js"></script>
    <!-- Include the JS for AG Grid -->
    <script src="/common/IDCC_plugin/ag-grid/ag-grid-community.min.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/highcharts-3d.js"></script>
    <!-- 引入 Highcharts 主题 -->
    <script src="/common/IDCC_plugin/deprecated/highcharts/themes/high-contrast-light.js"></script>
    <script src="/common/IDCC_plugin/jquery/jquery.min.js"></script>


    <title></title>

    <style>
        body {
            color: white;
        }
    </style>

</head>
<?php 
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/language/language.php");
$lang = new Language();
$lang->load("general");
?>
<body>
    <!-- <div style="position: fixed; top: 0%; right: 10%; font-size: 45px; opacity: 0.5; color: white;">台中</div> -->

    <!-- <div><a href="../index.php" style=" position:absolute; left: 95%;">首頁</a> -->

    <?php

    require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
    use Config\DbMapper;

    $dbMapper = new DbMapper();
    $conn = $dbMapper->getLink(['IDCC', "ReturnDatesAsStrings"]);

    // 找table標題
    
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


    ?>
    <!-- <div><a href="../index.php" style=" position:absolute; left: 95%;">首頁</a> -->
    &nbsp;<?php echo $data_title[4]; ?>：<input type="text" id="myInput1" style="width:70px"
        oninput="restrictToNumbers(event)" />
    &nbsp;<?php echo $data_title[3]; ?>：<input type="text" id="myInput2" style="width:70px"
        oninput="restrictToNumbers(event)" />
    &nbsp;<?php echo $data_title[2]; ?>：<input type="text" id="myInput3" style="width:70px"
        oninput="restrictToNumbers(event)" />
    &nbsp;<?php echo $data_title[1]; ?>：<input type="text" id="myInput4" style="width:70px"
        oninput="restrictToNumbers(event)" />
    &nbsp;<?php echo $data_title[0]; ?>：<input type="text" id="myInput5" style="width:70px"
        oninput="restrictToNumbers(event)" />
    &nbsp;<?php echo $lang->line("general.Pre_staining_Plan"); ?>：<input type="text" id="myInput6" style="width:70px"
        oninput="restrictToNumbers(event)" />
    &nbsp;<?php echo 'Forecasts'; ?>：<input type="text" id="myInput7" style="width:70px"
        oninput="restrictToNumbers(event)" />
    &nbsp;<button onclick="getValue()"> <?php echo $lang->line("general.Pre_staining_Plan"); ?></button>
    <!-- <br>Pre_staining_Plan
</br> -->

    <!-- </div> -->
    <div id="Daluen-result"></div>
    <div id="Daluen-result2"></div>

    <script>
        const queryString = window.location.search;

        function ajaxCall(base) {
            $.ajax({
                // beforeSend: function () {
                //     $('.loading').fadeIn(300);
                // },
                url: "Chart_of_each_year_ajax.php" + queryString,
                type: "POST",
                data: {
                    base: base
                },
                success: function (response) {
                    $("#Daluen-result").html(response);
                },
                error: function (err) {
                    $("#Daluen-result").html(err);
                },

            });

            //         setTimeout(function () {
            //     ajaxCall()
            //   }, 300000);
        }


        ajaxCall([0, 0, 0, 0, 0])
        // ajaxCall2()




    </script>
    <script>
        function restrictToNumbers(event) {
            var input = event.target;
            var value = input.value;

            // 仅允许数字输入
            var newValue = value.replace(/[^0-9]/g, '');
            input.value = newValue;
        }


        function getValue() {
            var input1 = document.getElementById("myInput1").value;
            var input2 = document.getElementById("myInput2").value;
            var input3 = document.getElementById("myInput3").value;
            var input4 = document.getElementById("myInput4").value;
            var input5 = document.getElementById("myInput5").value;
            var input6 = document.getElementById("myInput6").value;
            var input7 = document.getElementById("myInput7").value;


            var input = [input1, input2, input3, input4, input5, input6, input7];
            // console.log(input);
            ajaxCall(input);
            console.log(input);

        }


        //   var divideGroup = document.getElementById('myInput');
        //   divideGroup.addEventListener('change', () => {
        //             // selectedValue = selectCSV.value;
        //             // console.log(divideGroup.value);
        //             ajaxCall(divideGroup.value);


        //         });

    </script>
    <?php // require_once('../../../common/web_mode.php'); ?>
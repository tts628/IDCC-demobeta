<?php
//  ini_set('display_errors', '0');
//  error_reporting(0);
//  ini_set('log_errors', '1');
// var_dump($_GET['web_name']);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="../../common/js/jquery.js"></script>

    <script src="../../common/highcharts/highcharts.js"></script>
    <script type="text/javascript" language="javascript">
        // var webURLs ='<?php //echo $_GET['web_name']; ?>';
        // webURLs=webURLs.split(",");
        // for (var i = 0; i < webURLs.length; i++) {
        //     webURLs[i] = "./" + webURLs[i] ;
        // }

        // 原本放網址
        // webURLs= [
        //     "./MachineProduction.php"
        //     "http://localhost:81/IDCC/iioT/Dyeing_machine/Dyeing_machine_visualization_v2_demo.php?machine1=H01&machine2=H02&lang=en-US"
        // ];
        var webURLs = [
            "../Dyeing_machine_visualization_v2.php?machine1=H01&machine2=H02",
            "../Dyeing_machine_visualization_v2.php?machine1=H03&machine2=H04",
            "../Dyeing_machine_visualization_v2.php?machine1=H05&machine2=H06",
            "../Dyeing_machine_visualization_v2.php?machine1=H07&machine2=H08",
            "../Dyeing_machine_visualization_v2.php?machine1=H09&machine2=H10",
            "../Dyeing_machine_visualization_v2.php?machine1=H11&machine2=H12",
            "../Dyeing_machine_visualization_v2.php?machine1=H13&machine2=H14",
            "../Dyeing_machine_visualization_v2.php?machine1=H15&machine2=H16",
            "../Dyeing_machine_visualization_v2.php?machine1=H17&machine2=H18",
            "../Dyeing_machine_visualization_v2.php?machine1=H19&machine2=H20",
            "../Dyeing_machine_visualization_v2.php?machine1=H21&machine2=H22",
            "../Dyeing_machine_visualization_v2.php?machine1=H23&machine2=H24",
            "../Dyeing_machine_visualization_v2.php?machine1=H25&machine2=H26",
            "../Dyeing_machine_visualization_v2.php?machine1=H27&machine2=H28",
            "../Dyeing_machine_visualization_v2.php?machine1=H29&machine2=H30",
            "../Dyeing_machine_visualization_v2.php?machine1=H31&machine2=H32",
            "../Dyeing_machine_visualization_v2.php?machine1=H33&machine2=H34"
            // "https://logicartidcc.website/AllSeven/iioT/Dyeing_machine/Dyeing_machine_visualization_v2.php?machine1=H23&machine2=H24",
            // "https://logicartidcc.website/AllSeven/iioT/Dyeing_machine/Dyeing_machine_visualization_v2.php?machine1=H33&machine2=H34",
            // "https://logicartidcc.website/AllSeven/iioT/Dyeing_machine/Dyeing_machine_visualization_v2.php?machine1=H09&machine2=H10",
            // "https://logicartidcc.website/AllSeven/iioT/Dyeing_machine/Dyeing_machine_visualization_v2.php"
        ];
        var currentIndex = 0;

        
        currentIndex = <?php echo $_GET['web_no']??1; ?>-1;

        // switch ('<?//php echo $_GET['web_no']; ?>') {
        //     case '1':
        //         currentIndex = 0;
        //         break;
        //     case '2':
        //         currentIndex = 1;
        //         break;
        //     case '3':
        //         currentIndex = 2;
        //         break;
        //     case '4':
        //         currentIndex = 3;
        //         break;
        //     case '5':
        //         currentIndex = 4;
        //         break;
        //     case '6':
        //         currentIndex = 5;
        //         break;
        //     default:
        //         currentIndex = 0
        //         break;
        // }

        var intervalId;
        function ajaxCall() {
            $.ajax({
                url: webURLs[currentIndex],
                success: function (show) {
                    // console.log(show);
                    $('#MainStage').empty();

                    if (typeof ajaxCall_2_time !== 'undefined')
                        clearInterval(ajaxCall_2_time);
                    if (typeof ajaxCall_1_time !== 'undefined')
                        clearInterval(ajaxCall_1_time);
                    if (typeof Flash_section_name_time !== 'undefined')
                        clearTimeout(Flash_section_name_time)
                    if (typeof Flash_section_alarm_time !== 'undefined')
                        clearTimeout(Flash_section_alarm_time)
                    if (typeof Flash_section_section_time !== 'undefined')
                        clearTimeout(Flash_section_section_time)


                    if (typeof BoilerMachine_timer !== 'undefined')
                        clearTimeout(BoilerMachine_timer)
                    if (typeof DispenseErrorReport_timer_call !== 'undefined')
                        clearTimeout(DispenseErrorReport_timer_call)
                    if (typeof DispenseErrorReport_timer_checktime !== 'undefined')
                        clearTimeout(DispenseErrorReport_timer_checktime)
                    if (typeof NgEvent_English_timer !== 'undefined')
                        clearTimeout(NgEvent_English_timer)



                    $('#MainStage').html(show);

                    //$("body").css("background-color","black");
                    //$("body").css("color","white");


                    if ('<?php echo $_GET['web_name']; ?>' == 'OvertimeDyelot.php') {
                        $("body").css("zoom", "0.75");
                        $(".table-wrapper").css("height", "95vh");


                    }


                    else {
                        $("body").css("zoom", "0.666666");

                    }
                    startInterval();
                },
                error: function (err) {
                    // console.log(err);
                }
            });
        }

        function startInterval() {
            clearInterval(intervalId);
            intervalId = setInterval(function () {
                currentIndex = (currentIndex + <?php echo $_GET['offset']??1;?>) % webURLs.length;
                ajaxCall();
            }, 5000);
        }
        ajaxCall();
        startInterval();
        var intervalId2 = setInterval(function () {
            location.reload();
        }, 5000 * (webURLs.length * 10 + 1));
    </script>
</head>

<body id="MainStage"></body>

</html>
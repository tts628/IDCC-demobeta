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
    <script src="/common/IDCC_plugin/jquery/jquery.min.js"></script>

    <script type="text/javascript" language="javascript">
        // var webURLs ='<?php //echo $_GET['web_name']; ?>';
        // webURLs=webURLs.split(",");
        // for (var i = 0; i < webURLs.length; i++) {
        //     webURLs[i] = "./" + webURLs[i] ;
        // }

        const mainQueryString = window.location.search;
        const adaptedQueryString = mainQueryString.replace('?', '&');
        
        var webURLs = [
            "./Dyeing_machine_visualization_v4.php?machine1=JD01&machine2=JD02",
            "./Dyeing_machine_visualization_v4.php?machine1=JD03&machine2=JD04",
            "./Dyeing_machine_visualization_v4.php?machine1=JD05&machine2=JD06",
            "./Dyeing_machine_visualization_v4.php?machine1=JD07&machine2=JD08",
            "./Dyeing_machine_visualization_v4.php?machine1=JD09&machine2=JD10",
            "./Dyeing_machine_visualization_v4.php?machine1=JD11&machine2=JD12",
            "./Dyeing_machine_visualization_v4.php?machine1=JD13&machine2=JD14",
            "./Dyeing_machine_visualization_v4.php?machine1=JD15&machine2=JD16",
            "./Dyeing_machine_visualization_v4.php?machine1=JD17&machine2=JD18",
            "./Dyeing_machine_visualization_v4.php?machine1=JD19&machine2=JD20",
            "./Dyeing_machine_visualization_v4.php?machine1=JD21&machine2=JD22",
            "./Dyeing_machine_visualization_v4.php?machine1=JD23&machine2=JD24",
            "./Dyeing_machine_visualization_v4.php?machine1=JD25&machine2=JD26",
            "./Dyeing_machine_visualization_v4.php?machine1=JD27&machine2=JD28",
            "./Dyeing_machine_visualization_v4.php?machine1=JD29&machine2=JD30",
            "./Dyeing_machine_visualization_v4.php?machine1=JD31&machine2=JD32",
            "./Dyeing_machine_visualization_v4.php?machine1=JD33&machine2=JD34"
        ];
        var currentIndex = 0;


        currentIndex = <?php echo $_GET['web_no'] ?? 1; ?> - 1;

        var intervalId;
        function ajaxCall() {
            $.ajax({
                url: webURLs[currentIndex] + adaptedQueryString,
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
                        // $("body").css("zoom", "0.75");
                        $(".table-wrapper").css("height", "95vh");


                    }


                    else {
                        // $("body").css("zoom", "0.666666");

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
                currentIndex = (currentIndex + <?php echo $_GET['offset'] ?? 1; ?>) % webURLs.length;
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
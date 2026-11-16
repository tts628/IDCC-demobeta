<!DOCTYPE html>
<html>

<head>
    <style>
        html,
        body {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
        }
    </style><!--
        <script src="../../logic_art/Demoidcc/common/highcharts/highcharts.js"></script>
        <script src="../../logic_art/Demoidcc/common/highcharts/accessibility.js"></script>
        <script src="../../logic_art/Demoidcc/common/highcharts/data.js"></script>
        <script src="../../logic_art/Demoidcc/common/highcharts/no-data-to-display.js"></script>-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js">
    </script>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script type="text/javascript" language="javascript">
        const queryString = window.location.search;

        var counter = 0;
        var timer = 0;
        var check = 0;
        function TimeLooping() {
            $(document).ready(function () {
                $.ajax({
                    //url: "C:/var/www/LAIDCC/R Week 4/DyeAlarmReport_ext/DyeAlarmReport-show_data.php",
                    url: "./DyeAlarmReport_ext/DyeAlarmReport-show_data.php" + queryString,
                    type: "POST",
                    data: {
                        "counter": counter
                    },
                    success: function (show) {
                        $('#stage').html(show);
                    },
                    error: function (err) {
                        console.log(err);
                    }
                });
            });
            if (timer > 37) {
                location.reload();
            }
            if (counter < 14) {
                counter += 1;
            }
            else {
                counter = 0;
            }
            timer += 1;
            setTimeout(TimeLooping, 2000)
        }
        function CheckUpdate() {
            $(document).ready(function () {
                $.ajax({
                    //url: "C:/var/www/LAIDCC/R Week 4/DyeAlarmReport_ext/DyeAlarmReport-show_data.php",
                    url: "./DyeAlarmReport_ext/DyeAlarmReport-check_update.php" + queryString,
                    success: function (flag) {
                        check = flag
                        //console.log("flag(0=updating,1=updated)");
                        //console.log(flag)
                    }
                })
            })
            if (check == 21 || check == 22 || counter == 0) {
                setTimeout(Update1, 1000)
            }
            if (check == 12 || check == 22 || counter == 0) {
                setTimeout(Update2, 1000)
            }
            setTimeout(CheckUpdate, 2000)
        }
        function Update1() {
            $(document).ready(function () {
                $.ajax({
                    //url: "C:/var/www/LAIDCC/R Week 4/DyeAlarmReport_ext/DyeAlarmReport-show_data.php",
                    url: "./DyeAlarmReport_ext/DyeAlarmReport-check_update.php" + queryString,
                    type: "POST",
                    data: {
                        "reset": 0
                    },
                    success: function () {
                        //console.log("Reset data status")
                    }
                })
                $.ajax({
                    //url: "C:/var/www/LAIDCC/R Week 4/DyeAlarmReport_ext/DyeAlarmReport-show_data.php",
                    url: "./DyeAlarmReport_ext/DyeAlarmReport-get_data.php" + queryString,
                    success: function () {
                        //console.log("Data updating..")
                    }
                })
            })
        }
        function Update2() {
            console.log("updating alarm data")
            $(document).ready(function () {
                $.ajax({
                    //url: "C:/var/www/LAIDCC/R Week 4/DyeAlarmReport_ext/DyeAlarmReport-show_data.php",
                    url: "./DyeAlarmReport_ext/DyeAlarmReport-check_update.php" + queryString,
                    type: "POST",
                    data: {
                        "reset": 1
                    },
                    success: function () {
                        //console.log("Reset data status")
                    }
                })
                $.ajax({
                    //url: "C:/var/www/LAIDCC/R Week 4/DyeAlarmReport_ext/DyeAlarmReport-show_data.php",
                    url: "./DyeAlarmReport_ext/DyeAlarmReport-get_alarm_data.php" + queryString,
                    success: function (alarm) {
                        console.log("alarm data updated")
                    }
                })
            })
        }
        CheckUpdate()
        TimeLooping()
    </script>
</head>

<body style="background-color:#272727;">
    <div id="status"></div>
    <div id="stage">
        <?php
        // require_once realpath("./DyeAlarmReport_ext/DyeAlarmReport-show_data.php")
            //include("C:/var/www/LAIDCC/R Week 4/DyeAlarmReport_ext/DyeAlarmReport-show_data.php") 
            ?>
    </div>
</body>

</html>
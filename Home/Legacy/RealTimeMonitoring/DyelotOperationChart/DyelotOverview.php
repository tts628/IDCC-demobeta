<!DOCTYPE html>
<html>

<head><!--
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

        async function showData() {
            $.ajax({
                url: "./DyelotOverview_ext/DyelotOverview_show_data.php" + queryString,
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
        }

        function TimeLooping() {
            showData();
            if (timer > 27) {
                location.reload();
            }
            counter += 1;
            timer += 1;
            setTimeout(TimeLooping, 20000)
            // setTimeout(TimeLooping , 500)
        }

        async function CheckUpdate() {
            $.ajax({
                url: "./DyelotOverview_ext/DyelotOverview_check_update.php" + queryString,
                success: function (flag) {
                    check = flag;
                    //console.log("flag(0=updating,1=updated)");
                    //console.log(flag)
                }
            })
            if (check == 1 || counter == 0) {
                setTimeout(Update, 10000)
            }
            setTimeout(CheckUpdate, 20000)
        }
        async function Update() {
            return $.ajax({
                url: "./DyelotOverview_ext/DyelotOverview_check_update.php" + queryString,
                type: "POST",
                data: {
                    "reset": 0
                },
                success: function () {
                    $.ajax({
                        url: "./DyelotOverview_ext/DyelotOverview_get_data.php" + queryString,
                        success: function () {
                            showData();
                            // console.log("Data updated")
                        }
                    })
                    //console.log("Reset data status")
                }
            });
        }

        async function main() {
            try {
                TimeLooping();
                await CheckUpdate();
                await Update();
            } catch (error) {
                console.error("An error occurred:", error);
            }
        }

        $(() => {
            main();
        })
    </script>
</head>

<body>
    <div id="status"></div>
    <div id="stage" style="background-color:#272727;color:white;height:100vh">
        <?php
        // require_once realpath("./DyelotOverview_ext/DyelotOverview_show_data.php")
            ?>
    </div>
</body>

</html>
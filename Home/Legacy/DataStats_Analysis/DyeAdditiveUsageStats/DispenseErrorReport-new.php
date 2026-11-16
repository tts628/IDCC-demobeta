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
    </style>
    <script src="/common/IDCC_plugin/deprecated/highcharts/highcharts.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/accessibility.js"></script>
    <script src="/common/IDCC_plugin/deprecated/highcharts/modules/no-data-to-display.js"></script>
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
        var counter = -1;
        var b = 0;
        function checktime() {
            b += 1;
            //console.log("Time counter(seconds)");
            //console.log(b);
            update()
            if (b > 28) {
                location.reload()
            }
            setTimeout(checktime, 1000)
        }
        function update() {
            $(document).ready(function () {
                $.ajax({
                    url: "./DispenseErrorReport_get_data.php" + queryString,
                    success: function () {
                        //console.log("Data updating..")
                    }
                });
            })
        }
        function call() {
            if (counter < 2) {
                counter += 1;
            }
            else {
                counter = 0;
            }
            $(document).ready(function () {
                $.ajax({
                    url: "./DispenseErrorReport_show_data.php" + queryString,
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
            setTimeout(call, 10000);
        }
        checktime()
        call()
    </script>
</head>

<body style="background-color:#272727;">
    <div id="status"></div>
    <div id="stage" style="color:white;max-height:100vh"><?php // include("./DispenseErrorReport_show_data.php") ?>
    </div>
</body>

</html>
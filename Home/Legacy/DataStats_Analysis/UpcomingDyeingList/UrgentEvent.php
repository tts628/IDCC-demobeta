<?php
function Readcsv($filename)
{
    //fetching data from local csv
    $f = fopen($filename, 'r');
    if ($f === false) {
        die('Cannot raed the file ' . $filename);
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

$timeSetting = (Readcsv("./UrgentEvent/timeSetting.csv"))[0][0] * 1000;

date_default_timezone_set('UTC+7');
$date = new DateTime();
$date->modify('-24 hours');
$quarterDayBefore = $date->format('Y-m-d H:i:s');


require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

$dbMapper = new DbMapper();
$link = $dbMapper->getLink(['IDCC']);

$sql_count = "SELECT COUNT(Job) AS Count FROM JobEvent WHERE What IS NOT NULL";
$stmt_count = sqlsrv_query($link, $sql_count, array($quarterDayBefore));
while (($rows = sqlsrv_fetch_array($stmt_count, SQLSRV_FETCH_ASSOC))) {
    $count = $rows["Count"];
}
?>
<!DOCTYPE html>
<html>

<head>
    <style>
        html,
        body {
            /* position: fixed; */
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
        }

        @media (max-width: 1608px) {

            html,
            body {
                /* position: static; */
            }
        }

        td {
            border-radius: 5px;
        }

        .page-index {
            color: gray;
            transition: background-color 0.3s ease-in-out;
            transition: color 0.3s ease-in-out;
        }

        .page-index.clicked {
            color: white;
        }

        .page-index:hover {
            background: #cfcfcf;
            color: black;
            cursor: pointer;
        }

        .empty-box {
            display: block;
            height: 35vh;

            box-sizing: border-box;
        }

        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff !important;
            text-decoration: none;
            border-radius: 5px;
            /* border: 1px solid #007bff; */
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        a:hover {
            background-color: #fff;
            color: #007bff !important;
        }
    </style>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>

<body style="background-color:#272727;color:white">
    <div class="container-fluid">
        <div class="row">
            <div class="col-8">
                <h2 class='font-weight-bold my-3 mx-5'><b>補胚 & 特急件 務必優先處理<br>
                        hàng bù mộc và hàng đặc biệt gấp nhất định cần phải được xử lý gấp</b></h2>
            </div>
            <div class="col mt-5 mx-4">
                <div id="page_index">
                    <table class="table table-borderless text-center text-white">
                    </table>
                </div>
            </div>
        </div>
        <div id="urgent_event_container"></div>
    </div>
</body>

</html>

<script>
    const queryString = window.location.search;

    var timeSetting = <?php echo $timeSetting; ?>;
    // console.log("using time Setting: " + timeSetting);
    function generateTable(max) {
        const table = document.querySelector('#page_index table');
        let rows = Math.ceil(max / 8);
        let current = 1;
        for (let i = 0; i < 2; i++) {
            let row = document.createElement('tr');
            for (let j = 0; j < 8; j++) {
                let cell = document.createElement('td');
                cell.classList.add('page-index');
                cell.setAttribute('data-value', j);
                if (current <= max) {
                    let start = current;
                    let end = Math.min(current + 7, max);
                    cell.innerText = `${start.toString().padStart(2, '0')}-${end.toString().padStart(2, '0')}`;
                    current += 8;
                    row.appendChild(cell);
                }
            }
            table.appendChild(row);
        }
    }

    var dataCount = <?php echo $count ?? 0; ?>;
    if (dataCount > 80) {
        dataCount = 80;
    }
    generateTable(dataCount);

    function divideIntoGroups(num) {
        let groups = [];
        for (let i = 0; i < num; i += 8) {
            groups.push(i);
        }
        return groups;
    }

    var elements = document.querySelectorAll(".page-index");
    var currentElement;
    var sequence = divideIntoGroups(dataCount);
    var counter = 0;
    var offset;
    var time = "<?php echo $quarterDayBefore; ?>";
    // console.log("using condition: " + time);

    // Function to execute the AJAX request and change the color
    function executeAjaxRequest(element, counter) {
        element.classList.add("clicked");
        var value = element.getAttribute("data-value");
        $.ajax({
            url: "./UrgentEvent/show_csv.php" + queryString,
            method: "POST",
            data: { offset: value },
            success: function (response) {
                $('#urgent_event_container').html(response);
            },
            error: function () {
                console.log("Error");
            }
        });
    }

    // Add click event listeners to elements
    for (var i = 0; i < elements.length; i++) {
        elements[i].addEventListener("click", function () {
            if (currentElement) {
                currentElement.classList.remove("clicked");
            }
            currentElement = this;
            executeAjaxRequest(currentElement);
        });
    }

    function changeElement() {
        if (dataCount === 0) {
            $("#urgent_event_container").html(`<h1 class="border fw-bold text-center mt-5 py-5">No Data</h1>`);
        }
        //console.log(counter);
        if (dataCount > 8) {
            if (counter > dataCount / 8) {
                location.reload();
            }
            if (counter == Math.floor(dataCount / 8) || counter == 0) {
                console.log("updating csv");
                $.ajax({
                    url: "./UrgentEvent/sql_to_csv.php" + queryString,
                    method: "POST",
                    data: { time: time },
                    success: function (response) {
                        console.log(response);
                    },
                    error: function () {
                        console.log("Error");
                    }
                });
            }
            if (elements.length === 0) {
                return; // do nothing if there are no elements
            }
            if (currentElement) {
                currentElement.classList.remove("clicked");
            }
            var index = sequence[counter % sequence.length] / 8;
            if (index >= elements.length) {
                index = index % elements.length; // wrap around if index is out of range
            }
            currentElement = elements[index];
            executeAjaxRequest(currentElement, counter);
        }
        else {
            var index = sequence[counter % sequence.length] / 8;
            currentElement = elements[index];
            if (currentElement) {
                executeAjaxRequest(currentElement, counter);
            }
            if (counter >= 1) {
                $.ajax({
                    url: "./UrgentEvent/sql_to_csv.php" + queryString,
                    method: "POST",
                    data: { time: time },
                    success: function (response) {
                        console.log(response);
                    },
                    error: function () {
                        console.log("Error");
                    }
                });
                location.reload();
            }
        }

        counter++;
        setTimeout(function () {
            changeElement();
        }, timeSetting); // call the function again after 10 seconds
    }

    changeElement(); // start the timer immediately
</script>
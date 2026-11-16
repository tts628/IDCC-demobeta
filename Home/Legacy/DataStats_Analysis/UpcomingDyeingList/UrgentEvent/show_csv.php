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

if (isset($_POST["offset"])) {
    $offset = $_POST["offset"] * 8;
} else {
    echo "Uncaught error, using offset = 0";
    $offset = 0;
    /*
    // Check if form has been submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $offset = htmlspecialchars($_POST['subOffset']);
        echo "<p>using offset: $offset</p>";
        //$offset = $_POST[""];
    }
    echo '
    <form method="POST">
        <label for="subOffset">Taking user manual input as offset:</label>
        <input type="text" name="subOffset" id="subOffset">
        <button type="submit">Submit</button>
    </form>';*/
}
//echo "<div class='row mx-3 mt-2'>";
$data = Readcsv("UrgentEvent.csv");
$k = 0;
foreach ($data as $key => $row) {
    if ($key >= $offset && $key < $offset + 8) {

        if (($key - $offset) % 4 == 0) {
            if ($k > 0) {
                echo "</div>";
            }
            echo "<div class='row mx-3 mt-2 w3-animate-left'>";
            $k++;
        }

        echo "<div class='col card mx-1' style='background-color:#2c3034;height:43vh;'>
                    <div class='card-body text-center overflow-x'>
                    $row[0]
                    <br>";

        if ($row[1] == "") {
            echo "<span class='bg-white empty-box'></span>";
        }
        echo "
                    $row[1]
                    </div>
                </div>";
    }
}
$last_boxes = sizeof($data) % 8;
if ($last_boxes && (sizeof($data) - $offset) < 8) {
    for ($i = 0; $i < (8 - $last_boxes); $i++) {
        if ((8 - $last_boxes + $i) % 4 == 0) {
            echo "</div>
                    <div class='row mx-3 mt-2 w3-animate-left'>";
        }
        echo "<div class='col card mx-1' style='background-color:#2c3034;'>
                    <div class='card-body text-center'>
                    -
                    <span class='bg-white empty-box'></span>
                    </div>
                </div>";
    }
}
echo "</div>";
?>
<style>
    .overflow-x {
        overflow-x: auto;
    }
</style>

<script>
    $(document).ready(function () {
        $('table td table td:contains("此訊息由大綸LA IDCC系統自動發送，請勿回覆")').parent().parent().parent().parent().remove();
        $('table td p:contains("配方如下:")').each(function () {
            var foundTarget = false;
            $(this).contents().filter(function () {
                if (this.nodeType === 3) { // text node
                    if (this.textContent.trim() === '配方如下:') {
                        foundTarget = true;
                    }
                    return foundTarget; // return false for all text nodes that come after the target
                } else { // non-text node
                    if ($(this).text().trim() === '配方如下:') {
                        foundTarget = true;
                    }
                    return foundTarget; // return true for all non-text nodes that come after the target
                }
            }).remove()
        });

        $('table td p').each(function () {
            // Get the current text
            const currentHtml = $(this).html();

            // Replace the desired text
            const filteredHtml = currentHtml.replace(/[\(（]顏色如下所示[\)）]/g, "");

            // Set the new text
            $(this).html(filteredHtml);
        });
    });
</script>
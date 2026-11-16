<?php
const MODULE_SETTINGS_FILE = 'models/parseModuleSettings.php';
const ENV_SETTINGS_FILE    = 'env/env.json';
const DB_FILE              = 'env/database_SPC.php';
const JSON_CACHE_FILE      = 'compensatorCache.json';
$pathSegments = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
define('CLIENT_ROOT', $pathSegments[1]);
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);

// Read the contents of the JSON file into a string
$jsonString = file_get_contents('../compensatorCache.json');

// Parse the JSON string into a PHP variable
$cachedCreationTime = json_decode($jsonString);

// Check if decoding was successful and if the data is numeric
if ($cachedCreationTime === null || !is_numeric($cachedCreationTime)) {
    // Failed to decode JSON or the data is not numeric
    echo "Failed to decode cached ID";
} else {
    // Convert the data to an integer
    $latestCreationTime = intval($cachedCreationTime);


    require_once realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/bootstrap/' . MODULE_SETTINGS_FILE);
    $settingsFilePath = realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/' . ENV_SETTINGS_FILE);
    $moduleSettingsModel = new ModuleSettingsModel(); // Create an instance of the model
    $JsonSettings = $moduleSettingsModel->getModuleSettingsData($settingsFilePath);
    $settings = json_decode($JsonSettings);

    // Extract and process dedicated machine
    $dedicatedMachine = $settings->machine ?? null; // Handle potential missing key
    if ($dedicatedMachine) {
        $machines = explode(' - ', $dedicatedMachine);

        if (count($machines) !== 2) {
            // Handle unexpected machine format
            throw new Exception('Invalid machine format in settings: ' . $dedicatedMachine);
        }

        // [$firstMachine, $lastMachine] = $machines;
    }

    require_once realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/' . DB_FILE);

    $allDyelotSql = "SELECT TOP(1) 
        CreationTime
    FROM
        Dyelots
    WHERE 
        SheetNo IN (
            SELECT 
                DISTINCT(SheetNo) AS SheetNo
            FROM 
                Dyelots
            WHERE
                StartTime IS NOT NULL
                AND SheetNo <> ' '
                And (
                    CAST(DATEDIFF(s, StartTime, EndTime) AS FLOAT) / 3600
                ) > 1
                AND (
                    Machine >= ?
                    AND Machine <= ?
                )
        )
        AND StartTime IS NOT NULL
        ORDER BY
            CreationTime DESC";

    $allDyelotStmt = sqlsrv_prepare($conn, $allDyelotSql, $machines);

    // Execute the statement
    if (!sqlsrv_execute($allDyelotStmt)) {
        // Handle invalid sql execution
        var_dump(sqlsrv_errors());
        throw new Exception('Query error on allDyelotStmt');
    }

    while ($row = sqlsrv_fetch_array($allDyelotStmt, SQLSRV_FETCH_ASSOC)) {
        $lastCreationTime = $row["CreationTime"]->format("Ymd His");
    }
    $lastTimeStamp = strtotime($lastCreationTime);

    if ($lastTimeStamp > $latestCreationTime || 1) {
        require_once realpath('../models/AllDyelotCompensator.php');

        $AllDyelotModel = new AllDyelotModel();
        $AllDyelotData = $AllDyelotModel->getDataByCustomInput($machines, $conn);

        ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <div id="status"></div>
        <script>
            const rawPHPData = `<?php echo $AllDyelotData ?>`;
            const jsData = JSON.parse(rawPHPData);
            console.log(jsData)

            const url = '../models/UpdateRedyeType.php';
            async function processData() { //jsData.length
                for (let index = 0; index < jsData.length; index++) {
                    const value = jsData[index];
                    const options = {
                        method: 'POST', // HTTP POST method
                        headers: {
                            'Content-Type': 'application/json' // Specify JSON content type
                        },
                        body: JSON.stringify(value) // Convert JSON data to a string
                    };

                    await fetch(url, options)
                        .then(response => {
                            // Check if the request was successful
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.text(); // Parse the JSON response
                        })
                        .then(data => {
                            // Handle the JSON response from the server
                            // $("#status").append($(`<p>${data}</p>`));
                        })
                        .catch(error => {
                            // Handle errors
                            console.error('There was a problem with the fetch operation:', error);
                        });
                }
            }

            processData();
        </script>
        <?php


        // require_once realpath('../models/UpdateRedyeType.php');

        // $UpdateRedyeTypeModel = new UpdateRedyeTypeModel();
        // $UpdateResult = $UpdateRedyeTypeModel->updateRedyeType($AllDyelotData);
        // echo $UpdateResult;
    }

    // $jsonData = json_encode($lastTimeStamp);
    // file_put_contents('../' . JSON_CACHE_FILE, $jsonData);
}
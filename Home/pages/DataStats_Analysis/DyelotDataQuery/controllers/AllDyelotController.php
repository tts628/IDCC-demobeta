<?php
ini_set('memory_limit', '-1');
function validateInput($inputString)
{
    // Check for allowed characters using regular expression
    $pattern = "/^[a-zA-Z0-9-]+$/";
    return preg_match($pattern, $inputString);
}

class AllDyelotController
{
    public function handleRequest()
    {
        // Validate request method and content type
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid request method or content type']);
            exit;
        }

        // $inputData = json_decode(file_get_contents('php://input'), true);
        $inputData = $_POST;

        if ($inputData === null) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid JSON data']);
            exit;
        }

        header('Content-Type: application/json');

        $rawDateStart = $inputData["start-time-selector"];
        $rawDateEnd = $inputData["end-time-selector"];
        $dateStart = $dateEnd = "";
        if (!empty($rawDateStart) && !empty($rawDateEnd)) {
            if ($rawDateStart < $rawDateEnd) {
                $dateStart = $rawDateStart;
                $dateEnd = date("Y-m-d", strtotime($rawDateEnd . " +1 day"));
            } else {
                $dateStart = $rawDateEnd;
                $dateEnd = date("Y-m-d", strtotime($rawDateStart . " +1 day"));
            }
        } else if (!empty($rawDateStart)) {
            $dateStart = $rawDateStart;
            $dateEnd = date("Y-m-d", strtotime($dateStart . " +1 day"));
        } else if (!empty($rawDateEnd)) {
            $dateStart = $rawDateEnd;
            $dateEnd = date("Y-m-d", strtotime($dateStart . " +1 day"));
        }

        if (!empty($dateStart) || !empty($dateEnd)) {
            $dateStart = date("Y-m-d H:i:s", strtotime($dateStart));
            $dateEnd = date("Y-m-d H:i:s", strtotime($dateEnd));
        }

        if (!empty($inputData["dyelot-selector"]) && !validateInput($inputData["dyelot-selector"])) {
            // Input is invalid, display error message
            echo json_encode('無效的輸入格式：請僅使用連字符"-",字母或數字');
            return;
        }

        $validatedInput = [
            "dateStart" => $dateStart,
            "dateEnd" => $dateEnd,
            "dyelotSelector" => "%" . $inputData["dyelot-selector"] . "%",
            "redyeInterval" => 3
        ];

        $modelPath = '../models/AllDyelotModel.php';
        require_once realpath($modelPath);
        $AllDyelotModel = new AllDyelotModel();

        $dataByCustomInput = $AllDyelotModel->getDataByCustomInput($validatedInput);

        echo json_encode($dataByCustomInput);
        
        if (empty(count(array_filter($validatedInput)))) {
            echo json_encode("請輸入工卡號或選擇區間");
            return;
        }
    }
}

$AllDyelotController = new AllDyelotController();
$AllDyelotController->handleRequest();
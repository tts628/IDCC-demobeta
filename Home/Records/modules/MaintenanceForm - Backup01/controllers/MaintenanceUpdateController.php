<?php

class MaintenanceUpdateController
{
    public function handleRequest()
    {
        header('Content-Type: application/json');

        // Validate request method and content type
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid request method or content type']);
            exit;
        }
        
        if ($_POST === null) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid POST data']);
            exit;
        }
        
        $photoHolder = $_POST["photo-holder"];
        $photoTmpName = $_FILES["Photo"]["tmp_name"];

        if ($photoHolder === "" && $photoTmpName === "") {
            $photoData = null;
        } elseif ($photoHolder !== "") {
            $photoData = base64_decode($photoHolder);
        } elseif ($photoTmpName !== "") {
            $photoData = file_get_contents($_FILES["Photo"]["tmp_name"]);
        }

        $userInput = array_merge($_POST, ["Photo" => $photoData]);
        $machineType = $_GET["machineType"];

        $controllerParams = [
            "userInput" => $userInput,
            "machineType" => $machineType
        ];

        $modelPath = '../models/MaintenanceUpdateModel.php';
        require_once realpath($modelPath);
        $maintenanceUpdateModel = new MaintenanceUpdateModel(); 
        $updateResult = $maintenanceUpdateModel->updateData($controllerParams);

        echo json_encode($updateResult);
    }
}

$maintenanceUpdateController = new MaintenanceUpdateController();
$maintenanceUpdateController->handleRequest();
<?php
class MachineListUpdateController
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

        $modelPath = '../models/MachineListUpdateModel.php';
        require_once realpath($modelPath);
        $machinelistUpdateModel = new MachineListUpdateModel(); 
        $updateResult = $machinelistUpdateModel->updateData($_POST);

        echo json_encode($updateResult);
    }
}

$machinelistUpdateController = new MachineListUpdateController();
$machinelistUpdateController->handleRequest();
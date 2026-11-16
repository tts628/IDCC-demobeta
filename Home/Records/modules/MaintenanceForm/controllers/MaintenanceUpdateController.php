<?php

class MaintenanceUpdateController
{
    public function handleRequest()
    {
        header('Content-Type: application/json');

        // Validate request method and content type
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid request method']);
            exit;
        }

        if (empty($_POST)) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'No data submitted']);
            exit;
        }

        // Determine the model to use based on the form method
        $modelPath = '../models/MaintenanceUpdateModel.php';
        if (!$modelPath || !file_exists($modelPath)) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid model path']);
            exit;
        }

        require_once realpath($modelPath);

        // Instantiate the appropriate model and perform the action
        $maintenanceUpdateModel = new MaintenanceUpdateModel();
        $updateResult = $maintenanceUpdateModel->updateData($_POST);

        echo json_encode($updateResult);
    }
}

$maintenanceUpdateController = new MaintenanceUpdateController();
$maintenanceUpdateController->handleRequest();

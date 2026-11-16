<?php

class IntegrityCheckController
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
        $modelPath = '../models/IntegrityCheckModel.php';
        if (!$modelPath || !file_exists($modelPath)) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid model path']);
            exit;
        }

        require_once realpath($modelPath);

        // Instantiate the appropriate model and perform the action
        $integrityCheckModel = new IntegrityCheckModel();
        $integrityData = $integrityCheckModel->checkIntegrity($_POST);

        echo json_encode($integrityData);
    }
}

$integrityCheckController = new IntegrityCheckController();
$integrityCheckController->handleRequest();

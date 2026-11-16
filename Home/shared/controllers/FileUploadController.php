<?php
class FileUploadController
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

        $modelPath = '../models/FileUploadModel.php';
        require_once realpath($modelPath);
        $fileUploadModel = new FileUploadModel(); 
        $uploadResult = $fileUploadModel->uploadFile($_FILES, $_POST);

        echo json_encode($uploadResult);
    }
}

$fileUploadController = new FileUploadController();
$fileUploadController->handleRequest();
<?php
class LanguagePackController
{
    public function getLanugagePack()
    {
        header('Content-Type: application/json');

        // Validate request method and content type
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $_SERVER['CONTENT_TYPE'] !== 'application/json') {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid request method or content type']);
            exit;
        }

        $postFileName = json_decode(file_get_contents('php://input'), true);
        
        require_once '../models/LanguagePackModel.php';
        $languagePackModel = new LanguagePackModel($postFileName["pageName"] ?? "");
        $loadedLanguagePack = $languagePackModel->loadLanguagePack();

        echo json_encode($loadedLanguagePack);
    }
}

$languagePackController = new LanguagePackController();
$languagePackController->getLanugagePack();
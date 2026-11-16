<?php
$pathSegments = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
define('CLIENT_ROOT', $pathSegments[1]);
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);

// require_once realpath('../models/parseModuleSettings.php');

class CarouselSettingsController
{
    public function handleRequest()
    {
        header('Content-Type: application/json');

        // Validate request method and content type
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $_SERVER['CONTENT_TYPE'] !== 'application/json') {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid request method or content type']);
            exit;
        }

        $carouselSettingsData = json_decode(file_get_contents('php://input'), true);

        if ($carouselSettingsData === null) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid JSON data']);
            exit;
        }

        $keysToCheck = array('carouselScreen', 'carouselTimer', 'cssLink', 'html');
        $arrayKeys = array_keys($carouselSettingsData);
        
        // Check if all keys to check for are present in the array
        $missingKeys = array_diff($keysToCheck, $arrayKeys);
        
        if (!empty($missingKeys)) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => "Unexpected input keys, missing " . implode(', ', $missingKeys)]);
            exit;
        }
        
        // echo json_encode($carouselSettingsData);
        $rawCarouselTimer = $carouselSettingsData["carouselTimer"];

        if (!is_numeric($rawCarouselTimer)) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => "Invalid timer"]);
            exit;
        }

        $filePath = realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/Home/Menu/modules/CarouselSettings/settings.json');
        $settingsData = json_encode($carouselSettingsData);

        if (file_put_contents($filePath, $settingsData)) {
            echo json_encode("Success");
        } else {
            // http_response_code(400); // Bad Request
            echo json_encode(['error' => "Unable to write data to the JSON file."]);
        }


        // $moduleSettingsModel = new ModuleSettingsModel(); // Create an instance of the model

        // $settingsPath = ROOT_PATH . '/' . CLIENT_ROOT . '/Home/' . $category . '/modules/' . $module . '/settings.json';
        // $settingsData = $moduleSettingsModel->getModuleSettingsData($settingsPath);
        // $decodedSettingsData = json_decode($settingsData);
        // $decodedSettingsData->category = $category;
        
        // echo json_encode($carouselSettingsData);
    }
}

$carouselSettingsController = new CarouselSettingsController();
$carouselSettingsController->handleRequest();
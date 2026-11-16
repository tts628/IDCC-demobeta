<?php
const SETTINGS_FILE = 'settings.json';
$pathSegments = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
define('CLIENT_ROOT', $pathSegments[1]);
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);

require_once realpath('../models/parseModuleSettings.php');

class ContentUpdaterController
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

        $contentUpdaterData = json_decode(file_get_contents('php://input'), true);

        if ($contentUpdaterData === null) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid JSON data']);
            exit;
        }

        $module = $contentUpdaterData['module'];
        // var_dump($module);

        $version = 0;
        switch($module) {
            case 'sidebar':
            case 'CarouselSettings':
            case 'ClientSettings':
                $category = "Menu";
                break;
            case 'DyelotStatistics':
            case 'ProductionWeekStatistics':
            case 'ProductionMonthStatistics':
            case 'AlarmTimeStatistics':
            case 'AllDyelot':
            case 'DyelotMonthStatistics':
                $category = "Reports";
                break;
            case 'MaintenanceForm':
            case 'FactoryProduction':
            case 'AllMachineList':
                $category = "Records";
                break;
            case 'DyelotDataQuery':
                $version = 0.5;
                $category = "DataStats_Analysis";
                break;
        }
        if (!isset($category)) {
            echo json_encode(json_encode("module not available"));
            return;
        }

        $moduleSettingsModel = new ModuleSettingsModel(); // Create an instance of the model

        if ($version === 0) {
            $settingsPath = ROOT_PATH . '/' . CLIENT_ROOT . '/Home/' . $category . '/modules/' . $module . '/settings.json';
        } elseif ($version === 0.5) {
            $settingsPath = ROOT_PATH . '/' . CLIENT_ROOT . '/Home/pages/' . $category . '/' . $module . '/settings.json';
        }

        if (!realpath($settingsPath)) {
            echo json_encode(json_encode("module not available"));
            return;
        }

        $settingsData = $moduleSettingsModel->getModuleSettingsData($settingsPath);
        $decodedSettingsData = json_decode($settingsData);
        if (!is_object($decodedSettingsData)) {
            if ($decodedSettingsData === "module not available") {
                echo json_encode(json_encode("module not available"));
                return;
            } else {
                throw new Exception("Invalid parsed module settings structure (object expected)");
            }
        }
        $decodedSettingsData->category = $category;
        
        echo json_encode(json_encode($decodedSettingsData));
    }
}

$contentUpdaterController = new ContentUpdaterController();
$contentUpdaterController->handleRequest();
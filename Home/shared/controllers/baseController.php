<?php

class BaseDataController
{
    public function handleRequest()
    {
        // Validate request method and content type
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $_SERVER['CONTENT_TYPE'] !== 'application/json') {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid request method or content type']);
            exit;
        }

        $inputData = json_decode(file_get_contents('php://input'), true);

        if ($inputData === null) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid JSON data']);
            exit;
        }

        $module = $inputData["module"];

        switch ($module) {
            case 'sidebar':
            case 'CarouselSettings':
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
        }
        if (!isset($category)) {
            throw new Exception("Invalid module ($module) or path hasn't been specified");
        }

        if (isset($inputData["model"])) {
            $modelName = $inputData["model"];
        } else {
            $modelName = $module;
        }

        $modelPath = '../../' . $category . '/modules/' . $module . '/models/' . $modelName . 'Model.php';
        require_once realpath($modelPath);

        switch ($module) {
            case 'AlarmTimeStatistics':
                $baseDataModel = new AlarmTimeStatisticsModel(); // Create an instance of the model
                break;
            case 'MaintenanceForm':
                switch ($modelName) {
                    case "MachineListMaxId":
                        $baseDataModel = new MachineListMaxIdModel(); // Create an instance of the model
                        break;
                    case "MachineListData":
                        $baseDataModel = new MachineListDataModel(); // Create an instance of the model
                        break;
                    default:
                        $baseDataModel = new MaintenanceFormModel(); // Create an instance of the model
                }
                break;
            case 'AllMachineList':
                switch ($modelName) {
                    case "MachineType":
                        $baseDataModel = new MachineTypeModel(); // Create an instance of the model
                        break;
                    default:
                        $baseDataModel = new MachineListModel(); // Create an instance of the model
                }
                break;
            case 'AllDyelot':
                $baseDataModel = new DateLimitModel(); // Create an instance of the model
                break;
            default:
                throw new Exception("Invalid module: $module");
        }

        header('Content-Type: application/json');
        $dataByDefault = $baseDataModel->getDataByDefault();

        echo json_encode($dataByDefault);
    }
}

$baseDataControllerController = new BaseDataController();
$baseDataControllerController->handleRequest();
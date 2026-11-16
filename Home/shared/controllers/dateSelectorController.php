<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
class DateSelectorController
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

        $version = 0;
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
                $category = "Records";
                break;
            case 'DyelotDataQuery':
                $version = 0.5;
                $category = "DataStats_Analysis";
                break;
        }
        if (!isset($category)) {
            throw new Exception("Invalid module ($module) or path hasn't been specified");
        }

        if ($version === 0) {
            $modelPath = "../../{$category}/modules/{$module}/models/{$module}Model.php";
        } elseif ($version === 0.5) {
            $modelPath = "../../pages/{$category}/{$module}/models/{$module}Model.php";
        }

        require_once realpath($modelPath);

        switch ($module) {
            case 'DyelotStatistics':
                $dataByDateRangeModel = new DyelotStatisticsModel(); // Create an instance of the model
                break;
            case 'ProductionMonthStatistics':
                $dataByDateRangeModel = new ProductionMonthStatisticsModel(); // Create an instance of the model
                break;
            case 'ProductionWeekStatistics':
                $dataByDateRangeModel = new ProductionWeekStatisticsModel(); // Create an instance of the model
                break;
            case 'DyelotMonthStatistics':
                $dataByDateRangeModel = new DyelotMonthStatisticsModel(); // Create an instance of the model
                break;
            case 'AlarmTimeStatistics':
                $dataByDateRangeModel = new AlarmTimeStatisticsModel(); // Create an instance of the model
                break;
            case 'DyelotDataQuery':
                $dataByDateRangeModel = new DyelotDataQueryModel(); // Create an instance of the model
                break;
        }

        header('Content-Type: application/json');

        $dateRange = $inputData['dateRange'];
        $rawDateStart = $dateRange["dateStart"];
        $rawDateEnd = $dateRange["dateEnd"];
        if (!empty($rawDateStart) && !empty($rawDateEnd)) {
            if ($rawDateStart < $rawDateEnd) {
                $dateStart = $rawDateStart;
                $dateEnd = $rawDateEnd;
            } else {
                $dateStart = $rawDateEnd;
                $dateEnd = $rawDateStart;
            }
        } else if (!empty($rawDateStart)) {
            $dateStart = $rawDateStart;
            $dateEnd = date("Y-m-d", strtotime($dateStart . " +1 day"));
        } else if (!empty($rawDateEnd)) {
            $dateStart = $rawDateEnd;
            $dateEnd = date("Y-m-d", strtotime($dateStart . " +1 day"));
        } else {
            echo json_encode([]);
            exit;
        }

        $formattedDateRange = [
            date("Y-m-d H:i:s", strtotime($dateStart)),
            date("Y-m-d H:i:s", strtotime($dateEnd))
        ];

        $dataByDateRangeData = $dataByDateRangeModel->getDataByDateRange($formattedDateRange);

        echo json_encode($dataByDateRangeData);
    }
}

$dateSelectorController = new DateSelectorController();
$dateSelectorController->handleRequest();
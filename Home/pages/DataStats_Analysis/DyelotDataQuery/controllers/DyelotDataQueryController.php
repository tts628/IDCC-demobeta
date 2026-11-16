<?php
require_once realpath('../models/DyelotDataQueryModel.php');

class DyelotDataQueryController
{
    private $inputData;
    private $dyelotDataQueryModel;

    public function __construct()
    {
        $inputData = !empty($_POST) ? $_POST : json_decode(file_get_contents('php://input'), true);

        if ($inputData === null) {
            http_response_code(400); // Bad Request
            echo json_encode(['error' => 'Invalid JSON data']);
            exit;
        }

        $this->inputData = $inputData;
        $this->dyelotDataQueryModel = new DyelotDataQueryModel();
    }

    public function handleRequest()
    {
        $queryResult = $this->dyelotDataQueryModel->queryDyelotData($this->inputData);
        echo json_encode($queryResult);
    }
}


$DyelotDataQueryController = new DyelotDataQueryController();
$DyelotDataQueryController->handleRequest();
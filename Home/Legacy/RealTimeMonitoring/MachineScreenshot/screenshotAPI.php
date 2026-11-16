<?php
ini_set('display_errors', '1');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;


class ImageRetriever
{
    private $pdo;

    public function __construct($dbConfig)
    {
        $this->initializePDO($dbConfig);
    }

    private function initializePDO($dbConfig)
    {
        try {
            $dbMapper = new DbMapper();
            $this->pdo = $dbMapper->getPDO($dbConfig);
        } catch (Exception $e) {
            // Handle initialization errors gracefully
            throw new Exception("Database connection error: " . $e->getMessage());
        }
    }

    public function methodController($method)
    {
        try {
            switch ($method) {
                case 'machineName':
                    return $this->getMachines();
                case 'screenShot':
                    return $this->getImages();
                default:
                    throw new Exception("Invalid method");
            }
        } catch (Exception $e) {
            // Return JSON error response
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    public function getImages()
    {
        try {
            $sql = "SELECT ScreenImage, Name FROM ScreenShot";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $queryResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $finalData = [];
            foreach ($queryResult as $row) {
                $binaryData = $row['ScreenImage'];
                $finalData[$row['Name']] = base64_encode($binaryData);
            }

            return json_encode($finalData);
        } catch (Exception $e) {
            // Return JSON error response
            return json_encode(["error" => $e->getMessage()]);
        }
    }

    public function getMachines()
    {
        try {
            $sql = "SELECT DISTINCT([Name]) AS MachineName FROM ScreenShot";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $queryResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return json_encode($queryResult);
        } catch (Exception $e) {
            // Return JSON error response
            return json_encode(["error" => $e->getMessage()]);
        }
    }
}

// Securely handle input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!is_array($inputData) || !isset($inputData["method"])) {
    echo json_encode(["error" => "Invalid input data"]);
    exit;
}

// Usage example:
$dbConfig = ['IDCC'];
$imageRetriever = new ImageRetriever($dbConfig);
echo $imageRetriever->methodController($inputData["method"]);
<?php
const MODULE_SETTINGS_FILE = 'models/parseModuleSettings.php';
const ENV_SETTINGS_FILE    = 'env/env.json';
const DB_FILE              = 'env/database_SPC.php';
const JSON_CACHE_FILE      = 'compensatorCache.json';
$pathSegments = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
define('CLIENT_ROOT', $pathSegments[1]);
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
class UpdateRedyeTypeModel
{
    public function updateRedyeType($AllDyelotData)
    {
        try {
            $decodedData = json_decode($AllDyelotData);
            require realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/' . DB_FILE);

            $updateRedyeTypeSql = "UPDATE
                Dyelots
            SET
                RedyeType = :RedyeType
            WHERE
                Dyelot = :Dyelot";

            $stmt = $PDOconn->prepare($updateRedyeTypeSql);

            $stmt->bindParam(':RedyeType', $redyeType);
            $stmt->bindParam(':Dyelot', $dyelot);
            foreach ($decodedData as $dyelotData) {
                $redyeType = $dyelotData->RedyeType;
                $dyelot = $dyelotData->Dyelot;
                echo json_encode("Updating $dyelot to $redyeType...");
                $stmt->execute();
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

$postData = file_get_contents('php://input');
$UpdateRedyeTypeModel = new UpdateRedyeTypeModel();
$UpdateRedyeTypeModel->updateRedyeType($postData);
// echo json_encode($UpdateResult);
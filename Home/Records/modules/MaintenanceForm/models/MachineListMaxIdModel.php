<?php
const DB_FILE = 'env/database_IDCC.php';
$pathSegments = explode("/", parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
define('CLIENT_ROOT', $pathSegments[1]);
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
class MachineListMaxIdModel
{
    public function getDataByDefault()
    {
        try {
            require_once realpath(ROOT_PATH . '/' . CLIENT_ROOT . '/' . DB_FILE);

            // Prepare SQL statement
            $stmt = $PDOconn->prepare("SELECT MAX(ID) AS MaxID FROM Machine_List WHERE Deleted = '0'");

            // Execute the query
            $stmt->execute();

            // Set the resulting array to associative
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            return $data;
        } catch (Exception $e) {
            // Handle errors appropriately
            echo 'Error: ' . $e->getMessage();
        }
    }
}
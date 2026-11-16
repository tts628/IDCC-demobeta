<?php
ini_set('display_errors', '1');

$rootPath = $_SERVER['DOCUMENT_ROOT'];
$requestURI = $_SERVER['REQUEST_URI'];
$clientRoot = explode('/', $_SERVER["REQUEST_URI"])[1];

require_once realpath($rootPath . '/common/Config/DbMapper.php');

session_start();
$_SESSION = [];
if (!isset($_SESSION["user"])) {
    $loginToken = true;
    require_once realpath('./login.php');
} else {
    header("Location: /" . $clientRoot . "/Home/");
}
<?php
$serverName = getenv('IDCC_DB_HOST');
$Database = getenv('IDCC_DB_NAME');
$UID = getenv('IDCC_DB_UID');
$PWD = getenv('IDCC_DB_PWD');
$connectionInfo = array(
    "Database" => $Database,
    "UID" => $UID,
    "PWD" => $PWD,
    "CharacterSet" => "UTF-8"
);
$conn = sqlsrv_connect($serverName, $connectionInfo);

// connection validating
if (!$conn) {
    echo json_encode("Connection could not be established.\nPlease check your network or contact LA engineer");
    // die (print_r(sqlsrv_errors(), true));
    die();
}


$dsn = "sqlsrv:server=$serverName ; Database=$Database";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

try {
    $PDOconn = new PDO($dsn, $UID, $PWD, $options);
    // echo "Connected successfully";
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
}
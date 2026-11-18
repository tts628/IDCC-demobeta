<?php
$serverName = getenv('SPC_DB_HOST') ?: getenv('HOST');
$Database = getenv('SPC_DB_NAME') ?: getenv('MDSVN_SPC_DB');
$UID = getenv('SPC_DB_UID') ?: getenv('UID');
$PWD = getenv('SPC_DB_PWD') ?: getenv('PWD');
require_once __DIR__ . '/database-required.php';
idccRequireDatabaseSettings([$serverName, $Database, $UID, $PWD]);
$trustServerCertificate = filter_var(getenv('SPC_DB_TRUST_SERVER_CERTIFICATE') ?: 'false', FILTER_VALIDATE_BOOLEAN);
$connectionInfo = array(
    "Database" => $Database,
    "UID" => $UID,
    "PWD" => $PWD,
    "CharacterSet" => "UTF-8",
    "Encrypt" => true,
    "TrustServerCertificate" => $trustServerCertificate
);
$conn = sqlsrv_connect($serverName, $connectionInfo);

// connection validating
if (!$conn) {
    echo json_encode("Connection could not be established.\nPlease check your network or contact LA engineer");
    // die (print_r(sqlsrv_errors(), true));
    die();
}


$dsn = "sqlsrv:server=$serverName ; Database=$Database";
$dsn .= ';Encrypt=1;TrustServerCertificate=' . ($trustServerCertificate ? '1' : '0');
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

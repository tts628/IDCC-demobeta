<?php
$serverName = getenv('IDCC_DB_HOST');
$Database = getenv('IDCC_DB_NAME');
$UID = getenv('IDCC_DB_UID');
$PWD = getenv('IDCC_DB_PWD');
$trustServerCertificate = filter_var(getenv('IDCC_DB_TRUST_SERVER_CERTIFICATE') ?: 'false', FILTER_VALIDATE_BOOLEAN);
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

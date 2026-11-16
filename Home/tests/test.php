<?php
$connection_type = "sqlsrv";

require_once realpath($_SERVER['DOCUMENT_ROOT'] . "/common/autoload.php");
use Config\DbMapper;

switch ($connection_type) {
    case "sqlsrv":
        $dbMapper = new DbMapper();
        $pdo = $dbMapper->getPdo(['IDCC']);
        break;
    case "dblib":
        $dsn = sprintf("dblib:host=%s;dbname=%s", getenv("HOST"), getenv("DEMO_IDCC_DB"));

        // deprecated - too modern
        // $dsn = sprintf("sqlsrv:server=%s;Database=%s", $env['host'], $env['db']);

        $pdo = new PDO($dsn, getenv("UID"), getenv("PWD"), [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        break;
    default:
        break;
}

// var_dump($pdo);

$sql_2 = 'SELECT TOP 1 * FROM DyeMachines';

// try {
//     $stmt_2 = $pdo->prepare($sql_2);
//     $stmt_2->execute($params);


// } catch (PDOException $e) {
//     die("Database query failed: " . $e->getMessage());
// }

// $stmt_2->execute($params);
// $rows = $stmt_2->fetchAll(PDO::FETCH_ASSOC);

try {
    $stmt_1 = $pdo->prepare($sql_2);
    $stmt_2 = $pdo->prepare($sql_2);
    $stmt_2->execute();
    // $rows = $stmt_2->fetchAll(PDO::FETCH_ASSOC);

    // echo "<br><br>";
    // print_r($rows);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}

?>
<html>
something in between
<br>

</html>
<?php
echo "######<br><br>";


$stmt_1->execute();
$rows = $stmt_1->fetchAll(PDO::FETCH_ASSOC);
print_r($rows);

echo "@@@@@@<br><br>";

// $stmt_2->execute($params);
$rows = $stmt_2->fetchAll(PDO::FETCH_ASSOC);
print_r($rows);
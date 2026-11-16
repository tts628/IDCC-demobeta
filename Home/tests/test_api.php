<?php

echo "######<br><br>";


    $stmt_2->execute($params);
$rows = $stmt_2->fetchAll(PDO::FETCH_ASSOC);
print_r($rows);
<?php
try {
    // Replace with your database details
    $dsn = sprintf("sqlsrv:server=%s;Database=%s", "logicartidcc.website", "NWVN_BDC_Small");
    $username = 'SA';
    $password = 'La84160206';

    // Establishing the PDO connection
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // echo "Database connection successful.";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}

try {
    // SQL query
    $sql = "SELECT TOP (1000) [Dyelot]
      ,[ReDye]
      ,[Machine]
	  ,Program
  FROM [NWVN_BDC_Small].[dbo].[Dyelots]
  WHERE [ModifyProgramRequest] = 1
";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    // Bind parameters
    // $stmt->bindParam(':value', $value, PDO::PARAM_STR);

    // Set the value and execute the query
    // $value = 'some_value';
    $stmt->execute();

    // Fetch the results
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output the results
    // print_r($result);
    foreach ($result as $row) {
        echo "$row[Dyelot]:<br>";
        foreach ($row as $key => $value) {
            // echo "$key: " . $value . "<br>";

            // Use regex to match the full number-quoted string pair
            preg_match_all('/(\d+)\s*"([^"]*?)"/s', $value, $matches);

            $res = [];
            // Combine the full pairs into an associative array
            foreach ($matches[1] as $key => $number) {
                // Reconstruct the full format with the number and quoted string
                $res[$number] = $matches[0][$key];
            }

            foreach ($res as $key => $value) {
                echo $value . "<br><br>";
            }
        }
    }
} catch (PDOException $e) {
    echo "Query failed: " . $e->getMessage();
}

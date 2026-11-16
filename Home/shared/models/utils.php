<?php
function bindParams(PDOStatement &$stmt, array $params)
{
    foreach ($params as $placeholder => &$value) {
        // Determine the type of the value
        $type = PDO::PARAM_STR; // Default to string
        if (is_int($value)) {
            $type = PDO::PARAM_INT;
        } elseif (is_bool($value)) {
            $type = PDO::PARAM_BOOL;
        } elseif (is_null($value)) {
            $type = PDO::PARAM_NULL;
        }

        // Bind the parameter as a reference
        $stmt->bindParam(':' . $placeholder, $value, $type);
    }
}

function bindValues(PDOStatement &$stmt, array $params)
{
    foreach ($params as $placeholder => $value) {
        // Determine the type of the value
        $type = PDO::PARAM_STR; // Default to string
        if (is_int($value)) {
            $type = PDO::PARAM_INT;
        } elseif (is_bool($value)) {
            $type = PDO::PARAM_BOOL;
        } elseif (is_null($value)) {
            $type = PDO::PARAM_NULL;
        }

        // Bind the value directly
        $stmt->bindValue(':' . $placeholder, $value, $type);
    }
}

function convertDateTimeString($value)
{
    // Check if the value matches the format 'YYYY-MM-DDTHH:MM'
    if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $value)) {
        // Replace 'T' with a space and add ':00' for seconds
        return str_replace('T', ' ', $value) . ":00";
    }
    return $value; // Return the original value if no conversion is needed
}

class sharedUtils
{
    public function getCurrentURI() {
        $requestURI = $_SERVER['REQUEST_URI'];
        return substr($requestURI, 0, strrpos($requestURI, '/'));
    }
}
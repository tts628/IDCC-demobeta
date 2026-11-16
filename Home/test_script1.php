<?php
// No declare(strict_types=1);

function processNumber(int $number): int {
    return $number * 2;
}

echo processNumber('3');    // Outputs 6
echo processNumber('abc');  // Outputs 0 (no error)

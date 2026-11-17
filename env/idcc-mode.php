<?php
function idccDemoMode(): bool
{
    $value = getenv('IDCC_DEMO_MODE');
    if ($value === false || $value === '') return true;
    $mode = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    if ($mode === null) throw new \RuntimeException('IDCC_DEMO_MODE must be true or false.');
    return $mode;
}

<?php
// Default to the existing SQL Server connection until migration is complete.
require_once __DIR__ . '/idcc-mode.php';
if (idccDemoMode()) {
    require __DIR__ . '/database_IDCC_DEMO.php';
} else {
    require __DIR__ . '/database_IDCC_POSTGRES.php';
}

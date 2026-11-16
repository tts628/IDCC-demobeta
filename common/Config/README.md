# Legacy database adapter

`DbMapper` uses this DemoBeta project's `env/database_IDCC.php` and `env/database_SPC.php` without modifying their credentials or database settings. `getLink()` returns the procedural connection needed by existing `sqlsrv_query()` calls; `getPdo()` returns the configured PDO connection.

Other deployments' client maps, environment variables, databases, and credentials are not imported. Only IDCC and SPC are supported because those are the configuration files provided by this project. Actual database connectivity still depends on the target computer's network and env settings.

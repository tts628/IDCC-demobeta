<?php
namespace Config;

/** Bridge legacy callers to this project's existing env/database_*.php files. */
class DbMapper
{
    private static array $connections = [];

    private function load(array $options): array
    {
        $database = strtoupper((string) ($options[0] ?? ''));
        $files = ['IDCC' => 'database_IDCC.php', 'SPC' => 'database_SPC.php'];
        if (!isset($files[$database])) {
            throw new \InvalidArgumentException('Supported database configurations are IDCC and SPC.');
        }
        if (!isset(self::$connections[$database])) {
            $file = dirname(__DIR__, 2) . '/env/' . $files[$database];
            if (!is_file($file)) {
                throw new \RuntimeException('Missing project database configuration: env/' . $files[$database]);
            }
            // Keep configuration variables local; preserve the original config file.
            self::$connections[$database] = (static function (string $file): array {
                require $file;
                return [
                    'link' => $conn ?? null,
                    'pdo' => $PDOconn ?? null,
                    'server' => $serverName ?? null,
                    'options' => $connectionInfo ?? null,
                ];
            })($file);
        }
        return self::$connections[$database];
    }

    public function getLink(array $connectOption)
    {
        $config = $this->load($connectOption);
        if (in_array('ReturnDatesAsStrings', $connectOption, true)) {
            if (!$config['server'] || !is_array($config['options'])) {
                throw new \RuntimeException('The environment does not expose SQL Server connection options.');
            }
            $options = $config['options'];
            $options['ReturnDatesAsStrings'] = true;
            $link = sqlsrv_connect($config['server'], $options);
        } else {
            $link = $config['link'];
        }
        if (!$link) {
            throw new \RuntimeException('SQL Server connection is unavailable. Check database access and project env settings.');
        }
        // Legacy sqlsrv_query callers need the procedural connection, not PDO.
        return $link;
    }

    public function getPdo(array $connectOption): \PDO
    {
        $config = $this->load($connectOption);
        if (!($config['pdo'] instanceof \PDO)) {
            throw new \RuntimeException('PDO SQL Server connection is unavailable. Check project env settings.');
        }
        return $config['pdo'];
    }
}

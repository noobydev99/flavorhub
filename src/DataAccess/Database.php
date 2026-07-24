<?php
namespace FlavorHub\DataAccess;

use PDO;
use PDOException;
use Exception;

/**
 * Database Connection Manager (Data Access Layer)
 * Manages a single PDO instance for database interactions.
 */
class Database {
    private static ?PDO $instance = null;

    /**
     * Get the PDO connection instance.
     *
     * @return PDO
     * @throws Exception
     */
    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $configPath = __DIR__ . '/../../config/database.php';
            if (!file_exists($configPath)) {
                throw new Exception("Configuration file not found at: " . $configPath);
            }

            $config = require $configPath;
            $port = isset($config['port']) ? ";port={$config['port']}" : '';
            $dsn = "mysql:host={$config['host']}{$port};dbname={$config['dbname']};charset={$config['charset']}";

            try {
                self::$instance = new PDO(
                    $dsn,
                    $config['username'],
                    $config['password'],
                    $config['options']
                );
            } catch (PDOException $e) {
                // Log errors in integration layer if available, otherwise throw
                throw new Exception("Database Connection Error: " . $e->getMessage(), (int)$e->getCode());
            }
        }
        return self::$instance;
    }
}

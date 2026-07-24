<?php
/**
 * FlavorHub Database Setup Script
 * Creates database and imports schema if needed
 */
require_once __DIR__ . '/config/autoload.php';

use FlavorHub\DataAccess\Database;

echo "=== FlavorHub Database Setup Wizard ===\n\n";

// Configuration
$config = require __DIR__ . '/config/database.php';
$dbname = $config['dbname'];
$host = $config['host'];
$username = $config['username'];
$password = $config['password'];

// Step 1: Connect to MySQL server (without selecting specific database)
echo "Step 1: Connecting to MySQL Server...\n";
try {
    $dsn = "mysql:host=$host;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo "✅ Connected to MySQL server\n\n";
} catch (PDOException $e) {
    echo "❌ Failed to connect to MySQL\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "\nMake sure:\n";
    echo "1. XAMPP MySQL service is running\n";
    echo "2. Username is 'root'\n";
    echo "3. Password is empty or correct\n";
    exit(1);
}

// Step 2: Create database if it doesn't exist
echo "Step 2: Creating/Checking Database...\n";
try {
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Database '$dbname' is ready\n\n";
} catch (PDOException $e) {
    echo "❌ Failed to create database\n";
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

// Step 3: Select database and run schema
echo "Step 3: Creating Tables and Importing Schema...\n";
try {
    $pdo->exec("USE `$dbname`");
    
    // Read and execute database.sql
    $sqlFile = __DIR__ . '/database.sql';
    if (!file_exists($sqlFile)) {
        echo "⚠️  Warning: database.sql file not found\n";
        echo "Location: $sqlFile\n";
        echo "\nPlease import the database schema manually:\n";
        echo "1. Open phpMyAdmin at http://localhost/phpmyadmin\n";
        echo "2. Select database 'my_project'\n";
        echo "3. Go to Import tab\n";
        echo "4. Upload database.sql file\n";
    } else {
        $sql = file_get_contents($sqlFile);
        
        // Split SQL statements (basic approach)
        $statements = array_filter(
            array_map('trim', preg_split('/;[\r\n]/', $sql)),
            function($s) { return !empty($s) && !preg_match('/^--/', $s); }
        );
        
        $executedCount = 0;
        foreach ($statements as $statement) {
            if (!empty($statement)) {
                try {
                    $pdo->exec($statement . ';');
                    $executedCount++;
                } catch (PDOException $e) {
                    // Skip if table already exists
                    if (strpos($e->getMessage(), 'already exists') === false) {
                        echo "Warning: " . $e->getMessage() . "\n";
                    }
                }
            }
        }
        
        echo "✅ Schema imported successfully ($executedCount statements executed)\n\n";
    }
} catch (Exception $e) {
    echo "❌ Error during schema import\n";
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

// Step 4: Verify connection with proper database
echo "Step 4: Verifying Database Connection...\n";
try {
    $db = Database::getConnection();
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    
    echo "✅ Database verified\n";
    echo "✅ Found " . count($tables) . " tables\n\n";
    
    // List tables
    echo "Tables created:\n";
    foreach ($tables as $table) {
        echo "  - $table\n";
    }
} catch (Exception $e) {
    echo "❌ Connection verification failed\n";
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n=== Setup Complete! ✅ ===\n";
echo "\nYour FlavorHub database is now ready to use!\n";
echo "\nNext steps:\n";
echo "1. Visit http://localhost/my%20project/admin/dashboard.php\n";
echo "2. Login with:\n";
echo "   Email: admin@flavorhub.com\n";
echo "   Password: admin123\n";
?>

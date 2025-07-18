<?php
/**
 * Simple test file to check if PHP is working
 * Access this file directly: your-domain.com/test.php
 */

echo "<!DOCTYPE html>";
echo "<html><head><title>PHP Test</title></head><body>";
echo "<h1>PHP Test Page</h1>";
echo "<p>✅ PHP is working!</p>";
echo "<p>📅 Current date: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>🔧 PHP Version: " . phpversion() . "</p>";

// Test basic file paths
echo "<h2>File System Check:</h2>";
echo "<p>Current directory: " . __DIR__ . "</p>";
echo "<p>Config directory exists: " . (is_dir(__DIR__ . '/config') ? '✅ Yes' : '❌ No') . "</p>";
echo "<p>Backend directory exists: " . (is_dir(__DIR__ . '/backend') ? '✅ Yes' : '❌ No') . "</p>";

// Test config file
echo "<h2>Configuration Check:</h2>";
$configFile = __DIR__ . '/config/config.php';
if (file_exists($configFile)) {
    echo "<p>✅ config.php exists</p>";
    try {
        include_once $configFile;
        echo "<p>✅ Config loaded successfully</p>";
        echo "<p>Database: " . (defined('DB_NAME') ? DB_NAME : 'Not defined') . "</p>";
        echo "<p>Site URL: " . (defined('SITE_URL') ? SITE_URL : 'Not defined') . "</p>";
    } catch (Exception $e) {
        echo "<p>❌ Config error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>❌ config.php not found</p>";
}

// Test database connection
echo "<h2>Database Connection Test:</h2>";
if (defined('DB_HOST') && defined('DB_NAME') && defined('DB_USER') && defined('DB_PASS')) {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        echo "<p>✅ Database connection successful</p>";
        
        // Test a simple query
        $stmt = $pdo->query("SELECT 1 as test");
        $result = $stmt->fetch();
        echo "<p>✅ Database query test: " . $result['test'] . "</p>";
        
    } catch (PDOException $e) {
        echo "<p>❌ Database error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>❌ Database configuration missing</p>";
}

echo "</body></html>";
?>

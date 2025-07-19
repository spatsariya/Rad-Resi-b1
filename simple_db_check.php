<?php
/**
 * Simple Database Check - No Config Dependencies
 * This script will help us identify the database issue
 */

// Start error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Simple Database Diagnostic</h2>";
echo "<pre>";

echo "1. Checking file structure...\n";
echo "Current directory: " . __DIR__ . "\n";
echo "Script location: " . __FILE__ . "\n\n";

// Check for config files
$possibleConfigs = [
    'backend/config/config.php',
    'config/config.php', 
    'backend/config.php',
    'config.php'
];

echo "2. Looking for config files...\n";
foreach ($possibleConfigs as $configPath) {
    if (file_exists($configPath)) {
        echo "✓ Found: $configPath\n";
        try {
            require_once $configPath;
            echo "✓ Config loaded successfully\n";
            break;
        } catch (Exception $e) {
            echo "✗ Error loading config: " . $e->getMessage() . "\n";
        }
    } else {
        echo "✗ Not found: $configPath\n";
    }
}

echo "\n3. Checking for Database class...\n";
$possibleDbClasses = [
    'backend/core/Database.php',
    'core/Database.php',
    'backend/Database.php',
    'Database.php'
];

foreach ($possibleDbClasses as $dbPath) {
    if (file_exists($dbPath)) {
        echo "✓ Found: $dbPath\n";
        try {
            require_once $dbPath;
            echo "✓ Database class loaded successfully\n";
            break;
        } catch (Exception $e) {
            echo "✗ Error loading Database class: " . $e->getMessage() . "\n";
        }
    } else {
        echo "✗ Not found: $dbPath\n";
    }
}

echo "\n4. Checking if constants are defined...\n";
$requiredConstants = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS'];
foreach ($requiredConstants as $constant) {
    if (defined($constant)) {
        echo "✓ $constant is defined\n";
    } else {
        echo "✗ $constant is NOT defined\n";
    }
}

echo "\n5. Attempting database connection...\n";
if (class_exists('Database')) {
    try {
        $db = Database::getInstance();
        $pdo = $db->connect();
        echo "✓ Database connection successful!\n";
        
        echo "\n6. Checking for notes_chapters table...\n";
        $stmt = $pdo->query("SHOW TABLES LIKE 'notes_chapters'");
        $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($tables)) {
            echo "✗ notes_chapters table does NOT exist!\n";
            echo "\nYou need to create the table. Here's the SQL:\n\n";
            echo "CREATE TABLE notes_chapters (\n";
            echo "    id INT AUTO_INCREMENT PRIMARY KEY,\n";
            echo "    chapter_name VARCHAR(255) NOT NULL,\n";
            echo "    sub_chapter_name VARCHAR(255) DEFAULT '',\n";
            echo "    parent_id INT DEFAULT NULL,\n";
            echo "    description TEXT,\n";
            echo "    thumbnail_image VARCHAR(500) DEFAULT '',\n";
            echo "    display_order INT DEFAULT 0,\n";
            echo "    status ENUM('active', 'inactive') DEFAULT 'active',\n";
            echo "    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,\n";
            echo "    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP\n";
            echo ");\n\n";
        } else {
            echo "✓ notes_chapters table exists!\n";
            
            // Check table structure
            $stmt = $pdo->query("DESCRIBE notes_chapters");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "\nTable columns:\n";
            $hasParentId = false;
            foreach ($columns as $column) {
                echo "  - {$column['Field']} ({$column['Type']})\n";
                if ($column['Field'] === 'parent_id') {
                    $hasParentId = true;
                }
            }
            
            if (!$hasParentId) {
                echo "\n⚠ Missing parent_id column! Add it with:\n";
                echo "ALTER TABLE notes_chapters ADD COLUMN parent_id INT DEFAULT NULL AFTER sub_chapter_name;\n\n";
            }
            
            // Check record count
            $stmt = $pdo->query("SELECT COUNT(*) as count FROM notes_chapters");
            $count = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "\nTotal records: {$count['count']}\n";
        }
        
    } catch (Exception $e) {
        echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    }
} else {
    echo "✗ Database class not available\n";
}

echo "\n=== DIAGNOSIS COMPLETE ===\n";
echo "</pre>";
?>

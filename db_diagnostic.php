<?php
/**
 * Database Diagnostic Script
 * Run this to check database connectivity and table structure
 */

// Start error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include necessary files
require_once 'backend/config/config.php';
require_once 'backend/core/Database.php';

echo "<h2>Database Diagnostic Report</h2>";
echo "<pre>";

try {
    echo "1. Testing Database Connection...\n";
    $db = Database::getInstance();
    $pdo = $db->connect();
    echo "✓ Database connection successful!\n\n";
    
    echo "2. Checking if notes_chapters table exists...\n";
    $stmt = $pdo->query("SHOW TABLES LIKE 'notes_chapters'");
    $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($tables)) {
        echo "✗ notes_chapters table does NOT exist!\n";
        echo "You need to create the table first. Here's the SQL:\n\n";
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
        echo "    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,\n";
        echo "    INDEX idx_parent_id (parent_id),\n";
        echo "    INDEX idx_status (status),\n";
        echo "    INDEX idx_display_order (display_order)\n";
        echo ");\n\n";
    } else {
        echo "✓ notes_chapters table exists!\n\n";
        
        echo "3. Checking table structure...\n";
        $stmt = $pdo->query("DESCRIBE notes_chapters");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "Table columns:\n";
        foreach ($columns as $column) {
            echo "  - {$column['Field']} ({$column['Type']}) ";
            if ($column['Null'] === 'NO') echo "[NOT NULL] ";
            if ($column['Key'] === 'PRI') echo "[PRIMARY KEY] ";
            if ($column['Default'] !== null) echo "[DEFAULT: {$column['Default']}] ";
            echo "\n";
        }
        
        // Check specifically for parent_id column
        $hasParentId = false;
        foreach ($columns as $column) {
            if ($column['Field'] === 'parent_id') {
                $hasParentId = true;
                break;
            }
        }
        
        if ($hasParentId) {
            echo "\n✓ parent_id column exists (hierarchical support enabled)\n";
        } else {
            echo "\n⚠ parent_id column is missing. Add it with:\n";
            echo "ALTER TABLE notes_chapters ADD COLUMN parent_id INT DEFAULT NULL AFTER sub_chapter_name;\n";
            echo "ALTER TABLE notes_chapters ADD INDEX idx_parent_id (parent_id);\n";
        }
        
        echo "\n4. Checking data in table...\n";
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM notes_chapters");
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Total records: {$count['count']}\n";
        
        if ($count['count'] > 0) {
            echo "\nSample records:\n";
            $stmt = $pdo->query("SELECT id, chapter_name, sub_chapter_name, parent_id, status FROM notes_chapters LIMIT 5");
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($records as $record) {
                echo "  ID: {$record['id']}, Chapter: '{$record['chapter_name']}', Sub: '{$record['sub_chapter_name']}', Parent: {$record['parent_id']}, Status: {$record['status']}\n";
            }
        }
        
        echo "\n5. Testing NotesChapter model methods...\n";
        require_once 'backend/models/NotesChapter.php';
        $notesModel = new NotesChapter();
        
        // Test checkTable
        $tableExists = $notesModel->checkTable();
        echo "checkTable() result: " . ($tableExists ? "TRUE" : "FALSE") . "\n";
        
        // Test getMainChapters
        try {
            $mainChapters = $notesModel->getMainChapters();
            echo "getMainChapters() result: " . count($mainChapters) . " chapters found\n";
        } catch (Exception $e) {
            echo "getMainChapters() ERROR: " . $e->getMessage() . "\n";
        }
        
        // Test findById with first record
        if ($count['count'] > 0) {
            $stmt = $pdo->query("SELECT id FROM notes_chapters LIMIT 1");
            $firstRecord = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($firstRecord) {
                try {
                    $chapter = $notesModel->findById($firstRecord['id']);
                    echo "findById({$firstRecord['id']}) result: " . ($chapter ? "SUCCESS" : "NULL") . "\n";
                } catch (Exception $e) {
                    echo "findById({$firstRecord['id']}) ERROR: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "</pre>";
?>

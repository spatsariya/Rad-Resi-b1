<?php
/**
 * Create Notes Chapters Table
 * Run this ONLY if the diagnostic shows the table is missing
 */

// Include necessary files
require_once 'backend/config/config.php';
require_once 'backend/core/Database.php';

echo "<h2>Notes Chapters Table Creation</h2>";
echo "<pre>";

try {
    echo "Connecting to database...\n";
    $db = Database::getInstance();
    $pdo = $db->connect();
    echo "✓ Connected successfully!\n\n";
    
    echo "Creating notes_chapters table...\n";
    
    $sql = "CREATE TABLE IF NOT EXISTS notes_chapters (
        id INT AUTO_INCREMENT PRIMARY KEY,
        chapter_name VARCHAR(255) NOT NULL,
        sub_chapter_name VARCHAR(255) DEFAULT '',
        parent_id INT DEFAULT NULL,
        description TEXT,
        thumbnail_image VARCHAR(500) DEFAULT '',
        display_order INT DEFAULT 0,
        status ENUM('active', 'inactive') DEFAULT 'active',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_parent_id (parent_id),
        INDEX idx_status (status),
        INDEX idx_display_order (display_order)
    )";
    
    $pdo->exec($sql);
    echo "✓ Table created successfully!\n\n";
    
    // Verify table creation
    $stmt = $pdo->query("SHOW TABLES LIKE 'notes_chapters'");
    $tables = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (!empty($tables)) {
        echo "✓ Table verification successful!\n";
        
        // Show table structure
        echo "\nTable structure:\n";
        $stmt = $pdo->query("DESCRIBE notes_chapters");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($columns as $column) {
            echo "  - {$column['Field']} ({$column['Type']})";
            if ($column['Null'] === 'NO') echo " [NOT NULL]";
            if ($column['Key'] === 'PRI') echo " [PRIMARY KEY]";
            if ($column['Default'] !== null) echo " [DEFAULT: {$column['Default']}]";
            echo "\n";
        }
        
        echo "\n✓ Setup complete! You can now use the notes chapters feature.\n";
    } else {
        echo "✗ Table verification failed!\n";
    }
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "</pre>";
?>

<?php
/**
 * Database Update Script for Registration Form
 * Adds missing fields to support the registration form functionality
 */

require_once 'config/config.php';
require_once 'backend/bootstrap.php';

echo "<h2>Database Update for Registration Form</h2>";

try {
    $db = Database::getInstance()->connect();
    
    echo "<p>Connected to database: " . DB_NAME . "</p>";
    
    // List of updates to perform
    $updates = [
        [
            'name' => 'Add phone field',
            'sql' => "ALTER TABLE users ADD COLUMN phone VARCHAR(20) NULL AFTER email",
            'check' => "SHOW COLUMNS FROM users LIKE 'phone'"
        ],
        [
            'name' => 'Add institution field',
            'sql' => "ALTER TABLE users ADD COLUMN institution VARCHAR(255) NULL AFTER bio",
            'check' => "SHOW COLUMNS FROM users LIKE 'institution'"
        ],
        [
            'name' => 'Add newsletter field',
            'sql' => "ALTER TABLE users ADD COLUMN newsletter TINYINT(1) DEFAULT 0 AFTER email_verified",
            'check' => "SHOW COLUMNS FROM users LIKE 'newsletter'"
        ],
        [
            'name' => 'Update role enum to include user',
            'sql' => "ALTER TABLE users MODIFY COLUMN role ENUM('student','instructor','admin','user') DEFAULT 'user'",
            'check' => "SHOW COLUMNS FROM users LIKE 'role'"
        ],
        [
            'name' => 'Add profile_picture field',
            'sql' => "ALTER TABLE users ADD COLUMN profile_picture VARCHAR(255) NULL AFTER avatar",
            'check' => "SHOW COLUMNS FROM users LIKE 'profile_picture'"
        ]
    ];
    
    foreach ($updates as $update) {
        echo "<h3>{$update['name']}</h3>";
        
        // Check if the column already exists
        $checkResult = $db->query($update['check']);
        
        if ($checkResult->rowCount() > 0) {
            echo "<p>✅ Already exists - skipping</p>";
        } else {
            try {
                $db->exec($update['sql']);
                echo "<p>✅ Successfully added</p>";
            } catch (PDOException $e) {
                echo "<p>❌ Error: " . $e->getMessage() . "</p>";
            }
        }
    }
    
    // Add indexes for performance
    echo "<h3>Adding indexes</h3>";
    $indexes = [
        "CREATE INDEX IF NOT EXISTS idx_phone ON users(phone)",
        "CREATE INDEX IF NOT EXISTS idx_institution ON users(institution)",
        "CREATE INDEX IF NOT EXISTS idx_newsletter ON users(newsletter)"
    ];
    
    foreach ($indexes as $indexSql) {
        try {
            $db->exec($indexSql);
            echo "<p>✅ Index added successfully</p>";
        } catch (PDOException $e) {
            echo "<p>⚠️ Index may already exist: " . $e->getMessage() . "</p>";
        }
    }
    
    // Update existing users to have default role 'user'
    echo "<h3>Setting default role for existing users</h3>";
    try {
        $stmt = $db->prepare("UPDATE users SET role = 'user' WHERE role IS NULL OR role = ''");
        $stmt->execute();
        $affected = $stmt->rowCount();
        echo "<p>✅ Updated {$affected} users with default role</p>";
    } catch (PDOException $e) {
        echo "<p>❌ Error updating roles: " . $e->getMessage() . "</p>";
    }
    
    // Show updated table structure
    echo "<h3>Updated Table Structure</h3>";
    $structure = $db->query("DESCRIBE users")->fetchAll();
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($structure as $column) {
        echo "<tr>";
        echo "<td>{$column['Field']}</td>";
        echo "<td>{$column['Type']}</td>";
        echo "<td>{$column['Null']}</td>";
        echo "<td>{$column['Key']}</td>";
        echo "<td>{$column['Default']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>✅ Database update completed successfully!</h3>";
    echo "<p>You can now use the registration form with all fields.</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Database update failed: " . $e->getMessage() . "</p>";
}
?>

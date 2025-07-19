<?php
/**
 * Quick Database Test for Notes Management
 * Use this to diagnose the "Statement preparation failed" error
 */

// Include the configuration
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../backend/core/Database.php';

echo "<h2>🔍 Database Diagnosis for Notes Management</h2>";

try {
    $db = Database::getInstance();
    echo "<p style='color: green;'>✅ Database connection successful</p>";
    
    // Test 1: Check if notes_chapters table exists (required for foreign key)
    echo "<h3>📋 Step 1: Check Notes Chapters Table</h3>";
    try {
        $result = $db->fetchAll("SHOW TABLES LIKE 'notes_chapters'");
        if (!empty($result)) {
            echo "<p style='color: green;'>✅ notes_chapters table exists</p>";
            
            // Count chapters
            $count = $db->fetch("SELECT COUNT(*) as count FROM notes_chapters");
            echo "<p>📊 Total chapters: " . ($count['count'] ?? 0) . "</p>";
        } else {
            echo "<p style='color: red;'>❌ notes_chapters table does NOT exist</p>";
            echo "<p><strong>ERROR:</strong> The notes table depends on notes_chapters table.</p>";
            echo "<p>Please run the notes_chapters setup first:</p>";
            echo "<code>database/notes_chapters_schema.sql</code>";
            return;
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error checking notes_chapters: " . htmlspecialchars($e->getMessage()) . "</p>";
        return;
    }
    
    // Test 2: Check if notes table exists
    echo "<h3>📝 Step 2: Check Notes Table</h3>";
    try {
        $result = $db->fetchAll("SHOW TABLES LIKE 'notes'");
        if (!empty($result)) {
            echo "<p style='color: green;'>✅ notes table exists</p>";
            
            // Count notes
            $count = $db->fetch("SELECT COUNT(*) as count FROM notes");
            echo "<p>📊 Total notes: " . ($count['count'] ?? 0) . "</p>";
            
            // Test the Notes model
            echo "<h3>🧪 Step 3: Test Notes Model</h3>";
            require_once __DIR__ . '/../backend/models/Notes.php';
            
            $notesModel = new Notes();
            if ($notesModel->checkTable()) {
                echo "<p style='color: green;'>✅ Notes model checkTable() works</p>";
                
                // Test getStatistics
                try {
                    $stats = $notesModel->getStatistics();
                    echo "<p style='color: green;'>✅ getStatistics() works</p>";
                    echo "<ul>";
                    echo "<li>Total Notes: " . $stats['total_notes'] . "</li>";
                    echo "<li>Active Notes: " . $stats['active_notes'] . "</li>";
                    echo "<li>Premium Notes: " . $stats['premium_notes'] . "</li>";
                    echo "<li>Total Views: " . number_format($stats['total_views']) . "</li>";
                    echo "</ul>";
                } catch (Exception $e) {
                    echo "<p style='color: red;'>❌ getStatistics() failed: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
                
                // Test getChapters
                try {
                    $chapters = $notesModel->getChapters();
                    echo "<p style='color: green;'>✅ getChapters() works - found " . count($chapters) . " chapters</p>";
                } catch (Exception $e) {
                    echo "<p style='color: red;'>❌ getChapters() failed: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
                
                // Test getAll with minimal params
                try {
                    $notes = $notesModel->getAll('', '', '', '', 5, 0);
                    echo "<p style='color: green;'>✅ getAll() works - found " . count($notes) . " notes</p>";
                } catch (Exception $e) {
                    echo "<p style='color: red;'>❌ getAll() failed: " . htmlspecialchars($e->getMessage()) . "</p>";
                    echo "<p><strong>This is likely the source of your error!</strong></p>";
                    echo "<p>Error details: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
                
            } else {
                echo "<p style='color: red;'>❌ Notes model checkTable() failed</p>";
            }
            
        } else {
            echo "<p style='color: orange;'>⚠️ notes table does NOT exist</p>";
            echo "<p>The table needs to be created. Run the setup script:</p>";
            echo "<p><a href='setup_notes.php' style='background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🚀 Run Notes Setup</a></p>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error checking notes table: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    // Test 3: Show database structure
    echo "<h3>🗃️ Step 4: Database Structure</h3>";
    try {
        $tables = $db->fetchAll("SHOW TABLES");
        echo "<p>📋 Available tables in database:</p>";
        echo "<ul>";
        foreach ($tables as $table) {
            $tableName = array_values($table)[0];
            echo "<li>$tableName</li>";
        }
        echo "</ul>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error listing tables: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ <strong>Database connection failed:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<h3>💡 Troubleshooting Steps:</h3>";
    echo "<ul>";
    echo "<li>Check database credentials in config.php</li>";
    echo "<li>Verify database server is running</li>";
    echo "<li>Check database user permissions</li>";
    echo "<li>Ensure database exists</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<h3>🎯 Next Steps:</h3>";
echo "<ul>";
echo "<li>If notes table doesn't exist: <a href='setup_notes.php'>Run Setup Script</a></li>";
echo "<li>If all tests pass: Try accessing <a href='/admin/notes'>/admin/notes</a></li>";
echo "<li>If still getting errors: Check the specific error message above</li>";
echo "</ul>";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notes Database Diagnosis</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; }
        h2, h3 { color: #333; }
        code { background: #f5f5f5; padding: 2px 4px; border-radius: 3px; }
        ul { margin: 10px 0; }
        li { margin: 5px 0; }
    </style>
</head>
<body>
    <h1>🔧 Radiology Resident - Notes Diagnosis</h1>
    <p>This script diagnoses database issues with the Notes management system.</p>
    <hr>
</body>
</html>

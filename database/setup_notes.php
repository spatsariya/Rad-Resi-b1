<?php
/**
 * Database Setup Script for Notes Management
 * Run this file to create the notes table and populate sample data
 */

// Include the configuration
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../backend/core/Database.php';

try {
    $db = Database::getInstance();
    
    echo "<h2>Notes Table Setup</h2>";
    
    // Check if notes table exists
    $result = $db->fetchAll("SHOW TABLES LIKE 'notes'");
    if (!empty($result)) {
        echo "<p style='color: orange;'>⚠️ Notes table already exists. This will drop and recreate it.</p>";
        echo "<p><strong>WARNING:</strong> This will delete all existing notes data!</p>";
    }
    
    // Read and execute the SQL schema
    $sqlFile = __DIR__ . '/notes_schema.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("SQL file not found: $sqlFile");
    }
    
    $sql = file_get_contents($sqlFile);
    if ($sql === false) {
        throw new Exception("Could not read SQL file");
    }
    
    // Split SQL into individual statements
    $statements = explode(';', $sql);
    $successCount = 0;
    $errorCount = 0;
    
    echo "<h3>Executing SQL Statements:</h3>";
    echo "<div style='background: #f5f5f5; padding: 10px; border-radius: 5px; font-family: monospace;'>";
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        
        // Skip empty statements and comments
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }
        
        try {
            $db->query($statement);
            $successCount++;
            
            // Show what we're executing (first 50 chars)
            $preview = substr(str_replace(["\n", "\r", "\t"], ' ', $statement), 0, 50) . '...';
            echo "<span style='color: green;'>✅ " . htmlspecialchars($preview) . "</span><br>";
            
        } catch (Exception $e) {
            $errorCount++;
            $preview = substr(str_replace(["\n", "\r", "\t"], ' ', $statement), 0, 50) . '...';
            echo "<span style='color: red;'>❌ " . htmlspecialchars($preview) . " - Error: " . htmlspecialchars($e->getMessage()) . "</span><br>";
        }
    }
    
    echo "</div>";
    
    // Summary
    echo "<h3>Setup Summary:</h3>";
    echo "<ul>";
    echo "<li style='color: green;'>✅ Successful statements: $successCount</li>";
    if ($errorCount > 0) {
        echo "<li style='color: red;'>❌ Failed statements: $errorCount</li>";
    }
    echo "</ul>";
    
    // Verify table creation
    $result = $db->fetchAll("SHOW TABLES LIKE 'notes'");
    if (!empty($result)) {
        echo "<p style='color: green; font-weight: bold;'>✅ Notes table successfully created!</p>";
        
        // Count records
        $count = $db->fetch("SELECT COUNT(*) as count FROM notes");
        if (!empty($count)) {
            echo "<p>📊 Total notes inserted: " . $count['count'] . "</p>";
        }
        
        // Test the statistics view
        try {
            $stats = $db->fetch("SELECT * FROM notes_statistics");
            if (!empty($stats)) {
                echo "<h4>📈 Database Statistics:</h4>";
                echo "<ul>";
                echo "<li>Total Notes: " . $stats['total_notes'] . "</li>";
                echo "<li>Active Notes: " . $stats['active_notes'] . "</li>";
                echo "<li>Premium Notes: " . $stats['premium_notes'] . "</li>";
                echo "<li>Total Views: " . number_format($stats['total_views']) . "</li>";
                echo "<li>Chapters with Notes: " . $stats['chapters_with_notes'] . "</li>";
                echo "</ul>";
            }
        } catch (Exception $e) {
            echo "<p style='color: orange;'>⚠️ Statistics view error: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
        
    } else {
        echo "<p style='color: red; font-weight: bold;'>❌ Notes table creation failed!</p>";
    }
    
    echo "<hr>";
    echo "<h3>Next Steps:</h3>";
    echo "<ol>";
    echo "<li>✅ Visit <a href='/admin/notes' target='_blank'>/admin/notes</a> to test the notes management interface</li>";
    echo "<li>✅ The interface should now work without 'Statement preparation failed' errors</li>";
    echo "<li>✅ You can now create, edit, delete, and manage notes</li>";
    echo "<li>✅ All notes are linked to chapters from the notes_chapters table</li>";
    echo "</ol>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Setup Failed</h2>";
    echo "<p style='color: red;'><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Please check:</strong></p>";
    echo "<ul>";
    echo "<li>Database connection settings in config.php</li>";
    echo "<li>Database user permissions</li>";
    echo "<li>That the notes_chapters table exists (required for foreign key)</li>";
    echo "</ul>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Notes Database Setup</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 40px auto; padding: 20px; }
        h2, h3 { color: #333; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        code { background: #f5f5f5; padding: 2px 4px; border-radius: 3px; }
    </style>
</head>
<body>
    <h1>🔧 Radiology Resident - Notes Database Setup</h1>
    <p>This script sets up the notes table and populates it with sample data.</p>
    <hr>
</body>
</html>

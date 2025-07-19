<?php
// Simple migration script to add parent_id column
session_start();

// Check if user is logged in and has admin access
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
    die('Unauthorized access');
}

include '../config/config.php';
include '../backend/core/Database.php';

try {
    $db = Database::getInstance();
    
    // Check if parent_id column exists
    $columns = $db->query("SHOW COLUMNS FROM notes_chapters LIKE 'parent_id'");
    
    if (empty($columns)) {
        echo "<h2>Adding parent_id column to notes_chapters table...</h2>";
        
        // Add parent_id column
        $db->query("ALTER TABLE notes_chapters ADD COLUMN parent_id INT DEFAULT NULL AFTER sub_chapter_name");
        echo "<p>✅ Added parent_id column</p>";
        
        // Add index
        $db->query("ALTER TABLE notes_chapters ADD INDEX idx_parent_id (parent_id)");
        echo "<p>✅ Added index for parent_id</p>";
        
        // Add foreign key constraint
        try {
            $db->query("ALTER TABLE notes_chapters ADD CONSTRAINT fk_parent_chapter FOREIGN KEY (parent_id) REFERENCES notes_chapters(id) ON DELETE CASCADE");
            echo "<p>✅ Added foreign key constraint</p>";
        } catch (Exception $e) {
            echo "<p>⚠️ Warning: Could not add foreign key constraint: " . $e->getMessage() . "</p>";
        }
        
        echo "<h3>✅ Migration completed successfully!</h3>";
        echo "<p><a href='/admin/notes-chapters'>Go back to Notes Chapters</a></p>";
        
    } else {
        echo "<h2>✅ parent_id column already exists!</h2>";
        echo "<p>The database is already up to date.</p>";
        echo "<p><a href='/admin/notes-chapters'>Go back to Notes Chapters</a></p>";
    }
    
} catch (Exception $e) {
    echo "<h2>❌ Error:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>

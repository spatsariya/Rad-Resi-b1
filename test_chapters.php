<?php
// Test script to check chapters table and parent_id column

try {
    include 'config/config.php';
    include 'backend/core/Database.php';
    $db = Database::getInstance();
    
    echo "=== Table Structure ===\n";
    $result = $db->query('DESCRIBE notes_chapters');
    foreach($result as $row) {
        echo $row['Field'] . ' - ' . $row['Type'] . "\n";
    }
    
    echo "\n=== Existing Chapters ===\n";
    $chapters = $db->query('SELECT id, chapter_name, parent_id FROM notes_chapters ORDER BY id LIMIT 10');
    foreach($chapters as $chapter) {
        echo 'ID: ' . $chapter['id'] . ', Name: ' . $chapter['chapter_name'] . ', Parent: ' . ($chapter['parent_id'] ?? 'NULL') . "\n";
    }
    
    echo "\n=== Main Chapters (for dropdown) ===\n";
    $mainChapters = $db->query('SELECT id, chapter_name FROM notes_chapters WHERE parent_id IS NULL OR parent_id = 0 ORDER BY chapter_name');
    foreach($mainChapters as $chapter) {
        echo 'ID: ' . $chapter['id'] . ', Name: ' . $chapter['chapter_name'] . "\n";
    }
    
} catch(Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
    
    // If parent_id column doesn't exist, let's add it
    if (strpos($e->getMessage(), 'parent_id') !== false || strpos($e->getMessage(), "Unknown column") !== false) {
        echo "\nAttempting to add parent_id column...\n";
        try {
            $db->query('ALTER TABLE notes_chapters ADD COLUMN parent_id INT DEFAULT NULL AFTER sub_chapter_name');
            $db->query('ALTER TABLE notes_chapters ADD INDEX idx_parent_id (parent_id)');
            echo "parent_id column added successfully!\n";
        } catch(Exception $e2) {
            echo "Failed to add parent_id column: " . $e2->getMessage() . "\n";
        }
    }
}
?>

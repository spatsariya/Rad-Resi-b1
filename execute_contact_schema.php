<?php
require_once __DIR__ . '/config/config.php';

try {
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($mysqli->connect_error) {
        throw new Exception("Connection failed: " . $mysqli->connect_error);
    }
    
    echo "Connected to database successfully!\n";
    
    $sql = file_get_contents(__DIR__ . '/contact_management_schema.sql');
    
    // Execute multi-query
    if ($mysqli->multi_query($sql)) {
        do {
            // Store first result set
            if ($result = $mysqli->store_result()) {
                $result->free();
            }
        } while ($mysqli->next_result());
    }
    
    if ($mysqli->error) {
        throw new Exception("SQL Error: " . $mysqli->error);
    }
    
    echo "Contact management schema executed successfully!\n";
    
    $mysqli->close();
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>

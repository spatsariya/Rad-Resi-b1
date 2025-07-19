<?php
require_once __DIR__ . '/config/config.php';

try {
    $mysqli = new mysqli(
        $config['db']['host'], 
        $config['db']['username'], 
        $config['db']['password'], 
        $config['db']['database']
    );
    
    if ($mysqli->connect_error) {
        throw new Exception("Connection failed: " . $mysqli->connect_error);
    }
    
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

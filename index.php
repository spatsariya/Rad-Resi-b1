<?php
/**
 * Main Application Entry Point
 * This file handles all incoming requests
 */

// Include the bootstrap file
require_once __DIR__ . '/backend/bootstrap.php';

// Handle the request
try {
    $router->handleRequest();
} catch (Exception $e) {
    if (DEBUG_MODE) {
        echo "Application Error: " . $e->getMessage();
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    } else {
        // Log the error
        error_log("Application Error: " . $e->getMessage());
        
        // Show generic error page
        http_response_code(500);
        include __DIR__ . '/views/errors/500.php';
    }
}
?>

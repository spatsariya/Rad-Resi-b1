<?php
/**
 * Main Application Entry Point
 * This file handles all incoming requests
 */

// Error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if config file exists
$configFile = __DIR__ . '/config/config.php';
if (!file_exists($configFile)) {
    die("Configuration file not found. Please copy config.example.php to config.php and update the settings.");
}

// Include configuration
try {
    require_once $configFile;
} catch (Exception $e) {
    die("Configuration error: " . $e->getMessage());
}

// Check if bootstrap exists
$bootstrapFile = __DIR__ . '/backend/bootstrap.php';
if (!file_exists($bootstrapFile)) {
    die("Bootstrap file not found: " . $bootstrapFile);
}

// Include the bootstrap file
try {
    require_once $bootstrapFile;
} catch (Exception $e) {
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        echo "Bootstrap Error: " . $e->getMessage();
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    } else {
        die("Application initialization failed.");
    }
}

// Handle the request
try {
    if (isset($router)) {
        $router->handleRequest();
    } else {
        // Fallback to simple homepage
        include __DIR__ . '/backend/views/home/index.php';
    }
} catch (Exception $e) {
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        echo "Application Error: " . $e->getMessage();
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    } else {
        // Log the error
        error_log("Application Error: " . $e->getMessage());
        
        // Show generic error page
        http_response_code(500);
        $errorFile = __DIR__ . '/backend/views/errors/500.php';
        if (file_exists($errorFile)) {
            include $errorFile;
        } else {
            echo "An error occurred. Please try again later.";
        }
    }
}
?>

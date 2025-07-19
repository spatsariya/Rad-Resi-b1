<?php
// Simple test to see if contacts page loads
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start session
session_start();

// Mock admin session (for testing)
$_SESSION['user_id'] = 1;
$_SESSION['user_role'] = 'admin';
$_SESSION['user_name'] = 'Test Admin';
$_SESSION['user_email'] = 'admin@test.com';

echo "<h1>Contact Page Test</h1>";
echo "<p>Testing if contact page can load...</p>";

try {
    // Include config and bootstrap
    require_once __DIR__ . '/config/config.php';
    require_once __DIR__ . '/backend/bootstrap.php';
    
    echo "<p style='color: green;'>✓ Bootstrap loaded successfully</p>";
    
    // Try to create ContactController
    $controller = new ContactController();
    echo "<p style='color: green;'>✓ ContactController created successfully</p>";
    
    echo "<p><a href='/admin/contacts' target='_blank'>→ Try accessing Contact List page</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>

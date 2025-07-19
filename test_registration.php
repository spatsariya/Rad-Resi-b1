<?php
// Enable error reporting and logging
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');

// Test error logging
error_log("Test log entry at " . date('Y-m-d H:i:s'));

// Include bootstrap to test the full registration process
session_start();
require_once 'config/config.php';
require_once 'backend/bootstrap.php';

// Simulate the registration data
$_POST = [
    'csrf_token' => 'test_token_' . time(),
    'first_name' => 'Test',
    'last_name' => 'User',
    'email' => 'test_' . time() . '@example.com',
    'phone' => '1234567890',
    'password' => 'testpass123',
    'confirm_password' => 'testpass123',
    'specialization' => 'ENT',
    'experience_years' => '0',
    'institution' => 'Test Institution',
    'newsletter' => 'on',
    'terms' => 'on'
];

$_SESSION['csrf_token'] = $_POST['csrf_token'];

echo "<h2>Registration Process Test</h2>";

try {
    // Create AuthController and test registration
    $authController = new AuthController();
    
    echo "<p>AuthController created successfully</p>";
    
    // Test User model creation
    $user = new User();
    echo "<p>User model created successfully</p>";
    
    // Test creating user data (similar to registration process)
    $userData = [
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => 'test_direct_' . time() . '@example.com',
        'password' => password_hash('testpass123', PASSWORD_DEFAULT),
        'specialization' => 'ENT',
        'experience_years' => 0,
        'bio' => 'Test Institution',
        'role' => 'student',
        'status' => 'active',
        'email_verified' => 0,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    echo "<p>User data prepared</p>";
    
    $userId = $user->create($userData);
    
    if ($userId) {
        echo "<p>✅ Direct user creation successful! User ID: $userId</p>";
    } else {
        echo "<p>❌ Direct user creation failed</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace: " . $e->getTraceAsString() . "</p>";
}

// Check if error log file was created
if (file_exists(__DIR__ . '/error_log.txt')) {
    echo "<h3>Error Log Contents:</h3>";
    echo "<pre>" . file_get_contents(__DIR__ . '/error_log.txt') . "</pre>";
} else {
    echo "<p>No error log file created</p>";
}
?>

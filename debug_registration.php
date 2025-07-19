<?php
// Debug registration process
session_start();

// Include necessary files
require_once 'config/config.php';
require_once 'backend/bootstrap.php';

echo "<h2>Registration Debug</h2>";

// Test database connection
try {
    $db = Database::getInstance()->connect();
    echo "<p>✅ Database connection: OK</p>";
} catch (Exception $e) {
    echo "<p>❌ Database connection failed: " . $e->getMessage() . "</p>";
}

// Test User model
try {
    $user = new User();
    echo "<p>✅ User model: OK</p>";
} catch (Exception $e) {
    echo "<p>❌ User model failed: " . $e->getMessage() . "</p>";
}

// Check if users table exists
try {
    $db = Database::getInstance()->connect();
    $result = $db->query("SHOW TABLES LIKE 'users'");
    if ($result->rowCount() > 0) {
        echo "<p>✅ Users table: EXISTS</p>";
        
        // Show table structure
        $structure = $db->query("DESCRIBE users")->fetchAll();
        echo "<h3>Users Table Structure:</h3><pre>";
        foreach ($structure as $column) {
            echo $column['Field'] . " - " . $column['Type'] . " - " . $column['Null'] . " - " . $column['Key'] . "\n";
        }
        echo "</pre>";
    } else {
        echo "<p>❌ Users table: NOT FOUND</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Table check failed: " . $e->getMessage() . "</p>";
}

// Test basic user creation (with minimal data)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_create'])) {
    try {
        $user = new User();
        $testData = [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test_' . time() . '@example.com',
            'password' => password_hash('testpass', PASSWORD_DEFAULT),
            'specialization' => 'Test',
            'role' => 'user',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $userId = $user->create($testData);
        if ($userId) {
            echo "<p>✅ Test user creation: SUCCESS (ID: $userId)</p>";
        } else {
            echo "<p>❌ Test user creation: FAILED</p>";
        }
    } catch (Exception $e) {
        echo "<p>❌ Test user creation error: " . $e->getMessage() . "</p>";
    }
}
?>

<form method="POST">
    <button type="submit" name="test_create">Test User Creation</button>
</form>

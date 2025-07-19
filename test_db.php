<?php
// Test database connection and table existence
try {
    require_once 'config/config.php';
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "Database connection: OK\n";
    
    // Check if subscriptions table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'subscriptions'");
    if ($stmt->rowCount() > 0) {
        echo "Subscriptions table: EXISTS\n";
        
        // Test query
        $stmt = $pdo->query('SELECT COUNT(*) as count FROM subscriptions');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Subscription count: " . $result['count'] . "\n";
    } else {
        echo "Subscriptions table: NOT EXISTS\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>

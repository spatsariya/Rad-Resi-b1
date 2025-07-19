<?php
session_start();

// Debug session and CSRF token
echo "<h3>Session Debug Information</h3>";
echo "<p><strong>Session ID:</strong> " . session_id() . "</p>";
echo "<p><strong>Session Status:</strong> " . session_status() . "</p>";
echo "<p><strong>CSRF Token in Session:</strong> " . ($_SESSION['csrf_token'] ?? 'NOT SET') . "</p>";
echo "<p><strong>All Session Data:</strong></p>";
echo "<pre>" . print_r($_SESSION, true) . "</pre>";

// Generate new token if needed
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    echo "<p><strong>Generated New Token:</strong> " . $_SESSION['csrf_token'] . "</p>";
}

echo "<hr>";
echo "<h3>POST Data</h3>";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<pre>" . print_r($_POST, true) . "</pre>";
} else {
    echo "<p>No POST data</p>";
}

echo "<hr>";
echo "<h3>Test Form</h3>";
?>
<form method="POST" action="">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    <input type="text" name="test_field" placeholder="Test field">
    <button type="submit">Test Submit</button>
</form>

<?php
// Simple debug script to test routing
echo "<h1>URL Debug Information</h1>";
echo "<p><strong>REQUEST_URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p><strong>REQUEST_METHOD:</strong> " . $_SERVER['REQUEST_METHOD'] . "</p>";
echo "<p><strong>SCRIPT_NAME:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
echo "<p><strong>Parsed Path:</strong> " . $path . "</p>";

// Normalize path
if ($path !== '/' && substr($path, -1) === '/') {
    $path = rtrim($path, '/');
}
echo "<p><strong>Normalized Path:</strong> " . $path . "</p>";

// Check if this should match /admin
if ($path === '/admin') {
    echo "<p style='color: green;'><strong>✅ This should match the /admin route!</strong></p>";
} else {
    echo "<p style='color: red;'><strong>❌ This does NOT match the /admin route</strong></p>";
}

echo "<hr>";
echo "<h2>Available Routes Test</h2>";
echo "<ul>";
echo "<li><a href='/'>Homepage (/)</a></li>";
echo "<li><a href='/admin'>Admin (no slash)</a></li>";
echo "<li><a href='/admin/'>Admin (with slash)</a></li>";
echo "<li><a href='/login'>Login</a></li>";
echo "</ul>";
?>

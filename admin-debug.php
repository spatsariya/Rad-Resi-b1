<?php
session_start();
echo "<h2>Admin Debug Information</h2>";
echo "<strong>Current URL:</strong> " . $_SERVER['REQUEST_URI'] . "<br>";
echo "<strong>Request Method:</strong> " . $_SERVER['REQUEST_METHOD'] . "<br>";
echo "<strong>Session Data:</strong><br>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<strong>User Login Status:</strong> ";
if (isset($_SESSION['user_id'])) {
    echo "✅ Logged in as: " . ($_SESSION['user_name'] ?? 'Unknown') . " (Role: " . ($_SESSION['user_role'] ?? 'Unknown') . ")";
} else {
    echo "❌ Not logged in";
}

echo "<br><br><strong>Testing Admin Access:</strong><br>";
if (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
    echo "✅ Has admin/instructor privileges<br>";
    echo "<a href='/p/admin' style='color: blue;'>Try Admin Dashboard</a><br>";
    echo "<a href='/admin' style='color: blue;'>Try Direct Admin Route</a><br>";
} else {
    echo "❌ No admin privileges";
}

echo "<br><br><strong>Available Links:</strong><br>";
echo "<a href='/p/login'>Login Page</a><br>";
echo "<a href='/p/'>Homepage</a><br>";
echo "<a href='/p/admin'>Admin Dashboard</a><br>";
echo "<a href='/admin'>Direct Admin</a><br>";
?>

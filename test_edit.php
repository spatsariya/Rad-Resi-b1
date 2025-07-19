<?php
/**
 * Test Edit Endpoint
 * This will test the specific endpoint that's failing
 */

// Start session (needed for authentication)
session_start();

echo "<h2>Edit Endpoint Test</h2>";
echo "<pre>";

// Test the specific endpoint that's failing
echo "Testing edit endpoint...\n\n";

// Simulate the AJAX request
$testUrl = '/admin/notes-chapters/get-details?chapter_id=1';
echo "Testing URL: $testUrl\n\n";

// Try to include the necessary files to simulate the request
$possibleIndexFiles = [
    'index.php',
    'backend/index.php'
];

foreach ($possibleIndexFiles as $indexFile) {
    if (file_exists($indexFile)) {
        echo "Found index file: $indexFile\n";
        break;
    }
}

// Try to manually call the controller
$possibleControllers = [
    'backend/controllers/NotesChapterController.php',
    'controllers/NotesChapterController.php'
];

foreach ($possibleControllers as $controllerPath) {
    if (file_exists($controllerPath)) {
        echo "Found controller: $controllerPath\n";
        break;
    }
}

echo "\n=== Manual Test Instructions ===\n";
echo "1. Log into your admin panel\n";
echo "2. Go to the notes chapters page\n";
echo "3. Open browser developer tools (F12)\n";
echo "4. Go to Network tab\n";
echo "5. Click edit on any chapter\n";
echo "6. Look for the failed request to 'get-details'\n";
echo "7. Check what error it shows\n\n";

echo "Expected URL: /admin/notes-chapters/get-details?chapter_id=X\n";
echo "Expected response: JSON with chapter data\n";

echo "</pre>";
?>

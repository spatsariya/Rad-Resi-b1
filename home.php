<?php
/**
 * Simple fallback homepage
 * Use this if the main application has issues
 */

// Basic error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if we should use the simple version
$useSimple = isset($_GET['simple']) || !file_exists(__DIR__ . '/config/config.php');

if ($useSimple) {
    include __DIR__ . '/simple.php';
    exit;
}

// Try to load the full application
try {
    include __DIR__ . '/index.php';
} catch (Exception $e) {
    // If anything fails, show the simple version
    include __DIR__ . '/simple.php';
}
?>

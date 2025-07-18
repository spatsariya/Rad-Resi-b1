<?php
/**
 * Bootstrap file for the application
 * Initializes the application and loads required files
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuration should already be loaded by index.php
if (!defined('DB_HOST')) {
    throw new Exception("Configuration not loaded");
}

// Autoloader for classes
spl_autoload_register(function ($class) {
    $directories = [
        __DIR__ . '/core/',
        __DIR__ . '/models/',
        __DIR__ . '/controllers/',
        __DIR__ . '/middleware/',
        __DIR__ . '/utils/'
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Error handler
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    if (LOG_ERRORS) {
        $error = date('Y-m-d H:i:s') . " - Error [$errno]: $errstr in $errfile on line $errline" . PHP_EOL;
        file_put_contents(ERROR_LOG_FILE, $error, FILE_APPEND | LOCK_EX);
    }
    
    if (DEBUG_MODE) {
        echo "<b>Error [$errno]:</b> $errstr in <b>$errfile</b> on line <b>$errline</b><br>";
    }
    
    return true;
}

// Exception handler
function customExceptionHandler($exception) {
    if (LOG_ERRORS) {
        $error = date('Y-m-d H:i:s') . " - Exception: " . $exception->getMessage() . 
                 " in " . $exception->getFile() . " on line " . $exception->getLine() . PHP_EOL;
        file_put_contents(ERROR_LOG_FILE, $error, FILE_APPEND | LOCK_EX);
    }
    
    if (DEBUG_MODE) {
        echo "<b>Uncaught Exception:</b> " . $exception->getMessage() . 
             " in <b>" . $exception->getFile() . "</b> on line <b>" . $exception->getLine() . "</b><br>";
        echo "<pre>" . $exception->getTraceAsString() . "</pre>";
    } else {
        echo "An error occurred. Please try again later.";
    }
}

// Set error and exception handlers
set_error_handler('customErrorHandler');
set_exception_handler('customExceptionHandler');

// Create necessary directories
$directories = [
    'logs',
    'uploads',
    'temp',
    'cache'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Initialize database connection
try {
    $db = Database::getInstance();
    $db->connect();
} catch (Exception $e) {
    if (DEBUG_MODE) {
        die("Database initialization failed: " . $e->getMessage());
    } else {
        die("Application initialization failed. Please try again later.");
    }
}

// CSRF Protection
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

if (ENVIRONMENT === 'production') {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}

// Initialize Router
require_once __DIR__ . '/core/Router.php';
$router = new Router();
?>

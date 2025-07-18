<?php
/**
 * Configuration Example File
 * Copy this file to config.php and update with your actual values
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');
define('DB_CHARSET', 'utf8mb4');

// Site Configuration
define('SITE_URL', 'https://your-domain.com');
define('SITE_NAME', 'Radiology Resident');
define('SITE_DESCRIPTION', 'Medical Education Platform for Radiology Residents');

// Environment
define('ENVIRONMENT', 'development'); // development, staging, production

// Security
define('JWT_SECRET', 'your-very-long-random-secret-key-here');
define('ENCRYPTION_KEY', 'your-32-character-encryption-key');
define('CSRF_TOKEN_NAME', 'csrf_token');

// Email Configuration
define('SMTP_HOST', 'your-smtp-host');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@domain.com');
define('SMTP_PASSWORD', 'your-email-password');
define('FROM_EMAIL', 'noreply@your-domain.com');
define('FROM_NAME', 'Radiology Resident');

// File Upload
define('UPLOAD_MAX_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']);
define('UPLOAD_PATH', 'uploads/');

// Session Configuration
define('SESSION_LIFETIME', 3600); // 1 hour
define('SESSION_NAME', 'rad_resi_session');

// API Configuration
define('API_VERSION', 'v1');
define('API_RATE_LIMIT', 100); // requests per hour

// Debug Mode
define('DEBUG_MODE', true);
define('LOG_ERRORS', true);
define('ERROR_LOG_FILE', 'logs/error.log');

// Timezone
date_default_timezone_set('UTC');

// Error Reporting
if (ENVIRONMENT === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}
?>

<?php
/**
 * Database Schema Creator
 * This will create all necessary tables for Radiology Resident
 */

// Include configuration
require_once 'config/config.php';
require_once 'backend/core/Database.php';

echo "<!DOCTYPE html><html><head><title>Database Schema Setup</title>";
echo "<link href='https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css' rel='stylesheet'>";
echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'>";
echo "</head><body class='bg-gray-100 py-8'>";
echo "<div class='max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8'>";
echo "<div class='flex items-center mb-6'>";
echo "<i class='fas fa-database text-blue-500 text-2xl mr-3'></i>";
echo "<h1 class='text-3xl font-bold text-gray-900'>Database Schema Setup</h1>";
echo "</div>";

try {
    $db = Database::getInstance();
    $pdo = $db->connect();
    
    echo "<div class='bg-green-50 border border-green-200 rounded-lg p-4 mb-4'>";
    echo "<i class='fas fa-check-circle text-green-600 mr-2'></i>";
    echo "<span class='text-green-600 font-medium'>Database connection successful!</span>";
    echo "</div>";
    
    // Create tables
    $tables = [
        'users' => "CREATE TABLE IF NOT EXISTS `users` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `email` varchar(255) NOT NULL,
            `password` varchar(255) NOT NULL,
            `first_name` varchar(100) NOT NULL,
            `last_name` varchar(100) NOT NULL,
            `role` enum('student','instructor','admin') DEFAULT 'student',
            `avatar` varchar(255) DEFAULT NULL,
            `bio` text DEFAULT NULL,
            `specialization` varchar(100) DEFAULT NULL,
            `experience_years` int(2) DEFAULT NULL,
            `email_verified` tinyint(1) DEFAULT 0,
            `email_verification_token` varchar(255) DEFAULT NULL,
            `password_reset_token` varchar(255) DEFAULT NULL,
            `password_reset_expires` datetime DEFAULT NULL,
            `last_login` datetime DEFAULT NULL,
            `status` enum('active','inactive','suspended') DEFAULT 'active',
            `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `email` (`email`),
            KEY `role` (`role`),
            KEY `status` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        'course_categories' => "CREATE TABLE IF NOT EXISTS `course_categories` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(100) NOT NULL,
            `slug` varchar(100) NOT NULL,
            `description` text DEFAULT NULL,
            `icon` varchar(100) DEFAULT NULL,
            `color` varchar(7) DEFAULT NULL,
            `order_index` int(11) DEFAULT 0,
            `active` tinyint(1) DEFAULT 1,
            `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `slug` (`slug`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        'courses' => "CREATE TABLE IF NOT EXISTS `courses` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(255) NOT NULL,
            `slug` varchar(255) NOT NULL,
            `description` text NOT NULL,
            `short_description` varchar(500) DEFAULT NULL,
            `image` varchar(255) DEFAULT NULL,
            `instructor_id` int(11) NOT NULL,
            `category_id` int(11) DEFAULT NULL,
            `price` decimal(10,2) DEFAULT 0.00,
            `discounted_price` decimal(10,2) DEFAULT NULL,
            `duration` int(11) DEFAULT NULL COMMENT 'Duration in minutes',
            `difficulty_level` enum('beginner','intermediate','advanced') DEFAULT 'beginner',
            `featured` tinyint(1) DEFAULT 0,
            `status` enum('draft','active','inactive') DEFAULT 'draft',
            `order_index` int(11) DEFAULT 0,
            `rating` decimal(3,2) DEFAULT 0.00,
            `total_enrollments` int(11) DEFAULT 0,
            `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `slug` (`slug`),
            KEY `instructor_id` (`instructor_id`),
            KEY `category_id` (`category_id`),
            KEY `status` (`status`),
            KEY `featured` (`featured`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        'course_lessons' => "CREATE TABLE IF NOT EXISTS `course_lessons` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `course_id` int(11) NOT NULL,
            `title` varchar(255) NOT NULL,
            `description` text DEFAULT NULL,
            `content` longtext DEFAULT NULL,
            `video_url` varchar(500) DEFAULT NULL,
            `duration` int(11) DEFAULT NULL COMMENT 'Duration in minutes',
            `order_index` int(11) DEFAULT 0,
            `free_preview` tinyint(1) DEFAULT 0,
            `status` enum('draft','published') DEFAULT 'draft',
            `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `course_id` (`course_id`),
            KEY `status` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
        
        'enrollments' => "CREATE TABLE IF NOT EXISTS `enrollments` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(11) NOT NULL,
            `course_id` int(11) NOT NULL,
            `enrolled_at` timestamp DEFAULT CURRENT_TIMESTAMP,
            `completed_at` timestamp NULL DEFAULT NULL,
            `progress` decimal(5,2) DEFAULT 0.00,
            `status` enum('active','completed','dropped') DEFAULT 'active',
            PRIMARY KEY (`id`),
            UNIQUE KEY `user_course` (`user_id`, `course_id`),
            KEY `user_id` (`user_id`),
            KEY `course_id` (`course_id`),
            KEY `status` (`status`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    ];
    
    $success_count = 0;
    $error_count = 0;
    
    foreach ($tables as $table_name => $sql) {
        try {
            $pdo->exec($sql);
            echo "<div class='bg-green-50 border border-green-200 rounded-lg p-3 mb-2'>";
            echo "<i class='fas fa-check text-green-600 mr-2'></i>";
            echo "<span class='text-green-600'>Table '$table_name' created successfully</span>";
            echo "</div>";
            $success_count++;
        } catch (Exception $e) {
            echo "<div class='bg-red-50 border border-red-200 rounded-lg p-3 mb-2'>";
            echo "<i class='fas fa-times text-red-600 mr-2'></i>";
            echo "<span class='text-red-600'>Error creating table '$table_name': " . htmlspecialchars($e->getMessage()) . "</span>";
            echo "</div>";
            $error_count++;
        }
    }
    
    // Summary
    echo "<div class='bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6'>";
    echo "<h3 class='text-lg font-semibold text-blue-900 mb-2'>";
    echo "<i class='fas fa-chart-bar mr-2'></i>Setup Summary";
    echo "</h3>";
    echo "<p class='text-blue-800'>✅ Tables created successfully: $success_count</p>";
    if ($error_count > 0) {
        echo "<p class='text-red-600'>❌ Errors: $error_count</p>";
    }
    echo "</div>";
    
    if ($success_count > 0 && $error_count == 0) {
        echo "<div class='bg-green-50 border border-green-200 rounded-lg p-6 mt-6'>";
        echo "<h3 class='text-xl font-semibold text-green-900 mb-4'>";
        echo "<i class='fas fa-rocket mr-2'></i>Database Schema Complete!";
        echo "</h3>";
        echo "<p class='text-green-800 mb-4'>All tables have been created successfully. You can now:</p>";
        echo "<ol class='list-decimal list-inside space-y-2 text-green-800'>";
        echo "<li><a href='setup.php' class='text-blue-600 hover:underline font-medium'>Run the Setup Script</a> to create admin users and sample data</li>";
        echo "<li><a href='p/login' class='text-blue-600 hover:underline font-medium'>Access the Login Page</a></li>";
        echo "<li><a href='p/admin' class='text-blue-600 hover:underline font-medium'>Visit the Admin Dashboard</a></li>";
        echo "</ol>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='bg-red-50 border border-red-200 rounded-lg p-4 mb-4'>";
    echo "<i class='fas fa-exclamation-triangle text-red-600 mr-2'></i>";
    echo "<span class='text-red-600 font-medium'>Database connection failed:</span><br>";
    echo "<span class='text-red-600'>" . htmlspecialchars($e->getMessage()) . "</span>";
    echo "</div>";
}

echo "</div></body></html>";
?>

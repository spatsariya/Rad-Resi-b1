<?php
/**
 * Database Update Script for Users Table
 * Adds missing fields needed for the Users management page
 */

// Include configuration
require_once 'config/config.php';
require_once 'backend/core/Database.php';

echo "<!DOCTYPE html><html><head><title>Database Update - Users Table</title>";
echo "<link href='https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css' rel='stylesheet'>";
echo "<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css'>";
echo "</head><body class='bg-gray-100 py-8'>";
echo "<div class='max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8'>";
echo "<div class='flex items-center mb-6'>";
echo "<i class='fas fa-database text-blue-500 text-2xl mr-3'></i>";
echo "<h1 class='text-3xl font-bold text-gray-900'>Database Update - Users Table</h1>";
echo "</div>";

try {
    $db = Database::getInstance();
    $pdo = $db->connect();
    
    echo "<div class='bg-green-50 border border-green-200 rounded-lg p-4 mb-4'>";
    echo "<i class='fas fa-check-circle text-green-600 mr-2'></i>";
    echo "<span class='text-green-600 font-medium'>Database connection successful!</span>";
    echo "</div>";
    
    echo "<div class='space-y-4'>";
    
    // Check if phone field exists
    echo "<div class='bg-blue-50 border border-blue-200 rounded-lg p-4'>";
    echo "<h3 class='font-semibold text-blue-900 mb-2'>📱 Checking for phone field...</h3>";
    
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'phone'");
    $phoneExists = $stmt->rowCount() > 0;
    
    if (!$phoneExists) {
        echo "<p class='text-blue-700 mb-2'>Adding phone field to users table...</p>";
        $pdo->exec("ALTER TABLE users ADD COLUMN phone varchar(20) DEFAULT NULL AFTER email");
        echo "<p class='text-green-600'><i class='fas fa-check mr-1'></i> Phone field added successfully!</p>";
    } else {
        echo "<p class='text-green-600'><i class='fas fa-check mr-1'></i> Phone field already exists!</p>";
    }
    echo "</div>";
    
    // Check if profile_picture field exists (and add it alongside avatar)
    echo "<div class='bg-purple-50 border border-purple-200 rounded-lg p-4'>";
    echo "<h3 class='font-semibold text-purple-900 mb-2'>🖼️ Checking for profile_picture field...</h3>";
    
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'profile_picture'");
    $profilePictureExists = $stmt->rowCount() > 0;
    
    if (!$profilePictureExists) {
        echo "<p class='text-purple-700 mb-2'>Adding profile_picture field to users table...</p>";
        $pdo->exec("ALTER TABLE users ADD COLUMN profile_picture varchar(255) DEFAULT NULL AFTER avatar");
        echo "<p class='text-green-600'><i class='fas fa-check mr-1'></i> Profile picture field added successfully!</p>";
    } else {
        echo "<p class='text-green-600'><i class='fas fa-check mr-1'></i> Profile picture field already exists!</p>";
    }
    echo "</div>";
    
    // Update existing avatar data to profile_picture if needed
    echo "<div class='bg-orange-50 border border-orange-200 rounded-lg p-4'>";
    echo "<h3 class='font-semibold text-orange-900 mb-2'>🔄 Migrating avatar data to profile_picture...</h3>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE avatar IS NOT NULL AND profile_picture IS NULL");
    $result = $stmt->fetch();
    $avatarCount = $result['count'];
    
    if ($avatarCount > 0) {
        echo "<p class='text-orange-700 mb-2'>Found $avatarCount users with avatar data. Copying to profile_picture field...</p>";
        $pdo->exec("UPDATE users SET profile_picture = avatar WHERE avatar IS NOT NULL AND profile_picture IS NULL");
        echo "<p class='text-green-600'><i class='fas fa-check mr-1'></i> Avatar data migrated successfully!</p>";
    } else {
        echo "<p class='text-green-600'><i class='fas fa-check mr-1'></i> No avatar data migration needed!</p>";
    }
    echo "</div>";
    
    // Add index for phone field for better search performance
    echo "<div class='bg-indigo-50 border border-indigo-200 rounded-lg p-4'>";
    echo "<h3 class='font-semibold text-indigo-900 mb-2'>⚡ Adding database indexes for better performance...</h3>";
    
    try {
        $pdo->exec("ALTER TABLE users ADD INDEX idx_phone (phone)");
        echo "<p class='text-green-600'><i class='fas fa-check mr-1'></i> Phone index added successfully!</p>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "<p class='text-green-600'><i class='fas fa-check mr-1'></i> Phone index already exists!</p>";
        } else {
            echo "<p class='text-red-600'><i class='fas fa-exclamation-triangle mr-1'></i> Error adding phone index: " . $e->getMessage() . "</p>";
        }
    }
    
    try {
        $pdo->exec("ALTER TABLE users ADD INDEX idx_first_last_name (first_name, last_name)");
        echo "<p class='text-green-600'><i class='fas fa-check mr-1'></i> Name index added successfully!</p>";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
            echo "<p class='text-green-600'><i class='fas fa-check mr-1'></i> Name index already exists!</p>";
        } else {
            echo "<p class='text-red-600'><i class='fas fa-exclamation-triangle mr-1'></i> Error adding name index: " . $e->getMessage() . "</p>";
        }
    }
    echo "</div>";
    
    // Show final table structure
    echo "<div class='bg-gray-50 border border-gray-200 rounded-lg p-4'>";
    echo "<h3 class='font-semibold text-gray-900 mb-2'>📋 Updated Users Table Structure</h3>";
    echo "<div class='overflow-x-auto'>";
    echo "<table class='min-w-full text-sm'>";
    echo "<thead><tr class='bg-gray-100'><th class='px-3 py-2 text-left'>Field</th><th class='px-3 py-2 text-left'>Type</th><th class='px-3 py-2 text-left'>Null</th><th class='px-3 py-2 text-left'>Default</th></tr></thead>";
    echo "<tbody>";
    
    $stmt = $pdo->query("DESCRIBE users");
    while ($row = $stmt->fetch()) {
        echo "<tr class='border-t'>";
        echo "<td class='px-3 py-2 font-mono text-blue-600'>" . htmlspecialchars($row['Field']) . "</td>";
        echo "<td class='px-3 py-2 text-gray-600'>" . htmlspecialchars($row['Type']) . "</td>";
        echo "<td class='px-3 py-2 text-gray-600'>" . htmlspecialchars($row['Null']) . "</td>";
        echo "<td class='px-3 py-2 text-gray-600'>" . htmlspecialchars($row['Default'] ?: 'NULL') . "</td>";
        echo "</tr>";
    }
    
    echo "</tbody></table>";
    echo "</div>";
    echo "</div>";
    
    echo "</div>"; // Close space-y-4
    
    echo "<div class='mt-8 bg-green-50 border border-green-200 rounded-lg p-6'>";
    echo "<div class='flex items-center'>";
    echo "<i class='fas fa-check-circle text-green-600 text-2xl mr-3'></i>";
    echo "<div>";
    echo "<h3 class='text-lg font-semibold text-green-900'>Database Update Complete!</h3>";
    echo "<p class='text-green-700 mt-1'>All required fields have been added to the users table. Your Users management page is now fully compatible with the database.</p>";
    echo "</div>";
    echo "</div>";
    echo "<div class='mt-4 flex space-x-4'>";
    echo "<a href='/admin/users' class='inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors'>";
    echo "<i class='fas fa-users mr-2'></i>View Users Page";
    echo "</a>";
    echo "<a href='/admin' class='inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors'>";
    echo "<i class='fas fa-tachometer-alt mr-2'></i>Back to Dashboard";
    echo "</a>";
    echo "</div>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='bg-red-50 border border-red-200 rounded-lg p-4'>";
    echo "<i class='fas fa-exclamation-triangle text-red-600 mr-2'></i>";
    echo "<span class='text-red-600 font-medium'>Error: " . htmlspecialchars($e->getMessage()) . "</span>";
    echo "</div>";
}

echo "</div></body></html>";
?>

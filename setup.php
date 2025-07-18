<?php
/**
 * Database Setup Script
 * Run this script to create the admin user and sample data
 */

// Include the configuration
require_once 'config/config.php';
require_once 'backend/core/Database.php';

echo "<!DOCTYPE html><html><head><title>Database Setup</title><style>body{font-family:Arial,sans-serif;margin:40px;background:#f5f5f5;} .container{background:white;padding:30px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.1);} .success{color:#16a085;} .error{color:#e74c3c;} .info{color:#3498db;} pre{background:#f8f9fa;padding:15px;border-radius:5px;overflow-x:auto;}</style></head><body><div class='container'>";

echo "<h2>🩺 Radiology Resident - Database Setup</h2>";

try {
    $db = Database::getInstance();
    $pdo = $db->connect();
    
    echo "<p class='success'>✅ Database connection successful!</p>";
    
    // Check if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() == 0) {
        echo "<p class='error'>❌ Users table not found. Please run the schema.sql first.</p>";
        echo "<p class='info'>📝 You need to execute the database/schema.sql file in your MySQL database.</p>";
    } else {
        echo "<p class='success'>✅ Users table exists.</p>";
        
        // Check if admin user exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = 'admin@radiologyresident.com'");
        $stmt->execute();
        $adminUser = $stmt->fetch();
        
        if ($adminUser) {
            echo "<p class='info'>ℹ️ Admin user already exists.</p>";
        } else {
            // Create admin user
            $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("
                INSERT INTO users (email, password, first_name, last_name, role, specialization, experience_years, email_verified, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $result = $stmt->execute([
                'admin@radiologyresident.com',
                $hashedPassword,
                'Admin',
                'User',
                'admin',
                'Administration',
                10,
                1,
                'active'
            ]);
            
            if ($result) {
                echo "<p class='success'>✅ Admin user created successfully!</p>";
            } else {
                echo "<p class='error'>❌ Failed to create admin user.</p>";
            }
        }
        
        // Check if sample instructor exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = 'doctor@radiologyresident.com'");
        $stmt->execute();
        $instructorUser = $stmt->fetch();
        
        if (!$instructorUser) {
            // Create sample instructor
            $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
            
            $stmt = $pdo->prepare("
                INSERT INTO users (email, password, first_name, last_name, role, specialization, experience_years, email_verified, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $result = $stmt->execute([
                'doctor@radiologyresident.com',
                $hashedPassword,
                'Dr. Sarah',
                'Johnson',
                'instructor',
                'Diagnostic Radiology',
                15,
                1,
                'active'
            ]);
            
            if ($result) {
                echo "<p class='success'>✅ Sample instructor created successfully!</p>";
            }
        }
        
        // Check course categories
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM course_categories");
        $stmt->execute();
        $categoriesCount = $stmt->fetch()['count'];
        
        if ($categoriesCount == 0) {
            // Insert sample categories
            $categories = [
                ['Chest Imaging', 'chest-imaging', 'Comprehensive chest radiology courses including X-rays, CT scans, and MRI', 'fas fa-lungs', '#3B82F6', 1],
                ['Neuroimaging', 'neuroimaging', 'Brain and spine imaging courses covering CT, MRI, and advanced techniques', 'fas fa-brain', '#10B981', 2],
                ['Musculoskeletal', 'musculoskeletal', 'Bone and joint imaging including X-rays, MRI, and sports medicine', 'fas fa-bone', '#8B5CF6', 3],
                ['Abdominal Imaging', 'abdominal-imaging', 'Comprehensive abdominal and pelvic imaging courses', 'fas fa-user-md', '#F59E0B', 4],
                ['Interventional Radiology', 'interventional-radiology', 'Minimally invasive procedures and techniques', 'fas fa-syringe', '#EF4444', 5]
            ];
            
            $stmt = $pdo->prepare("INSERT INTO course_categories (name, slug, description, icon, color, order_index, active) VALUES (?, ?, ?, ?, ?, ?, 1)");
            
            foreach ($categories as $category) {
                $stmt->execute($category);
            }
            
            echo "<p class='success'>✅ Sample course categories created!</p>";
        }
        
        // Check courses
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM courses");
        $stmt->execute();
        $coursesCount = $stmt->fetch()['count'];
        
        if ($coursesCount == 0) {
            // Get instructor ID
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = 'doctor@radiologyresident.com'");
            $stmt->execute();
            $instructor = $stmt->fetch();
            
            if ($instructor) {
                // Insert sample courses
                $courses = [
                    [
                        'Chest X-Ray Interpretation Fundamentals',
                        'chest-xray-fundamentals',
                        'Master the systematic approach to chest X-ray interpretation. This comprehensive course covers normal anatomy, common pathologies, and advanced techniques for accurate diagnosis.',
                        'Learn systematic chest X-ray interpretation with expert guidance',
                        $instructor['id'],
                        1, // chest-imaging category
                        149.00,
                        480,
                        'beginner',
                        1, // featured
                        'active',
                        4.8,
                        245
                    ],
                    [
                        'Neuroimaging Essentials: Brain MRI & CT',
                        'neuroimaging-essentials',
                        'Comprehensive neuroimaging course covering brain anatomy, pathology recognition, and differential diagnosis using MRI and CT. Perfect for residents and practicing physicians.',
                        'Master brain MRI and CT interpretation techniques',
                        $instructor['id'],
                        2, // neuroimaging category
                        249.00,
                        720,
                        'intermediate',
                        1, // featured
                        'active',
                        4.9,
                        189
                    ],
                    [
                        'Musculoskeletal Imaging Advanced Techniques',
                        'msk-advanced-techniques',
                        'Advanced musculoskeletal imaging course focusing on sports medicine, trauma, and degenerative conditions. Includes MRI, CT, and specialized imaging protocols.',
                        'Advanced MSK imaging for sports medicine and trauma',
                        $instructor['id'],
                        3, // musculoskeletal category
                        349.00,
                        960,
                        'advanced',
                        1, // featured
                        'active',
                        4.7,
                        156
                    ]
                ];
                
                $stmt = $pdo->prepare("
                    INSERT INTO courses (title, slug, description, short_description, instructor_id, category_id, price, duration, difficulty_level, featured, status, rating, total_enrollments) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ");
                
                foreach ($courses as $course) {
                    $stmt->execute($course);
                }
                
                echo "<p class='success'>✅ Sample courses created!</p>";
            }
        }
        
        // Display login credentials
        echo "<hr>";
        echo "<h3>🔐 Login Credentials</h3>";
        echo "<div style='background:#e8f5e8;padding:15px;border-radius:5px;margin:10px 0;'>";
        echo "<h4>Admin Login:</h4>";
        echo "<strong>URL:</strong> <a href='/login' target='_blank'>".SITE_URL."/login</a><br>";
        echo "<strong>Email:</strong> admin@radiologyresident.com<br>";
        echo "<strong>Password:</strong> admin123<br>";
        echo "</div>";
        
        echo "<div style='background:#e8f4f8;padding:15px;border-radius:5px;margin:10px 0;'>";
        echo "<h4>Instructor Login:</h4>";
        echo "<strong>Email:</strong> doctor@radiologyresident.com<br>";
        echo "<strong>Password:</strong> admin123<br>";
        echo "</div>";
        
        // Check database stats
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM users");
        $stmt->execute();
        $userCount = $stmt->fetch()['count'];
        
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM courses");
        $stmt->execute();
        $courseCount = $stmt->fetch()['count'];
        
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM course_categories");
        $stmt->execute();
        $categoryCount = $stmt->fetch()['count'];
        
        echo "<hr>";
        echo "<h3>📊 Database Statistics</h3>";
        echo "<ul>";
        echo "<li>👥 Users: $userCount</li>";
        echo "<li>📚 Courses: $courseCount</li>";
        echo "<li>🏷️ Categories: $categoryCount</li>";
        echo "</ul>";
        
        echo "<hr>";
        echo "<h3>🚀 Next Steps</h3>";
        echo "<ol>";
        echo "<li>Visit <a href='/login' target='_blank'>Login Page</a></li>";
        echo "<li>Use the admin credentials above</li>";
        echo "<li>Access the <a href='/admin' target='_blank'>Admin Dashboard</a></li>";
        echo "<li>Start managing your medical education platform!</li>";
        echo "</ol>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p class='info'>📝 Please check your database configuration in config/config.php</p>";
    echo "<pre>Current Configuration:\n";
    echo "Host: " . DB_HOST . "\n";
    echo "Database: " . DB_NAME . "\n";
    echo "Username: " . DB_USER . "\n";
    echo "Password: " . (DB_PASS ? "[Hidden]" : "[Empty]") . "</pre>";
}

echo "</div></body></html>";
?>

<?php

class AdminController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Require admin authentication
        $this->requireAdminAuth();
    }
    
    public function dashboard()
    {
        $data = [
            'title' => 'Admin Dashboard - Radiology Resident',
            'description' => 'Admin dashboard for managing courses, users, and content',
            'stats' => $this->getDashboardStats(),
            'recent_enrollments' => $this->getRecentEnrollments(),
            'popular_courses' => $this->getPopularCourses()
        ];
        
        $this->view('admin/dashboard', $data);
    }
    
    public function courses()
    {
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        $courses = $this->getCourses($limit, $offset);
        $totalCourses = $this->getTotalCourses();
        $totalPages = ceil($totalCourses / $limit);
        
        $data = [
            'title' => 'Manage Courses - Admin',
            'description' => 'Manage all courses in the platform',
            'courses' => $courses,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_courses' => $totalCourses
        ];
        
        $this->view('admin/courses', $data);
    }
    
    public function users()
    {
        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
        $search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING) ?: '';
        $limit = 15;
        $offset = ($page - 1) * $limit;
        
        $users = $this->getUsers($limit, $offset, $search);
        $totalUsers = $this->getTotalUsers($search);
        $totalPages = ceil($totalUsers / $limit);
        
        $data = [
            'title' => 'All Users - User Management',
            'description' => 'Manage all users in the platform',
            'page_title' => 'All Users',
            'page_description' => 'View and manage all registered users',
            'users' => $users,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_users' => $totalUsers,
            'search' => $search
        ];
        
        $this->view('admin/generic-page', $data);
    }
    
    public function createCourse()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCreateCourse();
        } else {
            $data = [
                'title' => 'Create Course - Admin',
                'description' => 'Create a new course',
                'categories' => $this->getCourseCategories(),
                'instructors' => $this->getInstructors()
            ];
            
            $this->view('admin/create-course', $data);
        }
    }
    
    public function editCourse($id)
    {
        $course = $this->getCourseById($id);
        
        if (!$course) {
            $this->redirect('/admin/courses?error=Course not found');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleEditCourse($id);
        } else {
            $data = [
                'title' => 'Edit Course - Admin',
                'description' => 'Edit course details',
                'course' => $course,
                'categories' => $this->getCourseCategories(),
                'instructors' => $this->getInstructors()
            ];
            
            $this->view('admin/edit-course', $data);
        }
    }
    
    private function requireAdminAuth()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->redirect('/login');
            exit;
        }
    }
    
    private function getDashboardStats()
    {
        try {
            $stats = [];
            
            // Total students
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'student' AND status = 'active'");
            $stmt->execute();
            $stats['students'] = $stmt->fetch()['count'];
            
            // Total courses
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM courses WHERE status = 'active'");
            $stmt->execute();
            $stats['courses'] = $stmt->fetch()['count'];
            
            // Total instructors
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM users WHERE role = 'instructor' AND status = 'active'");
            $stmt->execute();
            $stats['instructors'] = $stmt->fetch()['count'];
            
            // Total enrollments this month
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM enrollments WHERE enrolled_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
            $stmt->execute();
            $stats['monthly_enrollments'] = $stmt->fetch()['count'];
            
            // Total revenue this month (if you have payments table)
            $stats['monthly_revenue'] = 0; // Placeholder
            
            return $stats;
            
        } catch (Exception $e) {
            error_log("Error getting dashboard stats: " . $e->getMessage());
            return [
                'students' => 0,
                'courses' => 0,
                'instructors' => 0,
                'monthly_enrollments' => 0,
                'monthly_revenue' => 0
            ];
        }
    }
    
    private function getRecentEnrollments($limit = 5)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT e.enrolled_at, u.first_name, u.last_name, u.email, c.title as course_title
                FROM enrollments e 
                JOIN users u ON e.user_id = u.id 
                JOIN courses c ON e.course_id = c.id 
                ORDER BY e.enrolled_at DESC 
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error getting recent enrollments: " . $e->getMessage());
            return [];
        }
    }
    
    private function getPopularCourses($limit = 5)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT c.id, c.title, c.rating, c.total_enrollments, u.first_name, u.last_name
                FROM courses c 
                JOIN users u ON c.instructor_id = u.id 
                WHERE c.status = 'active'
                ORDER BY c.total_enrollments DESC 
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error getting popular courses: " . $e->getMessage());
            return [];
        }
    }
    
    private function getCourses($limit, $offset)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT c.*, u.first_name, u.last_name, cat.name as category_name
                FROM courses c 
                LEFT JOIN users u ON c.instructor_id = u.id 
                LEFT JOIN course_categories cat ON c.category_id = cat.id 
                ORDER BY c.created_at DESC 
                LIMIT ? OFFSET ?
            ");
            $stmt->execute([$limit, $offset]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error getting courses: " . $e->getMessage());
            return [];
        }
    }
    
    private function getTotalCourses()
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM courses");
            $stmt->execute();
            return $stmt->fetch()['count'];
        } catch (Exception $e) {
            error_log("Error getting total courses: " . $e->getMessage());
            return 0;
        }
    }
    
    private function getUsers($limit, $offset, $search = '')
    {
        try {
            $whereClause = '';
            $params = [$limit, $offset];
            
            if (!empty($search)) {
                $whereClause = "WHERE (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR 
                               CONCAT(first_name, ' ', last_name) LIKE ? OR 
                               specialization LIKE ? OR role LIKE ? OR 
                               phone LIKE ? OR status LIKE ?)";
                $searchParam = "%$search%";
                array_unshift($params, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
            }
            
            $stmt = $this->db->prepare("
                SELECT id, email, first_name, last_name, role, status, last_login, created_at,
                       specialization, phone, COALESCE(profile_picture, avatar) as profile_picture, experience_years
                FROM users 
                $whereClause
                ORDER BY created_at DESC 
                LIMIT ? OFFSET ?
            ");
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error getting users: " . $e->getMessage());
            return [];
        }
    }
    
    private function getTotalUsers($search = '')
    {
        try {
            $whereClause = '';
            $params = [];
            
            if (!empty($search)) {
                $whereClause = "WHERE (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR 
                               CONCAT(first_name, ' ', last_name) LIKE ? OR 
                               specialization LIKE ? OR role LIKE ? OR 
                               phone LIKE ? OR status LIKE ?)";
                $searchParam = "%$search%";
                $params = [$searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam];
            }
            
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM users $whereClause");
            $stmt->execute($params);
            return $stmt->fetch()['count'];
        } catch (Exception $e) {
            error_log("Error getting total users: " . $e->getMessage());
            return 0;
        }
    }
    
    private function getCourseCategories()
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM course_categories WHERE active = 1 ORDER BY order_index ASC");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error getting course categories: " . $e->getMessage());
            return [];
        }
    }
    
    private function getInstructors()
    {
        try {
            $stmt = $this->db->prepare("SELECT id, first_name, last_name, email FROM users WHERE role = 'instructor' AND status = 'active' ORDER BY first_name ASC");
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error getting instructors: " . $e->getMessage());
            return [];
        }
    }
    
    private function getCourseById($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM courses WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("Error getting course by ID: " . $e->getMessage());
            return null;
        }
    }
    
    private function handleCreateCourse()
    {
        // Implementation for creating course
        // This would handle form validation and course creation
        $this->redirect('/admin/courses?success=Course created successfully');
    }
    
    private function handleEditCourse($id)
    {
        // Implementation for editing course
        // This would handle form validation and course updates
        $this->redirect('/admin/courses?success=Course updated successfully');
    }
    
    // Users Management Methods
    public function contacts()
    {
        $this->renderAdminPage('Contact List', 'Manage contact messages and inquiries');
    }
    
    // Theory Exams Methods
    public function notesChapters()
    {
        $this->renderAdminPage('Notes Chapters', 'Manage notes chapters for theory exams');
    }
    
    public function notes()
    {
        $this->renderAdminPage('Notes', 'Manage theory exam notes');
    }
    
    public function prevYearQuestions()
    {
        $this->renderAdminPage('Previous Year Questions', 'Manage previous year exam questions');
    }
    
    // Video Tutorial Methods
    public function videoCategories()
    {
        $this->renderAdminPage('Video Categories', 'Manage video tutorial categories');
    }
    
    public function videos()
    {
        $this->renderAdminPage('Videos', 'Manage video tutorials');
    }
    
    // Spotters Methods
    public function spotterCategories()
    {
        $this->renderAdminPage('Spotter Categories', 'Manage spotter categories');
    }
    
    public function spotters()
    {
        $this->renderAdminPage('Spotters', 'Manage spotter questions');
    }
    
    public function osceCategories()
    {
        $this->renderAdminPage('OSCE Categories', 'Manage OSCE categories');
    }
    
    public function osce()
    {
        $this->renderAdminPage('OSCE', 'Manage OSCE questions and cases');
    }
    
    // Rapid FRS Methods
    public function rapidFrsCategories()
    {
        $this->renderAdminPage('Rapid FRS Categories', 'Manage Rapid FRS categories');
    }
    
    public function rapidFrs()
    {
        $this->renderAdminPage('Rapid FRS', 'Manage Rapid FRS questions');
    }
    
    // Table Viva Methods
    public function tableVivaCategories()
    {
        $this->renderAdminPage('Table Viva Categories', 'Manage table viva categories');
    }
    
    public function tableViva()
    {
        $this->renderAdminPage('Table Viva', 'Manage table viva questions');
    }
    
    // Long Cases Methods
    public function longcasesCategories()
    {
        $this->renderAdminPage('Long Cases Categories', 'Manage long cases categories');
    }
    
    public function longCases()
    {
        $this->renderAdminPage('Long Cases', 'Manage long case studies');
    }
    
    // Short Cases Methods
    public function shortcasesCategories()
    {
        $this->renderAdminPage('Short Cases Categories', 'Manage short cases categories');
    }
    
    public function shortCases()
    {
        $this->renderAdminPage('Short Cases', 'Manage short case studies');
    }
    
    // FRCR Methods
    public function frcrCategories()
    {
        $this->renderAdminPage('FRCR Categories', 'Manage FRCR exam categories');
    }
    
    public function frcrSubjects()
    {
        $this->renderAdminPage('FRCR Subjects', 'Manage FRCR exam subjects');
    }
    
    public function frcrQuiz()
    {
        $this->renderAdminPage('FRCR Quiz', 'Manage FRCR quiz questions');
    }
    
    public function frcrQuestions()
    {
        $this->renderAdminPage('FRCR Questions', 'Manage FRCR exam questions');
    }
    
    // Content Management Methods
    public function pages()
    {
        $this->renderAdminPage('Pages', 'Manage website pages and content');
    }
    
    public function banners()
    {
        $this->renderAdminPage('Banners', 'Manage website banners and promotional content');
    }
    
    public function blogs()
    {
        $this->renderAdminPage('All Blogs', 'Manage blog posts and articles');
    }
    
    public function testimonials()
    {
        $this->renderAdminPage('All Testimonials', 'Manage user testimonials and reviews');
    }
    
    public function plans()
    {
        $this->renderAdminPage('Plans', 'Manage subscription plans and pricing');
    }
    
    public function subscriptions()
    {
        $this->renderAdminPage('Subscriptions', 'Manage user subscriptions');
    }
    
    public function faq()
    {
        $this->renderAdminPage('FAQ', 'Manage frequently asked questions');
    }
    
    public function reports()
    {
        $this->renderAdminPage('Reports & Notifications', 'View reports and manage notifications');
    }
    
    // User Management API Methods
    public function toggleUserStatus()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
        
        if (!$userId || !in_array($status, ['active', 'inactive'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
            return;
        }
        
        try {
            $stmt = $this->db->prepare("UPDATE users SET status = ? WHERE id = ?");
            $result = $stmt->execute([$status, $userId]);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'User status updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update user status']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    }
    
    public function deleteUser()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        
        if (!$userId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
            return;
        }
        
        try {
            // Check if user is admin
            $stmt = $this->db->prepare("SELECT role FROM users WHERE id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            
            if ($user && $user['role'] === 'admin') {
                echo json_encode(['success' => false, 'message' => 'Cannot delete admin user']);
                return;
            }
            
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
            $result = $stmt->execute([$userId]);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete user']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    }
    
    public function getUserData()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        $userId = filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT);
        
        if (!$userId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
            return;
        }
        
        try {
            $stmt = $this->db->prepare("
                SELECT id, email, first_name, last_name, role, status, specialization, 
                       phone, COALESCE(profile_picture, avatar) as profile_picture, experience_years, created_at, last_login
                FROM users WHERE id = ?
            ");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            
            if ($user) {
                echo json_encode(['success' => true, 'user' => $user]);
            } else {
                echo json_encode(['success' => false, 'message' => 'User not found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    }
    
    public function updateUser()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        $firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
        $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
        $specialization = filter_input(INPUT_POST, 'specialization', FILTER_SANITIZE_STRING);
        $role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
        
        if (!$userId || !$firstName || !$lastName || !$email) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Required fields missing']);
            return;
        }
        
        try {
            $stmt = $this->db->prepare("
                UPDATE users SET 
                    first_name = ?, last_name = ?, email = ?, phone = ?, 
                    specialization = ?, role = ?, status = ?
                WHERE id = ?
            ");
            $result = $stmt->execute([$firstName, $lastName, $email, $phone, $specialization, $role, $status, $userId]);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'User updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update user']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }
    
    // General Settings Methods
    public function globalSettings()
    {
        $this->renderAdminPage('Global Settings', 'Manage global website settings');
    }
    
    public function logoFavicon()
    {
        $this->renderAdminPage('Logo & Favicon', 'Manage website logo and favicon');
    }
    
    public function settings()
    {
        $this->renderAdminPage('Settings', 'Manage general admin settings');
    }
    
    // Helper method to render admin pages with consistent structure
    private function renderAdminPage($title, $description, $additionalData = [])
    {
        $data = array_merge([
            'title' => $title . ' - Admin Dashboard',
            'description' => $description,
            'page_title' => $title,
            'page_description' => $description,
            'stats' => $this->getDashboardStats()
        ], $additionalData);
        
        $this->view('admin/generic-page', $data);
    }
}

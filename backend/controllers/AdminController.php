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
        $limit = 15;
        $offset = ($page - 1) * $limit;
        
        $users = $this->getUsers($limit, $offset);
        $totalUsers = $this->getTotalUsers();
        $totalPages = ceil($totalUsers / $limit);
        
        $data = [
            'title' => 'Manage Users - Admin',
            'description' => 'Manage all users in the platform',
            'users' => $users,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_users' => $totalUsers
        ];
        
        $this->view('admin/users', $data);
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
    
    private function getUsers($limit, $offset)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, email, first_name, last_name, role, status, last_login, created_at
                FROM users 
                ORDER BY created_at DESC 
                LIMIT ? OFFSET ?
            ");
            $stmt->execute([$limit, $offset]);
            return $stmt->fetchAll();
        } catch (Exception $e) {
            error_log("Error getting users: " . $e->getMessage());
            return [];
        }
    }
    
    private function getTotalUsers()
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM users");
            $stmt->execute();
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
}

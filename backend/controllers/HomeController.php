<?php
/**
 * Home Controller
 * Handles main website pages
 */

class HomeController extends BaseController {
    
    /**
     * Home page
     */
    public function index() {
        $data = [
            'title' => 'Welcome to Radiology Resident',
            'description' => 'Master Radiology with Expert-Led Courses',
            'featured_courses' => $this->getFeaturedCourses(),
            'stats' => $this->getStats()
        ];
        
        $this->view('home/index', $data);
    }
    
    /**
     * About page
     */
    public function about() {
        $data = [
            'title' => 'About Us - Radiology Resident',
            'description' => 'Learn about our mission to advance radiology education',
            'team' => $this->getTeamMembers(),
            'achievements' => $this->getAchievements()
        ];
        
        $this->view('home/about', $data);
    }
    
    /**
     * Contact page
     */
    public function contact() {
        $data = [
            'title' => 'Contact Us - Radiology Resident',
            'description' => 'Get in touch with our team',
            'csrf_token' => generateCSRFToken()
        ];
        
        $this->view('home/contact', $data);
    }
    
    /**
     * Handle contact form submission
     */
    public function handleContact() {
        $this->validateCSRF();
        
        $name = $this->sanitize($_POST['name'] ?? '');
        $email = $this->sanitize($_POST['email'] ?? '');
        $subject = $this->sanitize($_POST['subject'] ?? '');
        $message = $this->sanitize($_POST['message'] ?? '');
        
        // Validation
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'Name is required';
        }
        
        if (empty($email) || !$this->validateEmail($email)) {
            $errors[] = 'Valid email is required';
        }
        
        if (empty($subject)) {
            $errors[] = 'Subject is required';
        }
        
        if (empty($message)) {
            $errors[] = 'Message is required';
        }
        
        if (!empty($errors)) {
            $this->json(['errors' => $errors], 400);
        }
        
        // Save to database
        try {
            $this->db->insert('contact_messages', [
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message' => $message,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
            // Send email notification (implement later)
            // $this->sendContactNotification($name, $email, $subject, $message);
            
            $this->json(['success' => true, 'message' => 'Thank you for your message. We will get back to you soon.']);
            
        } catch (Exception $e) {
            error_log("Contact form error: " . $e->getMessage());
            $this->json(['error' => 'Failed to send message. Please try again.'], 500);
        }
    }
    
    /**
     * Get featured courses
     */
    private function getFeaturedCourses() {
        try {
            return $this->db->fetchAll(
                "SELECT id, title, description, image, instructor, price, rating 
                 FROM courses 
                 WHERE featured = 1 AND status = 'active' 
                 ORDER BY order_index ASC 
                 LIMIT 6"
            );
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Get website statistics
     */
    private function getStats() {
        try {
            $stats = [];
            
            // Total students
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM users WHERE role = 'student'");
            $stats['students'] = $result['count'] ?? 0;
            
            // Total courses
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM courses WHERE status = 'active'");
            $stats['courses'] = $result['count'] ?? 0;
            
            // Total instructors
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM users WHERE role = 'instructor'");
            $stats['instructors'] = $result['count'] ?? 0;
            
            // Total hours of content
            $result = $this->db->fetch("SELECT SUM(duration) as total FROM course_lessons");
            $stats['hours'] = round(($result['total'] ?? 0) / 60, 0);
            
            return $stats;
            
        } catch (Exception $e) {
            return [
                'students' => 0,
                'courses' => 0,
                'instructors' => 0,
                'hours' => 0
            ];
        }
    }
    
    /**
     * Get team members
     */
    private function getTeamMembers() {
        try {
            return $this->db->fetchAll(
                "SELECT name, title, bio, image, linkedin, twitter 
                 FROM team_members 
                 WHERE active = 1 
                 ORDER BY order_index ASC"
            );
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Get achievements/milestones
     */
    private function getAchievements() {
        return [
            [
                'year' => '2023',
                'title' => 'Founded',
                'description' => 'Radiology Resident platform launched'
            ],
            [
                'year' => '2024',
                'title' => '1000+ Students',
                'description' => 'Reached first thousand students milestone'
            ],
            [
                'year' => '2025',
                'title' => 'Advanced Platform',
                'description' => 'Launched comprehensive learning management system'
            ]
        ];
    }
}
?>

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
    
    /**
     * Testimonials page
     */
    public function testimonials() {
        $data = [
            'title' => 'Testimonials - Radiology Resident',
            'description' => 'What our students say about their learning experience',
            'page_title' => 'Student Testimonials',
            'page_description' => 'Hear from our successful students and graduates',
            'testimonials' => $this->getTestimonials()
        ];
        
        $this->view('frontend/testimonials', $data);
    }
    
    /**
     * Blog page
     */
    public function blog() {
        $data = [
            'title' => 'Blog - Radiology Resident',
            'description' => 'Latest insights and articles on radiology education',
            'page_title' => 'Radiology Blog',
            'page_description' => 'Expert insights, tips, and latest developments in radiology',
            'blog_posts' => $this->getBlogPosts()
        ];
        
        $this->view('frontend/blog', $data);
    }
    
    /**
     * Plans page
     */
    public function plans() {
        $data = [
            'title' => 'Subscription Plans - Radiology Resident',
            'description' => 'Choose the perfect plan for your radiology education journey',
            'page_title' => 'Subscription Plans',
            'page_description' => 'Flexible plans designed for medical students and residents',
            'plans' => $this->getSubscriptionPlans()
        ];
        
        $this->view('frontend/plans', $data);
    }
    
    /**
     * Get testimonials data
     */
    private function getTestimonials() {
        // Mock data - replace with database queries
        return [
            [
                'id' => 1,
                'name' => 'Dr. Sarah Johnson',
                'position' => 'Radiology Resident',
                'institution' => 'Johns Hopkins Hospital',
                'content' => 'The comprehensive courses and practical spotters helped me excel in my radiology exams.',
                'rating' => 5,
                'image' => 'https://ui-avatars.com/api/?name=Sarah+Johnson&background=3b82f6&color=fff'
            ],
            [
                'id' => 2,
                'name' => 'Dr. Michael Chen',
                'position' => 'Medical Student',
                'institution' => 'Harvard Medical School',
                'content' => 'The OSCE practice sessions were invaluable for my clinical rotations.',
                'rating' => 5,
                'image' => 'https://ui-avatars.com/api/?name=Michael+Chen&background=10b981&color=fff'
            ]
        ];
    }
    
    /**
     * Get blog posts data
     */
    private function getBlogPosts() {
        // Mock data - replace with database queries
        return [
            [
                'id' => 1,
                'title' => 'Understanding Chest X-Ray Interpretation',
                'excerpt' => 'A comprehensive guide to systematic chest X-ray interpretation for medical students.',
                'author' => 'Dr. Emily Rodriguez',
                'date' => '2025-01-15',
                'image' => 'https://images.unsplash.com/photo-1559757148-5c350d0d3c56?w=800&h=400&fit=crop',
                'category' => 'Education'
            ],
            [
                'id' => 2,
                'title' => 'Latest Advances in MRI Technology',
                'excerpt' => 'Exploring the latest developments in magnetic resonance imaging technology.',
                'author' => 'Dr. James Smith',
                'date' => '2025-01-10',
                'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1f?w=800&h=400&fit=crop',
                'category' => 'Technology'
            ]
        ];
    }
    
    /**
     * Get subscription plans data
     */
    private function getSubscriptionPlans() {
        try {
            // Use Plan model to get active plans
            require_once __DIR__ . '/../models/Plan.php';
            $planModel = new Plan();
            $plans = $planModel->getActivePlans();
            
            // Convert to the format expected by the frontend
            $formattedPlans = [];
            foreach ($plans as $plan) {
                $formattedPlans[] = [
                    'id' => $plan['id'],
                    'name' => $plan['name'],
                    'price' => ($plan['price'] === 'Free' || $plan['price'] === '0') ? 0 : floatval($plan['price']),
                    'period' => $plan['period'],
                    'description' => $plan['description'],
                    'features' => $plan['features'],
                    'popular' => (bool)$plan['is_popular'],
                    'icon' => $plan['icon'] ?? 'fas fa-star'
                ];
            }
            
            return $formattedPlans;
            
        } catch (Exception $e) {
            error_log("Get subscription plans error: " . $e->getMessage());
            
            // Fallback to hardcoded data if database fails
            return [
                [
                    'id' => 1,
                    'name' => 'Basic',
                    'price' => 29,
                    'period' => 'month',
                    'description' => 'Perfect for medical students starting their radiology journey',
                    'features' => [
                        'Access to theory notes',
                        'Basic video tutorials',
                        'Practice questions',
                        'Email support'
                    ],
                    'popular' => false
                ],
                [
                    'id' => 2,
                    'name' => 'Professional',
                    'price' => 79,
                    'period' => 'month',
                    'description' => 'Ideal for residents and advanced students',
                    'features' => [
                        'Everything in Basic',
                        'OSCE practice sessions',
                        'Advanced spotters',
                        'Table viva preparation',
                        'Priority support',
                        'Downloadable resources'
                    ],
                    'popular' => true
                ],
                [
                    'id' => 3,
                    'name' => 'Premium',
                    'price' => 149,
                    'period' => 'month',
                    'description' => 'Complete access for serious professionals',
                    'features' => [
                        'Everything in Professional',
                        'One-on-one mentoring',
                        'Custom study plans',
                        'Exclusive masterclasses',
                        '24/7 expert support',
                        'Certification courses'
                    ],
                    'popular' => false
                ]
            ];
        }
    }
}
?>

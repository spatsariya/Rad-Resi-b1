<?php

class AuthController extends BaseController
{
    public function loginForm()
    {
        // Check if user is already logged in
        if ($this->isLoggedIn()) {
            $this->redirect('/admin');
            return;
        }
        
        $data = [
            'title' => 'Login - Radiology Resident',
            'description' => 'Login to your Radiology Resident account'
        ];
        
        $this->view('auth/login', $data);
    }
    
    public function registerForm()
    {
        // Check if user is already logged in
        if ($this->isLoggedIn()) {
            $this->redirect('/admin');
            return;
        }
        
        // Generate CSRF token if not exists
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        $data = [
            'title' => 'Register - Radiology Resident',
            'description' => 'Create your Radiology Resident account'
        ];
        
        $this->view('auth/register', $data);
    }
    
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
            return;
        }
        
        // Validate CSRF token
        if (!$this->validateCSRF()) {
            $this->jsonResponse(['error' => 'Invalid CSRF token'], 403);
            return;
        }
        
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        // Validate input
        if (empty($email) || empty($password)) {
            $this->jsonResponse(['error' => 'Email and password are required'], 400);
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(['error' => 'Invalid email format'], 400);
            return;
        }
        
        try {
            $db = Database::getInstance();
            
            // Get user from database
            $stmt = $db->prepare("SELECT id, email, password, first_name, last_name, role, status FROM users WHERE email = ? AND status = 'active'");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user || !password_verify($password, $user['password'])) {
                $this->jsonResponse(['error' => 'Invalid email or password'], 401);
                return;
            }
            
            // Update last login
            $stmt = $db->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $stmt->execute([$user['id']]);
            
            // Start session and store user data
            $this->startSession($user);
            
            // Determine redirect URL based on role
            $redirectUrl = '/dashboard';
            if ($user['role'] === 'admin' || $user['role'] === 'instructor') {
                $redirectUrl = '/admin';
            }
            
            $this->jsonResponse([
                'success' => true,
                'message' => 'Login successful',
                'redirect' => $redirectUrl,
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['first_name'] . ' ' . $user['last_name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ]
            ]);
            
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $this->jsonResponse(['error' => 'Login failed. Please try again.'], 500);
        }
    }
    
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
            return;
        }
        
        // Validate CSRF token
        if (!$this->validateCSRF()) {
            $this->jsonResponse(['error' => 'Invalid CSRF token'], 403);
            return;
        }
        
        $firstName = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lastName = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $confirmPassword = filter_input(INPUT_POST, 'confirm_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $specialization = filter_input(INPUT_POST, 'specialization', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $experienceYears = filter_input(INPUT_POST, 'experience_years', FILTER_VALIDATE_INT);
        $institution = filter_input(INPUT_POST, 'institution', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $newsletter = filter_input(INPUT_POST, 'newsletter', FILTER_VALIDATE_BOOLEAN);
        
        // Validate input
        $errors = [];
        
        if (empty($firstName)) $errors[] = 'First name is required';
        if (empty($lastName)) $errors[] = 'Last name is required';
        if (empty($email)) $errors[] = 'Email is required';
        if (empty($password)) $errors[] = 'Password is required';
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        
        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long';
        }
        
        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match';
        }
        
        if (!empty($errors)) {
            $this->jsonResponse(['error' => implode('. ', $errors)], 400);
            return;
        }
        
        try {
            $db = Database::getInstance();
            
            // Check if email already exists
            $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $this->jsonResponse(['error' => 'Email already registered'], 400);
                return;
            }
            
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Generate email verification token
            $verificationToken = bin2hex(random_bytes(32));
            
            // Insert user
            $stmt = $db->prepare("
                INSERT INTO users (email, password, first_name, last_name, phone, specialization, 
                                 experience_years, email_verification_token, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $result = $stmt->execute([
                $email,
                $hashedPassword,
                $firstName,
                $lastName,
                $phone,
                $specialization,
                $experienceYears,
                $verificationToken
            ]);
            
            if ($result) {
                // TODO: Send verification email
                
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Registration successful! Please check your email to verify your account.',
                    'redirect' => '/login'
                ]);
            } else {
                $this->jsonResponse(['error' => 'Registration failed. Please try again.'], 500);
            }
            
        } catch (Exception $e) {
            error_log("Registration error: " . $e->getMessage());
            $this->jsonResponse(['error' => 'Registration failed. Please try again.'], 500);
        }
    }
    
    public function logout()
    {
        $this->endSession();
        $this->redirect('/login');
    }
    
    private function startSession($user)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['login_time'] = time();
        
        // Regenerate session ID for security
        session_regenerate_id(true);
    }
    
    private function endSession()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }
    
    private function isLoggedIn()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
}

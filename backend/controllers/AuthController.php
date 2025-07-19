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
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is already logged in
        if ($this->isLoggedIn()) {
            $this->redirect('/admin');
            return;
        }
        
        // Generate CSRF token
        $csrfToken = $this->generateCSRFToken();
        
        $data = [
            'title' => 'Register - Radiology Resident',
            'description' => 'Create your Radiology Resident account',
            'csrf_token' => $csrfToken
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
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            $this->jsonResponse(['error' => 'Email and password are required'], 400);
            return;
        }
        
        try {
            $user = new User();
            $existingUser = $user->findByEmail($email);
            
            if (!$existingUser || !password_verify($password, $existingUser['password'])) {
                $this->jsonResponse(['error' => 'Invalid email or password'], 401);
                return;
            }
            
            if ($existingUser['status'] !== 'active') {
                $this->jsonResponse(['error' => 'Account is not active'], 401);
                return;
            }
            
            // Start user session
            $this->startSession($existingUser);
            
            // Determine redirect URL based on role
            $redirectUrl = $existingUser['role'] === 'admin' ? '/admin' : '/dashboard';
            
            $this->jsonResponse([
                'success' => true,
                'message' => 'Login successful',
                'redirect' => $redirectUrl,
                'user' => [
                    'id' => $existingUser['id'],
                    'name' => $existingUser['first_name'] . ' ' . $existingUser['last_name'],
                    'email' => $existingUser['email'],
                    'role' => $existingUser['role']
                ]
            ]);
            
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            $this->jsonResponse(['error' => 'Login failed. Please try again.'], 500);
        }
    }
    
    public function register()
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
            return;
        }
        
        try {
            // Log the start of registration process
            error_log("Registration process started for email: " . ($_POST['email'] ?? 'unknown'));
            
            // Validate CSRF token
            if (!$this->validateCSRF()) {
                error_log("CSRF validation failed");
                $this->jsonResponse([
                    'error' => 'Invalid CSRF token. Please refresh the page and try again.',
                    'debug' => [
                        'session_id' => session_id(),
                        'session_csrf' => $_SESSION['csrf_token'] ?? 'missing',
                        'post_csrf' => $_POST['csrf_token'] ?? 'missing'
                    ]
                ], 403);
                return;
            }
            
            error_log("CSRF validation passed");
            
            // Get and validate input
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
            
            error_log("Input validation completed");
            
            // Validate required fields
            $errors = [];
            if (empty($firstName)) $errors[] = 'First name is required';
            if (empty($lastName)) $errors[] = 'Last name is required';
            if (empty($email)) $errors[] = 'Email is required';
            if (empty($password)) $errors[] = 'Password is required';
            if (empty($specialization)) $errors[] = 'Specialization is required';
            
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
                error_log("Validation errors: " . implode(', ', $errors));
                $this->jsonResponse(['error' => implode('. ', $errors)], 400);
                return;
            }
            
            error_log("All validations passed, creating User model");
            
            // Check if user already exists
            $user = new User();
            error_log("User model created successfully");
            
            $existingUser = $user->findByEmail($email);
            error_log("Email check completed");
            
            if ($existingUser) {
                error_log("User already exists with email: $email");
                $this->jsonResponse(['error' => 'Email already registered'], 400);
                return;
            }
            
            error_log("Preparing user data for creation");
            
            // Create new user
            $userData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'specialization' => $specialization,
                'experience_years' => $experienceYears ?: 0,
                'institution' => $institution,
                'newsletter' => $newsletter ? 1 : 0,
                'role' => 'user',
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            error_log("Creating user in database");
            
            $userId = $user->create($userData);
            
            if ($userId) {
                error_log("User created successfully with ID: $userId");
                
                // Start session for the new user
                $newUser = $user->findById($userId);
                $this->startSession($newUser);
                
                error_log("User session started, registration complete");
                
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Registration successful! Welcome to RadRes.',
                    'redirect' => '/dashboard'
                ]);
            } else {
                error_log("User creation failed - no ID returned");
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

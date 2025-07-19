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
            // Test response first to ensure server is working
            $this->jsonResponse([
                'success' => false,
                'error' => 'Test response - server is working',
                'debug' => [
                    'session_id' => session_id(),
                    'post_data_received' => !empty($_POST),
                    'csrf_token_received' => isset($_POST['csrf_token']) ? 'YES' : 'NO'
                ]
            ]);
            return;
            
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

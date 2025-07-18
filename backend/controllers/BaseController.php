<?php
/**
 * Base Controller Class
 * Contains common functionality for all controllers
 */

class BaseController {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    /**
     * Render a view
     */
    protected function view($view, $data = []) {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            throw new Exception("View not found: $view");
        }
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Redirect to a URL
     */
    protected function redirect($url) {
        header("Location: $url");
        exit;
    }
    
    /**
     * Validate CSRF token
     */
    protected function validateCSRF() {
        $token = $_POST['csrf_token'] ?? '';
        if (!validateCSRFToken($token)) {
            $this->json(['error' => 'Invalid CSRF token'], 403);
        }
    }
    
    /**
     * Sanitize input
     */
    protected function sanitize($input) {
        if (is_array($input)) {
            return array_map([$this, 'sanitize'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Validate email
     */
    protected function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Hash password
     */
    protected function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Verify password
     */
    protected function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * Generate random token
     */
    protected function generateToken($length = 32) {
        return bin2hex(random_bytes($length));
    }
    
    /**
     * Check if user is authenticated
     */
    protected function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Get current user ID
     */
    protected function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Require authentication
     */
    protected function requireAuth() {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
        }
    }
}
?>

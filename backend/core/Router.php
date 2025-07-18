<?php
/**
 * Simple Router Class
 * Handles routing for the application
 */

class Router {
    private $routes = [];
    private $middleware = [];
    
    public function __construct() {
        $this->loadRoutes();
    }
    
    /**
     * Add a GET route
     */
    public function get($path, $callback, $middleware = []) {
        $this->addRoute('GET', $path, $callback, $middleware);
    }
    
    /**
     * Add a POST route
     */
    public function post($path, $callback, $middleware = []) {
        $this->addRoute('POST', $path, $callback, $middleware);
    }
    
    /**
     * Add a PUT route
     */
    public function put($path, $callback, $middleware = []) {
        $this->addRoute('PUT', $path, $callback, $middleware);
    }
    
    /**
     * Add a DELETE route
     */
    public function delete($path, $callback, $middleware = []) {
        $this->addRoute('DELETE', $path, $callback, $middleware);
    }
    
    /**
     * Add a route
     */
    private function addRoute($method, $path, $callback, $middleware = []) {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback,
            'middleware' => $middleware
        ];
    }
    
    /**
     * Load route definitions
     */
    private function loadRoutes() {
        // Frontend Routes
        $this->get('/', 'HomeController@index');
        $this->get('/about', 'HomeController@about');
        $this->get('/courses', 'CourseController@index');
        $this->get('/course/{id}', 'CourseController@show');
        $this->get('/login', 'AuthController@loginForm');
        $this->get('/register', 'AuthController@registerForm');
        $this->get('/contact', 'HomeController@contact');
        
        // Auth Routes
        $this->post('/auth/login', 'AuthController@login');
        $this->post('/auth/register', 'AuthController@register');
        $this->post('/auth/logout', 'AuthController@logout');
        
        // API Routes
        $this->get('/api/courses', 'ApiController@courses');
        $this->get('/api/course/{id}', 'ApiController@course');
        $this->post('/api/contact', 'ApiController@contact');
        
        // Admin Routes (protected)
        $this->get('/admin', 'AdminController@dashboard', ['auth']);
        $this->get('/admin/courses', 'AdminController@courses', ['auth']);
        $this->get('/admin/users', 'AdminController@users', ['auth']);
        $this->post('/admin/course/create', 'AdminController@createCourse', ['auth']);
        $this->put('/admin/course/{id}', 'AdminController@updateCourse', ['auth']);
        $this->delete('/admin/course/{id}', 'AdminController@deleteCourse', ['auth']);
    }
    
    /**
     * Handle the current request
     */
    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // No base path removal needed - running in root directory
        $path = $path ?: '/';
        
        // Normalize path - remove trailing slash except for root
        if ($path !== '/' && substr($path, -1) === '/') {
            $path = rtrim($path, '/');
        }
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $path)) {
                // Apply middleware
                foreach ($route['middleware'] as $middlewareName) {
                    if (!$this->applyMiddleware($middlewareName)) {
                        return;
                    }
                }
                
                // Extract parameters
                $params = $this->extractParams($route['path'], $path);
                
                // Execute callback
                $this->executeCallback($route['callback'], $params);
                return;
            }
        }
        
        // 404 Not Found
        $this->notFound();
    }
    
    /**
     * Check if path matches route
     */
    private function matchPath($routePath, $requestPath) {
        $routePattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $routePath);
        $routePattern = str_replace('/', '\/', $routePattern);
        return preg_match('/^' . $routePattern . '$/', $requestPath);
    }
    
    /**
     * Extract parameters from path
     */
    private function extractParams($routePath, $requestPath) {
        $params = [];
        
        if (strpos($routePath, '{') !== false) {
            $routeSegments = explode('/', $routePath);
            $requestSegments = explode('/', $requestPath);
            
            foreach ($routeSegments as $index => $segment) {
                if (strpos($segment, '{') === 0 && strpos($segment, '}') === strlen($segment) - 1) {
                    $paramName = substr($segment, 1, -1);
                    $params[$paramName] = $requestSegments[$index] ?? null;
                }
            }
        }
        
        return $params;
    }
    
    /**
     * Apply middleware
     */
    private function applyMiddleware($middlewareName) {
        switch ($middlewareName) {
            case 'auth':
                return $this->authMiddleware();
            default:
                return true;
        }
    }
    
    /**
     * Authentication middleware
     */
    private function authMiddleware() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        return true;
    }
    
    /**
     * Execute callback
     */
    private function executeCallback($callback, $params = []) {
        if (is_string($callback) && strpos($callback, '@') !== false) {
            list($controller, $method) = explode('@', $callback);
            
            $controllerFile = __DIR__ . '/../controllers/' . $controller . '.php';
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                $controllerInstance = new $controller();
                call_user_func_array([$controllerInstance, $method], $params);
            } else {
                $this->notFound();
            }
        } elseif (is_callable($callback)) {
            call_user_func_array($callback, $params);
        } else {
            $this->notFound();
        }
    }
    
    /**
     * Handle 404 Not Found
     */
    private function notFound() {
        http_response_code(404);
        include __DIR__ . '/../views/404.php';
    }
}
?>

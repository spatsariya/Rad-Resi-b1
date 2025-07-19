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
        
        // Theory Exams Routes
        $this->get('/theory/notes', 'TheoryController@notes');
        $this->get('/theory/previous-year-questions', 'TheoryController@previousYearQuestions');
        $this->get('/theory/video-tutorials', 'TheoryController@videoTutorials');
        
        // Practical Exams Routes
        $this->get('/practical/spotters', 'PracticalController@spotters');
        $this->get('/practical/osce', 'PracticalController@osce');
        $this->get('/practical/exam-cases', 'PracticalController@examCases');
        $this->get('/practical/table-viva', 'PracticalController@tableViva');
        
        // Practice & Quiz Routes
        $this->get('/practice/spotters', 'PracticeController@spotters');
        $this->get('/practice/osce', 'PracticeController@osce');
        $this->get('/practice/exam-cases', 'PracticeController@examCases');
        $this->get('/practice/table-viva', 'PracticeController@tableViva');
        
        // Content Pages Routes
        $this->get('/testimonials', 'HomeController@testimonials');
        $this->get('/blog', 'HomeController@blog');
        $this->get('/plans', 'HomeController@plans');
        
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
        
        // Users Management
        $this->get('/admin/users', 'AdminController@users', ['auth']);
        $this->post('/api/admin/users/toggle-status', 'AdminController@toggleUserStatus', ['auth']);
        $this->post('/api/admin/users/delete', 'AdminController@deleteUser', ['auth']);
        $this->get('/api/admin/users/get', 'AdminController@getUserData', ['auth']);
        $this->post('/api/admin/users/update', 'AdminController@updateUser', ['auth']);
        
        // Contact Management
        $this->get('/admin/contacts', 'ContactController@index', ['auth']);
        $this->get('/api/admin/contacts/user-info', 'ContactController@getUserInfo', ['auth']);
        $this->post('/api/admin/contacts/send-message', 'ContactController@sendMessage', ['auth']);
        $this->post('/api/admin/contacts/bulk-message', 'ContactController@sendBulkMessage', ['auth']);
        $this->get('/api/admin/contacts/history', 'ContactController@getHistory', ['auth']);
        
        // Theory Exams
        // Notes Chapters Management
        $this->get('/admin/notes-chapters', 'NotesChapterController@index', ['auth']);
        $this->get('/admin/notes-chapters/get-details', 'NotesChapterController@getChapterDetails', ['auth']);
        $this->get('/admin/notes-chapters/get-main-chapters', 'NotesChapterController@getMainChapters', ['auth']);
        $this->post('/admin/notes-chapters/create', 'NotesChapterController@create', ['auth']);
        $this->post('/admin/notes-chapters/update', 'NotesChapterController@update', ['auth']);
        $this->post('/admin/notes-chapters/delete', 'NotesChapterController@delete', ['auth']);
        $this->post('/admin/notes-chapters/update-status', 'NotesChapterController@updateStatus', ['auth']);
        $this->post('/admin/notes-chapters/update-order', 'NotesChapterController@updateOrder', ['auth']);
        
        // Notes Management
        $this->get('/admin/notes', 'NotesController@index', ['auth']);
        $this->get('/admin/notes/get-note-details', 'NotesController@getNoteDetails', ['auth']);
        $this->get('/admin/notes/get/(\d+)', 'NotesController@get', ['auth']); // RESTful get note by ID
        $this->post('/admin/notes/create', 'NotesController@create', ['auth']);
        $this->post('/admin/notes/update', 'NotesController@update', ['auth']);
        $this->post('/admin/notes/delete', 'NotesController@delete', ['auth']);
        $this->post('/admin/notes/update-status', 'NotesController@updateStatus', ['auth']);
        $this->post('/admin/notes/update-order', 'NotesController@updateOrder', ['auth']);
        $this->get('/admin/notes/get-chapters', 'NotesController@getChapters', ['auth']);
        
        // Database Setup Routes
        $this->get('/admin/setup-notes', 'SetupController@setupNotes', ['auth']);
        $this->post('/admin/setup-notes', 'SetupController@setupNotes', ['auth']);
        $this->get('/admin/prev-year-questions', 'AdminController@prevYearQuestions', ['auth']);
        
        // Video Tutorial
        $this->get('/admin/video-categories', 'AdminController@videoCategories', ['auth']);
        $this->get('/admin/videos', 'AdminController@videos', ['auth']);
        
        // Spotters
        $this->get('/admin/spotter-categories', 'AdminController@spotterCategories', ['auth']);
        $this->get('/admin/spotters', 'AdminController@spotters', ['auth']);
        $this->get('/admin/osce-categories', 'AdminController@osceCategories', ['auth']);
        $this->get('/admin/osce', 'AdminController@osce', ['auth']);
        
        // Rapid FRS
        $this->get('/admin/rapid-frs-categories', 'AdminController@rapidFrsCategories', ['auth']);
        $this->get('/admin/rapid-frs', 'AdminController@rapidFrs', ['auth']);
        
        // Table Viva
        $this->get('/admin/table-viva-categories', 'AdminController@tableVivaCategories', ['auth']);
        $this->get('/admin/table-viva', 'AdminController@tableViva', ['auth']);
        
        // Long Cases
        $this->get('/admin/longcases-categories', 'AdminController@longcasesCategories', ['auth']);
        $this->get('/admin/long-cases', 'AdminController@longCases', ['auth']);
        
        // Short Cases
        $this->get('/admin/shortcases-categories', 'AdminController@shortcasesCategories', ['auth']);
        $this->get('/admin/short-cases', 'AdminController@shortCases', ['auth']);
        
        // FRCR
        $this->get('/admin/frcr-categories', 'AdminController@frcrCategories', ['auth']);
        $this->get('/admin/frcr-subjects', 'AdminController@frcrSubjects', ['auth']);
        $this->get('/admin/frcr-quiz', 'AdminController@frcrQuiz', ['auth']);
        $this->get('/admin/frcr-questions', 'AdminController@frcrQuestions', ['auth']);
        
        // Content Management
        $this->get('/admin/pages', 'AdminController@pages', ['auth']);
        $this->get('/admin/banners', 'AdminController@banners', ['auth']);
        $this->get('/admin/blogs', 'AdminController@blogs', ['auth']);
        $this->get('/admin/testimonials', 'AdminController@testimonials', ['auth']);
        
        // Plans Management
        $this->get('/admin/plans', 'PlanController@index', ['auth']);
        $this->post('/admin/plans/create', 'PlanController@create', ['auth']);
        $this->post('/admin/plans/update', 'PlanController@update', ['auth']);
        $this->post('/admin/plans/delete', 'PlanController@delete', ['auth']);
        $this->get('/admin/plans/get-details', 'PlanController@getPlanDetails', ['auth']);
        $this->post('/admin/plans/update-order', 'PlanController@updateOrder', ['auth']);
        
        // Subscriptions Management
        $this->get('/admin/subscriptions', 'SubscriptionController@index', ['auth']);
        $this->get('/admin/subscriptions/get-details', 'SubscriptionController@getSubscriptionDetails', ['auth']);
        $this->post('/admin/subscriptions/update-status', 'SubscriptionController@updateStatus', ['auth']);
        $this->post('/admin/subscriptions/delete', 'SubscriptionController@delete', ['auth']);
        $this->get('/admin/subscriptions/generate-invoice', 'SubscriptionController@generateInvoice', ['auth']);
        $this->get('/admin/faq', 'AdminController@faq', ['auth']);
        $this->get('/admin/reports', 'AdminController@reports', ['auth']);
        
        // General Settings
        $this->get('/admin/global-settings', 'AdminController@globalSettings', ['auth']);
        $this->get('/admin/logo-favicon', 'AdminController@logoFavicon', ['auth']);
        $this->get('/admin/settings', 'AdminController@settings', ['auth']);
        
        // Legacy routes
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

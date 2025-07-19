<?php

class PlanController extends BaseController
{
    private $planModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->planModel = new Plan();
    }
    
    /**
     * Display plans management page
     */
    public function index()
    {
        // Check authentication
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            header('Location: /admin/login');
            exit;
        }
        
        // Get filter parameters
        $search = $_GET['search'] ?? '';
        $page = max(1, intval($_GET['page'] ?? 1));
        $per_page = 20;
        $offset = ($page - 1) * $per_page;
        
        // Get plans
        $plans = $this->planModel->getAll($search, $per_page, $offset);
        
        // Decode features for each plan
        foreach ($plans as &$plan) {
            $plan['features'] = json_decode($plan['features'], true) ?? [];
        }
        
        // Get total count for pagination
        $total_plans = $this->planModel->count($search);
        $total_pages = ceil($total_plans / $per_page);
        
        // Get statistics
        $stats = $this->planModel->getStatistics();
        
        // Prepare data for view
        $data = [
            'title' => 'Plans Management - Admin Panel',
            'description' => 'Manage subscription plans and pricing',
            'page_title' => 'Plans Management',
            'page_description' => 'Create, modify, and manage subscription plans',
            'plans' => $plans,
            'total_plans' => $total_plans,
            'current_page' => $page,
            'total_pages' => $total_pages,
            'search' => $search,
            'stats' => $stats
        ];
        
        $this->view('admin/generic-page', $data);
    }
    
    /**
     * Create a new plan
     */
    public function create()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCreatePlan();
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    /**
     * Update an existing plan
     */
    public function update()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleUpdatePlan();
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    /**
     * Delete a plan
     */
    public function delete()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleDeletePlan();
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    /**
     * Get plan details
     */
    public function getPlanDetails()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $plan_id = intval($_GET['plan_id'] ?? 0);
        if (!$plan_id) {
            $this->jsonResponse(['success' => false, 'message' => 'Plan ID required']);
            return;
        }
        
        $plan = $this->planModel->findById($plan_id);
        if (!$plan) {
            $this->jsonResponse(['success' => false, 'message' => 'Plan not found']);
            return;
        }
        
        // Decode features
        $plan['features'] = json_decode($plan['features'], true) ?? [];
        
        $this->jsonResponse(['success' => true, 'plan' => $plan]);
    }
    
    /**
     * Update plan order
     */
    public function updateOrder()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $planOrders = $input['plan_orders'] ?? [];
            
            if (empty($planOrders)) {
                $this->jsonResponse(['success' => false, 'message' => 'Plan orders required']);
                return;
            }
            
            if ($this->planModel->updateOrder($planOrders)) {
                $this->jsonResponse(['success' => true, 'message' => 'Plan order updated successfully']);
            } else {
                $this->jsonResponse(['success' => false, 'message' => 'Failed to update plan order']);
            }
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    /**
     * Handle create plan form submission
     */
    private function handleCreatePlan()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validation
        $errors = $this->validatePlanData($input);
        if (!empty($errors)) {
            $this->jsonResponse(['success' => false, 'errors' => $errors]);
            return;
        }
        
        // Prepare plan data
        $planData = [
            'name' => $input['name'],
            'description' => $input['description'],
            'price' => $input['price'],
            'period' => $input['period'],
            'features' => $input['features'] ?? [],
            'icon' => $input['icon'] ?? 'fas fa-star',
            'is_popular' => intval($input['is_popular'] ?? 0),
            'is_active' => intval($input['is_active'] ?? 1),
            'order_index' => intval($input['order_index'] ?? 0)
        ];
        
        $planId = $this->planModel->create($planData);
        
        if ($planId) {
            $this->jsonResponse(['success' => true, 'message' => 'Plan created successfully', 'plan_id' => $planId]);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to create plan']);
        }
    }
    
    /**
     * Handle update plan form submission
     */
    private function handleUpdatePlan()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $plan_id = intval($input['plan_id'] ?? 0);
        if (!$plan_id) {
            $this->jsonResponse(['success' => false, 'message' => 'Plan ID required']);
            return;
        }
        
        // Check if plan exists
        $existingPlan = $this->planModel->findById($plan_id);
        if (!$existingPlan) {
            $this->jsonResponse(['success' => false, 'message' => 'Plan not found']);
            return;
        }
        
        // Validation
        $errors = $this->validatePlanData($input);
        if (!empty($errors)) {
            $this->jsonResponse(['success' => false, 'errors' => $errors]);
            return;
        }
        
        // Prepare plan data
        $planData = [
            'name' => $input['name'],
            'description' => $input['description'],
            'price' => $input['price'],
            'period' => $input['period'],
            'features' => $input['features'] ?? [],
            'icon' => $input['icon'] ?? 'fas fa-star',
            'is_popular' => intval($input['is_popular'] ?? 0),
            'is_active' => intval($input['is_active'] ?? 1),
            'order_index' => intval($input['order_index'] ?? 0)
        ];
        
        if ($this->planModel->update($plan_id, $planData)) {
            $this->jsonResponse(['success' => true, 'message' => 'Plan updated successfully']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to update plan']);
        }
    }
    
    /**
     * Handle delete plan
     */
    private function handleDeletePlan()
    {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $plan_id = intval($input['plan_id'] ?? 0);
        if (!$plan_id) {
            $this->jsonResponse(['success' => false, 'message' => 'Plan ID required']);
            return;
        }
        
        // Check if plan exists
        $existingPlan = $this->planModel->findById($plan_id);
        if (!$existingPlan) {
            $this->jsonResponse(['success' => false, 'message' => 'Plan not found']);
            return;
        }
        
        if ($this->planModel->delete($plan_id)) {
            $this->jsonResponse(['success' => true, 'message' => 'Plan deleted successfully']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to delete plan']);
        }
    }
    
    /**
     * Validate plan data
     */
    private function validatePlanData($data)
    {
        $errors = [];
        
        if (empty($data['name'])) {
            $errors['name'] = 'Plan name is required';
        }
        
        if (empty($data['description'])) {
            $errors['description'] = 'Plan description is required';
        }
        
        if (empty($data['price'])) {
            $errors['price'] = 'Plan price is required';
        }
        
        if (empty($data['period'])) {
            $errors['period'] = 'Plan period is required';
        } elseif (!in_array($data['period'], ['month', 'year', 'lifetime'])) {
            $errors['period'] = 'Invalid plan period';
        }
        
        if (empty($data['features']) || !is_array($data['features'])) {
            $errors['features'] = 'At least one feature is required';
        }
        
        return $errors;
    }
}

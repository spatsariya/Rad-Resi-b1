<?php

class SubscriptionController extends BaseController
{
    private $subscriptionModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->subscriptionModel = new Subscription();
    }
    
    /**
     * Display subscriptions management page
     */
    public function index()
    {
        // Check authentication
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            header('Location: /admin/login');
            exit;
        }
        
        try {
            // Check if subscriptions table exists
            if (!$this->subscriptionModel->checkTable()) {
                // Show setup message if table doesn't exist
                $data = [
                    'title' => 'Subscriptions Management - Admin Panel',
                    'description' => 'Manage user subscriptions and billing',
                    'page_title' => 'Subscriptions Management',
                    'page_description' => 'View and manage all user subscriptions',
                    'table_missing' => true
                ];
                $this->view('admin/generic-page', $data);
                return;
            }
            
            // Get filter parameters
            $search = $_GET['search'] ?? '';
            $status_filter = $_GET['status_filter'] ?? '';
            $plan_filter = $_GET['plan_filter'] ?? '';
            $page = max(1, intval($_GET['page'] ?? 1));
            $per_page = 20;
            $offset = ($page - 1) * $per_page;
            
            // Get subscriptions
            $subscriptions = $this->subscriptionModel->getAll($search, $status_filter, $plan_filter, $per_page, $offset);
            
            // Get total count for pagination
            $total_subscriptions = $this->subscriptionModel->count($search, $status_filter, $plan_filter);
            $total_pages = ceil($total_subscriptions / $per_page);
            
            // Get statistics
            $stats = $this->subscriptionModel->getStatistics();
            
            // Get plans for filter dropdown
            $plans = $this->subscriptionModel->getPlansForFilter();
            
            // Prepare data for view
            $data = [
                'title' => 'Subscriptions Management - Admin Panel',
                'description' => 'Manage user subscriptions and billing',
                'page_title' => 'Subscriptions Management',
                'page_description' => 'View and manage all user subscriptions',
                'subscriptions' => $subscriptions,
                'total_subscriptions' => $total_subscriptions,
                'current_page' => $page,
                'total_pages' => $total_pages,
                'search' => $search,
                'status_filter' => $status_filter,
                'plan_filter' => $plan_filter,
                'stats' => $stats,
                'plans' => $plans,
                'table_missing' => false
            ];
            
            $this->view('admin/generic-page', $data);
            
        } catch (Exception $e) {
            error_log("Subscriptions page error: " . $e->getMessage());
            
            // Show error message
            $data = [
                'title' => 'Subscriptions Management - Admin Panel',
                'description' => 'Manage user subscriptions and billing',
                'page_title' => 'Subscriptions Management',
                'page_description' => 'View and manage all user subscriptions',
                'error_message' => 'Error loading subscriptions: ' . $e->getMessage(),
                'table_missing' => false
            ];
            $this->view('admin/generic-page', $data);
        }
    }
    
    /**
     * Get subscription details
     */
    public function getSubscriptionDetails()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $subscription_id = intval($_GET['subscription_id'] ?? 0);
        if (!$subscription_id) {
            $this->jsonResponse(['success' => false, 'message' => 'Subscription ID required']);
            return;
        }
        
        try {
            $subscription = $this->subscriptionModel->findById($subscription_id);
            if (!$subscription) {
                $this->jsonResponse(['success' => false, 'message' => 'Subscription not found']);
                return;
            }
            
            // Decode plan features if they exist
            if (!empty($subscription['plan_features'])) {
                $subscription['plan_features'] = json_decode($subscription['plan_features'], true) ?? [];
            } else {
                $subscription['plan_features'] = [];
            }
            
            $this->jsonResponse(['success' => true, 'subscription' => $subscription]);
            
        } catch (Exception $e) {
            error_log("Get subscription details error: " . $e->getMessage());
            
            // Provide specific error messages
            if (strpos($e->getMessage(), "Subscriptions table does not exist") !== false) {
                $this->jsonResponse(['success' => false, 'message' => 'Subscriptions table not found. Please run the database schema first.']);
            } else {
                $this->jsonResponse(['success' => false, 'message' => 'Error loading subscription details: ' . $e->getMessage()]);
            }
        }
    }
    
    /**
     * Update subscription status
     */
    public function updateStatus()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            $subscription_id = intval($input['subscription_id'] ?? 0);
            $status = $input['status'] ?? '';
            
            if (!$subscription_id) {
                $this->jsonResponse(['success' => false, 'message' => 'Subscription ID required']);
                return;
            }
            
            if (!in_array($status, ['active', 'cancelled', 'expired', 'pending', 'failed'])) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid status']);
                return;
            }
            
            // Check if subscription exists
            $subscription = $this->subscriptionModel->findById($subscription_id);
            if (!$subscription) {
                $this->jsonResponse(['success' => false, 'message' => 'Subscription not found']);
                return;
            }
            
            if ($this->subscriptionModel->updateStatus($subscription_id, $status)) {
                $this->jsonResponse(['success' => true, 'message' => 'Subscription status updated successfully']);
            } else {
                $this->jsonResponse(['success' => false, 'message' => 'Failed to update subscription status']);
            }
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    /**
     * Delete subscription
     */
    public function delete()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            $subscription_id = intval($input['subscription_id'] ?? 0);
            if (!$subscription_id) {
                $this->jsonResponse(['success' => false, 'message' => 'Subscription ID required']);
                return;
            }
            
            // Check if subscription exists
            $subscription = $this->subscriptionModel->findById($subscription_id);
            if (!$subscription) {
                $this->jsonResponse(['success' => false, 'message' => 'Subscription not found']);
                return;
            }
            
            if ($this->subscriptionModel->delete($subscription_id)) {
                $this->jsonResponse(['success' => true, 'message' => 'Subscription deleted successfully']);
            } else {
                $this->jsonResponse(['success' => false, 'message' => 'Failed to delete subscription']);
            }
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    /**
     * Generate invoice (placeholder for future implementation)
     */
    public function generateInvoice()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $subscription_id = intval($_GET['subscription_id'] ?? 0);
        if (!$subscription_id) {
            $this->jsonResponse(['success' => false, 'message' => 'Subscription ID required']);
            return;
        }
        
        $subscription = $this->subscriptionModel->findById($subscription_id);
        if (!$subscription) {
            $this->jsonResponse(['success' => false, 'message' => 'Subscription not found']);
            return;
        }
        
        // TODO: Implement actual invoice generation
        // For now, return a success message
        $this->jsonResponse([
            'success' => true, 
            'message' => 'Invoice generation feature will be implemented soon',
            'invoice_url' => '/admin/subscriptions/invoice/' . $subscription_id
        ]);
    }
}

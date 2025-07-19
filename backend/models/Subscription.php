<?php

class Subscription
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all subscriptions with optional search and pagination
     */
    public function getAll($search = '', $status_filter = '', $plan_filter = '', $limit = 20, $offset = 0)
    {
        try {
            $whereClauses = [];
            $params = [];
            
            // Search filter
            if (!empty($search)) {
                $whereClauses[] = "(u.first_name LIKE :search OR u.last_name LIKE :search OR u.email LIKE :search OR s.transaction_id LIKE :search)";
                $params['search'] = "%$search%";
            }
            
            // Status filter
            if (!empty($status_filter)) {
                $whereClauses[] = "s.status = :status";
                $params['status'] = $status_filter;
            }
            
            // Plan filter
            if (!empty($plan_filter)) {
                $whereClauses[] = "s.plan_id = :plan_id";
                $params['plan_id'] = $plan_filter;
            }
            
            $whereClause = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';
            
            $sql = "
                SELECT 
                    s.*,
                    u.first_name,
                    u.last_name,
                    u.email,
                    u.phone,
                    p.name as plan_name,
                    p.icon as plan_icon,
                    CASE 
                        WHEN s.expires_at > NOW() AND s.status = 'active' THEN 'active'
                        WHEN s.expires_at <= NOW() AND s.status = 'active' THEN 'expired'
                        ELSE s.status
                    END as computed_status
                FROM subscriptions s
                LEFT JOIN users u ON s.user_id = u.id
                LEFT JOIN plans p ON s.plan_id = p.id
                $whereClause
                ORDER BY s.created_at DESC
                LIMIT :limit OFFSET :offset
            ";
            
            $stmt = $this->db->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Get all subscriptions error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Count total subscriptions with optional search and filters
     */
    public function count($search = '', $status_filter = '', $plan_filter = '')
    {
        try {
            $whereClauses = [];
            $params = [];
            
            // Search filter
            if (!empty($search)) {
                $whereClauses[] = "(u.first_name LIKE :search OR u.last_name LIKE :search OR u.email LIKE :search OR s.transaction_id LIKE :search)";
                $params['search'] = "%$search%";
            }
            
            // Status filter
            if (!empty($status_filter)) {
                $whereClauses[] = "s.status = :status";
                $params['status'] = $status_filter;
            }
            
            // Plan filter
            if (!empty($plan_filter)) {
                $whereClauses[] = "s.plan_id = :plan_id";
                $params['plan_id'] = $plan_filter;
            }
            
            $whereClause = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';
            
            $sql = "
                SELECT COUNT(*) as total 
                FROM subscriptions s
                LEFT JOIN users u ON s.user_id = u.id
                LEFT JOIN plans p ON s.plan_id = p.id
                $whereClause
            ";
            
            $stmt = $this->db->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
            
        } catch (PDOException $e) {
            error_log("Count subscriptions error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Find subscription by ID
     */
    public function findById($id)
    {
        try {
            $sql = "
                SELECT 
                    s.*,
                    u.first_name,
                    u.last_name,
                    u.email,
                    u.phone,
                    u.institution,
                    u.specialization,
                    p.name as plan_name,
                    p.description as plan_description,
                    p.icon as plan_icon,
                    p.features as plan_features
                FROM subscriptions s
                LEFT JOIN users u ON s.user_id = u.id
                LEFT JOIN plans p ON s.plan_id = p.id
                WHERE s.id = :id
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Find subscription by ID error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Update subscription status
     */
    public function updateStatus($id, $status)
    {
        try {
            $sql = "UPDATE subscriptions SET status = :status, updated_at = NOW() WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':status', $status);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Update subscription status error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get subscription statistics
     */
    public function getStatistics()
    {
        try {
            $stats = [];
            
            // Total subscriptions
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM subscriptions");
            $stats['total_subscriptions'] = $result['count'] ?? 0;
            
            // Active subscriptions
            $result = $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM subscriptions 
                WHERE status = 'active' AND expires_at > NOW()
            ");
            $stats['active_subscriptions'] = $result['count'] ?? 0;
            
            // Expired subscriptions
            $result = $this->db->fetch("
                SELECT COUNT(*) as count 
                FROM subscriptions 
                WHERE expires_at <= NOW() OR status = 'expired'
            ");
            $stats['expired_subscriptions'] = $result['count'] ?? 0;
            
            // Total revenue
            $result = $this->db->fetch("
                SELECT SUM(CAST(amount AS DECIMAL(10,2))) as total_revenue 
                FROM subscriptions 
                WHERE status IN ('active', 'completed', 'expired')
            ");
            $stats['total_revenue'] = round($result['total_revenue'] ?? 0, 2);
            
            // Monthly revenue (current month)
            $result = $this->db->fetch("
                SELECT SUM(CAST(amount AS DECIMAL(10,2))) as monthly_revenue 
                FROM subscriptions 
                WHERE status IN ('active', 'completed', 'expired')
                AND MONTH(created_at) = MONTH(NOW()) 
                AND YEAR(created_at) = YEAR(NOW())
            ");
            $stats['monthly_revenue'] = round($result['monthly_revenue'] ?? 0, 2);
            
            return $stats;
            
        } catch (PDOException $e) {
            error_log("Get subscription statistics error: " . $e->getMessage());
            return [
                'total_subscriptions' => 0,
                'active_subscriptions' => 0,
                'expired_subscriptions' => 0,
                'total_revenue' => 0,
                'monthly_revenue' => 0
            ];
        }
    }
    
    /**
     * Get plans for filter dropdown
     */
    public function getPlansForFilter()
    {
        try {
            $sql = "
                SELECT DISTINCT p.id, p.name 
                FROM plans p
                INNER JOIN subscriptions s ON p.id = s.plan_id
                ORDER BY p.name ASC
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Get plans for filter error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Create a new subscription (for manual creation)
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO subscriptions (
                user_id, plan_id, amount, currency, payment_method, 
                transaction_id, status, starts_at, expires_at, created_at
            ) VALUES (
                :user_id, :plan_id, :amount, :currency, :payment_method,
                :transaction_id, :status, :starts_at, :expires_at, NOW()
            )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':user_id', $data['user_id'], PDO::PARAM_INT);
            $stmt->bindValue(':plan_id', $data['plan_id'], PDO::PARAM_INT);
            $stmt->bindValue(':amount', $data['amount']);
            $stmt->bindValue(':currency', $data['currency'] ?? 'USD');
            $stmt->bindValue(':payment_method', $data['payment_method'] ?? 'manual');
            $stmt->bindValue(':transaction_id', $data['transaction_id']);
            $stmt->bindValue(':status', $data['status'] ?? 'active');
            $stmt->bindValue(':starts_at', $data['starts_at']);
            $stmt->bindValue(':expires_at', $data['expires_at']);
            
            $stmt->execute();
            
            // Get the last inserted ID
            $sql = "SELECT LAST_INSERT_ID() as id";
            $result = $this->db->fetch($sql);
            return $result['id'] ?? false;
            
        } catch (PDOException $e) {
            error_log("Create subscription error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete a subscription
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM subscriptions WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Delete subscription error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if subscriptions table exists
     */
    public function checkTable()
    {
        try {
            $result = $this->db->fetch("SHOW TABLES LIKE 'subscriptions'");
            return !empty($result);
        } catch (Exception $e) {
            return false;
        }
    }
}

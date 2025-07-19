<?php

class Plan
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Get all plans with optional search and pagination
     */
    public function getAll($search = '', $limit = 10, $offset = 0)
    {
        try {
            $whereClause = '';
            $params = [];
            
            if (!empty($search)) {
                $whereClause = "WHERE name LIKE :search OR description LIKE :search";
                $params['search'] = "%$search%";
            }
            
            $sql = "SELECT * FROM plans $whereClause ORDER BY order_index ASC, id ASC LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Get all plans error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Count total plans with optional search
     */
    public function count($search = '')
    {
        try {
            $whereClause = '';
            $params = [];
            
            if (!empty($search)) {
                $whereClause = "WHERE name LIKE :search OR description LIKE :search";
                $params['search'] = "%$search%";
            }
            
            $sql = "SELECT COUNT(*) as total FROM plans $whereClause";
            $stmt = $this->db->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
            
        } catch (PDOException $e) {
            error_log("Count plans error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Find plan by ID
     */
    public function findById($id)
    {
        try {
            $sql = "SELECT * FROM plans WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Find plan by ID error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Create a new plan
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO plans (name, description, price, period, features, icon, is_popular, is_active, order_index, created_at) 
                    VALUES (:name, :description, :price, :period, :features, :icon, :is_popular, :is_active, :order_index, NOW())";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':name', $data['name']);
            $stmt->bindValue(':description', $data['description']);
            $stmt->bindValue(':price', $data['price'], PDO::PARAM_STR);
            $stmt->bindValue(':period', $data['period']);
            $stmt->bindValue(':features', json_encode($data['features']));
            $stmt->bindValue(':icon', $data['icon'] ?? 'fas fa-star');
            $stmt->bindValue(':is_popular', $data['is_popular'] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':is_active', $data['is_active'] ?? 1, PDO::PARAM_INT);
            $stmt->bindValue(':order_index', $data['order_index'] ?? 0, PDO::PARAM_INT);
            
            $stmt->execute();
            
            // Get the last inserted ID
            $sql = "SELECT LAST_INSERT_ID() as id";
            $result = $this->db->fetch($sql);
            return $result['id'] ?? false;
            
        } catch (PDOException $e) {
            error_log("Create plan error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update a plan
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE plans SET 
                    name = :name, 
                    description = :description, 
                    price = :price, 
                    period = :period, 
                    features = :features, 
                    icon = :icon, 
                    is_popular = :is_popular, 
                    is_active = :is_active, 
                    order_index = :order_index,
                    updated_at = NOW()
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':name', $data['name']);
            $stmt->bindValue(':description', $data['description']);
            $stmt->bindValue(':price', $data['price'], PDO::PARAM_STR);
            $stmt->bindValue(':period', $data['period']);
            $stmt->bindValue(':features', json_encode($data['features']));
            $stmt->bindValue(':icon', $data['icon'] ?? 'fas fa-star');
            $stmt->bindValue(':is_popular', $data['is_popular'] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':is_active', $data['is_active'] ?? 1, PDO::PARAM_INT);
            $stmt->bindValue(':order_index', $data['order_index'] ?? 0, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Update plan error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete a plan
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM plans WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Delete plan error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update plan order
     */
    public function updateOrder($planOrders)
    {
        try {
            $this->db->beginTransaction();
            
            foreach ($planOrders as $order => $planId) {
                $sql = "UPDATE plans SET order_index = :order WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':order', $order, PDO::PARAM_INT);
                $stmt->bindValue(':id', $planId, PDO::PARAM_INT);
                $stmt->execute();
            }
            
            $this->db->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->db->rollback();
            error_log("Update plan order error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get plan statistics
     */
    public function getStatistics()
    {
        try {
            $stats = [];
            
            // Total plans
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM plans");
            $stats['total_plans'] = $result['count'] ?? 0;
            
            // Active plans
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM plans WHERE is_active = 1");
            $stats['active_plans'] = $result['count'] ?? 0;
            
            // Popular plan
            $result = $this->db->fetch("SELECT name FROM plans WHERE is_popular = 1 LIMIT 1");
            $stats['popular_plan'] = $result['name'] ?? 'None';
            
            // Average price
            $result = $this->db->fetch("SELECT AVG(CAST(price AS DECIMAL(10,2))) as avg_price FROM plans WHERE is_active = 1 AND price != 'Free'");
            $stats['average_price'] = round($result['avg_price'] ?? 0, 2);
            
            return $stats;
            
        } catch (PDOException $e) {
            error_log("Get plan statistics error: " . $e->getMessage());
            return [
                'total_plans' => 0,
                'active_plans' => 0,
                'popular_plan' => 'None',
                'average_price' => 0
            ];
        }
    }
    
    /**
     * Get active plans for public display
     */
    public function getActivePlans()
    {
        try {
            $sql = "SELECT * FROM plans WHERE is_active = 1 ORDER BY order_index ASC, id ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            $plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Decode features JSON
            foreach ($plans as &$plan) {
                $plan['features'] = json_decode($plan['features'], true) ?? [];
            }
            
            return $plans;
            
        } catch (PDOException $e) {
            error_log("Get active plans error: " . $e->getMessage());
            return [];
        }
    }
}

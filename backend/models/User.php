<?php

class User
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance()->connect();
    }
    
    /**
     * Create a new user
     */
    public function create($userData)
    {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO users (
                    first_name, last_name, email, phone, password, 
                    specialization, experience_years, institution, 
                    newsletter, role, status, email_verified, created_at
                ) VALUES (
                    :first_name, :last_name, :email, :phone, :password,
                    :specialization, :experience_years, :institution,
                    :newsletter, :role, :status, :email_verified, :created_at
                )
            ");
            
            $stmt->execute($userData);
            return $this->db->lastInsertId();
            
        } catch (PDOException $e) {
            error_log("User creation error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Find user by email
     */
    public function findByEmail($email)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->execute(['email' => $email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Find user by email error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Find user by ID
     */
    public function findById($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Find user by ID error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all users with optional search and pagination
     */
    public function getAll($search = '', $limit = 10, $offset = 0)
    {
        try {
            $whereClause = '';
            $params = [];
            
            if (!empty($search)) {
                $whereClause = "WHERE first_name LIKE :search OR last_name LIKE :search OR email LIKE :search OR specialization LIKE :search";
                $params['search'] = "%$search%";
            }
            
            $sql = "SELECT * FROM users $whereClause ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Get all users error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Count total users with optional search
     */
    public function count($search = '')
    {
        try {
            $whereClause = '';
            $params = [];
            
            if (!empty($search)) {
                $whereClause = "WHERE first_name LIKE :search OR last_name LIKE :search OR email LIKE :search OR specialization LIKE :search";
                $params['search'] = "%$search%";
            }
            
            $sql = "SELECT COUNT(*) as total FROM users $whereClause";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['total'];
            
        } catch (PDOException $e) {
            error_log("Count users error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Update user
     */
    public function update($id, $userData)
    {
        try {
            $setParts = [];
            $params = ['id' => $id];
            
            foreach ($userData as $field => $value) {
                if ($field !== 'id') {
                    $setParts[] = "$field = :$field";
                    $params[$field] = $value;
                }
            }
            
            $setClause = implode(', ', $setParts);
            $sql = "UPDATE users SET $setClause WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
            
        } catch (PDOException $e) {
            error_log("Update user error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete user
     */
    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
            return $stmt->execute(['id' => $id]);
            
        } catch (PDOException $e) {
            error_log("Delete user error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Toggle user status
     */
    public function toggleStatus($id)
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE users 
                SET status = CASE 
                    WHEN status = 'active' THEN 'inactive' 
                    ELSE 'active' 
                END 
                WHERE id = :id
            ");
            return $stmt->execute(['id' => $id]);
            
        } catch (PDOException $e) {
            error_log("Toggle user status error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get user by ID (alias for findById for consistency)
     */
    public function getUserById($id)
    {
        return $this->findById($id);
    }
    
    /**
     * Get contacts with advanced filtering for contact management
     */
    public function getContactsWithFilters($filters = [])
    {
        try {
            $whereClauses = [];
            $params = [];
            
            // Search filter
            if (!empty($filters['search'])) {
                $whereClauses[] = "(first_name LIKE :search OR last_name LIKE :search OR email LIKE :search OR phone LIKE :search OR institution LIKE :search OR specialization LIKE :search)";
                $params['search'] = '%' . $filters['search'] . '%';
            }
            
            // Role filter
            if (!empty($filters['role'])) {
                $whereClauses[] = "role = :role";
                $params['role'] = $filters['role'];
            }
            
            // Status filter
            if (!empty($filters['status'])) {
                $whereClauses[] = "status = :status";
                $params['status'] = $filters['status'];
            }
            
            // Newsletter filter
            if (isset($filters['newsletter']) && $filters['newsletter'] !== '') {
                $whereClauses[] = "newsletter = :newsletter";
                $params['newsletter'] = (int)$filters['newsletter'];
            }
            
            // Group filter (if implementing contact groups)
            if (!empty($filters['group_id'])) {
                $whereClauses[] = "id IN (SELECT user_id FROM contact_group_members WHERE group_id = :group_id)";
                $params['group_id'] = $filters['group_id'];
            }
            
            $whereClause = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';
            
            // Count total for pagination
            $countSql = "SELECT COUNT(*) as total FROM users $whereClause";
            $countStmt = $this->db->prepare($countSql);
            $countStmt->execute($params);
            $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Get contacts with message counts
            $page = $filters['page'] ?? 1;
            $per_page = $filters['per_page'] ?? 20;
            $offset = ($page - 1) * $per_page;
            
            // Check if the messaging tables exist, if not use simpler query
            $tablesExist = $this->checkMessagingTables();
            
            if ($tablesExist) {
                $sql = "
                    SELECT u.*, 
                           COUNT(DISTINCT m_received.id) as messages_received,
                           COUNT(DISTINCT ci.id) as interactions_count
                    FROM users u
                    LEFT JOIN messages m_received ON u.id = m_received.recipient_id
                    LEFT JOIN contact_interactions ci ON u.id = ci.user_id
                    $whereClause
                    GROUP BY u.id
                    ORDER BY u.created_at DESC
                    LIMIT :limit OFFSET :offset
                ";
            } else {
                $sql = "
                    SELECT u.*, 
                           0 as messages_received,
                           0 as interactions_count
                    FROM users u
                    $whereClause
                    ORDER BY u.created_at DESC
                    LIMIT :limit OFFSET :offset
                ";
            }
            
            $stmt = $this->db->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            $stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            
            $stmt->execute();
            $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return [
                'contacts' => $contacts,
                'total' => $total
            ];
            
        } catch (PDOException $e) {
            error_log("Get contacts with filters error: " . $e->getMessage());
            return [
                'contacts' => [],
                'total' => 0
            ];
        }
    }
    
    /**
     * Update user's last login time
     */
    public function updateLastLogin($id)
    {
        try {
            $stmt = $this->db->prepare("UPDATE users SET last_login = NOW() WHERE id = :id");
            return $stmt->execute(['id' => $id]);
            
        } catch (PDOException $e) {
            error_log("Update last login error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get user statistics for dashboard
     */
    public function getUserStats()
    {
        try {
            $stats = [];
            
            // Total users
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM users");
            $stats['total_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            // Active users
            $stmt = $this->db->query("SELECT COUNT(*) as active FROM users WHERE status = 'active'");
            $stats['active_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['active'];
            
            // New users this month
            $stmt = $this->db->query("SELECT COUNT(*) as new_month FROM users WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())");
            $stats['new_users_month'] = $stmt->fetch(PDO::FETCH_ASSOC)['new_month'];
            
            // Users by role
            $stmt = $this->db->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
            $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($roles as $role) {
                $stats['role_' . $role['role']] = $role['count'];
            }
            
            return $stats;
            
        } catch (PDOException $e) {
            error_log("Get user stats error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Check if messaging tables exist in the database
     */
    private function checkMessagingTables()
    {
        try {
            $stmt = $this->db->query("SHOW TABLES LIKE 'messages'");
            $messagesExist = $stmt->rowCount() > 0;
            
            $stmt = $this->db->query("SHOW TABLES LIKE 'contact_interactions'");
            $interactionsExist = $stmt->rowCount() > 0;
            
            return $messagesExist && $interactionsExist;
        } catch (PDOException $e) {
            error_log("Check messaging tables error: " . $e->getMessage());
            return false;
        }
    }
}

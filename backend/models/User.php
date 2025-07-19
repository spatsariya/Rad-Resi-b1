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
                    newsletter, role, status, created_at
                ) VALUES (
                    :first_name, :last_name, :email, :phone, :password,
                    :specialization, :experience_years, :institution,
                    :newsletter, :role, :status, :created_at
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
}

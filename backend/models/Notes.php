<?php

class Notes
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Check if notes table exists
     */
    public function checkTable()
    {
        try {
            $result = $this->db->fetchAll("SHOW TABLES LIKE 'notes'");
            return !empty($result);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get all notes with chapter information and optional search/filters
     */
    public function getAll($search = '', $status_filter = '', $type_filter = '', $chapter_filter = '', $limit = 20, $offset = 0)
    {
        try {
            $whereClauses = [];
            $params = [];
            
            // Search filter
            if (!empty($search)) {
                $whereClauses[] = "(n.title LIKE :search OR n.content LIKE :search OR nc.chapter_name LIKE :search OR nc.sub_chapter_name LIKE :search)";
                $params['search'] = "%$search%";
            }
            
            // Status filter
            if (!empty($status_filter)) {
                $whereClauses[] = "n.status = :status";
                $params['status'] = $status_filter;
            }
            
            // Type filter (premium/free)
            if (!empty($type_filter)) {
                $whereClauses[] = "n.is_premium = :is_premium";
                $params['is_premium'] = $type_filter === 'premium' ? 1 : 0;
            }
            
            // Chapter filter
            if (!empty($chapter_filter)) {
                $whereClauses[] = "n.chapter_id = :chapter_id";
                $params['chapter_id'] = $chapter_filter;
            }
            
            $whereClause = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';
            
            $sql = "
                SELECT 
                    n.id,
                    n.title,
                    n.content,
                    n.chapter_id,
                    n.is_premium,
                    n.display_order,
                    n.status,
                    n.views_count,
                    n.created_at,
                    n.updated_at,
                    nc.chapter_name,
                    nc.sub_chapter_name,
                    CASE 
                        WHEN n.is_premium = 1 THEN 'Premium'
                        ELSE 'Free'
                    END as access_type
                FROM notes n
                LEFT JOIN notes_chapters nc ON n.chapter_id = nc.id
                $whereClause
                ORDER BY n.display_order ASC, n.created_at DESC
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
            error_log("Get all notes error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Count total notes with optional search and filters
     */
    public function count($search = '', $status_filter = '', $type_filter = '', $chapter_filter = '')
    {
        try {
            $whereClauses = [];
            $params = [];
            
            // Search filter
            if (!empty($search)) {
                $whereClauses[] = "(n.title LIKE :search OR n.content LIKE :search OR nc.chapter_name LIKE :search OR nc.sub_chapter_name LIKE :search)";
                $params['search'] = "%$search%";
            }
            
            // Status filter
            if (!empty($status_filter)) {
                $whereClauses[] = "n.status = :status";
                $params['status'] = $status_filter;
            }
            
            // Type filter (premium/free)
            if (!empty($type_filter)) {
                $whereClauses[] = "n.is_premium = :is_premium";
                $params['is_premium'] = $type_filter === 'premium' ? 1 : 0;
            }
            
            // Chapter filter
            if (!empty($chapter_filter)) {
                $whereClauses[] = "n.chapter_id = :chapter_id";
                $params['chapter_id'] = $chapter_filter;
            }
            
            $whereClause = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';
            
            $sql = "
                SELECT COUNT(*) as count 
                FROM notes n
                LEFT JOIN notes_chapters nc ON n.chapter_id = nc.id
                $whereClause
            ";
            $stmt = $this->db->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] ?? 0;
            
        } catch (PDOException $e) {
            error_log("Count notes error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Find note by ID with chapter information
     */
    public function findById($id)
    {
        try {
            $sql = "
                SELECT 
                    n.*,
                    nc.chapter_name,
                    nc.sub_chapter_name
                FROM notes n
                LEFT JOIN notes_chapters nc ON n.chapter_id = nc.id
                WHERE n.id = :id
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            // Check if error is due to missing table
            if (strpos($e->getMessage(), "doesn't exist") !== false || strpos($e->getMessage(), "Table") !== false) {
                throw new Exception("Notes table does not exist. Please run the database schema.");
            }
            error_log("Find note by ID error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Create new note
     */
    public function create($data)
    {
        try {
            // Get next display order
            $maxOrder = $this->getMaxDisplayOrder();
            $displayOrder = $data['display_order'] ?? ($maxOrder + 1);
            
            $sql = "
                INSERT INTO notes (
                    title, 
                    content, 
                    chapter_id, 
                    is_premium, 
                    display_order, 
                    status,
                    views_count,
                    created_at,
                    updated_at
                ) VALUES (
                    :title, 
                    :content, 
                    :chapter_id, 
                    :is_premium, 
                    :display_order, 
                    :status,
                    0,
                    NOW(),
                    NOW()
                )
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':title', $data['title']);
            $stmt->bindValue(':content', $data['content'] ?? '');
            $stmt->bindValue(':chapter_id', $data['chapter_id'] ? intval($data['chapter_id']) : null, PDO::PARAM_INT);
            $stmt->bindValue(':is_premium', intval($data['is_premium'] ?? 0), PDO::PARAM_INT);
            $stmt->bindValue(':display_order', $displayOrder, PDO::PARAM_INT);
            $stmt->bindValue(':status', $data['status'] ?? 'active');
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Create note error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update note
     */
    public function update($id, $data)
    {
        try {
            $sql = "
                UPDATE notes SET 
                    title = :title,
                    content = :content,
                    chapter_id = :chapter_id,
                    is_premium = :is_premium,
                    display_order = :display_order,
                    status = :status,
                    updated_at = NOW()
                WHERE id = :id
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':title', $data['title']);
            $stmt->bindValue(':content', $data['content'] ?? '');
            $stmt->bindValue(':chapter_id', $data['chapter_id'] ? intval($data['chapter_id']) : null, PDO::PARAM_INT);
            $stmt->bindValue(':is_premium', intval($data['is_premium'] ?? 0), PDO::PARAM_INT);
            $stmt->bindValue(':display_order', intval($data['display_order']), PDO::PARAM_INT);
            $stmt->bindValue(':status', $data['status']);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Update note error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete note
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM notes WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Delete note error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update note status
     */
    public function updateStatus($id, $status)
    {
        try {
            $sql = "UPDATE notes SET status = :status, updated_at = NOW() WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':status', $status);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Update note status error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update display order
     */
    public function updateOrder($orderData)
    {
        try {
            $this->db->beginTransaction();
            
            foreach ($orderData as $item) {
                $sql = "UPDATE notes SET display_order = :order, updated_at = NOW() WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':id', $item['id'], PDO::PARAM_INT);
                $stmt->bindValue(':order', $item['order'], PDO::PARAM_INT);
                $stmt->execute();
            }
            
            $this->db->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->db->rollback();
            error_log("Update note order error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get maximum display order
     */
    private function getMaxDisplayOrder()
    {
        try {
            $sql = "SELECT MAX(display_order) as max_order FROM notes";
            $result = $this->db->fetch($sql);
            return $result['max_order'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Get notes statistics
     */
    public function getStatistics()
    {
        try {
            $stats = [];
            
            // Total notes
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM notes");
            $stats['total_notes'] = $result['count'] ?? 0;
            
            // Active notes
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM notes WHERE status = 'active'");
            $stats['active_notes'] = $result['count'] ?? 0;
            
            // Inactive notes
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM notes WHERE status = 'inactive'");
            $stats['inactive_notes'] = $result['count'] ?? 0;
            
            // Premium notes
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM notes WHERE is_premium = 1");
            $stats['premium_notes'] = $result['count'] ?? 0;
            
            // Free notes
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM notes WHERE is_premium = 0");
            $stats['free_notes'] = $result['count'] ?? 0;
            
            // Total views
            $result = $this->db->fetch("SELECT SUM(views_count) as total FROM notes");
            $stats['total_views'] = $result['total'] ?? 0;
            
            return $stats;
            
        } catch (Exception $e) {
            return [
                'total_notes' => 0,
                'active_notes' => 0,
                'inactive_notes' => 0,
                'premium_notes' => 0,
                'free_notes' => 0,
                'total_views' => 0
            ];
        }
    }
    
    /**
     * Get available chapters for dropdown
     */
    public function getChapters()
    {
        try {
            $sql = "
                SELECT id, chapter_name, sub_chapter_name, 
                       CONCAT(chapter_name, 
                              CASE 
                                  WHEN sub_chapter_name IS NOT NULL AND sub_chapter_name != '' 
                                  THEN CONCAT(' - ', sub_chapter_name) 
                                  ELSE '' 
                              END) as display_name
                FROM notes_chapters 
                WHERE status = 'active' 
                ORDER BY display_order ASC, chapter_name ASC, sub_chapter_name ASC
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Get chapters error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Increment view count
     */
    public function incrementViews($id)
    {
        try {
            $sql = "UPDATE notes SET views_count = views_count + 1, updated_at = NOW() WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Increment views error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get notes by chapter
     */
    public function getByChapter($chapterId, $includeInactive = false)
    {
        try {
            $statusClause = $includeInactive ? '' : 'AND n.status = "active"';
            
            $sql = "
                SELECT 
                    n.*,
                    nc.chapter_name,
                    nc.sub_chapter_name
                FROM notes n
                LEFT JOIN notes_chapters nc ON n.chapter_id = nc.id
                WHERE n.chapter_id = :chapter_id $statusClause
                ORDER BY n.display_order ASC, n.created_at DESC
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':chapter_id', $chapterId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Get notes by chapter error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get popular notes (by views)
     */
    public function getPopularNotes($limit = 10)
    {
        try {
            $sql = "
                SELECT 
                    n.*,
                    nc.chapter_name,
                    nc.sub_chapter_name
                FROM notes n
                LEFT JOIN notes_chapters nc ON n.chapter_id = nc.id
                WHERE n.status = 'active'
                ORDER BY n.views_count DESC, n.created_at DESC
                LIMIT :limit
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Get popular notes error: " . $e->getMessage());
            return [];
        }
    }
}
?>

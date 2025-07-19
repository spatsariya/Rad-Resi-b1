<?php

class NotesChapter
{
    private $db;
    
    public function __construct()
    {
        $this->db = Database::getInstance();
    }
    
    /**
     * Check if notes_chapters table exists
     */
    public function checkTable()
    {
        try {
            $result = $this->db->query("SHOW TABLES LIKE 'notes_chapters'");
            return !empty($result);
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get all chapters with optional search and filters
     */
    public function getAll($search = '', $status_filter = '', $limit = 20, $offset = 0)
    {
        try {
            $whereClauses = [];
            $params = [];
            
            // Search filter
            if (!empty($search)) {
                $whereClauses[] = "(chapter_name LIKE :search OR sub_chapter_name LIKE :search OR description LIKE :search)";
                $params['search'] = "%$search%";
            }
            
            // Status filter
            if (!empty($status_filter)) {
                $whereClauses[] = "status = :status";
                $params['status'] = $status_filter;
            }
            
            $whereClause = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';
            
            $sql = "
                SELECT 
                    id,
                    chapter_name,
                    sub_chapter_name,
                    parent_id,
                    description,
                    thumbnail_image,
                    display_order,
                    status,
                    created_at,
                    updated_at
                FROM notes_chapters
                $whereClause
                ORDER BY display_order ASC, chapter_name ASC, sub_chapter_name ASC
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
            error_log("Get all notes chapters error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Count total chapters with optional search and filters
     */
    public function count($search = '', $status_filter = '')
    {
        try {
            $whereClauses = [];
            $params = [];
            
            // Search filter
            if (!empty($search)) {
                $whereClauses[] = "(chapter_name LIKE :search OR sub_chapter_name LIKE :search OR description LIKE :search)";
                $params['search'] = "%$search%";
            }
            
            // Status filter
            if (!empty($status_filter)) {
                $whereClauses[] = "status = :status";
                $params['status'] = $status_filter;
            }
            
            $whereClause = !empty($whereClauses) ? 'WHERE ' . implode(' AND ', $whereClauses) : '';
            
            $sql = "SELECT COUNT(*) as count FROM notes_chapters $whereClause";
            $stmt = $this->db->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] ?? 0;
            
        } catch (PDOException $e) {
            error_log("Count notes chapters error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Find chapter by ID
     */
    public function findById($id)
    {
        try {
            $sql = "SELECT * FROM notes_chapters WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            // Check if error is due to missing table
            if (strpos($e->getMessage(), "doesn't exist") !== false || strpos($e->getMessage(), "Table") !== false) {
                throw new Exception("Notes chapters table does not exist. Please run the database schema.");
            }
            error_log("Find chapter by ID error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Create new chapter
     */
    public function create($data)
    {
        try {
            // Get next display order
            $maxOrder = $this->getMaxDisplayOrder();
            $displayOrder = $data['display_order'] ?? ($maxOrder + 1);
            
            $sql = "
                INSERT INTO notes_chapters (
                    chapter_name, 
                    sub_chapter_name, 
                    parent_id,
                    description, 
                    thumbnail_image, 
                    display_order, 
                    status,
                    created_at,
                    updated_at
                ) VALUES (
                    :chapter_name, 
                    :sub_chapter_name, 
                    :parent_id,
                    :description, 
                    :thumbnail_image, 
                    :display_order, 
                    :status,
                    NOW(),
                    NOW()
                )
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':chapter_name', $data['chapter_name']);
            $stmt->bindValue(':sub_chapter_name', $data['sub_chapter_name'] ?? '');
            $stmt->bindValue(':parent_id', $data['parent_id'] ?? null);
            $stmt->bindValue(':description', $data['description'] ?? '');
            $stmt->bindValue(':thumbnail_image', $data['thumbnail_image'] ?? '');
            $stmt->bindValue(':display_order', $displayOrder, PDO::PARAM_INT);
            $stmt->bindValue(':status', $data['status'] ?? 'active');
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Create chapter error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update chapter
     */
    public function update($id, $data)
    {
        try {
            $sql = "
                UPDATE notes_chapters SET 
                    chapter_name = :chapter_name,
                    sub_chapter_name = :sub_chapter_name,
                    parent_id = :parent_id,
                    description = :description,
                    thumbnail_image = :thumbnail_image,
                    display_order = :display_order,
                    status = :status,
                    updated_at = NOW()
                WHERE id = :id
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':chapter_name', $data['chapter_name']);
            $stmt->bindValue(':sub_chapter_name', $data['sub_chapter_name'] ?? '');
            $stmt->bindValue(':parent_id', $data['parent_id'] ?? null);
            $stmt->bindValue(':description', $data['description'] ?? '');
            $stmt->bindValue(':thumbnail_image', $data['thumbnail_image'] ?? '');
            $stmt->bindValue(':display_order', $data['display_order'], PDO::PARAM_INT);
            $stmt->bindValue(':status', $data['status']);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Update chapter error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete chapter
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM notes_chapters WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Delete chapter error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update chapter status
     */
    public function updateStatus($id, $status)
    {
        try {
            $sql = "UPDATE notes_chapters SET status = :status, updated_at = NOW() WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':status', $status);
            
            return $stmt->execute();
            
        } catch (PDOException $e) {
            error_log("Update chapter status error: " . $e->getMessage());
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
                $sql = "UPDATE notes_chapters SET display_order = :order, updated_at = NOW() WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindValue(':id', $item['id'], PDO::PARAM_INT);
                $stmt->bindValue(':order', $item['order'], PDO::PARAM_INT);
                $stmt->execute();
            }
            
            $this->db->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->db->rollback();
            error_log("Update chapter order error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get maximum display order
     */
    private function getMaxDisplayOrder()
    {
        try {
            $sql = "SELECT MAX(display_order) as max_order FROM notes_chapters";
            $result = $this->db->fetch($sql);
            return $result['max_order'] ?? 0;
        } catch (Exception $e) {
            return 0;
        }
    }
    
    /**
     * Get chapter statistics
     */
    public function getStatistics()
    {
        try {
            $stats = [];
            
            // Total chapters
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM notes_chapters");
            $stats['total_chapters'] = $result['count'] ?? 0;
            
            // Active chapters
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM notes_chapters WHERE status = 'active'");
            $stats['active_chapters'] = $result['count'] ?? 0;
            
            // Inactive chapters
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM notes_chapters WHERE status = 'inactive'");
            $stats['inactive_chapters'] = $result['count'] ?? 0;
            
            // Chapters with thumbnails
            $result = $this->db->fetch("SELECT COUNT(*) as count FROM notes_chapters WHERE thumbnail_image IS NOT NULL AND thumbnail_image != ''");
            $stats['chapters_with_thumbnails'] = $result['count'] ?? 0;
            
            // Unique chapter names
            $result = $this->db->fetch("SELECT COUNT(DISTINCT chapter_name) as count FROM notes_chapters");
            $stats['unique_chapters'] = $result['count'] ?? 0;
            
            return $stats;
            
        } catch (Exception $e) {
            return [
                'total_chapters' => 0,
                'active_chapters' => 0,
                'inactive_chapters' => 0,
                'chapters_with_thumbnails' => 0,
                'unique_chapters' => 0
            ];
        }
    }
    
    /**
     * Get unique chapter names for dropdown
     */
    public function getUniqueChapterNames()
    {
        try {
            $sql = "SELECT DISTINCT chapter_name FROM notes_chapters WHERE chapter_name IS NOT NULL AND chapter_name != '' ORDER BY chapter_name";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
            
        } catch (PDOException $e) {
            error_log("Get unique chapter names error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Upload thumbnail image
     */
    public function uploadThumbnail($file, $chapterId = null)
    {
        try {
            // Validate file
            if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
                throw new Exception('No valid file uploaded');
            }
            
            // Check file type
            $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file['type'], $allowedTypes)) {
                throw new Exception('Invalid file type. Only JPG, PNG, GIF, and WebP are allowed.');
            }
            
            // Check file size (5MB max)
            if ($file['size'] > 5 * 1024 * 1024) {
                throw new Exception('File size too large. Maximum 5MB allowed.');
            }
            
            // Create upload directory if it doesn't exist
            $uploadDir = 'uploads/chapters/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Generate unique filename
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'chapter_' . ($chapterId ?? time()) . '_' . uniqid() . '.' . $extension;
            $filepath = $uploadDir . $filename;
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                return $filepath;
            } else {
                throw new Exception('Failed to upload file');
            }
            
        } catch (Exception $e) {
            error_log("Upload thumbnail error: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Delete thumbnail image
     */
    public function deleteThumbnail($imagePath)
    {
        try {
            if (!empty($imagePath) && file_exists($imagePath)) {
                return unlink($imagePath);
            }
            return true;
        } catch (Exception $e) {
            error_log("Delete thumbnail error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get main chapters (chapters without parent_id) for dropdown selection
     */
    public function getMainChapters()
    {
        try {
            $sql = "
                SELECT 
                    id,
                    chapter_name,
                    display_order
                FROM notes_chapters 
                WHERE (parent_id IS NULL OR parent_id = 0) 
                AND status = 'active'
                ORDER BY display_order ASC, chapter_name ASC
            ";
            
            return $this->db->query($sql);
            
        } catch (Exception $e) {
            error_log("Get main chapters error: " . $e->getMessage());
            throw new Exception("Error getting main chapters: " . $e->getMessage());
        }
    }
}
?>

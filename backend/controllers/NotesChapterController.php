<?php

class NotesChapterController extends BaseController
{
    private $notesChapterModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->notesChapterModel = new NotesChapter();
    }
    
    /**
     * Display notes chapters management page
     */
    public function index()
    {
        // Check authentication
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            header('Location: /admin/login');
            exit;
        }
        
        try {
            // Check if notes_chapters table exists
            if (!$this->notesChapterModel->checkTable()) {
                // Show setup message if table doesn't exist
                $data = [
                    'title' => 'Notes Chapters Management - Admin Panel',
                    'description' => 'Manage theory exam notes chapters',
                    'page_title' => 'Notes Chapters Management',
                    'page_description' => 'Create and manage notes chapters for theory exams',
                    'table_missing' => true
                ];
                $this->view('admin/generic-page', $data);
                return;
            }
            
            // Get filter parameters
            $search = $_GET['search'] ?? '';
            $status_filter = $_GET['status_filter'] ?? '';
            $page = max(1, intval($_GET['page'] ?? 1));
            $per_page = 20;
            $offset = ($page - 1) * $per_page;
            
            // Get chapters
            $chapters = $this->notesChapterModel->getAll($search, $status_filter, $per_page, $offset);
            
            // Get total count for pagination
            $total_chapters = $this->notesChapterModel->count($search, $status_filter);
            $total_pages = ceil($total_chapters / $per_page);
            
            // Get statistics
            $stats = $this->notesChapterModel->getStatistics();
            
            // Get unique chapter names for filter
            $unique_chapters = $this->notesChapterModel->getUniqueChapterNames();
            
            $data = [
                'title' => 'Notes Chapters Management - Admin Panel',
                'description' => 'Manage theory exam notes chapters',
                'page_title' => 'Notes Chapters Management',
                'page_description' => 'Create and manage notes chapters for theory exams',
                'chapters' => $chapters,
                'stats' => $stats,
                'unique_chapters' => $unique_chapters,
                'search' => $search,
                'status_filter' => $status_filter,
                'current_page' => $page,
                'total_pages' => $total_pages,
                'total_chapters' => $total_chapters,
                'table_missing' => false
            ];
            $this->view('admin/generic-page', $data);
            
        } catch (Exception $e) {
            error_log("Notes chapters index error: " . $e->getMessage());
            $data = [
                'title' => 'Notes Chapters Management - Admin Panel',
                'description' => 'Manage theory exam notes chapters',
                'page_title' => 'Notes Chapters Management',
                'page_description' => 'Create and manage notes chapters for theory exams',
                'error_message' => $e->getMessage(),
                'table_missing' => false
            ];
            $this->view('admin/generic-page', $data);
        }
    }
    
    /**
     * Get chapter details for modal
     */
    public function getChapterDetails()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $chapter_id = intval($_GET['chapter_id'] ?? 0);
        if (!$chapter_id) {
            $this->jsonResponse(['success' => false, 'message' => 'Chapter ID required']);
            return;
        }
        
        try {
            $chapter = $this->notesChapterModel->findById($chapter_id);
            if (!$chapter) {
                $this->jsonResponse(['success' => false, 'message' => 'Chapter not found']);
                return;
            }
            
            $this->jsonResponse(['success' => true, 'chapter' => $chapter]);
            
        } catch (Exception $e) {
            error_log("Get chapter details error: " . $e->getMessage());
            
            // Provide specific error messages
            if (strpos($e->getMessage(), "Notes chapters table does not exist") !== false) {
                $this->jsonResponse(['success' => false, 'message' => 'Notes chapters table not found. Please run the database schema first.']);
            } else {
                $this->jsonResponse(['success' => false, 'message' => 'Error loading chapter details: ' . $e->getMessage()]);
            }
        }
    }
    
    /**
     * Create new chapter
     */
    public function create()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate required fields
                $chapter_name = trim($_POST['chapter_name'] ?? '');
                if (empty($chapter_name)) {
                    $this->jsonResponse(['success' => false, 'message' => 'Chapter name is required']);
                    return;
                }
                
                $data = [
                    'chapter_name' => $chapter_name,
                    'sub_chapter_name' => trim($_POST['sub_chapter_name'] ?? ''),
                    'parent_id' => !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : null,
                    'description' => trim($_POST['description'] ?? ''),
                    'display_order' => intval($_POST['display_order'] ?? 0),
                    'status' => $_POST['status'] ?? 'active'
                ];
                
                // Handle thumbnail upload
                if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                    try {
                        $data['thumbnail_image'] = $this->notesChapterModel->uploadThumbnail($_FILES['thumbnail']);
                    } catch (Exception $e) {
                        $this->jsonResponse(['success' => false, 'message' => 'Thumbnail upload error: ' . $e->getMessage()]);
                        return;
                    }
                }
                
                if ($this->notesChapterModel->create($data)) {
                    $this->jsonResponse(['success' => true, 'message' => 'Chapter created successfully']);
                } else {
                    $this->jsonResponse(['success' => false, 'message' => 'Failed to create chapter']);
                }
                
            } catch (Exception $e) {
                error_log("Create chapter error: " . $e->getMessage());
                $this->jsonResponse(['success' => false, 'message' => 'Error creating chapter: ' . $e->getMessage()]);
            }
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    /**
     * Update chapter
     */
    public function update()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $chapter_id = intval($_POST['chapter_id'] ?? 0);
                if (!$chapter_id) {
                    $this->jsonResponse(['success' => false, 'message' => 'Chapter ID required']);
                    return;
                }
                
                // Check if chapter exists
                $existingChapter = $this->notesChapterModel->findById($chapter_id);
                if (!$existingChapter) {
                    $this->jsonResponse(['success' => false, 'message' => 'Chapter not found']);
                    return;
                }
                
                // Validate required fields
                $chapter_name = trim($_POST['chapter_name'] ?? '');
                if (empty($chapter_name)) {
                    $this->jsonResponse(['success' => false, 'message' => 'Chapter name is required']);
                    return;
                }
                
                $data = [
                    'chapter_name' => $chapter_name,
                    'sub_chapter_name' => trim($_POST['sub_chapter_name'] ?? ''),
                    'parent_id' => !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : null,
                    'description' => trim($_POST['description'] ?? ''),
                    'display_order' => intval($_POST['display_order'] ?? $existingChapter['display_order']),
                    'status' => $_POST['status'] ?? $existingChapter['status'],
                    'thumbnail_image' => $existingChapter['thumbnail_image'] // Keep existing by default
                ];
                
                // Handle thumbnail upload
                if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                    try {
                        // Delete old thumbnail if it exists
                        if (!empty($existingChapter['thumbnail_image'])) {
                            $this->notesChapterModel->deleteThumbnail($existingChapter['thumbnail_image']);
                        }
                        
                        $data['thumbnail_image'] = $this->notesChapterModel->uploadThumbnail($_FILES['thumbnail'], $chapter_id);
                    } catch (Exception $e) {
                        $this->jsonResponse(['success' => false, 'message' => 'Thumbnail upload error: ' . $e->getMessage()]);
                        return;
                    }
                } elseif (isset($_POST['remove_thumbnail']) && $_POST['remove_thumbnail'] === '1') {
                    // Remove thumbnail if requested
                    if (!empty($existingChapter['thumbnail_image'])) {
                        $this->notesChapterModel->deleteThumbnail($existingChapter['thumbnail_image']);
                        $data['thumbnail_image'] = '';
                    }
                }
                
                if ($this->notesChapterModel->update($chapter_id, $data)) {
                    $this->jsonResponse(['success' => true, 'message' => 'Chapter updated successfully']);
                } else {
                    $this->jsonResponse(['success' => false, 'message' => 'Failed to update chapter']);
                }
                
            } catch (Exception $e) {
                error_log("Update chapter error: " . $e->getMessage());
                $this->jsonResponse(['success' => false, 'message' => 'Error updating chapter: ' . $e->getMessage()]);
            }
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    /**
     * Delete chapter
     */
    public function delete()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            $chapter_id = intval($input['chapter_id'] ?? 0);
            if (!$chapter_id) {
                $this->jsonResponse(['success' => false, 'message' => 'Chapter ID required']);
                return;
            }
            
            try {
                // Check if chapter exists
                $chapter = $this->notesChapterModel->findById($chapter_id);
                if (!$chapter) {
                    $this->jsonResponse(['success' => false, 'message' => 'Chapter not found']);
                    return;
                }
                
                // Delete thumbnail if exists
                if (!empty($chapter['thumbnail_image'])) {
                    $this->notesChapterModel->deleteThumbnail($chapter['thumbnail_image']);
                }
                
                if ($this->notesChapterModel->delete($chapter_id)) {
                    $this->jsonResponse(['success' => true, 'message' => 'Chapter deleted successfully']);
                } else {
                    $this->jsonResponse(['success' => false, 'message' => 'Failed to delete chapter']);
                }
                
            } catch (Exception $e) {
                error_log("Delete chapter error: " . $e->getMessage());
                $this->jsonResponse(['success' => false, 'message' => 'Error deleting chapter: ' . $e->getMessage()]);
            }
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    /**
     * Update chapter status
     */
    public function updateStatus()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            $chapter_id = intval($input['chapter_id'] ?? 0);
            $status = $input['status'] ?? '';
            
            if (!$chapter_id) {
                $this->jsonResponse(['success' => false, 'message' => 'Chapter ID required']);
                return;
            }
            
            if (!in_array($status, ['active', 'inactive'])) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid status']);
                return;
            }
            
            try {
                // Check if chapter exists
                $chapter = $this->notesChapterModel->findById($chapter_id);
                if (!$chapter) {
                    $this->jsonResponse(['success' => false, 'message' => 'Chapter not found']);
                    return;
                }
                
                if ($this->notesChapterModel->updateStatus($chapter_id, $status)) {
                    $this->jsonResponse(['success' => true, 'message' => 'Chapter status updated successfully']);
                } else {
                    $this->jsonResponse(['success' => false, 'message' => 'Failed to update chapter status']);
                }
                
            } catch (Exception $e) {
                error_log("Update chapter status error: " . $e->getMessage());
                $this->jsonResponse(['success' => false, 'message' => 'Error updating status: ' . $e->getMessage()]);
            }
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    /**
     * Update display order
     */
    public function updateOrder()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            $orderData = $input['order_data'] ?? [];
            if (empty($orderData)) {
                $this->jsonResponse(['success' => false, 'message' => 'Order data required']);
                return;
            }
            
            try {
                if ($this->notesChapterModel->updateOrder($orderData)) {
                    $this->jsonResponse(['success' => true, 'message' => 'Display order updated successfully']);
                } else {
                    $this->jsonResponse(['success' => false, 'message' => 'Failed to update display order']);
                }
                
            } catch (Exception $e) {
                error_log("Update chapter order error: " . $e->getMessage());
                $this->jsonResponse(['success' => false, 'message' => 'Error updating order: ' . $e->getMessage()]);
            }
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    /**
     * Get main chapters (without parent_id) for dropdown
     */
    public function getMainChapters()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        try {
            $chapters = $this->notesChapterModel->getMainChapters();
            $this->jsonResponse(['success' => true, 'chapters' => $chapters]);
            
        } catch (Exception $e) {
            error_log("Get main chapters error: " . $e->getMessage());
            $this->jsonResponse(['success' => false, 'message' => 'Error loading main chapters: ' . $e->getMessage()]);
        }
    }
}
?>

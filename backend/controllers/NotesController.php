<?php

class NotesController extends BaseController
{
    private $notesModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->notesModel = new Notes();
    }
    
    /**
     * Display notes management page
     */
    public function index()
    {
        // Check authentication
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            header('Location: /admin/login');
            exit;
        }
        
        try {
            // Check if notes table exists
            if (!$this->notesModel->checkTable()) {
                // Show setup message if table doesn't exist
                $data = [
                    'title' => 'Notes Management - Admin Panel',
                    'description' => 'Manage theory exam notes',
                    'page_title' => 'Notes Management',
                    'page_description' => 'Create and manage theory exam notes',
                    'table_missing' => true
                ];
                $this->view('admin/generic-page', $data);
                return;
            }
            
            // Get filter parameters
            $search = $_GET['search'] ?? '';
            $status_filter = $_GET['status_filter'] ?? '';
            $type_filter = $_GET['type_filter'] ?? '';
            $chapter_filter = $_GET['chapter_filter'] ?? '';
            $page = max(1, intval($_GET['page'] ?? 1));
            $per_page = 20;
            $offset = ($page - 1) * $per_page;
            
            // Get notes
            $notes = $this->notesModel->getAll($search, $status_filter, $type_filter, $chapter_filter, $per_page, $offset);
            
            // Get total count for pagination
            $total_notes = $this->notesModel->count($search, $status_filter, $type_filter, $chapter_filter);
            $total_pages = ceil($total_notes / $per_page);
            
            // Get statistics
            $stats = $this->notesModel->getStatistics();
            
            // Get available chapters for filter
            $chapters = $this->notesModel->getChapters();
            
            $data = [
                'title' => 'Notes Management - Admin Panel',
                'description' => 'Manage theory exam notes',
                'page_title' => 'Notes Management',
                'page_description' => 'Create and manage theory exam notes',
                'notes' => $notes,
                'stats' => $stats,
                'chapters' => $chapters,
                'search' => $search,
                'status_filter' => $status_filter,
                'type_filter' => $type_filter,
                'chapter_filter' => $chapter_filter,
                'current_page' => $page,
                'total_pages' => $total_pages,
                'total_notes' => $total_notes,
                'table_missing' => false
            ];
            $this->view('admin/generic-page', $data);
            
        } catch (Exception $e) {
            error_log("Notes index error: " . $e->getMessage());
            $data = [
                'title' => 'Notes Management - Admin Panel',
                'description' => 'Manage theory exam notes',
                'page_title' => 'Notes Management',
                'page_description' => 'Create and manage theory exam notes',
                'error_message' => $e->getMessage(),
                'table_missing' => false
            ];
            $this->view('admin/generic-page', $data);
        }
    }
    
    /**
     * Get note details for modal
     */
    public function getNoteDetails()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $note_id = intval($_GET['note_id'] ?? 0);
        if (!$note_id) {
            $this->jsonResponse(['success' => false, 'message' => 'Note ID required']);
            return;
        }
        
        try {
            $note = $this->notesModel->findById($note_id);
            if (!$note) {
                $this->jsonResponse(['success' => false, 'message' => 'Note not found']);
                return;
            }
            
            $this->jsonResponse(['success' => true, 'note' => $note]);
            
        } catch (Exception $e) {
            error_log("Get note details error: " . $e->getMessage());
            
            // Provide specific error messages
            if (strpos($e->getMessage(), "Notes table does not exist") !== false) {
                $this->jsonResponse(['success' => false, 'message' => 'Notes table not found. Please run the database schema first.']);
            } else {
                $this->jsonResponse(['success' => false, 'message' => 'Error loading note details: ' . $e->getMessage()]);
            }
        }
    }
    
    /**
     * Create new note
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
                $title = trim($_POST['title'] ?? '');
                if (empty($title)) {
                    $this->jsonResponse(['success' => false, 'message' => 'Title is required']);
                    return;
                }
                
                $data = [
                    'title' => $title,
                    'content' => trim($_POST['content'] ?? ''),
                    'chapter_id' => $_POST['chapter_id'] ?: null,
                    'is_premium' => intval($_POST['is_premium'] ?? 0),
                    'display_order' => intval($_POST['display_order'] ?? 0),
                    'status' => $_POST['status'] ?? 'active'
                ];
                
                if ($this->notesModel->create($data)) {
                    $this->jsonResponse(['success' => true, 'message' => 'Note created successfully']);
                } else {
                    $this->jsonResponse(['success' => false, 'message' => 'Failed to create note']);
                }
                
            } catch (Exception $e) {
                error_log("Create note error: " . $e->getMessage());
                $this->jsonResponse(['success' => false, 'message' => 'Error creating note: ' . $e->getMessage()]);
            }
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    /**
     * Update note
     */
    public function update()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $note_id = intval($_POST['note_id'] ?? 0);
                if (!$note_id) {
                    $this->jsonResponse(['success' => false, 'message' => 'Note ID required']);
                    return;
                }
                
                // Check if note exists
                $existingNote = $this->notesModel->findById($note_id);
                if (!$existingNote) {
                    $this->jsonResponse(['success' => false, 'message' => 'Note not found']);
                    return;
                }
                
                // Validate required fields
                $title = trim($_POST['title'] ?? '');
                if (empty($title)) {
                    $this->jsonResponse(['success' => false, 'message' => 'Title is required']);
                    return;
                }
                
                $data = [
                    'title' => $title,
                    'content' => trim($_POST['content'] ?? ''),
                    'chapter_id' => $_POST['chapter_id'] ?: null,
                    'is_premium' => intval($_POST['is_premium'] ?? 0),
                    'display_order' => intval($_POST['display_order'] ?? $existingNote['display_order']),
                    'status' => $_POST['status'] ?? $existingNote['status']
                ];
                
                if ($this->notesModel->update($note_id, $data)) {
                    $this->jsonResponse(['success' => true, 'message' => 'Note updated successfully']);
                } else {
                    $this->jsonResponse(['success' => false, 'message' => 'Failed to update note']);
                }
                
            } catch (Exception $e) {
                error_log("Update note error: " . $e->getMessage());
                $this->jsonResponse(['success' => false, 'message' => 'Error updating note: ' . $e->getMessage()]);
            }
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    /**
     * Delete note
     */
    public function delete()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            $note_id = intval($input['note_id'] ?? 0);
            if (!$note_id) {
                $this->jsonResponse(['success' => false, 'message' => 'Note ID required']);
                return;
            }
            
            try {
                // Check if note exists
                $note = $this->notesModel->findById($note_id);
                if (!$note) {
                    $this->jsonResponse(['success' => false, 'message' => 'Note not found']);
                    return;
                }
                
                if ($this->notesModel->delete($note_id)) {
                    $this->jsonResponse(['success' => true, 'message' => 'Note deleted successfully']);
                } else {
                    $this->jsonResponse(['success' => false, 'message' => 'Failed to delete note']);
                }
                
            } catch (Exception $e) {
                error_log("Delete note error: " . $e->getMessage());
                $this->jsonResponse(['success' => false, 'message' => 'Error deleting note: ' . $e->getMessage()]);
            }
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    /**
     * Update note status
     */
    public function updateStatus()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            $note_id = intval($input['note_id'] ?? 0);
            $status = $input['status'] ?? '';
            
            if (!$note_id) {
                $this->jsonResponse(['success' => false, 'message' => 'Note ID required']);
                return;
            }
            
            if (!in_array($status, ['active', 'inactive'])) {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid status']);
                return;
            }
            
            try {
                // Check if note exists
                $note = $this->notesModel->findById($note_id);
                if (!$note) {
                    $this->jsonResponse(['success' => false, 'message' => 'Note not found']);
                    return;
                }
                
                if ($this->notesModel->updateStatus($note_id, $status)) {
                    $this->jsonResponse(['success' => true, 'message' => 'Note status updated successfully']);
                } else {
                    $this->jsonResponse(['success' => false, 'message' => 'Failed to update note status']);
                }
                
            } catch (Exception $e) {
                error_log("Update note status error: " . $e->getMessage());
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
                if ($this->notesModel->updateOrder($orderData)) {
                    $this->jsonResponse(['success' => true, 'message' => 'Display order updated successfully']);
                } else {
                    $this->jsonResponse(['success' => false, 'message' => 'Failed to update display order']);
                }
                
            } catch (Exception $e) {
                error_log("Update note order error: " . $e->getMessage());
                $this->jsonResponse(['success' => false, 'message' => 'Error updating order: ' . $e->getMessage()]);
            }
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method']);
        }
    }
    
    /**
     * Get chapters for dropdown (AJAX)
     */
    public function getChapters()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        try {
            $chapters = $this->notesModel->getChapters();
            $this->jsonResponse(['success' => true, 'chapters' => $chapters]);
        } catch (Exception $e) {
            error_log("Get chapters error: " . $e->getMessage());
            $this->jsonResponse(['success' => false, 'message' => 'Error loading chapters: ' . $e->getMessage()]);
        }
    }
}
?>

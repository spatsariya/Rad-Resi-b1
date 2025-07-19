<?php

class ContactController extends BaseController {
    private $userModel;
    private $messageModel;
    
    public function __construct() {
        parent::__construct();
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Require admin authentication
        $this->requireAdminAuth();
        
        $this->userModel = new User();
        $this->messageModel = new Message();
    }
    
    public function index() {
        // Get filter parameters
        $search = trim($_GET['search'] ?? '');
        $role_filter = $_GET['role_filter'] ?? '';
        $status_filter = $_GET['status_filter'] ?? '';
        $group_filter = $_GET['group_filter'] ?? '';
        $newsletter_filter = $_GET['newsletter_filter'] ?? '';
        $current_page = max(1, intval($_GET['page'] ?? 1));
        $per_page = 20;
        
        // Get contacts with search and filters
        $contactsData = $this->userModel->getContactsWithFilters([
            'search' => $search,
            'role' => $role_filter,
            'status' => $status_filter,
            'group_id' => $group_filter,
            'newsletter' => $newsletter_filter,
            'page' => $current_page,
            'per_page' => $per_page
        ]);
        
        // Get contact statistics
        $stats = $this->getContactStatistics();
        
        // Get contact groups
        $contact_groups = $this->getContactGroups();
        
        // Calculate pagination
        $total_contacts = $contactsData['total'];
        $total_pages = ceil($total_contacts / $per_page);
        
        // Prepare data for view
        $data = [
            'title' => 'Contact List - User Management',
            'description' => 'Manage contacts and communications with all users',
            'page_title' => 'Contact List',
            'page_description' => 'Manage contacts and communications with all users',
            'contacts' => $contactsData['contacts'],
            'total_contacts' => $total_contacts,
            'current_page' => $current_page,
            'total_pages' => $total_pages,
            'search' => $search,
            'role_filter' => $role_filter,
            'status_filter' => $status_filter,
            'group_filter' => $group_filter,
            'newsletter_filter' => $newsletter_filter,
            'stats' => $stats,
            'contact_groups' => $contact_groups
        ];
        
        $this->view('admin/generic-page', $data);
    }
    
    public function getUserInfo() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $user_id = intval($_GET['user_id'] ?? 0);
        if (!$user_id) {
            $this->jsonResponse(['success' => false, 'message' => 'User ID required']);
            return;
        }
        
        $user = $this->userModel->getUserById($user_id);
        if (!$user) {
            $this->jsonResponse(['success' => false, 'message' => 'User not found']);
            return;
        }
        
        $this->jsonResponse(['success' => true, 'user' => $user]);
    }
    
    public function sendMessage() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        $user_id = intval($_POST['user_id'] ?? 0);
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $message_type = $_POST['message_type'] ?? 'internal';
        
        if (!$user_id || !$subject || !$message) {
            $this->jsonResponse(['success' => false, 'message' => 'All fields are required']);
            return;
        }
        
        // Get recipient user
        $recipient = $this->userModel->getUserById($user_id);
        if (!$recipient) {
            $this->jsonResponse(['success' => false, 'message' => 'Recipient not found']);
            return;
        }
        
        try {
            $message_id = $this->messageModel->createMessage([
                'sender_id' => $_SESSION['user_id'],
                'recipient_id' => $user_id,
                'subject' => $subject,
                'message' => $message,
                'message_type' => $message_type,
                'status' => 'sent'
            ]);
            
            // Send external message if needed
            if ($message_type === 'email') {
                $this->sendEmail($recipient['email'], $subject, $message);
            } elseif ($message_type === 'sms' && !empty($recipient['phone'])) {
                $this->sendSMS($recipient['phone'], $subject . ': ' . $message);
            }
            
            // Log interaction
            $this->logContactInteraction($user_id, 'message_sent', [
                'message_id' => $message_id,
                'subject' => $subject,
                'type' => $message_type
            ]);
            
            $this->jsonResponse(['success' => true, 'message' => 'Message sent successfully']);
            
        } catch (Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to send message: ' . $e->getMessage()]);
        }
    }
    
    public function sendBulkMessage() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        $recipients = $_POST['recipients'] ?? '';
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');
        $message_type = $_POST['message_type'] ?? 'internal';
        $selected_contacts = json_decode($_POST['selected_contacts'] ?? '[]', true);
        
        if (!$subject || !$message) {
            $this->jsonResponse(['success' => false, 'message' => 'Subject and message are required']);
            return;
        }
        
        // Get recipient list based on criteria
        $recipient_users = $this->getRecipientList($recipients, $selected_contacts);
        
        if (empty($recipient_users)) {
            $this->jsonResponse(['success' => false, 'message' => 'No recipients found']);
            return;
        }
        
        $sent_count = 0;
        $failed_count = 0;
        
        foreach ($recipient_users as $recipient) {
            try {
                $message_id = $this->messageModel->createMessage([
                    'sender_id' => $_SESSION['user_id'],
                    'recipient_id' => $recipient['id'],
                    'subject' => $subject,
                    'message' => $message,
                    'message_type' => $message_type,
                    'status' => 'sent'
                ]);
                
                // Send external message if needed
                if ($message_type === 'email') {
                    $this->sendEmail($recipient['email'], $subject, $message);
                }
                
                // Log interaction
                $this->logContactInteraction($recipient['id'], 'bulk_message_sent', [
                    'message_id' => $message_id,
                    'subject' => $subject,
                    'type' => $message_type
                ]);
                
                $sent_count++;
                
            } catch (Exception $e) {
                $failed_count++;
            }
        }
        
        $this->jsonResponse([
            'success' => true, 
            'message' => "Bulk message sent to {$sent_count} recipients" . ($failed_count > 0 ? ", {$failed_count} failed" : "")
        ]);
    }
    
    public function getHistory() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->jsonResponse(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $user_id = intval($_GET['user_id'] ?? 0);
        if (!$user_id) {
            $this->jsonResponse(['success' => false, 'message' => 'User ID required']);
            return;
        }
        
        try {
            $history = $this->messageModel->getMessageHistory($user_id);
            $this->jsonResponse(['success' => true, 'history' => $history]);
        } catch (Exception $e) {
            // If messaging tables don't exist yet, return empty history
            $this->jsonResponse(['success' => true, 'history' => []]);
        }
    }
    
    private function getContactStatistics() {
        $pdo = Database::getInstance()->connect();
        
        $stats = [];
        
        try {
            // Total users
            $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
            $stats['total_users'] = $stmt->fetch()['total'];
            
            // Active users
            $stmt = $pdo->query("SELECT COUNT(*) as active FROM users WHERE status = 'active'");
            $stats['active_users'] = $stmt->fetch()['active'];
            
            // Newsletter subscribers
            $stmt = $pdo->query("SELECT COUNT(*) as newsletter FROM users WHERE newsletter = 1");
            $stats['newsletter_subscribers'] = $stmt->fetch()['newsletter'];
            
            // Messages sent today (check if table exists)
            try {
                $stmt = $pdo->query("SELECT COUNT(*) as today FROM messages WHERE DATE(created_at) = CURDATE()");
                $stats['messages_sent_today'] = $stmt->fetch()['today'];
            } catch (Exception $e) {
                $stats['messages_sent_today'] = 0;
            }
            
            // Total interactions (check if table exists)
            try {
                $stmt = $pdo->query("SELECT COUNT(*) as interactions FROM contact_interactions");
                $stats['total_interactions'] = $stmt->fetch()['interactions'];
            } catch (Exception $e) {
                $stats['total_interactions'] = 0;
            }
            
        } catch (Exception $e) {
            // Default values if there are any database errors
            $stats = [
                'total_users' => 0,
                'active_users' => 0,
                'newsletter_subscribers' => 0,
                'messages_sent_today' => 0,
                'total_interactions' => 0
            ];
        }
        
        return $stats;
    }
    
    private function getContactGroups() {
        $pdo = Database::getInstance()->connect();
        
        try {
            $stmt = $pdo->query("SELECT * FROM contact_groups ORDER BY name");
            return $stmt->fetchAll();
        } catch (Exception $e) {
            // Return empty array if table doesn't exist yet
            return [];
        }
    }
    
    private function getRecipientList($criteria, $selected_contacts = []) {
        $pdo = Database::getInstance()->connect();
        
        switch ($criteria) {
            case 'selected':
                if (empty($selected_contacts)) return [];
                $placeholders = str_repeat('?,', count($selected_contacts) - 1) . '?';
                $stmt = $pdo->prepare("SELECT * FROM users WHERE id IN ($placeholders)");
                $stmt->execute($selected_contacts);
                return $stmt->fetchAll();
                
            case 'all':
                $stmt = $pdo->query("SELECT * FROM users WHERE status = 'active'");
                return $stmt->fetchAll();
                
            case 'role_user':
                $stmt = $pdo->query("SELECT * FROM users WHERE role = 'user' AND status = 'active'");
                return $stmt->fetchAll();
                
            case 'role_student':
                $stmt = $pdo->query("SELECT * FROM users WHERE role = 'student' AND status = 'active'");
                return $stmt->fetchAll();
                
            case 'role_instructor':
                $stmt = $pdo->query("SELECT * FROM users WHERE role = 'instructor' AND status = 'active'");
                return $stmt->fetchAll();
                
            case 'newsletter':
                $stmt = $pdo->query("SELECT * FROM users WHERE newsletter = 1 AND status = 'active'");
                return $stmt->fetchAll();
                
            case 'active':
                $stmt = $pdo->query("SELECT * FROM users WHERE status = 'active'");
                return $stmt->fetchAll();
                
            default:
                return [];
        }
    }
    
    private function sendEmail($to, $subject, $message) {
        // Simple email sending using PHP mail function
        // In production, you should use a proper email service like PHPMailer or SwiftMailer
        $headers = "From: noreply@radiologyresident.com\r\n";
        $headers .= "Reply-To: admin@radiologyresident.com\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        $html_message = "
        <html>
        <body>
            <h2>Message from Radiology Resident Platform</h2>
            <p>" . nl2br(htmlspecialchars($message)) . "</p>
            <hr>
            <p><small>This message was sent from the Radiology Resident Platform administration.</small></p>
        </body>
        </html>
        ";
        
        return mail($to, $subject, $html_message, $headers);
    }
    
    private function sendSMS($phone, $message) {
        // SMS sending would require integration with services like Twilio, Nexmo, etc.
        // This is a placeholder for SMS functionality
        // For now, we'll just log it
        error_log("SMS to {$phone}: {$message}");
        return true;
    }
    
    private function logContactInteraction($user_id, $interaction_type, $metadata = []) {
        $pdo = Database::getInstance()->connect();
        
        $stmt = $pdo->prepare("
            INSERT INTO contact_interactions (user_id, admin_id, interaction_type, metadata, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        
        return $stmt->execute([
            $user_id,
            $_SESSION['user_id'],
            $interaction_type,
            json_encode($metadata)
        ]);
    }
    
    private function requireAdminAuth()
    {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin', 'instructor'])) {
            $this->redirect('/login');
            exit;
        }
    }
}

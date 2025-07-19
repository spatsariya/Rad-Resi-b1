<?php

class Message {
    private $pdo;
    
    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }
    
    public function createMessage($data) {
        $stmt = $this->pdo->prepare("
            INSERT INTO messages (sender_id, recipient_id, subject, message, message_type, status, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        
        $stmt->execute([
            $data['sender_id'],
            $data['recipient_id'],
            $data['subject'],
            $data['message'],
            $data['message_type'],
            $data['status']
        ]);
        
        return $this->pdo->lastInsertId();
    }
    
    public function getMessageHistory($user_id) {
        $stmt = $this->pdo->prepare("
            SELECT m.*, 
                   u_sender.first_name as sender_first_name, 
                   u_sender.last_name as sender_last_name,
                   u_recipient.first_name as recipient_first_name,
                   u_recipient.last_name as recipient_last_name
            FROM messages m
            LEFT JOIN users u_sender ON m.sender_id = u_sender.id
            LEFT JOIN users u_recipient ON m.recipient_id = u_recipient.id
            WHERE m.sender_id = ? OR m.recipient_id = ?
            ORDER BY m.created_at DESC
            LIMIT 50
        ");
        
        $stmt->execute([$user_id, $user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUserMessages($user_id, $limit = 20, $offset = 0) {
        $stmt = $this->pdo->prepare("
            SELECT m.*, 
                   u_sender.first_name as sender_first_name, 
                   u_sender.last_name as sender_last_name
            FROM messages m
            LEFT JOIN users u_sender ON m.sender_id = u_sender.id
            WHERE m.recipient_id = ?
            ORDER BY m.created_at DESC
            LIMIT ? OFFSET ?
        ");
        
        $stmt->execute([$user_id, $limit, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function markAsRead($message_id, $user_id) {
        $stmt = $this->pdo->prepare("
            UPDATE messages 
            SET status = 'read', read_at = NOW() 
            WHERE id = ? AND recipient_id = ?
        ");
        
        return $stmt->execute([$message_id, $user_id]);
    }
    
    public function getUnreadCount($user_id) {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) as unread_count 
            FROM messages 
            WHERE recipient_id = ? AND status = 'sent'
        ");
        
        $stmt->execute([$user_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['unread_count'];
    }
    
    public function deleteMessage($message_id, $user_id) {
        $stmt = $this->pdo->prepare("
            UPDATE messages 
            SET status = 'deleted' 
            WHERE id = ? AND (sender_id = ? OR recipient_id = ?)
        ");
        
        return $stmt->execute([$message_id, $user_id, $user_id]);
    }
    
    public function getMessageById($message_id) {
        $stmt = $this->pdo->prepare("
            SELECT m.*, 
                   u_sender.first_name as sender_first_name, 
                   u_sender.last_name as sender_last_name,
                   u_recipient.first_name as recipient_first_name,
                   u_recipient.last_name as recipient_last_name
            FROM messages m
            LEFT JOIN users u_sender ON m.sender_id = u_sender.id
            LEFT JOIN users u_recipient ON m.recipient_id = u_recipient.id
            WHERE m.id = ?
        ");
        
        $stmt->execute([$message_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getMessageStats() {
        $stats = [];
        
        // Total messages
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM messages WHERE status != 'deleted'");
        $stats['total_messages'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Messages today
        $stmt = $this->pdo->query("SELECT COUNT(*) as today FROM messages WHERE DATE(created_at) = CURDATE() AND status != 'deleted'");
        $stats['messages_today'] = $stmt->fetch(PDO::FETCH_ASSOC)['today'];
        
        // Unread messages
        $stmt = $this->pdo->query("SELECT COUNT(*) as unread FROM messages WHERE status = 'sent'");
        $stats['unread_messages'] = $stmt->fetch(PDO::FETCH_ASSOC)['unread'];
        
        return $stats;
    }
}

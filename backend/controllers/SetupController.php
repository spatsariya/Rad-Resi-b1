<?php
/**
 * Notes Database Setup Controller
 * Accessible through admin panel at /admin/setup-notes
 */

class SetupController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Setup notes database
     */
    public function setupNotes()
    {
        // Check authentication
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['admin'])) {
            header('Location: /admin/login');
            exit;
        }
        
        $data = [
            'title' => 'Notes Database Setup - Admin Panel',
            'description' => 'Setup notes database table and sample data',
            'page_title' => 'Notes Database Setup',
            'page_description' => 'Create notes table and populate with sample data'
        ];
        
        // If POST request, run the setup
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data['setup_results'] = $this->executeSetup();
        }
        
        $this->view('admin/generic-page', $data);
    }
    
    /**
     * Execute the database setup
     */
    private function executeSetup()
    {
        $results = [];
        $db = Database::getInstance();
        
        try {
            // Check if notes_chapters table exists first
            $chaptersExist = $db->fetchAll("SHOW TABLES LIKE 'notes_chapters'");
            if (empty($chaptersExist)) {
                return [
                    'success' => false,
                    'message' => 'notes_chapters table does not exist. Please set up Notes Chapters first.',
                    'details' => 'The notes table requires notes_chapters table for foreign key relationships.'
                ];
            }
            
            $results['chapters_check'] = 'Notes chapters table exists ✅';
            
            // Check if notes table already exists
            $notesExist = $db->fetchAll("SHOW TABLES LIKE 'notes'");
            if (!empty($notesExist)) {
                $results['table_exists'] = 'Notes table already exists - will recreate ⚠️';
            }
            
            // Create the notes table
            $this->createNotesTable();
            $results['table_created'] = 'Notes table created successfully ✅';
            
            // Insert sample data
            $insertCount = $this->insertSampleNotes();
            $results['data_inserted'] = "Inserted $insertCount sample notes ✅";
            
            // Create indexes
            $this->createIndexes();
            $results['indexes_created'] = 'Database indexes created ✅';
            
            // Create statistics view
            $this->createStatisticsView();
            $results['view_created'] = 'Statistics view created ✅';
            
            // Test the setup
            $testResults = $this->testSetup();
            $results['test_results'] = $testResults;
            
            return [
                'success' => true,
                'message' => 'Notes database setup completed successfully!',
                'details' => $results
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Setup failed: ' . $e->getMessage(),
                'details' => $results
            ];
        }
    }
    
    /**
     * Create the notes table
     */
    private function createNotesTable()
    {
        $db = Database::getInstance();
        
        $sql = "
        DROP TABLE IF EXISTS `notes`;
        
        CREATE TABLE `notes` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `title` varchar(255) NOT NULL,
            `content` longtext,
            `chapter_id` int(11) DEFAULT NULL,
            `is_premium` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0=Free, 1=Premium',
            `pdf_file` varchar(255) DEFAULT NULL COMMENT 'Path to uploaded PDF file',
            `view_count` int(11) NOT NULL DEFAULT 0,
            `display_order` int(11) NOT NULL DEFAULT 0,
            `status` enum('active','inactive') NOT NULL DEFAULT 'active',
            `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_chapter_id` (`chapter_id`),
            KEY `idx_status` (`status`),
            KEY `idx_is_premium` (`is_premium`),
            KEY `idx_display_order` (`display_order`),
            KEY `idx_view_count` (`view_count`),
            KEY `idx_created_at` (`created_at`),
            CONSTRAINT `fk_notes_chapter` FOREIGN KEY (`chapter_id`) REFERENCES `notes_chapters` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        // Split and execute each statement
        $statements = explode(';', $sql);
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement)) {
                $db->query($statement);
            }
        }
    }
    
    /**
     * Insert sample notes data
     */
    private function insertSampleNotes()
    {
        $db = Database::getInstance();
        
        $notes = [
            ['Chest X-ray Interpretation Basics', 'Systematic approach to chest X-ray interpretation:\n\n1. Patient details and technical factors\n2. Airways (trachea, main bronchi)\n3. Breathing (lungs, pleura)\n4. Circulation (heart, vessels)\n5. Disability (bones, soft tissues)\n6. Everything else\n\nKey signs to look for:\n- Pneumothorax\n- Pneumonia\n- Pleural effusion\n- Cardiomegaly\n- Pulmonary edema', 1, 0, 245, 1],
            ['CT Chest Protocol and Anatomy', 'High-resolution CT chest protocol:\n\n1. Patient preparation\n2. Contrast timing\n3. Breathing instructions\n4. Slice thickness\n5. Reconstruction algorithms\n\nKey anatomical landmarks:\n- Carina level\n- Aortic arch\n- Pulmonary trunk bifurcation\n- Left atrial level\n\nCommon pathology patterns:\n- Ground glass opacities\n- Honeycombing\n- Tree-in-bud\n- Crazy paving', 1, 1, 189, 2],
            ['Pulmonary Embolism Imaging', 'CT Pulmonary Angiogram (CTPA) technique:\n\n1. Contrast injection rate: 4-5 ml/sec\n2. Scan delay: 15-20 seconds\n3. Breath hold at inspiration\n4. Thin section reconstruction\n\nDirect signs:\n- Filling defects in pulmonary arteries\n- Complete vessel cutoff\n- Peripheral wedge-shaped opacity\n\nIndirect signs:\n- Pulmonary infarction\n- Right heart strain\n- Pleural effusion', 2, 1, 156, 1],
            ['Abdominal X-ray Systematic Review', 'Systematic approach to abdominal X-ray:\n\n1. Patient details\n2. Bowel gas pattern\n3. Organ outlines\n4. Bones\n5. Soft tissues\n6. Foreign objects\n\nNormal bowel gas:\n- Small bowel: <3cm diameter\n- Large bowel: <6cm diameter\n- Cecum: <9cm diameter\n\nPathological signs:\n- Bowel obstruction\n- Free air\n- Ascites\n- Mass effect', 3, 0, 298, 1],
            ['CT Abdomen and Pelvis Protocol', 'Triple-phase CT abdomen protocol:\n\n1. Non-contrast phase\n2. Arterial phase (25-30 sec)\n3. Portal venous phase (65-70 sec)\n4. Delayed phase (3-5 min) if needed\n\nContrast volume: 1.5-2 ml/kg\nInjection rate: 3-4 ml/sec\n\nKey phases for different organs:\n- Liver: Portal venous\n- Pancreas: Arterial\n- Kidneys: Nephrographic (100 sec)\n- Adrenals: Portal venous', 4, 1, 167, 1],
            ['Brain MRI Sequences and Applications', 'Standard brain MRI protocol:\n\n1. T1-weighted (anatomy)\n2. T2-weighted (pathology)\n3. FLAIR (CSF suppression)\n4. DWI (acute stroke)\n5. T2* GRE (hemorrhage)\n6. Contrast T1 (enhancement)\n\nSequence selection:\n- T1: Anatomy, contrast enhancement\n- T2: Pathology, edema\n- FLAIR: Periventricular lesions\n- DWI: Acute infarction\n- SWI: Microhemorrhages', 8, 1, 267, 1],
            ['Knee MRI Anatomy and Pathology', 'Knee MRI protocol:\n\n1. Sagittal PD/T2\n2. Coronal PD fat-sat\n3. Axial PD fat-sat\n4. Optional: T1-weighted\n\nKey structures to evaluate:\n- Anterior cruciate ligament\n- Posterior cruciate ligament\n- Medial meniscus\n- Lateral meniscus\n- Collateral ligaments\n- Cartilage surfaces\n\nCommon pathology:\n- ACL tear\n- Meniscal tears\n- Osteoarthritis\n- Bone marrow edema', 6, 1, 223, 1],
            ['Mammography Interpretation', 'BI-RADS assessment categories:\n\n0: Incomplete - need additional imaging\n1: Negative\n2: Benign finding\n3: Probably benign (<2% malignancy)\n4: Suspicious (2-95% malignancy)\n5: Highly suspicious (>95% malignancy)\n6: Known malignancy\n\nMammographic features:\n- Mass characteristics\n- Calcification patterns\n- Architectural distortion\n- Asymmetries', 12, 1, 234, 1],
            ['Radiation Safety Principles', 'ALARA principle:\n- As Low As Reasonably Achievable\n\nRadiation protection:\n1. Time - minimize exposure time\n2. Distance - inverse square law\n3. Shielding - lead protection\n\nDose monitoring:\n- Personal dosimeters\n- Area monitoring\n- Patient dose tracking\n\nPregnancy considerations:\n- Risk assessment\n- Alternative imaging\n- Shielding protocols', 16, 0, 298, 1],
            ['Pediatric Chest Imaging', 'Pediatric chest X-ray technique:\n\n1. AP projection preferred\n2. Inspiration timing\n3. Thymus appearance\n4. Heart size assessment\n\nNormal variants:\n- Thymic sail sign\n- Prominent pulmonary vessels\n- Round pneumonia pattern\n\nCommon pathology:\n- Respiratory distress syndrome\n- Bronchiolitis\n- Pneumonia patterns\n- Congenital anomalies', 10, 0, 156, 1]
        ];
        
        $count = 0;
        foreach ($notes as $note) {
            $sql = "INSERT INTO notes (title, content, chapter_id, is_premium, view_count, display_order, status) VALUES (?, ?, ?, ?, ?, ?, 'active')";
            $db->query($sql, $note);
            $count++;
        }
        
        return $count;
    }
    
    /**
     * Create database indexes
     */
    private function createIndexes()
    {
        $db = Database::getInstance();
        
        $indexes = [
            "CREATE INDEX idx_notes_title ON notes(title)",
            "CREATE INDEX idx_notes_composite ON notes(status, is_premium, display_order)"
        ];
        
        foreach ($indexes as $sql) {
            try {
                $db->query($sql);
            } catch (Exception $e) {
                // Index might already exist, ignore error
            }
        }
    }
    
    /**
     * Create statistics view
     */
    private function createStatisticsView()
    {
        $db = Database::getInstance();
        
        $sql = "
        CREATE OR REPLACE VIEW notes_statistics AS
        SELECT 
            COUNT(*) as total_notes,
            SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_notes,
            SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive_notes,
            SUM(CASE WHEN is_premium = 1 THEN 1 ELSE 0 END) as premium_notes,
            SUM(CASE WHEN is_premium = 0 THEN 1 ELSE 0 END) as free_notes,
            SUM(view_count) as total_views,
            AVG(view_count) as average_views,
            MAX(view_count) as max_views,
            COUNT(DISTINCT chapter_id) as chapters_with_notes
        FROM notes";
        
        $db->query($sql);
    }
    
    /**
     * Test the setup
     */
    private function testSetup()
    {
        $db = Database::getInstance();
        $tests = [];
        
        // Test table exists
        $result = $db->fetchAll("SHOW TABLES LIKE 'notes'");
        $tests['table_exists'] = !empty($result) ? '✅ Table exists' : '❌ Table missing';
        
        // Test record count
        $count = $db->fetch("SELECT COUNT(*) as count FROM notes");
        $tests['record_count'] = "✅ Records: " . ($count['count'] ?? 0);
        
        // Test statistics view
        try {
            $stats = $db->fetch("SELECT * FROM notes_statistics");
            $tests['statistics_view'] = $stats ? "✅ Statistics: {$stats['total_notes']} total, {$stats['active_notes']} active" : '❌ Statistics failed';
        } catch (Exception $e) {
            $tests['statistics_view'] = '❌ Statistics view error';
        }
        
        // Test Notes model
        try {
            require_once __DIR__ . '/../models/Notes.php';
            $notesModel = new Notes();
            $notes = $notesModel->getAll('', '', '', '', 5, 0);
            $tests['model_test'] = "✅ Model works: " . count($notes) . " notes retrieved";
        } catch (Exception $e) {
            $tests['model_test'] = '❌ Model error: ' . $e->getMessage();
        }
        
        return $tests;
    }
}
?>

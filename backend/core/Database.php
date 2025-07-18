<?php
/**
 * Database Connection Class
 * Handles database connections and basic operations
 */

class Database {
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $charset;
    private $pdo;
    private static $instance = null;

    public function __construct() {
        $this->host = DB_HOST;
        $this->dbname = DB_NAME;
        $this->username = DB_USER;
        $this->password = DB_PASS;
        $this->charset = DB_CHARSET;
    }

    /**
     * Get singleton instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Create PDO connection
     */
    public function connect() {
        if ($this->pdo === null) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->charset}"
                ];
                
                $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
                
                if (DEBUG_MODE) {
                    error_log("Database connection established successfully");
                }
                
            } catch (PDOException $e) {
                if (DEBUG_MODE) {
                    error_log("Database connection failed: " . $e->getMessage());
                    throw new Exception("Database connection failed: " . $e->getMessage());
                } else {
                    throw new Exception("Database connection failed");
                }
            }
        }
        
        return $this->pdo;
    }

    /**
     * Execute a query
     */
    public function query($sql, $params = []) {
        try {
            $pdo = $this->connect();
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Query execution failed: " . $e->getMessage());
                throw new Exception("Query execution failed: " . $e->getMessage());
            } else {
                throw new Exception("Query execution failed");
            }
        }
    }

    /**
     * Prepare a statement
     */
    public function prepare($sql) {
        try {
            $pdo = $this->connect();
            return $pdo->prepare($sql);
        } catch (PDOException $e) {
            if (DEBUG_MODE) {
                error_log("Statement preparation failed: " . $e->getMessage());
                throw new Exception("Statement preparation failed: " . $e->getMessage());
            } else {
                throw new Exception("Statement preparation failed");
            }
        }
    }

    /**
     * Fetch all results
     */
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Fetch single result
     */
    public function fetch($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    /**
     * Insert record and return last insert ID
     */
    public function insert($table, $data) {
        $keys = array_keys($data);
        $fields = implode(',', $keys);
        $placeholders = ':' . implode(', :', $keys);
        
        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
        $this->query($sql, $data);
        
        return $this->connect()->lastInsertId();
    }

    /**
     * Update record
     */
    public function update($table, $data, $where, $whereParams = []) {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "{$key} = :{$key}";
        }
        $setClause = implode(', ', $set);
        
        $sql = "UPDATE {$table} SET {$setClause} WHERE {$where}";
        $params = array_merge($data, $whereParams);
        
        return $this->query($sql, $params);
    }

    /**
     * Delete record
     */
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        return $this->query($sql, $params);
    }

    /**
     * Check if table exists
     */
    public function tableExists($table) {
        $sql = "SHOW TABLES LIKE :table";
        $result = $this->fetch($sql, ['table' => $table]);
        return !empty($result);
    }

    /**
     * Begin transaction
     */
    public function beginTransaction() {
        return $this->connect()->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public function commit() {
        return $this->connect()->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback() {
        return $this->connect()->rollBack();
    }
}
?>

<?php
/**
 * Universal CRUD - Create Operation
 * A simple, reusable class for creating records in any database table
 */

class UniversalCRUD_Create {
    protected $conn;
    protected $table;

    /**
     * Constructor to initialize database connection and table
     * @param string $table The table name to operate on
     */
    public function __construct($table) {
        $this->table = $table;
        $this->conn = $this->connectDB();
    }

    /**
     * Connect to database
     * @return PDO Database connection object
     */
    protected function connectDB() {
        $host = 'localhost';
        $dbname = 'ws310_db';  // Change this to your database name
        $username = 'root';
        $password = '';

        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    /**
     * Create a new record in the specified table
     * @param array $data Associative array of column => value pairs
     * @return int|bool ID of inserted record or false on failure
     */
    public function create($data) {
        // Get column names and values from the data array
        $columns = array_keys($data);
        $values = array_values($data);

        // Create placeholders for prepared statement
        $placeholders = str_repeat('?,', count($values) - 1) . '?';

        // Build SQL query
        $sql = "INSERT INTO {$this->table} (" . implode(',', $columns) . ") VALUES ($placeholders)";

        try {
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute($values);

            if ($result) {
                return $this->conn->lastInsertId();  // Return the ID of the new record
            }
            return false;
        } catch(PDOException $e) {
            error_log("Create error: " . $e->getMessage());
            return false;
        }
    }
}
?>
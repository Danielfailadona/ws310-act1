<?php
/**
 * Universal CRUD - Delete Operations
 * A simple, reusable class for deleting records from any database table
 */

class UniversalCRUD_Delete {
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
     * Delete records from the specified table
     * @param array $conditions Conditions for WHERE clause (column => value pairs)
     * @return bool True on success, false on failure
     */
    public function delete($conditions) {
        if (empty($conditions)) {
            return false;
        }

        // Prepare WHERE clause with placeholders
        $whereClauses = [];
        $params = [];

        foreach ($conditions as $column => $value) {
            $whereClauses[] = "$column = ?";
            $params[] = $value;
        }

        // Build SQL query
        $sql = "DELETE FROM {$this->table} WHERE " . implode(' AND ', $whereClauses);

        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        } catch(PDOException $e) {
            error_log("Delete error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a record by ID
     * @param mixed $id The ID of the record to delete
     * @param string $idColumn The name of the ID column (defaults to 'id')
     * @return bool True on success, false on failure
     */
    public function deleteById($id, $idColumn = 'id') {
        return $this->delete([$idColumn => $id]);
    }

    /**
     * Delete multiple records matching conditions
     * @param array $conditions Conditions for WHERE clause
     * @return int Number of affected rows
     */
    public function deleteMultiple($conditions) {
        if (empty($conditions)) {
            return 0;
        }

        // Prepare WHERE clause
        $whereClauses = [];
        $params = [];

        foreach ($conditions as $column => $value) {
            $whereClauses[] = "$column = ?";
            $params[] = $value;
        }

        // Build SQL query
        $sql = "DELETE FROM {$this->table} WHERE " . implode(' AND ', $whereClauses);

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();  // Return number of affected rows
        } catch(PDOException $e) {
            error_log("DeleteMultiple error: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Delete all records from the table
     * @return bool True on success, false on failure
     */
    public function deleteAll() {
        $sql = "DELETE FROM {$this->table}";

        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("DeleteAll error: " . $e->getMessage());
            return false;
        }
    }
}
?>
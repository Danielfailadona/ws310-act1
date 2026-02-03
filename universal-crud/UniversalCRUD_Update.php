<?php
/**
 * Universal CRUD - Update Operations
 * A simple, reusable class for updating records in any database table
 */

class UniversalCRUD_Update {
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
     * Update records in the specified table
     * @param array $data Associative array of column => new_value pairs to update
     * @param array $conditions Conditions for WHERE clause (column => value pairs)
     * @return bool True on success, false on failure
     */
    public function update($data, $conditions) {
        if (empty($data) || empty($conditions)) {
            return false;
        }

        // Prepare SET clause with placeholders
        $setClauses = [];
        $params = [];

        foreach ($data as $column => $value) {
            $setClauses[] = "$column = ?";
            $params[] = $value;
        }

        // Prepare WHERE clause with placeholders
        $whereClauses = [];
        foreach ($conditions as $column => $value) {
            $whereClauses[] = "$column = ?";
            $params[] = $value;
        }

        // Build the SQL query
        $sql = "UPDATE {$this->table} SET " . implode(',', $setClauses) . " WHERE " . implode(' AND ', $whereClauses);

        try {
            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        } catch(PDOException $e) {
            error_log("Update error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update a record by ID
     * @param array $data Associative array of column => new_value pairs
     * @param mixed $id The ID of the record to update
     * @param string $idColumn The name of the ID column (defaults to 'id')
     * @return bool True on success, false on failure
     */
    public function updateById($data, $id, $idColumn = 'id') {
        return $this->update($data, [$idColumn => $id]);
    }

    /**
     * Update multiple records matching conditions
     * @param array $data Associative array of column => new_value pairs
     * @param array $conditions Conditions for WHERE clause
     * @return int Number of affected rows
     */
    public function updateMultiple($data, $conditions) {
        if (empty($data) || empty($conditions)) {
            return 0;
        }

        // Prepare SET clause
        $setClauses = [];
        $params = [];

        foreach ($data as $column => $value) {
            $setClauses[] = "$column = ?";
            $params[] = $value;
        }

        // Prepare WHERE clause
        $whereClauses = [];
        foreach ($conditions as $column => $value) {
            $whereClauses[] = "$column = ?";
            $params[] = $value;
        }

        // Build SQL query
        $sql = "UPDATE {$this->table} SET " . implode(',', $setClauses) . " WHERE " . implode(' AND ', $whereClauses);

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();  // Return number of affected rows
        } catch(PDOException $e) {
            error_log("UpdateMultiple error: " . $e->getMessage());
            return 0;
        }
    }
}
?>
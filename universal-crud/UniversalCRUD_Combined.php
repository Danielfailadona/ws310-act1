<?php
/**
 * Universal CRUD - Combined Operations
 * A complete, reusable class for all CRUD operations on any database table
 */

class UniversalCRUD_Combined {
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

    /**
     * Read records from the specified table with optional conditions
     * @param array $conditions Optional conditions for WHERE clause (column => value pairs)
     * @param string $orderBy Optional column to order by
     * @param string $orderDirection Optional direction (ASC or DESC)
     * @param int $limit Optional limit for number of records
     * @param int $offset Optional offset for pagination
     * @return array Array of records or empty array if none found
     */
    public function read($conditions = [], $orderBy = '', $orderDirection = 'ASC', $limit = 0, $offset = 0) {
        // Start building the SQL query
        $sql = "SELECT * FROM {$this->table}";
        $params = [];

        // Add WHERE conditions if provided
        if (!empty($conditions)) {
            $whereClauses = [];
            foreach ($conditions as $column => $value) {
                $whereClauses[] = "$column = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        // Add ORDER BY if specified
        if (!empty($orderBy)) {
            $sql .= " ORDER BY $orderBy $orderDirection";
        }

        // Add LIMIT and OFFSET if specified
        if ($limit > 0) {
            $sql .= " LIMIT $limit";
            if ($offset > 0) {
                $sql .= " OFFSET $offset";
            }
        }

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Read error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get a single record by ID
     * @param mixed $id The ID value to search for
     * @param string $idColumn The name of the ID column (defaults to 'id')
     * @return array|null Single record or null if not found
     */
    public function readOne($id, $idColumn = 'id') {
        $sql = "SELECT * FROM {$this->table} WHERE $idColumn = ?";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("ReadOne error: " . $e->getMessage());
            return null;
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
     * Get count of records matching conditions
     * @param array $conditions Optional conditions for WHERE clause
     * @return int Number of records
     */
    public function count($conditions = []) {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $params = [];

        if (!empty($conditions)) {
            $whereClauses = [];
            foreach ($conditions as $column => $value) {
                $whereClauses[] = "$column = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClauses);
        }

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'];
        } catch(PDOException $e) {
            error_log("Count error: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Search records with LIKE operator
     * @param array $searchFields Associative array of column => search_value pairs
     * @param string $matchType Type of match ('any' for OR, 'all' for AND)
     * @return array Array of matching records
     */
    public function search($searchFields, $matchType = 'any') {
        if (empty($searchFields)) {
            return [];
        }

        $whereClauses = [];
        $params = [];

        foreach ($searchFields as $column => $value) {
            $whereClauses[] = "$column LIKE ?";
            $params[] = "%$value%";  // Add wildcards for partial matching
        }

        $operator = ($matchType === 'all') ? ' AND ' : ' OR ';
        $sql = "SELECT * FROM {$this->table} WHERE " . implode($operator, $whereClauses);

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            error_log("Search error: " . $e->getMessage());
            return [];
        }
    }
}
?>
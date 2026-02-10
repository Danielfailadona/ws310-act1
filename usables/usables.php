<?php
/**
 * Usables.php - Reusable functions for the WS310-ACT1 project
 * Contains dynamic, reusable functions that can be used across the project
 */

// Create directory if it doesn't exist
if (!is_dir(dirname(__FILE__))) {
    mkdir(dirname(__FILE__), 0755, true);
}

//================================================================================================
//                           getDataFromDatabase
//================================================================================================
/**
 * Fetches data from the database
 * @param string $table The table name to query
 * @param array $columns Columns to select (empty array means all *)
 * @param array $where Associative array of column => value pairs for WHERE clause
 * @param string $orderBy Column to order by
 * @param string $orderDir Direction of order (ASC or DESC)
 * @param int $limit Limit of records
 * @param int $offset Offset for pagination
 * @param array $config Database configuration
 * @return array Array of records
 */
function getDataFromDatabase($table, $columns = [], $where = [], $orderBy = '', $orderDir = 'ASC', $limit = 0, $offset = 0, $config = []) {
    try {
        $conn = getConnection($config);
        if (!$conn) {
            error_log("DEBUG: Database connection failed in getDataFromDatabase");
            throw new Exception("Database connection failed");
        }

        error_log("DEBUG: Database connection successful in getDataFromDatabase");

        // Prepare columns
        $columnList = empty($columns) ? '*' : implode(', ', $columns);

        // Prepare WHERE clause if conditions exist
        $whereClause = '';
        if (!empty($where)) {
            $whereParts = [];
            foreach (array_keys($where) as $column) {
                $whereParts[] = "{$column} = :{$column}";
            }
            $whereClause = 'WHERE ' . implode(' AND ', $whereParts);
        }

        // Prepare ORDER BY clause
        $orderClause = !empty($orderBy) ? "ORDER BY {$orderBy} {$orderDir}" : '';

        // Prepare LIMIT clause
        $limitClause = '';
        if ($limit > 0) {
            $limitClause = "LIMIT " . (int)$limit;
            if ($offset > 0) {
                $limitClause .= " OFFSET " . (int)$offset;
            }
        }

        $query = "SELECT {$columnList} FROM {$table} {$whereClause} {$orderClause} {$limitClause}";
        error_log("DEBUG: Executing query: " . $query);
        
        $stmt = $conn->prepare($query);

        // Bind parameters if WHERE conditions exist
        if (!empty($where)) {
            foreach ($where as $column => $value) {
                $stmt->bindValue(":{$column}", $value);
            }
        }

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        error_log("DEBUG: Query returned " . count($result) . " records");
        return $result;
    } catch(Exception $e) {
        error_log("Select error: " . $e->getMessage());
        // Re-throw the exception to allow it to be caught by the calling function
        throw $e;
    }
}

/*
 * USAGE EXAMPLE:
 * $users = getDataFromDatabase('users', ['id', 'name', 'email'], ['status' => 'active'], 'name', 'ASC', 10, 0);
 * foreach ($users as $user) {
 *     echo $user['name'];
 * }
 */


//================================================================================================
//                           insertDataToDatabase
//================================================================================================
/**
 * Inserts data to the database
 * @param string $table The table name to insert into
 * @param array $data Associative array of column => value pairs to insert
 * @param array $config Database configuration
 * @return int|bool ID of inserted record or false on failure
 */
function insertDataToDatabase($table, $data, $config = []) {
    try {
        $conn = getConnection($config);
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        // Prepare columns and placeholders
        $columns = array_keys($data);
        $placeholders = ':' . implode(', :', $columns);
        $columnList = implode(', ', $columns);

        $query = "INSERT INTO {$table} ({$columnList}) VALUES ({$placeholders})";
        $stmt = $conn->prepare($query);

        // Bind parameters
        foreach ($data as $column => $value) {
            $stmt->bindValue(":{$column}", $value);
        }

        if ($stmt->execute()) {
            return $conn->lastInsertId() ?: true; // Return ID if available, otherwise true
        }

        return false;
    } catch(Exception $e) {
        error_log("Insert error: " . $e->getMessage());
        return false;
    }
}

/*
 * USAGE EXAMPLE:
 * $data = ['name' => 'John', 'email' => 'john@example.com'];
 * $id = insertDataToDatabase('users', $data);
 * if ($id) {
 *     echo "Record inserted with ID: $id";
 * }
 */


//================================================================================================
//                           updateDataInDatabase
//================================================================================================
/**
 * Updates data in the database
 * @param string $table The table name to update
 * @param array $data Associative array of column => value pairs to update
 * @param array $where Associative array of column => value pairs for WHERE clause
 * @param array $config Database configuration
 * @return bool True on success, false on failure
 */
function updateDataInDatabase($table, $data, $where, $config = []) {
    try {
        $conn = getConnection($config);
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        // Prepare SET clause
        $setParts = [];
        foreach (array_keys($data) as $column) {
            $setParts[] = "{$column} = :set_{$column}";
        }
        $setClause = implode(', ', $setParts);

        // Prepare WHERE clause
        $whereParts = [];
        foreach (array_keys($where) as $column) {
            $whereParts[] = "{$column} = :where_{$column}";
        }
        $whereClause = implode(' AND ', $whereParts);

        $query = "UPDATE {$table} SET {$setClause} WHERE {$whereClause}";
        $stmt = $conn->prepare($query);

        // Bind data parameters
        foreach ($data as $column => $value) {
            $stmt->bindValue(":set_{$column}", $value);
        }

        // Bind where parameters
        foreach ($where as $column => $value) {
            $stmt->bindValue(":where_{$column}", $value);
        }

        return $stmt->execute();
    } catch(Exception $e) {
        error_log("Update error: " . $e->getMessage());
        return false;
    }
}

/*
 * USAGE EXAMPLE:
 * $data = ['name' => 'John Updated', 'email' => 'john.updated@example.com'];
 * $where = ['id' => 123];
 * $success = updateDataInDatabase('users', $data, $where);
 * if ($success) {
 *     echo "Record updated successfully";
 * }
 */


//================================================================================================
//                           deleteDataFromDatabase
//================================================================================================
/**
 * Deletes data from the database
 * @param string $table The table name to delete from
 * @param array $where Associative array of column => value pairs for WHERE clause
 * @param array $config Database configuration
 * @return bool True on success, false on failure
 */
function deleteDataFromDatabase($table, $where, $config = []) {
    try {
        $conn = getConnection($config);
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        // Prepare WHERE clause
        $whereParts = [];
        foreach (array_keys($where) as $column) {
            $whereParts[] = "{$column} = :{$column}";
        }
        $whereClause = implode(' AND ', $whereParts);

        $query = "DELETE FROM {$table} WHERE {$whereClause}";
        $stmt = $conn->prepare($query);

        // Bind parameters
        foreach ($where as $column => $value) {
            $stmt->bindValue(":{$column}", $value);
        }

        return $stmt->execute();
    } catch(Exception $e) {
        error_log("Delete error: " . $e->getMessage());
        return false;
    }
}

/*
 * USAGE EXAMPLE:
 * $where = ['id' => 123];
 * $success = deleteDataFromDatabase('users', $where);
 * if ($success) {
 *     echo "Record deleted successfully";
 * }
 */


//================================================================================================
//                           getSingleRecord
//================================================================================================
/**
 * Gets a single record from the database
 * @param string $table The table name to query
 * @param mixed $id The ID value
 * @param string $idColumn The name of the ID column (default: 'id')
 * @param array $config Database configuration
 * @return array|null The record or null if not found
 */
function getSingleRecord($table, $id, $idColumn = 'id', $config = []) {
    $records = getDataFromDatabase($table, [], [$idColumn => $id], '', 'ASC', 1, 0, $config);
    return !empty($records) ? $records[0] : null;
}

/*
 * USAGE EXAMPLE:
 * $user = getSingleRecord('users', 123);
 * if ($user) {
 *     echo "User found: " . $user['name'];
 * } else {
 *     echo "User not found";
 * }
 */


//================================================================================================
//                           recordExists
//================================================================================================
/**
 * Checks if a record exists in the database
 * @param string $table The table name
 * @param array $where Conditions to check
 * @param array $config Database configuration
 * @return bool True if record exists, false otherwise
 */
function recordExists($table, $where, $config = []) {
    $records = getDataFromDatabase($table, ['COUNT(*) as count'], $where, '', 'ASC', 1, 0, $config);
    return !empty($records) && $records[0]['count'] > 0;
}

/*
 * USAGE EXAMPLE:
 * if (recordExists('users', ['email' => 'test@example.com'])) {
 *     echo "User already exists";
 * } else {
 *     echo "User does not exist";
 * }
 */


//================================================================================================
//                           getConnection
//================================================================================================
/**
 * Establishes a database connection
 * @param array $config Configuration array with host, dbname, username, password
 * @return PDO|null Database connection object or null on failure
 */
function getConnection($config = []) {
    // Default configuration
    $defaultConfig = [
        'host' => 'localhost',
        'dbname' => 'ws310_db',  // You may need to adjust this to match your actual database name
        'username' => 'root',    // Adjust if using different username
        'password' => ''         // Adjust if using password
    ];

    // Merge provided config with defaults
    $config = array_merge($defaultConfig, $config);

    try {
        $conn = new PDO("mysql:host={$config['host']};dbname={$config['dbname']}",
                       $config['username'],
                       $config['password']);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        error_log("Connection failed: " . $e->getMessage());
        return null;
    }
}

/*
 * USAGE EXAMPLE:
 * $conn = getConnection();
 * $conn = getConnection(['host' => 'localhost', 'dbname' => 'mydb', 'username' => 'user', 'password' => 'pass']);
 */


//================================================================================================
//                           clean
//================================================================================================
/**
 * Sanitizes and cleans input data
 * @param mixed $value The value to clean
 * @param bool $upper Whether to convert to uppercase
 * @param bool $trim Whether to trim whitespace
 * @return string The cleaned value
 */
function clean($value, $upper = false, $trim = true) {
    $value = $value ?? '';

    if ($trim) {
        $value = trim($value);
    }

    if ($upper) {
        $value = strtoupper($value);
    }

    return $value;
}

/*
 * USAGE EXAMPLE:
 * $cleanValue = clean($_POST['user_input']);
 * $upperValue = clean($_POST['user_input'], true); // Convert to uppercase
 */


//================================================================================================
//                           validateRequired
//================================================================================================
/**
 * Validates if a value is required (not empty)
 * @param mixed $value The value to validate
 * @return bool True if valid, false otherwise
 */
function validateRequired($value) {
    return !empty(clean($value));
}

/*
 * USAGE EXAMPLE:
 * if (validateRequired($_POST['name'])) {
 *     // Name is provided
 * } else {
 *     // Name is required
 * }
 */


//================================================================================================
//                           validateEmail
//================================================================================================
/**
 * Validates email format
 * @param string $email The email to validate
 * @return bool True if valid, false otherwise
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/*
 * USAGE EXAMPLE:
 * if (validateEmail($_POST['email'])) {
 *     // Email is valid
 * } else {
 *     // Invalid email format
 * }
 */


//================================================================================================
//                           validatePhone
//================================================================================================
/**
 * Validates Philippine phone number format
 * @param string $phone The phone number to validate
 * @return bool True if valid, false otherwise
 */
function validatePhone($phone) {
    return preg_match('/^(09\d{9}|(\+639)\d{9})$/', preg_replace('/\D/', '', $phone));
}


/*
 * USAGE EXAMPLE:
 * if (validatePhone($_POST['phone'])) {
 *     // Phone number is valid
 * } else {
 *     // Invalid phone number format
 * }
 */


//================================================================================================
//                           validatePastDate
//================================================================================================
/**
 * Validates date to ensure it's in the past
 * @param string $date The date to validate
 * @return bool True if valid, false otherwise
 */
function validatePastDate($date) {
    if (empty($date)) return false;

    $dateObj = new DateTime($date);
    $currentDate = new DateTime();

    return $dateObj < $currentDate;
}

/*
 * USAGE EXAMPLE:
 * if (validatePastDate($_POST['birth_date'])) {
 *     // Date is in the past
 * } else {
 *     // Date is not in the past
 * }
 */


//================================================================================================
//                           formatDateForDB
//================================================================================================
/**
 * Formats a date string to MySQL DATE format (Y-m-d)
 * @param string $date The date string to format
 * @return string|null Formatted date or null if empty
 */
function formatDateForDB($date) {
    if (empty($date)) return null;
    return date('Y-m-d', strtotime($date));
}

/*
 * USAGE EXAMPLE:
 * $formattedDate = formatDateForDB($_POST['date_field']);
 * // Returns '2023-01-15' for input '2023-01-15' or equivalent
 */


//================================================================================================
//                           insertRecord
//================================================================================================
/**
 * Generic insert function for any table
 * @param string $table The table name
 * @param array $data Associative array of column => value pairs
 * @param array $config Database configuration
 * @return int|bool ID of inserted record or false on failure
 */
function insertRecord($table, $data, $config = []) {
    try {
        $conn = getConnection($config);
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        // Prepare columns and placeholders
        $columns = array_keys($data);
        $placeholders = ':' . implode(', :', $columns);
        $columnList = implode(', ', $columns);

        $query = "INSERT INTO {$table} ({$columnList}) VALUES ({$placeholders})";
        $stmt = $conn->prepare($query);

        // Bind parameters
        foreach ($data as $column => $value) {
            $stmt->bindValue(":{$column}", $value);
        }

        if ($stmt->execute()) {
            return $conn->lastInsertId() ?: true; // Return ID if available, otherwise true
        }

        return false;
    } catch(Exception $e) {
        error_log("Insert error: " . $e->getMessage());
        return false;
    }
}

/*
 * USAGE EXAMPLE:
 * $data = ['name' => 'John', 'email' => 'john@example.com'];
 * $id = insertRecord('users', $data);
 * if ($id) {
 *     echo "Record inserted with ID: $id";
 * }
 */


//================================================================================================
//                           updateRecord
//================================================================================================
/**
 * Generic update function for any table
 * @param string $table The table name
 * @param array $data Associative array of column => value pairs to update
 * @param array $where Associative array of column => value pairs for WHERE clause
 * @param array $config Database configuration
 * @return bool True on success, false on failure
 */
function updateRecord($table, $data, $where, $config = []) {
    try {
        $conn = getConnection($config);
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        // Prepare SET clause
        $setParts = [];
        foreach (array_keys($data) as $column) {
            $setParts[] = "{$column} = :set_{$column}";
        }
        $setClause = implode(', ', $setParts);

        // Prepare WHERE clause
        $whereParts = [];
        foreach (array_keys($where) as $column) {
            $whereParts[] = "{$column} = :where_{$column}";
        }
        $whereClause = implode(' AND ', $whereParts);

        $query = "UPDATE {$table} SET {$setClause} WHERE {$whereClause}";
        $stmt = $conn->prepare($query);

        // Bind data parameters
        foreach ($data as $column => $value) {
            $stmt->bindValue(":set_{$column}", $value);
        }

        // Bind where parameters
        foreach ($where as $column => $value) {
            $stmt->bindValue(":where_{$column}", $value);
        }

        return $stmt->execute();
    } catch(Exception $e) {
        error_log("Update error: " . $e->getMessage());
        return false;
    }
}

/*
 * USAGE EXAMPLE:
 * $data = ['name' => 'John Updated', 'email' => 'john.updated@example.com'];
 * $where = ['id' => 123];
 * $success = updateRecord('users', $data, $where);
 * if ($success) {
 *     echo "Record updated successfully";
 * }
 */


//================================================================================================
//                           deleteRecord
//================================================================================================
/**
 * Generic delete function for any table
 * @param string $table The table name
 * @param array $where Associative array of column => value pairs for WHERE clause
 * @param array $config Database configuration
 * @return bool True on success, false on failure
 */
function deleteRecord($table, $where, $config = []) {
    try {
        $conn = getConnection($config);
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        // Prepare WHERE clause
        $whereParts = [];
        foreach (array_keys($where) as $column) {
            $whereParts[] = "{$column} = :{$column}";
        }
        $whereClause = implode(' AND ', $whereParts);

        $query = "DELETE FROM {$table} WHERE {$whereClause}";
        $stmt = $conn->prepare($query);

        // Bind parameters
        foreach ($where as $column => $value) {
            $stmt->bindValue(":{$column}", $value);
        }

        return $stmt->execute();
    } catch(Exception $e) {
        error_log("Delete error: " . $e->getMessage());
        return false;
    }
}

/*
 * USAGE EXAMPLE:
 * $where = ['id' => 123];
 * $success = deleteRecord('users', $where);
 * if ($success) {
 *     echo "Record deleted successfully";
 * }
 */


//================================================================================================
//                           selectRecords
//================================================================================================
/**
 * Generic select function for any table
 * @param string $table The table name
 * @param array $columns Columns to select (empty array means all *)
 * @param array $where Associative array of column => value pairs for WHERE clause
 * @param string $orderBy Column to order by
 * @param string $orderDir Direction of order (ASC or DESC)
 * @param int $limit Limit of records
 * @param int $offset Offset for pagination
 * @param array $config Database configuration
 * @return array Array of records
 */
function selectRecords($table, $columns = [], $where = [], $orderBy = '', $orderDir = 'ASC', $limit = 0, $offset = 0, $config = []) {
    try {
        $conn = getConnection($config);
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        // Prepare columns
        $columnList = empty($columns) ? '*' : implode(', ', $columns);

        // Prepare WHERE clause if conditions exist
        $whereClause = '';
        if (!empty($where)) {
            $whereParts = [];
            foreach (array_keys($where) as $column) {
                $whereParts[] = "{$column} = :{$column}";
            }
            $whereClause = 'WHERE ' . implode(' AND ', $whereParts);
        }

        // Prepare ORDER BY clause
        $orderClause = !empty($orderBy) ? "ORDER BY {$orderBy} {$orderDir}" : '';

        // Prepare LIMIT clause
        $limitClause = '';
        if ($limit > 0) {
            $limitClause = "LIMIT " . (int)$limit;
            if ($offset > 0) {
                $limitClause .= " OFFSET " . (int)$offset;
            }
        }

        $query = "SELECT {$columnList} FROM {$table} {$whereClause} {$orderClause} {$limitClause}";
        $stmt = $conn->prepare($query);

        // Bind parameters if WHERE conditions exist
        if (!empty($where)) {
            foreach ($where as $column => $value) {
                $stmt->bindValue(":{$column}", $value);
            }
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(Exception $e) {
        error_log("Select error: " . $e->getMessage());
        return [];
    }
}

/*
 * USAGE EXAMPLE:
 * $users = selectRecords('users', ['id', 'name', 'email'], ['status' => 'active'], 'name', 'ASC', 10, 0);
 * foreach ($users as $user) {
 *     echo $user['name'];
 * }
 */


//================================================================================================
//                           validateData
//================================================================================================
/**
 * Generic validation function that takes an array of validation rules
 * @param array $data The data to validate
 * @param array $rules Associative array of field => rules
 * @return array Array of validation errors
 */
function validateData($data, $rules) {
    $errors = [];

    foreach ($rules as $field => $ruleSet) {
        $value = $data[$field] ?? '';

        // Split rules by '|'
        $individualRules = explode('|', $ruleSet);

        foreach ($individualRules as $rule) {
            $rule = trim($rule);

            switch ($rule) {
                case 'required':
                    if (!validateRequired($value)) {
                        $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required";
                    }
                    break;

                case 'email':
                    if (!empty($value) && !validateEmail($value)) {
                        $errors[] = ucfirst(str_replace('_', ' ', $field)) . " must be a valid email";
                    }
                    break;

                case 'phone':
                    if (!empty($value) && !validatePhone($value)) {
                        $errors[] = ucfirst(str_replace('_', ' ', $field)) . " must be a valid Philippine phone number";
                    }
                    break;

                case 'past_date':
                    if (!empty($value) && !validatePastDate($value)) {
                        $errors[] = ucfirst(str_replace('_', ' ', $field)) . " must be a date in the past";
                    }
                    break;
            }
        }
    }

    return $errors;
}

/*
 * USAGE EXAMPLE:
 * $data = ['name' => 'John', 'email' => 'invalid-email'];
 * $rules = ['name' => 'required', 'email' => 'required|email'];
 * $errors = validateData($data, $rules);
 * if (empty($errors)) {
 *     // Data is valid
 * } else {
 *     // Handle errors
 * }
 */


//================================================================================================
//                           processFormData
//================================================================================================
/**
 * Processes form data with validation and database operations
 * @param array $formData The form data ($_POST typically)
 * @param array $validationRules Validation rules for the form
 * @param array $tableMappings Mapping of form fields to database tables
 * @param string $operation Type of operation (insert or update)
 * @param array $config Database configuration
 * @return array Result of the operation
 */
function processFormData($formData, $validationRules, $tableMappings, $operation = 'insert', $config = []) {
    // Validate the data
    $errors = validateData($formData, $validationRules);

    if (!empty($errors)) {
        return [
            'success' => false,
            'message' => implode(', ', $errors),
            'errors' => $errors
        ];
    }

    try {
        $conn = getConnection($config);
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        $conn->beginTransaction();

        $results = [];
        $successCount = 0;

        foreach ($tableMappings as $mapping) {
            $table = $mapping['table'];
            $fields = $mapping['fields']; // Array mapping form fields to DB columns
            $whereField = $mapping['where_field'] ?? null; // Field to use for WHERE in updates

            // Prepare data for this table
            $tableData = [];
            foreach ($fields as $formField => $dbColumn) {
                if (isset($formData[$formField])) {
                    $value = $formData[$formField];

                    // Handle special cases like date formatting
                    if (strpos($formField, 'date') !== false || strpos($formField, 'birth') !== false) {
                        $value = formatDateForDB($value);
                    }

                    $tableData[$dbColumn] = clean($value, true); // Convert to uppercase
                }
            }

            // Skip if no data to insert/update
            if (empty(array_filter($tableData))) {
                continue;
            }

            if ($operation === 'insert') {
                // Add foreign key if available
                if (isset($mapping['foreign_key']) && isset($results[$mapping['foreign_key']['source_table']])) {
                    $tableData[$mapping['foreign_key']['column']] = $results[$mapping['foreign_key']['source_table']];
                }

                $result = insertRecord($table, $tableData, $config);
                if ($result) {
                    $results[$table] = $result; // Store the ID
                    $successCount++;
                } else {
                    throw new Exception("Failed to insert into {$table}");
                }
            } elseif ($operation === 'update' && $whereField && isset($formData[$whereField])) {
                $whereCondition = [$mapping['where_column'] ?? 'id' => $formData[$whereField]];
                $result = updateRecord($table, $tableData, $whereCondition, $config);
                if ($result) {
                    $successCount++;
                } else {
                    throw new Exception("Failed to update {$table}");
                }
            }
        }

        $conn->commit();

        return [
            'success' => true,
            'message' => 'Operation completed successfully',
            'details' => $results
        ];

    } catch (Exception $e) {
        if (isset($conn) && $conn->inTransaction()) {
            $conn->rollback();
        }

        return [
            'success' => false,
            'message' => 'Database error: ' . $e->getMessage(),
            'error' => $e->getMessage()
        ];
    }
}

/*
 * USAGE EXAMPLE:
 * $formData = $_POST;
 * $validationRules = ['name' => 'required', 'email' => 'required|email'];
 * $tableMappings = [
 *     [
 *         'table' => 'users',
 *         'fields' => ['name' => 'name', 'email' => 'email'],
 *         'where_field' => 'user_id'
 *     ]
 * ];
 * $result = processFormData($formData, $validationRules, $tableMappings, 'insert');
 */


//================================================================================================
//                           getRecordById
//================================================================================================
/**
 * Helper function to get a single record by ID
 * @param string $table The table name
 * @param mixed $id The ID value
 * @param string $idColumn The name of the ID column (default: 'id')
 * @param array $config Database configuration
 * @return array|null The record or null if not found
 */
function getRecordById($table, $id, $idColumn = 'id', $config = []) {
    $records = selectRecords($table, [], [$idColumn => $id], '', 'ASC', 1, 0, $config);
    return !empty($records) ? $records[0] : null;
}

/*
 * USAGE EXAMPLE:
 * $user = getRecordById('users', 123);
 * if ($user) {
 *     echo "User found: " . $user['name'];
 * } else {
 *     echo "User not found";
 * }
 */


//================================================================================================
//                           handleCRUDOperation
//================================================================================================
/**
 * Generic CRUD operation handler
 * @param string $action The CRUD action (create, read, read_single, update, delete)
 * @param array $config Configuration options
 * @param array $inputData Input data (defaults to $_POST if not provided)
 * @return void
 */
function handleCRUDOperation($action, $config = [], $inputData = null) {
    // Default configuration
    $defaultConfig = [
        'table' => 'applicants',
        'primaryKey' => 'id',
        'fieldMap' => [
            'first' => 'fname',
            'last' => 'lname',
            'email' => 'email',
            'phone' => 'cphone',
            'location' => 'nation',
            'hobby' => 'religion'
        ],
        'requiredFields' => ['first', 'last', 'email'],
        'validationRules' => [
            'email' => 'required|email'
        ],
        'responseFormat' => function($record) {
            return [
                'id' => $record['applicant_id'] ?? $record['id'],
                'first' => $record['fname'] ?? $record['first'],
                'last' => $record['lname'] ?? $record['last'],
                'email' => $record['email'],
                'phone' => $record['cphone'] ?? $record['phone'],
                'location' => $record['nation'] ?? $record['location'],
                'hobby' => $record['religion'] ?? $record['hobby']
            ];
        }
    ];

    $config = array_merge($defaultConfig, $config);
    $inputData = $inputData ?: $_POST;

    switch($action) {
        case 'create':
            handleCreateOperation($config, $inputData);
            break;

        case 'read':
            handleReadOperation($config);
            break;

        case 'read_single':
            handleReadSingleOperation($config);
            break;

        case 'update':
            handleUpdateOperation($config, $inputData);
            break;

        case 'delete':
            handleDeleteOperation($config, $inputData);
            break;

        default:
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
}

/*
 * USAGE EXAMPLE:
 * handleCRUDOperation('create', [
 *     'table' => 'users',
 *     'fieldMap' => ['name' => 'name', 'email' => 'email']
 * ]);
 */


//================================================================================================
//                           handleCreateOperation
//================================================================================================
/**
 * Handle CREATE operation
 * @param array $config Configuration options
 * @param array $inputData Input data to use (defaults to $_POST)
 * @return void
 */
function handleCreateOperation($config, $inputData = null) {
    $inputData = $inputData ?: $_POST;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit;
    }

    $errors = [];

    // Get form data based on field mapping
    $data = [];
    foreach ($config['fieldMap'] as $postField => $dbField) {
        $data[$dbField] = trim($inputData[$postField] ?? '');
    }

    // Validate required fields
    foreach ($config['requiredFields'] as $field) {
        if (isset($config['fieldMap'][$field])) {
            $dbField = $config['fieldMap'][$field];
            if (!validateRequired($data[$dbField])) {
                $fieldName = ucfirst(str_replace('_', ' ', $field));
                $errors[] = "{$fieldName} is required";
            }
        }
    }

    // Additional validation based on rules
    if (!empty($config['validationRules'])) {
        foreach ($config['validationRules'] as $field => $rules) {
            if (isset($config['fieldMap'][$field]) && isset($data[$config['fieldMap'][$field]])) {
                $dbField = $config['fieldMap'][$field];
                $value = $data[$dbField];

                $ruleList = explode('|', $rules);
                foreach ($ruleList as $rule) {
                    $rule = trim($rule);
                    if ($rule === 'email' && !empty($value) && !validateEmail($value)) {
                        $fieldName = ucfirst(str_replace('_', ' ', $field));
                        $errors[] = "{$fieldName} must be a valid email";
                    }
                }
            }
        }
    }

    // If no errors, create the record
    if (empty($errors)) {
        try {
            $conn = getConnection($config);
            if (!$conn) {
                throw new Exception("Database connection failed");
            }

            // Prepare columns and values for insertion
            $columns = array_keys($data);
            $placeholders = ':' . implode(', :', $columns);
            $columnList = implode(', ', $columns);

            $query = "INSERT INTO {$config['table']} ({$columnList}) VALUES ({$placeholders})";
            $stmt = $conn->prepare($query);

            // Bind parameters
            foreach ($data as $column => $value) {
                $stmt->bindValue(":{$column}", $value);
            }

            if ($stmt->execute()) {
                $id = $conn->lastInsertId();
                echo json_encode(['success' => true, 'message' => 'Record created successfully', 'id' => $id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to create record']);
            }
            exit();
        } catch(Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
        exit();
    }
}

/*
 * USAGE EXAMPLE:
 * // Call this function when processing a form submission for creating records
 * handleCreateOperation([
 *     'table' => 'users',
 *     'fieldMap' => ['name' => 'name', 'email' => 'email'],
 *     'requiredFields' => ['name', 'email']
 * ]);
 */


//================================================================================================
//                           handleReadOperation
//================================================================================================
/**
 * Handle READ operation
 * @param array $config Configuration options
 * @return void
 */
function handleReadOperation($config) {
    try {
        $conn = getConnection($config);
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        // Get all records ordered by primary key (descending)
        $primaryKey = $config['primaryKey'];
        $query = "SELECT * FROM {$config['table']} ORDER BY {$primaryKey} DESC";

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Format the data using the response formatter
        $formattedRecords = [];
        foreach ($records as $record) {
            $formattedRecords[] = $config['responseFormat']($record);
        }

        header('Content-Type: application/json');
        echo json_encode($formattedRecords);
    } catch(Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

/*
 * USAGE EXAMPLE:
 * // Call this function to retrieve all records from a table
 * handleReadOperation([
 *     'table' => 'users',
 *     'primaryKey' => 'id',
 *     'responseFormat' => function($record) {
 *         return ['id' => $record['id'], 'name' => $record['name']];
 *     }
 * ]);
 */


//================================================================================================
//                           handleReadSingleOperation
//================================================================================================
/**
 * Handle READ_SINGLE operation
 * @param array $config Configuration options
 * @return void
 */
function handleReadSingleOperation($config) {
    $id = $_GET['id'] ?? '';

    if (empty($id)) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'ID parameter is required']);
        exit;
    }

    try {
        $conn = getConnection($config);
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        $query = "SELECT * FROM {$config['table']} WHERE {$config['primaryKey']} = :id";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($record) {
            // Format the data using the response formatter
            $formattedRecord = $config['responseFormat']($record);

            header('Content-Type: application/json');
            echo json_encode($formattedRecord);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Record not found']);
        }
    } catch(Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

/*
 * USAGE EXAMPLE:
 * // First occurrence of handleReadSingleOperation
 * // Call this function to retrieve a single record by ID
 * // Access via: ?id=123
 */


//================================================================================================
//                           handleUpdateOperation
//================================================================================================
/**
 * Handle UPDATE operation
 * @param array $config Configuration options
 * @param array $inputData Input data to use (defaults to $_POST)
 * @return void
 */
function handleUpdateOperation($config, $inputData = null) {
    $inputData = $inputData ?: $_POST;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit;
    }

    $id = $inputData['id'] ?? '';

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID is required for update']);
        exit;
    }

    $errors = [];

    // Get form data based on field mapping
    $data = [];
    foreach ($config['fieldMap'] as $postField => $dbField) {
        $data[$dbField] = trim($inputData[$postField] ?? '');
    }

    // Additional validation based on rules
    if (!empty($config['validationRules'])) {
        foreach ($config['validationRules'] as $field => $rules) {
            if (isset($config['fieldMap'][$field]) && isset($data[$config['fieldMap'][$field]])) {
                $dbField = $config['fieldMap'][$field];
                $value = $data[$dbField];

                $ruleList = explode('|', $rules);
                foreach ($ruleList as $rule) {
                    $rule = trim($rule);
                    if ($rule === 'email' && !empty($value) && !validateEmail($value)) {
                        $fieldName = ucfirst(str_replace('_', ' ', $field));
                        $errors[] = "{$fieldName} must be a valid email";
                    }
                }
            }
        }
    }

    // If no errors, update the record
    if (empty($errors)) {
        try {
            $conn = getConnection($config);
            if (!$conn) {
                throw new Exception("Database connection failed");
            }

            // Prepare SET clause
            $setParts = [];
            foreach (array_keys($data) as $column) {
                $setParts[] = "{$column} = :set_{$column}";
            }
            $setClause = implode(', ', $setParts);

            $query = "UPDATE {$config['table']} SET {$setClause} WHERE {$config['primaryKey']} = :id";
            $stmt = $conn->prepare($query);

            // Bind data parameters
            foreach ($data as $column => $value) {
                $stmt->bindValue(":set_{$column}", $value);
            }

            // Bind ID parameter
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Record updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update record']);
            }
            exit();
        } catch(Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
        exit();
    }
}

/*
 * USAGE EXAMPLE:
 * // Call this function when processing a form submission for updating records
 * handleUpdateOperation([
 *     'table' => 'users',
 *     'fieldMap' => ['name' => 'name', 'email' => 'email'],
 *     'validationRules' => ['email' => 'email']
 * ]);
 */


//================================================================================================
//                           handleDeleteOperation
//================================================================================================
/**
 * Handle DELETE operation
 * @param array $config Configuration options
 * @param array $inputData Input data to use (defaults to $_POST)
 * @return void
 */
function handleDeleteOperation($config, $inputData = null) {
    $inputData = $inputData ?: $_POST;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit;
    }

    $id = $inputData['id'] ?? '';

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID is required for deletion']);
        exit;
    }

    try {
        $conn = getConnection($config);
        if (!$conn) {
            throw new Exception("Database connection failed");
        }

        // Delete query
        $query = "DELETE FROM {$config['table']} WHERE {$config['primaryKey']} = :id";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Record deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete record']);
        }
        exit();
    } catch(Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        exit();
    }
}
/**
 * Usables Class - Object-oriented wrapper for the utility functions
 */
class Usables {
    /**
     * Fetches data from the database
     * @param string $table The table name to query
     * @param array $columns Columns to select (empty array means all *)
     * @param array $where Associative array of column => value pairs for WHERE clause
     * @param string $orderBy Column to order by
     * @param string $orderDir Direction of order (ASC or DESC)
     * @param int $limit Limit of records
     * @param int $offset Offset for pagination
     * @param array $config Database configuration
     * @return array Array of records
     */
    public function getDataFromDatabase($table, $columns = [], $where = [], $orderBy = '', $orderDir = 'ASC', $limit = 0, $offset = 0, $config = []) {
        return getDataFromDatabase($table, $columns, $where, $orderBy, $orderDir, $limit, $offset, $config);
    }

    /**
     * Inserts data to the database
     * @param string $table The table name to insert into
     * @param array $data Associative array of column => value pairs to insert
     * @param array $config Database configuration
     * @return int|bool ID of inserted record or false on failure
     */
    public function insertDataToDatabase($table, $data, $config = []) {
        return insertDataToDatabase($table, $data, $config);
    }

    /**
     * Updates data in the database
     * @param string $table The table name to update
     * @param array $data Associative array of column => value pairs to update
     * @param array $where Associative array of column => value pairs for WHERE clause
     * @param array $config Database configuration
     * @return bool True on success, false on failure
     */
    public function updateDataInDatabase($table, $data, $where, $config = []) {
        return updateDataInDatabase($table, $data, $where, $config);
    }

    /**
     * Deletes data from the database
     * @param string $table The table name to delete from
     * @param array $where Associative array of column => value pairs for WHERE clause
     * @param array $config Database configuration
     * @return bool True on success, false on failure
     */
    public function deleteDataFromDatabase($table, $where, $config = []) {
        return deleteDataFromDatabase($table, $where, $config);
    }

    /**
     * Gets a single record from the database
     * @param string $table The table name to query
     * @param mixed $id The ID value
     * @param string $idColumn The name of the ID column (default: 'id')
     * @param array $config Database configuration
     * @return array|null The record or null if not found
     */
    public function getSingleRecord($table, $id, $idColumn = 'id', $config = []) {
        return getSingleRecord($table, $id, $idColumn, $config);
    }

    /**
     * Checks if a record exists in the database
     * @param string $table The table name
     * @param array $where Conditions to check
     * @param array $config Database configuration
     * @return bool True if record exists, false otherwise
     */
    public function recordExists($table, $where, $config = []) {
        return recordExists($table, $where, $config);
    }

    /**
     * Creates a new record in the database (similar to CRUD create operation)
     * @param array $data Associative array of field => value pairs to insert
     * @param array $config Database configuration
     * @return array Result of the operation
     */
    public function createRecord($data, $config = []) {
        // Validate required fields
        $errors = [];
        $first = trim($data['first'] ?? '');
        $last = trim($data['last'] ?? '');
        $email = trim($data['email'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $location = trim($data['location'] ?? '');
        $hobby = trim($data['hobby'] ?? '');

        if (empty($first)) {
            $errors[] = "First name is required";
        }
        if (empty($last)) {
            $errors[] = "Last name is required";
        }
        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }

        if (!empty($errors)) {
            return ['success' => false, 'message' => implode(', ', $errors)];
        }

        try {
            // Prepare data for insertion - mapping to actual database field names
            $insertData = [
                'fname' => trim($first),
                'lname' => trim($last),
                'email' => trim($email),
                'cphone' => trim($phone),
                'nation' => trim($location),
                'religion' => trim($hobby)
            ];

            $id = insertDataToDatabase('applicants', $insertData, $config);

            if ($id) {
                return ['success' => true, 'message' => 'Record created successfully', 'id' => $id];
            } else {
                return ['success' => false, 'message' => 'Failed to create record'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Reads all records from the database (similar to CRUD read operation)
     * @param array $config Database configuration
     * @return array Array of formatted records
     */
    public function readRecords($config = []) {
        try {
            // Debug: Log the database call
            error_log("DEBUG: Calling getDataFromDatabase for applicants table");
            
            $applicants = getDataFromDatabase('applicants', [], [], 'applicant_id', 'DESC', 0, 0, $config);

            // Debug: Check what getDataFromDatabase returned
            error_log("DEBUG: getDataFromDatabase returned " . count($applicants) . " records");
            if (count($applicants) > 0) {
                error_log("DEBUG: First record keys: " . implode(', ', array_keys($applicants[0])));
            }

            // Format the data to match what the frontend expects
            $formattedApplicants = [];
            foreach ($applicants as $applicant) {
                $formattedApplicants[] = [
                    'id' => $applicant['applicant_id'] ?? $applicant['id'],
                    'first' => $applicant['fname'] ?? $applicant['first'],
                    'last' => $applicant['lname'] ?? $applicant['last'],
                    'email' => $applicant['email'] ?? $applicant['email'],
                    'phone' => $applicant['cphone'] ?? $applicant['phone'],
                    'location' => $applicant['nation'] ?? $applicant['location'],
                    'hobby' => $applicant['religion'] ?? $applicant['hobby']
                ];
            }

            error_log("DEBUG: Returning " . count($formattedApplicants) . " formatted records");
            return $formattedApplicants;
        } catch (Exception $e) {
            error_log("ERROR in readRecords: " . $e->getMessage());
            return ['error' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Reads a single record from the database (similar to CRUD read_single operation)
     * @param mixed $id The ID of the record to retrieve
     * @param array $config Database configuration
     * @return array Formatted record data
     */
    public function readSingleRecord($id, $config = []) {
        if (empty($id)) {
            return ['error' => 'ID parameter is required'];
        }

        try {
            // Get main applicant data
            $applicant = getSingleRecord('applicants', $id, 'applicant_id', $config);

            if ($applicant) {
                // Get related address data
                $address = getSingleRecord('applicant_addresses', $id, 'applicant_id', $config);

                // Get related parents data
                $parents = getSingleRecord('applicant_parents', $id, 'applicant_id', $config);

                // Get related spouse data
                $spouse = getSingleRecord('applicant_spouse', $id, 'applicant_id', $config);

                // Get related children data
                $children = getDataFromDatabase('applicant_children', [], ['applicant_id' => $id], '', 'ASC', 0, 0, $config);

                // Get related employment data
                $employment = getSingleRecord('applicant_employment', $id, 'applicant_id', $config);

                // Format the data for the page1.html form
                $response = [
                    'applicant' => $applicant,
                    'address' => $address ?: [],
                    'parents' => $parents ?: [],
                    'spouse' => $spouse ?: [],
                    'children' => $children ?: [],
                    'employment' => $employment ?: []
                ];

                return $response;
            } else {
                return ['error' => 'Record not found'];
            }
        } catch (Exception $e) {
            return ['error' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Updates a record in the database (similar to CRUD update operation)
     * @param array $data Associative array of field => value pairs to update
     * @param array $config Database configuration
     * @return array Result of the operation
     */
    public function updateRecord($data, $config = []) {
        // Validate required fields
        $errors = [];
        $id = $data['id'] ?? '';
        $first = trim($data['first'] ?? '');
        $last = trim($data['last'] ?? '');
        $email = trim($data['email'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $location = trim($data['location'] ?? '');
        $hobby = trim($data['hobby'] ?? '');

        if (empty($id)) {
            $errors[] = "ID is required for update";
        }

        if (empty($first)) {
            $errors[] = "First name is required";
        }
        if (empty($last)) {
            $errors[] = "Last name is required";
        }
        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }

        if (!empty($errors)) {
            return ['success' => false, 'message' => implode(', ', $errors)];
        }

        try {
            // Prepare data for update - mapping to actual database field names
            $updateData = [
                'fname' => trim($first),
                'lname' => trim($last),
                'email' => trim($email),
                'cphone' => trim($phone),
                'nation' => trim($location),
                'religion' => trim($hobby)
            ];

            $where = ['applicant_id' => $id];

            $success = updateDataInDatabase('applicants', $updateData, $where, $config);

            if ($success) {
                return ['success' => true, 'message' => 'Record updated successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to update record'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Deletes a record from the database (similar to CRUD delete operation)
     * @param mixed $id The ID of the record to delete
     * @param array $config Database configuration
     * @return array Result of the operation
     */
    public function deleteRecord($id, $config = []) {
        if (empty($id)) {
            return ['success' => false, 'message' => 'ID is required for deletion'];
        }

        try {
            $where = ['applicant_id' => $id];

            $success = deleteDataFromDatabase('applicants', $where, $config);

            if ($success) {
                return ['success' => true, 'message' => 'Record deleted successfully'];
            } else {
                return ['success' => false, 'message' => 'Failed to delete record'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
}
?>
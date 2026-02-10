# CRUD Operations Seed File

## Overview

This file contains ready-to-use PHP and JavaScript syntaxes for performing CRUD operations with database tables.

## Database Table Structure

### Basic Table Schema
```sql
CREATE TABLE IF NOT EXISTS table_name (
    id INT AUTO_INCREMENT PRIMARY KEY,
    field1 VARCHAR(100) NOT NULL,
    field2 VARCHAR(100) NOT NULL,
    field3 TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Example: User Table
```sql
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    age INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## PHP Syntax Used

### Database Connection
```php
<?php
function connectDB() {
    $host = 'localhost';
    $dbname = 'your_database_name';
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
?>
```

### Create (Insert) Operation
```php
<?php
function createRecord($table, $data) {
    $conn = connectDB();
    
    $columns = array_keys($data);
    $values = array_values($data);
    $placeholders = str_repeat('?,', count($values) - 1) . '?';
    
    $sql = "INSERT INTO $table (" . implode(',', $columns) . ") VALUES ($placeholders)";
    
    try {
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute($values);
        
        if ($result) {
            return $conn->lastInsertId();
        }
        return false;
    } catch(PDOException $e) {
        error_log("Create error: " . $e->getMessage());
        return false;
    }
}

// Usage example:
$newId = createRecord('users', [
    'username' => 'johndoe',
    'email' => 'john@example.com',
    'full_name' => 'John Doe',
    'age' => 25
]);
?>
```

### Read (Select) Operation
```php
<?php
function readRecords($table, $conditions = [], $orderBy = '', $orderDirection = 'ASC', $limit = 0, $offset = 0) {
    $sql = "SELECT * FROM $table";
    $params = [];
    
    if (!empty($conditions)) {
        $whereClauses = [];
        foreach ($conditions as $column => $value) {
            $whereClauses[] = "$column = ?";
            $params[] = $value;
        }
        $sql .= " WHERE " . implode(' AND ', $whereClauses);
    }
    
    if (!empty($orderBy)) {
        $sql .= " ORDER BY $orderBy $orderDirection";
    }
    
    if ($limit > 0) {
        $sql .= " LIMIT $limit";
        if ($offset > 0) {
            $sql .= " OFFSET $offset";
        }
    }
    
    try {
        $conn = connectDB();
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Read error: " . $e->getMessage());
        return [];
    }
}

// Usage examples:
$allUsers = readRecords('users');
$johnUsers = readRecords('users', ['full_name' => 'John']);
$sortedUsers = readRecords('users', [], 'full_name', 'ASC', 10, 0);
?>
```

### Read Single Record
```php
<?php
function readSingleRecord($table, $variable1, $idColumn = 'id') {
    $sql = "SELECT * FROM $table WHERE $idColumn = ?";
    
    try {
        $conn = connectDB();
        $stmt = $conn->prepare($sql);
        $stmt->execute([$variable1]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("ReadSingle error: " . $e->getMessage());
        return null;
    }
}

// Usage example:
$variable2 = readSingleRecord('users', 123, 'user_id');
?>
```

### Update Operation
```php
<?php
function updateRecord($table, $data, $conditions) {
    if (empty($data) || empty($conditions)) {
        return false;
    }

    $setClauses = [];
    $params = [];

    foreach ($data as $column => $value) {
        $setClauses[] = "$column = ?";
        $params[] = $value;
    }

    $whereClauses = [];
    foreach ($conditions as $column => $value) {
        $whereClauses[] = "$column = ?";
        $params[] = $value;
    }

    $sql = "UPDATE $table SET " . implode(',', $setClauses) . " WHERE " . implode(' AND ', $whereClauses);

    try {
        $conn = connectDB();
        $stmt = $conn->prepare($sql);
        return $stmt->execute($params);
    } catch(PDOException $e) {
        error_log("Update error: " . $e->getMessage());
        return false;
    }
}

// Usage example:
$variable1 = updateRecord('users',
    ['full_name' => 'Jane Smith', 'age' => 28],
    ['user_id' => 123]
);
?>
```

### Delete Operation
```php
<?php
function deleteRecord($table, $conditions) {
    if (empty($conditions)) {
        return false;
    }

    $whereClauses = [];
    $params = [];

    foreach ($conditions as $column => $value) {
        $whereClauses[] = "$column = ?";
        $params[] = $value;
    }

    $sql = "DELETE FROM $table WHERE " . implode(' AND ', $whereClauses);

    try {
        $conn = connectDB();
        $stmt = $conn->prepare($sql);
        return $stmt->execute($params);
    } catch(PDOException $e) {
        error_log("Delete error: " . $e->getMessage());
        return false;
    }
}

// Usage example:
$variable1 = deleteRecord('users', ['user_id' => 123]);
?>
```

### Search Records
```php
<?php
function searchRecords($table, $searchFields, $matchType = 'any') {
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
    $sql = "SELECT * FROM $table WHERE " . implode($operator, $whereClauses);

    try {
        $conn = connectDB();
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Search error: " . $e->getMessage());
        return [];
    }
}

// Usage examples:
$variable1 = searchRecords('users', ['full_name' => 'John'], 'any');  // Find users with 'John' in full name (OR match)
$variable2 = searchRecords('users', ['full_name' => 'John', 'email' => 'gmail'], 'all');  // Find users with both 'John' in name AND 'gmail' in email (AND match)
?>
```

### JavaScript Syntaxes

#### Fetch API with Async/Await
```javascript
// Basic fetch with async/await
async function loadData() {
    try {
        const response = await fetch('api.php?action=read');
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error loading data:', error);
        return [];
    }
}

// POST request with fetch
async function postData(data) {
    try {
        const response = await fetch('api.php?action=create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        return await response.json();
    } catch (error) {
        console.error('Error posting data:', error);
        return { success: false, message: error.message };
    }
}

// Usage examples:
const variable1 = await loadData();
const variable2 = await postData({ field1: 'value1', field2: 'value2' });
```

#### FormData for Form Handling
```javascript
// Create FormData from form element
const formElement = document.querySelector('form');
const formData = new FormData(formElement);

// Create FormData manually
const formData = new FormData();
formData.append('fieldName', 'fieldValue');
formData.append('anotherField', 'anotherValue');

// Submit form data with fetch
async function submitForm() {
    const form = document.getElementById('myForm');
    const formData = new FormData(form);

    try {
        const response = await fetch('process.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error submitting form:', error);
        return { success: false, message: error.message };
    }
}

// Usage example:
const variable1 = await submitForm();
```

#### DOM Manipulation
```javascript
// Select single element by ID
const element = document.getElementById('myId');

// Select multiple elements
const elements = document.querySelectorAll('.myClass');

// Create new element
const newElement = document.createElement('div');

// Set content
newElement.innerHTML = '<p>New content</p>';

// Add to page
document.body.appendChild(newElement);

// Update existing element
document.getElementById('myDiv').style.display = 'block';

// Usage examples:
const variable1 = document.getElementById('myInput');
const variable2 = document.querySelectorAll('input[type="text"]');
const variable3 = document.createElement('p');
variable3.textContent = 'Hello World';
document.body.appendChild(variable3);
```

#### Array Methods
```javascript
// Loop through array with forEach
const array = [1, 2, 3];
array.forEach(item => {
    console.log(item);
});

// Transform array with map
const doubled = array.map(x => x * 2);  // [2, 4, 6]

// Filter array
const evens = array.filter(x => x % 2 === 0);  // [2]

// Find element in array
const found = array.find(x => x === 2);  // 2

// Check if array contains element
const hasTwo = array.some(x => x === 2);  // true

// Usage examples:
const variable1 = users.map(user => user.full_name);
const variable2 = users.filter(user => user.active === true);
const variable3 = users.find(user => user.id === 123);
```

#### String Methods
```javascript
// Convert to uppercase
const upper = text.toUpperCase();

// Convert to lowercase
const lower = text.toLowerCase();

// Remove whitespace
const trimmed = text.trim();

// Split string into array
const parts = text.split(',');

// Replace part of string
const newText = text.replace('old', 'new');

// Check if string contains substring
const contains = text.includes('substring');

// Usage examples:
const variable1 = 'hello world'.toUpperCase();  // 'HELLO WORLD'
const variable2 = '  hello  '.trim();  // 'hello'
const variable3 = 'apple,banana,orange'.split(',');  // ['apple', 'banana', 'orange']
```

#### Date Operations
```javascript
// Create date object
const date = new Date();

// Create date from string
const birthDate = new Date('1990-01-01');

// Get current timestamp
const timestamp = Date.now();

// Parse date string
const parsedDate = new Date(Date.parse('2023-01-01'));

// Calculate difference between dates
const today = new Date();
const birth = new Date('1990-01-01');
const age = today.getFullYear() - birth.getFullYear();

// Usage examples:
const variable1 = new Date();
const variable2 = new Date('2023-12-25');
const variable3 = Date.now();
```

#### Conditional Logic
```javascript
// Basic if statement
if (condition) {
    // Do something
}

// If-else statement
if (condition) {
    // Do something
} else {
    // Do something else
}

// Ternary operator
const result = condition ? value1 : value2;

// Multiple conditions
if (condition1 && condition2) {
    // Both true
} else if (condition1 || condition2) {
    // Either true
}

// Usage examples:
const variable1 = age >= 18 ? 'adult' : 'minor';
if (user.active && user.confirmed) {
    // Both conditions met
}
```

#### Event Handling
```javascript
// Add event listener
document.getElementById('myButton').addEventListener('click', function() {
    // Code to execute when clicked
});

// Handle form submission
document.getElementById('myForm').addEventListener('submit', function(e) {
    e.preventDefault();  // Prevent default form submission
    // Handle form submission manually
});

// Handle input changes
document.getElementById('myInput').addEventListener('input', function() {
    console.log(this.value);  // Log current value as user types
});

// Usage examples:
document.getElementById('submitBtn').addEventListener('click', handleSubmit);
document.getElementById('searchInput').addEventListener('input', handleSearch);
```

#### Local Storage
```javascript
// Store data
localStorage.setItem('key', 'value');

// Retrieve data
const value = localStorage.getItem('key');

// Remove data
localStorage.removeItem('key');

// Clear all data
localStorage.clear();

// Usage examples:
localStorage.setItem('userPref', 'darkMode');
const variable1 = localStorage.getItem('userPref');
localStorage.removeItem('tempData');
```

#### Session Storage
```javascript
// Store data
sessionStorage.setItem('key', 'value');

// Retrieve data
const value = sessionStorage.getItem('key');

// Remove data
sessionStorage.removeItem('key');

// Clear all data
sessionStorage.clear();

// Usage examples:
sessionStorage.setItem('formSuccess', 'true');
const variable1 = sessionStorage.getItem('formSuccess');
sessionStorage.removeItem('tempForm');
```

#### Template Literals
```javascript
// Create dynamic strings
const name = 'John';
const greeting = `Hello ${name}, welcome to our site!`;

// Multi-line strings
const html = `
    <div>
        <p>Line 1</p>
        <p>Line 2</p>
    </div>
`;

// Dynamic content in strings
const url = `https://api.example.com/users/${userId}`;
const message = `User ${user.name} is ${user.age} years old`;

// Usage examples:
const variable1 = `Hello ${userName}`;
const variable2 = `ID: ${userId}, Status: ${status}`;
```

#### Error Handling with Try-Catch
```javascript
try {
    // Code that might throw an error
    const response = await fetch('api/data');
    const data = await response.json();
    return data;
} catch (error) {
    // Handle the error
    console.error('Error occurred:', error);
    return null;
}

// Usage example:
try {
    const variable1 = await loadData();
    if (variable1) {
        processResults(variable1);
    }
} catch (error) {
    console.error('Failed to load data:', error);
}
```

#### Timeout Functions
```javascript
// Execute code after delay
setTimeout(function() {
    console.log('This runs after 2 seconds');
}, 2000);

// Execute code repeatedly at intervals
const intervalId = setInterval(function() {
    console.log('This runs every second');
}, 1000);

// Stop repeated execution
clearInterval(intervalId);

// Usage examples:
setTimeout(hideMessage, 5000);  // Hide message after 5 seconds
const timer = setInterval(updateClock, 1000);  // Update clock every second
```

#### Object Methods
```javascript
// Get object keys
const keys = Object.keys(object);

// Get object values
const values = Object.values(object);

// Get key-value pairs
const entries = Object.entries(object);

// Usage examples:
const variable1 = Object.keys(user);  // ['name', 'age', 'email']
const variable2 = Object.values(user);  // ['John', 30, 'john@example.com']
const variable3 = Object.entries(user);  // [['name', 'John'], ['age', 30], ['email', 'john@example.com']]
```

These JavaScript functions provide the client-side functionality to interact with your PHP backend and manipulate the DOM for dynamic user interfaces.

### Count Records
```php
<?php
function countRecords($table, $conditions = []) {
    $sql = "SELECT COUNT(*) as count FROM $table";
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
        $conn = connectDB();
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    } catch(PDOException $e) {
        error_log("Count error: " . $e->getMessage());
        return 0;
    }
}

// Usage example:
$variable1 = countRecords('users');
$variable2 = countRecords('users', ['full_name' => 'John']);
?>
```

### Complete Example: Form Processing
```php
<?php
// Include database connection
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $variable1 = trim($_POST['field1'] ?? '');
    $variable2 = trim($_POST['field2'] ?? '');
    $variable3 = trim($_POST['field3'] ?? '');

    // Validate required fields
    $errors = [];
    if (empty($variable1)) {
        $errors[] = "Field 1 is required";
    }
    if (empty($variable2)) {
        $errors[] = "Field 2 is required";
    }

    if (empty($errors)) {
        try {
            // Create CRUD instance
            $crud = new UniversalCRUD('your_table');

            // Prepare data
            $data = [
                'field1' => $variable1,
                'field2' => $variable2,
                'field3' => $variable3
            ];

            // Insert record
            $newId = $crud->create($data);

            if ($newId) {
                // Success - redirect or show message
                $_SESSION['success'] = "Record created successfully";
                header("Location: success.php");
                exit;
            } else {
                $errorMessage = "Failed to create record";
            }
        } catch (Exception $e) {
            $errorMessage = "Database error: " . $e->getMessage();
        }
    } else {
        $errorMessage = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Form Processing Example</title>
</head>
<body>
    <?php if (isset($errorMessage)): ?>
        <div style="color: red;"><?php echo $errorMessage; ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="field1" value="<?php echo htmlspecialchars($variable1 ?? ''); ?>" required>
        <input type="text" name="field2" value="<?php echo htmlspecialchars($variable2 ?? ''); ?>" required>
        <input type="text" name="field3" value="<?php echo htmlspecialchars($variable3 ?? ''); ?>">
        <input type="submit" value="Submit">
    </form>
</body>
</html>
```

### Complete Example: CRUD Interface
```php
<?php
require_once 'config.php';

$action = $_GET['action'] ?? 'read';

switch($action) {
    case 'create':
        // Handle create operation
        $data = [
            'field1' => $_POST['field1'] ?? '',
            'field2' => $_POST['field2'] ?? '',
            'field3' => $_POST['field3'] ?? ''
        ];

        $crud = new UniversalCRUD('your_table');
        $id = $crud->create($data);

        if ($id) {
            echo json_encode(['success' => true, 'id' => $id]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create']);
        }
        break;

    case 'read':
        $crud = new UniversalCRUD('your_table');
        $records = $crud->read();

        header('Content-Type: application/json');
        echo json_encode($records);
        break;

    case 'update':
        $id = $_POST['id'] ?? '';
        $data = [
            'field1' => $_POST['field1'] ?? '',
            'field2' => $_POST['field2'] ?? '',
            'field3' => $_POST['field3'] ?? ''
        ];

        $crud = new UniversalCRUD('your_table');
        $success = $crud->update($data, ['id' => $id]);

        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update']);
        }
        break;

    case 'delete':
        $id = $_POST['id'] ?? '';

        $crud = new UniversalCRUD('your_table');
        $success = $crud->delete(['id' => $id]);

        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete']);
        }
        break;

    default:
        // Display the CRUD interface
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>CRUD Interface</title>
        </head>
        <body>
            <h1>Manage Records</h1>

            <table id="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Field 1</th>
                        <th>Field 2</th>
                        <th>Field 3</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                </tbody>
            </table>

            <button onclick="showAddForm()">Add New</button>

            <script>
                let records = [];

                // Load records
                async function loadRecords() {
                    try {
                        const response = await fetch('?action=read');
                        records = await response.json();
                        renderTable();
                    } catch (error) {
                        console.error('Error loading records:', error);
                    }
                }

                // Render table
                function renderTable() {
                    const tableBody = document.getElementById('table-body');
                    tableBody.innerHTML = '';

                    records.forEach(record => {
                        const row = `
                            <tr>
                                <td>${record.id}</td>
                                <td>${record.field1}</td>
                                <td>${record.field2}</td>
                                <td>${record.field3}</td>
                                <td>
                                    <button onclick="editRecord(${record.id})">Edit</button>
                                    <button onclick="deleteRecord(${record.id})">Delete</button>
                                </td>
                            </tr>
                        `;
                        tableBody.innerHTML += row;
                    });
                }

                // Delete record
                async function deleteRecord(id) {
                    if (confirm('Are you sure?')) {
                        const formData = new FormData();
                        formData.append('id', id);

                        const response = await fetch('?action=delete', {
                            method: 'POST',
                            body: formData
                        });
                        const result = await response.json();

                        if (result.success) {
                            loadRecords(); // Refresh data
                        }
                    }
                }

                // Initial load
                loadRecords();
            </script>
        </body>
        </html>
        <?php
}
?>
```

This comprehensive guide provides all the essential syntaxes and patterns you need to build similar web applications from scratch. The UniversalCRUD system simplifies database operations while maintaining security and best practices.

These code snippets are ready to copy-paste and adapt to your specific needs. Just replace table names, field names, and adjust the logic as needed for your specific application.
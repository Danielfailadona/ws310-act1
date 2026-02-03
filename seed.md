# CRUD Operations Seed File

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

## PHP Syntaxes

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
function readSingleRecord($table, $id, $idColumn = 'id') {
    $sql = "SELECT * FROM $table WHERE $idColumn = ?";
    
    try {
        $conn = connectDB();
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("ReadSingle error: " . $e->getMessage());
        return null;
    }
}

// Usage example:
$user = readSingleRecord('users', 123, 'user_id');
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
$success = updateRecord('users', 
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
$success = deleteRecord('users', ['user_id' => 123]);
?>
```

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
$totalUsers = countRecords('users');
$johnCount = countRecords('users', ['full_name' => 'John']);
?>
```

## JavaScript Syntaxes

### Load Data from Server
```javascript
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

// Usage:
const records = await loadData();
```

### Create Record
```javascript
async function createRecord(data) {
    const formData = new FormData();
    Object.keys(data).forEach(key => {
        formData.append(key, data[key]);
    });
    
    try {
        const response = await fetch('api.php?action=create', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error creating record:', error);
        return { success: false, message: error.message };
    }
}

// Usage:
const result = await createRecord({
    username: 'janedoe',
    email: 'jane@example.com',
    full_name: 'Jane Doe',
    age: 25
});
```

### Update Record
```javascript
async function updateRecord(id, data) {
    const formData = new FormData();
    formData.append('id', id);
    Object.keys(data).forEach(key => {
        formData.append(key, data[key]);
    });
    
    try {
        const response = await fetch('api.php?action=update', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error updating record:', error);
        return { success: false, message: error.message };
    }
}

// Usage:
const result = await updateRecord(123, {
    full_name: 'Jane Smith',
    age: 28
});
```

### Delete Record
```javascript
async function deleteRecord(id) {
    const formData = new FormData();
    formData.append('id', id);
    
    try {
        const response = await fetch('api.php?action=delete', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error deleting record:', error);
        return { success: false, message: error.message };
    }
}

// Usage:
const result = await deleteRecord(123);
if (result.success) {
    console.log('Record deleted successfully');
}
```

### Render Table with Data
```javascript
function renderTable(data, tableBodyId) {
    const tableBody = document.getElementById(tableBodyId);
    tableBody.innerHTML = ""; // Clear existing content
    
    data.forEach(record => {
        const row = `
            <tr>
                <td>${record.id}</td>
                <td>${record.field1}</td>
                <td>${record.field2}</td>
                <td>
                    <button onclick="editRecord(${record.id})">Edit</button>
                    <button onclick="deleteRecord(${record.id})">Delete</button>
                </td>
            </tr>
        `;
        tableBody.innerHTML += row;
    });
}

// Usage:
const records = await loadData();
renderTable(records, 'table-body');
```

### Form Submission Handler
```javascript
document.getElementById('record-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const id = document.getElementById('record-id').value;
    
    try {
        const url = id ? 'api.php?action=update' : 'api.php?action=create';
        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        
        if (result.success) {
            alert(id ? 'Record updated successfully' : 'Record created successfully');
            this.reset();
            loadAndRenderData(); // Reload the table
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        alert('Error saving record');
    }
});
```

### Complete HTML Template with CRUD
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Application</title>
</head>
<body>
    <div class="container">
        <h1>CRUD Application</h1>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Field 1</th>
                    <th>Field 2</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Data will be loaded here -->
            </tbody>
        </table>
        
        <button onclick="showAddForm()">Add New Record</button>
    </div>
    
    <!-- Modal for Add/Edit -->
    <div id="modal" style="display:none;">
        <h2 id="modal-title">Add New Record</h2>
        <form id="record-form">
            <input type="hidden" id="record-id">
            <p><label>Field 1: <input type="text" id="field1" required></label></p>
            <p><label>Field 2: <input type="text" id="field2" required></label></p>
            <p>
                <button type="submit">Save</button>
                <button type="button" onclick="closeModal()">Cancel</button>
            </p>
        </form>
    </div>

    <script>
        let recordsData = [];

        // Load and render data
        async function loadAndRenderData() {
            recordsData = await loadData();
            renderTable(recordsData, 'table-body');
        }

        // Show modal for adding new record
        function showAddForm() {
            document.getElementById('modal-title').textContent = 'Add New Record';
            document.getElementById('record-form').reset();
            document.getElementById('record-id').value = '';
            document.getElementById('modal').style.display = 'block';
        }

        // Populate form and show modal for editing
        async function editRecord(id) {
            const record = recordsData.find(r => r.id == id);
            if (record) {
                document.getElementById('modal-title').textContent = 'Edit Record';
                document.getElementById('record-id').value = record.id;
                document.getElementById('field1').value = record.field1 || '';
                document.getElementById('field2').value = record.field2 || '';
                document.getElementById('modal').style.display = 'block';
            }
        }

        // Close modal
        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        // Handle form submission
        document.getElementById('record-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const id = document.getElementById('record-id').value;
            const data = {
                field1: document.getElementById('field1').value,
                field2: document.getElementById('field2').value
            };
            
            let result;
            if (id) {
                result = await updateRecord(id, data);
            } else {
                result = await createRecord(data);
            }
            
            if (result.success) {
                alert(id ? 'Record updated successfully' : 'Record created successfully');
                closeModal();
                loadAndRenderData();
            } else {
                alert('Error: ' + result.message);
            }
        });

        // Delete record with confirmation
        async function deleteRecord(id) {
            if (confirm('Are you sure you want to delete this record?')) {
                const result = await deleteRecord(id);
                if (result.success) {
                    alert('Record deleted successfully');
                    loadAndRenderData();
                } else {
                    alert('Error: ' + result.message);
                }
            }
        }

        // Initial load
        loadAndRenderData();
    </script>
</body>
</html>
```

### Search Records
```javascript
async function searchRecords(searchTerm) {
    try {
        const response = await fetch(`api.php?action=search&term=${encodeURIComponent(searchTerm)}`);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error searching records:', error);
        return [];
    }
}

// Usage:
const results = await searchRecords('john');
renderTable(results, 'table-body');
```

### Filter Records
```javascript
async function filterRecords(filters) {
    const params = new URLSearchParams({ action: 'filter' });
    Object.keys(filters).forEach(key => {
        params.append(key, filters[key]);
    });

    try {
        const response = await fetch(`api.php?${params.toString()}`);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error filtering records:', error);
        return [];
    }
}

// Usage:
const filteredResults = await filterRecords({
    status: 'active',
    category: 'premium'
});
renderTable(filteredResults, 'table-body');
```

### Validation Functions
```javascript
// Validate required fields
function validateRequired(value) {
    return value && value.trim() !== '';
}

// Validate email format
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Validate phone number
function validatePhone(phone) {
    const re = /^[0-9+\-\s()]+$/;
    return re.test(phone.replace(/\D/g, ''));
}

// Validate date format
function validateDate(dateString) {
    const date = new Date(dateString);
    return date instanceof Date && !isNaN(date);
}

// Example usage in form validation:
document.getElementById('record-form').addEventListener('submit', function(e) {
    const field1 = document.getElementById('field1').value;
    const field2 = document.getElementById('field2').value;

    if (!validateRequired(field1)) {
        alert('Field 1 is required');
        e.preventDefault();
        return;
    }

    if (!validateRequired(field2)) {
        alert('Field 2 is required');
        e.preventDefault();
        return;
    }
});
```

## API Endpoint Template (api.php)
```php
<?php
require_once 'config.php'; // Your database connection file

$action = $_GET['action'] ?? 'read';

switch($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'field1' => $_POST['field1'] ?? '',
                'field2' => $_POST['field2'] ?? ''
            ];

            $id = createRecord('your_table', $data);
            if ($id) {
                echo json_encode(['success' => true, 'message' => 'Record created', 'id' => $id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to create record']);
            }
        }
        break;

    case 'read':
        $records = readRecords('your_table');
        header('Content-Type: application/json');
        echo json_encode($records);
        break;

    case 'read_single':
        $id = $_GET['id'] ?? '';
        $record = readSingleRecord('your_table', $id, 'id');
        header('Content-Type: application/json');
        echo json_encode($record);
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $data = [
                'field1' => $_POST['field1'] ?? '',
                'field2' => $_POST['field2'] ?? ''
            ];

            $success = updateRecord('your_table', $data, ['id' => $id]);
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Record updated']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update record']);
            }
        }
        break;

    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $success = deleteRecord('your_table', ['id' => $id]);
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Record deleted']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete record']);
            }
        }
        break;

    case 'search':
        $term = $_GET['term'] ?? '';
        $records = searchRecords('your_table', $term);
        header('Content-Type: application/json');
        echo json_encode($records);
        break;

    case 'filter':
        $filters = [];
        foreach ($_GET as $key => $value) {
            if ($key !== 'action') {
                $filters[$key] = $value;
            }
        }
        $records = filterRecords('your_table', $filters);
        header('Content-Type: application/json');
        echo json_encode($records);
        break;

    case 'count':
        $conditions = [];
        foreach ($_GET as $key => $value) {
            if ($key !== 'action') {
                $conditions[$key] = $value;
            }
        }
        $count = countRecords('your_table', $conditions);
        header('Content-Type: application/json');
        echo json_encode(['count' => $count]);
        break;

    default:
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid action']);
}
?>
```

## Configuration Template (config.php)
```php
<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'root');
define('DB_PASS', '');

// Create connection function
function connectDB() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Validation functions
function validateRequired($value) {
    return !empty(trim($value));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePhone($phone) {
    return preg_match('/^[0-9+\-\s()]+$/', preg_replace('/\D/', '', $phone));
}
?>
```

These code snippets are ready to copy-paste and adapt to your specific needs. Just replace table names, field names, and adjust the logic as needed for your specific application.
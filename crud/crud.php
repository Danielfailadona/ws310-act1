<?php
// Include the Usables class
require_once '../usables/usables.php';

// Use the functions from usables.php
// ============================== FLOW EXPLANATION ==============================
//
// EXECUTION FLOW:
// 1. Page loads and checks for action parameter in URL
// 2. If action parameter exists OR request is POST, execute CRUD operation
// 3. If no action parameter and GET request, show HTML interface
//
// DATA FLOW:
// - $_GET['action'] determines which CRUD operation to execute
// - $_POST data contains form values for create/update operations
// - $_GET['id'] contains record ID for read_single/delete operations
//
// WHO DETERMINES EXECUTION:
// - The URL parameter 'action' determines which case runs in the switch statement
// - JavaScript in the HTML calls URLs like: crud.php?action=read_single&id=123
// - Each case calls a specific handler function (handleCreate, handleRead, etc.)
//
// LOGIC STARTS HERE:
// - If action parameter exists in URL, execute corresponding CRUD operation
// - If no action parameter, show the HTML interface for the CRUD table
//
// ===========================================================================

// Functions like clean(), validateRequired(), etc. are already defined in usables.php

// Determine action based on the action parameter
$action = $_GET['action'] ?? null; // Don't default to anything, let the HTML interface handle it

// Debug: Log the initial action value with more detail
error_log("DEBUG: Initial action value: " . var_export($action, true));
error_log("DEBUG: Full GET array: " . var_export($_GET, true));
error_log("DEBUG: REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT_SET'));
error_log("DEBUG: QUERY_STRING: " . ($_SERVER['QUERY_STRING'] ?? 'NOT_SET'));
error_log("DEBUG: Raw request: " . ($_SERVER['REQUEST_METHOD'] ?? 'NOT_SET') . " " . ($_SERVER['REQUEST_URI'] ?? 'NOT_SET'));

// Process different CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST' || !empty($_GET['action'])) {
    // Only process CRUD operations if it's a POST request or an action is specified
    $action = $action ?? 'read'; // Default to read if an action was specified but is empty

    // More specific debug: Check if the action contains unexpected characters
    if (strpos($action, '?') !== false) {
        error_log("ERROR: Action parameter contains '?' character: '$action'. This indicates malformed URL.");
        error_log("ERROR: Expected action to be one of: create, read, read_single, update, delete");
        error_log("ERROR: Check how the URL is being constructed in the JavaScript.");
    }

    // Debug: Log the action after potential default assignment
    error_log("DEBUG: Action after default assignment: " . var_export($action, true));

    // ============================== SWITCH STATEMENT EXPLANATION ==============================
    //
    // SWITCH($ACTION) LOGIC:
    // - CASE 'create': Handles form submissions for creating new records
    //   - Receives: $_POST data with form fields
    //   - Processes: Validates and inserts data into database
    //   - Returns: JSON response with success/error status and new record ID
    //
    // - CASE 'read': Retrieves all records from database
    //   - Receives: No specific input (uses default parameters)
    //   - Processes: Queries database for all applicant records
    //   - Returns: JSON array of all applicant records
    //
    // - CASE 'read_single': Retrieves a single record by ID
    //   - Receives: $_GET['id'] parameter with specific record ID
    //   - Processes: Queries database for specific applicant and related data
    //   - Returns: JSON object with complete applicant information
    //
    // - CASE 'update': Updates an existing record
    //   - Receives: $_POST data with form fields + record ID
    //   - Processes: Validates and updates existing record in database
    //   - Returns: JSON response with success/error status
    //
    // - CASE 'delete': Removes a record from database
    //   - Receives: $_POST['id'] or $_GET['id'] with record ID to delete
    //   - Processes: Removes record from database
    //   - Returns: JSON response with success/error status
    //
    // ===========================================================================
    
    switch($action) {
        case 'create':
            handleCreate();
            break;

        case 'read':
            handleRead();
            break;

        case 'read_single':
            handleReadSingle();
            break;

        case 'update':
            handleUpdate();
            break;

        case 'delete':
            handleDelete();
            break;

        default:
            // More specific debugging for invalid action
            error_log("ERROR: Received invalid action parameter: " . var_export($action, true));
            error_log("ERROR: Full GET parameters: " . var_export($_GET, true));
            error_log("ERROR: REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'NOT_SET'));
            error_log("ERROR: QUERY_STRING: " . ($_SERVER['QUERY_STRING'] ?? 'NOT_SET'));
            
            // Check if the action contains query string parts
            if (is_string($action) && strpos($action, '?') !== false) {
                $parts = explode('?', $action, 2);
                error_log("ERROR: Action appears to contain query string. Split result: " . var_export($parts, true));
                error_log("ERROR: This suggests the URL was constructed as 'action=value?param=value' instead of 'action=value&param=value'");
            }
            
            header('Content-Type: application/json');
            $errorMessage = 'Invalid action: "' . ($action ?? 'NULL') . '". ';
            $errorMessage .= 'Valid actions are: create, read, read_single, update, delete. ';
            $errorMessage .= 'GET params: ' . var_export($_GET, true) . '. ';
            $errorMessage .= 'Check URL construction - action should not contain "?" character.';
            
            echo json_encode(['error' => $errorMessage]);
            break;
    }
}

//==============================

// CREATE FUNCTION
function handleCreate() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit;
    }

    $errors = [];

    // Get form data
    $first = trim($_POST['first'] ?? '');
    $last = trim($_POST['last'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $hobby = trim($_POST['hobby'] ?? '');

    // Validate required fields
    if (!validateRequired($first)) {
        $errors[] = "First name is required";
    }
    if (!validateRequired($last)) {
        $errors[] = "Last name is required";
    }
    if (!validateRequired($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // If no errors, create the record
    if (empty($errors)) {
        try {
            // Create Usables instance
            $usables = new Usables();

            // Prepare data for insertion
            $first = trim($first);
            $last = trim($last);
            $email = trim($email);
            $phone = trim($phone);
            $location = trim($location);
            $hobby = trim($hobby);

            $data = [
                'first' => $first,
                'last' => $last,
                'email' => $email,
                'phone' => $phone,
                'location' => $location,
                'hobby' => $hobby
            ];

            $result = $usables->createRecord($data);

            if ($result['success']) {
                echo json_encode(['success' => true, 'message' => $result['message'], 'id' => $result['id']]);
            } else {
                echo json_encode(['success' => false, 'message' => $result['message']]);
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

//==============================

// READ FUNCTION
function handleRead() {
    try {
        // Create Usables instance
        $usables = new Usables();

        // Debug: Check if Usables instance was created
        if (!$usables) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Failed to create Usables instance']);
            return;
        }

        // Get all applicants ordered by ID (descending)
        $applicants = $usables->readRecords();

        // Debug: Check if readRecords returned an error
        if (isset($applicants['error'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Error in readRecords: ' . $applicants['error']]);
            return;
        }

        // Debug: Check if the result is an array and has data
        if (!is_array($applicants)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'readRecords did not return an array. Got: ' . gettype($applicants)]);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode($applicants);
    } catch(Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error in handleRead: ' . $e->getMessage()]);
    }
}

//==============================

// READ SINGLE FUNCTION
function handleReadSingle() {
    $id = $_GET['id'] ?? '';

    if (empty($id)) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'ID parameter is required']);
        exit;
    }

    try {
        // Create Usables instance
        $usables = new Usables();

        $applicant = $usables->readSingleRecord($id);

        if (isset($applicant['error'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => $applicant['error']]);
        } else {
            header('Content-Type: application/json');
            echo json_encode($applicant);
        }
    } catch(Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}

//==============================

// UPDATE FUNCTION
function handleUpdate() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit;
    }

    $errors = [];

    // Get form data
    $id = $_POST['id'] ?? '';
    $first = trim($_POST['first'] ?? '');
    $last = trim($_POST['last'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $hobby = trim($_POST['hobby'] ?? '');

    if (empty($id)) {
        $errors[] = "ID is required for update";
    }

    // Validate required fields
    if (!validateRequired($first)) {
        $errors[] = "First name is required";
    }
    if (!validateRequired($last)) {
        $errors[] = "Last name is required";
    }
    if (!validateRequired($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // If no errors, update the record
    if (empty($errors)) {
        try {
            // Create Usables instance
            $usables = new Usables();

            // Prepare data for update
            $first = trim($first);
            $last = trim($last);
            $email = trim($email);
            $phone = trim($phone);
            $location = trim($location);
            $hobby = trim($hobby);

            $data = [
                'id' => $id,
                'first' => $first,
                'last' => $last,
                'email' => $email,
                'phone' => $phone,
                'location' => $location,
                'hobby' => $hobby
            ];

            $result = $usables->updateRecord($data);

            if ($result['success']) {
                echo json_encode(['success' => true, 'message' => $result['message']]);
            } else {
                echo json_encode(['success' => false, 'message' => $result['message']]);
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

//==============================

// DELETE FUNCTION
function handleDelete() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit;
    }

    $id = $_POST['id'] ?? '';

    if (empty($id)) {
        echo json_encode(['success' => false, 'message' => 'ID is required for deletion']);
        exit;
    }

    try {
        // Create Usables instance
        $usables = new Usables();

        $result = $usables->deleteRecord($id);

        if ($result['success']) {
            echo json_encode(['success' => true, 'message' => $result['message']]);
        } else {
            echo json_encode(['success' => false, 'message' => $result['message']]);
        }
        exit();
    } catch(Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        exit();
    }
}

//==============================

// HTML interface
if ($_SERVER['REQUEST_METHOD'] === 'GET' && (empty($_GET['action']) || $_GET['action'] === 'read_interface')) {
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Simple CRUD Table</title>
        <link rel="stylesheet" href="crud.css">
    </head>
    <body>
        <div class="container">
            <h1>SSS Online Form</h1>

            <div class="main">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First</th>
                            <th>Last</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Location</th>
                            <th>Hobby</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        </tbody>
                </table>

                <div class="footer-actions">
                    <a href="../page1/page1.html" class="btn btn-add">Add New Record</a>
                    <a href="../age-cal/age-calculator.html" class="btn btn-view">View Ages</a>
                </div>
            </div>

            <!-- Modal for Add/Edit -->
            <div id="modal" class="modal">
                <div class="modal-content">
                    <h3 id="modal-title">Add New Item</h3>
                    <form id="item-form">
                        <input type="hidden" id="item-id">
                        <p><label>First Name: <input type="text" id="first" required></label></p>
                        <p><label>Last Name: <input type="text" id="last" required></label></p>
                        <p><label>Email: <input type="email" id="email" required></label></p>
                        <p><label>Phone: <input type="text" id="phone"></label></p>
                        <p><label>Location: <input type="text" id="location"></label></p>
                        <p><label>Hobby: <input type="text" id="hobby"></label></p>
                        <p>
                            <button type="submit" class="btn btn-add">Save</button>
                            <button type="button" class="btn btn-del" onclick="closeModal()">Cancel</button>
                        </p>
                    </form>
                </div>
            </div>
        </div>


        <script src="../usables/js-usables.js"></script>
        <script>
            let userData = [];

            // Load data from server
            async function loadData() {
                try {
                    // Direct API call to get records - using the main crud.php with action parameter
                    const response = await fetch(window.location.pathname + '?action=read');
                    const result = await response.json();
                    
                    if (result.error) {
                        console.error('Error loading data:', result.error);
                        // Show error to user
                        alert('Error loading data: ' + result.error);
                        return;
                    }
                    
                    userData = result;
                    renderTable();
                } catch (error) {
                    console.error('Error loading data:', error);
                    // Show error to user
                    alert('Error loading data: ' + error.message);
                }
            }

            const tableBody = document.getElementById('table-body');

            // Render function using forEach
            function renderTable() {
                // Clear existing content
                tableBody.innerHTML = "";

                // Loop through each item in the array
                userData.forEach((user) => {
                    const row = `
                        <tr>
                            <td class="id-col">${user.id}</td>
                            <td>${user.first}</td>
                            <td>${user.last}</td>
                            <td>${user.email}</td>
                            <td>${user.phone}</td>
                            <td>${user.location}</td>
                            <td>${user.hobby}</td>
                            <td>
                                <button class="btn btn-edit" onclick="editItem(${user.id})">Edit</button>
                                <button class="btn btn-del" onclick="deleteItem(${user.id})">Del</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            }

            // Show modal for adding new item
            function addItem() {
                document.getElementById('modal-title').textContent = 'Add New Item';
                document.getElementById('item-form').reset();
                document.getElementById('item-id').value = '';
                document.getElementById('modal').style.display = 'block';
            }

            // Redirect to page1.html in edit mode
            function editItem(id) {
                window.location.href = '../page1/page1.html?edit=' + encodeURIComponent(id);
            }

            // Delete item
            async function deleteItem(id) {
                if (confirm("Are you sure you want to delete ID: " + id + "?")) {
                    try {
                        // Direct API call to delete record
                        const formData = new FormData();
                        formData.append('id', id);

                        const response = await fetch(window.location.pathname + '?action=delete', {
                            method: 'POST',
                            body: formData
                        });

                        const result = await response.json();

                        if (result.success) {
                            alert("Record deleted successfully");
                            loadData(); // Reload data
                        } else {
                            alert("Error: " + result.message);
                        }
                    } catch (error) {
                        console.error('Error deleting item:', error);
                        alert("Error deleting record");
                    }
                }
            }


            // Close modal
            function closeModal() {
                document.getElementById('modal').style.display = 'none';
            }

            // Handle form submission
            document.getElementById('item-form').addEventListener('submit', async function(e) {
                e.preventDefault();

                const id = document.getElementById('item-id').value;
                const first = document.getElementById('first').value;
                const last = document.getElementById('last').value;
                const email = document.getElementById('email').value;
                const phone = document.getElementById('phone').value;
                const location = document.getElementById('location').value;
                const hobby = document.getElementById('hobby').value;

                const data = {
                    first: first,
                    last: last,
                    email: email,
                    phone: phone,
                    location: location,
                    hobby: hobby
                };

                if (id) {
                    data.id = id;
                }

                try {
                    // Direct API call to create or update record
                    let response;
                    if (id) {
                        // Update existing record
                        const formData = new FormData();
                        formData.append('id', id);
                        formData.append('first', first);
                        formData.append('last', last);
                        formData.append('email', email);
                        formData.append('phone', phone);
                        formData.append('location', location);
                        formData.append('hobby', hobby);
                        
                        response = await fetch(window.location.pathname + '?action=update', {
                            method: 'POST',
                            body: formData
                        });
                    } else {
                        // Create new record
                        const formData = new FormData();
                        formData.append('first', first);
                        formData.append('last', last);
                        formData.append('email', email);
                        formData.append('phone', phone);
                        formData.append('location', location);
                        formData.append('hobby', hobby);
                        
                        response = await fetch(window.location.pathname + '?action=create', {
                            method: 'POST',
                            body: formData
                        });
                    }

                    const result = await response.json();

                    if (result.success) {
                        alert(id ? "Record updated successfully" : "Record created successfully");
                        closeModal();
                        loadData(); // Reload data
                    } else {
                        alert("Error: " + result.message);
                    }
                } catch (error) {
                    console.error('Error saving item:', error);
                    alert("Error saving record");
                }
            });

            // Initial call to display the data
            loadData();
        </script>
    </body>
</html>
<?php
}
?>
<?php
// Include the UniversalCRUD class
require_once '../universal-crud/UniversalCRUD.php';

// Validation function
function validateRequired($value) {
    return !empty(trim($value));
}

// Determine action based on the action parameter
$action = $_GET['action'] ?? 'read';

// Process different CRUD operations
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
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid action']);
        break;
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
            $crud = new UniversalCRUD('applicants');

            // Prepare data for insertion
            $data = [
                'fname' => $first,
                'lname' => $last,
                'email' => $email,
                'cphone' => $phone,
                'nation' => $location,
                'religion' => $hobby
            ];

            $id = $crud->create($data);

            if ($id) {
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

//==============================

// READ FUNCTION
function handleRead() {
    try {
        $crud = new UniversalCRUD('applicants');

        // Get all applicants ordered by ID (descending)
        $applicants = $crud->read([], 'applicant_id', 'DESC');

        // Format the data to match what the frontend expects
        $formattedApplicants = [];
        foreach ($applicants as $applicant) {
            $formattedApplicants[] = [
                'id' => $applicant['applicant_id'],
                'first' => $applicant['fname'],
                'last' => $applicant['lname'],
                'email' => $applicant['email'],
                'phone' => $applicant['cphone'],
                'location' => $applicant['nation'],
                'hobby' => $applicant['religion']
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($formattedApplicants);
    } catch(Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
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
        $crud = new UniversalCRUD('applicants');
        $applicant = $crud->readOne($id, 'applicant_id');

        if ($applicant) {
            // Format the data to match frontend expectations
            $formattedApplicant = [
                'id' => $applicant['applicant_id'],
                'first' => $applicant['fname'],
                'last' => $applicant['lname'],
                'email' => $applicant['email'],
                'phone' => $applicant['cphone'],
                'location' => $applicant['nation'],
                'hobby' => $applicant['religion']
            ];

            header('Content-Type: application/json');
            echo json_encode($formattedApplicant);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Applicant not found']);
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
            $crud = new UniversalCRUD('applicants');

            // Prepare data for update
            $data = [
                'fname' => $first,
                'lname' => $last,
                'email' => $email,
                'cphone' => $phone,
                'nation' => $location,
                'religion' => $hobby
            ];

            $success = $crud->update($data, ['applicant_id' => $id]);

            if ($success) {
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
        $crud = new UniversalCRUD('applicants');
        $success = $crud->delete(['applicant_id' => $id]);

        if ($success) {
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

//==============================
?>
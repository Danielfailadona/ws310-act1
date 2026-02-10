# How to Code Simple CRUD Operations in PHP

This guide explains the CRUD (Create, Read, Update, Delete) operations implemented in the SSS Personal Record Form application, showcasing the specific syntaxes and techniques used in the project.

## Overview

CRUD stands for Create, Read, Update, and Delete - the four basic operations for managing data in a database. This project implements these operations using PHP and MySQL with a focus on security and user experience.

## Database Structure

The application uses a relational database with multiple tables:

```sql
-- Main Applicants Table
CREATE TABLE applicants (
    applicant_id INT AUTO_INCREMENT PRIMARY KEY,
    last_name VARCHAR(100) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    suffix VARCHAR(10),
    dob DATE NOT NULL,
    sex ENUM('Male', 'Female') NOT NULL,
    civil_status VARCHAR(50) NOT NULL,
    tin VARCHAR(50),
    nationality VARCHAR(100) NOT NULL,
    religion VARCHAR(100),
    place_of_birth TEXT NOT NULL,
    mobile VARCHAR(20) NOT NULL,
    email VARCHAR(255) NOT NULL,
    telephone VARCHAR(50),
    printed_name VARCHAR(255),
    certification_date DATE,
    submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## C - Create Operation

### Form Collection (create.php)
```php
// Collecting form data
<input type="text" name="last_name" placeholder="Last Name" required>
<input type="text" name="first_name" placeholder="First Name" required>
<input type="date" name="dob" required>
```

### Data Processing (includes/process_crud.php)
```php
// Sanitize function
function clean($conn, $value, $upper = false) {
    $value = trim($value ?? '');
    if ($upper) {
        $value = strtoupper($value);
    }
    return mysqli_real_escape_string($conn, $value);
}

// Collect applicant data
$data['last_name'] = clean($conn, $_POST['last_name'], true);
$data['first_name'] = clean($conn, $_POST['first_name'], true);
$data['dob'] = clean($conn, $_POST['dob']);
// ... more fields

// Insert query
$insert_query = "INSERT INTO applicants (
    last_name, first_name, middle_name, suffix, dob, sex, civil_status,
    tin, nationality, religion, place_of_birth, mobile, email, telephone,
    printed_name, certification_date
) VALUES (
    '{$data['last_name']}', '{$data['first_name']}', '{$data['middle_name']}', '{$data['suffix']}',
    '{$data['dob']}', '{$data['sex']}', '{$data['civil_status']}',
    '{$data['tin']}', '{$data['nationality']}', '{$data['religion']}', '{$data['place_of_birth']}',
    '{$data['mobile']}', '{$data['email']}', '{$data['telephone']}',
    '{$data['printed_name']}', '{$data['certification_date']}'
)";

if (!mysqli_query($conn, $insert_query)) {
    throw new Exception(mysqli_error($conn));
}

$applicant_id = mysqli_insert_id($conn);
```

### Insert Related Data
```php
// Insert address data
$address_query = "INSERT INTO applicant_addresses (
    applicant_id, address_unit, address_street, address_subdivision,
    address_barangay, address_city, address_province, address_country, address_zip,
    same_as_place_of_birth
) VALUES (
    $applicant_id, '{$address_data['address_unit']}', '{$address_data['address_street']}',
    '{$address_data['address_subdivision']}', '{$address_data['address_barangay']}',
    '{$address_data['address_city']}', '{$address_data['address_province']}',
    '{$address_data['address_country']}', '{$address_data['address_zip']}',
    {$address_data['same_as_place_of_birth']}
)";

mysqli_query($conn, $address_query);
```

## R - Read Operation

### Fetching All Records (read.php)
```php
// Pagination setup
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 10;
$offset = ($page - 1) * $records_per_page;

// Count total records
$count_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM applicants");
$count_row = mysqli_fetch_assoc($count_result);
$total_records = $count_row['total'];

// Fetch records with pagination
$query = "SELECT applicant_id, last_name, first_name, middle_name, email, mobile, dob, submission_date
          FROM applicants
          ORDER BY submission_date DESC
          LIMIT $offset, $records_per_page";

$result = mysqli_query($conn, $query);

// Display records
while ($row = mysqli_fetch_assoc($result)):
    echo $row['applicant_id'];
    echo htmlspecialchars($row['last_name'] . ', ' . $row['first_name'] . ' ' . $row['middle_name']);
    // ... display other fields
endwhile;
```

### Search Functionality (read.php)
```php
// Search implementation
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_query = '';
if ($search !== '') {
    $s_esc = mysqli_real_escape_string($conn, $search);
    $like = "%$s_esc%";

    $conds = [
        "last_name LIKE '$like'",
        "first_name LIKE '$like'",
        "middle_name LIKE '$like'",
        "email LIKE '$like'",
        "CONCAT(first_name, ' ', last_name) LIKE '$like'",
        "CONCAT(last_name, ' ', first_name) LIKE '$like'",
        "CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE '$like'"
    ];

    $search_query = 'WHERE (' . implode(' OR ', $conds) . ')';
}
```

### Fetch Single Record (view_detail.php)
```php
// Fetch single record with related data
$query = "SELECT a.*, ad.*, ap.*, sp.*, ch.*
          FROM applicants a
          LEFT JOIN applicant_addresses ad ON a.applicant_id = ad.applicant_id
          LEFT JOIN applicant_parents ap ON a.applicant_id = ap.applicant_id
          LEFT JOIN applicant_spouse sp ON a.applicant_id = sp.applicant_id
          LEFT JOIN applicant_children ch ON a.applicant_id = ch.applicant_id
          WHERE a.applicant_id = $id";

$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);
```

## U - Update Operation

### Form Pre-population (update.php)
```php
// Pre-populating form with existing data
<input type="text" name="last_name" placeholder="Last Name" value="<?php echo htmlspecialchars($data['last_name']); ?>" required>
<input type="text" name="first_name" placeholder="First Name" value="<?php echo htmlspecialchars($data['first_name']); ?>" required>
<input type="date" name="dob" value="<?php echo $data['dob']; ?>" required>

// Radio buttons with checked state
<label>
    <input type="radio" name="sex" value="Male" <?php echo $data['sex'] == 'Male' ? 'checked' : ''; ?> required> Male
    <input type="radio" name="sex" value="Female" <?php echo $data['sex'] == 'Female' ? 'checked' : ''; ?>> Female
</label>
```

### Update Query (includes/process_crud.php)
```php
// Update applicant data
$update_query = "UPDATE applicants SET
    last_name = '{$data['last_name']}',
    first_name = '{$data['first_name']}',
    middle_name = '{$data['middle_name']}',
    suffix = '{$data['suffix']}',
    dob = '{$data['dob']}',
    sex = '{$data['sex']}',
    civil_status = '{$data['civil_status']}',
    tin = '{$data['tin']}',
    nationality = '{$data['nationality']}',
    religion = '{$data['religion']}',
    place_of_birth = '{$data['place_of_birth']}',
    mobile = '{$data['mobile']}',
    email = '{$data['email']}',
    telephone = '{$data['telephone']}',
    printed_name = '{$data['printed_name']}',
    certification_date = '{$data['certification_date']}'
WHERE applicant_id = $applicant_id";

if (!mysqli_query($conn, $update_query)) {
    throw new Exception(mysqli_error($conn));
}
```

### Update Related Data
```php
// Update address
$address_query = "UPDATE applicant_addresses SET
    address_unit = '{$address_data['address_unit']}',
    address_street = '{$address_data['address_street']}',
    address_subdivision = '{$address_data['address_subdivision']}',
    address_barangay = '{$address_data['address_barangay']}',
    address_city = '{$address_data['address_city']}',
    address_province = '{$address_data['address_province']}',
    address_country = '{$address_data['address_country']}',
    address_zip = '{$address_data['address_zip']}',
    same_as_place_of_birth = {$address_data['same_as_place_of_birth']}
WHERE applicant_id = $applicant_id";

mysqli_query($conn, $address_query);
```

## D - Delete Operation

### Confirmation Page (delete.php)
```php
// Fetch record for confirmation
$query = "SELECT first_name, last_name FROM applicants WHERE applicant_id = $id";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Display confirmation
<p>You are about to permanently delete the record for:</p>
<p style="font-weight: bold; font-size: 18px;">
    <?php echo htmlspecialchars($data['first_name'] . ' ' . $data['last_name']); ?>
</p>
```

### Delete Query with Cascade (includes/process_crud.php)
```php
// Delete related records first (due to foreign key constraints)
mysqli_query($conn, "DELETE FROM applicant_addresses WHERE applicant_id = $applicant_id");
mysqli_query($conn, "DELETE FROM applicant_parents WHERE applicant_id = $applicant_id");
mysqli_query($conn, "DELETE FROM applicant_spouse WHERE applicant_id = $applicant_id");
mysqli_query($conn, "DELETE FROM applicant_children WHERE applicant_id = $applicant_id");
mysqli_query($conn, "DELETE FROM applicant_employment WHERE applicant_id = $applicant_id");

// Delete main applicant record
$delete_query = "DELETE FROM applicants WHERE applicant_id = $applicant_id";

if (!mysqli_query($conn, $delete_query)) {
    throw new Exception(mysqli_error($conn));
}
```

## Security Measures

### Input Sanitization
```php
function clean($conn, $value, $upper = false) {
    $value = trim($value ?? '');
    if ($upper) {
        $value = strtoupper($value);
    }
    return mysqli_real_escape_string($conn, $value);
}
```

### CSRF Protection
```php
// Generate token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Validate token
if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $_SESSION['error'] = 'Invalid CSRF token.';
    header('Location: dashboard.php');
    exit;
}
```

### Session Management
```php
// Store success/error messages
$_SESSION['success'] = 'Record created successfully!';
$_SESSION['error'] = 'An internal error occurred. Please try again later.';

// Display messages
if (isset($_SESSION['success'])) {
    echo '<div class="success-message">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
}
```

## Best Practices Used

1. **Input Validation**: All user inputs are validated and sanitized
2. **SQL Injection Prevention**: Using mysqli_real_escape_string() for all inputs
3. **CSRF Protection**: Token-based protection for form submissions
4. **Session Management**: Proper handling of success/error messages
5. **Error Handling**: Try-catch blocks for exception handling
6. **Data Integrity**: Foreign key constraints and cascade deletes
7. **User Experience**: Confirmation dialogs and clear feedback
8. **Code Reusability**: Centralized configuration and functions

This CRUD implementation provides a secure, user-friendly way to manage SSS personal records with proper validation, security measures, and user experience considerations.
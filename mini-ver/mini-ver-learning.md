# Mini Version Learning Guide

## Overview

This guide explains the **mini-ver** (mini version) of the SSS Online Form system. The mini version is a simplified implementation that demonstrates core concepts with reduced complexity.

## Architecture

### Database Structure
The mini version uses 2 tables with 2 data columns each:

1. **applicants** table
   - `ssnum` - Social Security Number
   - `lname` - Last Name
   - `fname` - First Name

2. **applicant_addresses** table
   - `address_6` - City
   - `address_7` - Province

### Relationships
- One-to-one relationship between applicants and addresses
- Foreign key: `applicant_id` in addresses table references `applicant_id` in applicants table

## File Structure

| File | Purpose |
|------|---------|
| `schema.sql` | Database schema definition |
| `mini_form.html` | User input form |
| `mini_process.php` | Form processing backend |
| `mini_style.css` | Styling for the form |
| `mini_crud.php` | Complete CRUD operations interface |

## HTML Form Structure

### Basic Form Elements
```html
<form action="mini_process.php" method="post">
    <input type="text" name="ssnum" placeholder="SS Number" required>
    <input type="text" name="lname" placeholder="Last Name" required>
    <input type="text" name="fname" placeholder="First Name" required>
    <input type="text" name="address_6" placeholder="City" required>
    <input type="text" name="address_7" placeholder="Province" required>
    <input type="submit" value="Submit">
</form>
```

### Form Processing Flow
1. User fills the form
2. Form submits to `mini_process.php` via POST method
3. PHP validates input data
4. Data is inserted into both database tables
5. Success message is shown

## PHP Processing

### Database Connection
```php
function connectDB() {
    $host = 'localhost';
    $dbname = 'ws310_mini_db';
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
```

### Data Validation
```php
function validateRequired($value) {
    return !empty(trim($value));
}
```

### Insert Operations
The system performs a two-step insert:
1. Insert into `applicants` table
2. Use the generated `applicant_id` to insert into `applicant_addresses` table

```php
// Insert applicant data
$applicantSql = "INSERT INTO applicants (ssnum, lname, fname) VALUES (?, ?, ?)";
$applicantStmt = $conn->prepare($applicantSql);
$applicantStmt->execute([$ssnum, $lname, $fname]);
$applicantId = $conn->lastInsertId();  // Get the ID of the newly inserted record

// Insert address data using the applicant ID
$addressSql = "INSERT INTO applicant_addresses (applicant_id, address_6, address_7) VALUES (?, ?, ?)";
$addressStmt = $conn->prepare($addressSql);
$addressStmt->execute([$applicantId, $address_6, $address_7]);
```

## CRUD Operations

### Create (C)
- Collects data from the form
- Validates required fields
- Inserts into both tables within a transaction
- Shows success/error messages

### Read (R)
- Retrieves data by joining both tables
- Displays all records in the mini_crud.php interface
- Uses LEFT JOIN to show applicant info even if address is missing

### Update (U)
- Modifies existing records in both tables
- Uses transactions to ensure data consistency
- Updates both applicant and address information

### Delete (D)
- Removes records from both tables
- Uses transactions to maintain referential integrity
- Deletes address record first, then applicant record

## JavaScript Features

### Auto-Capitalization
```javascript
const textInputs = document.querySelectorAll('input[type="text"]');
textInputs.forEach(input => {
    input.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
});
```

### Success Message Display
```javascript
// Show success message if form was submitted
if (sessionStorage.getItem('formSuccess') === 'true') {
    document.getElementById('success-message').style.display = 'block';
    sessionStorage.removeItem('formSuccess');
    setTimeout(function() {
        document.getElementById('success-message').style.display = 'none';
    }, 5000);
}
```

## Database Transactions

The mini version uses database transactions to ensure data integrity:

```php
$conn->beginTransaction();  // Start transaction

// Perform multiple operations
// If any fails, all are rolled back
// If all succeed, all are committed

try {
    // Insert applicant
    // Insert address
    $conn->commit();  // Save all changes
} catch(PDOException $e) {
    $conn->rollBack();  // Undo all changes
    // Handle error
}
```

## Error Handling

### Validation Errors
- Checks for required fields
- Shows error messages to user
- Prevents form submission if validation fails

### Database Errors
- Uses try-catch blocks
- Implements transaction rollbacks
- Provides user-friendly error messages

## CSS Styling

The mini version uses a simplified CSS structure:

- CSS variables for consistent colors
- Responsive layout using flexbox
- Consistent styling for form elements
- Visual feedback for user interactions

## Key Concepts Demonstrated

### 1. Database Normalization
- Separates applicant and address data into different tables
- Maintains referential integrity with foreign keys

### 2. Form Processing
- Secure data handling with prepared statements
- Input validation on both client and server side

### 3. User Experience
- Success/failure feedback
- Auto-capitalization for proper formatting
- Responsive design

### 4. Data Integrity
- Transaction-based operations
- Foreign key constraints
- Proper error handling

## Differences from Full Version

| Aspect | Mini Version | Full Version |
|--------|--------------|--------------|
| Tables | 2 | 6 |
| Data Columns | 2 per table | Many per table |
| Features | Basic CRUD | Full functionality |
| Complexity | Low | High |
| Learning Focus | Core concepts | Complete system |

## Getting Started

1. Execute `schema.sql` to create the database
2. Update database credentials in PHP files if needed
3. Access `mini_form.html` to add records
4. Access `mini_crud.php` to view, edit, and delete records

## Best Practices Demonstrated

- **Security**: Prepared statements prevent SQL injection
- **Data Integrity**: Transactions ensure consistency
- **Validation**: Both client and server-side validation
- **User Experience**: Clear feedback and intuitive interface
- **Code Organization**: Separation of concerns (HTML, CSS, JS, PHP)

This mini version serves as an excellent learning tool to understand the fundamental concepts before moving to the full, more complex implementation.
# Re-Usables Guide: Creating and Using Utility Classes in Multiple Languages

## Introduction

This guide explains how to create and use reusable utility classes in PHP and JavaScript, following the example of the `Usables` class. The `Usables` class is a utility system that provides common functionality for applications, designed to be accessible from anywhere in the project. It includes database operations, data validation, form handling, and other common utilities.

## Understanding the Usables System

The `Usables` system consists of:
- **PHP functions** (`usables.php`): Procedural functions for server-side operations
- **PHP class** (`Usables`): Object-oriented wrapper for the procedural functions
- **JavaScript functions** (`js-usables.js`): Client-side utility functions
- **JavaScript class** (`Usables`): Object-oriented wrapper for client-side functions

### Core Functionality

The `Usables` system provides:

1. **Database Operations**:
   - `getDataFromDatabase()` - Fetch records from database
   - `insertDataToDatabase()` - Insert records to database
   - `updateDataInDatabase()` - Update records in database
   - `deleteDataFromDatabase()` - Delete records from database
   - `getSingleRecord()` - Get a single record by ID
   - `recordExists()` - Check if a record exists

2. **Data Processing**:
   - `clean()` - Sanitize and clean input data
   - `validateRequired()` - Validate required fields
   - `validateEmail()` - Validate email format
   - `validatePhone()` - Validate Philippine phone numbers
   - `validatePastDate()` - Validate dates in the past
   - `formatDateForDB()` - Format dates for database storage
   - `validateData()` - Generic validation with rules
   - `processFormData()` - Process form data with validation

3. **Form Handling**:
   - `applyAutoUppercase()` - Auto-uppercase text inputs
   - `setupCivilStatusToggle()` - Handle civil status "Others" field
   - `setupSameAddressFunctionality()` - Handle "Same as Home Address" functionality
   - `setupAddChildFunctionality()` - Dynamically add child entries
   - `populateFormFields()` - Populate form fields with data
   - `populateNestedData()` - Populate complex nested data
   - `loadApplicantData()` - Load applicant data for editing

4. **CRUD Operations**:
   - `handleCRUDOperation()` - Generic CRUD operation handler
   - `handleCreateOperation()` - Handle CREATE operations
   - `handleReadOperation()` - Handle READ operations
   - `handleReadSingleOperation()` - Handle READ_SINGLE operations
   - `handleUpdateOperation()` - Handle UPDATE operations
   - `handleDeleteOperation()` - Handle DELETE operations

## How to Use the Usables System

### PHP Implementation

#### Including the File

```php
require_once 'path/to/usables.php';
```

#### Using Procedural Functions

```php
// Database operations
$users = getDataFromDatabase('users', ['id', 'name', 'email'], ['status' => 'active'], 'name', 'ASC');
$id = insertDataToDatabase('users', ['name' => 'John', 'email' => 'john@example.com']);
$success = updateDataInDatabase('users', ['name' => 'Updated John'], ['id' => 123]);
$exists = recordExists('users', ['email' => 'john@example.com']);

// Data validation
if (validateRequired($_POST['name'])) {
    $cleanName = clean($_POST['name'], true); // Clean and convert to uppercase
}
```

#### Using the Object-Oriented Wrapper

```php
$usables = new Usables();

// Using the class methods
$users = $usables->getDataFromDatabase('users', ['id', 'name'], ['status' => 'active']);
$id = $usables->insertDataToDatabase('users', ['name' => 'John', 'email' => 'john@example.com']);
$record = $usables->getSingleRecord('users', 123);
$exists = $usables->recordExists('users', ['email' => 'john@example.com']);

// CRUD operations
$result = $usables->createRecord([
    'first' => 'John',
    'last' => 'Doe',
    'email' => 'john@example.com'
]);
```

### JavaScript Implementation

#### Including the Script

```html
<script src="path/to/js-usables.js"></script>
```

#### Using Procedural Functions

```javascript
// Form handling
applyAutoUppercase();
setupCivilStatusToggle();
setupSameAddressFunctionality();

// Loading data
const applicant = await loadApplicantData(123, '/api/applicant');
populateFormFields(applicant.data);
```

#### Using the Object-Oriented Wrapper

```javascript
const usables = new Usables();

// Database operations
const users = await usables.getDataFromDatabase('users', ['id', 'name'], { status: 'active' });
const id = await usables.insertDataToDatabase('users', { name: 'John', email: 'john@example.com' });
const record = await usables.getSingleRecord('users', 123);
const exists = await usables.recordExists('users', { email: 'john@example.com' });

// CRUD operations
const result = await usables.createRecord({
    first: 'John',
    last: 'Doe',
    email: 'john@example.com'
});
```

## Best Practices for Using Usables

### 1. Consistent Naming Convention
Use consistent method names across the system for similar functionality.

### 2. Proper Error Handling
Always include proper error handling when using utility functions:

```php
try {
    $result = $usables->createRecord($data);
    if ($result['success']) {
        // Handle success
    } else {
        // Handle error
        echo $result['message'];
    }
} catch (Exception $e) {
    error_log("Error creating record: " . $e->getMessage());
}
```

### 3. Data Validation
Always validate data before processing:

```php
$validationRules = [
    'name' => 'required',
    'email' => 'required|email',
    'phone' => 'phone'
];

$errors = validateData($_POST, $validationRules);
if (empty($errors)) {
    // Process data
} else {
    // Handle validation errors
}
```

### 4. Configuration Management
Use configuration arrays for flexibility:

```php
$config = [
    'host' => 'localhost',
    'dbname' => 'mydb',
    'username' => 'user',
    'password' => 'pass'
];

$users = getDataFromDatabase('users', [], [], 'name', 'ASC', 0, 0, $config);
```

## Common Use Cases

The Usables system is ideal for:
- **Database operations**: Simplifying CRUD operations
- **Form processing**: Handling validation and data insertion
- **Data validation**: Standardizing validation across the application
- **User input sanitization**: Ensuring secure data handling
- **API integration**: Providing consistent data access patterns
- **Form handling**: Managing complex form interactions

## Extending the Usables System

To add new functionality to the Usables system:

1. **Add procedural functions** in the appropriate file (usables.php or js-usables.js)
2. **Add corresponding class methods** in the Usables class
3. **Follow the same naming conventions** and parameter patterns
4. **Include proper documentation** with PHPDoc/JSdoc comments
5. **Add usage examples** in the documentation

## Conclusion

The Usables system provides a comprehensive set of utilities for handling common operations in web applications. By using this system, you can:
- Reduce code duplication
- Ensure consistent behavior across your application
- Improve maintainability
- Enhance security through standardized validation and sanitization
- Simplify complex operations with easy-to-use functions

The system is designed to be flexible and extensible, allowing you to add new functionality while maintaining consistency with existing patterns.
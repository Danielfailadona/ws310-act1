# Universal CRUD Manual

## Overview

The Universal CRUD system is a simplified, reusable class for performing database operations on any table. It provides a consistent interface for Create, Read, Update, and Delete operations with built-in security features.

## Getting Started

### 1. Include the UniversalCRUD Class

```php
// Include the main UniversalCRUD class
require_once 'universal-crud/UniversalCRUD.php';

// Or include specific operations
require_once 'universal-crud/UniversalCRUD_Create.php';
require_once 'universal-crud/UniversalCRUD_Read.php';
require_once 'universal-crud/UniversalCRUD_Update.php';
require_once 'universal-crud/UniversalCRUD_Delete.php';
```

### 2. Create a CRUD Instance

```php
// Create a CRUD instance for a specific table
$crud = new UniversalCRUD('applicants');  // For the 'applicants' table
$addressCrud = new UniversalCRUD('applicant_addresses');  // For the 'applicant_addresses' table
```

## CRUD Operations

### Create (Insert New Records)

#### Basic Usage
```php
// Create a new applicant
$crud = new UniversalCRUD('applicants');

$data = [
    'ssnum' => '123-456-789',
    'lname' => 'DOE',
    'fname' => 'JOHN'
];

$newId = $crud->create($data);

if ($newId) {
    echo "Record created successfully with ID: $newId";
} else {
    echo "Failed to create record";
}
```

#### Example 1: Using Concrete Values
```php
// Create a new applicant with specific data
$crud = new UniversalCRUD('applicants');

$applicantData = [
    'ssnum' => '123-456-789',
    'lname' => 'DOE',
    'fname' => 'JOHN',
    'mname' => 'JAMES',
    'dbirth' => '1990-01-15'
];

$newApplicantId = $crud->create($applicantData);

if ($newApplicantId) {
    echo "Applicant created successfully with ID: $newApplicantId";

    // Now create address for this applicant
    $addressCrud = new UniversalCRUD('applicant_addresses');
    $addressData = [
        'applicant_id' => $newApplicantId,
        'address_6' => 'MANILA',
        'address_7' => 'NCR'
    ];

    $newAddressId = $addressCrud->create($addressData);
    if ($newAddressId) {
        echo "Address created successfully with ID: $newAddressId";
    }
} else {
    echo "Failed to create applicant";
}
```

#### Example 2: Using Variables
```php
// Create a new applicant using variables
$crud = new UniversalCRUD('applicants');

$ssnum = '987-654-321';
$lname = 'SMITH';
$fname = 'JANE';
$mname = 'MARIE';
$dbirth = '1985-05-20';

$applicantData = [
    'ssnum' => $ssnum,
    'lname' => $lname,
    'fname' => $fname,
    'mname' => $mname,
    'dbirth' => $dbirth
];

$newApplicantId = $crud->create($applicantData);

if ($newApplicantId) {
    echo "Applicant created successfully with ID: $newApplicantId";

    // Using variables for address data
    $addressCrud = new UniversalCRUD('applicant_addresses');
    $city = 'CEBU CITY';
    $province = 'CEBU';

    $addressData = [
        'applicant_id' => $newApplicantId,
        'address_6' => $city,
        'address_7' => $province
    ];

    $newAddressId = $addressCrud->create($addressData);
    if ($newAddressId) {
        echo "Address created successfully with ID: $newAddressId";
    }
} else {
    echo "Failed to create applicant";
}
```

#### Parameters
- `$data` - Associative array of column => value pairs to insert
- Returns the ID of the newly created record or false on failure

#### Example with Validation
```php
// Validate data before creating
if (empty(trim($_POST['ssnum']))) {
    echo "SS Number is required";
} else {
    $data = [
        'ssnum' => trim($_POST['ssnum']),
        'lname' => strtoupper(trim($_POST['lname'])),
        'fname' => strtoupper(trim($_POST['fname']))
    ];
    
    $crud = new UniversalCRUD('applicants');
    $id = $crud->create($data);
    
    if ($id) {
        echo "Applicant created with ID: $id";
    }
}
```

### Read (Retrieve Records)

#### Read All Records
```php
$crud = new UniversalCRUD('applicants');

// Get all records
$allApplicants = $crud->read();

// Get all records ordered by last name
$orderedApplicants = $crud->read([], 'lname', 'ASC');

// Get all records with limit and offset (for pagination)
$limitedApplicants = $crud->read([], 'lname', 'ASC', 10, 0);  // 10 records, starting from 0
```

#### Example 1: Using Concrete Values
```php
$crud = new UniversalCRUD('applicants');

// Get all applicants with 'DOE' last name, ordered by first name ascending
$doeRecords = $crud->read(['lname' => 'DOE'], 'fname', 'ASC');

// Get first 5 applicants ordered by ID descending
$recentApplicants = $crud->read([], 'applicant_id', 'DESC', 5, 0);

// Get applicants with specific conditions
$specificRecords = $crud->read([
    'lname' => 'DOE',
    'cvstatus' => 'SINGLE'
], 'fname', 'ASC');

// Display the results
foreach ($doeRecords as $applicant) {
    echo "ID: " . $applicant['applicant_id'] . " - " .
         $applicant['fname'] . " " . $applicant['lname'] . "<br>";
}
```

#### Example 2: Using Variables
```php
$crud = new UniversalCRUD('applicants');

$searchLastName = 'SMITH';
$orderByField = 'lname';
$orderDirection = 'DESC';
$limitCount = 20;
$offsetValue = 0;

// Get applicants using variables
$applicants = $crud->read(
    ['lname' => $searchLastName],
    $orderByField,
    $orderDirection,
    $limitCount,
    $offsetValue
);

// Using variables for multiple conditions
$cvStatus = 'MARRIED';
$nationality = 'FILIPINO';

$filteredApplicants = $crud->read([
    'cvstatus' => $cvStatus,
    'nation' => $nationality
], 'lname', 'ASC');

// Process the results
foreach ($applicants as $applicant) {
    $id = $applicant['applicant_id'];
    $firstName = $applicant['fname'];
    $lastName = $applicant['lname'];

    echo "Applicant ID: $id - $firstName $lastName<br>";
}
```

#### Read with Conditions
```php
$crud = new UniversalCRUD('applicants');

// Get applicants with specific last name
$doeApplicants = $crud->read(['lname' => 'DOE']);

// Get applicants with multiple conditions
$specificApplicants = $crud->read([
    'lname' => 'DOE',
    'fname' => 'JOHN'
]);
```

#### Read Single Record
```php
$crud = new UniversalCRUD('applicants');

// Get single applicant by ID (default ID column is 'id')
$applicant = $crud->readOne(123);

// Get single applicant by custom ID column
$applicant = $crud->readOne(123, 'applicant_id');
```

#### Parameters for Read Operations
- `$conditions` - Associative array of column => value pairs for WHERE clause (optional)
- `$orderBy` - Column name to order by (optional)
- `$orderDirection` - Direction to order ('ASC' or 'DESC', optional, defaults to 'ASC')
- `$limit` - Maximum number of records to return (optional)
- `$offset` - Number of records to skip (for pagination, optional)

### Update (Modify Existing Records)

#### Basic Update
```php
$crud = new UniversalCRUD('applicants');

// Data to update
$updateData = [
    'fname' => 'JANE',
    'email' => 'jane.doe@example.com'
];

// Conditions to identify which record(s) to update
$conditions = [
    'applicant_id' => 123
];

$success = $crud->update($updateData, $conditions);

if ($success) {
    echo "Record updated successfully";
} else {
    echo "Failed to update record";
}
```

#### Update by ID (Simplified)
```php
$crud = new UniversalCRUD('applicants');

// Update applicant with ID 123
$success = $crud->update(
    ['fname' => 'JANET'],  // What to update
    ['applicant_id' => 123]  // Which record to update
);

if ($success) {
    echo "Applicant updated successfully";
}
```

#### Example 1: Using Concrete Values
```php
$crud = new UniversalCRUD('applicants');

// Update applicant with specific values
$updateSuccess = $crud->update(
    [
        'fname' => 'MARY',
        'lname' => 'JOHNSON',
        'email' => 'mary.johnson@example.com',
        'cphone' => '09171234567'
    ],  // Data to update
    [
        'applicant_id' => 456  // Which record to update
    ]  // Conditions
);

if ($updateSuccess) {
    echo "Applicant record updated successfully";

    // Also update their address
    $addressCrud = new UniversalCRUD('applicant_addresses');
    $addressUpdateSuccess = $addressCrud->update(
        [
            'address_6' => 'BAGUIO CITY',
            'address_7' => 'BENGUET'
        ],
        [
            'applicant_id' => 456  // Same applicant ID
        ]
    );

    if ($addressUpdateSuccess) {
        echo "Address updated successfully";
    }
} else {
    echo "Failed to update applicant record";
}
```

#### Example 2: Using Variables
```php
$crud = new UniversalCRUD('applicants');

// Using variables for update data
$newFirstName = 'SARAH';
$newLastName = 'CONNOR';
$newEmail = 'sarah.connor@example.com';
$targetId = 789;

$updateData = [
    'fname' => $newFirstName,
    'lname' => $newLastName,
    'email' => $newEmail
];

$conditions = [
    'applicant_id' => $targetId
];

$success = $crud->update($updateData, $conditions);

if ($success) {
    echo "Record updated successfully";

    // Using variables for address update
    $addressCrud = new UniversalCRUD('applicant_addresses');
    $newCity = 'DAVAO CITY';
    $newProvince = 'DAVAO DEL SUR';

    $addressData = [
        'address_6' => $newCity,
        'address_7' => $newProvince
    ];

    $addressConditions = [
        'applicant_id' => $targetId
    ];

    $addressSuccess = $addressCrud->update($addressData, $addressConditions);

    if ($addressSuccess) {
        echo "Address updated successfully";
    }
} else {
    echo "Failed to update record";
}
```

#### Parameters for Update
- `$data` - Associative array of column => new_value pairs to update
- `$conditions` - Associative array of column => value pairs for WHERE clause
- Returns true on success, false on failure

#### Example with Form Processing
```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    if (empty($id)) {
        echo "ID is required for update";
    } else {
        $crud = new UniversalCRUD('applicants');
        
        $updateData = [
            'fname' => $fname,
            'lname' => $lname,
            'email' => $email
        ];
        
        $conditions = ['applicant_id' => $id];
        
        $success = $crud->update($updateData, $conditions);
        
        if ($success) {
            echo "Record updated successfully";
        } else {
            echo "Failed to update record";
        }
    }
}
```

### Delete (Remove Records)

#### Delete with Conditions
```php
$crud = new UniversalCRUD('applicants');

// Delete applicant with specific ID
$success = $crud->delete(['applicant_id' => 123]);

// Delete applicants with specific last name
$success = $crud->delete(['lname' => 'DOE']);

// Delete with multiple conditions
$success = $crud->delete([
    'lname' => 'DOE',
    'fname' => 'JOHN'
]);

if ($success) {
    echo "Record(s) deleted successfully";
} else {
    echo "Failed to delete record(s)";
}
```

#### Example 1: Using Concrete Values
```php
$crud = new UniversalCRUD('applicants');

// Delete applicant with specific ID
$deleteSuccess = $crud->delete(['applicant_id' => 123]);

if ($deleteSuccess) {
    echo "Applicant with ID 123 deleted successfully";

    // Also delete related address records
    $addressCrud = new UniversalCRUD('applicant_addresses');
    $addressDeleteSuccess = $addressCrud->delete(['applicant_id' => 123]);

    if ($addressDeleteSuccess) {
        echo "Related address records deleted successfully";
    }
} else {
    echo "Failed to delete applicant record";
}

// Delete multiple records with same last name
$multipleDeleteSuccess = $crud->delete(['lname' => 'DOE']);
echo "Attempted to delete records with last name 'DOE'";
```

#### Example 2: Using Variables
```php
$crud = new UniversalCRUD('applicants');

// Using variables for deletion
$targetId = 456;
$targetLastName = 'SMITH';

// Delete by ID using variable
$success = $crud->delete(['applicant_id' => $targetId]);

if ($success) {
    echo "Record with ID $targetId deleted successfully";

    // Using variable for another deletion
    $anotherSuccess = $crud->delete(['lname' => $targetLastName]);
    if ($anotherSuccess) {
        echo "All records with last name '$targetLastName' deleted successfully";
    }
} else {
    echo "Failed to delete record with ID $targetId";
}

// Using variables for complex conditions
$firstName = 'JOHN';
$city = 'MANILA';

$complexDeleteSuccess = $crud->delete([
    'fname' => $firstName,
    'cvstatus' => 'SINGLE'
]);

if ($complexDeleteSuccess) {
    echo "All single '$firstName' records deleted successfully";
}
```

#### Delete by ID (Simplified)
```php
$crud = new UniversalCRUD('applicants');

// Delete applicant with ID 123
$success = $crud->delete(['applicant_id' => 123]);

if ($success) {
    echo "Record deleted successfully";
}
```

#### Parameters for Delete
- `$conditions` - Associative array of column => value pairs for WHERE clause
- Returns true on success, false on failure

#### Example with Confirmation
```php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = $_POST['delete_id'];
    
    $crud = new UniversalCRUD('applicants');
    $success = $crud->delete(['applicant_id' => $id]);
    
    if ($success) {
        echo "Record deleted successfully";
    } else {
        echo "Failed to delete record";
    }
}
```

## Advanced Operations

### Count Records
```php
$crud = new UniversalCRUD('applicants');

// Count all records
$total = $crud->count();

// Count records with conditions
$doeCount = $crud->count(['lname' => 'DOE']);

echo "Total applicants: $total";
echo "Applicants with last name DOE: $doeCount";
```

### Search Records (Using LIKE)
```php
$crud = new UniversalCRUD('applicants');

// Search for records containing 'DOE' in any field (OR match)
$results = $crud->search(['lname' => 'DOE'], 'any');

// Search for records matching multiple fields (AND match)
$results = $crud->search([
    'fname' => 'JOHN',
    'lname' => 'DOE'
], 'all');

// Display search results
foreach ($results as $applicant) {
    echo $applicant['fname'] . ' ' . $applicant['lname'] . '<br>';
}
```

## Complete Example: Processing a Form

Here's a complete example of how to use UniversalCRUD in a form processing script:

```php
<?php
// Include the UniversalCRUD class
require_once 'universal-crud/UniversalCRUD.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $ssnum = trim($_POST['ssnum'] ?? '');
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $province = trim($_POST['province'] ?? '');
    
    // Validate required fields
    $errors = [];
    if (empty($ssnum)) $errors[] = "SS Number is required";
    if (empty($fname)) $errors[] = "First Name is required";
    if (empty($lname)) $errors[] = "Last Name is required";
    if (empty($city)) $errors[] = "City is required";
    if (empty($province)) $errors[] = "Province is required";
    
    if (!empty($errors)) {
        // Show validation errors
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
    } else {
        try {
            // Create CRUD instance for applicants
            $applicantCrud = new UniversalCRUD('applicants');
            
            // Insert applicant data
            $applicantData = [
                'ssnum' => $ssnum,
                'fname' => strtoupper($fname),
                'lname' => strtoupper($lname),
                'email' => strtolower($email)
            ];
            
            $applicantId = $applicantCrud->create($applicantData);
            
            if ($applicantId) {
                // Create CRUD instance for addresses
                $addressCrud = new UniversalCRUD('applicant_addresses');
                
                // Insert address data
                $addressData = [
                    'applicant_id' => $applicantId,
                    'address_6' => strtoupper($city),
                    'address_7' => strtoupper($province)
                ];
                
                $addressId = $addressCrud->create($addressData);
                
                if ($addressId) {
                    echo "Applicant and address saved successfully!";
                } else {
                    echo "Failed to save address";
                }
            } else {
                echo "Failed to save applicant";
            }
        } catch (Exception $e) {
            echo "Database error: " . $e->getMessage();
        }
    }
}
?>

<!-- HTML Form -->
<form method="POST">
    <input type="text" name="ssnum" placeholder="SS Number" required>
    <input type="text" name="fname" placeholder="First Name" required>
    <input type="text" name="lname" placeholder="Last Name" required>
    <input type="email" name="email" placeholder="Email">
    <input type="text" name="city" placeholder="City" required>
    <input type="text" name="province" placeholder="Province" required>
    <input type="submit" value="Submit">
</form>
```

## Best Practices

### 1. Always Validate Input
```php
// Validate before using UniversalCRUD
function validateRequired($value) {
    return !empty(trim($value));
}

if (!validateRequired($ssnum)) {
    $errors[] = "SS Number is required";
}
```

### 2. Use Transactions for Related Operations
```php
try {
    $conn->beginTransaction();
    
    $applicantId = $applicantCrud->create($applicantData);
    $addressId = $addressCrud->create($addressData);
    
    $conn->commit();
    echo "Both records saved successfully";
} catch (Exception $e) {
    $conn->rollBack();
    echo "Transaction failed: " . $e->getMessage();
}
```

### 3. Handle Errors Gracefully
```php
$success = $crud->update($data, $conditions);

if (!$success) {
    error_log("Update failed for applicant ID: " . ($conditions['applicant_id'] ?? 'unknown'));
    echo "Failed to update record. Please try again.";
}
```

### 4. Sanitize Data
```php
// Always sanitize data before passing to CRUD methods
$fname = strtoupper(trim($_POST['fname'] ?? ''));
$lname = strtoupper(trim($_POST['lname'] ?? ''));
$email = strtolower(trim($_POST['email'] ?? ''));
```

## Troubleshooting

### Common Issues and Solutions

1. **"Class not found" error**
   - Make sure you've included the correct file: `require_once 'UniversalCRUD.php';`

2. **Database connection issues**
   - Check your database credentials in the UniversalCRUD class
   - Ensure the database exists and is accessible

3. **Empty results when expecting data**
   - Verify that your table name is correct
   - Check that your conditions match existing data

4. **Update/Delete not working**
   - Ensure your conditions match existing records
   - Check that the ID column name matches your database

## Security Features

The UniversalCRUD system includes several built-in security features:

- **Prepared Statements**: All database queries use prepared statements to prevent SQL injection
- **Parameter Binding**: Values are properly bound to prevent malicious input
- **Input Validation**: Always validate and sanitize input before using CRUD methods
- **Error Logging**: Errors are logged instead of displayed to users

## Migration from Direct SQL

If you're migrating from direct SQL queries to UniversalCRUD:

### Before (Direct SQL):
```php
$stmt = $pdo->prepare("INSERT INTO applicants (ssnum, lname, fname) VALUES (?, ?, ?)");
$stmt->execute([$ssnum, $lname, $fname]);
$newId = $pdo->lastInsertId();
```

### After (UniversalCRUD):
```php
$crud = new UniversalCRUD('applicants');
$data = [
    'ssnum' => $ssnum,
    'lname' => $lname,
    'fname' => $fname
];
$newId = $crud->create($data);
```

The UniversalCRUD approach is simpler, more secure, and more maintainable than direct SQL queries.
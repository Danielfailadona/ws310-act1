# How sample-use-of-usables Files Use usables Classes

## Overview

This document explains how different sample files demonstrate practical usage of the `Usables` utility class across multiple programming languages (Java, PHP, and JavaScript). Here's how each implementation uses the utilities, organized by function type:

## CRUD Functions

### Java Implementation

#### Database Methods Usage

The `Usables` class provides database access methods:

##### getValuesFromDatabase Method

**Method**: `getValuesFromDatabase(String column1, String column2, String tableName, String whereClause)`
- **Purpose**: Retrieves values from a database table based on specified columns and conditions
- **Parameters**:
  - `column1`: First column to retrieve
  - `column2`: Second column to retrieve
  - `tableName`: Table to query
  - `whereClause`: Optional WHERE clause condition

**Example Usage**:
```java
public class SomeClass {
    public final Usables use = new Usables();

    public void someMethod() {
        // Retrieve first and last names from users table where active = 1
        String[][] results = use.getValuesFromDatabase("fname", "lname", "users", "active = 1");

        for (String[] row : results) {
            System.out.println("First: " + row[0] + ", Last: " + row[1]);
        }
    }
}
```

##### getValueFromDatabase Method

**Method**: `getValueFromDatabase(String column, String tableName, String whereClause)`
- **Purpose**: Retrieves a single value from a database table
- **Parameters**:
  - `column`: Column to retrieve
  - `tableName`: Table to query
  - `whereClause`: WHERE clause condition

**Example Usage**:
```java
public class SomeClass {
    public final Usables use = new Usables();

    public void someMethod() {
        // Get the first name of a user with id = 123
        String firstName = use.getValueFromDatabase("fname", "users", "id = 123");
        System.out.println("First name: " + firstName);
    }
}
```

### PHP Implementation

#### Database Methods Usage

The `Usables` class provides database access methods:

##### getValuesFromDatabase Method

**Method**: `getValuesFromDatabase($column1, $column2, $tableName, $whereClause = '')`
- **Purpose**: Retrieves values from a database table based on specified columns and conditions
- **Parameters**:
  - `$column1`: First column to retrieve
  - `$column2`: Second column to retrieve
  - `$tableName`: Table to query
  - `$whereClause`: Optional WHERE clause condition

**Example Usage**:
```php
$usables = new Usables();
$pdo = new PDO(/* connection details */);
$usables->setDbConnection($pdo); // Set the connection once

// Retrieve first and last names from users table where active = 1
$results = $usables->getValuesFromDatabase("fname", "lname", "users", "active = 1");

foreach ($results as $row) {
    echo "First: " . $row[0] . ", Last: " . $row[1] . "\n";
}
```

##### getValueFromDatabase Method

**Method**: `getValueFromDatabase($column, $tableName, $whereClause)`
- **Purpose**: Retrieves a single value from a database table
- **Parameters**:
  - `$column`: Column to retrieve
  - `$tableName`: Table to query
  - `$whereClause`: WHERE clause condition

**Example Usage**:
```php
$usables = new Usables();
$pdo = new PDO(/* connection details */);
$usables->setDbConnection($pdo); // Set the connection once

// Get the first name of a user with id = 123
$firstName = $usables->getValueFromDatabase("fname", "users", "id = 123");
echo "First name: " . $firstName;
```

### JavaScript Implementation

#### Database Methods Usage

The `Usables` class also provides database access methods:

##### getValuesFromDatabase Method

**Method**: `getValuesFromDatabase(column1, column2, tableName, whereClause = '')`
- **Purpose**: Retrieves values from a database table based on specified columns and conditions (via API call)
- **Parameters**:
  - `column1`: First column to retrieve
  - `column2`: Second column to retrieve
  - `tableName`: Table to query
  - `whereClause`: Optional WHERE clause condition
- **Returns**: Promise that resolves to an array of results

**Example Usage**:
```javascript
const usables = new Usables();
usables.setApiEndpoint('/api/database'); // Set the API endpoint once

async function getUsers() {
    // Retrieve first and last names from users table where active = 1
    const results = await usables.getValuesFromDatabase("fname", "lname", "users", "active = 1");

    results.forEach(row => {
        console.log(`First: ${row[0]}, Last: ${row[1]}`);
    });
}

getUsers();
```

##### getValueFromDatabase Method

**Method**: `getValueFromDatabase(column, tableName, whereClause)`
- **Purpose**: Retrieves a single value from a database table (via API call)
- **Parameters**:
  - `column`: Column to retrieve
  - `tableName`: Table to query
  - `whereClause`: WHERE clause condition
- **Returns**: Promise that resolves to the value or null

**Example Usage**:
```javascript
const usables = new Usables();
usables.setApiEndpoint('/api/database'); // Set the API endpoint once

async function getUserFirstName() {
    // Get the first name of a user with id = 123
    const firstName = await usables.getValueFromDatabase("fname", "users", "id = 123");
    console.log("First name:", firstName);
}

getUserFirstName();
```

## Other Functions

### Java Implementation

#### Import Statement

The `Registration` class imports the `Usables` class:
```java
import config.Usables;
```

#### Instance Creation

An instance of the `Usables` class is created as a class member:
```java
public final Usables use = new Usables();
```

#### Usage in Constructor

The utility method is called in the constructor to set an image to a JLabel:
```java
public Registration() {
    initComponents();
    this.setResizable(false);
    use.setImageToLabel(Change_Pass, "src/image/Jeva.png");
}
```

#### Specific Method Used

The `Registration` class uses the `setImageToLabel` method from the `Usables` class:
- **Method**: `setImageToLabel(JLabel label, String imagePath)`
- **Purpose**: Resizes and sets an image to a JLabel component
- **Parameters**:
  - `label`: The JLabel component to set the image to (in this case, `Change_Pass`)
  - `imagePath`: The path to the image file (in this case, `"src/image/Jeva.png"`)

#### Practical Application

In the `Registration` class, the utilities are used to:
- Set a background image for the registration form
- Retrieve user data from the database when needed
- Validate user information against existing records

### PHP Implementation

#### Require Statement

The `Registration` class includes the `Usables` class:
```php
require_once 'usables.php';
```

#### Instance Creation

An instance of the `Usables` class is created as a class property:
```php
private $usables;

public function __construct() {
    $this->usables = new Usables();
    // ...
}
```

#### Usage in Constructor

The utility method is called to generate an image HTML tag:
```php
public function __construct() {
    $this->usables = new Usables();
    // Use the utility method to generate an image HTML tag
    $imageHtml = $this->usables->setImageToLabel("src/image/Jeva.png", 800, 600);
    echo $imageHtml;
}
```

#### Specific Methods Used

The `Registration` class uses multiple methods from the `Usables` class:
- **Method**: `setImageToLabel($imagePath, $width, $height)`
  - **Purpose**: Generates an HTML img tag with the specified image and dimensions
  - **Parameters**:
    - `$imagePath`: Path to the image file
    - `$width`: Desired width of the image
    - `$height`: Desired height of the image

- **Method**: `sanitizeString($input)`
  - **Purpose**: Sanitizes string input to prevent XSS attacks
  - **Parameters**:
    - `$input`: The string to sanitize

- **Method**: `isValidEmail($email)`
  - **Purpose**: Validates email format
  - **Parameters**:
    - `$email`: The email to validate

#### Practical Application

In the `Registration` class, the utilities are used for:
- Generating HTML image tags with proper sizing
- Sanitizing user input to prevent security vulnerabilities
- Validating email addresses before processing
- Creating thumbnails for uploaded images
- Retrieving user data from the database when needed
- Validating user information against existing records

### JavaScript Implementation

#### Class Instantiation

The `Registration` class creates an instance of the `Usables` class:
```javascript
class Registration {
    constructor() {
        this.usables = new Usables();
        this.init();
    }
    // ...
}
```

#### Usage in Initialization

The utility method is called during initialization to set an image to a DOM element:
```javascript
init() {
    // ...
    const imageElement = document.getElementById('change-pass');
    if (imageElement) {
        this.usables.setImageToLabel(imageElement, "src/image/Jeva.png", 800, 600);
    }
    // ...
}
```

#### Specific Methods Used

The `Registration` class uses multiple methods from the `Usables` class:
- **Method**: `setImageToLabel(element, imagePath, width, height)`
  - **Purpose**: Sets an image to a DOM element with specified dimensions
  - **Parameters**:
    - `element`: The DOM element to set the image to
    - `imagePath`: Path to the image file
    - `width`: Desired width of the image
    - `height`: Desired height of the image

- **Method**: `isValidEmail(email)`
  - **Purpose**: Validates email format
  - **Parameters**:
    - `email`: The email to validate

- **Method**: `sanitizeString(input)`
  - **Purpose**: Sanitizes string input to prevent XSS attacks
  - **Parameters**:
    - `input`: The string to sanitize

- **Method**: `showDialog(title, message, type)`
  - **Purpose**: Shows a modal dialog to the user
  - **Parameters**:
    - `title`: Title of the dialog
    - `message`: Message to display
    - `type`: Type of dialog (alert or confirm)

#### Practical Application

In the `Registration` class, the utilities are used for:
- Setting images to DOM elements with proper sizing
- Validating user input in real-time
- Sanitizing data before sending to the server
- Showing modal dialogs for user notifications
- Implementing debounced functions for performance
- Retrieving user data from the database via API calls
- Validating user information against existing records

## Benefits Demonstrated Across All Implementations

The usage across all implementations shows the benefits of the utility class approach:
1. **Code Reusability**: The same functionality is available across different parts of the application
2. **Consistency**: Ensures operations are handled the same way throughout the application
3. **Maintainability**: Changes to utility logic only need to be made in one place
4. **Error Handling**: Centralized error handling for common operations
5. **Simplicity**: Complex operations are abstracted away from the main business logic
6. **Security**: Centralized input sanitization and validation
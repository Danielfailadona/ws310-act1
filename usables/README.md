# Usables Directory

This directory contains reusable components for the WS310-ACT1 project:

## Files

### `usables.php`
Reusable PHP functions that can be used across the project:
- `getConnection($config = [])` - Establishes a database connection with configurable parameters
- `clean($value, $upper = false, $trim = true)` - Sanitizes and cleans input data
- `validateRequired($value)` - Validates if a value is required (not empty)
- `validateEmail($email)` - Validates email format
- `validatePhone($phone)` - Validates Philippine phone number format
- `validatePastDate($date)` - Validates date to ensure it's in the past
- `formatDateForDB($date)` - Formats a date string to MySQL DATE format
- `insertRecord($table, $data, $config = [])` - Generic insert function for any table
- `updateRecord($table, $data, $where, $config = [])` - Generic update function for any table
- `deleteRecord($table, $where, $config = [])` - Generic delete function for any table
- `selectRecords($table, $columns = [], $where = [], $orderBy = '', $orderDir = 'ASC', $limit = 0, $offset = 0, $config = [])` - Generic select function for any table
- `validateData($data, $rules)` - Generic validation function that takes an array of validation rules
- `processFormData($formData, $validationRules, $tableMappings, $operation = 'insert', $config = [])` - Processes form data with validation and database operations
- `getRecordById($table, $id, $idColumn = 'id', $config = [])` - Helper function to get a single record by ID
- `recordExists($table, $where, $config = [])` - Helper function to check if a record exists

### `js-usables.js`
Reusable JavaScript functions that can be used across the project:
- `getUrlParameter(name)` - Gets URL parameters
- `toggleSuccessMessage(elementId, show)` - Shows/hides success messages
- `applyAutoUppercase(selector)` - Applies auto-uppercase to text inputs
- `setupCivilStatusToggle(radioSelector, inputId)` - Handles civil status "Others" field toggle
- `setupSameAddressFunctionality(checkboxId, pbirthInputId, addressLayerId, addressInputIds, hiddenFieldId)` - Handles "Same as Home Address" functionality
- `setupAddChildFunctionality(addButtonId, containerId, childTemplate)` - Adds child functionality dynamically
- `populateFormFields(data, prefix)` - Populates form fields with data
- `populateNestedData(dataArray, containerId, templateFn)` - Populates complex nested data (like children)
- `handleFormUpdate(formActionUrl, redirectUrl)` - Handles form updates
- `loadApplicantData(id, apiUrl)` - Loads applicant data for editing
- `initializeEditMode(editParamName, submitButtonSelector, updateButtonSelector, apiUrl)` - Initializes edit mode
- `setupUpdateHandler(updateFunctionName, formActionUrl, redirectUrl)` - Sets up a generic update handler
- `initializeForm(options)` - Initializes common form behaviors with configurable options

## Usage

### PHP Usage
Include the file in your PHP scripts:
```php
require_once '../usables/usables.php';
```

Then use any of the available functions:
```php
// Example usage
$conn = getConnection();
$users = selectRecords('users', ['id', 'name', 'email'], ['status' => 'active'], 'name', 'ASC');
```

### JavaScript Usage
Include the script in your HTML files:
```html
<script src="../usables/js-usables.js"></script>
```

Then use any of the available functions:
```javascript
// Example usage
initializeForm({
    successMessageId: 'success-message',
    textInputSelector: 'input[type="text"]',
    // ... other options
});
```

## Benefits

1. **Code Reusability**: Functions can be used across multiple files and pages
2. **Maintainability**: Changes to common functionality only need to be made in one place
3. **Consistency**: Ensures consistent behavior across the application
4. **Reduced Duplication**: Eliminates the need to copy-paste common functions
5. **Configurable Options**: Many functions accept configuration parameters for flexibility
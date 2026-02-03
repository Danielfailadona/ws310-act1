# JavaScript Code Explanation for age-calculator.html

This guide explains each part of the JavaScript code in the age-calculator.html file for educational purposes.

## Complete JavaScript Code from age-calculator.html

```javascript
let applicantsData = [];

// Load data from server using UniversalCRUD function
async function loadApplicantsData() {
    try {
        // Using the UniversalCRUD function to get data
        applicantsData = await getData('applicants', ['applicant_id', 'fname', 'lname', 'dbirth'], {}, 'lname', 'ASC');
        
        // Calculate age for each applicant
        applicantsData.forEach(applicant => {
            applicant.age = calculateAgeFromDateString(applicant.dbirth);
        });
        
        renderApplicantsTable();
    } catch (error) {
        console.error('Error loading applicants data:', error);
        document.getElementById('applicants-table-body').innerHTML = '<tr><td colspan="5">Error loading data</td></tr>';
    }
}

// Function to calculate age from date string
function calculateAgeFromDateString(dateString) {
    if (!dateString) return null;

    const birthDate = new Date(dateString);
    const currentDate = new Date();

    if (birthDate > currentDate) return null; // Birth date is in the future

    let age = currentDate.getFullYear() - birthDate.getFullYear();
    const monthDiff = currentDate.getMonth() - birthDate.getMonth();

    if (monthDiff < 0 || (monthDiff === 0 && currentDate.getDate() < birthDate.getDate())) {
        age--;
    }

    return age;
}

const tableBody = document.getElementById('applicants-table-body');

// Render function using forEach
function renderApplicantsTable() {
    // Clear existing content
    tableBody.innerHTML = "";

    // Loop through each applicant in the array
    applicantsData.forEach((applicant) => {
        const ageDisplay = applicant.age !== null ? applicant.age + ' years' : 'N/A';
        const row = `
            <tr>
                <td class="id-col">${applicant.applicant_id || 'N/A'}</td>
                <td>${applicant.fname || 'N/A'}</td>
                <td>${applicant.lname || 'N/A'}</td>
                <td>${applicant.dbirth || 'N/A'}</td>
                <td class="age-highlight">${ageDisplay}</td>
            </tr>
        `;
        tableBody.innerHTML += row;
    });
}

// Initial call to display the data
loadApplicantsData();
```

## Line-by-Line Explanation

### Line 1: Variable Declaration
```javascript
let applicantsData = [];
```
- `let` - Declares a variable that can be reassigned
- `applicantsData` - Variable name to store the array of applicant records
- `= []` - Initializes as an empty array
- This variable will hold all the applicant data retrieved from the server

### Lines 3-16: Async Function Definition
```javascript
// Load data from server using UniversalCRUD function
async function loadApplicantsData() {
    try {
        // Using the UniversalCRUD function to get data
        applicantsData = await getData('applicants', ['applicant_id', 'fname', 'lname', 'dbirth'], {}, 'lname', 'ASC');
        
        // Calculate age for each applicant
        applicantsData.forEach(applicant => {
            applicant.age = calculateAgeFromDateString(applicant.dbirth);
        });
        
        renderApplicantsTable();
    } catch (error) {
        console.error('Error loading applicants data:', error);
        document.getElementById('applicants-table-body').innerHTML = '<tr><td colspan="5">Error loading data</td></tr>';
    }
}
```
- `async function` - Defines an asynchronous function that can use await
- `try` - Block that might throw an error
- `await getData()` - Waits for the UniversalCRUD function to return data
- Parameters for getData():
  - `'applicants'` - Table name to query
  - `['applicant_id', 'fname', 'lname', 'dbirth']` - Array of columns to retrieve
  - `{}` - Conditions for WHERE clause (empty = no conditions)
  - `'lname'` - Column to order by
  - `'ASC'` - Order direction (ascending)
- `forEach()` - Loops through each applicant to calculate age
- `catch (error)` - Handles any errors that occur in the try block
- `console.error()` - Logs error to browser console
- `document.getElementById()` - Gets HTML element by its ID
- `innerHTML` - Sets HTML content inside the element

### Lines 18-32: Age Calculation Function
```javascript
// Function to calculate age from date string
function calculateAgeFromDateString(dateString) {
    if (!dateString) return null;

    const birthDate = new Date(dateString);
    const currentDate = new Date();

    if (birthDate > currentDate) return null; // Birth date is in the future

    let age = currentDate.getFullYear() - birthDate.getFullYear();
    const monthDiff = currentDate.getMonth() - birthDate.getMonth();

    if (monthDiff < 0 || (monthDiff === 0 && currentDate.getDate() < birthDate.getDate())) {
        age--;
    }

    return age;
}
```
- `function` - Defines a reusable block of code
- `calculateAgeFromDateString(dateString)` - Function name with parameter
- `if (!dateString)` - Checks if dateString is falsy (null, undefined, empty string)
- `new Date()` - Creates a Date object from a date string
- `getFullYear()` - Gets the year from the date (e.g., 2023)
- `getMonth()` - Gets the month from the date (0-11)
- `getDate()` - Gets the day of the month from the date (1-31)
- The algorithm correctly calculates age by comparing years and adjusting for months/days

### Line 34: DOM Element Reference
```javascript
const tableBody = document.getElementById('applicants-table-body');
```
- `const` - Declares a constant variable (cannot be reassigned)
- `tableBody` - Variable name to store the table body element
- `document.getElementById()` - Gets an HTML element by its ID attribute
- `'applicants-table-body'` - The ID of the tbody element in the HTML
- This stores a reference to the table body for later use

### Lines 36-51: Render Function
```javascript
// Render function using forEach
function renderApplicantsTable() {
    // Clear existing content
    tableBody.innerHTML = "";

    // Loop through each applicant in the array
    applicantsData.forEach((applicant) => {
        const ageDisplay = applicant.age !== null ? applicant.age + ' years' : 'N/A';
        const row = `
            <tr>
                <td class="id-col">${applicant.applicant_id || 'N/A'}</td>
                <td>${applicant.fname || 'N/A'}</td>
                <td>${applicant.lname || 'N/A'}</td>
                <td>${applicant.dbirth || 'N/A'}</td>
                <td class="age-highlight">${ageDisplay}</td>
            </tr>
        `;
        tableBody.innerHTML += row;
    });
}
```
- `renderApplicantsTable()` - Function to display applicant data in the table
- `tableBody.innerHTML = ""` - Clears all existing content from the table
- `forEach((applicant) => {})` - Arrow function that loops through each applicant
- `const ageDisplay = applicant.age !== null ? applicant.age + ' years' : 'N/A'` - Ternary operator to show age or 'N/A'
- Template literal (backticks) - Allows embedding variables with `${variable}`
- `${applicant.applicant_id || 'N/A'}` - OR operator to show 'N/A' if value is null/undefined
- `tableBody.innerHTML += row` - Adds the new row HTML to the table body

### Lines 53-55: Initial Function Call
```javascript
// Initial call to display the data
loadApplicantsData();
```
- Calls the loadApplicantsData function when the page loads
- This starts the process of loading and displaying applicant data
- Without this call, the data wouldn't be loaded automatically

## Key JavaScript Concepts Used

### 1. Asynchronous Programming
- `async/await` - Modern syntax for handling promises
- `try/catch` - Error handling for asynchronous operations

### 2. DOM Manipulation
- `document.getElementById()` - Accessing HTML elements
- `innerHTML` - Setting HTML content
- `getElementById()` - Getting references to elements

### 3. Array Methods
- `forEach()` - Looping through array elements
- Array operations for data processing

### 4. String Interpolation
- Template literals with backticks and `${}`
- Embedding variables in strings

### 5. Conditional Logic
- Ternary operator (`condition ? value1 : value2`)
- OR operator (`||`) for fallback values
- If statements for validation

### 6. Date Operations
- `new Date()` - Creating date objects
- Date comparison and calculation methods

## Educational Takeaways

1. **Asynchronous Data Loading**: How to load data from a server without blocking the UI
2. **Error Handling**: Using try/catch to handle potential errors gracefully
3. **DOM Manipulation**: How to update HTML content with JavaScript
4. **Data Processing**: Calculating derived values (age) from raw data (birth date)
5. **Template Literals**: Using ES6 template literals for dynamic HTML generation
6. **Null Checking**: Using OR operator and ternary operator for safe data access
7. **Function Organization**: Breaking code into reusable functions for better maintainability

This code demonstrates a complete client-side data loading and display pattern that's commonly used in modern web applications.
# WS310-ACT1 Project Guide

## Detailed Syntax Explanations

### JavaScript Syntax

#### DOM Manipulation
```
Syntax: document.getElementById('success-message')
```
**document** = Global object representing the HTML document
**.getElementById()** = Method to find an element by its ID attribute
**'success-message'** = The ID value to search for
**document.getElementById('success-message')** = Returns the HTML element with id="success-message"

**Example**:
```javascript
let element = document.getElementById('myButton');
// This finds the HTML element with id="myButton" and assigns it to the variable element
```

===================================================================================================

```
Syntax: .style.display = 'block'
```
**.style** = Property that accesses the inline CSS styles of an element
**.display** = CSS property controlling how an element is displayed
**'block'** = CSS display value that makes element behave as a block-level element
**.style.display = 'block'** = Sets the display property to show the element

**Example**:
```javascript
document.getElementById('myDiv').style.display = 'block';
// This makes the div with id="myDiv" visible as a block element
```

===================================================================================================

```
Syntax: .style.display = 'none'
```
**Explanation**:
- `'none'` = CSS display value that hides the element completely
- `.style.display = 'none'` = Hides the element from view

**Example**:
```javascript
document.getElementById('myDiv').style.display = 'none';
// This hides the div with id="myDiv" completely
```

===================================================================================================

```
Syntax: element.disabled = false
```
**Explanation**:
- `element` = Reference to an HTML element
- `.disabled` = Property that controls if an input is disabled
- `false` = Boolean value meaning "not disabled"
- `element.disabled = false` = Enables the input element

**Example**:
```javascript
let inputField = document.getElementById('myInput');
inputField.disabled = false;
// This enables the input field, allowing user interaction
```

===================================================================================================

```
Syntax: element.disabled = true
```
**Explanation**:
- `true` = Boolean value meaning "is disabled"
- `element.disabled = true` = Disables the input element

**Example**:
```javascript
let inputField = document.getElementById('myInput');
inputField.disabled = true;
// This disables the input field, preventing user interaction
```

===================================================================================================

```
Syntax: element.value = ''
```
**Explanation**:
- `.value` = Property that gets or sets the value of an input field
- `''` = Empty string
- `element.value = ''` = Clears the value of the input field

**Example**:
```javascript
document.getElementById('myInput').value = '';
// This clears any text in the input field with id="myInput"
```

===================================================================================================

```
Syntax: element.focus()
```
**Explanation**:
- `.focus()` = Method that moves cursor to the element
- `element.focus()` = Sets focus to the element, typically for inputs

**Example**:
```javascript
document.getElementById('myInput').focus();
// This moves the cursor to the input field with id="myInput"
```

===================================================================================================

#### Event Handling
**Syntax**: `addEventListener('click', function() { })`

**Explanation**: 
- `addEventListener()` = Method to attach event handlers to elements
- `'click'` = Event type to listen for
- `function() { }` = Anonymous function to execute when event occurs
- `addEventListener('click', function() { })` = Runs code when element is clicked

**Example**:
```javascript
document.getElementById('myButton').addEventListener('click', function() {
    alert('Button clicked!');
});
// This shows an alert when the button with id="myButton" is clicked
```

===================================================================================================

**Syntax**: `addEventListener('change', function() { })`

**Explanation**: 
- `'change'` = Event type for when input value changes
- `addEventListener('change', function() { })` = Runs code when input value changes

**Example**:
```javascript
document.getElementById('mySelect').addEventListener('change', function() {
    console.log('Selection changed to: ' + this.value);
});
// This logs the new selection value when the dropdown changes
```

===================================================================================================

**Syntax**: `addEventListener('input', function() { })`

**Explanation**: 
- `'input'` = Event type for when user types in an input
- `addEventListener('input', function() { })` = Runs code as user types

**Example**:
```javascript
document.getElementById('myInput').addEventListener('input', function() {
    console.log('Current value: ' + this.value);
});
// This logs the current input value as the user types
```

===================================================================================================

**Syntax**: `addEventListener('change', function() { })`

**Explanation**: 
- `'change'` = Event type for when input value changes
- `addEventListener('change', function() { })` = Runs code when input value changes

**Example**:
```javascript
document.getElementById('mySelect').addEventListener('change', function() {
    console.log('Selection changed to: ' + this.value);
});
// This logs the new selection value when the dropdown changes
```

===================================================================================================

#### String Methods
**Syntax**: `.split(',')`

**Explanation**: 
- `.split()` = Method that splits a string into an array
- `','` = Delimiter character to split on
- `.split(',')` = Splits string at commas into array elements

**Example**:
```javascript
let text = "apple,banana,orange";
let fruits = text.split(',');
// fruits = ["apple", "banana", "orange"]
```

===================================================================================================

```
Syntax: .trim()
```
**Explanation**:
- `.trim()` = Method that removes whitespace from start and end of string
- `.trim()` = Returns string with leading/trailing spaces removed

**Example**:
```javascript
let text = "  hello world  ";
let trimmed = text.trim();
// trimmed = "hello world"
```

===================================================================================================

```
Syntax: .map()
```
**Explanation**:
- `.map()` = Method that transforms each element in an array
- `.map()` = Returns new array with transformed elements

**Example**:
```javascript
let numbers = [1, 2, 3];
let doubled = numbers.map(x => x * x);
// doubled = [1, 4, 9]
```

===================================================================================================

```
Syntax: .toUpperCase()
```
**Explanation**:
- `.toUpperCase()` = Method that converts string to uppercase
- `.toUpperCase()` = Returns string with all letters capitalized

**Example**:
```javascript
let text = "hello";
let upper = text.toUpperCase();
// upper = "HELLO"
```

===================================================================================================

#### Array Methods
```
Syntax: .forEach()
```
**Explanation**:
- `.forEach()` = Method that executes function for each array element
- `.forEach()` = Loops through each element in array

**Example**:
```javascript
let items = ['apple', 'banana'];
items.forEach(item => console.log(item));
// Logs: "apple" then "banana"
```

===================================================================================================

```
Syntax: .push()
```
**Explanation**:
- `.push()` = Method that adds element to end of array
- `.push()` = Adds item to array

**Example**:
```javascript
let arr = [1, 2];
arr.push(3);
// arr = [1, 2, 3]
```

===================================================================================================

#### Conditional Logic
```
Syntax: if (condition)
```
**Explanation**:
- `if` = Statement for conditional execution
- `condition` = Expression that evaluates to true or false
- `if (condition)` = Executes code if condition is true

**Example**:
```javascript
let age = 18;
if (age >= 18) {
    console.log("Adult");
}
// Logs "Adult" because age is 18
```

===================================================================================================

```
Syntax: else
```
**Explanation**:
- `else` = Statement for alternative execution
- `else` = Executes code if if-condition is false

**Example**:
```javascript
let age = 16;
if (age >= 18) {
    console.log("Adult");
} else {
    console.log("Minor");
}
// Logs "Minor" because age is less than 18
```

===================================================================================================

```
Syntax: ===
```
**Explanation**:
- `===` = Strict equality operator (compares value and type)
- `===` = Returns true if both sides are identical

**Example**:
```javascript
console.log(5 === 5); // true
console.log(5 === "5"); // false (different types)
```

===================================================================================================

```
Syntax: !==
```
**Explanation**:
- `!==` = Strict inequality operator
- `!==` = Returns true if both sides are different

**Example**:
```javascript
console.log(5 !== 3); // true
console.log(5 !== 5); // false
```

===================================================================================================

#### DOM Creation
```
Syntax: .createElement('div')
```
**Explanation**:
- `.createElement()` = Method to create new HTML element
- `'div'` = Type of element to create
- `.createElement('div')` = Creates a new div element

**Example**:
```javascript
let newDiv = document.createElement('div');
// Creates a new div element in memory
```

===================================================================================================

```
Syntax: .appendChild()
```
**Explanation**:
- `.appendChild()` = Method to add child element to parent
- `.appendChild()` = Adds element as last child of parent

**Example**:
```javascript
let parent = document.getElementById('container');
let child = document.createElement('p');
parent.appendChild(child);
// Adds the paragraph to the container div
```

===================================================================================================

```
Syntax: .setAttribute('attribute', 'value')
```
**Explanation**:
- `.setAttribute()` = Method to set an attribute on an element
- `'attribute'` = Name of the attribute to set
- `'value'` = Value to assign to the attribute
- `.setAttribute('attribute', 'value')` = Sets the specified attribute with the given value

**Example**:
```javascript
let element = document.createElement('div');
element.setAttribute('data-child-num', '1');
// Sets the data-child-num attribute to '1' on the element
```

===================================================================================================

```
Syntax: .getAttribute('attribute')
```
**Explanation**:
- `.getAttribute()` = Method to get the value of an attribute on an element
- `'attribute'` = Name of the attribute to retrieve
- `.getAttribute('attribute')` = Returns the value of the specified attribute

**Example**:
```javascript
let element = document.getElementById('myDiv');
let value = element.getAttribute('data-child-num');
// Gets the value of the data-child-num attribute
```

===================================================================================================

```
Syntax: .innerHTML = 'content'
```
**Explanation**:
- `.innerHTML` = Property that gets or sets HTML content inside element
- `= 'content'` = Assigns new HTML content
- `.innerHTML = 'content'` = Sets HTML content inside element

**Example**:
```javascript
document.getElementById('myDiv').innerHTML = '<p>Hello</p>';
// Puts a paragraph with "Hello" inside the div
```

===================================================================================================

```
Syntax: .outerHTML = 'content'
```
**Explanation**:
- `.outerHTML` = Property that gets or sets the element including its content
- `= 'content'` = Replaces the entire element with new content
- `.outerHTML = 'content'` = Replaces the element and its content

**Example**:
```javascript
document.getElementById('myDiv').outerHTML = '<span>New element</span>';
// Replaces the div with a span element
```

===================================================================================================

```
Syntax: .textContent = 'text'
```
**Explanation**:
- `.textContent` = Property that gets or sets plain text content inside element
- `= 'text'` = Assigns new text content
- `.textContent = 'text'` = Sets plain text content inside element (not HTML)

**Example**:
```javascript
document.getElementById('myDiv').textContent = 'Hello World';
// Sets plain text inside the div (HTML tags will be escaped)
```

===================================================================================================

#### Data Attributes
```
Syntax: element.dataset.attributeName
```
**Explanation**:
- `dataset` = Property that provides access to data-* attributes
- `element.dataset.attributeName` = Gets or sets the value of data-attribute-name
- Converts kebab-case to camelCase (data-child-num becomes dataset.childNum)

**Example**:
```javascript
let element = document.getElementById('myDiv');
element.dataset.childNum = '1';  // Sets data-child-num="1"
let value = element.dataset.childNum;  // Gets the value "1"
```

===================================================================================================

### Session Storage
```
Syntax: sessionStorage.setItem('key', 'value')
```
**Explanation**:
- `sessionStorage` = Built-in browser API for temporary storage
- `.setItem()` = Method to store key-value pair
- `'key'` = Identifier for the stored value
- `'value'` = Data to store
- `sessionStorage.setItem('key', 'value')` = Stores data in session storage

**Example**:
```javascript
sessionStorage.setItem('formSuccess', 'true');
// Stores the value 'true' with the key 'formSuccess'
```

===================================================================================================

```
Syntax: sessionStorage.getItem('key')
```
**Explanation**:
- `.getItem()` = Method to retrieve stored value
- `sessionStorage.getItem('key')` = Returns stored value for the key

**Example**:
```javascript
let success = sessionStorage.getItem('formSuccess');
// Gets the value stored with key 'formSuccess'
```

===================================================================================================

```
Syntax: sessionStorage.removeItem('key')
```
**Explanation**:
- `.removeItem()` = Method to delete stored value
- `sessionStorage.removeItem('key')` = Removes item from storage

**Example**:
```javascript
sessionStorage.removeItem('formSuccess');
// Removes the item with key 'formSuccess'
```

===================================================================================================

#### Date Operations
```
Syntax: new Date(value)
```
**Explanation**:
- `new Date()` = Constructor to create date object
- `new Date(value)` = Creates date object from value

**Example**:
```javascript
let today = new Date();
// Creates a date object for the current date/time
```

===================================================================================================

```
Syntax: Date.parse(dateString)
```
**Explanation**:
- `Date.parse()` = Method to parse date string into timestamp
- `Date.parse(dateString)` = Returns timestamp in milliseconds

**Example**:
```javascript
let timestamp = Date.parse('2023-01-01');
// Converts date string to milliseconds since Jan 1, 1970
```

===================================================================================================

```
Syntax: Date.now()
```
**Explanation**:
- `Date.now()` = Method that returns current timestamp
- `Date.now()` = Current time in milliseconds since epoch

**Example**:
```javascript
let currentTime = Date.now();
// Gets current time in milliseconds
```

===================================================================================================

#### Math Operations
```
Syntax: Math.floor(number)
```
**Explanation**:
- `Math.floor()` = Method that rounds down to nearest integer
- `Math.floor(number)` = Returns largest integer ≤ number

**Example**:
```javascript
let result = Math.floor(4.7);
// result = 4
```

===================================================================================================

```
Syntax: Math.ceil(number)
```
**Explanation**:
- `Math.ceil()` = Method that rounds up to nearest integer
- `Math.ceil(number)` = Returns smallest integer ≥ number

**Example**:
```javascript
let result = Math.ceil(4.2);
// result = 5
```

===================================================================================================

```
Syntax: Math.random()
```
**Explanation**:
- `Math.random()` = Method that returns random number between 0 and 1
- `Math.random()` = Returns random decimal

**Example**:
```javascript
let randomNum = Math.random();
// Could return 0.23456789123456789
```

===================================================================================================

#### Object Properties
```
Syntax: Object.keys(obj)
```
**Explanation**:
- `Object.keys()` = Method that returns array of object keys
- `Object.keys(obj)` = Gets all property names of object

**Example**:
```javascript
let person = {name: 'John', age: 30};
let keys = Object.keys(person);
// keys = ['name', 'age']
```

===================================================================================================

```
Syntax: Object.values(obj)
```
**Explanation**:
- `Object.values()` = Method that returns array of object values
- `Object.values(obj)` = Gets all property values of object

**Example**:
```javascript
let person = {name: 'John', age: 30};
let values = Object.values(person);
// values = ['John', 30]
```

===================================================================================================

## HTML Syntax Used (page1.html)

### Custom Data Attributes
**Syntax**:
```html
<!-- Custom data attributes for storing additional information -->
<div custom-attribute="value">Content</div>
<div fdprocessedid="no205y">Content</div>  <!-- Custom attribute for processing identification -->
<div data-custom-info="extra-data">Content</div>
```

**Explanation**:
- Custom attributes can be used to store additional information on HTML elements
- `fdprocessedid` or similar attributes might be added by external scripts or frameworks for identification
- `data-*` attributes are the standard way to store custom data in HTML5
- Access custom attributes in JavaScript using `element.getAttribute('attribute-name')`

**Example**:
```html
<div id="myDiv" custom-id="12345" fdprocessedid="no205y">Some content</div>
```

```javascript
// Access custom attributes
const element = document.getElementById('myDiv');
const customId = element.getAttribute('custom-id');  // Returns "12345"
const processedId = element.getAttribute('fdprocessedid');  // Returns "no205y"
```

===================================================================================================

### Form Structure
**Syntax**: 
```html
<form action="page1.php" method="post">
    <!-- Form fields here -->
</form>
```

**Explanation**: Creates a form that submits data to page1.php using POST method.
- `action="page1.php"` - Where to send form data
- `method="post"` - How to send (post = hidden, get = visible in URL)
- Put all input fields inside form tags

**Example**:
```html
<form action="process.php" method="post">
    <input type="text" name="username">
    <input type="submit" value="Submit">
</form>
```

===================================================================================================

### Input Types
**Syntax**: 
```html
<!-- Text Input -->
<input type="text" name="lname" placeholder="Last Name">

<!-- Email Input (validates email format) -->
<input type="email" name="email">

<!-- Date Input (provides date picker) -->
<input type="date" name="dbirth">

<!-- Number Input (only accepts numbers) -->
<input type="number" name="mearning" step="0.01">

<!-- Radio Buttons -->
<input type="radio" name="sex" value="Male"> Male
<input type="radio" name="sex" value="Female"> Female
```

**Explanation**: 
- `name="fieldname"` - Used to get value in PHP: `$_POST['fieldname']`
- `placeholder="text"` - Shows hint text inside input
- `type="email"` - Auto-validates email before submit
- `type="date"` - Shows calendar picker
- `type="number" step="0.01"` - Allows decimals (0.01, 0.02, etc.)
- Radio buttons with same `name` = only one can be selected

**Example**:
```html
<input type="text" name="firstName" placeholder="Enter your first name">
<input type="email" name="email" placeholder="your@email.com">
<input type="radio" name="gender" value="male"> Male
<input type="radio" name="gender" value="female"> Female
```

===================================================================================================

### Array Input Names (for multiple children)
**Syntax**:
```html
<input type="text" name="children[0][lname]">
<input type="text" name="children[1][lname]">
<input type="text" name="children[0][fname]">
<input type="text" name="children[0][mname]">
```

**Explanation**: Creates array structure in PHP: `$_POST['children'][0]['lname']`
- Use `name="arrayname[0][field]"` for first item
- Use `name="arrayname[1][field]"` for second item
- In PHP: Loop through `$_POST['arrayname']` to get all items
- Can create multidimensional arrays with multiple brackets `[index][field]`

**Example**:
```html
<input type="text" name="students[0][name]">
<input type="text" name="students[0][grade]">
<input type="text" name="students[1][name]">
<input type="text" name="students[1][grade]">
```

===================================================================================================

### Dynamic Form Elements
**Syntax**:
```html
<!-- Creating dynamic form elements with JavaScript -->
<div id="container">
    <!-- Elements added dynamically via JavaScript -->
</div>

<script>
// Create and append new form elements
const container = document.getElementById('container');
const newElement = document.createElement('input');
newElement.type = 'text';
newElement.name = `children[${index}][lname]`;
newElement.value = 'Initial value';
container.appendChild(newElement);
</script>
```

**Explanation**:
- Dynamically create form elements using JavaScript DOM methods
- Use template literals with backticks and ${variable} for dynamic naming
- Append elements to container using `appendChild()`
- Important for creating dynamic forms like adding multiple children

**Example**:
```javascript
let childIndex = 2;
const container = document.getElementById('children-container');
const newChildDiv = document.createElement('div');
newChildDiv.innerHTML = `
    <input type="text" name="children[${childIndex}][lname]" placeholder="Last Name">
    <input type="text" name="children[${childIndex}][fname]" placeholder="First Name">
`;
container.appendChild(newChildDiv);
```

===================================================================================================

### Disabled Attribute
**Syntax**: 
```html
<input type="text" name="cvstatus_other" disabled>
```

**Explanation**: Makes input uneditable until enabled via JavaScript.
- Add `disabled` to make input gray and unclickable
- Remove with JavaScript: `element.disabled = false`
- Disabled inputs don't submit with form

**Example**:
```html
<input type="text" name="special_field" disabled>
<!-- This field is grayed out and cannot be edited -->
```

===================================================================================================

### Hidden Input Fields
**Syntax**: 
```html
<input type="hidden" name="same_as_pbirth" id="same-as-pbirth-hidden" value="0">
```

**Explanation**: Stores data that is not visible to the user but gets submitted with the form.
- Use to store state information (like checkbox states)
- Value can be updated via JavaScript
- Automatically sent with form submission

**Example**:
```html
<input type="hidden" name="userId" value="123">
<input type="hidden" name="formToken" value="abc123">
```

===================================================================================================

### Checkbox and Label
**Syntax**:
```html
<input type="checkbox" id="same-address-checkbox">
<label for="same-address-checkbox">Same as Home Address</label>
```

**Explanation**: Creates a checkbox with associated label.
- `id` on checkbox matches `for` attribute on label
- Clicking label toggles the checkbox

**Example**:
```html
<input type="checkbox" id="accept-terms">
<label for="accept-terms">I accept the terms and conditions</label>
```

===================================================================================================

### Table Elements
**Syntax**:
```html
<!-- Basic table structure -->
<table>
    <thead>
        <tr>
            <th>Header 1</th>
            <th>Header 2</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Data 1</td>
            <td>Data 2</td>
        </tr>
    </tbody>
</table>
```

**Explanation**:
- `<table>` - Container for tabular data
- `<thead>` - Groups header content in table
- `<tbody>` - Groups body content in table
- `<tr>` - Table row
- `<th>` - Table header cell (bold by default)
- `<td>` - Table data cell

**Example**:
```html
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>John Doe</td>
            <td>john@example.com</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Jane Smith</td>
            <td>jane@example.com</td>
        </tr>
    </tbody>
</table>
```

===================================================================================================

## CSS Syntax Used (page1.css)

### CSS Variables
**Syntax**: 
```css
:root {
    --primary-blue: #2d68da;
    --bg-light-blue: #f7f9fc;
    --border-light: #e4e9f0;
    --text-grey: #4a5568;
    --input-border: #edf2f7;
}
```

**Explanation**: Defines custom CSS properties that can be reused throughout the stylesheet.
- `:root` - Selector for the highest-level element in the document
- `--variable-name` - Custom property name
- `var(--variable-name)` - Function to use the custom property

**Example**:
```css
:root {
    --main-color: #3498db;
}
.button {
    background-color: var(--main-color);
}
```

===================================================================================================

### Flexbox Layout
**Syntax**: 
```css
.name-cont {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
```

**Explanation**: Creates flexible row layout that wraps to next line when needed.
- `display: flex` - Makes children arrange in row
- `flex-wrap: wrap` - Move to next line if no space
- `gap: 10px` - Space between items
- `justify-content: center` - Center horizontally
- `align-items: center` - Center vertically

**Example**:
```css
.container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
}
```

===================================================================================================

### Box Sizing
**Syntax**: 
```css
* {
    box-sizing: border-box;
}
```

**Explanation**: Includes padding and border in element's total width/height.
- Put at top of CSS file
- `*` means apply to all elements
- Makes width calculations easier (width includes padding)

**Example**:
```css
.box {
    width: 100px;
    padding: 10px;
    border: 5px solid black;
    /* Total width remains 100px with border-box */
}
```

===================================================================================================

### Hover Effects
**Syntax**: 
```css
input[type="submit"]:hover {
    background-color: #1e4eb8;
}
```

**Explanation**: Changes style when mouse hovers over element.
- Add `:hover` after selector
- Works on any element
- Example: `.button:hover { color: red; }`

**Example**:
```css
.button:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}
```

===================================================================================================

### CSS Opacity and Pointer Events
**Syntax**: 
```css
.disabled-section {
    opacity: 0.5;              /* Makes element semi-transparent */
    pointer-events: none;      /* Disables clicking/interaction */
}
```

**Explanation**: 
- `opacity: 0.5` - 50% transparent (0 = invisible, 1 = solid)
- `pointer-events: none` - Can't click, type, or interact
- Use together to show "disabled" state

**Example**:
```css
.inactive {
    opacity: 0.3;
    pointer-events: none;
}
```

===================================================================================================

### Advanced CSS Selectors and Properties
**Syntax**:
```css
/* Attribute selectors */
input[name^="children"] { /* Selects inputs whose name starts with "children" */ }
input[name$="address"] { /* Selects inputs whose name ends with "address" */ }
input[name*="phone"] { /* Selects inputs whose name contains "phone" */ }

/* Pseudo-class selectors */
input:focus { /* Styles when input is focused */ }
input:disabled { /* Styles when input is disabled */ }
tr:nth-child(even) { /* Styles even rows */ }
tr:nth-child(odd) { /* Styles odd rows */ }

/* Advanced properties */
box-shadow: 0 2px 4px rgba(0,0,0,0.02); /* Subtle shadow effect */
background-color: rgba(0,0,0,0.4); /* Semi-transparent background */
z-index: 1; /* Layering control */
position: fixed; /* Fixed positioning */
```

**Explanation**:
- `name^="value"` - Attribute starts with value
- `name$="value"` - Attribute ends with value
- `name*="value"` - Attribute contains value
- `:focus` - Applied when element has focus
- `:disabled` - Applied when element is disabled
- `nth-child()` - Selects elements by position
- `rgba()` - Color with alpha transparency
- `box-shadow` - Adds shadow effects
- `z-index` - Controls stacking order

**Example**:
```css
/* Highlight focused inputs */
input:focus {
    border-color: #2d68da;
    outline: none;
    box-shadow: 0 0 0 2px rgba(45, 104, 218, 0.2);
}

/* Style alternate table rows */
tr:nth-child(even) {
    background-color: #f8f9fa;
}
```

===================================================================================================

### Positioning
**Syntax**:
```css
.check-box-inline {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0;
    border: none;
    background: transparent;
    justify-content: flex-start;
}
```

**Explanation**: Styles for inline checkbox and label containers.
- `display: flex` - Creates flexible layout
- `align-items: center` - Vertically centers items
- `justify-content: flex-start` - Aligns items to the left

**Example**:
```css
.checkbox-container {
    display: flex;
    align-items: center;
    gap: 8px;
}
```

===================================================================================================

## JavaScript Syntax Used (page1.html)

### Event Listeners
**Syntax**: 
```javascript
document.getElementById('add-child-btn').addEventListener('click', function() {
    // Code here
});
```

**Explanation**: Runs code when button is clicked.
- Get element: `document.getElementById('id')`
- Add listener: `.addEventListener('click', function() { })`
- Common events: 'click', 'change', 'submit', 'keyup'

**Example**:
```javascript
document.getElementById('myButton').addEventListener('click', function() {
    alert('Button was clicked!');
});
```

===================================================================================================

### DOM Manipulation
**Syntax**: 
```javascript
// Select element
const element = document.getElementById('myId');

// Select multiple elements
const radios = document.querySelectorAll('.cvstatus-radio');

// Create new element
const newDiv = document.createElement('div');

// Set HTML content
newDiv.innerHTML = `<p>Child ${count}</p>`;

// Add to page
container.appendChild(newDiv);
```

**Explanation**: 
- Select by ID: `getElementById('id')` - Gets one element
- Select by class: `querySelectorAll('.class')` - Gets all matching
- Create: `createElement('div')` - Makes new element
- Set content: `element.innerHTML = 'html'` - Adds HTML inside
- Add to page: `parent.appendChild(child)` - Inserts element

**Example**:
```javascript
let button = document.getElementById('deleteBtn');
let allInputs = document.querySelectorAll('input[type="text"]');
let newParagraph = document.createElement('p');
newParagraph.innerHTML = 'New paragraph';
document.body.appendChild(newParagraph);
```

===================================================================================================

### Advanced DOM Selection
**Syntax**:
```javascript
// Select single element by CSS selector
const element = document.querySelector('#myId');
const element = document.querySelector('.myClass');
const element = document.querySelector('input[name="myName"]');
const element = document.querySelector('div[data-child-num="1"]');

// Select multiple elements by various selectors
const elements = document.querySelectorAll('.myClass');
const inputs = document.querySelectorAll('input[type="text"]');
const namedInputs = document.querySelectorAll('input[name^="children"]');  // Inputs whose name starts with "children"
```

**Explanation**:
- `querySelector('selector')` - Gets the first element that matches the CSS selector
- `querySelectorAll('selector')` - Gets all elements that match the CSS selector
- Supports complex selectors like attribute selectors `[name="value"]`, pseudo-selectors `:first-child`, etc.
- More flexible than `getElementById` as it supports any CSS selector

**Example**:
```javascript
let firstTextInput = document.querySelector('input[type="text"]');
let allTextInputs = document.querySelectorAll('input[type="text"]');
let childrenInputs = document.querySelectorAll('input[name^="children"]');  // All inputs with names starting with "children"
```

===================================================================================================

### Enable/Disable Input
**Syntax**: 
```javascript
input.disabled = false;  // Enable
input.disabled = true;   // Disable
input.value = '';        // Clear value
input.focus();           // Focus on input
```

**Explanation**: 
- Get input: `const input = document.getElementById('myInput')`
- Enable: `input.disabled = false`
- Disable: `input.disabled = true`
- Clear: `input.value = ''`
- Focus: `input.focus()` - Cursor goes to input

**Example**:
```javascript
let myInput = document.getElementById('myInput');
myInput.disabled = true;  // Disable the input
myInput.value = '';       // Clear its value
```

===================================================================================================

### String Manipulation
**Syntax**: 
```javascript
const text = "Manila, Metro Manila, Philippines";
const parts = text.split(',');  // Split by comma
// parts = ["Manila", " Metro Manila", " Philippines"]

const cleaned = parts.map(part => part.trim());  // Remove spaces
// cleaned = ["Manila", "Metro Manila", "Philippines"]
```

**Explanation**: 
- `split(',')` - Splits string into array by comma
- `map()` - Applies function to each array item
- `trim()` - Removes spaces from start/end
- Access items: `parts[0]` = first, `parts[1]` = second

**Example**:
```javascript
let address = "New York, NY, USA";
let addressParts = address.split(',');
let cleanParts = addressParts.map(part => part.trim());
// cleanParts = ["New York", "NY", "USA"]
```

===================================================================================================

### Template Literals
**Syntax**: 
```javascript
const html = `
    <p>Child ${childCount + 1}</p>
    <input name="children[${childCount}][lname]">
`;
```

**Explanation**: Uses backticks for multi-line strings with variables.
- Use backticks ` instead of quotes
- Insert variables with `${variable}`
- Can span multiple lines
- Example: `const msg = `Hello ${name}, you are ${age} years old`;`

**Example**:
```javascript
let name = "John";
let greeting = `Hello ${name}! Welcome to our website.`;
// greeting = "Hello John! Welcome to our website."
```

===================================================================================================

### Fetch API & async/await
**Syntax**:
```javascript
async function loadData() {
        const response = await fetch('crud.php?action=read');
        const data = await response.json();
        return data;
}
```

**Explanation**:
- `fetch(url, options)` = Browser API to make HTTP requests.
- `async` / `await` = Modern syntax to write asynchronous code that looks synchronous.
- `response.json()` = Parses JSON response body and returns a Promise resolving to JavaScript object.

**Notes**:
- For `GET` requests you can simply pass the URL. For `POST`, pass an `options` object with `method` and `body`.
- Handle errors with `try { ... } catch (err) { ... }` around `await` calls.

**Example (GET)**:
```javascript
try {
    const res = await fetch('crud.php?action=read');
    const list = await res.json();
    console.log(list);
} catch (err) {
    console.error('Fetch failed', err);
}
```

===================================================================================================

### FormData and POST requests
**Syntax**:
```javascript
const formData = new FormData();
formData.append('first', 'Alice');
await fetch('crud.php?action=create', { method: 'POST', body: formData });
```

**Explanation**:
- `FormData` is a convenient way to send form fields (and files) in `multipart/form-data` format.
- When using `FormData`, do not manually set the `Content-Type` header; the browser sets the correct boundary.

**Example (update/create)**:
```javascript
const form = new FormData(document.querySelector('form'));
const res = await fetch('page1.php?action=update', { method: 'POST', body: form });
const result = await res.json();
```

===================================================================================================

### Form Element Methods
**Syntax**:
```javascript
const formElement = document.querySelector('form');
const formData = new FormData(formElement);
formElement.reset();  // Clears all form fields
formElement.checkValidity();  // Checks if all form fields meet validation requirements
```

**Explanation**:
- `FormData(formElement)` - Creates FormData object from all inputs in the form
- `formElement.reset()` - Clears all form fields to their initial values
- `formElement.checkValidity()` - Checks if all form fields meet validation requirements

**Example**:
```javascript
const form = document.getElementById('myForm');
const isValid = form.checkValidity();  // Returns true if all fields are valid
if (isValid) {
    const data = new FormData(form);
    // Submit the form data
}
```

===================================================================================================

### JSON Handling
**Syntax**:
```javascript
// Converting JavaScript objects to JSON string
const jsonString = JSON.stringify(object);

// Converting JSON string to JavaScript object
const object = JSON.parse(jsonString);

// Working with JSON in fetch responses
const response = await fetch('api/data');
const data = await response.json();  // Parse JSON from response
```

**Explanation**:
- `JSON.stringify()` - Converts JavaScript object/array to JSON string
- `JSON.parse()` - Converts JSON string to JavaScript object/array
- `response.json()` - Asynchronously parses JSON from fetch response

**Example**:
```javascript
const user = { name: 'John', age: 30 };
const jsonStr = JSON.stringify(user);  // '{"name":"John","age":30}'

const parsedUser = JSON.parse(jsonStr);  // { name: 'John', age: 30 }

// In fetch operations:
try {
    const response = await fetch('api/users');
    const users = await response.json();
    console.log(users);
} catch (error) {
    console.error('Error parsing JSON:', error);
}
```

===================================================================================================

### Local Storage
**Syntax**:
```javascript
// Storing data
localStorage.setItem('key', 'value');

// Retrieving data
const value = localStorage.getItem('key');

// Removing data
localStorage.removeItem('key');

// Clearing all data
localStorage.clear();
```

**Explanation**:
- `localStorage.setItem(key, value)` - Stores a key-value pair in browser storage
- `localStorage.getItem(key)` - Retrieves value by key from browser storage
- `localStorage.removeItem(key)` - Removes a key-value pair from storage
- `localStorage.clear()` - Removes all data from storage

**Example**:
```javascript
// Store user preferences
localStorage.setItem('theme', 'dark');
localStorage.setItem('language', 'en');

// Retrieve stored values
const theme = localStorage.getItem('theme');  // 'dark'
const language = localStorage.getItem('language');  // 'en'

// Remove specific item
localStorage.removeItem('theme');
```

===================================================================================================

### URLSearchParams
**Syntax**:
```javascript
const params = new URLSearchParams(window.location.search);
const editId = params.get('edit');
const allParams = params.getAll('paramName');  // Get all values for a parameter
const hasParam = params.has('paramName');      // Check if parameter exists
params.set('paramName', 'newValue');           // Set a parameter value
params.delete('paramName');                   // Remove a parameter
```

**Explanation**:
- `URLSearchParams` parses the query string portion of a URL and provides helpers like `get()`.
- `get('name')` - Gets the value of a URL parameter
- `getAll('name')` - Gets all values for a parameter (if multiple exist)
- `has('name')` - Checks if a parameter exists
- `set('name', 'value')` - Sets a parameter value
- `delete('name')` - Removes a parameter

**Example**:
```javascript
// page1.html uses this to detect edit mode
const urlParams = new URLSearchParams(window.location.search);
const editId = urlParams.get('edit');
if (editId) {
    console.log('Editing record with ID:', editId);
}
```

===================================================================================================

### Blob and file download (client-side)
**Syntax**:
```javascript
const blob = new Blob([csvText], { type: 'text/csv' });
const url = window.URL.createObjectURL(blob);
const a = document.createElement('a');
a.href = url; a.download = 'data.csv'; a.click();
window.URL.revokeObjectURL(url);
```

**Explanation**:
- `Blob` wraps binary/text data so the browser can treat it as a file-like object.
- `createObjectURL` makes a temporary URL for the blob; revoke it after use to free memory.

**Example**:
```javascript
// crud.html builds CSV and triggers a download using Blob + createObjectURL
```

===================================================================================================

### PHP: returning JSON responses
**Syntax**:
```php
header('Content-Type: application/json');
echo json_encode($result);
```

**Explanation**:
- `header('Content-Type: application/json')` tells the client the response is JSON.
- `json_encode()` converts PHP arrays/objects into JSON strings that `fetch(...).then(res => res.json())` can parse.

**Example**:
```php
$data = ['success' => true, 'message' => 'OK'];
header('Content-Type: application/json');
echo json_encode($data);
```

===================================================================================================

### Checkbox Properties
**Syntax**:
```javascript
const isChecked = checkbox.checked;
checkbox.checked = true;  // Check the checkbox
checkbox.checked = false; // Uncheck the checkbox
```

**Explanation**:
- `checkbox.checked` = Property that indicates if checkbox is checked (true/false)
- `checkbox.checked = true` = Programmatically checks the checkbox
- `checkbox.checked = false` = Programmatically unchecks the checkbox

**Example**:
```javascript
const myCheckbox = document.getElementById('myCheckbox');
if (myCheckbox.checked) {
    console.log('Checkbox is checked');
} else {
    myCheckbox.checked = true;  // Check it
}
```

===================================================================================================

### Conditional Logic
**Syntax**:
```javascript
if (sameAddressCheckbox.checked) {
    // Do something when checked
} else {
    // Do something when unchecked
}
```

**Explanation**: Executes different code based on condition.
- Check if checkbox is checked: `checkbox.checked`
- Compare values: `if (value === 'expected')`
- Multiple conditions: `if (condition1 && condition2)`

**Example**:
```javascript
if (document.getElementById('myCheckbox').checked) {
    console.log('Checkbox is checked');
} else {
    console.log('Checkbox is not checked');
}
```

===================================================================================================

### Promise and Async Error Handling
**Syntax**:
```javascript
// Using try-catch with async/await
async function fetchData() {
    try {
        const response = await fetch('api/data');
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching data:', error);
        throw error; // Re-throw if needed
    }
}

// Using Promise.catch() for error handling
fetch('api/data')
    .then(response => response.json())
    .then(data => console.log(data))
    .catch(error => console.error('Error:', error));
```

**Explanation**:
- `try` block - Contains code that might throw an error
- `catch` block - Handles errors thrown in the try block
- `async/await` - Use try-catch to handle asynchronous errors
- `Promise.catch()` - Alternative way to handle promise rejections

**Example**:
```javascript
async function loadUserData(userId) {
    try {
        const response = await fetch(`/api/users/${userId}`);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const userData = await response.json();
        return userData;
    } catch (error) {
        console.error('Failed to load user data:', error);
        return null;
    }
}
```

===================================================================================================

### Timeout Functions
**Syntax**:
```javascript
setTimeout(function() {
    document.getElementById('success-message').style.display = 'none';
}, 5000);
```

**Explanation**: Executes code after a specified delay.
- `setTimeout()` - Function to schedule delayed execution
- First parameter: function to execute
- Second parameter: delay in milliseconds
- 5000ms = 5 seconds

**Example**:
```javascript
setTimeout(function() {
    alert('5 seconds have passed!');
}, 5000);
```

===================================================================================================

### URL Parameters and Query Strings
**Syntax**:
```javascript
// Get URL parameters
const urlParams = new URLSearchParams(window.location.search);
const editId = urlParams.get('edit');

// Check if in edit mode
if (editId) {
    // Load data for editing
    loadApplicantData(editId);
}
```

**Explanation**:
- `URLSearchParams()` - Parses the query string portion of a URL
- `window.location.search` - Gets the query string (everything after ?)
- `.get('paramName')` - Gets the value of a URL parameter
- Used to detect if page is in edit mode based on URL parameters

**Example**:
```javascript
// For URL: page.html?edit=123
const params = new URLSearchParams(window.location.search);
const id = params.get('edit');  // Returns "123"
const action = params.get('action');  // Returns null if not present
```

===================================================================================================

### Date Calculations
**Syntax**:
```javascript
// Calculate age from birth date
function calculateAge(birthDateString) {
    if (!birthDateString) return null;

    const birthDate = new Date(birthDateString);
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

**Explanation**:
- `new Date()` - Creates a date object
- `getFullYear()` - Gets the 4-digit year
- `getMonth()` - Gets the month (0-11)
- `getDate()` - Gets the day of the month (1-31)
- Calculates age by comparing years and adjusting for months/days

**Example**:
```javascript
const age = calculateAge('1990-05-15');  // Calculates age from May 15, 1990
console.log(age);  // Outputs current age in years
```

===================================================================================================

### Array Filtering and Mapping
**Syntax**:
```javascript
// Filter array to get specific records
const filteredApplicants = applicantsData.filter(applicant => {
    return applicant.age >= 18;  // Only adults
});

// Map array to transform data
const applicantNames = applicantsData.map(applicant => {
    return applicant.fname + ' ' + applicant.lname;
});

// Combine filter and map
const adultNames = applicantsData
    .filter(applicant => applicant.age >= 18)
    .map(applicant => applicant.fname + ' ' + applicant.lname);
```

**Explanation**:
- `.filter()` - Creates new array with elements that pass test
- `.map()` - Creates new array with transformed elements
- Returns new arrays without modifying original
- Can be chained together for complex operations

**Example**:
```javascript
const numbers = [1, 2, 3, 4, 5];
const evenNumbers = numbers.filter(n => n % 2 === 0);  // [2, 4]
const squaredNumbers = numbers.map(n => n * n);  // [1, 4, 9, 16, 25]
```

===================================================================================================

### Conditional Logic with Ternary Operator
**Syntax**:
```javascript
// Ternary operator for simple conditions
const ageDisplay = applicant.age !== null ? applicant.age + ' years' : 'N/A';
const nameDisplay = applicant.fname && applicant.lname ?
    applicant.fname + ' ' + applicant.lname : 'Unknown';

// Nested ternary operators
const status = applicant.age < 18 ? 'Minor' :
    applicant.age < 65 ? 'Adult' : 'Senior';
```

**Explanation**:
- `condition ? valueIfTrue : valueIfFalse` - Ternary operator syntax
- `condition` - Expression that evaluates to true or false
- `valueIfTrue` - Value returned if condition is true
- `valueIfFalse` - Value returned if condition is false
- Shorthand for simple if-else statements

**Example**:
```javascript
const score = 85;
const grade = score >= 90 ? 'A' : score >= 80 ? 'B' : score >= 70 ? 'C' : 'F';
// grade = 'B' for score of 85
```

===================================================================================================

### Template Literals (String Interpolation)
**Syntax**:
```javascript
// Using template literals with variables
const html = `
    <tr>
        <td>${applicant.applicant_id || 'N/A'}</td>
        <td>${applicant.fname || 'N/A'}</td>
        <td>${applicant.lname || 'N/A'}</td>
        <td>${applicant.dbirth || 'N/A'}</td>
        <td class="age-highlight">${ageDisplay}</td>
    </tr>
`;

// Dynamic content in template literals
const message = `Hello ${name}, you are ${age} years old`;
const url = `api.php?action=read&table=${tableName}&id=${id}`;
```

**Explanation**:
- Backticks (`) - Define template literal
- `${expression}` - Embed expressions in string
- Allows multi-line strings
- Can embed variables and expressions
- More readable than string concatenation

**Example**:
```javascript
const name = 'John';
const age = 30;
const greeting = `Hello ${name}, you are ${age} years old`;
// greeting = "Hello John, you are 30 years old"
```

===================================================================================================

### Object Property Access
**Syntax**:
```javascript
// Dot notation
const firstName = applicant.fname;
const lastName = applicant.lname;

// Bracket notation
const firstName = applicant['fname'];
const dynamicProperty = applicant[dynamicPropertyName];

// Safe property access with OR operator
const name = applicant.fname || 'N/A';
const phone = applicant.cphone || applicant.phone || 'Not provided';

// Safe nested property access
const city = applicant.address ? applicant.address.city : 'N/A';
```

**Explanation**:
- `object.property` - Dot notation for known properties
- `object['property']` - Bracket notation for dynamic properties
- `||` operator - Provides fallback values
- Safe access prevents errors when properties don't exist

**Example**:
```javascript
const user = { name: 'John', age: 30 };
const name = user.name;  // 'John'
const fallback = user.email || 'No email';  // 'No email' (since email doesn't exist)
```

===================================================================================================

### Fetch API with Async/Await
**Syntax**:
```javascript
// Basic fetch with async/await
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

// POST request with fetch
async function postData(data) {
    try {
        const response = await fetch('api.php?action=create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        return await response.json();
    } catch (error) {
        console.error('Error posting data:', error);
        return { success: false, message: error.message };
    }
}
```

**Explanation**:
- `async function` - Defines an asynchronous function
- `await` - Pauses execution until promise resolves
- `fetch()` - Modern way to make HTTP requests
- `response.json()` - Parses JSON response
- `try/catch` - Handles errors in async functions
- Returns promises that can be awaited

**Example**:
```javascript
// Using the async function
const applicants = await loadData();
console.log(applicants);  // Will contain the loaded data
```

===================================================================================================

### FormData for Form Submissions
**Syntax**:
```javascript
// Create FormData from form element
const formElement = document.querySelector('form');
const formData = new FormData(formElement);

// Create FormData manually
const formData = new FormData();
formData.append('fieldName', 'fieldValue');
formData.append('anotherField', 'anotherValue');

// Submit form data with fetch
async function submitForm() {
    const form = document.getElementById('myForm');
    const formData = new FormData(form);

    try {
        const response = await fetch('process.php', {
            method: 'POST',
            body: formData
        });
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Error submitting form:', error);
        return { success: false, message: error.message };
    }
}
```

**Explanation**:
- `new FormData()` - Creates a FormData object
- `.append('name', 'value')` - Adds field to FormData
- `new FormData(formElement)` - Creates FormData from all inputs in form
- Automatically handles file uploads
- No need to manually set Content-Type header

**Example**:
```javascript
const form = document.forms['myForm'];
const data = new FormData(form);
// Contains all form field values
```

===================================================================================================

### String Methods
**Syntax**:
```javascript
// Convert to uppercase
const upper = text.toUpperCase();

// Convert to lowercase
const lower = text.toLowerCase();

// Trim whitespace
const trimmed = text.trim();

// Split string into array
const parts = text.split(',');

// Replace parts of string
const newText = text.replace('old', 'new');

// Check if string contains substring
const contains = text.includes('substring');

// Get part of string
const sub = text.substring(0, 5);  // First 5 characters
const slice = text.slice(-3);     // Last 3 characters
```

**Explanation**:
- `.toUpperCase()` - Converts string to uppercase
- `.toLowerCase()` - Converts string to lowercase
- `.trim()` - Removes whitespace from start/end
- `.split('delimiter')` - Splits string into array
- `.replace('old', 'new')` - Replaces first occurrence
- `.includes('substring')` - Checks if string contains substring
- `.substring(start, end)` - Gets part of string
- `.slice(start, end)` - Gets part of string (supports negative indices)

**Example**:
```javascript
const name = "  john doe  ";
const cleanName = name.trim().toUpperCase();  // "JOHN DOE"
const parts = "apple,banana,orange".split(',');  // ["apple", "banana", "orange"]
```

===================================================================================================

### Array Methods
**Syntax**:
```javascript
// Find element in array
const found = array.find(element => element.id === 123);

// Find index of element
const index = array.findIndex(element => element.name === 'target');

// Check if array contains element
const exists = array.some(element => element.active === true);

// Check if all elements meet condition
const allValid = array.every(element => element.valid === true);

// Reduce array to single value
const sum = numbers.reduce((total, num) => total + num, 0);

// Sort array
const sorted = array.sort((a, b) => a.name.localeCompare(b.name));
const sortedNum = numbers.sort((a, b) => a - b);
```

**Explanation**:
- `.find()` - Returns first element that matches condition
- `.findIndex()` - Returns index of first matching element
- `.some()` - Returns true if any element matches condition
- `.every()` - Returns true if all elements match condition
- `.reduce()` - Combines array elements into single value
- `.sort()` - Sorts array elements (with comparison function)

**Example**:
```javascript
const users = [{id: 1, name: 'John'}, {id: 2, name: 'Jane'}];
const user = users.find(u => u.id === 1);  // {id: 1, name: 'John'}
const hasActive = users.some(u => u.active);  // false (assuming no active property)
```

===================================================================================================

## PHP Syntax Used (page1.php)

### Database Connection (PDO)
```
Syntax: $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
```
**Explanation**:
- Set variables: `$host = 'localhost'; $dbname = 'mydb';`
- Create connection: `new PDO("mysql:host=$host;dbname=$dbname", $user, $pass)`
- Set error mode to show errors
- Wrap in try-catch to handle connection errors

**Example**:
```php
$connection = new PDO("mysql:host=localhost;dbname=mydb", "root", "");
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
```

===================================================================================================

```
Syntax: function validateRequired($value) {
    return !empty(trim($value));
}
```
**Explanation**:
- Define: `function functionName($param) { return $result; }`
- Call: `$result = functionName($value);`
- Can return true/false, strings, arrays, etc.

**Example**:
```php
function addNumbers($a, $b) {
    return $a + $b;
}
$result = addNumbers(5, 3);  // $result = 8
```

===================================================================================================

```
Syntax: $lname = trim($_POST['lname'] ?? '');
```

**Explanation**: If `$_POST['lname']` doesn't exist, use empty string.
- `$var = $value ?? 'default';`
- If `$value` exists, use it; otherwise use 'default'
- Prevents "undefined index" errors

**Example**:
```php
$username = $_POST['username'] ?? 'guest';
// If $_POST['username'] exists, use it; otherwise use 'guest'
```

===================================================================================================

```
Syntax: $result = $value ?? $anotherValue ?? 'default';
```

**Explanation**: Null coalescing operator chaining.
- Evaluates from left to right
- Returns the first operand that exists and is not null
- Useful for providing multiple fallback values

**Example**:
```php
$id = $_GET['id'] ?? $_POST['id'] ?? 'default_id';
// Uses GET id if available, then POST id, then defaults to 'default_id'
```

===================================================================================================

### Null Coalescing Assignment Operator
```
Syntax: $variable ??= 'default_value';
```

**Explanation**:
- `$variable ??=` - Null coalescing assignment operator
- `'default_value'` - Value to assign if variable is null
- `$variable ??= 'default_value'` - Assigns default value only if variable is null

**Example**:
```php
$options = [];
$options['timezone'] ??= 'UTC';  // Sets timezone to UTC if not already set
$options['language'] ??= 'en';   // Sets language to 'en' if not already set
```

===================================================================================================

### Superglobals
```
Syntax: $_POST['field_name']
```

**Explanation**:
- `$_POST` - Superglobal array containing data from POST requests
- `['field_name']` - Accesses value by field name
- `$_POST['field_name']` - Gets value from POST data with specified field name

**Example**:
```php
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
// Gets values from POST data, defaults to empty string if not set
```

===================================================================================================

### Ternary Operator
```
Syntax: $result = condition ? value_if_true : value_if_false;
```

**Explanation**:
- `condition` - Expression that evaluates to true or false
- `?` - Ternary operator symbol
- `value_if_true` - Value assigned if condition is true
- `:` - Separator between true and false values
- `value_if_false` - Value assigned if condition is false

**Example**:
```php
$status = $isActive ? 'Active' : 'Inactive';
// If $isActive is true, $status = 'Active', otherwise $status = 'Inactive'
```

===================================================================================================

### Array Functions
```
Syntax: $array = [];
```

**Explanation**:
- `$array` - Variable to hold array
- `[]` - Array constructor
- `$array = []` - Creates empty array

**Example**:
```php
$names = [];  // Empty array
$numbers = [1, 2, 3];  // Array with values
$assoc = ['name' => 'John', 'age' => 30];  // Associative array
```

===================================================================================================

```
Syntax: $value = $array['key'] ?? 'default';
```

**Explanation**:
- `$array['key']` - Gets value from associative array
- `?? 'default'` - Provides default value if key doesn't exist
- `$array['key'] ?? 'default'` - Gets value or default if key is missing

**Example**:
```php
$data = ['name' => 'John'];
$name = $data['name'] ?? 'Anonymous';  // 'John'
$age = $data['age'] ?? 0;             // 0 (default since age doesn't exist)
```

===================================================================================================

### Prepared Statements (SQL Injection Prevention)
```
Syntax: $sql = "INSERT INTO applicants (ssnum, lname) VALUES (:ssnum, :lname)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':ssnum', $ssnum);
$stmt->bindParam(':lname', $lname);
$stmt->execute();
```

**Explanation**:
- Write SQL with placeholders: `:fieldname`
- Prepare: `$stmt = $conn->prepare($sql);`
- Bind values: `$stmt->bindParam(':fieldname', $variable);`
- Execute: `$stmt->execute();`
- NEVER put variables directly in SQL string

**Example**:
```php
$query = "SELECT * FROM users WHERE id = :id AND status = :status";
$statement = $pdo->prepare($query);
$statement->bindParam(':id', $userId);
$statement->bindParam(':status', $userStatus);
$statement->execute();
```

===================================================================================================

```
Syntax: $applicantId = $conn->lastInsertId();
```

**Explanation**: Gets the ID of the last inserted record.
- Insert record first
- Immediately call `$conn->lastInsertId()`
- Use this ID to insert related records (like addresses, parents, etc.)

**Example**:
```php
$stmt = $pdo->prepare("INSERT INTO users (name) VALUES (?)");
$stmt->execute(['John']);
$newUserId = $pdo->lastInsertId();
// $newUserId contains the ID of the newly inserted user
```

===================================================================================================

### Array Handling
```
Syntax: if (isset($_POST['children']) && is_array($_POST['children'])) {
    foreach ($_POST['children'] as $child) {
        $lname = trim($child['lname'] ?? '');
    }
}
```

**Explanation**:
- Check if exists: `isset($_POST['children'])`
- Check if array: `is_array($_POST['children'])`
- Loop: `foreach ($array as $item) { }`
- Access nested: `$item['fieldname']`

**Example**:
```php
if (isset($_POST['products']) && is_array($_POST['products'])) {
    foreach ($_POST['products'] as $product) {
        $name = $product['name'] ?? '';
        $price = $product['price'] ?? 0;
    }
}
```

===================================================================================================

### Validation
```
Syntax: // Check if empty
if (!validateRequired($ssnum)) {
    $errors[] = "SS Number is required";
}

// Email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
}

// Input filtering and validation
$input = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
if ($input === false) {
    $errors[] = "Invalid email format";
}

// Regex validation
if (!preg_match('/^[0-9+\-\s()]+$/', $phone)) {
    $errors[] = "Invalid phone format";
}
```

**Explanation**:
- Empty check: `!empty(trim($value))` - Returns true if has content
- Email: `filter_var($email, FILTER_VALIDATE_EMAIL)` - Returns false if invalid
- Input filtering: `filter_input(INPUT_POST, 'field', FILTER_VALIDATE_EMAIL)` - Gets and validates input in one step
- Regex: `preg_match('/pattern/', $value)` - Returns true if matches pattern
- Store errors in array: `$errors[] = "message";`

**Example**:
```php
if (empty(trim($_POST['username']))) {
    $errors[] = "Username is required";
}
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
if ($email === false) {
    $errors[] = "Invalid email format";
}
```

===================================================================================================

### Error Handling
**Syntax**: 
```php
try {
    // Database operations
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
```

**Explanation**: 
- Wrap risky code in `try { }`
- Catch errors with `catch(ExceptionType $e) { }`
- Show error: `$e->getMessage()`
- Prevents script from crashing

**Example**:
```php
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([1]);
    $result = $stmt->fetch();
} catch(PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
```

===================================================================================================

### Exception Handling in PHP
**Syntax**:
```php
try {
    // Code that might throw an exception
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // More database operations
} catch(PDOException $e) {
    // Handle PDO exceptions
    echo "Database error: " . $e->getMessage();
} catch(Exception $e) {
    // Handle general exceptions
    echo "General error: " . $e->getMessage();
} finally {
    // Optional: code that always runs
    // Cleanup operations
}
```

**Explanation**:
- `try` block - Contains code that might throw an exception
- `catch` block - Handles specific types of exceptions
- `PDOException` - Specific exception type for database errors
- `finally` block - Code that runs regardless of whether an exception occurred
- `$e->getMessage()` - Gets the error message from the exception

**Example**:
```php
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([1]);
    $result = $stmt->fetch();
} catch(PDOException $e) {
    error_log("Database query failed: " . $e->getMessage());
    echo "Sorry, there was a database error.";
} catch(Exception $e) {
    error_log("General error: " . $e->getMessage());
    echo "Sorry, something went wrong.";
}
```

===================================================================================================

### JSON Functions
**Syntax**:
```php
// Convert PHP array/object to JSON string
$jsonString = json_encode($array);

// Convert JSON string to PHP array/object
$array = json_decode($jsonString, true);  // true = return associative array
```

**Explanation**:
- `json_encode()` - Converts PHP data to JSON string
- `json_decode()` - Converts JSON string to PHP data
- Second parameter `true` - Returns associative array instead of object
- Used for API responses and AJAX communication

**Example**:
```php
$data = ['success' => true, 'message' => 'OK', 'id' => 123];
$jsonResponse = json_encode($data);
// Returns: {"success":true,"message":"OK","id":123}

$decoded = json_decode($jsonResponse, true);
// Returns: ['success' => true, 'message' => 'OK', 'id' => 123]
```

===================================================================================================

### Header Functions
**Syntax**:
```php
// Set content type header
header('Content-Type: application/json');

// Redirect to another page
header('Location: success.php');

// Set cache control header
header('Cache-Control: no-cache, must-revalidate');
```

**Explanation**:
- `header()` - Sends raw HTTP header to browser
- `'Content-Type: application/json'` - Tells browser response is JSON
- `'Location: page.php'` - Redirects browser to different page
- Headers must be sent before any output

**Example**:
```php
// Send JSON response
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Created successfully']);

// Redirect after successful form submission
header('Location: thank-you.php');
exit; // Always exit after redirect
```

===================================================================================================

### String Functions
**Syntax**:
```php
// Remove whitespace from start and end
$trimmed = trim($string);

// Convert to uppercase
$upper = strtoupper($string);

// Convert to lowercase
$lower = strtolower($string);

// Get string length
$length = strlen($string);

// Replace part of string
$newString = str_replace('old', 'new', $originalString);
```

**Explanation**:
- `trim()` - Removes whitespace from beginning and end
- `strtoupper()` - Converts to uppercase
- `strtolower()` - Converts to lowercase
- `strlen()` - Gets number of characters in string
- `str_replace()` - Replaces occurrences of substring

**Example**:
```php
$text = "  Hello World  ";
$clean = trim($text);           // "Hello World"
$upper = strtoupper($clean);    // "HELLO WORLD"
$length = strlen($upper);       // 11
$modified = str_replace('WORLD', 'UNIVERSE', $upper);  // "HELLO UNIVERSE"
```

===================================================================================================

### Regular Expressions
**Syntax**:
```php
// Check if string matches pattern
$matches = preg_match('/pattern/', $string);

// Replace pattern with new text
$newString = preg_replace('/pattern/', 'replacement', $originalString);

// Split string by pattern
$parts = preg_split('/pattern/', $string);
```

**Explanation**:
- `preg_match()` - Returns 1 if pattern found, 0 if not
- `preg_replace()` - Replaces pattern with replacement text
- `preg_split()` - Splits string into array using pattern as delimiter
- Patterns are enclosed in delimiters (usually /)

**Example**:
```php
// Validate phone number format
if (preg_match('/^\d{3}-\d{3}-\d{4}$/', '123-456-7890')) {
    echo "Valid phone format";
}

// Replace all digits with X
$masked = preg_replace('/\d/', 'X', '123-456-7890');  // XXX-XXX-XXXX

// Split by comma
$parts = preg_split('/,/', 'apple,banana,orange');  // ['apple', 'banana', 'orange']
```

===================================================================================================

### Conditional Logic
**Syntax**:
```php
// Basic if statement
if ($condition) {
    // Code when condition is true
}

// If-else statement
if ($condition) {
    // Code when true
} else {
    // Code when false
}

// If-elseif-else statement
if ($condition1) {
    // Code for condition1
} elseif ($condition2) {
    // Code for condition2
} else {
    // Code for none of the above
}
```

**Explanation**:
- `if` - Executes code when condition is true
- `else` - Executes when if condition is false
- `elseif` - Additional condition to check
- Conditions can use operators: ==, ===, !=, !==, <, >, <=, >=

**Example**:
```php
$age = 25;
if ($age >= 18) {
    $status = "adult";
} elseif ($age >= 13) {
    $status = "teenager";
} else {
    $status = "child";
}
```

===================================================================================================

### Loop Structures
**Syntax**:
```php
// For loop
for ($i = 0; $i < 10; $i++) {
    // Code executed 10 times
}

// While loop
while ($condition) {
    // Code executed while condition is true
}

// Foreach loop (for arrays)
foreach ($array as $key => $value) {
    // Code executed for each array element
}

// Foreach without key
foreach ($array as $value) {
    // Code executed for each array element
}
```

**Explanation**:
- `for` - Loop with counter variable
- `while` - Loop while condition is true
- `foreach` - Loop through array elements
- `$key => $value` - Gets both key and value from array
- `$value` - Gets only value from array

**Example**:
```php
// Print numbers 0-9
for ($i = 0; $i < 10; $i++) {
    echo $i . "\n";
}

// Process array items
$names = ['John', 'Jane', 'Bob'];
foreach ($names as $name) {
    echo "Hello " . $name . "\n";
}

// Process associative array
$user = ['name' => 'John', 'age' => 30];
foreach ($user as $field => $value) {
    echo $field . ": " . $value . "\n";
}
```

===================================================================================================

### JavaScript Alert from PHP
**Syntax**:
```php
echo "<script>alert('Success!'); window.location.href='page1.html';</script>";
```

**Explanation**:
- Use `echo` to output JavaScript code
- `alert('message')` - Shows popup
- `window.location.href='file.html'` - Redirects to page
- `window.history.back()` - Goes back to previous page

**Example**:
```php
echo "<script>alert('Record saved successfully!');</script>";
echo "<script>window.location.href='dashboard.php';</script>";
```

===================================================================================================

### String Functions
**Syntax**: 
```php
trim($value)              // Remove whitespace
implode("\\n", $errors)   // Join array with newline
```

**Explanation**: 
- `trim($str)` - Removes spaces from start/end
- `implode("separator", $array)` - Joins array into string
- Example: `implode(", ", ['a','b','c'])` returns "a, b, c"

**Example**:
```php
$text = "  hello world  ";
$clean = trim($text);  // $clean = "hello world"
$list = implode(", ", ['apple', 'banana', 'orange']);  
// $list = "apple, banana, orange"
```

===================================================================================================

### Date Formatting
**Syntax**: 
```php
$formattedDate = date('Y-m-d', strtotime($dateString));
```

**Explanation**: Converts date string to MySQL DATE format (YYYY-MM-DD).
- `strtotime()` - Parses date string to timestamp
- `date('Y-m-d', timestamp)` - Formats timestamp to MySQL date format
- Essential when working with DATE type columns in database

**Example**:
```php
$userDate = "01/15/2023";
$mysqlDate = date('Y-m-d', strtotime($userDate));
// $mysqlDate = "2023-01-15"
```

===================================================================================================

### Transactions
**Syntax**: 
```php
$conn->beginTransaction();
// Multiple queries here
$conn->commit();  // Save all changes
// OR
$conn->rollBack(); // Undo all changes if error occurs
```

**Explanation**: Ensures all database operations succeed or fail together.
- Start transaction: `$conn->beginTransaction()`
- Execute multiple queries
- If all succeed: `$conn->commit()`
- If any fail: `$conn->rollBack()`

**Example**:
```php
try {
    $pdo->beginTransaction();
    $pdo->prepare("INSERT INTO orders ...")->execute([...]);
    $pdo->prepare("UPDATE inventory ...")->execute([...]);
    $pdo->commit();  // All changes saved
} catch(Exception $e) {
    $pdo->rollBack();  // All changes undone
    echo "Transaction failed";
}
```

===================================================================================================

## Project Flow

1. **User opens**: `http://localhost/ws310-act1/page1.html`
2. **User fills form** and clicks submit
3. **Browser sends** POST request to `page1.php`
4. **PHP validates** required fields and data types
5. **If errors**: JavaScript alert shows errors, user stays on page
6. **If valid**:
   - Insert into `applicants` table
   - Get applicant ID
   - Insert address into `applicant_addresses` table
   - Insert parents into `applicant_parents` table
   - Insert spouse into `applicant_spouse` table (if provided)
   - Insert children into `applicant_children` table
   - Insert employment into `applicant_employment` table
   - Insert certification into `applicant_certification` table
   - Show success alert
   - Redirect to page1.html

===================================================================================================

## Key Concepts

### Client-Side Validation (HTML5)
- `type="email"` - Browser checks email format
- `type="date"` - Browser provides date picker
- `type="number"` - Browser only allows numbers

### Server-Side Validation (PHP)
- Always validate on server (users can bypass client-side)
- Check required fields
- Validate formats (email, phone)
- Trim whitespace

### Security
- **PDO Prepared Statements**: Prevents SQL injection
- **trim()**: Removes extra whitespace
- **filter_var()**: Validates email format
- **Transaction handling**: Ensures data consistency

### Database Design
- **Normalized structure**: Separate tables for different entities
- **Foreign Keys**: Links related records
- **Proper data types**: DATE, DECIMAL, ENUM for better validation

===================================================================================================

## UniversalCRUD Class Syntax

### UniversalCRUD Class Initialization
**Syntax**:
```php
$crud = new UniversalCRUD('table_name');
```

**Explanation**:
- `new UniversalCRUD()` - Creates a new instance of the Universal CRUD class
- `'table_name'` - Specifies which database table to operate on
- `$crud` - Variable to hold the CRUD instance for operations
- Sets up database connection and table reference

**Example**:
```php
$applicantCrud = new UniversalCRUD('applicants');
$addressCrud = new UniversalCRUD('applicant_addresses');
```

===================================================================================================

### Create Operation (C)
**Syntax**:
```php
$id = $crud->create($data);
```

**Explanation**:
- `$crud->create()` - Method to insert a new record
- `$data` - Associative array of column => value pairs to insert
- Returns the ID of the newly created record
- Handles prepared statements and error handling internally

**Example**:
```php
$crud = new UniversalCRUD('applicants');
$data = [
    'ssnum' => '123-456-789',
    'lname' => 'DOE',
    'fname' => 'JOHN'
];
$newId = $crud->create($data);
// Returns the ID of the newly inserted applicant
```

===================================================================================================

### Read Operation (R)
**Syntax**:
```php
$records = $crud->read($conditions, $orderBy, $orderDirection, $limit, $offset);
```

**Explanation**:
- `$crud->read()` - Method to retrieve records from the table
- `$conditions` - Associative array of column => value pairs for WHERE clause (optional)
- `$orderBy` - Column name to order results by (optional)
- `$orderDirection` - Direction to order ('ASC' or 'DESC', optional)
- `$limit` - Maximum number of records to return (optional)
- `$offset` - Number of records to skip (for pagination, optional)
- Returns array of matching records

**Example**:
```php
$crud = new UniversalCRUD('applicants');
// Get all records ordered by last name
$allApplicants = $crud->read([], 'lname', 'ASC');
// Get only applicants with specific last name
$doeApplicants = $crud->read(['lname' => 'DOE'], 'fname', 'ASC');
// Get first 10 records
$limitedApplicants = $crud->read([], 'lname', 'ASC', 10, 0);
```

===================================================================================================

### Read Single Record
**Syntax**:
```php
$record = $crud->readOne($id, $idColumn);
```

**Explanation**:
- `$crud->readOne()` - Method to retrieve a single record by ID
- `$id` - The ID value to search for
- `$idColumn` - Name of the ID column (defaults to 'id')
- Returns single record as associative array or null if not found

**Example**:
```php
$crud = new UniversalCRUD('applicants');
$applicant = $crud->readOne(123, 'applicant_id');
if ($applicant) {
    echo "Found: " . $applicant['fname'] . " " . $applicant['lname'];
}
```

===================================================================================================

### Update Operation (U)
**Syntax**:
```php
$success = $crud->update($data, $conditions);
```

**Explanation**:
- `$crud->update()` - Method to modify existing records
- `$data` - Associative array of column => new_value pairs to update
- `$conditions` - Associative array of column => value pairs for WHERE clause
- Returns true on success, false on failure
- Updates only records that match the conditions

**Example**:
```php
$crud = new UniversalCRUD('applicants');
$updateData = [
    'fname' => 'JANE',
    'email' => 'jane.doe@example.com'
];
$conditions = ['applicant_id' => 123];
$success = $crud->update($updateData, $conditions);
// Updates the applicant with ID 123
```

===================================================================================================

### Delete Operation (D)
**Syntax**:
```php
$success = $crud->delete($conditions);
```

**Explanation**:
- `$crud->delete()` - Method to remove records from the table
- `$conditions` - Associative array of column => value pairs for WHERE clause
- Returns true on success, false on failure
- Deletes all records that match the conditions

**Example**:
```php
$crud = new UniversalCRUD('applicants');
$conditions = ['applicant_id' => 123];
$success = $crud->delete($conditions);
// Deletes the applicant with ID 123

// Delete multiple records matching conditions
$success = $crud->delete(['lname' => 'SMITH']);
// Deletes all applicants with last name 'SMITH'
```

===================================================================================================

### Search Operation
**Syntax**:
```php
$results = $crud->search($searchFields, $matchType);
```

**Explanation**:
- `$crud->search()` - Method to find records using LIKE operator
- `$searchFields` - Associative array of column => search_value pairs
- `$matchType` - Type of match ('any' for OR, 'all' for AND)
- Returns array of matching records
- Uses %wildcards% for partial matching

**Example**:
```php
$crud = new UniversalCRUD('applicants');
// Find applicants with 'DOE' in last name (OR match)
$results = $crud->search(['lname' => 'DOE'], 'any');
// Find applicants with 'JOHN' in first name AND 'DOE' in last name
$results = $crud->search(['fname' => 'JOHN', 'lname' => 'DOE'], 'all');
```

===================================================================================================

### Count Records
**Syntax**:
```php
$count = $crud->count($conditions);
```

**Explanation**:
- `$crud->count()` - Method to count records matching conditions
- `$conditions` - Associative array of column => value pairs for WHERE clause (optional)
- Returns integer count of matching records
- Useful for pagination and statistics

**Example**:
```php
$crud = new UniversalCRUD('applicants');
// Count all applicants
$total = $crud->count();
// Count applicants with specific last name
$doeCount = $crud->count(['lname' => 'DOE']);
// Count married applicants
$marriedCount = $crud->count(['cvstatus' => 'Married']);
```

===================================================================================================

### UniversalCRUD Benefits
**Advantages**:
- **Simplified Syntax**: Single methods for all CRUD operations
- **Security**: Built-in prepared statements prevent SQL injection
- **Consistency**: Same interface for all database tables
- **Error Handling**: Automatic error handling and logging
- **Flexibility**: Support for conditions, ordering, limits, and pagination
- **Maintainability**: Centralized database operations in one class

**Comparison**:
```php
// Traditional approach (complex)
$conn = new PDO($dsn, $user, $pass);
$stmt = $conn->prepare("INSERT INTO applicants (ssnum, lname, fname) VALUES (?, ?, ?)");
$stmt->execute([$ssnum, $lname, $fname]);
$newId = $conn->lastInsertId();

// UniversalCRUD approach (simple)
$crud = new UniversalCRUD('applicants');
$newId = $crud->create(['ssnum' => $ssnum, 'lname' => $lname, 'fname' => $fname]);
```

===================================================================================================
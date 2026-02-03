<?php
// Include the helper functions
require_once 'helpers.php';

echo "<h1>Universal CRUD System - Complete Example</h1>";

// Example 1: Create a new applicant
echo "<h2>Example 1: Creating a new applicant</h2>";
$newApplicantId = createItem('applicants', [
    'ssnum' => '123-456-789',
    'lname' => 'DOE',
    'fname' => 'JOHN'
]);

if ($newApplicantId) {
    echo "Applicant created successfully with ID: $newApplicantId<br>";
} else {
    echo "Failed to create applicant<br>";
}

// Example 2: Create corresponding address
echo "<h2>Example 2: Creating address for the applicant</h2>";
$newAddressId = createItem('applicant_addresses', [
    'applicant_id' => $newApplicantId,
    'address_6' => 'MANILA',
    'address_7' => 'NCR'
]);

if ($newAddressId) {
    echo "Address created successfully<br>";
} else {
    echo "Failed to create address<br>";
}

// Example 3: Get specific data (using getData function)
echo "<h2>Example 3: Getting specific data</h2>";
$applicantData = getData('applicants', ['fname', 'lname', 'ssnum'], ['applicant_id' => $newApplicantId]);

if (!empty($applicantData)) {
    foreach ($applicantData as $applicant) {
        echo "Name: {$applicant['fname']} {$applicant['lname']}, SS: {$applicant['ssnum']}<br>";
    }
} else {
    echo "No applicant found<br>";
}

// Example 4: Get single item by ID
echo "<h2>Example 4: Getting single item by ID</h2>";
$singleApplicant = getItemById('applicants', $newApplicantId, 'applicant_id');
if ($singleApplicant) {
    echo "Found: {$singleApplicant['fname']} {$singleApplicant['lname']}<br>";
} else {
    echo "Applicant not found<br>";
}

// Example 5: Update an item
echo "<h2>Example 5: Updating applicant</h2>";
$updated = updateItem('applicants', 
    ['fname' => 'JANE'], 
    ['applicant_id' => $newApplicantId]
);

if ($updated) {
    echo "Applicant updated successfully<br>";
} else {
    echo "Failed to update applicant<br>";
}

// Example 6: Search for records
echo "<h2>Example 6: Searching for records</h2>";
$searchResults = searchItems('applicants', ['lname' => 'DOE']);
echo "Found " . count($searchResults) . " applicant(s) with 'DOE' in last name<br>";

// Example 7: Count records
echo "<h2>Example 7: Counting records</h2>";
$totalApplicants = countItems('applicants');
echo "Total applicants in database: $totalApplicants<br>";

// Example 8: Delete the created records
echo "<h2>Example 8: Deleting created records</h2>";

// First delete the address records for this applicant
$addressDeleted = deleteItem('applicant_addresses', ['applicant_id' => $newApplicantId]);
if ($addressDeleted) {
    echo "Address records deleted successfully<br>";
} else {
    echo "Failed to delete address records<br>";
}

// Then delete the applicant
$applicantDeleted = deleteItemById('applicants', $newApplicantId, 'applicant_id');
if ($applicantDeleted) {
    echo "Applicant record deleted successfully<br>";
} else {
    echo "Failed to delete applicant record<br>";
}

echo "<h2>Universal CRUD System Demo Complete!</h2>";

echo "<h3>Summary of Available Helper Functions:</h3>";
echo "<ul>";
echo "<li><strong>createItem(table, data)</strong> - Create a new record</li>";
echo "<li><strong>getItemById(table, id, idColumn)</strong> - Get a single record by ID</li>";
echo "<li><strong>getData(table, columns, conditions)</strong> - Get specific columns with optional conditions</li>";
echo "<li><strong>updateItem(table, data, conditions)</strong> - Update records</li>";
echo "<li><strong>deleteItem(table, id, idColumn)</strong> - Delete a record by ID</li>";
echo "<li><strong>countItems(table, conditions)</strong> - Count records with optional conditions</li>";
echo "<li><strong>searchItems(table, searchFields, matchType)</strong> - Search records</li>";
echo "</ul>";

echo "<h3>How to Use These Functions:</h3>";
echo "<pre>";
echo "// Delete an item\n";
echo "\$deleted = deleteItem('applicants', 123, 'applicant_id');\n\n";

echo "// Get specific data\n";
echo "\$applicants = getData('applicants', ['fname', 'lname', 'ssnum'], ['lname' => 'DOE']);\n\n";

echo "// Create a new item\n";
echo "\$newId = createItem('applicants', [\n";
echo "    'ssnum' => '987-654-321',\n";
echo "    'lname' => 'SMITH',\n";
echo "    'fname' => 'JANE'\n";
echo "]);\n\n";

echo "// Update an item\n";
echo "\$updated = updateItem('applicants', \n";
echo "    ['fname' => 'JANET'], \n";
echo "    ['applicant_id' => \$newId]\n";
echo ");\n";
echo "</pre>";
?>
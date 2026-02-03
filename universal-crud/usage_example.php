<?php
// Include the Universal CRUD class
require_once 'UniversalCRUD.php';

// Example 1: Create a new applicant record
echo "<h2>Example 1: Creating a new applicant</h2>";

// Create CRUD instance for the 'applicants' table
$applicantCRUD = new UniversalCRUD('applicants');

// Data to insert
$applicantData = [
    'ssnum' => '123-456-789',
    'lname' => 'DOE',
    'fname' => 'JOHN'
];

// Create the record
$newApplicantId = $applicantCRUD->create($applicantData);

if ($newApplicantId) {
    echo "Applicant created successfully with ID: $newApplicantId<br>";
} else {
    echo "Failed to create applicant<br>";
}

// Example 2: Create corresponding address record
echo "<h2>Example 2: Creating address for the applicant</h2>";

// Create CRUD instance for the 'applicant_addresses' table
$addressCRUD = new UniversalCRUD('applicant_addresses');

// Address data to insert (using the applicant ID we just created)
$addressData = [
    'applicant_id' => $newApplicantId,
    'address_6' => 'MANILA',
    'address_7' => 'NCR'
];

// Create the address record
$newAddressId = $addressCRUD->create($addressData);

if ($newAddressId) {
    echo "Address created successfully with ID: $newAddressId<br>";
} else {
    echo "Failed to create address<br>";
}

// Example 3: Reading all applicants
echo "<h2>Example 3: Reading all applicants</h2>";

// Read all applicants
$applicants = $applicantCRUD->read();

echo "<table border='1'>";
echo "<tr><th>ID</th><th>SS Number</th><th>First Name</th><th>Last Name</th></tr>";
foreach ($applicants as $applicant) {
    echo "<tr>";
    echo "<td>{$applicant['applicant_id']}</td>";
    echo "<td>{$applicant['ssnum']}</td>";
    echo "<td>{$applicant['fname']}</td>";
    echo "<td>{$applicant['lname']}</td>";
    echo "</tr>";
}
echo "</table>";

// Example 4: Reading with conditions
echo "<h2>Example 4: Reading applicant with specific ID</h2>";

// Read specific applicant
$specificApplicant = $applicantCRUD->readOne($newApplicantId, 'applicant_id');

if ($specificApplicant) {
    echo "Found applicant: {$specificApplicant['fname']} {$specificApplicant['lname']}<br>";
} else {
    echo "Applicant not found<br>";
}

// Example 5: Updating a record
echo "<h2>Example 5: Updating applicant's first name</h2>";

// Update the applicant's first name
$updateData = [
    'fname' => 'JANE'
];

$conditions = [
    'applicant_id' => $newApplicantId
];

$updated = $applicantCRUD->update($updateData, $conditions);

if ($updated) {
    echo "Applicant updated successfully<br>";
} else {
    echo "Failed to update applicant<br>";
}

// Example 6: Searching records
echo "<h2>Example 6: Searching for applicants with 'DOE' in last name</h2>";

$searchResults = $applicantCRUD->search(['lname' => 'DOE']);

echo "<table border='1'>";
echo "<tr><th>ID</th><th>SS Number</th><th>First Name</th><th>Last Name</th></tr>";
foreach ($searchResults as $applicant) {
    echo "<tr>";
    echo "<td>{$applicant['applicant_id']}</td>";
    echo "<td>{$applicant['ssnum']}</td>";
    echo "<td>{$applicant['fname']}</td>";
    echo "<td>{$applicant['lname']}</td>";
    echo "</tr>";
}
echo "</table>";

// Example 7: Count records
echo "<h2>Example 7: Counting total applicants</h2>";

$totalApplicants = $applicantCRUD->count();
echo "Total applicants: $totalApplicants<br>";

// Example 8: Delete a record
echo "<h2>Example 8: Deleting the created records</h2>";

// First delete the address record
$addressDeleted = deleteItem('applicant_addresses', ['applicant_id' => $newApplicantId]);

if ($addressDeleted) {
    echo "Address record deleted successfully<br>";
} else {
    echo "Failed to delete address record<br>";
}

// Then delete the applicant record
$applicantDeleted = deleteItemById('applicants', $newApplicantId, 'applicant_id');

if ($applicantDeleted) {
    echo "Applicant record deleted successfully<br>";
} else {
    echo "Failed to delete applicant record<br>";
}

echo "<h2>Universal CRUD System Demonstration Complete!</h2>";
?>
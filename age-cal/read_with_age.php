<?php
// Include the Universal CRUD class
require_once '../universal-crud/UniversalCRUD.php';

//==============================

// AGE CALCULATION FUNCTION
function calculateAge($dob) {
    if (!$dob) return null;

    $birthDate = new DateTime($dob);
    $currentDate = new DateTime();

    if ($birthDate > $currentDate) {
        return null; // Birth date is in the future
    }

    $interval = $currentDate->diff($birthDate);
    return $interval->y; // Return only years
}

//==============================

// DATA RETRIEVAL FUNCTION
function retrieveApplicantsWithAges() {
    try {
        // Create CRUD instance for applicants table
        $crud = new UniversalCRUD('applicants');

        // Get all applicants with their names and birth dates
        $applicants = $crud->read([], 'lname', 'ASC'); // Read all records ordered by last name

        // Add calculated age to each applicant
        foreach ($applicants as &$applicant) {
            $applicant['age'] = calculateAge($applicant['dbirth']);
        }

        return $applicants;
    } catch(Exception $e) {
        error_log("Error retrieving applicants with ages: " . $e->getMessage());
        return [];
    }
}

//==============================

// MAIN EXECUTION
$applicants = retrieveApplicantsWithAges();

// Set response header to JSON format
header('Content-Type: application/json');
// Output the applicants data as JSON
echo json_encode($applicants);
?>
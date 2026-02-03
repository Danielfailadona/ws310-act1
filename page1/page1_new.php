<?php
// Include the UniversalCRUD class
require_once '../universal-crud/UniversalCRUD.php';

// Validation function
function validateRequired($value) {
    return !empty(trim($value));
}

// Determine action based on the action parameter
$action = $_GET['action'] ?? 'insert';

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && $action === 'update') {
    // Handle update operation
    $errors = [];

    // Get applicant ID from the form
    $applicantId = $_POST['applicant_id'] ?? '';

    if (empty($applicantId)) {
        $errors[] = "Applicant ID is required for update";
    }

    if (empty($errors)) {
        try {
            // Create CRUD instances for different tables
            $applicantCrud = new UniversalCRUD('applicants');
            $addressCrud = new UniversalCRUD('applicant_addresses');
            $parentsCrud = new UniversalCRUD('applicant_parents');
            $spouseCrud = new UniversalCRUD('applicant_spouse');
            $employmentCrud = new UniversalCRUD('applicant_employment');

            // Prepare applicant data
            $applicantData = [
                'ssnum' => trim($_POST['ssnum'] ?? ''),
                'lname' => trim($_POST['lname'] ?? ''),
                'fname' => trim($_POST['fname'] ?? ''),
                'mname' => trim($_POST['mname'] ?? ''),
                'sfx' => trim($_POST['sfx'] ?? ''),
                'dbirth' => !empty($_POST['dbirth']) ? date('Y-m-d', strtotime($_POST['dbirth'])) : null,
                'sex' => trim($_POST['sex'] ?? ''),
                'cvstatus' => trim($_POST['cvstatus'] ?? ''),
                'cvstatus_other' => trim($_POST['cvstatus_other'] ?? ''),
                'taxid' => trim($_POST['taxid'] ?? ''),
                'nation' => trim($_POST['nation'] ?? ''),
                'religion' => trim($_POST['religion'] ?? ''),
                'pbirth' => trim($_POST['pbirth'] ?? ''),
                'cphone' => trim($_POST['cphone'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'tphone' => trim($_POST['tphone'] ?? ''),
                'printed_name' => trim($_POST['printed-name'] ?? ''),
                'cert_date' => !empty($_POST['cert-date']) ? date('Y-m-d', strtotime($_POST['cert-date'])) : null
            ];

            // Update applicant data
            $applicantSuccess = $applicantCrud->update($applicantData, ['applicant_id' => $applicantId]);

            // Prepare address data
            $addressData = [
                'address_1' => trim($_POST['address-1'] ?? ''),
                'address_2' => trim($_POST['address-2'] ?? ''),
                'address_3' => trim($_POST['address-3'] ?? ''),
                'address_4' => trim($_POST['address-4'] ?? ''),
                'address_5' => trim($_POST['address-5'] ?? ''),
                'address_6' => trim($_POST['address-6'] ?? ''),
                'address_7' => trim($_POST['address-7'] ?? ''),
                'address_8' => trim($_POST['address-8'] ?? ''),
                'address_9' => trim($_POST['address-9'] ?? ''),
                'same_as_pbirth' => isset($_POST['same_as_pbirth']) ? 1 : 0
            ];

            // Update address data
            $addressSuccess = $addressCrud->update($addressData, ['applicant_id' => $applicantId]);

            // Prepare parents data
            $parentsData = [
                'lfather' => trim($_POST['lfather'] ?? ''),
                'ffather' => trim($_POST['ffather'] ?? ''),
                'mfather' => trim($_POST['mfather'] ?? ''),
                'sfxfather' => trim($_POST['sfxfather'] ?? ''),
                'fbirth' => !empty($_POST['fbirth']) ? date('Y-m-d', strtotime($_POST['fbirth'])) : null,
                'lmother' => trim($_POST['lmother'] ?? ''),
                'fmother' => trim($_POST['fmother'] ?? ''),
                'mmother' => trim($_POST['mmother'] ?? ''),
                'sfxmother' => trim($_POST['sfxmother'] ?? ''),
                'mbirth' => !empty($_POST['mbirth']) ? date('Y-m-d', strtotime($_POST['mbirth'])) : null
            ];

            // Update parents data
            $parentsSuccess = $parentsCrud->update($parentsData, ['applicant_id' => $applicantId]);

            // Prepare spouse data if provided
            $spouseSuccess = true; // Assume success if no spouse data
            if (!empty(trim($_POST['lspouse'] ?? '')) || !empty(trim($_POST['fspouse'] ?? ''))) {
                $spouseData = [
                    'lspouse' => trim($_POST['lspouse'] ?? ''),
                    'fspouse' => trim($_POST['fspouse'] ?? ''),
                    'mspouse' => trim($_POST['mspouse'] ?? ''),
                    'sfxspouse' => trim($_POST['sfxspouse'] ?? ''),
                    'sbirth' => !empty($_POST['sbirth']) ? date('Y-m-d', strtotime($_POST['sbirth'])) : null
                ];
                $spouseSuccess = $spouseCrud->update($spouseData, ['applicant_id' => $applicantId]);
            }

            // Prepare employment data
            $employmentSuccess = true; // Assume success if no employment data
            $employmentType = '';
            if (!empty(trim($_POST['profession'] ?? ''))) $employmentType = 'Self-Employed';
            elseif (!empty(trim($_POST['faddress'] ?? ''))) $employmentType = 'OFW';
            elseif (!empty(trim($_POST['spouse-ssnum'] ?? ''))) $employmentType = 'Non-Working Spouse';

            if (!empty($employmentType)) {
                $employmentData = [
                    'employment_type' => $employmentType,
                    'profession' => trim($_POST['profession'] ?? ''),
                    'ystart' => trim($_POST['ystart'] ?? ''),
                    'mearning' => trim($_POST['mearning'] ?? ''),
                    'faddress' => trim($_POST['faddress'] ?? ''),
                    'ofw_monthly_earnings' => trim($_POST['ofw_monthly_earnings'] ?? ''),
                    'spouse_ssnum' => trim($_POST['spouse-ssnum'] ?? ''),
                    'ffprogram' => trim($_POST['ffprogram'] ?? ''),
                    'ffp' => trim($_POST['ffp'] ?? '')
                ];
                $employmentSuccess = $employmentCrud->update($employmentData, ['applicant_id' => $applicantId]);
            }

            // Check if all operations were successful
            if ($applicantSuccess && $addressSuccess && $parentsSuccess && $spouseSuccess && $employmentSuccess) {
                echo json_encode(['success' => true, 'message' => 'Record updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update record']);
            }
            exit();

        } catch(Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
            exit();
        }
    } else {
        echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
        exit();
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $action === 'insert') {
    $errors = [];

    // Validate required fields
    $ssnum = trim($_POST['ssnum'] ?? '');
    if (!validateRequired($ssnum)) {
        $errors[] = "SS Number is required";
    }

    $lname = trim($_POST['lname'] ?? '');
    if (!validateRequired($lname)) {
        $errors[] = "Last Name is required";
    }

    $fname = trim($_POST['fname'] ?? '');
    if (!validateRequired($fname)) {
        $errors[] = "First Name is required";
    }

    $dbirth = trim($_POST['dbirth'] ?? '');
    if (!validateRequired($dbirth)) {
        $errors[] = "Date of Birth is required";
    } elseif (!empty($dbirth) && strtotime($dbirth) >= time()) {
        $errors[] = "Date of Birth must be in the past";
    }

    if (!validateRequired($_POST['sex'] ?? '')) $errors[] = "Sex is required";
    if (!validateRequired($_POST['cvstatus'] ?? '')) $errors[] = "Civil Status is required";
    if (!validateRequired($_POST['nation'] ?? '')) $errors[] = "Nationality is required";
    if (!validateRequired($_POST['pbirth'] ?? '')) $errors[] = "Place of Birth is required";
    if (!validateRequired($_POST['address_6'] ?? '')) $errors[] = "Address (City) is required";
    if (!validateRequired($_POST['address_7'] ?? '')) $errors[] = "Address (Province) is required";
    if (!validateRequired($_POST['cphone'] ?? '')) $errors[] = "Mobile Number is required";

    $email = trim($_POST['email'] ?? '');
    if (!validateRequired($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    $cphone = trim($_POST['cphone'] ?? '');
    if (!empty($cphone) && !preg_match('/^[0-9+\-\s()]+$/', preg_replace('/\D/', '', $cphone))) {
        $errors[] = "Invalid phone number format";
    }

    // If no errors, process the data
    if (empty($errors)) {
        try {
            // Create CRUD instances for different tables
            $applicantCrud = new UniversalCRUD('applicants');
            $addressCrud = new UniversalCRUD('applicant_addresses');
            $parentsCrud = new UniversalCRUD('applicant_parents');
            $spouseCrud = new UniversalCRUD('applicant_spouse');
            $childrenCrud = new UniversalCRUD('applicant_children');
            $employmentCrud = new UniversalCRUD('applicant_employment');

            // Prepare applicant data
            $applicantData = [
                'ssnum' => $ssnum,
                'lname' => $lname,
                'fname' => $fname,
                'mname' => trim($_POST['mname'] ?? ''),
                'sfx' => trim($_POST['sfx'] ?? ''),
                'dbirth' => date('Y-m-d', strtotime($dbirth)),  // Format date for DATE type
                'sex' => trim($_POST['sex'] ?? ''),
                'cvstatus' => trim($_POST['cvstatus'] ?? ''),
                'cvstatus_other' => trim($_POST['cvstatus_other'] ?? ''),
                'taxid' => trim($_POST['taxid'] ?? ''),
                'nation' => trim($_POST['nation'] ?? ''),
                'religion' => trim($_POST['religion'] ?? ''),
                'pbirth' => trim($_POST['pbirth'] ?? ''),
                'cphone' => $cphone,
                'email' => $email,
                'tphone' => trim($_POST['tphone'] ?? ''),
                'printed_name' => trim($_POST['printed-name'] ?? ''),
                'cert_date' => !empty($_POST['cert-date']) ? date('Y-m-d', strtotime($_POST['cert-date'])) : null
            ];

            // Insert applicant data
            $applicantId = $applicantCrud->create($applicantData);

            if ($applicantId) {
                // Prepare address data
                $addressData = [
                    'applicant_id' => $applicantId,
                    'address_1' => trim($_POST['address-1'] ?? ''),
                    'address_2' => trim($_POST['address-2'] ?? ''),
                    'address_3' => trim($_POST['address-3'] ?? ''),
                    'address_4' => trim($_POST['address-4'] ?? ''),
                    'address_5' => trim($_POST['address-5'] ?? ''),
                    'address_6' => trim($_POST['address-6'] ?? ''),
                    'address_7' => trim($_POST['address-7'] ?? ''),
                    'address_8' => trim($_POST['address-8'] ?? ''),
                    'address_9' => trim($_POST['address-9'] ?? ''),
                    'same_as_pbirth' => isset($_POST['same_as_pbirth']) ? 1 : 0
                ];

                // Insert address data
                $addressId = $addressCrud->create($addressData);

                // Prepare parents data
                $parentsData = [
                    'applicant_id' => $applicantId,
                    'lfather' => trim($_POST['lfather'] ?? ''),
                    'ffather' => trim($_POST['ffather'] ?? ''),
                    'mfather' => trim($_POST['mfather'] ?? ''),
                    'sfxfather' => trim($_POST['sfxfather'] ?? ''),
                    'fbirth' => !empty($_POST['fbirth']) ? date('Y-m-d', strtotime($_POST['fbirth'])) : null,
                    'lmother' => trim($_POST['lmother'] ?? ''),
                    'fmother' => trim($_POST['fmother'] ?? ''),
                    'mmother' => trim($_POST['mmother'] ?? ''),
                    'sfxmother' => trim($_POST['sfxmother'] ?? ''),
                    'mbirth' => !empty($_POST['mbirth']) ? date('Y-m-d', strtotime($_POST['mbirth'])) : null
                ];

                // Insert parents data
                $parentsId = $parentsCrud->create($parentsData);

                // Insert spouse data if provided
                if (!empty(trim($_POST['lspouse'] ?? '')) || !empty(trim($_POST['fspouse'] ?? ''))) {
                    $spouseData = [
                        'applicant_id' => $applicantId,
                        'lspouse' => trim($_POST['lspouse'] ?? ''),
                        'fspouse' => trim($_POST['fspouse'] ?? ''),
                        'mspouse' => trim($_POST['mspouse'] ?? ''),
                        'sfxspouse' => trim($_POST['sfxspouse'] ?? ''),
                        'sbirth' => !empty($_POST['sbirth']) ? date('Y-m-d', strtotime($_POST['sbirth'])) : null
                    ];
                    $spouseId = $spouseCrud->create($spouseData);
                }

                // Insert children data
                if (isset($_POST['children']) && is_array($_POST['children'])) {
                    foreach ($_POST['children'] as $child) {
                        if (!empty($child['lname']) || !empty($child['fname'])) {
                            $childData = [
                                'applicant_id' => $applicantId,
                                'lname' => trim($child['lname'] ?? ''),
                                'fname' => trim($child['fname'] ?? ''),
                                'mname' => trim($child['mname'] ?? ''),
                                'sfx' => trim($child['sfx'] ?? ''),
                                'dbirth' => !empty($child['dbirth']) ? date('Y-m-d', strtotime($child['dbirth'])) : null
                            ];
                            $childrenCrud->create($childData);
                        }
                    }
                }

                // Insert employment data
                $employmentType = '';
                if (!empty(trim($_POST['profession'] ?? ''))) $employmentType = 'Self-Employed';
                elseif (!empty(trim($_POST['faddress'] ?? ''))) $employmentType = 'OFW';
                elseif (!empty(trim($_POST['spouse-ssnum'] ?? ''))) $employmentType = 'Non-Working Spouse';

                if (!empty($employmentType)) {
                    $employmentData = [
                        'applicant_id' => $applicantId,
                        'employment_type' => $employmentType,
                        'profession' => trim($_POST['profession'] ?? ''),
                        'ystart' => trim($_POST['ystart'] ?? ''),
                        'mearning' => trim($_POST['mearning'] ?? ''),
                        'faddress' => trim($_POST['faddress'] ?? ''),
                        'ofw_monthly_earnings' => trim($_POST['ofw_monthly_earnings'] ?? ''),
                        'spouse_ssnum' => trim($_POST['spouse-ssnum'] ?? ''),
                        'ffprogram' => trim($_POST['ffprogram'] ?? ''),
                        'ffp' => trim($_POST['ffp'] ?? '')
                    ];
                    $employmentId = $employmentCrud->create($employmentData);
                }

                echo "<script>sessionStorage.setItem('formSuccess', 'true'); window.location.href='page1.html';</script>";
                exit();

            } else {
                echo "<script>alert('Failed to create applicant record'); window.history.back();</script>";
                exit();
            }

        } catch(Exception $e) {
            echo "<script>alert('Database error: " . $e->getMessage() . "'); window.history.back();</script>";
            exit();
        }
    } else {
        $errorMsg = implode("\\n", $errors);
        echo "<script>alert('$errorMsg'); window.history.back();</script>";
        exit();
    }
}
?>
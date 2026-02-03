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
switch($action) {
    case 'update':
        handleUpdate();
        break;

    case 'insert':
        handleInsert();
        break;

    default:
        // Handle invalid action
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}

//==============================

// UPDATE OPERATION
function handleUpdate() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit;
    }

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
}

//==============================

// INSERT OPERATION
function handleInsert() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo "<script>alert('Invalid request method'); window.history.back();</script>";
        exit;
    }

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

//==============================
?>

            $updateStmt = mysqli_prepare($conn, $updateSql);

            // Prepare variables for binding
            $ssnum = trim($_POST['ssnum'] ?? '');
            $lname = trim($_POST['lname'] ?? '');
            $fname = trim($_POST['fname'] ?? '');
            $mname = trim($_POST['mname'] ?? '');
            $sfx = trim($_POST['sfx'] ?? '');

            // Format date to ensure it's in YYYY-MM-DD format for DATE type
            $dbirth = !empty($_POST['dbirth']) ? date('Y-m-d', strtotime($_POST['dbirth'])) : null;

            $sex = trim($_POST['sex'] ?? '');
            $cvstatus = trim($_POST['cvstatus'] ?? '');
            $cvstatus_other = trim($_POST['cvstatus_other'] ?? '');
            $taxid = trim($_POST['taxid'] ?? '');
            $nation = trim($_POST['nation'] ?? '');
            $religion = trim($_POST['religion'] ?? '');
            $pbirth = trim($_POST['pbirth'] ?? '');
            $cphone = trim($_POST['cphone'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $tphone = trim($_POST['tphone'] ?? '');
            $printed_name = trim($_POST['printed-name'] ?? '');

            // Format date fields to ensure they're in YYYY-MM-DD format for DATE type
            $cert_date = !empty($_POST['cert-date']) ? date('Y-m-d', strtotime($_POST['cert-date'])) : null;

            // Bind parameters
            mysqli_stmt_bind_param($updateStmt, "ssssssssssssssssssi", 
                $ssnum, $lname, $fname, $mname, $sfx, $dbirth, $sex, $cvstatus, $cvstatus_other,
                $taxid, $nation, $religion, $pbirth, $cphone, $email, $tphone, $printed_name, $cert_date, $applicantId);

            mysqli_stmt_execute($updateStmt);

            // Update address data
            $addressUpdateSql = "UPDATE applicant_addresses SET
                address_1 = ?, address_2 = ?, address_3 = ?, address_4 = ?, address_5 = ?,
                address_6 = ?, address_7 = ?, address_8 = ?, address_9 = ?, same_as_pbirth = ?
            WHERE applicant_id = ?";

            $addressUpdateStmt = mysqli_prepare($conn, $addressUpdateSql);

            // Prepare address variables
            $address_1 = trim($_POST['address-1'] ?? '');
            $address_2 = trim($_POST['address-2'] ?? '');
            $address_3 = trim($_POST['address-3'] ?? '');
            $address_4 = trim($_POST['address-4'] ?? '');
            $address_5 = trim($_POST['address-5'] ?? '');
            $address_6 = trim($_POST['address-6'] ?? '');
            $address_7 = trim($_POST['address-7'] ?? '');
            $address_8 = trim($_POST['address-8'] ?? '');
            $address_9 = trim($_POST['address-9'] ?? '');
            $same_as_pbirth = isset($_POST['same_as_pbirth']) ? 1 : 0;

            mysqli_stmt_bind_param($addressUpdateStmt, "sssssssssi", 
                $address_1, $address_2, $address_3, $address_4, $address_5,
                $address_6, $address_7, $address_8, $address_9, $same_as_pbirth, $applicantId);

            mysqli_stmt_execute($addressUpdateStmt);

            // Update parents data
            $parentsUpdateSql = "UPDATE applicant_parents SET
                lfather = ?, ffather = ?, mfather = ?, sfxfather = ?, fbirth = ?,
                lmother = ?, fmother = ?, mmother = ?, sfxmother = ?, mbirth = ?
            WHERE applicant_id = ?";

            $parentsUpdateStmt = mysqli_prepare($conn, $parentsUpdateSql);

            // Prepare parents variables
            $lfather = trim($_POST['lfather'] ?? '');
            $ffather = trim($_POST['ffather'] ?? '');
            $mfather = trim($_POST['mfather'] ?? '');
            $sfxfather = trim($_POST['sfxfather'] ?? '');

            // Format date fields to ensure they're in YYYY-MM-DD format for DATE type
            $fbirth = !empty($_POST['fbirth']) ? date('Y-m-d', strtotime($_POST['fbirth'])) : null;

            $lmother = trim($_POST['lmother'] ?? '');
            $fmother = trim($_POST['fmother'] ?? '');
            $mmother = trim($_POST['mmother'] ?? '');
            $sfxmother = trim($_POST['sfxmother'] ?? '');

            // Format date fields to ensure they're in YYYY-MM-DD format for DATE type
            $mbirth = !empty($_POST['mbirth']) ? date('Y-m-d', strtotime($_POST['mbirth'])) : null;

            mysqli_stmt_bind_param($parentsUpdateStmt, "sssssssssi", 
                $lfather, $ffather, $mfather, $sfxfather, $fbirth,
                $lmother, $fmother, $mmother, $sfxmother, $mbirth, $applicantId);

            mysqli_stmt_execute($parentsUpdateStmt);

            // Update spouse data if provided
            if (!empty(trim($_POST['lspouse'] ?? '')) || !empty(trim($_POST['fspouse'] ?? ''))) {
                $spouseUpdateSql = "UPDATE applicant_spouse SET
                    lspouse = ?, fspouse = ?, mspouse = ?, sfxspouse = ?, sbirth = ?
                WHERE applicant_id = ?";

                $spouseUpdateStmt = mysqli_prepare($conn, $spouseUpdateSql);

                // Prepare spouse variables
                $lspouse = trim($_POST['lspouse'] ?? '');
                $fspouse = trim($_POST['fspouse'] ?? '');
                $mspouse = trim($_POST['mspouse'] ?? '');
                $sfxspouse = trim($_POST['sfxspouse'] ?? '');

                // Format date fields to ensure they're in YYYY-MM-DD format for DATE type
                $sbirth = !empty($_POST['sbirth']) ? date('Y-m-d', strtotime($_POST['sbirth'])) : null;

                mysqli_stmt_bind_param($spouseUpdateStmt, "sssssi", 
                    $lspouse, $fspouse, $mspouse, $sfxspouse, $sbirth, $applicantId);

                mysqli_stmt_execute($spouseUpdateStmt);
            }

            // Update employment data
            $employmentType = '';
            if (!empty(trim($_POST['profession'] ?? ''))) $employmentType = 'Self-Employed';
            elseif (!empty(trim($_POST['faddress'] ?? ''))) $employmentType = 'OFW';
            elseif (!empty(trim($_POST['spouse-ssnum'] ?? ''))) $employmentType = 'Non-Working Spouse';

            if (!empty($employmentType)) {
                $employmentUpdateSql = "UPDATE applicant_employment SET
                    employment_type = ?, profession = ?, ystart = ?, mearning = ?, faddress = ?,
                    ofw_monthly_earnings = ?, spouse_ssnum = ?, ffprogram = ?, ffp = ?
                WHERE applicant_id = ?";

                $employmentUpdateStmt = mysqli_prepare($conn, $employmentUpdateSql);

                // Prepare employment variables
                $profession = trim($_POST['profession'] ?? '');
                $ystart = trim($_POST['ystart'] ?? '');
                $mearning = trim($_POST['mearning'] ?? '');
                $faddress = trim($_POST['faddress'] ?? '');
                $ofw_monthly_earnings = trim($_POST['ofw_monthly_earnings'] ?? '');
                $spouse_ssnum = trim($_POST['spouse-ssnum'] ?? '');
                $ffprogram = trim($_POST['ffprogram'] ?? '');
                $ffp = trim($_POST['ffp'] ?? '');

                mysqli_stmt_bind_param($employmentUpdateStmt, "sssssssssi", 
                    $employmentType, $profession, $ystart, $mearning, $faddress,
                    $ofw_monthly_earnings, $spouse_ssnum, $ffprogram, $ffp, $applicantId);

                mysqli_stmt_execute($employmentUpdateStmt);
            }

            mysqli_commit($conn);
            echo json_encode(['success' => true, 'message' => 'Record updated successfully']);
            exit();

        } catch (Exception $e) {
            error_log($e->getMessage());
            mysqli_rollback($conn);
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
    if (!validateRequired($_POST['address-6'] ?? '')) $errors[] = "Address (City) is required";
    if (!validateRequired($_POST['address-7'] ?? '')) $errors[] = "Address (Province) is required";
    if (!validateRequired($_POST['address-9'] ?? '')) $errors[] = "Zip Code is required";
    if (!validateRequired($_POST['cphone'] ?? '')) $errors[] = "Mobile Number is required";

    $email = trim($_POST['email'] ?? '');
    if (!validateRequired($email)) {
        $errors[] = "Email is required";
    } elseif (!validateEmail($email)) {
        $errors[] = "Invalid email format";
    }

    $cphone = trim($_POST['cphone'] ?? '');
    if (!empty($cphone) && !validatePhone($cphone)) {
        $errors[] = "Invalid phone number format (must be 09XXXXXXXXX or +639XXXXXXXXX)";
    }

    // If no errors, process the data
    if (empty($errors)) {
        mysqli_begin_transaction($conn);
        try {
            // Insert applicant data
            $applicantSql = "INSERT INTO applicants (
                ssnum, lname, fname, mname, sfx, dbirth, sex, cvstatus, cvstatus_other,
                taxid, nation, religion, pbirth, cphone, email, tphone,
                printed_name, cert_date
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $applicantStmt = mysqli_prepare($conn, $applicantSql);

            // Prepare variables for binding
            $mname = trim($_POST['mname'] ?? '');
            $sfx = trim($_POST['sfx'] ?? '');
            $sex = trim($_POST['sex'] ?? '');
            $cvstatus = trim($_POST['cvstatus'] ?? '');
            $cvstatus_other = trim($_POST['cvstatus_other'] ?? '');
            $taxid = trim($_POST['taxid'] ?? '');
            $nation = trim($_POST['nation'] ?? '');
            $religion = trim($_POST['religion'] ?? '');
            $pbirth = trim($_POST['pbirth'] ?? '');
            $tphone = trim($_POST['tphone'] ?? '');
            $printed_name = trim($_POST['printed-name'] ?? '');

            // Format date to ensure it's in YYYY-MM-DD format for DATE type
            $formattedDbirth = date('Y-m-d', strtotime($dbirth));

            // Format date fields to ensure they're in YYYY-MM-DD format for DATE type
            $formattedCertDate = !empty($_POST['cert-date']) ? date('Y-m-d', strtotime($_POST['cert-date'])) : null;

            // Bind parameters for applicant
            mysqli_stmt_bind_param($applicantStmt, "ssssssssssssssssss",
                $ssnum, $lname, $fname, $mname, $sfx, $formattedDbirth, $sex, $cvstatus, $cvstatus_other,
                $taxid, $nation, $religion, $pbirth, $cphone, $email, $tphone,
                $printed_name, $formattedCertDate);

            mysqli_stmt_execute($applicantStmt);
            $applicantId = mysqli_insert_id($conn);

            // Insert address data
            $addressSql = "INSERT INTO applicant_addresses (
                applicant_id, address_1, address_2, address_3, address_4, address_5,
                address_6, address_7, address_8, address_9, same_as_pbirth
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $addressStmt = mysqli_prepare($conn, $addressSql);

            // Prepare variables for binding
            $address_1 = trim($_POST['address-1'] ?? '');
            $address_2 = trim($_POST['address-2'] ?? '');
            $address_3 = trim($_POST['address-3'] ?? '');
            $address_4 = trim($_POST['address-4'] ?? '');
            $address_5 = trim($_POST['address-5'] ?? '');
            $address_6 = trim($_POST['address-6'] ?? '');
            $address_7 = trim($_POST['address-7'] ?? '');
            $address_8 = trim($_POST['address-8'] ?? '');
            $address_9 = trim($_POST['address-9'] ?? '');
            $same_as_pbirth = isset($_POST['same_as_pbirth']) ? 1 : 0;

            mysqli_stmt_bind_param($addressStmt, "isssssssssi",
                $applicantId, $address_1, $address_2, $address_3, $address_4, $address_5,
                $address_6, $address_7, $address_8, $address_9, $same_as_pbirth);
            
            mysqli_stmt_execute($addressStmt);

            // Insert parents data
            $parentsSql = "INSERT INTO applicant_parents (
                applicant_id, lfather, ffather, mfather, sfxfather, fbirth,
                lmother, fmother, mmother, sfxmother, mbirth
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $parentsStmt = mysqli_prepare($conn, $parentsSql);

            // Prepare variables for binding
            $lfather = trim($_POST['lfather'] ?? '');
            $ffather = trim($_POST['ffather'] ?? '');
            $mfather = trim($_POST['mfather'] ?? '');
            $sfxfather = trim($_POST['sfxfather'] ?? '');
            $lmother = trim($_POST['lmother'] ?? '');
            $fmother = trim($_POST['fmother'] ?? '');
            $mmother = trim($_POST['mmother'] ?? '');
            $sfxmother = trim($_POST['sfxmother'] ?? '');

            // Format date fields to ensure they're in YYYY-MM-DD format for DATE type
            $formattedFbirth = !empty($_POST['fbirth']) ? date('Y-m-d', strtotime($_POST['fbirth'])) : null;
            $formattedMbirth = !empty($_POST['mbirth']) ? date('Y-m-d', strtotime($_POST['mbirth'])) : null;

            mysqli_stmt_bind_param($parentsStmt, "isssssssssi",
                $applicantId, $lfather, $ffather, $mfather, $sfxfather, $formattedFbirth,
                $lmother, $fmother, $mmother, $sfxmother, $formattedMbirth);

            mysqli_stmt_execute($parentsStmt);

            // Insert spouse data if provided
            if (!empty(trim($_POST['lspouse'] ?? '')) || !empty(trim($_POST['fspouse'] ?? ''))) {
                $spouseSql = "INSERT INTO applicant_spouse (
                    applicant_id, lspouse, fspouse, mspouse, sfxspouse, sbirth
                ) VALUES (?, ?, ?, ?, ?, ?)";

                $spouseStmt = mysqli_prepare($conn, $spouseSql);

                // Prepare variables for binding
                $lspouse = trim($_POST['lspouse'] ?? '');
                $fspouse = trim($_POST['fspouse'] ?? '');
                $mspouse = trim($_POST['mspouse'] ?? '');
                $sfxspouse = trim($_POST['sfxspouse'] ?? '');

                // Format date fields to ensure they're in YYYY-MM-DD format for DATE type
                $formattedSbirth = !empty($_POST['sbirth']) ? date('Y-m-d', strtotime($_POST['sbirth'])) : null;

                mysqli_stmt_bind_param($spouseStmt, "isssss",
                    $applicantId, $lspouse, $fspouse, $mspouse, $sfxspouse, $formattedSbirth);

                mysqli_stmt_execute($spouseStmt);
            }

            // Insert children data
            if (isset($_POST['children']) && is_array($_POST['children'])) {
                $childSql = "INSERT INTO applicant_children (
                    applicant_id, lname, fname, mname, sfx, dbirth
                ) VALUES (?, ?, ?, ?, ?, ?)";

                $childStmt = mysqli_prepare($conn, $childSql);

                foreach ($_POST['children'] as $child) {
                    if (!empty($child['lname']) || !empty($child['fname'])) {
                        // Format child date of birth to ensure it's in YYYY-MM-DD format for DATE type
                        $formattedChildDbirth = !empty($child['dbirth']) ? date('Y-m-d', strtotime($child['dbirth'])) : null;

                        mysqli_stmt_bind_param($childStmt, "isssss",
                            $applicantId, $child['lname'], $child['fname'], $child['mname'], $child['sfx'], $formattedChildDbirth);

                        mysqli_stmt_execute($childStmt);
                    }
                }
            }

            // Insert employment data
            $employmentType = '';
            if (!empty(trim($_POST['profession'] ?? ''))) $employmentType = 'Self-Employed';
            elseif (!empty(trim($_POST['faddress'] ?? ''))) $employmentType = 'OFW';
            elseif (!empty(trim($_POST['spouse-ssnum'] ?? ''))) $employmentType = 'Non-Working Spouse';

            if (!empty($employmentType)) {
                $employmentSql = "INSERT INTO applicant_employment (
                    applicant_id, employment_type, profession, ystart, mearning,
                    faddress, ofw_monthly_earnings, spouse_ssnum, ffprogram, ffp
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $employmentStmt = mysqli_prepare($conn, $employmentSql);

                // Prepare variables for binding
                $profession = trim($_POST['profession'] ?? '');
                $ystart = trim($_POST['ystart'] ?? '');
                $mearning = trim($_POST['mearning'] ?? '');
                $faddress = trim($_POST['faddress'] ?? '');
                $ofw_monthly_earnings = trim($_POST['ofw_monthly_earnings'] ?? '');
                $spouse_ssnum = trim($_POST['spouse-ssnum'] ?? '');
                $ffprogram = trim($_POST['ffprogram'] ?? '');
                $ffp = trim($_POST['ffp'] ?? '');

                mysqli_stmt_bind_param($employmentStmt, "isssssssss",
                    $applicantId, $employmentType, $profession, $ystart, $mearning,
                    $faddress, $ofw_monthly_earnings, $spouse_ssnum, $ffprogram, $ffp);
                
                mysqli_stmt_execute($employmentStmt);
            }

            mysqli_commit($conn);
            echo "<script>sessionStorage.setItem('formSuccess', 'true'); window.location.href='page1.html';</script>";
            exit();

        } catch (Exception $e) {
            error_log($e->getMessage());
            mysqli_rollback($conn);
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
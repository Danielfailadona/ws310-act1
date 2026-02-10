<?php
// Include the Usables class
require_once '../usables/usables.php';

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
            // Create Usables instance
            $usables = new Usables();

            // Prepare applicant data
            $ssnum = clean($_POST['ssnum'] ?? '');
            $lname = clean($_POST['lname'] ?? '');
            $fname = clean($_POST['fname'] ?? '');
            $mname = clean($_POST['mname'] ?? '');
            $sfx = clean($_POST['sfx'] ?? '');
            $dbirth = !empty($_POST['dbirth']) ? formatDateForDB($_POST['dbirth']) : null;
            $sex = clean($_POST['sex'] ?? '');
            $cvstatus = clean($_POST['cvstatus'] ?? '');
            $cvstatus_other = clean($_POST['cvstatus_other'] ?? '');
            $taxid = clean($_POST['taxid'] ?? '');
            $nation = clean($_POST['nation'] ?? '');
            $religion = clean($_POST['religion'] ?? '');
            $pbirth = clean($_POST['pbirth'] ?? '');
            $cphone = clean($_POST['cphone'] ?? '');
            $email = clean($_POST['email'] ?? '');
            $tphone = clean($_POST['tphone'] ?? '');
            $printed_name = clean($_POST['printed-name'] ?? '');
            $cert_date = !empty($_POST['cert-date']) ? formatDateForDB($_POST['cert-date']) : null;

            // Update applicant data using the Usables class
            $applicantSuccess = $usables->updateDataInDatabase(
                'applicants',
                [
                    'ssnum' => $ssnum,
                    'lname' => $lname,
                    'fname' => $fname,
                    'mname' => $mname,
                    'sfx' => $sfx,
                    'dbirth' => $dbirth,
                    'sex' => $sex,
                    'cvstatus' => $cvstatus,
                    'cvstatus_other' => $cvstatus_other,
                    'taxid' => $taxid,
                    'nation' => $nation,
                    'religion' => $religion,
                    'pbirth' => $pbirth,
                    'cphone' => $cphone,
                    'email' => $email,
                    'tphone' => $tphone,
                    'printed_name' => $printed_name,
                    'cert_date' => $cert_date
                ],
                ['applicant_id' => $applicantId]
            );

            // Prepare address data
            $address_1 = clean($_POST['address-1'] ?? '');
            $address_2 = clean($_POST['address-2'] ?? '');
            $address_3 = clean($_POST['address-3'] ?? '');
            $address_4 = clean($_POST['address-4'] ?? '');
            $address_5 = clean($_POST['address-5'] ?? '');
            $address_6 = clean($_POST['address-6'] ?? '');
            $address_7 = clean($_POST['address-7'] ?? '');
            $address_8 = clean($_POST['address-8'] ?? '');
            $address_9 = clean($_POST['address-9'] ?? '');
            $same_as_pbirth = isset($_POST['same_as_pbirth']) ? 1 : 0;

            // Update address data using the Usables class
            $addressSuccess = $usables->updateDataInDatabase(
                'applicant_addresses',
                [
                    'address_1' => $address_1,
                    'address_2' => $address_2,
                    'address_3' => $address_3,
                    'address_4' => $address_4,
                    'address_5' => $address_5,
                    'address_6' => $address_6,
                    'address_7' => $address_7,
                    'address_8' => $address_8,
                    'address_9' => $address_9,
                    'same_as_pbirth' => $same_as_pbirth
                ],
                ['applicant_id' => $applicantId]
            );

            // Prepare parents data
            $lfather = clean($_POST['lfather'] ?? '');
            $ffather = clean($_POST['ffather'] ?? '');
            $mfather = clean($_POST['mfather'] ?? '');
            $sfxfather = clean($_POST['sfxfather'] ?? '');
            $fbirth = !empty($_POST['fbirth']) ? formatDateForDB($_POST['fbirth']) : null;
            $lmother = clean($_POST['lmother'] ?? '');
            $fmother = clean($_POST['fmother'] ?? '');
            $mmother = clean($_POST['mmother'] ?? '');
            $sfxmother = clean($_POST['sfxmother'] ?? '');
            $mbirth = !empty($_POST['mbirth']) ? formatDateForDB($_POST['mbirth']) : null;

            // Update parents data using the Usables class
            $parentsSuccess = $usables->updateDataInDatabase(
                'applicant_parents',
                [
                    'lfather' => $lfather,
                    'ffather' => $ffather,
                    'mfather' => $mfather,
                    'sfxfather' => $sfxfather,
                    'fbirth' => $fbirth,
                    'lmother' => $lmother,
                    'fmother' => $fmother,
                    'mmother' => $mmother,
                    'sfxmother' => $sfxmother,
                    'mbirth' => $mbirth
                ],
                ['applicant_id' => $applicantId]
            );

            // Prepare spouse data if provided
            $spouseSuccess = true; // Assume success if no spouse data
            if (!empty(clean($_POST['lspouse'] ?? '')) || !empty(clean($_POST['fspouse'] ?? ''))) {
                $lspouse = clean($_POST['lspouse'] ?? '');
                $fspouse = clean($_POST['fspouse'] ?? '');
                $mspouse = clean($_POST['mspouse'] ?? '');
                $sfxspouse = clean($_POST['sfxspouse'] ?? '');
                $sbirth = !empty($_POST['sbirth']) ? formatDateForDB($_POST['sbirth']) : null;

                $spouseSuccess = $usables->updateDataInDatabase(
                    'applicant_spouse',
                    [
                        'lspouse' => $lspouse,
                        'fspouse' => $fspouse,
                        'mspouse' => $mspouse,
                        'sfxspouse' => $sfxspouse,
                        'sbirth' => $sbirth
                    ],
                    ['applicant_id' => $applicantId]
                );
            }

            // Prepare employment data
            $employmentSuccess = true; // Assume success if no employment data
            $employmentType = '';
            if (!empty(clean($_POST['profession'] ?? ''))) $employmentType = 'Self-Employed';
            elseif (!empty(clean($_POST['faddress'] ?? ''))) $employmentType = 'OFW';
            elseif (!empty(clean($_POST['spouse-ssnum'] ?? ''))) $employmentType = 'Non-Working Spouse';

            if (!empty($employmentType)) {
                $profession = clean($_POST['profession'] ?? '');
                $ystart = clean($_POST['ystart'] ?? '');
                $mearning = clean($_POST['mearning'] ?? '');
                $faddress = clean($_POST['faddress'] ?? '');
                $ofw_monthly_earnings = clean($_POST['ofw_monthly_earnings'] ?? '');
                $spouse_ssnum = clean($_POST['spouse-ssnum'] ?? '');
                $ffprogram = clean($_POST['ffprogram'] ?? '');
                $ffp = clean($_POST['ffp'] ?? '');

                $employmentSuccess = $usables->updateDataInDatabase(
                    'applicant_employment',
                    [
                        'employment_type' => $employmentType,
                        'profession' => $profession,
                        'ystart' => $ystart,
                        'mearning' => $mearning,
                        'faddress' => $faddress,
                        'ofw_monthly_earnings' => $ofw_monthly_earnings,
                        'spouse_ssnum' => $spouse_ssnum,
                        'ffprogram' => $ffprogram,
                        'ffp' => $ffp
                    ],
                    ['applicant_id' => $applicantId]
                );
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
    $ssnum = clean($_POST['ssnum'] ?? '');
    if (!validateRequired($ssnum)) {
        $errors[] = "SS Number is required";
    }

    $lname = clean($_POST['lname'] ?? '');
    if (!validateRequired($lname)) {
        $errors[] = "Last Name is required";
    }

    $fname = clean($_POST['fname'] ?? '');
    if (!validateRequired($fname)) {
        $errors[] = "First Name is required";
    }

    $dbirth = clean($_POST['dbirth'] ?? '');
    if (!validateRequired($dbirth)) {
        $errors[] = "Date of Birth is required";
    } elseif (!empty($dbirth) && !validatePastDate($dbirth)) {
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

    $email = clean($_POST['email'] ?? '');
    if (!validateRequired($email)) {
        $errors[] = "Email is required";
    } elseif (!validateEmail($email)) {
        $errors[] = "Invalid email format";
    }

    $cphone = clean($_POST['cphone'] ?? '');
    if (!empty($cphone) && !validatePhone($cphone)) {
        $errors[] = "Invalid phone number format (must be 09XXXXXXXXX or +639XXXXXXXXX)";
    }

    // Check if email already exists using the Usables class
    if (empty($errors) && !empty($email)) {
        $usables = new Usables();
        $emailExists = $usables->recordExists('applicants', ['email' => $email]);
        if ($emailExists) {
            $errors[] = "An account with this email already exists";
        }
    }

    // If no errors, process the data
    if (empty($errors)) {
        try {
            // Create Usables instance
            $usables = new Usables();

            // Prepare applicant data
            $ssnum = clean($ssnum);
            $lname = clean($lname);
            $fname = clean($fname);
            $mname = clean($_POST['mname'] ?? '');
            $sfx = clean($_POST['sfx'] ?? '');
            $dbirth = formatDateForDB($dbirth);  // Format date for DATE type
            $sex = clean($_POST['sex'] ?? '');
            $cvstatus = clean($_POST['cvstatus'] ?? '');
            $cvstatus_other = clean($_POST['cvstatus_other'] ?? '');
            $taxid = clean($_POST['taxid'] ?? '');
            $nation = clean($_POST['nation'] ?? '');
            $religion = clean($_POST['religion'] ?? '');
            $pbirth = clean($_POST['pbirth'] ?? '');
            $cphone = clean($cphone);
            $email = clean($email);
            $tphone = clean($_POST['tphone'] ?? '');
            $printed_name = clean($_POST['printed-name'] ?? '');
            $cert_date = !empty($_POST['cert-date']) ? formatDateForDB($_POST['cert-date']) : null;

            // Insert applicant data using the Usables class
            $applicantId = $usables->insertDataToDatabase(
                'applicants',
                [
                    'ssnum' => $ssnum,
                    'lname' => $lname,
                    'fname' => $fname,
                    'mname' => $mname,
                    'sfx' => $sfx,
                    'dbirth' => $dbirth,
                    'sex' => $sex,
                    'cvstatus' => $cvstatus,
                    'cvstatus_other' => $cvstatus_other,
                    'taxid' => $taxid,
                    'nation' => $nation,
                    'religion' => $religion,
                    'pbirth' => $pbirth,
                    'cphone' => $cphone,
                    'email' => $email,
                    'tphone' => $tphone,
                    'printed_name' => $printed_name,
                    'cert_date' => $cert_date
                ]
            );

            if ($applicantId) {
                // Prepare address data
                $address_1 = clean($_POST['address-1'] ?? '');
                $address_2 = clean($_POST['address-2'] ?? '');
                $address_3 = clean($_POST['address-3'] ?? '');
                $address_4 = clean($_POST['address-4'] ?? '');
                $address_5 = clean($_POST['address-5'] ?? '');
                $address_6 = clean($_POST['address-6'] ?? '');
                $address_7 = clean($_POST['address-7'] ?? '');
                $address_8 = clean($_POST['address-8'] ?? '');
                $address_9 = clean($_POST['address-9'] ?? '');
                $same_as_pbirth = isset($_POST['same_as_pbirth']) ? 1 : 0;

                // Insert address data using the Usables class
                $addrResult = $usables->insertDataToDatabase(
                    'applicant_addresses',
                    [
                        'applicant_id' => $applicantId,
                        'address_1' => $address_1,
                        'address_2' => $address_2,
                        'address_3' => $address_3,
                        'address_4' => $address_4,
                        'address_5' => $address_5,
                        'address_6' => $address_6,
                        'address_7' => $address_7,
                        'address_8' => $address_8,
                        'address_9' => $address_9,
                        'same_as_pbirth' => $same_as_pbirth
                    ]
                );

                // Prepare parents data
                $lfather = clean($_POST['lfather'] ?? '');
                $ffather = clean($_POST['ffather'] ?? '');
                $mfather = clean($_POST['mfather'] ?? '');
                $sfxfather = clean($_POST['sfxfather'] ?? '');
                $fbirth = !empty($_POST['fbirth']) ? formatDateForDB($_POST['fbirth']) : null;
                $lmother = clean($_POST['lmother'] ?? '');
                $fmother = clean($_POST['fmother'] ?? '');
                $mmother = clean($_POST['mmother'] ?? '');
                $sfxmother = clean($_POST['sfxmother'] ?? '');
                $mbirth = !empty($_POST['mbirth']) ? formatDateForDB($_POST['mbirth']) : null;

                // Insert parents data using the Usables class
                $parentsResult = $usables->insertDataToDatabase(
                    'applicant_parents',
                    [
                        'applicant_id' => $applicantId,
                        'lfather' => $lfather,
                        'ffather' => $ffather,
                        'mfather' => $mfather,
                        'sfxfather' => $sfxfather,
                        'fbirth' => $fbirth,
                        'lmother' => $lmother,
                        'fmother' => $fmother,
                        'mmother' => $mmother,
                        'sfxmother' => $sfxmother,
                        'mbirth' => $mbirth
                    ]
                );

                // Insert spouse data if provided
                if (!empty(clean($_POST['lspouse'] ?? '')) || !empty(clean($_POST['fspouse'] ?? ''))) {
                    $lspouse = clean($_POST['lspouse'] ?? '');
                    $fspouse = clean($_POST['fspouse'] ?? '');
                    $mspouse = clean($_POST['mspouse'] ?? '');
                    $sfxspouse = clean($_POST['sfxspouse'] ?? '');
                    $sbirth = !empty($_POST['sbirth']) ? formatDateForDB($_POST['sbirth']) : null;

                    $spouseResult = $usables->insertDataToDatabase(
                        'applicant_spouse',
                        [
                            'applicant_id' => $applicantId,
                            'lspouse' => $lspouse,
                            'fspouse' => $fspouse,
                            'mspouse' => $mspouse,
                            'sfxspouse' => $sfxspouse,
                            'sbirth' => $sbirth
                        ]
                    );
                }

                // Insert children data
                if (isset($_POST['children']) && is_array($_POST['children'])) {
                    foreach ($_POST['children'] as $child) {
                        if (!empty($child['lname']) || !empty($child['fname'])) {
                            $child_lname = clean($child['lname'] ?? '');
                            $child_fname = clean($child['fname'] ?? '');
                            $child_mname = clean($child['mname'] ?? '');
                            $child_sfx = clean($child['sfx'] ?? '');
                            $child_dbirth = !empty($child['dbirth']) ? formatDateForDB($child['dbirth']) : null;

                            $usables->insertDataToDatabase(
                                'applicant_children',
                                [
                                    'applicant_id' => $applicantId,
                                    'lname' => $child_lname,
                                    'fname' => $child_fname,
                                    'mname' => $child_mname,
                                    'sfx' => $child_sfx,
                                    'dbirth' => $child_dbirth
                                ]
                            );
                        }
                    }
                }

                // Insert employment data
                $employmentType = '';
                if (!empty(clean($_POST['profession'] ?? ''))) $employmentType = 'Self-Employed';
                elseif (!empty(clean($_POST['faddress'] ?? ''))) $employmentType = 'OFW';
                elseif (!empty(clean($_POST['spouse-ssnum'] ?? ''))) $employmentType = 'Non-Working Spouse';

                if (!empty($employmentType)) {
                    $profession = clean($_POST['profession'] ?? '');
                    $ystart = clean($_POST['ystart'] ?? '');
                    $mearning = clean($_POST['mearning'] ?? '');
                    $faddress = clean($_POST['faddress'] ?? '');
                    $ofw_monthly_earnings = clean($_POST['ofw_monthly_earnings'] ?? '');
                    $spouse_ssnum = clean($_POST['spouse-ssnum'] ?? '');
                    $ffprogram = clean($_POST['ffprogram'] ?? '');
                    $ffp = clean($_POST['ffp'] ?? '');

                    $usables->insertDataToDatabase(
                        'applicant_employment',
                        [
                            'applicant_id' => $applicantId,
                            'employment_type' => $employmentType,
                            'profession' => $profession,
                            'ystart' => $ystart,
                            'mearning' => $mearning,
                            'faddress' => $faddress,
                            'ofw_monthly_earnings' => $ofw_monthly_earnings,
                            'spouse_ssnum' => $spouse_ssnum,
                            'ffprogram' => $ffprogram,
                            'ffp' => $ffp
                        ]
                    );
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
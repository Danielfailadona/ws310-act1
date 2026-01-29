<?php
// Database connection
function connectDB() {
    $host = 'localhost';
    $dbname = 'ws310_db';
    $username = 'root';
    $password = '';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Validation functions
function validateRequired($value) {
    return !empty(trim($value));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePhone($phone) {
    return preg_match('/^(09|\+639)\d{9}$/', preg_replace('/\D/', '', $phone));
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
            $conn = connectDB();
            $conn->beginTransaction();

            // Update applicant data
            $updateSql = "UPDATE applicants SET
                ssnum = :ssnum,
                lname = :lname,
                fname = :fname,
                mname = :mname,
                sfx = :sfx,
                dbirth = :dbirth,
                sex = :sex,
                cvstatus = :cvstatus,
                cvstatus_other = :cvstatus_other,
                taxid = :taxid,
                nation = :nation,
                religion = :religion,
                pbirth = :pbirth,
                cphone = :cphone,
                email = :email,
                tphone = :tphone,
                printed_name = :printed_name,
                cert_date = :cert_date
            WHERE applicant_id = :applicant_id";

            $updateStmt = $conn->prepare($updateSql);

            // Prepare variables for binding to avoid "passed by reference" error
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
            $updateStmt->bindParam(':applicant_id', $applicantId);
            $updateStmt->bindParam(':ssnum', $ssnum);
            $updateStmt->bindParam(':lname', $lname);
            $updateStmt->bindParam(':fname', $fname);
            $updateStmt->bindParam(':mname', $mname);
            $updateStmt->bindParam(':sfx', $sfx);
            $updateStmt->bindParam(':dbirth', $dbirth);
            $updateStmt->bindParam(':sex', $sex);
            $updateStmt->bindParam(':cvstatus', $cvstatus);
            $updateStmt->bindParam(':cvstatus_other', $cvstatus_other);
            $updateStmt->bindParam(':taxid', $taxid);
            $updateStmt->bindParam(':nation', $nation);
            $updateStmt->bindParam(':religion', $religion);
            $updateStmt->bindParam(':pbirth', $pbirth);
            $updateStmt->bindParam(':cphone', $cphone);
            $updateStmt->bindParam(':email', $email);
            $updateStmt->bindParam(':tphone', $tphone);
            $updateStmt->bindParam(':printed_name', $printed_name);
            $updateStmt->bindParam(':cert_date', $cert_date);

            $updateStmt->execute();

            // Update address data
            $addressUpdateSql = "UPDATE applicant_addresses SET
                address_1 = :address_1,
                address_2 = :address_2,
                address_3 = :address_3,
                address_4 = :address_4,
                address_5 = :address_5,
                address_6 = :address_6,
                address_7 = :address_7,
                address_8 = :address_8,
                address_9 = :address_9,
                same_as_pbirth = :same_as_pbirth
            WHERE applicant_id = :applicant_id";

            $addressUpdateStmt = $conn->prepare($addressUpdateSql);

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

            $addressUpdateStmt->bindParam(':applicant_id', $applicantId);
            $addressUpdateStmt->bindParam(':address_1', $address_1);
            $addressUpdateStmt->bindParam(':address_2', $address_2);
            $addressUpdateStmt->bindParam(':address_3', $address_3);
            $addressUpdateStmt->bindParam(':address_4', $address_4);
            $addressUpdateStmt->bindParam(':address_5', $address_5);
            $addressUpdateStmt->bindParam(':address_6', $address_6);
            $addressUpdateStmt->bindParam(':address_7', $address_7);
            $addressUpdateStmt->bindParam(':address_8', $address_8);
            $addressUpdateStmt->bindParam(':address_9', $address_9);
            $addressUpdateStmt->bindParam(':same_as_pbirth', $same_as_pbirth);

            $addressUpdateStmt->execute();

            // Update parents data
            $parentsUpdateSql = "UPDATE applicant_parents SET
                lfather = :lfather,
                ffather = :ffather,
                mfather = :mfather,
                sfxfather = :sfxfather,
                fbirth = :fbirth,
                lmother = :lmother,
                fmother = :fmother,
                mmother = :mmother,
                sfxmother = :sfxmother,
                mbirth = :mbirth
            WHERE applicant_id = :applicant_id";

            $parentsUpdateStmt = $conn->prepare($parentsUpdateSql);

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

            $parentsUpdateStmt->bindParam(':applicant_id', $applicantId);
            $parentsUpdateStmt->bindParam(':lfather', $lfather);
            $parentsUpdateStmt->bindParam(':ffather', $ffather);
            $parentsUpdateStmt->bindParam(':mfather', $mfather);
            $parentsUpdateStmt->bindParam(':sfxfather', $sfxfather);
            $parentsUpdateStmt->bindParam(':fbirth', $fbirth);
            $parentsUpdateStmt->bindParam(':lmother', $lmother);
            $parentsUpdateStmt->bindParam(':fmother', $fmother);
            $parentsUpdateStmt->bindParam(':mmother', $mmother);
            $parentsUpdateStmt->bindParam(':sfxmother', $sfxmother);
            $parentsUpdateStmt->bindParam(':mbirth', $mbirth);

            $parentsUpdateStmt->execute();

            // Update spouse data if provided
            if (!empty(trim($_POST['lspouse'] ?? '')) || !empty(trim($_POST['fspouse'] ?? ''))) {
                $spouseUpdateSql = "UPDATE applicant_spouse SET
                    lspouse = :lspouse,
                    fspouse = :fspouse,
                    mspouse = :mspouse,
                    sfxspouse = :sfxspouse,
                    sbirth = :sbirth
                WHERE applicant_id = :applicant_id";

                $spouseUpdateStmt = $conn->prepare($spouseUpdateSql);

                // Prepare spouse variables
                $lspouse = trim($_POST['lspouse'] ?? '');
                $fspouse = trim($_POST['fspouse'] ?? '');
                $mspouse = trim($_POST['mspouse'] ?? '');
                $sfxspouse = trim($_POST['sfxspouse'] ?? '');

                // Format date fields to ensure they're in YYYY-MM-DD format for DATE type
                $sbirth = !empty($_POST['sbirth']) ? date('Y-m-d', strtotime($_POST['sbirth'])) : null;

                $spouseUpdateStmt->bindParam(':applicant_id', $applicantId);
                $spouseUpdateStmt->bindParam(':lspouse', $lspouse);
                $spouseUpdateStmt->bindParam(':fspouse', $fspouse);
                $spouseUpdateStmt->bindParam(':mspouse', $mspouse);
                $spouseUpdateStmt->bindParam(':sfxspouse', $sfxspouse);
                $spouseUpdateStmt->bindParam(':sbirth', $sbirth);

                $spouseUpdateStmt->execute();
            }

            // Update employment data
            $employmentType = '';
            if (!empty(trim($_POST['profession'] ?? ''))) $employmentType = 'Self-Employed';
            elseif (!empty(trim($_POST['faddress'] ?? ''))) $employmentType = 'OFW';
            elseif (!empty(trim($_POST['spouse-ssnum'] ?? ''))) $employmentType = 'Non-Working Spouse';

            if (!empty($employmentType)) {
                $employmentUpdateSql = "UPDATE applicant_employment SET
                    employment_type = :employment_type,
                    profession = :profession,
                    ystart = :ystart,
                    mearning = :mearning,
                    faddress = :faddress,
                    ofw_monthly_earnings = :ofw_monthly_earnings,
                    spouse_ssnum = :spouse_ssnum,
                    ffprogram = :ffprogram,
                    ffp = :ffp
                WHERE applicant_id = :applicant_id";

                $employmentUpdateStmt = $conn->prepare($employmentUpdateSql);

                // Prepare employment variables
                $profession = trim($_POST['profession'] ?? '');
                $ystart = trim($_POST['ystart'] ?? '');
                $mearning = trim($_POST['mearning'] ?? '');
                $faddress = trim($_POST['faddress'] ?? '');
                $ofw_monthly_earnings = trim($_POST['ofw_monthly_earnings'] ?? '');
                $spouse_ssnum = trim($_POST['spouse-ssnum'] ?? '');
                $ffprogram = trim($_POST['ffprogram'] ?? '');
                $ffp = trim($_POST['ffp'] ?? '');

                $employmentUpdateStmt->bindParam(':applicant_id', $applicantId);
                $employmentUpdateStmt->bindParam(':employment_type', $employmentType);
                $employmentUpdateStmt->bindParam(':profession', $profession);
                $employmentUpdateStmt->bindParam(':ystart', $ystart);
                $employmentUpdateStmt->bindParam(':mearning', $mearning);
                $employmentUpdateStmt->bindParam(':faddress', $faddress);
                $employmentUpdateStmt->bindParam(':ofw_monthly_earnings', $ofw_monthly_earnings);
                $employmentUpdateStmt->bindParam(':spouse_ssnum', $spouse_ssnum);
                $employmentUpdateStmt->bindParam(':ffprogram', $ffprogram);
                $employmentUpdateStmt->bindParam(':ffp', $ffp);

                $employmentUpdateStmt->execute();
            }

            $conn->commit();
            echo json_encode(['success' => true, 'message' => 'Record updated successfully']);
            exit();

        } catch(PDOException $e) {
            $conn->rollBack();
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
        try {
            $conn = connectDB();
            $conn->beginTransaction();

            // Insert applicant data
            $applicantSql = "INSERT INTO applicants (
                ssnum, lname, fname, mname, sfx, dbirth, sex, cvstatus, cvstatus_other,
                taxid, nation, religion, pbirth, cphone, email, tphone,
                printed_name, cert_date
            ) VALUES (
                :ssnum, :lname, :fname, :mname, :sfx, :dbirth, :sex, :cvstatus, :cvstatus_other,
                :taxid, :nation, :religion, :pbirth, :cphone, :email, :tphone,
                :printed_name, :cert_date
            )";

            $applicantStmt = $conn->prepare($applicantSql);

            // Prepare variables for binding to avoid "passed by reference" error
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
            $applicantStmt->bindParam(':ssnum', $ssnum);
            $applicantStmt->bindParam(':lname', $lname);
            $applicantStmt->bindParam(':fname', $fname);
            $applicantStmt->bindParam(':mname', $mname);
            $applicantStmt->bindParam(':sfx', $sfx);
            $applicantStmt->bindParam(':dbirth', $formattedDbirth);
            $applicantStmt->bindParam(':sex', $sex);
            $applicantStmt->bindParam(':cvstatus', $cvstatus);
            $applicantStmt->bindParam(':cvstatus_other', $cvstatus_other);
            $applicantStmt->bindParam(':taxid', $taxid);
            $applicantStmt->bindParam(':nation', $nation);
            $applicantStmt->bindParam(':religion', $religion);
            $applicantStmt->bindParam(':pbirth', $pbirth);
            $applicantStmt->bindParam(':cphone', $cphone);
            $applicantStmt->bindParam(':email', $email);
            $applicantStmt->bindParam(':tphone', $tphone);
            $applicantStmt->bindParam(':printed_name', $printed_name);
            $applicantStmt->bindParam(':cert_date', $formattedCertDate);

            $applicantStmt->execute();
            $applicantId = $conn->lastInsertId();

            // Insert address data
            $addressSql = "INSERT INTO applicant_addresses (
                applicant_id, address_1, address_2, address_3, address_4, address_5,
                address_6, address_7, address_8, address_9, same_as_pbirth
            ) VALUES (
                :applicant_id, :address_1, :address_2, :address_3, :address_4, :address_5,
                :address_6, :address_7, :address_8, :address_9, :same_as_pbirth
            )";

            $addressStmt = $conn->prepare($addressSql);

            // Prepare variables for binding to avoid "passed by reference" error
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

            $addressStmt->bindParam(':applicant_id', $applicantId);
            $addressStmt->bindParam(':address_1', $address_1);
            $addressStmt->bindParam(':address_2', $address_2);
            $addressStmt->bindParam(':address_3', $address_3);
            $addressStmt->bindParam(':address_4', $address_4);
            $addressStmt->bindParam(':address_5', $address_5);
            $addressStmt->bindParam(':address_6', $address_6);
            $addressStmt->bindParam(':address_7', $address_7);
            $addressStmt->bindParam(':address_8', $address_8);
            $addressStmt->bindParam(':address_9', $address_9);
            $addressStmt->bindParam(':same_as_pbirth', $same_as_pbirth);
            $addressStmt->execute();

            // Insert parents data
            $parentsSql = "INSERT INTO applicant_parents (
                applicant_id, lfather, ffather, mfather, sfxfather, fbirth,
                lmother, fmother, mmother, sfxmother, mbirth
            ) VALUES (
                :applicant_id, :lfather, :ffather, :mfather, :sfxfather, :fbirth,
                :lmother, :fmother, :mmother, :sfxmother, :mbirth
            )";

            $parentsStmt = $conn->prepare($parentsSql);

            // Prepare variables for binding to avoid "passed by reference" error
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

            $parentsStmt->bindParam(':applicant_id', $applicantId);
            $parentsStmt->bindParam(':lfather', $lfather);
            $parentsStmt->bindParam(':ffather', $ffather);
            $parentsStmt->bindParam(':mfather', $mfather);
            $parentsStmt->bindParam(':sfxfather', $sfxfather);
            $parentsStmt->bindParam(':fbirth', $formattedFbirth);
            $parentsStmt->bindParam(':lmother', $lmother);
            $parentsStmt->bindParam(':fmother', $fmother);
            $parentsStmt->bindParam(':mmother', $mmother);
            $parentsStmt->bindParam(':sfxmother', $sfxmother);
            $parentsStmt->bindParam(':mbirth', $formattedMbirth);

            $parentsStmt->execute();

            // Insert spouse data if provided
            if (!empty(trim($_POST['lspouse'] ?? '')) || !empty(trim($_POST['fspouse'] ?? ''))) {
                $spouseSql = "INSERT INTO applicant_spouse (
                    applicant_id, lspouse, fspouse, mspouse, sfxspouse, sbirth
                ) VALUES (
                    :applicant_id, :lspouse, :fspouse, :mspouse, :sfxspouse, :sbirth
                )";

                $spouseStmt = $conn->prepare($spouseSql);

                // Prepare variables for binding to avoid "passed by reference" error
                $lspouse = trim($_POST['lspouse'] ?? '');
                $fspouse = trim($_POST['fspouse'] ?? '');
                $mspouse = trim($_POST['mspouse'] ?? '');
                $sfxspouse = trim($_POST['sfxspouse'] ?? '');

                // Format date fields to ensure they're in YYYY-MM-DD format for DATE type
                $formattedSbirth = !empty($_POST['sbirth']) ? date('Y-m-d', strtotime($_POST['sbirth'])) : null;

                $spouseStmt->bindParam(':applicant_id', $applicantId);
                $spouseStmt->bindParam(':lspouse', $lspouse);
                $spouseStmt->bindParam(':fspouse', $fspouse);
                $spouseStmt->bindParam(':mspouse', $mspouse);
                $spouseStmt->bindParam(':sfxspouse', $sfxspouse);
                $spouseStmt->bindParam(':sbirth', $formattedSbirth);

                $spouseStmt->execute();
            }

            // Insert children data
            if (isset($_POST['children']) && is_array($_POST['children'])) {
                $childSql = "INSERT INTO applicant_children (
                    applicant_id, lname, fname, mname, sfx, dbirth
                ) VALUES (
                    :applicant_id, :lname, :fname, :mname, :sfx, :dbirth
                )";
                
                $childStmt = $conn->prepare($childSql);

                foreach ($_POST['children'] as $child) {
                    if (!empty($child['lname']) || !empty($child['fname'])) {
                        // Format child date of birth to ensure it's in YYYY-MM-DD format for DATE type
                        $formattedChildDbirth = !empty($child['dbirth']) ? date('Y-m-d', strtotime($child['dbirth'])) : null;
                        
                        $childStmt->execute([
                            ':applicant_id' => $applicantId,
                            ':lname' => trim($child['lname'] ?? ''),
                            ':fname' => trim($child['fname'] ?? ''),
                            ':mname' => trim($child['mname'] ?? ''),
                            ':sfx' => trim($child['sfx'] ?? ''),
                            ':dbirth' => $formattedChildDbirth
                        ]);
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
                ) VALUES (
                    :applicant_id, :employment_type, :profession, :ystart, :mearning,
                    :faddress, :ofw_monthly_earnings, :spouse_ssnum, :ffprogram, :ffp
                )";

                $employmentStmt = $conn->prepare($employmentSql);

                // Prepare variables for binding to avoid "passed by reference" error
                $profession = trim($_POST['profession'] ?? '');
                $ystart = trim($_POST['ystart'] ?? '');
                $mearning = trim($_POST['mearning'] ?? '');
                $faddress = trim($_POST['faddress'] ?? '');
                $ofw_monthly_earnings = trim($_POST['ofw_monthly_earnings'] ?? '');
                $spouse_ssnum = trim($_POST['spouse-ssnum'] ?? '');
                $ffprogram = trim($_POST['ffprogram'] ?? '');
                $ffp = trim($_POST['ffp'] ?? '');

                $employmentStmt->bindParam(':applicant_id', $applicantId);
                $employmentStmt->bindParam(':employment_type', $employmentType);
                $employmentStmt->bindParam(':profession', $profession);
                $employmentStmt->bindParam(':ystart', $ystart);
                $employmentStmt->bindParam(':mearning', $mearning);
                $employmentStmt->bindParam(':faddress', $faddress);
                $employmentStmt->bindParam(':ofw_monthly_earnings', $ofw_monthly_earnings);
                $employmentStmt->bindParam(':spouse_ssnum', $spouse_ssnum);
                $employmentStmt->bindParam(':ffprogram', $ffprogram);
                $employmentStmt->bindParam(':ffp', $ffp);
                $employmentStmt->execute();
            }

            $conn->commit();
            echo "<script>sessionStorage.setItem('formSuccess', 'true'); window.location.href='page1.html';</script>";
            exit();

        } catch(PDOException $e) {
            $conn->rollBack();
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
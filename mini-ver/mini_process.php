<?php
// Database connection
function connectDB() {
    $host = 'localhost';
    $dbname = 'ws310_mini_db';
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

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    $address_6 = trim($_POST['address_6'] ?? '');
    if (!validateRequired($address_6)) {
        $errors[] = "City is required";
    }

    $address_7 = trim($_POST['address_7'] ?? '');
    if (!validateRequired($address_7)) {
        $errors[] = "Province is required";
    }

    // If no errors, process the data
    if (empty($errors)) {
        try {
            $conn = connectDB();
            $conn->beginTransaction();

            // Insert applicant data
            $applicantSql = "INSERT INTO applicants (
                ssnum, lname, fname
            ) VALUES (
                :ssnum, :lname, :fname
            )";

            $applicantStmt = $conn->prepare($applicantSql);

            $applicantStmt->bindParam(':ssnum', $ssnum);
            $applicantStmt->bindParam(':lname', $lname);
            $applicantStmt->bindParam(':fname', $fname);

            $applicantStmt->execute();
            $applicantId = $conn->lastInsertId();

            // Insert address data
            $addressSql = "INSERT INTO applicant_addresses (
                applicant_id, address_6, address_7
            ) VALUES (
                :applicant_id, :address_6, :address_7
            )";

            $addressStmt = $conn->prepare($addressSql);

            $addressStmt->bindParam(':applicant_id', $applicantId);
            $addressStmt->bindParam(':address_6', $address_6);
            $addressStmt->bindParam(':address_7', $address_7);

            $addressStmt->execute();

            $conn->commit();
            echo "<script>sessionStorage.setItem('formSuccess', 'true'); window.location.href='mini_form.html';</script>";
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
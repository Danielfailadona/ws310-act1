<?php
// Test database connection
require_once 'usables/usables.php';

try {
    $conn = getConnection();
    if ($conn) {
        echo "Database connection successful!\n";
        
        // Test if the applicants table exists
        $stmt = $conn->prepare("SHOW TABLES LIKE 'applicants'");
        $stmt->execute();
        $result = $stmt->fetch();
        
        if ($result) {
            echo "Table 'applicants' exists.\n";
            
            // Count records in the table
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM applicants");
            $stmt->execute();
            $count = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "Number of records in applicants table: " . $count['count'] . "\n";
            
            // Get first few records to see the structure
            if ($count['count'] > 0) {
                $stmt = $conn->prepare("SELECT * FROM applicants LIMIT 2");
                $stmt->execute();
                $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo "Sample records:\n";
                print_r($records);
            }
        } else {
            echo "Table 'applicants' does not exist.\n";
        }
    } else {
        echo "Database connection failed.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

// Test the Usables class
try {
    $usables = new Usables();
    $records = $usables->readRecords();
    echo "\nTesting Usables->readRecords():\n";
    if (isset($records['error'])) {
        echo "Error: " . $records['error'] . "\n";
    } else {
        echo "Number of records returned: " . count($records) . "\n";
        if (count($records) > 0) {
            echo "First record: ";
            print_r($records[0]);
        }
    }
} catch (Exception $e) {
    echo "Usables error: " . $e->getMessage() . "\n";
}
?>
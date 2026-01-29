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

// Handle different CRUD operations based on the action parameter
$action = $_GET['action'] ?? 'view';

switch($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ssnum = $_POST['ssnum'] ?? '';
            $fname = $_POST['fname'] ?? '';
            $lname = $_POST['lname'] ?? '';
            $city = $_POST['city'] ?? '';
            $province = $_POST['province'] ?? '';

            $conn = connectDB();
            $conn->beginTransaction();

            try {
                // Insert applicant data
                $stmt = $conn->prepare("INSERT INTO applicants (ssnum, lname, fname) VALUES (?, ?, ?)");
                $stmt->execute([$ssnum, $lname, $fname]);
                $applicantId = $conn->lastInsertId();

                // Insert address data
                $addrStmt = $conn->prepare("INSERT INTO applicant_addresses (applicant_id, address_6, address_7) VALUES (?, ?, ?)");
                $addrStmt->execute([$applicantId, $city, $province]);

                $conn->commit();
                echo json_encode(['success' => true, 'message' => 'Record created successfully']);
            } catch (PDOException $e) {
                $conn->rollBack();
                echo json_encode(['success' => false, 'message' => 'Failed to create record: ' . $e->getMessage()]);
            }
        }
        break;

    case 'read':
        $conn = connectDB();
        $stmt = $conn->prepare("
            SELECT a.applicant_id as id, a.fname, a.lname, a.ssnum, ad.address_6 as city, ad.address_7 as province
            FROM applicants a
            LEFT JOIN applicant_addresses ad ON a.applicant_id = ad.applicant_id
            ORDER BY a.applicant_id DESC
        ");
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($results);
        break;

    case 'read_single':
        $id = $_GET['id'] ?? '';

        $conn = connectDB();

        // Get main applicant data
        $stmt = $conn->prepare("
            SELECT *
            FROM applicants
            WHERE applicant_id = ?
        ");
        $stmt->execute([$id]);
        $applicant = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($applicant) {
            // Get address data
            $addrStmt = $conn->prepare("
                SELECT *
                FROM applicant_addresses
                WHERE applicant_id = ?
            ");
            $addrStmt->execute([$id]);
            $address = $addrStmt->fetch(PDO::FETCH_ASSOC);

            // Combine all data
            $result = [
                'applicant' => $applicant,
                'address' => $address
            ];
        } else {
            $result = null;
        }

        header('Content-Type: application/json');
        echo json_encode($result);
        break;

    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $ssnum = $_POST['ssnum'] ?? '';
            $fname = $_POST['fname'] ?? '';
            $lname = $_POST['lname'] ?? '';
            $city = $_POST['city'] ?? '';
            $province = $_POST['province'] ?? '';

            $conn = connectDB();
            $conn->beginTransaction();

            try {
                // Update applicant data
                $stmt = $conn->prepare("UPDATE applicants SET ssnum=?, lname=?, fname=? WHERE applicant_id=?");
                $stmt->execute([$ssnum, $lname, $fname, $id]);

                // Update address data
                $addrStmt = $conn->prepare("UPDATE applicant_addresses SET address_6=?, address_7=? WHERE applicant_id=?");
                $addrStmt->execute([$city, $province, $id]);

                $conn->commit();
                echo json_encode(['success' => true, 'message' => 'Record updated successfully']);
            } catch (PDOException $e) {
                $conn->rollBack();
                echo json_encode(['success' => false, 'message' => 'Failed to update record: ' . $e->getMessage()]);
            }
        }
        break;

    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';

            $conn = connectDB();

            try {
                // Delete from both tables due to foreign key constraint
                $conn->beginTransaction();

                // Delete address record first (due to foreign key constraint)
                $addrStmt = $conn->prepare("DELETE FROM applicant_addresses WHERE applicant_id = ?");
                $addrStmt->execute([$id]);

                // Then delete applicant record
                $stmt = $conn->prepare("DELETE FROM applicants WHERE applicant_id = ?");
                $result = $stmt->execute([$id]);

                $conn->commit();

                if ($result) {
                    echo json_encode(['success' => true, 'message' => 'Record deleted successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to delete record']);
                }
            } catch (PDOException $e) {
                $conn->rollBack();
                echo json_encode(['success' => false, 'message' => 'Failed to delete record: ' . $e->getMessage()]);
            }
        }
        break;

    default:
        // Display the CRUD interface
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Mini CRUD Table</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                    background-color: #f5f5f5;
                }
                .main {
                    max-width: 1000px;
                    margin: 0 auto;
                    background: white;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                }
                th, td {
                    padding: 12px;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                }
                th {
                    background-color: #f2f2f2;
                }
                .btn {
                    padding: 8px 12px;
                    margin: 2px;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    text-decoration: none;
                    display: inline-block;
                }
                .btn-edit { background-color: #007bff; color: white; }
                .btn-del { background-color: #dc3545; color: white; }
                .btn-add { background-color: #28a745; color: white; }
                .btn-download { background-color: #6c757d; color: white; }
                .footer-actions {
                    margin: 20px 0;
                }

                /* Modal styles */
                .modal {
                    display: none;
                    position: fixed;
                    z-index: 1;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0,0,0,0.4);
                }

                .modal-content {
                    background-color: #fefefe;
                    margin: 15% auto;
                    padding: 20px;
                    border: 1px solid #888;
                    width: 40%;
                    border-radius: 8px;
                }

                .form-group {
                    margin-bottom: 15px;
                }

                .form-group label {
                    display: block;
                    margin-bottom: 5px;
                    font-weight: bold;
                }

                .form-group input {
                    width: 100%;
                    padding: 8px;
                    border: 1px solid #ddd;
                    border-radius: 4px;
                    box-sizing: border-box;
                }

                .form-actions {
                    margin-top: 20px;
                    text-align: right;
                }
            </style>
        </head>
        <body>

        <div class="main">
            <h1>Mini SSS Records</h1>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>SS Number</th>
                        <th>City</th>
                        <th>Province</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                </tbody>
            </table>

            <div class="footer-actions">
                <button class="btn btn-add" onclick="openAddModal()">Add New Record</button>
            </div>
        </div>

        <!-- Modal for Add/Edit -->
        <div id="modal" class="modal">
            <div class="modal-content">
                <h3 id="modal-title">Add New Record</h3>
                <form id="record-form">
                    <input type="hidden" id="record-id">

                    <div class="form-group">
                        <label for="ssnum">SS Number:</label>
                        <input type="text" id="ssnum" required>
                    </div>

                    <div class="form-group">
                        <label for="fname">First Name:</label>
                        <input type="text" id="fname" required>
                    </div>

                    <div class="form-group">
                        <label for="lname">Last Name:</label>
                        <input type="text" id="lname" required>
                    </div>

                    <div class="form-group">
                        <label for="city">City:</label>
                        <input type="text" id="city" required>
                    </div>

                    <div class="form-group">
                        <label for="province">Province:</label>
                        <input type="text" id="province" required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-add">Save</button>
                        <button type="button" class="btn btn-del" onclick="closeModal()">Cancel</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            let userData = [];

            // Load data from server
            async function loadData() {
                try {
                    const response = await fetch('mini_crud.php?action=read');
                    userData = await response.json();
                    renderTable();
                } catch (error) {
                    console.error('Error loading data:', error);
                }
            }

            const tableBody = document.getElementById('table-body');

            // Render function using forEach
            function renderTable() {
                // Clear existing content
                tableBody.innerHTML = "";

                // Loop through each item in the array
                userData.forEach((user) => {
                    const row = `
                        <tr>
                            <td>${user.id}</td>
                            <td>${user.fname}</td>
                            <td>${user.lname}</td>
                            <td>${user.ssnum}</td>
                            <td>${user.city || 'N/A'}</td>
                            <td>${user.province || 'N/A'}</td>
                            <td>
                                <button class="btn btn-edit" onclick="editRecord(${user.id})">Edit</button>
                                <button class="btn btn-del" onclick="deleteRecord(${user.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            }

            // Open modal for adding new record
            function openAddModal() {
                document.getElementById('modal-title').textContent = 'Add New Record';
                document.getElementById('record-form').reset();
                document.getElementById('record-id').value = '';
                document.getElementById('modal').style.display = 'block';
            }

            // Populate form and show modal for editing
            async function editRecord(id) {
                try {
                    const response = await fetch(`mini_crud.php?action=read_single&id=${id}`);
                    const data = await response.json();

                    if (data) {
                        document.getElementById('modal-title').textContent = 'Edit Record';
                        document.getElementById('record-id').value = id;
                        document.getElementById('ssnum').value = data.applicant.ssnum || '';
                        document.getElementById('fname').value = data.applicant.fname || '';
                        document.getElementById('lname').value = data.applicant.lname || '';
                        document.getElementById('city').value = data.address?.address_6 || '';
                        document.getElementById('province').value = data.address?.address_7 || '';

                        document.getElementById('modal').style.display = 'block';
                    } else {
                        alert('Record not found');
                    }
                } catch (error) {
                    console.error('Error loading record:', error);
                    alert('Error loading record');
                }
            }

            // Delete record
            async function deleteRecord(id) {
                if (confirm("Are you sure you want to delete this record?")) {
                    try {
                        const formData = new FormData();
                        formData.append('id', id);

                        const response = await fetch('mini_crud.php?action=delete', {
                            method: 'POST',
                            body: formData
                        });

                        const result = await response.json();

                        if (result.success) {
                            alert("Record deleted successfully");
                            loadData(); // Reload data
                        } else {
                            alert("Error: " + result.message);
                        }
                    } catch (error) {
                        console.error('Error deleting record:', error);
                        alert("Error deleting record");
                    }
                }
            }

            // Close modal
            function closeModal() {
                document.getElementById('modal').style.display = 'none';
            }

            // Handle form submission
            document.getElementById('record-form').addEventListener('submit', async function(e) {
                e.preventDefault();

                const id = document.getElementById('record-id').value;
                const ssnum = document.getElementById('ssnum').value;
                const fname = document.getElementById('fname').value;
                const lname = document.getElementById('lname').value;
                const city = document.getElementById('city').value;
                const province = document.getElementById('province').value;

                const formData = new FormData();
                formData.append('id', id);
                formData.append('ssnum', ssnum);
                formData.append('fname', fname);
                formData.append('lname', lname);
                formData.append('city', city);
                formData.append('province', province);

                try {
                    const url = id ? 'mini_crud.php?action=update' : 'mini_crud.php?action=create';
                    const response = await fetch(url, {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        alert(id ? "Record updated successfully" : "Record created successfully");
                        closeModal();
                        loadData(); // Reload data
                    } else {
                        alert("Error: " + result.message);
                    }
                } catch (error) {
                    console.error('Error saving record:', error);
                    alert("Error saving record");
                }
            });

            // Close modal when clicking outside of it
            window.onclick = function(event) {
                const modal = document.getElementById('modal');
                if (event.target == modal) {
                    closeModal();
                }
            }

            // Initial call to display the data
            loadData();
        </script>

        </body>
        </html>
        <?php
        break;
}
?>
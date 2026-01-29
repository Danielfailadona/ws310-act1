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

// Handle different CRUD operations based on the action parameter
$action = $_GET['action'] ?? 'view';

switch($action) {
    case 'create':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $first = $_POST['first'] ?? '';
            $last = $_POST['last'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $location = $_POST['location'] ?? '';
            $hobby = $_POST['hobby'] ?? '';

            $conn = connectDB();
            $stmt = $conn->prepare("INSERT INTO applicants (fname, lname, email, cphone, nation, religion) VALUES (?, ?, ?, ?, ?, ?)");
            $result = $stmt->execute([$first, $last, $email, $phone, $location, $hobby]);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Record created successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to create record']);
            }
        }
        break;

    case 'read':
        $conn = connectDB();
        $stmt = $conn->prepare("
            SELECT applicant_id as id, fname as first, lname as last, email, cphone as phone, nation as location, religion as hobby
            FROM applicants
            ORDER BY applicant_id DESC
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

            // Get parents data
            $parentStmt = $conn->prepare("
                SELECT *
                FROM applicant_parents
                WHERE applicant_id = ?
            ");
            $parentStmt->execute([$id]);
            $parents = $parentStmt->fetch(PDO::FETCH_ASSOC);

            // Get spouse data
            $spouseStmt = $conn->prepare("
                SELECT *
                FROM applicant_spouse
                WHERE applicant_id = ?
            ");
            $spouseStmt->execute([$id]);
            $spouse = $spouseStmt->fetch(PDO::FETCH_ASSOC);

            // Get children data
            $childStmt = $conn->prepare("
                SELECT *
                FROM applicant_children
                WHERE applicant_id = ?
            ");
            $childStmt->execute([$id]);
            $children = $childStmt->fetchAll(PDO::FETCH_ASSOC);

            // Get employment data
            $empStmt = $conn->prepare("
                SELECT *
                FROM applicant_employment
                WHERE applicant_id = ?
            ");
            $empStmt->execute([$id]);
            $employment = $empStmt->fetch(PDO::FETCH_ASSOC);

            // Debug output - show what data was retrieved from each table
            $debug_info = [
                'tables_queried' => [
                    'applicants' => $applicant ? 'FOUND' : 'NOT FOUND',
                    'applicant_addresses' => $address ? 'FOUND' : 'NOT FOUND',
                    'applicant_parents' => $parents ? 'FOUND' : 'NOT FOUND',
                    'applicant_spouse' => $spouse ? 'FOUND' : 'NOT FOUND',
                    'applicant_children' => count($children) > 0 ? 'FOUND (' . count($children) . ' records)' : 'NOT FOUND',
                    'applicant_employment' => $employment ? 'FOUND' : 'NOT FOUND'
                ],
                'data_summary' => [
                    'applicant_id' => $applicant['applicant_id'] ?? null,
                    'applicant_name' => ($applicant['fname'] ?? '') . ' ' . ($applicant['lname'] ?? ''),
                    'address_count' => $address ? 1 : 0,
                    'parent_data' => $parents ? 'Available' : 'Not Available',
                    'spouse_data' => $spouse ? 'Available' : 'Not Available',
                    'children_count' => count($children),
                    'employment_data' => $employment ? 'Available' : 'Not Available'
                ],
                'actual_data' => [
                    'applicant' => $applicant,
                    'address' => $address,
                    'parents' => $parents,
                    'spouse' => $spouse,
                    'children' => $children,
                    'employment' => $employment
                ]
            ];

            // Output debug information as a comment in the JSON response
            // Uncomment the next line to see debug info in browser console
            // error_log("DEBUG INFO: " . json_encode($debug_info));

            // Combine all data
            $result = [
                'applicant' => $applicant,
                'address' => $address,
                'parents' => $parents,
                'spouse' => $spouse,
                'children' => $children,
                'employment' => $employment,
                'debug_info' => $debug_info  // Include debug info in response
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
            $first = $_POST['first'] ?? '';
            $last = $_POST['last'] ?? '';
            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $location = $_POST['location'] ?? '';
            $hobby = $_POST['hobby'] ?? '';

            $conn = connectDB();
            $stmt = $conn->prepare("UPDATE applicants SET fname=?, lname=?, email=?, cphone=?, nation=?, religion=? WHERE applicant_id=?");
            $result = $stmt->execute([$first, $last, $email, $phone, $location, $hobby, $id]);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Record updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update record']);
            }
        }
        break;

    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';

            $conn = connectDB();
            $stmt = $conn->prepare("DELETE FROM applicants WHERE applicant_id = ?");
            $result = $stmt->execute([$id]);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Record deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete record']);
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
            <title>Dynamic CRUD Table</title>
            <link rel="stylesheet" href="crud.css">
        </head>
        <body>

        <div class="main">
            <h1>SSS Online Form</h1>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First</th>
                        <th>Last</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Location</th>
                        <th>Hobby</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    </tbody>
            </table>

            <div class="footer-actions">
                <button class="btn btn-download" onclick="downloadCSV()">Download CSV</button>
                <button class="btn btn-add" onclick="addItem()">Add Item</button>
            </div>
        </div>

        <!-- Modal for Add/Edit -->
        <div id="modal" class="modal" style="display:none; position:fixed; z-index:1; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.4);">
            <div class="modal-content" style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 40%;">
                <h3 id="modal-title">Add New Item</h3>
                <form id="item-form">
                    <input type="hidden" id="item-id">
                    <p><label>First Name: <input type="text" id="first" required></label></p>
                    <p><label>Last Name: <input type="text" id="last" required></label></p>
                    <p><label>Email: <input type="email" id="email" required></label></p>
                    <p><label>Phone: <input type="text" id="phone"></label></p>
                    <p><label>Location: <input type="text" id="location"></label></p>
                    <p><label>Hobby: <input type="text" id="hobby"></label></p>
                    <p>
                        <button type="submit" class="btn btn-add">Save</button>
                        <button type="button" class="btn btn-del" onclick="closeModal()">Cancel</button>
                    </p>
                </form>
            </div>
        </div>

        <script>
            let userData = [];

            // Load data from server
            async function loadData() {
                try {
                    const response = await fetch('crud.php?action=read');
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
                            <td class="id-col">${user.id}</td>
                            <td>${user.first}</td>
                            <td>${user.last}</td>
                            <td>${user.email}</td>
                            <td>${user.phone}</td>
                            <td>${user.location}</td>
                            <td>${user.hobby}</td>
                            <td>
                                <button class="btn btn-edit" onclick="editItem(${user.id})">Edit</button>
                                <button class="btn btn-del" onclick="deleteItem(${user.id})">Del</button>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });
            }

            // Show modal for adding new item
            function addItem() {
                document.getElementById('modal-title').textContent = 'Add New Item';
                document.getElementById('item-form').reset();
                document.getElementById('item-id').value = '';
                document.getElementById('modal').style.display = 'block';
            }

            // Populate form and show modal for editing
            function editItem(id) {
                const user = userData.find(u => u.id == id);
                if (user) {
                    document.getElementById('modal-title').textContent = 'Edit Item';
                    document.getElementById('item-id').value = user.id;
                    document.getElementById('first').value = user.first;
                    document.getElementById('last').value = user.last;
                    document.getElementById('email').value = user.email;
                    document.getElementById('phone').value = user.phone || '';
                    document.getElementById('location').value = user.location || '';
                    document.getElementById('hobby').value = user.hobby || '';
                    document.getElementById('modal').style.display = 'block';
                }
            }

            // Delete item
            async function deleteItem(id) {
                if (confirm("Are you sure you want to delete ID: " + id + "?")) {
                    try {
                        const response = await fetch('crud.php?action=delete', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'id=' + encodeURIComponent(id)
                        });

                        const result = await response.json();

                        if (result.success) {
                            alert("Record deleted successfully");
                            loadData(); // Reload data
                        } else {
                            alert("Error: " + result.message);
                        }
                    } catch (error) {
                        console.error('Error deleting item:', error);
                        alert("Error deleting record");
                    }
                }
            }

            // Download CSV
            function downloadCSV() {
                let csv = 'ID,First,Last,Email,Phone,Location,Hobby\n';
                userData.forEach(user => {
                    csv += `"${user.id}","${user.first}","${user.last}","${user.email}","${user.phone}","${user.location}","${user.hobby}"\n`;
                });

                const blob = new Blob([csv], { type: 'text/csv' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'data.csv';
                a.click();
            }

            // Close modal
            function closeModal() {
                document.getElementById('modal').style.display = 'none';
            }

            // Handle form submission
            document.getElementById('item-form').addEventListener('submit', async function(e) {
                e.preventDefault();

                const id = document.getElementById('item-id').value;
                const first = document.getElementById('first').value;
                const last = document.getElementById('last').value;
                const email = document.getElementById('email').value;
                const phone = document.getElementById('phone').value;
                const location = document.getElementById('location').value;
                const hobby = document.getElementById('hobby').value;

                const formData = new FormData();
                formData.append('id', id);
                formData.append('first', first);
                formData.append('last', last);
                formData.append('email', email);
                formData.append('phone', phone);
                formData.append('location', location);
                formData.append('hobby', hobby);

                try {
                    const url = id ? 'crud.php?action=update' : 'crud.php?action=create';
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
                    console.error('Error saving item:', error);
                    alert("Error saving record");
                }
            });

            // Initial call to display the data
            loadData();
        </script>

        </body>
        </html>
        <?php
        break;
}
?>
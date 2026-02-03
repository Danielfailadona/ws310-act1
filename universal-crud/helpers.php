<?php
// Include the Universal CRUD class
require_once 'UniversalCRUD.php';

/**
 * Helper functions for common CRUD operations
 * These functions provide simplified interfaces for common tasks
 */

/**
 * Delete records from any table based on conditions
 * @param string $table The table name
 * @param array $conditions Conditions for WHERE clause (column => value pairs)
 * @return bool True on success, false on failure
 */
function deleteItem($table, $conditions) {
    try {
        $crud = new UniversalCRUD($table);
        return $crud->delete($conditions);
    } catch (Exception $e) {
        error_log("Delete error: " . $e->getMessage());
        return false;
    }
}

/**
 * Delete a record by ID from any table
 * @param string $table The table name
 * @param mixed $id The ID value to delete
 * @param string $idColumn The name of the ID column (defaults to 'id')
 * @return bool True on success, false on failure
 */
function deleteItemById($table, $id, $idColumn = 'id') {
    return deleteItem($table, [$idColumn => $id]);
}

/**
 * Get specific data from any table
 * @param string $table The table name
 * @param array $columns Array of column names to retrieve (use '*' for all columns)
 * @param array $conditions Conditions for WHERE clause (column => value pairs)
 * @param string $orderBy Column to order by
 * @param string $orderDirection Direction (ASC or DESC)
 * @param int $limit Number of records to return
 * @param int $offset Offset for pagination
 * @return array Array of records matching the criteria
 */
function getData($table, $columns = ['*'], $conditions = [], $orderBy = '', $orderDirection = 'ASC', $limit = 0, $offset = 0) {
    try {
        $crud = new UniversalCRUD($table);

        // Get records with all parameters
        $allRecords = $crud->read($conditions, $orderBy, $orderDirection, $limit, $offset);

        // If columns is ['*'] or empty, return all records as-is
        if (empty($columns) || (count($columns) == 1 && $columns[0] == '*')) {
            return $allRecords;
        }

        // Filter to only requested columns
        $filteredData = [];
        foreach ($allRecords as $record) {
            $filteredRecord = [];
            foreach ($columns as $column) {
                if (isset($record[$column])) {
                    $filteredRecord[$column] = $record[$column];
                } else {
                    $filteredRecord[$column] = null; // Add null if column doesn't exist
                }
            }
            $filteredData[] = $filteredRecord;
        }
        return $filteredData;
    } catch (Exception $e) {
        error_log("GetData error: " . $e->getMessage());
        return [];
    }
}

/**
 * Get a single record by ID
 * @param string $table The table name
 * @param mixed $id The ID value to search for
 * @param string $idColumn The name of the ID column (defaults to 'id')
 * @param array $columns Array of column names to retrieve (use '*' for all columns)
 * @return array|null Single record or null if not found
 */
function getItemById($table, $id, $idColumn = 'id', $columns = ['*']) {
    try {
        $crud = new UniversalCRUD($table);
        $record = $crud->readOne($id, $idColumn);

        if ($record && (!empty($columns) && !(count($columns) == 1 && $columns[0] == '*'))) {
            // Filter to only requested columns
            $filteredRecord = [];
            foreach ($columns as $column) {
                if (isset($record[$column])) {
                    $filteredRecord[$column] = $record[$column];
                } else {
                    $filteredRecord[$column] = null;
                }
            }
            return $filteredRecord;
        }

        return $record;
    } catch (Exception $e) {
        error_log("GetItemById error: " . $e->getMessage());
        return null;
    }
}

/**
 * Create a new record in any table
 * @param string $table The table name
 * @param array $data Associative array of column => value pairs
 * @param bool $returnRecord Whether to return the created record with its new ID
 * @return int|array|bool ID of created record, full record if $returnRecord=true, or false on failure
 */
function createItem($table, $data, $returnRecord = false) {
    try {
        $crud = new UniversalCRUD($table);
        $id = $crud->create($data);

        if ($returnRecord && $id) {
            return getItemById($table, $id, $table . '_id', ['*']); // Return full record
        }

        return $id;
    } catch (Exception $e) {
        error_log("CreateItem error: " . $e->getMessage());
        return false;
    }
}

/**
 * Update a record in any table
 * @param string $table The table name
 * @param array $data Associative array of column => new_value pairs
 * @param array $conditions Conditions for WHERE clause (column => value pairs)
 * @param bool $returnUpdated Whether to return the updated record
 * @return bool|array True on success, updated record if $returnUpdated=true, false on failure
 */
function updateItem($table, $data, $conditions, $returnUpdated = false) {
    try {
        $crud = new UniversalCRUD($table);
        $success = $crud->update($data, $conditions);

        if ($success && $returnUpdated && !empty($conditions) && isset($conditions[key($conditions)])) {
            // Try to return updated record - need to determine which condition to use for lookup
            $firstConditionKey = key($conditions);
            $firstConditionValue = $conditions[$firstConditionKey];
            return getItemById($table, $firstConditionValue, $firstConditionKey, ['*']);
        }

        return $success;
    } catch (Exception $e) {
        error_log("UpdateItem error: " . $e->getMessage());
        return false;
    }
}

/**
 * Count records in any table with optional conditions
 * @param string $table The table name
 * @param array $conditions Optional conditions for WHERE clause
 * @return int Number of records
 */
function countItems($table, $conditions = []) {
    try {
        $crud = new UniversalCRUD($table);
        return $crud->count($conditions);
    } catch (Exception $e) {
        error_log("CountItems error: " . $e->getMessage());
        return 0;
    }
}

/**
 * Search records in any table
 * @param string $table The table name
 * @param array $searchFields Associative array of column => search_value pairs
 * @param string $matchType Type of match ('any' for OR, 'all' for AND)
 * @param array $columns Array of column names to retrieve (use '*' for all columns)
 * @return array Array of matching records
 */
function searchItems($table, $searchFields, $matchType = 'any', $columns = ['*']) {
    try {
        $crud = new UniversalCRUD($table);
        $results = $crud->search($searchFields, $matchType);

        // If columns is ['*'] or empty, return all records as-is
        if (empty($columns) || (count($columns) == 1 && $columns[0] == '*')) {
            return $results;
        }

        // Filter to only requested columns
        $filteredResults = [];
        foreach ($results as $record) {
            $filteredRecord = [];
            foreach ($columns as $column) {
                if (isset($record[$column])) {
                    $filteredRecord[$column] = $record[$column];
                } else {
                    $filteredRecord[$column] = null;
                }
            }
            $filteredResults[] = $filteredRecord;
        }
        return $filteredResults;
    } catch (Exception $e) {
        error_log("SearchItems error: " . $e->getMessage());
        return [];
    }
}

/**
 * Get all records from a table with optional filtering
 * @param string $table The table name
 * @param array $columns Array of column names to retrieve (use '*' for all columns)
 * @param string $orderBy Column to order by
 * @param string $orderDirection Direction (ASC or DESC)
 * @param int $limit Number of records to return
 * @param int $offset Offset for pagination
 * @return array Array of all records
 */
function getAllItems($table, $columns = ['*'], $orderBy = '', $orderDirection = 'ASC', $limit = 0, $offset = 0) {
    return getData($table, $columns, [], $orderBy, $orderDirection, $limit, $offset);
}

// Example usage:
/*
// DELETE OPERATIONS
// Delete an applicant by ID
$deleted = deleteItemById('applicants', 123, 'applicant_id');
if ($deleted) {
    echo "Applicant deleted successfully";
}

// Delete records based on conditions
$deleted = deleteItem('applicants', ['lname' => 'DOE', 'fname' => 'JOHN']);
if ($deleted) {
    echo "Applicants deleted successfully";
}

// READ OPERATIONS
// Get specific data for applicants with certain conditions
$applicants = getData('applicants', ['fname', 'lname', 'ssnum'], ['lname' => 'DOE']);
foreach ($applicants as $applicant) {
    echo $applicant['fname'] . ' ' . $applicant['lname'] . '<br>';
}

// Get all records with specific columns
$allApplicants = getData('applicants', ['fname', 'lname'], [], 'lname', 'ASC', 10, 0);

// Get a single record with specific columns
$singleApplicant = getItemById('applicants', 123, 'applicant_id', ['fname', 'lname', 'ssnum']);

// Get all records from a table
$allRecords = getAllItems('applicants', ['fname', 'lname'], 'lname', 'ASC', 50);

// SEARCH OPERATIONS
// Search for records with partial matching
$results = searchItems('applicants', ['lname' => 'DOE'], 'any', ['fname', 'lname']);

// CREATE OPERATIONS
// Create a new applicant and get back the full record
$newApplicant = createItem('applicants', [
    'ssnum' => '987-654-321',
    'lname' => 'SMITH',
    'fname' => 'JANE'
], true); // true means return the full created record

if ($newApplicant) {
    echo "Created applicant: " . $newApplicant['fname'] . " " . $newApplicant['lname'];
}

// UPDATE OPERATIONS
// Update an applicant and get back the updated record
$updatedApplicant = updateItem('applicants',
    ['fname' => 'JANET'],
    ['applicant_id' => 123],
    true  // true means return the updated record
);
if ($updatedApplicant) {
    echo "Updated applicant: " . $updatedApplicant['fname'];
}

// COUNT OPERATIONS
// Count records with conditions
$totalApplicants = countItems('applicants', ['lname' => 'DOE']);
echo "Total applicants with last name DOE: " . $totalApplicants;

// DYNAMIC FIELD MAPPING
// You can now map fields dynamically:
$first_name = 'fname';
$last_name = 'lname';
$ssnum = 'ssnum';
$dbirth = 'dbirth';

// Then call with mapped fields:
$applicants = getData('applicants', [$first_name, $last_name, $ssnum, $dbirth], ['lname' => 'DOE']);

// Or use in other operations:
$foundApplicant = getItemById('applicants', 123, 'applicant_id', [$first_name, $last_name]);
*/
?>
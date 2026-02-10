/**
 * JS Usables - Reusable JavaScript functions for the WS310-ACT1 project
 * Contains dynamic, reusable functions that can be used across the project
 */

//================================================================================================
//                           getDataFromDatabase
//================================================================================================
/**
 * Fetches data from the database via API
 * @param {string} table - The table name to query
 * @param {Array} columns - Array of columns to select (empty array means all *)
 * @param {Object} where - Object of column => value pairs for WHERE clause
 * @param {string} orderBy - Column to order by
 * @param {string} orderDir - Direction of order (ASC or DESC)
 * @param {number} limit - Limit of records
 * @param {number} offset - Offset for pagination
 * @returns {Promise<Object>} Promise that resolves to the API response
 */
async function getDataFromDatabase(table, columns = [], where = {}, orderBy = '', orderDir = 'ASC', limit = 0, offset = 0) {
    try {
        const params = new URLSearchParams({
            action: 'read',
            table: table,
            columns: JSON.stringify(columns),
            where: JSON.stringify(where),
            orderBy: orderBy,
            orderDir: orderDir,
            limit: limit,
            offset: offset
        });

        const response = await fetch(`/api/database?${params}`);
        const data = await response.json();
        
        return data;
    } catch (error) {
        console.error('Database fetch error:', error);
        return { error: error.message };
    }
}

/*
 * USAGE EXAMPLE:
 * const result = await getDataFromDatabase('users', ['id', 'name'], { status: 'active' });
 * console.log(result);
 */


//================================================================================================
//                           insertDataToDatabase
//================================================================================================
/**
 * Inserts data to the database via API
 * @param {string} table - The table name to insert into
 * @param {Object} data - Object of column => value pairs to insert
 * @returns {Promise<Object>} Promise that resolves to the API response
 */
async function insertDataToDatabase(table, data) {
    try {
        const response = await fetch('/api/database', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'create',
                table: table,
                data: data
            })
        });
        
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Database insert error:', error);
        return { error: error.message };
    }
}

/*
 * USAGE EXAMPLE:
 * const result = await insertDataToDatabase('users', { name: 'John', email: 'john@example.com' });
 * console.log(result);
 */


//================================================================================================
//                           updateDataInDatabase
//================================================================================================
/**
 * Updates data in the database via API
 * @param {string} table - The table name to update
 * @param {Object} data - Object of column => value pairs to update
 * @param {Object} where - Object of column => value pairs for WHERE clause
 * @returns {Promise<Object>} Promise that resolves to the API response
 */
async function updateDataInDatabase(table, data, where) {
    try {
        const response = await fetch('/api/database', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'update',
                table: table,
                data: data,
                where: where
            })
        });
        
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Database update error:', error);
        return { error: error.message };
    }
}

/*
 * USAGE EXAMPLE:
 * const result = await updateDataInDatabase('users', { name: 'John Updated' }, { id: 123 });
 * console.log(result);
 */


//================================================================================================
//                           deleteDataFromDatabase
//================================================================================================
/**
 * Deletes data from the database via API
 * @param {string} table - The table name to delete from
 * @param {Object} where - Object of column => value pairs for WHERE clause
 * @returns {Promise<Object>} Promise that resolves to the API response
 */
async function deleteDataFromDatabase(table, where) {
    try {
        const response = await fetch('/api/database', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'delete',
                table: table,
                where: where
            })
        });
        
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('Database delete error:', error);
        return { error: error.message };
    }
}

/*
 * USAGE EXAMPLE:
 * const result = await deleteDataFromDatabase('users', { id: 123 });
 * console.log(result);
 */


//================================================================================================
//                           getSingleRecord
//================================================================================================
/**
 * Fetches a single record from the database via API
 * @param {string} table - The table name to query
 * @param {number|string} id - The ID of the record to fetch
 * @param {string} idColumn - The name of the ID column (default: 'id')
 * @returns {Promise<Object>} Promise that resolves to the API response
 */
async function getSingleRecord(table, id, idColumn = 'id') {
    try {
        const params = new URLSearchParams({
            action: 'read_single',
            table: table,
            id: id,
            idColumn: idColumn
        });

        const response = await fetch(`/api/database?${params}`);
        const data = await response.json();
        
        return data;
    } catch (error) {
        console.error('Database fetch single error:', error);
        return { error: error.message };
    }
}

/*
 * USAGE EXAMPLE:
 * const result = await getSingleRecord('users', 123);
 * console.log(result);
 */


//================================================================================================
//                           recordExists
//================================================================================================
/**
 * Checks if a record exists in the database via API
 * @param {string} table - The table name to check
 * @param {Object} where - Object of column => value pairs for WHERE clause
 * @returns {Promise<boolean>} Promise that resolves to true if record exists
 */
async function recordExists(table, where) {
    try {
        const params = new URLSearchParams({
            action: 'exists',
            table: table,
            where: JSON.stringify(where)
        });

        const response = await fetch(`/api/database?${params}`);
        const data = await response.json();
        
        return data.exists || false;
    } catch (error) {
        console.error('Database exists check error:', error);
        return false;
    }
}

/*
 * USAGE EXAMPLE:
 * const exists = await recordExists('users', { email: 'test@example.com' });
 * console.log(exists);
 */


//================================================================================================
//                           getUrlParameter
//================================================================================================
/**
 * Utility function to get URL parameters
 * @param {string} name - The name of the parameter to retrieve
 * @returns {string|null} The value of the parameter or null if not found
 */
function getUrlParameter(name) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(name);
}

//================================================================================================
//                           toggleSuccessMessage
//================================================================================================
/**
 * Utility function to show/hide success messages
 * @param {string} elementId - The ID of the success message element
 * @param {boolean} show - Whether to show or hide the message
 */
function toggleSuccessMessage(elementId, show) {
    const element = document.getElementById(elementId);
    if (element) {
        element.style.display = show ? 'block' : 'none';

        if (show) {
            // Auto-hide after 5 seconds
            setTimeout(() => {
                element.style.display = 'none';
            }, 5000);
        }
    }
}

/*
 * USAGE EXAMPLE:
 * toggleSuccessMessage('success-message', true);  // Show the success message
 * toggleSuccessMessage('success-message', false); // Hide the success message
 */


//================================================================================================
//                           applyAutoUppercase
//================================================================================================
/**
 * Utility function to apply auto-uppercase to text inputs
 * @param {string} selector - CSS selector for the inputs (default: 'input[type="text"]')
 */
function applyAutoUppercase(selector = 'input[type="text"]') {
    const textInputs = document.querySelectorAll(selector);
    textInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    });
}

//================================================================================================
//                           setupCivilStatusToggle
//================================================================================================
/**
 * Utility function to handle civil status "Others" field toggle
 * @param {string} radioSelector - CSS selector for the civil status radio buttons
 * @param {string} inputId - ID of the "Others" input field
 */
function setupCivilStatusToggle(radioSelector = '.cvstatus-radio', inputId = 'cvstatus-other-input') {
    const cvstatusRadios = document.querySelectorAll(radioSelector);
    const cvstatusOtherInput = document.getElementById(inputId);

    cvstatusRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.id === 'cvstatus-others' && this.checked) {
                cvstatusOtherInput.disabled = false;
                cvstatusOtherInput.focus();
            } else {
                cvstatusOtherInput.disabled = true;
                cvstatusOtherInput.value = '';
            }
        });
    });
}

//================================================================================================
//                           setupSameAddressFunctionality
//================================================================================================
/**
 * Utility function to handle "Same as Home Address" functionality
 * @param {string} checkboxId - ID of the checkbox
 * @param {string} pbirthInputId - ID of the place of birth input
 * @param {string} addressLayerId - ID of the address layer to disable
 * @param {Array<string>} addressInputIds - Array of address input IDs [barangay, city, province, country, zip]
 * @param {string} hiddenFieldId - ID of the hidden field to update
 */
function setupSameAddressFunctionality(checkboxId = 'same-address-checkbox',
                                     pbirthInputId = 'pbirth-input',
                                     addressLayerId = 'address-layer2',
                                     addressInputIds = ['address-5', 'address-6-input', 'address-7-input', 'address-8-input', 'address-9'],
                                     hiddenFieldId = 'same-as-pbirth-hidden') {

    const sameAddressCheckbox = document.getElementById(checkboxId);
    const pbirthInput = document.getElementById(pbirthInputId);
    const addressLayer = document.getElementById(addressLayerId);
    const [address5Input, address6Input, address7Input, address8Input, address9Input] = addressInputIds.map(id => document.getElementById(id));
    const hiddenField = document.getElementById(hiddenFieldId);

    if (!sameAddressCheckbox || !pbirthInput || !addressLayer) return;

    sameAddressCheckbox.addEventListener('change', function() {
        if (this.checked) {
            addressLayer.style.opacity = '0.5';
            addressLayer.style.pointerEvents = 'none';

            const pbirth = pbirthInput.value;
            if (pbirth) {
                const parts = pbirth.split(',').map(part => part.trim());

                if (parts.length >= 1 && address5Input) address5Input.value = parts[0] || '';
                if (parts.length >= 2 && address6Input) address6Input.value = parts[1] || '';
                if (parts.length >= 3 && address7Input) address7Input.value = parts[2] || '';
                if (parts.length >= 4 && address8Input) address8Input.value = parts[3] || 'PHILIPPINES';
                if (parts.length >= 5 && address9Input) address9Input.value = parts[4] || '';
            }

            if (hiddenField) hiddenField.value = '1';
        } else {
            addressLayer.style.opacity = '1';
            addressLayer.style.pointerEvents = 'auto';

            if (address5Input) address5Input.value = '';
            if (address6Input) address6Input.value = '';
            if (address7Input) address7Input.value = '';
            if (address8Input) address8Input.value = 'PHILIPPINES';
            if (address9Input) address9Input.value = '';

            if (hiddenField) hiddenField.value = '0';
        }
    });

    pbirthInput.addEventListener('input', function() {
        if (sameAddressCheckbox.checked) {
            const pbirth = this.value;
            const parts = pbirth.split(',').map(part => part.trim());

            if (parts.length >= 1 && address5Input) address5Input.value = parts[0] || '';
            if (parts.length >= 2 && address6Input) address6Input.value = parts[1] || '';
            if (parts.length >= 3 && address7Input) address7Input.value = parts[2] || '';
            if (parts.length >= 4 && address8Input) address8Input.value = parts[3] || 'PHILIPPINES';
            if (parts.length >= 5 && address9Input) address9Input.value = parts[4] || '';
        }
    });
}

/*
 * USAGE EXAMPLE:
 * setupSameAddressFunctionality('same-address-checkbox', 'pbirth-input', 'address-layer2',
 *                             ['address-5', 'address-6-input', 'address-7-input', 'address-8-input', 'address-9'],
 *                             'same-as-pbirth-hidden');
 * // When checkbox is checked, address fields will be populated from place of birth
 */


//================================================================================================
//                           setupAddChildFunctionality
//================================================================================================
/**
 * Utility function to add child functionality dynamically
 * @param {string} addButtonId - ID of the "Add Child" button
 * @param {string} containerId - ID of the container for child entries
 * @param {string} childTemplate - HTML template for a child entry
 */
function setupAddChildFunctionality(addButtonId = 'add-child-btn', containerId = 'children-container', childTemplate = null) {
    let childCount = document.querySelectorAll(`#${containerId} .child-entry`).length;

    // Default template if none provided
    if (!childTemplate) {
        childTemplate = (index) => `
            <p>Child ${index}</p>
            <div class="child-fields">
                <div class="fp-box">
                    <p>(Last Name):</p>
                    <input type="text" name="children[${index - 1}][lname]">
                </div>
                <div class="fp-box">
                    <p>(First Name):</p>
                    <input type="text" name="children[${index - 1}][fname]">
                </div>
                <div class="fp-box">
                    <p>(Middle Name):</p>
                    <input type="text" name="children[${index - 1}][mname]">
                </div>
                <div class="fp-box">
                    <p>(Suffix):</p>
                    <input type="text" name="children[${index - 1}][sfx]">
                </div>
                <div class="fp-box">
                    <p>Date of Birth (MM/DD/YYYY):</p>
                    <input type="date" name="children[${index - 1}][dbirth]">
                </div>
            </div>
        `;
    }

    document.getElementById(addButtonId).addEventListener('click', function() {
        const container = document.getElementById(containerId);
        const newChild = document.createElement('div');
        newChild.className = 'child-entry';
        newChild.setAttribute('data-child-num', childCount + 1);

        newChild.innerHTML = childTemplate(childCount + 1);

        container.appendChild(newChild);
        childCount++;
    });
}

//================================================================================================
//                           populateFormFields
//================================================================================================
/**
 * Utility function to populate form fields with data
 * @param {Object} data - Object containing field names and values
 * @param {string} prefix - Optional prefix to prepend to field names
 */
function populateFormFields(data, prefix = '') {
    for (const [key, value] of Object.entries(data)) {
        const fieldName = prefix ? `${prefix}[${key}]` : key;
        const element = document.querySelector(`[name="${fieldName}"]`);

        if (element) {
            if (element.type === 'checkbox' || element.type === 'radio') {
                if (element.value === value || value === true || value === '1') {
                    element.checked = true;
                }
            } else if (element.tagName === 'SELECT') {
                element.value = value || '';
            } else {
                element.value = value || '';
            }
        }

        // Also try direct ID match
        const elementById = document.getElementById(key);
        if (elementById && !element) { // Only if we didn't find it by name
            if (elementById.type === 'checkbox' || elementById.type === 'radio') {
                if (elementById.value === value || value === true || value === '1') {
                    elementById.checked = true;
                }
            } else {
                elementById.value = value || '';
            }
        }
    }
}

//================================================================================================
//                           populateNestedData
//================================================================================================
/**
 * Utility function to populate complex nested data (like children)
 * @param {Array} dataArray - Array of objects to populate
 * @param {string} containerId - ID of the container element
 * @param {Function} templateFn - Function that generates HTML template for each item
 */
function populateNestedData(dataArray, containerId, templateFn) {
    if (!dataArray || !Array.isArray(dataArray) || dataArray.length === 0) return;

    const container = document.getElementById(containerId);
    if (!container) return;

    container.innerHTML = ''; // Clear existing entries

    dataArray.forEach((item, index) => {
        const newEntry = document.createElement('div');
        newEntry.className = 'child-entry';
        newEntry.setAttribute('data-child-num', index + 1);

        newEntry.innerHTML = templateFn(item, index);

        container.appendChild(newEntry);
    });
}

/*
 * USAGE EXAMPLE:
 * const children = [{fname: 'Child1', lname: 'Family'}, {fname: 'Child2', lname: 'Family'}];
 * populateNestedData(children, 'children-container', (child, index) => `
 *   <div>Name: ${child.fname} ${child.lname}</div>
 * `);
 * // Populates the container with nested data
 */


//================================================================================================
//                           handleFormUpdate
//================================================================================================
/**
 * Utility function to handle form updates
 * @param {string} formActionUrl - URL to submit the form to
 * @param {string} redirectUrl - URL to redirect after successful update
 */
async function handleFormUpdate(formActionUrl, redirectUrl) {
    const formData = new FormData(document.querySelector('form'));

    try {
        const response = await fetch(formActionUrl, {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            if (redirectUrl) {
                window.location.href = redirectUrl;
            }
            return { success: true, message: result.message || 'Record updated successfully' };
        } else {
            return { success: false, message: result.message || 'Error updating record' };
        }
    } catch (error) {
        console.error('Error updating record:', error);
        return { success: false, message: 'Network error occurred' };
    }
}

//================================================================================================
//                           loadApplicantData
//================================================================================================
/**
 * Utility function to load applicant data for editing
 * @param {number|string} id - The applicant ID to load
 * @param {string} apiUrl - The API endpoint to fetch applicant data
 */
async function loadApplicantData(id, apiUrl) {
    try {
        const response = await fetch(`${apiUrl}?id=${id}`);
        const data = await response.json();

        if (data) {
            const applicant = data.applicant;
            const address = data.address;
            const parents = data.parents;
            const spouse = data.spouse;
            const children = data.children;
            const employment = data.employment;

            // Populate main applicant fields
            if (applicant) {
                document.getElementById('applicant-id').value = applicant.applicant_id || id;
                populateFormFields({
                    'ssnum': applicant.ssnum || '',
                    'lname': applicant.lname || '',
                    'fname': applicant.fname || '',
                    'mname': applicant.mname || '',
                    'sfx': applicant.sfx || '',
                    'dbirth': applicant.dbirth || '',
                    'sex': applicant.sex || '',
                    'cvstatus': applicant.cvstatus || '',
                    'cvstatus_other': applicant.cvstatus_other || '',
                    'taxid': applicant.taxid || '',
                    'nation': applicant.nation || '',
                    'religion': applicant.religion || '',
                    'pbirth': applicant.pbirth || '',
                    'cphone': applicant.cphone || '',
                    'email': applicant.email || '',
                    'tphone': applicant.tphone || '',
                    'printed-name': applicant.printed_name || '',
                    'cert-date': applicant.cert_date || ''
                });

                // Set radio buttons specifically
                if (applicant.sex) {
                    const sexRadio = document.querySelector(`input[name="sex"][value="${applicant.sex}"]`);
                    if (sexRadio) sexRadio.checked = true;
                }

                if (applicant.cvstatus) {
                    const cvstatusRadio = document.querySelector(`input[name="cvstatus"][value="${applicant.cvstatus}"]`);
                    if (cvstatusRadio) cvstatusRadio.checked = true;
                }
            }

            // Populate address fields
            if (address) {
                populateFormFields({
                    'address-1': address.address_1 || '',
                    'address-2': address.address_2 || '',
                    'address-3': address.address_3 || '',
                    'address-4': address.address_4 || '',
                    'address-5': address.address_5 || '',
                    'address-6': address.address_6 || '',
                    'address-7': address.address_7 || '',
                    'address-8': address.address_8 || '',
                    'address-9': address.address_9 || ''
                });

                // Set same as place of birth checkbox
                if (address.same_as_pbirth) {
                    document.getElementById('same-address-checkbox').checked = true;
                    document.getElementById('address-layer2').style.opacity = '0.5';
                    document.getElementById('address-layer2').style.pointerEvents = 'none';
                }
            }

            // Populate parents fields
            if (parents) {
                populateFormFields({
                    'lfather': parents.lfather || '',
                    'ffather': parents.ffather || '',
                    'mfather': parents.mfather || '',
                    'sfxfather': parents.sfxfather || '',
                    'fbirth': parents.fbirth || '',
                    'lmother': parents.lmother || '',
                    'fmother': parents.fmother || '',
                    'mmother': parents.mmother || '',
                    'sfxmother': parents.sfxmother || '',
                    'mbirth': parents.mbirth || ''
                });
            }

            // Populate spouse fields
            if (spouse) {
                populateFormFields({
                    'lspouse': spouse.lspouse || '',
                    'fspouse': spouse.fspouse || '',
                    'mspouse': spouse.mspouse || '',
                    'sfxspouse': spouse.sfxspouse || '',
                    'sbirth': spouse.sbirth || ''
                });
            }

            // Populate children fields
            if (children && children.length > 0) {
                populateNestedData(children, 'children-container', (child, index) => `
                    <p>Child ${index + 1}</p>
                    <div class="child-fields">
                        <div class="fp-box">
                            <p>(Last Name):</p>
                            <input type="text" name="children[${index}][lname]" value="${child.lname || ''}">
                        </div>
                        <div class="fp-box">
                            <p>(First Name):</p>
                            <input type="text" name="children[${index}][fname]" value="${child.fname || ''}">
                        </div>
                        <div class="fp-box">
                            <p>(Middle Name):</p>
                            <input type="text" name="children[${index}][mname]" value="${child.mname || ''}">
                        </div>
                        <div class="fp-box">
                            <p>(Suffix):</p>
                            <input type="text" name="children[${index}][sfx]" value="${child.sfx || ''}">
                        </div>
                        <div class="fp-box">
                            <p>Date of Birth (MM/DD/YYYY):</p>
                            <input type="date" name="children[${index}][dbirth]" value="${child.dbirth || ''}">
                        </div>
                    </div>
                `);
            }

            // Populate employment fields
            if (employment) {
                populateFormFields({
                    'profession': employment.profession || '',
                    'ystart': employment.ystart || '',
                    'mearning': employment.mearning || '',
                    'faddress': employment.faddress || '',
                    'ofw_monthly_earnings': employment.ofw_monthly_earnings || '',
                    'spouse-ssnum': employment.spouse_ssnum || '',
                    'ffprogram': employment.ffprogram || '',
                    'ffp': employment.ffp || ''
                });
            }
        } else {
            console.warn('Applicant not found');
        }
    } catch (error) {
        console.error('Error loading applicant data:', error);
    }
}

/*
 * USAGE EXAMPLE:
 * loadApplicantData(123, '../crud/crud-operations/read_single.php');
 * // Loads applicant data with ID 123 for editing
 */


//================================================================================================
//                           initializeEditMode
//================================================================================================
/**
 * Utility function to initialize edit mode
 * @param {string} editParamName - Name of the URL parameter that indicates edit mode (default: 'edit')
 * @param {string} submitButtonSelector - Selector for the submit button to hide
 * @param {string} updateButtonSelector - Selector for the update button to show
 * @param {string} apiUrl - API endpoint to fetch applicant data
 */
function initializeEditMode(editParamName = 'edit', submitButtonSelector = 'input[type="submit"]',
                          updateButtonSelector = '#update-btn', apiUrl = '../crud/crud-operations/read_single.php') {
    const editId = getUrlParameter(editParamName);

    if (editId) {
        // Hide submit button and show update button
        const submitButton = document.querySelector(submitButtonSelector);
        const updateButton = document.querySelector(updateButtonSelector);

        if (submitButton) submitButton.style.display = 'none';
        if (updateButton) updateButton.style.display = 'inline-block';

        // Load the applicant data
        loadApplicantData(editId, apiUrl);
    }
}

//================================================================================================
//                           setupUpdateHandler
//================================================================================================
/**
 * Utility function to setup a generic update handler
 * @param {string} updateFunctionName - Name of the function to call for updates
 * @param {string} formActionUrl - URL to submit the form to
 * @param {string} redirectUrl - URL to redirect after successful update
 */
function setupUpdateHandler(updateFunctionName = 'updateRecord', formActionUrl = 'page1.php?action=update', redirectUrl = '../crud/crud.html') {
    window[updateFunctionName] = async function() {
        const result = await handleFormUpdate(formActionUrl, redirectUrl);

        if (!result.success) {
            alert(result.message);
        }
    };
}

//================================================================================================
//                           initializeForm
//================================================================================================
/**
 * Utility function to initialize common form behaviors
 * @param {Object} options - Configuration options
 */
function initializeForm(options = {}) {
    const config = {
        successMessageId: 'success-message',
        textInputSelector: 'input[type="text"]',
        civilStatusRadioSelector: '.cvstatus-radio',
        civilStatusInputId: 'cvstatus-other-input',
        sameAddressCheckboxId: 'same-address-checkbox',
        pbirthInputId: 'pbirth-input',
        addressLayerId: 'address-layer2',
        addressInputIds: ['address-5', 'address-6-input', 'address-7-input', 'address-8-input', 'address-9'],
        hiddenFieldId: 'same-as-pbirth-hidden',
        addChildButtonId: 'add-child-btn',
        childrenContainerId: 'children-container',
        editParamName: 'edit',
        submitButtonSelector: 'input[type="submit"]',
        updateButtonSelector: '#update-btn',
        apiEndpoint: '../crud/crud-operations/read_single.php',
        formActionUrl: 'page1.php?action=update',
        redirectUrl: '../crud/crud.html',
        ...options
    };

    // Show success message if form was submitted
    if (sessionStorage.getItem('formSuccess')) {
        toggleSuccessMessage(config.successMessageId, true);
        sessionStorage.removeItem('formSuccess');
    }

    // Apply auto uppercase to text inputs
    applyAutoUppercase(config.textInputSelector);

    // Setup civil status toggle
    setupCivilStatusToggle(config.civilStatusRadioSelector, config.civilStatusInputId);

    // Setup same address functionality
    setupSameAddressFunctionality(
        config.sameAddressCheckboxId,
        config.pbirthInputId,
        config.addressLayerId,
        config.addressInputIds,
        config.hiddenFieldId
    );

    // Setup add child functionality
    setupAddChildFunctionality(
        config.addChildButtonId,
        config.childrenContainerId
    );

    // Initialize edit mode if applicable
    initializeEditMode(
        config.editParamName,
        config.submitButtonSelector,
        config.updateButtonSelector,
        config.apiEndpoint
    );

    // Setup update handler
    setupUpdateHandler('updateRecord', config.formActionUrl, config.redirectUrl);
}

/**
 * Usables Class - Object-oriented wrapper for the utility functions
 */
class Usables {
    constructor() {
        this.apiEndpoint = '/api/database';
    }
    
    /**
     * Set the API endpoint for database operations
     * @param {string} endpoint - The API endpoint URL
     */
    setApiEndpoint(endpoint) {
        this.apiEndpoint = endpoint;
    }
    
    /**
     * Fetches data from the database via API call
     * @param {string} table - The table name to query
     * @param {Array} columns - Array of columns to select (empty array means all *)
     * @param {Object} where - Object of column => value pairs for WHERE clause
     * @param {string} orderBy - Column to order by
     * @param {string} orderDir - Direction of order (ASC or DESC)
     * @param {number} limit - Limit of records
     * @param {number} offset - Offset for pagination
     * @returns {Promise<Array>} Promise that resolves to an array of records
     */
    async getDataFromDatabase(table, columns = [], where = {}, orderBy = '', orderDir = 'ASC', limit = 0, offset = 0) {
        return await getDataFromDatabase(table, columns, where, orderBy, orderDir, limit, offset);
    }
    
    /**
     * Inserts data to the database via API call
     * @param {string} table - The table name to insert into
     * @param {Object} data - Object of column => value pairs to insert
     * @returns {Promise<Object>} Promise that resolves to the API response
     */
    async insertDataToDatabase(table, data) {
        return await insertDataToDatabase(table, data);
    }
    
    /**
     * Updates data in the database via API call
     * @param {string} table - The table name to update
     * @param {Object} data - Object of column => value pairs to update
     * @param {Object} where - Object of column => value pairs for WHERE clause
     * @returns {Promise<Object>} Promise that resolves to the API response
     */
    async updateDataInDatabase(table, data, where) {
        return await updateDataInDatabase(table, data, where);
    }
    
    /**
     * Deletes data from the database via API call
     * @param {string} table - The table name to delete from
     * @param {Object} where - Object of column => value pairs for WHERE clause
     * @returns {Promise<Object>} Promise that resolves to the API response
     */
    async deleteDataFromDatabase(table, where) {
        return await deleteDataFromDatabase(table, where);
    }
    
    /**
     * Gets a single record from the database via API call
     * @param {string} table - The table name to query
     * @param {number|string} id - The ID of the record to fetch
     * @param {string} idColumn - The name of the ID column (default: 'id')
     * @returns {Promise<Object>} Promise that resolves to the record or null
     */
    async getSingleRecord(table, id, idColumn = 'id') {
        return await getSingleRecord(table, id, idColumn);
    }
    
    /**
     * Checks if a record exists in the database via API call
     * @param {string} table - The table name to check
     * @param {Object} where - Object of column => value pairs for WHERE clause
     * @returns {Promise<boolean>} Promise that resolves to true if record exists
     */
    async recordExists(table, where) {
        return await recordExists(table, where);
    }
    
    /**
     * Creates a new record in the database (similar to CRUD create operation)
     * @param {Object} data - Object of field => value pairs to insert
     * @returns {Promise<Object>} Promise that resolves to the API response
     */
    async createRecord(data) {
        try {
            const response = await fetch('../crud/crud-operations/create.php', {
                method: 'POST',
                body: (() => {
                    const formData = new FormData();
                    for (const [key, value] of Object.entries(data)) {
                        formData.append(key, value);
                    }
                    return formData;
                })()
            });
            
            const result = await response.json();
            return result;
        } catch (error) {
            console.error('Create record error:', error);
            return { success: false, message: error.message };
        }
    }
    
    /**
     * Reads all records from the database (similar to CRUD read operation)
     * @returns {Promise<Array>} Promise that resolves to an array of records
     */
    async readRecords() {
        try {
            const response = await fetch('../crud/crud-operations/read.php');
            const result = await response.json();
            return result;
        } catch (error) {
            console.error('Read records error:', error);
            return { error: error.message };
        }
    }
    
    /**
     * Reads a single record from the database (similar to CRUD read_single operation)
     * @param {number|string} id - The ID of the record to retrieve
     * @returns {Promise<Object>} Promise that resolves to the record data
     */
    async readSingleRecord(id) {
        try {
            const response = await fetch(`../crud/crud-operations/read_single.php?id=${id}`);
            const result = await response.json();
            return result;
        } catch (error) {
            console.error('Read single record error:', error);
            return { error: error.message };
        }
    }
    
    /**
     * Updates a record in the database (similar to CRUD update operation)
     * @param {Object} data - Object of field => value pairs to update
     * @returns {Promise<Object>} Promise that resolves to the API response
     */
    async updateRecord(data) {
        try {
            const response = await fetch('../crud/crud-operations/update.php', {
                method: 'POST',
                body: (() => {
                    const formData = new FormData();
                    for (const [key, value] of Object.entries(data)) {
                        formData.append(key, value);
                    }
                    return formData;
                })()
            });
            
            const result = await response.json();
            return result;
        } catch (error) {
            console.error('Update record error:', error);
            return { success: false, message: error.message };
        }
    }
    
    /**
     * Deletes a record from the database (similar to CRUD delete operation)
     * @param {number|string} id - The ID of the record to delete
     * @returns {Promise<Object>} Promise that resolves to the API response
     */
    async deleteRecord(id) {
        try {
            const formData = new FormData();
            formData.append('id', id);
            
            const response = await fetch('../crud/crud-operations/delete.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            return result;
        } catch (error) {
            console.error('Delete record error:', error);
            return { success: false, message: error.message };
        }
    }
}
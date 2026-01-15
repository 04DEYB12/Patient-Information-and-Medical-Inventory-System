<div id="EditRecordModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4); backdrop-filter: blur(2px);">
    <div class="modal-content" style="background-color: #ffffff; margin: 5% auto; padding: 2rem; border-radius: 0.75rem; width: 90%; max-width: 700px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); border: 1px solid #e5e7eb;">
        <!-- Modal Header -->
        <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-100">
            <div>
                <h2 class="text-xl font-semibold text-gray-800" id="EditRecordModalTitle">Medical Record Details</h2>
                <p class="text-sm text-gray-500 mt-1">Review and update patient medical record</p>
            </div>
            <button onclick="closeModal('EditRecordModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        
        <!-- Patient Profile Section -->
        <div class="flex items-center gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
            <div id="EditAvatar" class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-2xl font-semibold" id="studentAvatar">
                JD
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-800" id="EditStudentName">Loading...</h3>
                <p class="text-sm text-gray-500" id="EditStudentID">ID: Loading...</p>
            </div>
        </div>
        
        <input type="hidden" id="recordID" name="recordID">

        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button type="button" 
                        onclick="switchTab('assessment-tab')" 
                        class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600" 
                        id="assessment-tab-btn">
                    <i class="bx bx-user mr-2"></i>Assessment
                </button>
                <button type="button" 
                        onclick="switchTab('prescription-tab')" 
                        class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300"
                        id="prescription-tab-btn">
                    <i class="bx bx-capsule mr-2"></i>Prescription
                </button>
                <button type="button" 
                        onclick="switchTab('outcome-tab')" 
                        class="tab-button whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300"
                        id="outcome-tab-btn">
                    <i class="bx bx-clipboard mr-2"></i>Treatment Outcome
                </button>
            </nav>
        </div>
        
        <!-- Tab Contents -->
        <div class="tab-contents">
            <!-- Assessment Tab -->
            <div id="assessment-tab" class="tab-pane active">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <!-- Left Column - Basic Info -->
                    <div class="space-y-4 md:col-span-1">
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Check-up Date & Time</label>
                            <div class="p-2.5 bg-gray-200 rounded border border-gray-200 text-gray-700 text-sm cursor-not-allowed" id="CheckInDateTime_record">Loading...</div>
                        </div>
                        
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <div class="p-2.5 bg-gray-200 rounded border border-gray-200 text-gray-700 text-sm cursor-not-allowed" id="Status_record">Loading...</div>
                        </div>
                        
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Updated</label>
                            <div class="p-2.5 bg-gray-200 rounded border border-gray-200 text-gray-700 text-sm cursor-not-allowed" id="DateTime_record">Loading...</div>
                        </div>
                        
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Follow Up Date</label>
                            <input  id="FollowUpDate_record" type="datetime-local" class="p-2.5 bg-gray-200 rounded border border-gray-200 text-gray-700 text-sm cursor-not-allowed" disabled Title="Allow edit First.">
                        </div>
                    </div>
                    
                    <!-- Right Column - Notes and Reason -->
                    <div class="space-y-4 md:col-span-2">
                        <div class="form-group">
                            <div class="flex items-center justify-between mb-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Visit</label>
                                <div class="flex items-center">
                                    <span class="text-xs text-gray-500 mr-2">Allow edit</span>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="editOutcomeToggle" class="sr-only peer" onchange="toggle_FollowUp_Reason_Notes_Edit(this.checked)">
                                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-500"></div>
                                    </label>
                                </div>
                            </div>
                            <!-- <div class="p-2.5 bg-gray-50 rounded border border-gray-200 text-gray-700 min-h-[42px] text-sm" id="EditReason">Loading...</div> -->
                            <select 
                                id="checkup_reason_record" 
                                required disabled Title="Allow Edit First."
                                class="w-full px-3 py-2 pr-10 text-gray-700 bg-gray-200 border border-gray-200 rounded-lg cursor-pointer focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:ring-opacity-50 transition-all duration-200 appearance-none cursor-not-allowed"
                                onfocus="this.classList.add('ring-2', 'ring-blue-100', 'border-blue-500')"
                                onblur="this.classList.remove('ring-2', 'ring-blue-100', 'border-blue-500')">
                                <option value="" disabled selected>Select a reason</option>
                                <option value="Fever">🤒 Fever</option>
                                <option value="Cough / Cold">🤧 Cough / Cold</option>
                                <option value="Headache">🤕 Headache</option>
                                <option value="Stomach Ache">🤢 Stomach Ache</option>
                                <option value="Wound / Injury">🩹 Wound / Injury</option>
                                <option value="Dizziness">😵‍💫 Dizziness</option>
                                <option value="Toothache">🦷 Toothache</option>
                                <option value="Others">📝 Others</option>
                            </select>
                        </div>
                        
                        <div class="form-group flex-1 flex flex-col h-full">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea id="Notes_record" disabled Title="Allow Edit First." class="p-3 bg-gray-50 rounded border border-gray-200 text-gray-700 flex-1 min-h-[100px] max-h-[220px] overflow-y-auto text-sm cursor-not-allowed">
                                <p class="m-0 text-gray-500">No notes available</p>
                            </textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 mt-6">
                    <button type="button" onclick="closeModal('EditRecordModal')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        Cancel
                    </button>
                    <button type="button" id="EditBtn" disabled Title="Allow Edit First." onclick="SaveChanges()"
                            class="px-4 py-2 text-sm font-medium text-white bg-gray-400 border border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors cursor-not-allowed">
                        Save Changes
                    </button>
                </div>
            </div>
            
            <!-- Prescription Tab -->
            <div id="prescription-tab" class="tab-pane hidden">
                <div class="flex flex-col space-y-3">
                    <div class="flex space-x-3">
                        <!-- Medicine Dropdown -->
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Medicine</label>
                            <select id="medicine_select" onchange="loadBatches(this.value)" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="" disabled selected>Select Medicine</option>
                            </select>
                        </div>
                        
                        <!-- Batch Dropdown -->
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Batch</label>
                            <select id="batch_select" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm" disabled>
                                <option value="" selected>Select Batch</option>
                            </select>
                        </div>
                        
                        <!-- Quantity Input -->
                        <div class="flex-1">
                            <label class="block text-xs font-medium text-gray-500 mb-1">Quantity</label>
                            <div class="flex rounded-md shadow-sm">
                                <input type="number" id="quantity" min="1" value="1" class="flex-1 min-w-0 block w-full px-3 py-2 rounded-md border border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            </div>
                        </div>
                        
                        <!-- Add Button -->
                        <div class="flex items-end">
                            <button type="button" id="add_prescription" onclick="addPrescription()" class="h-[42px] inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class='bx bx-plus mr-1'></i> Add
                            </button>
                        </div>
                    </div>
                    
                    <!-- Prescribed List -->
                    <div id="prescribed_list" class="mt-2 space-y-2 max-h-40 overflow-y-auto">
                        <!-- Prescribed items will be added here -->
                    </div>
                    
                    <!-- Prescription List -->
                    <div id="prescription_list" class="mt-2 space-y-2 max-h-40 overflow-y-auto">
                        <!-- Prescription items will be added here -->
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 mt-6">
                    <button type="button" onclick="closeModal('EditRecordModal')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        Cancel
                    </button>
                    <button type="button" id="PrescribeBtn" Title="Save Prescription." onclick="Prescribes()"
                            class="px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 border border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        Prescribe
                    </button>
                </div>
            </div>
            
            <!-- Outcome Tab -->
            <div id="outcome-tab" class="tab-pane hidden">
                <div class="flex items-center justify-between mb-1">
                    <label for="EditOutcome" class="block text-sm font-medium text-gray-700 mb-2">Treatment Outcome</label>
                    <div class="flex items-center">
                        <span class="text-xs text-gray-500 mr-2">Allow input</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="editOutcomeToggle" class="sr-only peer" onchange="toggleOutcomeEdit(this.checked)">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-500"></div>
                        </label>
                    </div>
                </div>
                <textarea id="EditOutcome" name="EditOutcome" rows="3" required disabled Title="Allow Input First."
                    class="w-full p-3 text-sm rounded-lg border border-gray-300 bg-gray-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors cursor-not-allowed"
                    placeholder="Enter detailed treatment outcome..."></textarea>
                    
                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 mt-6">
                    <button type="button" onclick="closeModal('EditRecordModal')"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                        Cancel
                    </button>
                    <button type="button" id="MarkasDoneBtn" disabled Title="Allow Input First." onclick="MarkasDone()"
                        class="px-4 py-2 text-sm font-medium text-white bg-gray-400 border border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors cursor-not-allowed">
                        Mark as Done
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .tab-pane {
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
        height: 0;
        overflow: hidden;
    }
    .tab-pane.active {
        display: block;
        opacity: 1;
        height: auto;
        overflow: visible;
        animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .tab-button {
        transition: all 0.2s ease-in-out;
    }
</style>

<script>
    // Tab switching function
    function switchTab(tabId) {
        // Hide all tab panes
        document.querySelectorAll('.tab-pane').forEach(pane => {
            pane.classList.add('hidden');
            pane.classList.remove('active');
        });
        
        // Deactivate all tab buttons
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('border-blue-500', 'text-blue-600');
            btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        });
        
        // Show selected tab pane
        const activePane = document.getElementById(tabId);
        if (activePane) {
            activePane.classList.remove('hidden');
            activePane.classList.add('active');
            
            // Activate clicked tab button
            const activeButton = document.getElementById(`${tabId}-btn`);
            if (activeButton) {
                activeButton.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
                activeButton.classList.add('border-blue-500', 'text-blue-600');
            }
        }
    }
    
    // toggle follow-up Date, check-up reason, notes edit
    function toggle_FollowUp_Reason_Notes_Edit(isEditable){
        const FollowUpDate = document.getElementById('FollowUpDate_record');
        FollowUpDate.disabled = !isEditable;
        FollowUpDate.classList.toggle('bg-gray-200', !isEditable);
        FollowUpDate.classList.toggle('bg-white', isEditable);
        FollowUpDate.classList.toggle('cursor-not-allowed', !isEditable);
        FollowUpDate.classList.toggle('cursor-pointer', isEditable);
        FollowUpDate.title = isEditable ? 'Follow Up Date' : 'Allow Edit First.';
        
        const checkup_reason = document.getElementById('checkup_reason_record');
        checkup_reason.disabled = !isEditable;
        checkup_reason.classList.toggle('bg-gray-200', !isEditable);
        checkup_reason.classList.toggle('bg-white', isEditable);
        checkup_reason.classList.toggle('cursor-not-allowed', !isEditable);
        checkup_reason.classList.toggle('cursor-pointer', isEditable);
        checkup_reason.title = isEditable ? 'Check-up Reason' : 'Allow Edit First.';
        
        const Notes = document.getElementById('Notes_record');
        Notes.disabled = !isEditable;
        Notes.classList.toggle('bg-gray-200', !isEditable);
        Notes.classList.toggle('bg-white', isEditable);
        Notes.classList.toggle('cursor-not-allowed', !isEditable);
        Notes.classList.toggle('cursor-pointer', isEditable);
        Notes.title = isEditable ? 'Notes' : 'Allow Edit First.';
        
        const EditBtn = document.getElementById('EditBtn');
        EditBtn.disabled = !isEditable;
        EditBtn.classList.toggle('bg-gray-400', !isEditable);
        EditBtn.classList.toggle('bg-green-600', isEditable);
        EditBtn.classList.toggle('hover:bg-green-700', isEditable);
        EditBtn.classList.toggle('cursor-not-allowed', !isEditable);
        EditBtn.classList.toggle('cursor-pointer', isEditable);
        EditBtn.title = isEditable ? 'Save Changes' : 'Allow Edit First.';
    
    }

    // toggle outcome edit
    function toggleOutcomeEdit(isEditable) {
        const outcomeInput = document.getElementById('EditOutcome');
        outcomeInput.disabled = !isEditable;
        outcomeInput.classList.toggle('bg-gray-200', !isEditable);
        outcomeInput.classList.toggle('bg-white', isEditable);
        outcomeInput.classList.toggle('cursor-not-allowed', !isEditable);
        outcomeInput.classList.toggle('cursor-pointer', isEditable);
        outcomeInput.title = isEditable ? 'Mark as Done' : 'Allow Input First.';
        
        const MarkasDoneBtn = document.getElementById('MarkasDoneBtn');
        MarkasDoneBtn.disabled = !isEditable;
        MarkasDoneBtn.classList.toggle('bg-gray-400', !isEditable);
        MarkasDoneBtn.classList.toggle('bg-green-600', isEditable);
        MarkasDoneBtn.classList.toggle('hover:bg-green-700', isEditable);
        MarkasDoneBtn.classList.toggle('cursor-not-allowed', !isEditable);
        MarkasDoneBtn.classList.toggle('cursor-pointer', isEditable);
        MarkasDoneBtn.title = isEditable ? 'Mark as Done' : 'Allow Input First.';
        
        // Focus the input when enabled
        if (isEditable) {
            setTimeout(() => outcomeInput.focus(), 100);
        }
    }
    
    function SaveChanges(){
        if (confirm('Are you sure you want to update this check-in record?')) {
            const recordId = document.getElementById('recordID').value;
            const studentId = document.getElementById('EditStudentID').textContent;
            const FollowUpDate_record = document.getElementById('FollowUpDate_record').value;
            const checkup_reason_record = document.getElementById('checkup_reason_record').value;
            const Notes_record = document.getElementById('Notes_record').value;
            
            const sendBtn = document.getElementById('EditBtn');
            const originalBtnText = sendBtn.innerHTML;
            
            // Disable button and show loading state
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-2"></i> Saving...';
            
            const formData = new FormData();
            formData.append('action', 'updateCheckInRecord');
            formData.append('recordId', recordId);
            formData.append('studentId', studentId);
            formData.append('FollowUpDate_record', FollowUpDate_record);
            formData.append('checkup_reason_record', checkup_reason_record);
            formData.append('Notes_record', Notes_record);
            
            fetch('../Functions/patientFunctions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Re-enable button and restore original text
                sendBtn.disabled = false;
                sendBtn.innerHTML = originalBtnText;
                if (data.success) {
                    // Refresh the user list to show updated status
                    showAlert(data.message, 'success');
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 5000);
                } else {
                    showAlert('Error: ' + (data.error || 'Failed to update check-in record'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while updating check-in record', 'error');
            });
        }
    }
    
    function MarkasDone(){
        if (confirm('Are you sure you want this Record to Mark as Done?')) {
            const recordId = document.getElementById('recordID').value;
            const studentId = document.getElementById('EditStudentID').textContent;
            const Outcome = document.getElementById('EditOutcome').value;
            
            const sendBtn = document.getElementById('MarkasDoneBtn');
            const originalBtnText = sendBtn.innerHTML;
            
            // Disable button and show loading state
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-2"></i> Saving...';
            
            const formData = new FormData();
            formData.append('action', 'MarkasDone_Record');
            formData.append('recordId', recordId);
            formData.append('studentId', studentId);
            formData.append('Outcome', Outcome);
            
            fetch('../Functions/patientFunctions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Re-enable button and restore original text
                sendBtn.disabled = false;
                sendBtn.innerHTML = originalBtnText;
                if (data.success) {
                    // Refresh the user list to show updated status
                    showAlert(data.message, 'success');
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 5000);
                } else {
                    showAlert('Error: ' + (data.error || 'Failed to update check-in record'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while updating check-in record', 'error');
            });
        }
    } 
    
    async function loadPrescriptions(recordId,studentId) {
        
        try {
            const response = await fetch(`../Functions/patientFunctions.php?action=getPrescriptions&recordId=${recordId}&studentId=${studentId}`);
            const data = await response.json();
            
            if (data.success) {
                const prescribedList = document.getElementById('prescribed_list');
                prescribedList.innerHTML = '';
                
                if (data.prescriptions.length === 0) {
                    prescribedList.innerHTML = '<p class="text-center text-gray-500 text-sm py-2">No prescriptions yet</p>';
                } else {
                    data.prescriptions.forEach(prescription => {
                        const prescriptionItem = document.createElement('div');
                        prescriptionItem.className = 'flex justify-between items-center p-2 bg-green-50 rounded border border-green-200';
                        
                        const prescribedDate = new Date(prescription.prescribed_date).toLocaleDateString();
                        const expiryDate = prescription.expiry_date ? new Date(prescription.expiry_date).toLocaleDateString() : 'N/A';
                        
                        prescriptionItem.innerHTML = `
                            <div class="flex-1">
                                <div class="font-medium text-green-800">${prescription.medicine_name}</div>
                                <div class="text-xs text-green-600">
                                    Qty: ${prescription.quantity} | Prescribed: ${prescribedDate} | By: ${prescription.prescribed_by}
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">${prescription.status}</span>
                            </div>
                        `;
                        prescribedList.appendChild(prescriptionItem);
                    });
                }
            } else {
                console.error('Failed to load prescriptions:', data.message);
            }
        } catch (error) {
            console.error('Error loading prescriptions:', error);
        }
    }
    
    async function loadMedicines() {
        try {
            const response = await fetch('../Functions/patientFunctions.php?action=getMedicines');
            const data = await response.json();
            
            if (data.success) {
                console.log(data.medicines);
                const select = document.getElementById('medicine_select');
                // Clear existing options except the first one
                select.innerHTML = '<option value="" disabled selected>Select Medicine</option>';
                
                // Add medicine options
                data.medicines.forEach(medicine => {
                    if(medicine.stock_quantity != 0){
                        const option = document.createElement('option');
                        option.value = medicine.name;
                        option.textContent = `${medicine.name}`;
                        select.appendChild(option);
                    }
                });

                // Enable the select and add change event
                select.disabled = false;
            } else {
                console.error('Failed to load medicines:', data.message);
            }
        } catch (error) {
            console.error('Error loading medicines:', error);
        }
    }
    
    async function loadBatches(medicine_name){
        try{
            const response = await fetch('../Functions/patientFunctions.php?action=LoadBatches&medicine_name=' + medicine_name);
            const data = await response.json();
            
            if (data.success) {
                console.log(data.medicines);
                const select = document.getElementById('batch_select');
                select.disabled = false;
                // Clear existing options except the first one
                select.innerHTML = '<option value="" disabled selected>Select Batch</option>';
                
                // Add medicine options
                data.medicines.forEach(medicine => {
                    if(medicine.stock_quantity != 0){
                        const option = document.createElement('option');
                        option.value = medicine.id + '-' + medicine.stock_quantity;
                        option.textContent = `Id: ${medicine.id} | (${medicine.stock_quantity} available) | expiry date: ${medicine.expiry_date}`;
                        select.appendChild(option);
                    }
                });
            } else {
                console.error('Failed to load Batches:', data.message);
            }
        }catch(error){
            console.error('Error loading batches:', error);
        }
    }
    
    // Add event listener for the Add button
    function addPrescription() {
        const medicineSelect = document.getElementById('medicine_select');
        const batchSelect = document.getElementById('batch_select');
        const quantityInput = document.getElementById('quantity');
        
        const medicineName = medicineSelect.options[medicineSelect.selectedIndex].text;
        const batchInfo = batchSelect.options[batchSelect.selectedIndex].text;
        const quantity = quantityInput.value;
        const selectedOption = batchSelect.options[batchSelect.selectedIndex];
        const [batchId, batchQuantity] = selectedOption.value.split('-');
        
        // Validate batch selection
        if (!batchId || batchId === 'undefined') {
            showAlert('Please select a valid batch', 'error');
            return;
        }

        // Parse and validate quantity
        const parsedQuantity = Number(quantity);
        const availableQuantity = Number(batchQuantity);

        if (isNaN(parsedQuantity) || parsedQuantity < 1) {
            showAlert('Please enter a valid quantity (must be at least 1)', 'error');
            return;
        }

        if (parsedQuantity > availableQuantity) {
            showAlert(`Invalid quantity. Only ${availableQuantity} items available in this batch.`, 'error');
            return;
        }
        
        // Add to prescription list
        const prescriptionList = document.getElementById('prescription_list');
        const prescriptionItem = document.createElement('div');
        prescriptionItem.className = 'flex justify-between items-center p-2 bg-gray-50 rounded border border-gray-200';
        prescriptionItem.innerHTML = `
            <div class="flex-1">
                <div class="font-medium">${medicineName}</div>
                <div class="text-sm text-gray-500">${batchInfo}</div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-sm rounded">${quantity}</span>
                <button type="button" class="text-red-500 hover:text-red-700" onclick="this.closest('.bg-gray-50').remove()">
                    <i class='bx bx-trash'></i>
                </button>
            </div>
        `;
        
        prescriptionList.appendChild(prescriptionItem);
        
        // Reset form
        batchSelect.selectedIndex = 0;
        quantityInput.value = 1;
        batchSelect.disabled = true;
    }

    // Function to get all prescriptions (for form submission)
    function getPrescriptions() {
        const prescriptions = [];
        document.querySelectorAll('#prescription_list > div').forEach(item => {
            const medicineText = item.querySelector('div:first-child > div:first-child').textContent;
            const medicine = medicineText.split(' - ')[0]; // Get only the medicine name before the dash
            const batchText = item.querySelector('div:first-child > div:last-child').textContent;
            const batchId = batchText.match(/Id:\s*(\d+)/)[1]; // Extract the ID number
            const quantity = item.querySelector('span[class*="bg-blue-100"]').textContent;
            prescriptions.push({ medicine, batchId, quantity });
        });
        return prescriptions;
    }
    
    function Prescribes(){
        if (confirm('Are you sure you want to prescribe this medicine?')){
            const recordId = document.getElementById('recordID').value;
            const studentId = document.getElementById('EditStudentID').textContent;
            const prescriptions = getPrescriptions();
            
            const sendBtn = document.getElementById('PrescribeBtn');
            const originalBtnText = sendBtn.innerHTML;
            
            // Disable button and show loading state
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-2"></i> Saving...';
            
            const formData = new FormData();
            formData.append('action', 'PrescribeMedicine');
            formData.append('recordId', recordId);
            formData.append('studentId', studentId);
            formData.append('prescriptions', JSON.stringify(prescriptions));
            
            fetch('../Functions/patientFunctions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Re-enable button and restore original text
                sendBtn.disabled = false;
                sendBtn.innerHTML = originalBtnText;
                if (data.success) {
                    // Refresh the user list to show updated status
                    showAlert(data.message, 'success');
                    
                    setTimeout(() => {
                        window.location.reload();
                    }, 5000);
                } else {
                    showAlert('Error: ' + (data.error || 'Failed to prescribe medicine'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while prescribing medicine', 'error');
            });
        }
    }
</script>
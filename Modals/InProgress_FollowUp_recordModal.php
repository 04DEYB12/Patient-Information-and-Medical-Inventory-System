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

        <!-- Form Grid -->
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

        <!-- Outcome Section -->
        <div class="mb-6">
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
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
            <button type="button" onclick="closeModal('EditRecordModal')"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                Cancel
            </button>
            <button type="button" id="EditBtn" disabled Title="Allow Edit First." onclick="SaveChanges()"
                    class="px-4 py-2 text-sm font-medium text-white bg-gray-400 border border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors cursor-not-allowed">
                Save Changes
            </button>
            <button type="button" id="MarkasDoneBtn" disabled Title="Allow Input First." onclick="MarkasDone()"
                class="px-4 py-2 text-sm font-medium text-white bg-gray-400 border border-transparent rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors cursor-not-allowed">
                Mark as Done
            </button>
        </div>
    </div>
</div>

<script>
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
    
</script>
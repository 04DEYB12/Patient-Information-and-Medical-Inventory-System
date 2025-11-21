<div id="alertGuardianModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden" onclick="if(event.target === this) this.classList.add('hidden')">
    <div class="bg-white rounded-lg w-full max-w-lg mx-4" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800">Inform Guardian</h3>
            <button type="button" class="text-gray-500 hover:text-gray-700" onclick="CloseAlertGuardianModal()">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
            <div class="mb-4">
                <div class="flex items-center justify-between mb-1">
                    <label for="recipientEmail" class="text-sm font-medium text-gray-700">Recipient Email</label>
                    <div class="flex items-center">
                        <span class="text-xs text-gray-500 mr-2">Allow edit</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="editEmailToggle" class="sr-only peer" onchange="toggleEmailEdit(this.checked)">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-blue-500"></div>
                        </label>
                    </div>
                </div>
                <input type="email" id="recipientEmail" name="recipientEmail" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100 cursor-not-allowed"
                       required disabled>
            </div>
            
            <div class="mb-4">
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                <input type="text" id="subject" name="subject" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-not-allowed"
                       value="Important: Student Health Update" readonly required>
            </div>
            
            <div class="mb-4">
                <div class="flex justify-between items-center mb-1">
                    <label for="message" class="text-sm font-medium text-gray-700">Message</label>
                    <div class="flex space-x-2">
                        <button type="button" onclick="loadTemplate()" class="text-xs px-3 py-1 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-md border border-blue-100 transition-colors flex items-center">
                            <i class='bx bx-file-blank text-sm mr-1'></i> Load Template
                        </button>
                        <button type="button" onclick="clearMessage()" class="text-xs px-3 py-1 bg-gray-50 text-gray-600 hover:bg-gray-100 rounded-md border border-gray-200 transition-colors flex items-center">
                            <i class='bx bx-trash text-sm mr-1'></i> Clear
                        </button>
                    </div>
                </div>
                <textarea id="message" name="message" rows="6" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Dear Guardian, \n\nThis is to inform you about your child's recent health update..." required></textarea>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="CloseAlertGuardianModal()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                    Cancel
                </button>
                <button id="sendNotificationBtn" type="button" onclick="sendNotification()"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors flex items-center">
                    <i class='bx bx-send mr-2'></i> Send Notification
                </button>
            </div>
        </div>
    </div>
</div>

<script src="../Functions/scripts.js"></script>
<script>

function sendNotification() {
    const sendBtn = document.getElementById('sendNotificationBtn');
    const originalBtnText = sendBtn.innerHTML;
    
    // Disable button and show loading state
    sendBtn.disabled = true;
    sendBtn.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-2"></i> Sending...';
    
    const recipientEmail = document.getElementById('recipientEmail').value;
    const subject = document.getElementById('subject').value;
    const message = document.getElementById('message').value;
    
    formData = new FormData();
    formData.append('recipientEmail', recipientEmail);
    formData.append('subject', subject);
    formData.append('message', message);
    
    fetch('../Functions/send_notification.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        // Re-enable button and restore original text
        sendBtn.disabled = false;
        sendBtn.innerHTML = originalBtnText;
        
        if (data.success) {
            showAlert(data.message, 'success');
            // Close the modal after successful send if needed
            // CloseAlertGuardianModal();
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert(error.message, 'error');
    });    
}

function CloseAlertGuardianModal() {
    document.getElementById('alertGuardianModal').classList.add('hidden');
    document.getElementById('viewStudentModal').style.display = 'block';
}

function toggleEmailEdit(isEditable) {
    const emailInput = document.getElementById('recipientEmail');
    emailInput.disabled = !isEditable;
    emailInput.classList.toggle('bg-gray-100', !isEditable);
    emailInput.classList.toggle('bg-white', isEditable);
    emailInput.classList.toggle('cursor-not-allowed', !isEditable);
    emailInput.classList.toggle('cursor-pointer', isEditable);
    
    // Focus the input when enabled
    if (isEditable) {
        setTimeout(() => emailInput.focus(), 100);
    }
}

function loadTemplate() {
    const messageField = document.getElementById('message');
    const studentName = document.getElementById('studentName')?.textContent || 'Student';
    const currentDate = new Date().toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
    
    const template = `Dear Guardian,

This is to inform you about your child's (${studentName}) recent health update from the school clinic on ${currentDate}.

Details:
- Date of Visit: ${currentDate}
- Reason: [Brief description of the health concern]
- Action Taken: [Treatment provided or recommended]
- Follow-up: [Any follow-up instructions or recommendations]

If you have any questions or need further information, please don't hesitate to contact the school clinic.

Sincerely,
School Health Center`;
    
    messageField.value = template;
    messageField.focus();
}

function clearMessage() {
    if (confirm('Are you sure you want to clear the message?')) {
        document.getElementById('message').value = '';
    }
}


</script>
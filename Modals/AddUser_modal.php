<style>
    /* Modal Backdrop */
    #addUserModal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    #addUserModal.show {
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 1;
    }
    
    /* Modal Content */
    #addUserModal .modal-content {
        background: white;
        border-radius: 8px;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s ease;
    }
    
    #addUserModal.show .modal-content {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show modal function
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = 'flex';
            void modal.offsetWidth;
            setTimeout(() => modal.classList.add('show'), 10);
            document.body.style.overflow = 'hidden';
        }
    };

    // Close modal function
    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
                const form = document.getElementById('addUserForm');
                if (form) form.reset();
            }, 300);
        }
    };

    // Close modal when clicking outside the content
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('modal')) {
            closeModal(event.target.id);
        }
    });

    // Handle form submission with AJAX
    // const addUserForm = document.getElementById('addUserForm');
    // if (addUserForm) {
    //     addUserForm.addEventListener('submit', function(e) {
    //         e.preventDefault();
            
    //         // Disable submit button and show loading state
    //         const submitBtn = this.querySelector('button[type="submit"]');
    //         const originalBtnText = submitBtn.innerHTML;
    //         submitBtn.disabled = true;
    //         submitBtn.innerHTML = 'Adding...';
            
    //         // Get form data
    //         const formData = new FormData(this);
            
    //         // Send AJAX request
    //         fetch('../Functions/UserFunctions.php', {
    //             method: 'POST',
    //             body: formData
    //         })
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data.success) {
    //                 // Show success message
    //                 alert('User added successfully! ' + (data.message || ''));
    //                 // Close modal and refresh user list
    //                 closeModal('addUserModal');
    //                 if (window.refreshUserList) {
    //                     window.refreshUserList();
    //                 }
    //             } else {
    //                 // Show error message
    //                 alert('Error: ' + (data.message || 'Failed to add user'));
    //             }
    //         })
    //         .catch(error => {
    //             console.error('Error:', error);
    //             alert('An error occurred while processing your request.');
    //         })
    //         .finally(() => {
    //             // Reset button state
    //             submitBtn.disabled = false;
    //             submitBtn.innerHTML = originalBtnText;
    //         });
    //     });
    // }
});
</script>

<div id="addUserModal" class="modal">
    <div class="modal-content" style="padding: 2rem;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="margin: 0; color: #1f2937; font-size: 1.25rem; font-weight: 600;">Add New User</h3>
            <span class="close" onclick="closeModal('addUserModal')" style="font-size: 1.5rem; font-weight: bold; color: #6b7280; cursor: pointer;">&times;</span>
        </div>
        <div class="modal-body">
            <form id="addUserForm" method="POST" action="../Functions/userFunctions.php">
                <div class="form-row" style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label for="firstName" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151;">First Name <span style="color: #ef4444;">*</span></label>
                        <input type="text" id="firstName" name="firstName" required 
                               style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;">
                    </div>
                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label for="lastName" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151;">Last Name <span style="color: #ef4444;">*</span></label>
                        <input type="text" id="lastName" name="lastName" required 
                               style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;">
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="middleName" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151;">Middle Name <span clas="text-gray-300">(Optional)</span></label>
                    <input type="text" id="middleName" name="middleName" 
                           style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;">
                </div>
                
                <div class="form-row" style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label for="contactNumber" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151;">Contact Number <span style="color: #ef4444;">*</span></label>
                        <input type="tel" id="contactNumber" name="contactNumber" required 
                               pattern="[0-9]{11}" title="Please enter a valid 11-digit phone number"
                               style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;">
                    </div>
                    <div class="form-group" style="flex: 1; margin-bottom: 0;">
                        <label for="role" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151;">Role <span style="color: #ef4444;">*</span></label>
                        <input type="text" id="role" name="role" placeholder="Staff" disabled
                               style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; cursor: not-allowed; background-color: #f3f4f6;">
                    </div>
                </div>
                
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="email" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151;">Email Address <span style="color: #ef4444;">*</span></label>
                    <input type="email" id="email" name="email" required 
                           style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;">
                </div>
                
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="password" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151;">Password <span style="color: #ef4444;">*</span></label>
                    <input type="password" id="password" name="password" disabled placeholder="Auto-Generated and Sent VIA email" 
                               style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem; cursor: not-allowed; background-color: #f3f4f6;">
                </div>
                
                <input type="hidden" name="action" value="addUser">
                
                <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 0.75rem;">
                    <button type="button" id="cancelBtn" onclick="closeModal('addUserModal')" 
                            style="padding: 0.5rem 1rem; background-color: #f3f4f6; border: 1px solid #d1d5db; border-radius: 0.375rem; color: #374151; cursor: pointer; font-weight: 500;">
                        Cancel
                    </button>
                    <button type="submit" id="submitBtn" name="send"
                            style="display: inline-flex; align-items: center; justify-content: center; padding: 0.5rem 1rem; background-color: #4f46e5; border: none; border-radius: 0.375rem; color: white; font-weight: 500; cursor: pointer; min-width: 100px;">
                        <span id="submitText">Add User</span>
                        <span id="submitSpinner" style="display: none; margin-left: 8px;">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                    <script>
                    document.getElementById('addUserForm').addEventListener('submit', function(e) {
                        const submitBtn = document.getElementById('submitBtn');
                        const submitText = document.getElementById('submitText');
                        const submitSpinner = document.getElementById('submitSpinner');
                        const cancelBtn = document.getElementById('cancelBtn');
                        
                        // Disable the button and show spinner
                        submitBtn.disabled = true;
                        submitText.textContent = 'Adding...';
                        submitSpinner.style.display = 'inline-flex';
                        cancelBtn.disabled = true;
                        
                        // Re-enable if form submission fails
                        window.addEventListener('unhandledrejection', function() {
                            submitBtn.disabled = false;
                            submitText.textContent = 'Add User';
                            submitSpinner.style.display = 'none';
                            cancelBtn.disabled = false;
                        });
                    });
                    </script>
                </div>
            </form>
        </div>
    </div>
</div>
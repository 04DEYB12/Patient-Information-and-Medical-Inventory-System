<style>
    /* ----- Status Toggle Button ----- */
    .status-toggle-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border: 1px solid #dee2e6;
        border-radius: 20px;
        background-color: #f8f9fa;
        color: #495057;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .status-toggle-btn:hover {
        background-color: #e9ecef;
        border-color: #cbd5e0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .status-toggle-btn i {
        font-size: 1rem;
    }
    .status-toggle-btn[data-status="Active"] {
        color: #28a745;
        border-color: #28a745;
        background-color: rgba(40, 167, 69, 0.1);
    }
    .status-toggle-btn[data-status="Inactive"] {
        color: #6c757d;
        border-color: #6c757d;
    }
    /* ----- Modal Backdrop & Container ----- */
    .modern-modal-backdrop {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(17, 24, 39, 0.6); /* Darker, more modern backdrop */
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
        padding: 1rem;
        box-sizing: border-box;
        overflow-y: auto; /* Allows scrolling if content is long */
    }

    .modern-modal-backdrop.show {
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 1;
    }

    .modern-modal-content {
        background: #ffffff;
        border-radius: 16px; /* Slightly more rounded corners */
        width: 100%;
        max-width: 580px; /* Slightly wider */
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15); /* Softer, larger shadow */
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); /* Smoother transition */
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 2rem);
    }

    .modern-modal-backdrop.show .modern-modal-content {
        opacity: 1;
        transform: translateY(0);
    }

    /* ----- Modal Header ----- */
    .modern-modal-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }

    .modern-modal-header h3 {
        margin: 0;
        color: #1f2937;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .modern-close-btn {
        background: none;
        border: none;
        font-size: 1.8rem;
        color: #9ca3af;
        cursor: pointer;
        line-height: 1;
        transition: color 0.2s ease;
    }
    
    .modern-close-btn:hover {
        color: #4b5563;
    }

    /* ----- Modal Body ----- */
    .modern-modal-body {
        padding: 2rem;
        overflow-y: auto;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .modern-modal-body::-webkit-scrollbar {
        display: none;
    }

    .modern-profile-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .modern-avatar {
        width: 96px;
        height: 96px;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, #6366f1, #4f46e5); /* Gradient for a modern look */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.25rem;
        font-weight: 600;
        box-shadow: 0 8px 16px rgba(79, 70, 229, 0.2);
    }

    .modern-profile-header h3 {
        margin: 0.5rem 0 0.25rem;
        color: #1f2937;
        font-size: 1.75rem;
        font-weight: 600;
    }
    
    .modern-status-badge {
        display: inline-block;
        padding: 0.25rem 1rem;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Active Status */
    .status-active {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    /* Inactive Status */
    .status-inactive {
        background-color: #fee2e2;
        color: #991b1b;
    }
    
    .modern-user-details {
        border-radius: 12px;
    }
    .modern-divider{
        background: #f9fafb;
        border-radius: 10px;
        width: 100%;
        padding: 1.5rem;
    }

    .modern-detail-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .modern-detail-row:last-child {
        border-bottom: none;
    }

    .modern-detail-label {
        color: #6b7280;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .modern-detail-value {
        font-weight: normal;
        font-size: 1rem;
        color: #525252ff;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* ----- Interactive Elements ----- */
    .modern-btn-icon-only {
        background: none;
        border: none;
        color: #6366f1;
        cursor: pointer;
        padding: 0;
        transition: color 0.2s ease;
    }
    
    .modern-btn-icon-only:hover {
        color: #4338ca;
    }
    
    .modern-input-group {
        display: hidden;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
        flex-grow: 1;
    }
    
    .modern-input-group input {
        flex: 1;
        padding: 0.625rem 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    
    .modern-input-group input:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
    }
    
    .modern-btn-primary {
        background: #4f46e5;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.625rem 1.25rem;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s ease, transform 0.1s ease;
    }
    
    .modern-btn-primary:hover {
        background-color: #4338ca;
    }
    
    .modern-btn-primary:active {
        transform: scale(0.98);
    }
    
    .modern-btn-secondary {
        background: #f3f4f6;
        color: #4b5563;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 0.625rem 1.25rem;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s ease, color 0.2s ease, transform 0.1s ease;
    }
    
    .modern-btn-secondary:hover {
        background-color: #e5e7eb;
        color: #374151;
    }

    .modern-btn-secondary:active {
        transform: scale(0.98);
    }

    .modern-btn-reset-password {
        background: #fff;
        color: #4f46e5;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    
    .modern-btn-reset-password:hover {
        background: #f9fafb;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    /* ----- Modal Footer ----- */
    .modern-modal-footer {
        padding: 1.5rem 2rem;
        border-top: 1px solid #e5e7eb;
        text-align: right;
        margin-top: auto;
    }
</style>

<div id="ViewUserModal" class="modern-modal-backdrop">
    <div class="modern-modal-content ">
        <div class="modern-modal-header">
            <h3>User Profile</h3>
            <button type="button" class="modern-close-btn" onclick="closeModal('ViewUserModal')">&times;</button>
        </div>
        
        <div class="modern-modal-body">
            <div id="profileContent">
                <div class="modern-profile-header">
                    <div class="modern-avatar" id="userAvatar">
                        </div>
                    <h3 id="userName"></h3>
                    <span class="modern-status-badge status-active" id="userStatus">Active</span>
                </div>
                
                <div class="modern-user-details">
                    <div class="modern-divider">
                        <!-- ----- User ID ----- -->
                        <div class="modern-detail-row">
                            <div class="modern-detail-label">
                                <i class='bx bx-id-card'></i> 
                                <span>User ID</span>
                            </div>
                            <div class="modern-detail-value" id="userId"></div>
                        </div>
                        
                        <!-- ----- User Contact ----- -->
                        <div class="modern-detail-row">
                            <div class="modern-detail-label">
                                <i class='bx bx-phone'></i> 
                                <span>Contact</span>
                            </div>
                            <div class="modern-detail-value" id="userContact"></div>
                        </div>

                        <!-- ----- User Join Date ----- -->
                        <div class="modern-detail-row mb-6">
                            <div class="modern-detail-label">
                                <i class='bx bx-calendar'></i> 
                                <span>Join Date</span>
                            </div>
                            <div class="modern-detail-value" id="userJoinDate"></div>
                        </div>  
                    </div>

                    <p class="border-top-2 border-gray-200 p-2"> Actions:</p>

                    <div class="modern-divider">
                        <!-- ----- User Role ----- -->
                        <div class="modern-detail-row">
                            <div class="modern-detail-label">
                                <i class='bx bx-briefcase'></i> 
                                <span>Role</span>
                                <input type="hidden" id="userRole">
                            </div>
                            <button type="button" id="roleToggleBtn" class="role-toggle-btn bg-blue-100 text-blue-600 border-2 border-blue-400 rounded-full p-2" title="Toggle Role" onclick="const currentRole = document.getElementById('userRole').textContent === 'Staff' ? 'Administrator' : 'Staff'; toggleUserRole(document.getElementById('hidden_userId').value, currentRole)">
                                <i class='bx bx-shield'></i>
                                <span id="roleText">Loading...</span>
                            </button>
                        </div>
                        
                        <!-- ----- User Account Status ----- -->
                        <div class="modern-detail-row">
                            <div class="modern-detail-label">
                                <i class='bx bx-toggle-left'></i> 
                                <span>Account Status</span>
                            </div>
                            <button type="button" id="userStatustry" class="status-toggle-btn" title="Toggle Status" onclick="const currentStatus = document.getElementById('userStatus').textContent === 'Active' ? 'Inactive' : 'Active'; toggleUserStatus(document.getElementById('hidden_userId').value, currentStatus)">
                                <i class='bx bx-power-off'></i>
                                <span id="statusText">Toggle Status</span>
                            </button>
                        </div>
                        
                        <!-- ----- User Email ----- -->
                        <div class="modern-detail-row flex flex-col gap-3 p-4 bg-white rounded-xl border transition mt-2">
    
                            <!-- Label -->
                            <div class="modern-detail-label flex items-center gap-2 text-gray-700 font-medium">
                                <i class='bx bx-envelope text-indigo-600 text-xl'></i> 
                                <span>Email</span>
                            </div>

                            <!-- Value + Edit Button -->
                            <div class="modern-detail-value flex items-center justify-between bg-gray-50 px-3 py-2 rounded-lg" id="emailContainer">
                                <span id="userEmail" class="text-gray-900 font-medium"></span>
                                <button 
                                    type="button" 
                                    id="editEmailBtn" 
                                    class="modern-btn-icon-only p-2 rounded-md text-indigo-600 hover:bg-indigo-100 transition" 
                                    title="Edit Email"
                                >
                                    <i class='bx bx-edit-alt text-lg  px-2' ></i>
                                </button>
                            </div>

                            <!-- Edit Form (hidden by default) -->
                            <div id="emailEditContainer" class="modern-input-group hidden">
                                <form id="emailForm" method="post" action="../Functions/UserFunctions.php" class="flex flex-col gap-3">
                                    
                                    <input 
                                        type="email" 
                                        name="editEmailInput" 
                                        id="editEmailInput" 
                                        placeholder="Enter new email" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                                    >

                                    <input type="hidden" name="hidden_userId" id="hidden_userId">
                                    <input type="hidden" name="action" value="updateEmail">

                                    <!-- Buttons -->
                                    <div class="flex gap-2 w-full flex items-center justify-center">
                                        <button 
                                            type="submit" 
                                            id="saveEmailBtn" 
                                            class="modern-btn-primary px-4 py-2 rounded-md bg-indigo-600 text-white hover:bg-indigo-700 transition w-[100px]"  
                                        >
                                            Save
                                        </button>
                                        <button 
                                            type="button" 
                                            id="cancelEditBtn" 
                                            class="modern-btn-secondary px-4 py-2 rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300 transition w-[100px]"
                                        >
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        
                        <!-- ----- User Reset Password ----- -->
                        <div class="modern-detail-row flex flex-col bg-white border-2 border-gray-100 gap-4 mt-2 rounded-lg">
                            <div class="modern-detail-label">
                                <i class='bx bx-lock text-xl text-indigo-600'></i> 
                                <span>Password</span>
                            </div>
                            <div class="modern-detail-value">
                                <button type="button" id="resetPasswordBtn" class="modern-btn-reset-password">
                                    <i class='bx bx-refresh'></i>
                                    Reset Password
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
            </div>
        </div>
        
        <div class="modern-modal-footer">
            <button type="button" onclick="closeModal('ViewUserModal')" class="modern-btn-secondary">
                Close
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editEmailBtn = document.getElementById('editEmailBtn');
    const saveEmailBtn = document.getElementById('saveEmailBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const resetPasswordBtn = document.getElementById('resetPasswordBtn');
    const emailContainer = document.getElementById('emailContainer');
    const emailEditContainer = document.getElementById('emailEditContainer');
    const userEmail = document.getElementById('userEmail');
    const editEmailInput = document.getElementById('editEmailInput');

    // Toggle email edit mode
    editEmailBtn?.addEventListener('click', function() {
        emailContainer.style.display = 'none';
        emailEditContainer.style.display = 'flex';
        editEmailInput.value = userEmail.textContent.trim();
        editEmailInput.focus();
    });

    // Cancel email edit
    cancelEditBtn?.addEventListener('click', function() {
        emailEditContainer.style.display = 'none';
        emailContainer.style.display = 'flex';
    });

    // Save email
    saveEmailBtn?.addEventListener('click', function() {
        const newEmail = editEmailInput.value.trim();
        if (newEmail && newEmail !== userEmail.textContent.trim()) {
            // Here you would typically make an API call to update the email
            console.log('Updating email to:', newEmail);
            userEmail.textContent = newEmail;
            emailEditContainer.style.display = 'none';
            emailContainer.style.display = 'flex';
        }
    });

    // Handle Enter key in email input
    editEmailInput?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            saveEmailBtn.click();
        }
    });

    // Reset password functionality
    resetPasswordBtn?.addEventListener('click', function() {
        if (confirm('Are you sure you want to reset this user\'s password? A new temporary password will be generated and sent to their email.')) {
            const userId = document.getElementById('hidden_userId').value;
            const userFullName = document.getElementById('userName').textContent.trim();
            
            // temporarily hide the button
            const originalBtnText = resetPasswordBtn.innerHTML;
            resetPasswordBtn.style.display = 'none';
            
            // Show loading indicator in the UI
            const statusElement = document.createElement('div');
            statusElement.className = 'alert alert-info mt-3';
            statusElement.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Resetting password...';
            resetPasswordBtn.parentNode.insertBefore(statusElement, resetPasswordBtn.nextSibling);
            
            // Make API call to reset password
            fetch('../Functions/UserFunctions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=PasswordReset&userId=${encodeURIComponent(userId)}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success message
                    statusElement.className = 'alert alert-success mt-3';
                    statusElement.innerHTML = `<i class="fas fa-check-circle"></i> ${data.message}`;
                    
                    // If we have a new password (in case email failed)
                    if (data.newPassword) {
                        statusElement.innerHTML += `<br><br><strong>New Password:</strong> ${data.newPassword}`;
                        statusElement.innerHTML += '<br><span class="text-muted">Please provide this password to the user manually.</span>';
                    }
                } else {
                    throw new Error(data.error || 'Failed to reset password');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                statusElement.className = 'alert alert-danger mt-3';
                statusElement.innerHTML = `<i class="fas fa-exclamation-circle"></i> Error: ${error.message || 'Failed to reset password'}`;
            })
            .finally(() => {
                // Reset button state
                resetPasswordBtn.disabled = false;
                resetPasswordBtn.innerHTML = '<i class="fas fa-key"></i> Reset Password';
                
                // Remove status message after 5 seconds
                setTimeout(() => {
                    if (statusElement.parentNode) {
                        statusElement.remove();
                        resetPasswordBtn.style.display = 'block';
                    }
                }, 5000);
            });
        }
    });
});

// Toggle user status between Active and Inactive
function toggleUserStatus(userId, newStatus) {
    if (confirm(`Are you sure you want to ${newStatus === 'Active' ? 'activate' : 'deactivate'} this user?`)) {
        const formData = new FormData();
        formData.append('action', 'toggleUserStatus');
        formData.append('userId', userId);
        formData.append('newStatus', newStatus);
        
        fetch('../Functions/UserFunctions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh the user list to show updated status
                alert('User status updated successfully!');
                window.location.reload();
            } else {
                alert('Error: ' + (data.error || 'Failed to update user status'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating user status');
        });
    }
}

// Toggle user role between Admin and Staff
function toggleUserRole(userId, newRole) {
    if (confirm(`Are you sure you want to change this user's role to ${newRole}?`)) {
        const formData = new FormData();
        formData.append('action', 'updateUserRole');
        formData.append('userId', userId);
        formData.append('newRole', newRole);
        
        fetch('../Functions/UserFunctions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh the user list to show updated Role
                alert('User Role updated successfully!');
                window.location.reload();
            } else {
                alert('Error: ' + (data.error || 'Failed to update user role'));
            }
        })
        .catch(error => {
            console.error('Error updating user role:', error);
            alert('An error occurred while updating user role: ' + error.message);
        });
    }
}
</script>
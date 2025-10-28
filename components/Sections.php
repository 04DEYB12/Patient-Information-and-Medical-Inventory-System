<section class="content-section" id="NameSection">
    <div class="flex items-center p-4 border-b border-gray-200">
        <button id="ArrowbackButton" onclick="goBackToProfile()" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors duration-200 mr-4">
            <i class='bx bx-chevron-left text-2xl'></i>
            <span class="ml-1 text-lg">Name</span>
        </button>
    </div>
    <div class="p-6">
        <h2 class="text-xl font-semibold mb-4">Edit Name</h2>
        <p class="text-gray-600 mb-6">Changes to your name will be reflected across your PIAMIS Account.</p>
        
        <div class="flex items-center justify-center gap-2 w-full">
            <div class="mb-4 w-full"> 
                <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                <input type="text" id="firstName" name="firstName" class="w-full p-2 py-[10px] border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="<?php echo htmlspecialchars($firstname); ?>">
            </div>
            
            <div class="mb-4 w-full">
                <label for="middleName" class="block text-sm font-medium text-gray-700 mb-1">Middle Name</label>
                <input type="text" id="middleName" name="middleName" class="w-full p-2 py-[10px] border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="<?php echo htmlspecialchars($middlename); ?>">
            </div>
            
            <div class="mb-4 w-full">
                <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                <input type="text" id="lastName" name="lastName" class="w-full p-2 py-[10px] border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="<?php echo htmlspecialchars($lastname); ?>">
            </div>
        </div>
        
        <div class="flex items-start gap-3 bg-blue-50 p-4 rounded-lg mb-6">
            <i class='bx bx-info-circle text-blue-600 text-xl mt-0.5'></i>
            <div>
                <h2 class="text-lg font-medium text-gray-800 mb-1">Name Visibility</h2>
                <p class="text-sm text-gray-600">Your name will be visible to other users within the PIAMIS system when they interact with you or view your activities.</p>
            </div>
        </div>
        <div class="flex gap-4">
            <button id="backButton" onclick="goBackToProfile()" type="button" class="flex-1 bg-gray-200 text-gray-800 p-2 rounded-md hover:bg-gray-300 transition-colors duration-200">Back</button>
            <button id="saveButton" onclick="saveChanges()" type="submit" class="flex-1 bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700 transition-colors duration-200">Save Changes</button>
        </div>
        
        <div id="passwordSection" class="hidden mt-4 space-y-3">
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-gray-700">Confirm your password</label>
                <span class="text-xs text-gray-500">Required for security</span>
            </div>
            <div class="flex gap-2">
                <div class="relative flex-1">
                    <input type="password" id="password" name="password" placeholder="Enter Password" class="w-full p-2 pr-10 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                    <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700" aria-label="Toggle password visibility">
                        <i class='bx bx-show text-xl'></i>
                    </button>
                </div>
                <button type="button" id="cancel" onclick="resetFormState()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200">Cancel</button>
                <button type="submit" id="submit" onclick="updateName()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">Confirm</button>
            </div>
        </div>
    </div>
</section>

<section class="content-section" id="PasswordSection">
    <div class="flex items-center p-4 border-b border-gray-200">
        <button id="ArrowbackButton" onclick="goBackToProfile()" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors duration-200 mr-4">
            <i class='bx bx-chevron-left text-2xl'></i>
            <span class="ml-1 text-lg">Password</span>
        </button>
    </div>
    <div class="p-6 max-w-2xl mx-auto" id="PasswordChanges" style="display: none;">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Change Your Password</h2>
                <p class="text-gray-600">Secure your account with a new password</p>
            </div>

            <div class="space-y-6">
                <div class="space-y-1">
                    <div class="flex justify-between items-center">
                        <label for="NewPassword" class="block text-sm font-medium text-gray-700">New Password</label>
                        <span id="passwordStrength" class="text-xs font-medium"></span>
                    </div>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="NewPassword" 
                            name="NewPassword" 
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" 
                            placeholder="Create a strong password"
                            oninput="checkPasswordStrength(this.value)"
                            required
                        >
                        <button 
                            type="button" 
                            onclick="togglePasswordVisibility('NewPassword', 'toggleNewPassword')" 
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                            id="toggleNewPassword"
                        >
                            <i class='bx bx-show text-xl'></i>
                        </button>
                    </div>
                    <div class="h-1.5 bg-gray-200 rounded-full mt-2 overflow-hidden">
                        <div id="passwordStrengthBar" class="h-full w-0 bg-red-500 transition-all duration-300"></div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        Use 8 or more characters with a mix of letters, numbers & symbols
                    </p>
                </div>

                <div class="space-y-1">
                    <label for="ConfirmNewPassword" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                    <div class="relative">
                        <input 
                            type="password" 
                            id="ConfirmNewPassword" 
                            name="ConfirmNewPassword" 
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200" 
                            placeholder="Re-enter your new password"
                            oninput="checkPasswordMatch()"
                            required
                        >
                        <button 
                            type="button" 
                            onclick="togglePasswordVisibility('ConfirmNewPassword', 'toggleConfirmPassword')" 
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700"
                            id="toggleConfirmPassword"
                        >
                            <i class='bx bx-show text-xl'></i>
                        </button>
                    </div>
                    <p id="passwordMatchMessage" class="mt-1 text-xs"></p>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="flex items-start">
                        <i class='bx bx-info-circle text-blue-500 text-xl mt-0.5 mr-2'></i>
                        <div>
                            <h3 class="font-medium text-blue-800">Password Tips</h3>
                            <ul class="mt-1 text-sm text-blue-700 list-disc list-inside space-y-1">
                                <li>Use at least 8 characters</li>
                                <li>Include numbers, letters, and special characters</li>
                                <li>Avoid common words or personal information</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 pt-4">
                    <button 
                        type="button" 
                        onclick="goBackToProfile()" 
                        class="flex-1 bg-gray-100 text-gray-700 font-medium py-2.5 px-4 rounded-lg hover:bg-gray-200 transition duration-200 border border-gray-300"
                    >
                        Cancel
                    </button>
                    <button 
                        type="button" 
                        id="UpdatePassword" 
                        onclick="UpdatePassword('<?php echo $user_id; ?>')" 
                        class="flex-1 bg-blue-600 text-white font-medium py-2.5 px-4 rounded-lg hover:bg-blue-700 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled
                    >
                        Update Password
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="p-6" id="ConfirmUser">
        <div class="max-w-md mx-auto">
            <h2 class="text-xl font-semibold mb-2">Verify Your Identity</h2>
            <p class="text-gray-600 mb-6">For security reasons, please enter your current password to continue.</p>
            
            <div class="mb-6">
                <label for="currentPassword" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                <div class="relative">
                    <input type="password" id="currentPassword" name="currentPassword" 
                            class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 pr-10"
                            placeholder="Enter your current password" required>
                    <button type="button" onclick="togglePasswordVisibility('currentPassword', 'toggleCurrentPassword')" 
                            id="toggleCurrentPassword" 
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700" 
                            aria-label="Toggle password visibility">
                        <i class='bx bx-show text-xl'></i>
                    </button>
                </div>
            </div>
            
            <div class="flex gap-3 justify-end">
                <button type="button" 
                        onclick="goBackToProfile()" 
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors duration-200">
                    Cancel
                </button>
                <button type="button" 
                        onclick="verifyPassword('<?php echo $user_id; ?>')" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                    Verify
                </button>
            </div>
        </div>
    </div>
</section>

<script>
    function goBackToProfile() {
        document.getElementById('NameSection').classList.remove('active');
        document.getElementById('PasswordSection').classList.remove('active');
        document.getElementById('MyProfileSection').classList.add('active');
        document.getElementById('currentPassword').value = '';
        document.getElementById('PasswordChanges').style.display = 'none';
        document.getElementById('ConfirmUser').style.display = 'block';
        document.getElementById('NewPassword').value = '';
        document.getElementById('ConfirmNewPassword').value = '';
        document.getElementById('passwordStrength').innerHTML = '';
        document.getElementById('passwordMatchMessage').innerHTML = '';
        document.getElementById('passwordStrengthBar').className = 'w-0 h-2 bg-blue-600';
    }
    
    function togglePasswordVisibility(inputId, toggleButtonId) {
        const passwordInput = document.getElementById(inputId);
        const toggleButton = document.getElementById(toggleButtonId);
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleButton.innerHTML = "<i class='bx bx-hide text-xl'></i>";
        } else {
            passwordInput.type = 'password';
            toggleButton.innerHTML = "<i class='bx bx-show text-xl'></i>";
        }
    }
    
    // Add event listener for the toggle button
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('togglePassword');
        if (toggleButton) {
            toggleButton.addEventListener('click', togglePasswordVisibility);
        }
    });
    
    function resetFormState() {
        // Show the main buttons
        document.getElementById('backButton').style.display = 'block';
        document.getElementById('saveButton').style.display = 'block';
        
        // Hide the password section
        document.getElementById('passwordSection').classList.add('hidden');
        
        // Clear and reset the password field
        const passwordInput = document.getElementById('password');
        passwordInput.value = '';
        passwordInput.type = 'password';
        // Reset the toggle icon if it was changed
        const toggleButton = document.getElementById('togglePassword');
        if (toggleButton) {
            toggleButton.innerHTML = "<i class='bx bx-show text-xl'></i>";
        }
    }
    
    // UPDATING NAME SCRIPTSSS
    function saveChanges() {
        const firstName = document.getElementById('firstName').value.trim();
        const middleName = document.getElementById('middleName').value.trim();
        const lastName = document.getElementById('lastName').value.trim();
        
        let hasAnyValue = false;
        hasAnyValue = firstName !== "" || middleName !== "" || lastName !== "";
        
        if(hasAnyValue) {
            // Hide the main buttons
            document.getElementById('backButton').style.display = 'none';
            document.getElementById('saveButton').style.display = 'none';
            
            // Show the password section
            document.getElementById('passwordSection').classList.remove('hidden');
            document.getElementById('password').focus();
        }else{
            alert('No changes detected');
            return;
        }
    }
    
    function updateName() {
        if (confirm(`Are you sure you want to save changes?`)) {
            const formData = new FormData();
            formData.append('action', 'updateUser_Name');
            formData.append('userId', <?php echo $user_id; ?>);
            formData.append('password', document.getElementById('password').value.trim());
            formData.append('firstName', document.getElementById('firstName').value.trim());
            formData.append('middleName', document.getElementById('middleName').value.trim());
            formData.append('lastName', document.getElementById('lastName').value.trim());
            
            fetch('../Functions/UserFunctions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Refresh the user list to show updated status
                    alert('Your Name updated successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.error || 'Failed to update your Name'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating your Name');
            });
        }
    }
    // UPDATING NAME SCRIPTSSS ENDS HERE
    
    // UPDATING PASSWORD SCRIPTSSS
    // Check password strength and update UI
    function checkPasswordStrength(password) {
        const strengthBar = document.getElementById('passwordStrengthBar');
        const strengthText = document.getElementById('passwordStrength');
        const saveButton = document.getElementById('saveButton');
        let strength = 0;
        
        // Reset
        strengthBar.className = 'h-full transition-all duration-300';
        
        // Empty password
        if (password.length === 0) strength = 0;
        // Length check
        if (password.length >= 8) strength += 1;
        // Contains numbers
        if (password.match(/([0-9])/)) strength += 1;
        // Contains lowercase
        if (password.match(/([a-z])/)) strength += 1;
        // Contains uppercase
        if (password.match(/([A-Z])/)) strength += 1;
        // Contains special chars
        if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1;
        
        // Update UI based on strength
        switch(strength) {
            case 0:
                strengthBar.className += ' w-0 bg-gray-200';
                strengthText.textContent = 'Empty';
                strengthText.className = 'text-xs font-medium text-gray-600';
                break;
            case 1:
                strengthBar.className += ' w-1/4 bg-red-500';
                strengthText.textContent = 'Weak';
                strengthText.className = 'text-xs font-medium text-red-600';
                break;
            case 2:
                strengthBar.className += ' w-2/4 bg-yellow-500';
                strengthText.textContent = 'Fair';
                strengthText.className = 'text-xs font-medium text-yellow-600';
                break;
            case 3:
                strengthBar.className += ' w-3/4 bg-blue-500';
                strengthText.textContent = 'Good';
                strengthText.className = 'text-xs font-medium text-blue-600';
                break;
            case 4:
            case 5:
                strengthBar.className += ' w-full bg-green-500';
                strengthText.textContent = 'Strong';
                strengthText.className = 'text-xs font-medium text-green-600';
                break;
        }
        
        // Check if passwords match when both fields have values
        const confirmPassword = document.getElementById('ConfirmNewPassword').value;
        if (confirmPassword) {
            checkPasswordMatch();
        }
        
        return strength >= 3; // Minimum 3/5 for valid password
    }
    
    // Check if passwords match
    function checkPasswordMatch() {
        const password = document.getElementById('NewPassword').value;
        const confirmPassword = document.getElementById('ConfirmNewPassword').value;
        const messageElement = document.getElementById('passwordMatchMessage');
        const UpdatePassword = document.getElementById('UpdatePassword');
        
        if (!password || !confirmPassword) {
            messageElement.textContent = '';
            UpdatePassword.disabled = true;
            return false;
        }
        
        if (password === confirmPassword) {
            messageElement.textContent = 'Passwords match!';
            messageElement.className = 'mt-1 text-xs text-green-600';
            UpdatePassword.disabled = password.length < 8;
            return true;
        } else {
            messageElement.textContent = 'Passwords do not match';
            messageElement.className = 'mt-1 text-xs text-red-600';
            UpdatePassword.disabled = true;
            return false;
        }
    }
    
    function verifyPassword(user_id) {
        const currentPassword = document.getElementById('currentPassword').value;
        
        // Basic client-side validation
        if (!currentPassword) {
            alert('Please enter your current password');
            return;
        }
        
        const formData = new FormData();
        formData.append('action', 'confirmPassword');
        formData.append('userId', user_id);
        formData.append('currentPassword', currentPassword);
        
        fetch('../Functions/UserFunctions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                alert(data.message);
                document.getElementById('PasswordChanges').style.display = 'block';
                document.getElementById('ConfirmUser').style.display = 'none';
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            alert('Error: ' + (error.message || 'Failed to verify password'));
        });
    }
    
    function UpdatePassword(user_id) {
        const confirmNewPassword = document.getElementById('ConfirmNewPassword').value;
        
        const formData = new FormData();
        formData.append('action', 'UpdatePassword');
        formData.append('userId', user_id);
        formData.append('confirmNewPassword', confirmNewPassword);
        
        fetch('../Functions/UserFunctions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log('Response data:', data);
            if (data.success) {
                alert(data.message);
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                } else {
                    window.location.reload();
                }
            } else {
                alert(data.error);
            }
        })
        .catch(error => {
            console.error('Error details:', {
                error: error.toString(),
                name: error.name,
                message: error.message,
                stack: error.stack
            });
            alert('Error: ' + (error.message || 'Failed to change your password'));
        });
    }
</script>    
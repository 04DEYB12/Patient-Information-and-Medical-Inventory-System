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
            <button id="backButtonAtName" onclick="goBackToProfile()" type="button" class="flex-1 bg-gray-200 text-gray-800 p-2 rounded-md hover:bg-gray-300 transition-colors duration-200">Back</button>
            <button id="saveButtonAtName" onclick="saveChanges('NameSection','backButtonAtName','saveButtonAtName','passwordSectionAtName','passwordAtName')" type="submit" class="flex-1 bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700 transition-colors duration-200">Save Changes</button>
        </div>
        
        <div id="passwordSectionAtName" class="hidden mt-4 space-y-3">
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-gray-700">Confirm your password</label>
                <span class="text-xs text-gray-500">Required for security</span>
            </div>
            <div class="flex gap-2">
                <div class="relative flex-1">
                    <input type="password" id="passwordAtName" name="password" placeholder="Enter Password" class="w-full p-2 pr-10 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                    <button type="button" onclick="togglePasswordVisibility('passwordAtName', 'togglePassword')" id="togglePassword" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700" aria-label="Toggle password visibility">
                        <i class='bx bx-show text-xl'></i>
                    </button>
                </div>
                <button type="button" id="cancel" onclick="resetFormState('backButtonAtName','saveButtonAtName','passwordSectionAtName','passwordAtName')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200">Cancel</button>
                <button type="submit" id="submit" onclick="updateName('<?php echo $user_id; ?>')" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">Confirm</button>
            </div>
        </div>
    </div>
</section>

<section class="content-section" id="EmailSection">
    <div class="flex items-center p-4 border-b border-gray-200">
        <button id="ArrowbackButton" onclick="goBackToProfile()" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors duration-200 mr-4">
            <i class='bx bx-chevron-left text-2xl'></i>
            <span class="ml-1 text-lg">Email</span>
        </button>
    </div>
    <div class="p-6">
        <h2 class="text-xl font-semibold mb-4">Edit Email</h2>
        <p class="text-gray-600 mb-6">Changes to your email will be reflected across your PIAMIS Account.</p>
        
        <div class="flex items-center justify-center gap-2 w-full">
            <div class="mb-4 w-full"> 
                <label for="CurrentEmail" class="block text-sm font-medium text-gray-700 mb-1">Current Email</label>
                <input type="text" id="CurrentEmail" class="w-full p-2 py-[10px] border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 cursor-not-allowed" disabled value="<?php echo htmlspecialchars($Email); ?>">
            </div>
            
            <div class="mb-4 w-full">
                <label for="NewEmail" class="block text-sm font-medium text-gray-700 mb-1">New Email</label>
                <input type="text" id="NewEmail" name="NewEmail" class="w-full p-2 py-[10px] border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your New Email here." oninput="autoAppendDomain(this)">
            </div>
        </div>
        
        <div class="flex items-start gap-3 bg-blue-50 p-4 rounded-lg mb-6">
            <i class='bx bx-info-circle text-blue-600 text-xl mt-0.5'></i>
            <div>
                <h2 class="text-lg font-medium text-gray-800 mb-1">Email Visibility</h2>
                <p class="text-sm text-gray-600">Your email will be visible to Administrator within the PIAMIS system when they interact with you.</p>
            </div>
        </div>
        <div class="flex gap-4">
            <button id="backButtonAtEmail" onclick="goBackToProfile()" type="button" class="flex-1 bg-gray-200 text-gray-800 p-2 rounded-md hover:bg-gray-300 transition-colors duration-200">Back</button>
            <button id="saveButtonAtEmail" onclick="saveChanges('EmailSection','backButtonAtEmail','saveButtonAtEmail','passwordSectionAtEmail','passwordAtEmail')" type="submit" class="flex-1 bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700 transition-colors duration-200">Save Changes</button>
        </div>
        
        <div id="passwordSectionAtEmail" class="hidden mt-4 space-y-3">
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-gray-700">Confirm your password</label>
                <span class="text-xs text-gray-500">Required for security</span>
            </div>
            <div class="flex gap-2">
                <div class="relative flex-1">
                    <input type="password" id="passwordAtEmail" name="password" placeholder="Enter Password" class="w-full p-2 pr-10 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                    <button type="button" onclick="togglePasswordVisibility('passwordAtEmail', 'togglePasswordAtEmail')" id="togglePasswordAtEmail" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700" aria-label="Toggle password visibility">
                        <i class='bx bx-show text-xl'></i>
                    </button>
                </div>
                <button type="button" id="cancel" onclick="resetFormState('backButtonAtEmail','saveButtonAtEmail','passwordSectionAtEmail','passwordAtEmail')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200">Cancel</button>
                <button type="submit" id="submit" onclick="updateEmail('<?php echo $user_id; ?>')" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">Confirm</button>
            </div>
        </div>
    </div>
</section>

<section class="content-section" id="PhoneSection">
    <div class="flex items-center p-4 border-b border-gray-200">
        <button id="ArrowbackButton" onclick="goBackToProfile()" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors duration-200 mr-4">
            <i class='bx bx-chevron-left text-2xl'></i>
            <span class="ml-1 text-lg">Phone Number</span>
        </button>
    </div>
    <div class="p-6">
        <h2 class="text-xl font-semibold mb-4">Edit Phone Number</h2>
        <p class="text-gray-600 mb-6">Changes to your phone number will be reflected across your PIAMIS Account.</p>
        
        <div class="flex items-center justify-center gap-2 w-full">
            <div class="mb-4 w-full"> 
                <label for="CurrentPhone" class="block text-sm font-medium text-gray-700 mb-1">Current Phone Number</label>
                <input type="text" id="CurrentPhone" class="w-full p-2 py-[10px] border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 cursor-not-allowed" disabled value="<?php echo htmlspecialchars($ContactNumber); ?>">
            </div>
            
            <div class="mb-4 w-full">
                <label for="NewPhone" class="block text-sm font-medium text-gray-700 mb-1">New Phone Number</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <img src="../Images/philippines.png" alt="PH" class="w-5 h-5">
                    </div>
                    <input type="text" id="NewPhone" name="NewPhone" onblur="validatePhoneNumber(this)" class="w-full pl-10 p-2 py-[10px] border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your New Phone Number here.">
                </div>
            </div>
        </div>
        
        <div class="flex items-start gap-3 bg-blue-50 p-4 rounded-lg mb-6">
            <i class='bx bx-info-circle text-blue-600 text-xl mt-0.5'></i>
            <div>
                <h2 class="text-lg font-medium text-gray-800 mb-1">Phone Number Visibility</h2>
                <p class="text-sm text-gray-600">Your phone number will be visible to Administrator within the PIAMIS system when they interact with you.</p>
            </div>
        </div>
        <div class="flex gap-4">
            <button id="backButtonAtPhone" onclick="goBackToProfile()" type="button" class="flex-1 bg-gray-200 text-gray-800 p-2 rounded-md hover:bg-gray-300 transition-colors duration-200">Back</button>
            <button id="saveButtonAtPhone" onclick="saveChanges('PhoneSection','backButtonAtPhone','saveButtonAtPhone','passwordSectionAtPhone','passwordAtPhone')" type="submit" class="flex-1 bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700 transition-colors duration-200">Save Changes</button>
        </div>
        
        <div id="passwordSectionAtPhone" class="hidden mt-4 space-y-3">
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-gray-700">Confirm your password</label>
                <span class="text-xs text-gray-500">Required for security</span>
            </div>
            <div class="flex gap-2">
                <div class="relative flex-1">
                    <input type="password" id="passwordAtPhone" name="password" placeholder="Enter Password" class="w-full p-2 pr-10 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                    <button type="button" onclick="togglePasswordVisibility('passwordAtPhone', 'togglePasswordAtPhone')" id="togglePasswordAtPhone" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700" aria-label="Toggle password visibility">
                        <i class='bx bx-show text-xl'></i>
                    </button>
                </div>
                <button type="button" id="cancel" onclick="resetFormState('backButtonAtPhone','saveButtonAtPhone','passwordSectionAtPhone','passwordAtPhone')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200">Cancel</button>
                <button type="button" id="submit" onclick="updatePhone('<?php echo $user_id; ?>')" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">Confirm</button>
            </div>
        </div>
    </div>
</section>

<section class="content-section" id="AddressSection">
    <div class="flex items-center p-4 border-b border-gray-200">
        <button id="ArrowbackButton" onclick="goBackToProfile()" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors duration-200 mr-4">
            <i class='bx bx-chevron-left text-2xl'></i>
            <span class="ml-1 text-lg">Address</span>
        </button>
    </div>
    <div class="p-6">
        <h2 class="text-xl font-semibold mb-4">Edit Address</h2>
        <p class="text-gray-600 mb-6">Changes to your address will be reflected across your PIAMIS Account.</p>
        
        <div class="flex items-center justify-center gap-2 w-full">
            <div class="mb-4 w-full"> 
                <label for="CurrentAddress" class="block text-sm font-medium text-gray-700 mb-1">Current Address</label>
                <input type="text" id="CurrentAddress" class="w-full p-2 py-[10px] border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 cursor-not-allowed" disabled value="<?php echo htmlspecialchars($Address); ?>">
            </div>
            
            <div class="mb-4 w-full">
                <label for="NewAddress" class="block text-sm font-medium text-gray-700 mb-1">New Address</label>
                <input type="text" id="NewAddress" name="NewAddress" class="w-full p-2 py-[10px] border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your New Address here.">
            </div>
        </div>
        
        <div class="flex items-start gap-3 bg-blue-50 p-4 rounded-lg mb-6">
            <i class='bx bx-info-circle text-blue-600 text-xl mt-0.5'></i>
            <div>
                <h2 class="text-lg font-medium text-gray-800 mb-1">Address Visibility</h2>
                <p class="text-sm text-gray-600">Your address will be visible to Administrator within the PIAMIS system when they interact with you.</p>
            </div>
        </div>
        <div class="flex gap-4">
            <button id="backButtonAtAddress" onclick="goBackToProfile()" type="button" class="flex-1 bg-gray-200 text-gray-800 p-2 rounded-md hover:bg-gray-300 transition-colors duration-200">Back</button>
            <button id="saveButtonAtAddress" onclick="saveChanges('AddressSection','backButtonAtAddress','saveButtonAtAddress','passwordSectionAtAddress','passwordAtAddress')" type="submit" class="flex-1 bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700 transition-colors duration-200">Save Changes</button>
        </div>
        
        <div id="passwordSectionAtAddress" class="hidden mt-4 space-y-3">
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-gray-700">Confirm your password</label>
                <span class="text-xs text-gray-500">Required for security</span>
            </div>
            <div class="flex gap-2">
                <div class="relative flex-1">
                    <input type="password" id="passwordAtAddress" name="password" placeholder="Enter Password" class="w-full p-2 pr-10 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                    <button type="button" onclick="togglePasswordVisibility('passwordAtAddress', 'togglePasswordAtAddress')" id="togglePasswordAtAddress" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700" aria-label="Toggle password visibility">
                        <i class='bx bx-show text-xl'></i>
                    </button>
                </div>
                <button type="button" id="cancel" onclick="resetFormState('backButtonAtAddress','saveButtonAtAddress','passwordSectionAtAddress','passwordAtAddress')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200">Cancel</button>
                <button type="button" id="submit" onclick="updateAddress('<?php echo $user_id; ?>')" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">Confirm</button>
            </div>
        </div>
    </div>
</section>

<section class="content-section" id="OfficeSection">
    <div class="flex items-center p-4 border-b border-gray-200">
        <button id="ArrowbackButton" onclick="goBackToProfile()" class="flex items-center text-gray-600 hover:text-blue-600 transition-colors duration-200 mr-4">
            <i class='bx bx-chevron-left text-2xl'></i>
            <span class="ml-1 text-lg">Office</span>
        </button>
    </div>
    <div class="p-6">
        <h2 class="text-xl font-semibold mb-4">Edit Office</h2>
        <p class="text-gray-600 mb-6">Changes to your office will be reflected across your PIAMIS Account.</p>
        
        <div class="flex items-center justify-center gap-2 w-full">
            <div class="mb-4 w-full"> 
                <label for="CurrentOffice" class="block text-sm font-medium text-gray-700 mb-1">Current Office</label>
                <input type="text" id="CurrentOffice" class="w-full p-2 py-[10px] border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 cursor-not-allowed" disabled value="<?php echo htmlspecialchars($Office); ?>">
            </div>
            
            <div class="mb-4 w-full">
                <label for="NewOffice" class="block text-sm font-medium text-gray-700 mb-1">New Office</label>
                <input type="text" id="NewOffice" name="NewOffice" class="w-full p-2 py-[10px] border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Enter your New Office here.">
            </div>
        </div>
        
        <div class="flex items-start gap-3 bg-blue-50 p-4 rounded-lg mb-6">
            <i class='bx bx-info-circle text-blue-600 text-xl mt-0.5'></i>
            <div>
                <h2 class="text-lg font-medium text-gray-800 mb-1">Office Visibility</h2>
                <p class="text-sm text-gray-600">Your office will be visible to Administrator within the PIAMIS system when they interact with you.</p>
            </div>
        </div>
        <div class="flex gap-4">
            <button id="backButtonAtOffice" onclick="goBackToProfile()" type="button" class="flex-1 bg-gray-200 text-gray-800 p-2 rounded-md hover:bg-gray-300 transition-colors duration-200">Back</button>
            <button id="saveButtonAtOffice" onclick="saveChanges('OfficeSection','backButtonAtOffice','saveButtonAtOffice','passwordSectionAtOffice','passwordAtOffice')" type="submit" class="flex-1 bg-blue-600 text-white p-2 rounded-md hover:bg-blue-700 transition-colors duration-200">Save Changes</button>
        </div>
        
        <div id="passwordSectionAtOffice" class="hidden mt-4 space-y-3">
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-gray-700">Confirm your password</label>
                <span class="text-xs text-gray-500">Required for security</span>
            </div>
            <div class="flex gap-2">
                <div class="relative flex-1">
                    <input type="password" id="passwordAtOffice" name="password" placeholder="Enter Password" class="w-full p-2 pr-10 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                    <button type="button" onclick="togglePasswordVisibility('passwordAtOffice', 'togglePasswordAtOffice')" id="togglePasswordAtOffice" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-700" aria-label="Toggle password visibility">
                        <i class='bx bx-show text-xl'></i>
                    </button>
                </div>
                <button type="button" id="cancel" onclick="resetFormState('backButtonAtOffice','saveButtonAtOffice','passwordSectionAtOffice','passwordAtOffice')" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors duration-200">Cancel</button>
                <button type="button" id="submit" onclick="updateOffice('<?php echo $user_id; ?>')" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">Confirm</button>
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
        // document.getElementById('NameSection').classList.remove('active');
        // document.getElementById('EmailSection').classList.remove('active');
        // document.getElementById('PasswordSection').classList.remove('active');
        // document.getElementById('MyProfileSection').classList.add('active');
        // document.getElementById('currentPassword').value = '';
        // document.getElementById('PasswordChanges').style.display = 'none';
        // document.getElementById('ConfirmUser').style.display = 'block';
        // document.getElementById('NewPassword').value = '';
        // document.getElementById('ConfirmNewPassword').value = '';
        // document.getElementById('passwordStrength').innerHTML = '';
        // document.getElementById('passwordMatchMessage').innerHTML = '';
        // document.getElementById('passwordStrengthBar').className = 'w-0 h-2 bg-blue-600';
        window.location.href = "../components/myProfile.php";
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
    
    function resetFormState(backbtn, savebtn, passwordSection, passwordinput) {
        // Show the main buttons
        document.getElementById(backbtn).style.display = 'block';
        document.getElementById(savebtn).style.display = 'block';
        
        // Hide the password section
        document.getElementById(passwordSection).classList.add('hidden');
        
        // Clear and reset the password field
        const passwordInput = document.getElementById(passwordinput);
        passwordInput.value = '';
        passwordInput.type = 'password';
        // Reset the toggle icon if it was changed
        const toggleButton = document.getElementById('togglePassword');
        if (toggleButton) {
            toggleButton.innerHTML = "<i class='bx bx-show text-xl'></i>";
        }
    }
    
    // SAVE CHANGES : Activating Password Confirmation and Update Trigger
    function saveChanges(Section, backbtn, savebtn, passwordSection, passwordinput) {
    
        let hasAnyValue = false;
        let errorMessage = '';
        switch (Section) {
            case 'NameSection':
                const firstName = document.getElementById('firstName').value.trim();
                const middleName = document.getElementById('middleName').value.trim();
                const lastName = document.getElementById('lastName').value.trim();
                hasAnyValue = firstName !== "" || middleName !== "" || lastName !== "";
                errorMessage = 'No changes detected';
                break;
            case 'EmailSection':
                const newEmail = document.getElementById('NewEmail').value.trim();
                hasAnyValue = newEmail !== "";
                errorMessage = 'Enter your Email first.';
                break;
            case 'PhoneSection':
                const newPhone = document.getElementById('NewPhone').value.trim();
                hasAnyValue = newPhone !== "";
                errorMessage = 'Enter your Phone Number first.';
                break;
            case 'AddressSection':
                const newAddress = document.getElementById('NewAddress').value.trim();
                hasAnyValue = newAddress !== "";
                errorMessage = 'Enter your Address first.';
                break;
            case 'OfficeSection':
                const newOffice = document.getElementById('NewOffice').value.trim();
                hasAnyValue = newOffice !== "";
                errorMessage = 'Enter your Office first.';
                break;
        }
        
        if(hasAnyValue) {
            // Hide the main buttons
            document.getElementById(backbtn).style.display = 'none';
            document.getElementById(savebtn).style.display = 'none';
            
            // Show the password section
            document.getElementById(passwordSection).classList.remove('hidden');
            document.getElementById(passwordinput).focus();
        }else{
            showAlert(errorMessage, 'error');
            return;
        }
    }
    
    // UPDATING NAME SCRIPTSSS
    function updateName(user_id) {
        if (confirm(`Are you sure you want to save changes?`)) {
            const formData = new FormData();
            formData.append('action', 'updateUser_Name');
            formData.append('userId', user_id);
            formData.append('password', document.getElementById('passwordAtName').value.trim());
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
                    showAlert('Your Name updated successfully!','success');
                    // Add delay before reload to show success message
                    setTimeout(() => {
                        window.location.reload();
                    }, 5000);
                } else {
                    showAlert(data.message,'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while updating your Name', 'error');
            });
        }
    }
    
    // UPDATING EMAIL SCRIPTSSS
    function updateEmail(user_id) {
        if (confirm(`Are you sure you want to save changes?`)) {
            const formData = new FormData();
            formData.append('action', 'UpdateEmail');
            formData.append('userId', user_id);
            formData.append('password', document.getElementById('passwordAtEmail').value.trim());
            formData.append('NewEmail', document.getElementById('NewEmail').value.trim());
            
            fetch('../Functions/UserFunctions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Refresh the user list to show updated status
                    showAlert('Your Email updated successfully!','success');
                    // Add delay before reload to show success message
                    setTimeout(() => {
                        window.location.reload();
                    }, 5000);
                } else {
                    showAlert(data.message,'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while updating your Email', 'error');
            });
        }
    }
    
    // UPDATING PHONE NUMBER SCRIPTSSS
    function updatePhone(user_id){
        if (confirm(`Are you sure you want to save changes?`)) {
            const formData = new FormData();
            formData.append('action', 'UpdatePhone');
            formData.append('userId', user_id);
            formData.append('password', document.getElementById('passwordAtPhone').value.trim());
            formData.append('NewPhone', document.getElementById('NewPhone').value.trim());
            
            fetch('../Functions/UserFunctions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Refresh the user list to show updated status
                    showAlert('Your Phone Number updated successfully!','success');
                    // Add delay before reload to show success message
                    setTimeout(() => {
                        window.location.reload();
                    }, 5000);
                } else {
                    showAlert(data.message,'error');
                    document.getElementById('passwordAtPhone').classList.add('border-red-500');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while updating your Phone Number', 'error');
            });
        }
    }
    
    // UPDATING ADDRESS SCRIPTSSS
    function updateAddress(user_id){
        if (confirm(`Are you sure you want to save changes?`)) {
            const formData = new FormData();
            formData.append('action', 'UpdateAddress');
            formData.append('userId', user_id);
            formData.append('password', document.getElementById('passwordAtAddress').value.trim());
            formData.append('NewAddress', document.getElementById('NewAddress').value.trim());
            
            fetch('../Functions/UserFunctions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Refresh the user list to show updated status
                    showAlert('Your Address updated successfully!','success');
                    // Add delay before reload to show success message
                    setTimeout(() => {
                        window.location.reload();
                    }, 5000);
                } else {
                    showAlert(data.message,'error');
                    document.getElementById('passwordAtAddress').classList.add('border-red-500');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while updating your Address', 'error');
            });
        }
    }
    
    // UPDATING OFFICE SCRIPTSSS
    function updateOffice(user_id){
        if (confirm(`Are you sure you want to save changes?`)) {
            const formData = new FormData();
            formData.append('action', 'UpdateOffice');
            formData.append('userId', user_id);
            formData.append('password', document.getElementById('passwordAtOffice').value.trim());
            formData.append('NewOffice', document.getElementById('NewOffice').value.trim());
            
            fetch('../Functions/UserFunctions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Refresh the user list to show updated status
                    showAlert('Your Office updated successfully!','success');
                    // Add delay before reload to show success message
                    setTimeout(() => {
                        window.location.reload();
                    }, 5000);
                } else {
                    showAlert(data.message,'error');
                    document.getElementById('passwordAtOffice').classList.add('border-red-500');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while updating your Office', 'error');
            });
        }
    }
    
    // UPDATING PASSWORD SCRIPTSSS
    function verifyPassword(user_id) {
        const currentPassword = document.getElementById('currentPassword').value;
        
        // Basic client-side validation
        if (!currentPassword) {
            showAlert('Please enter your current password', 'error');
            document.getElementById('currentPassword').classList.add('border-red-500');
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
                showAlert(data.message, 'success');
                document.getElementById('PasswordChanges').style.display = 'block';
                document.getElementById('ConfirmUser').style.display = 'none';
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            showAlert('Error: ' + (error.message || 'Failed to verify password'), 'error');
        });
    }
    
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
                showAlert(data.message, 'success');
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                } else {
                    window.location.reload();
                }
            } else {
                showAlert(data.error, 'error');
            }
        })
        .catch(error => {
            console.error('Error details:', {
                error: error.toString(),
                name: error.name,
                message: error.message,
                stack: error.stack
            });
            showAlert('Error: ' + (error.message || 'Failed to change your password'), 'error');
        });
    }
</script>    
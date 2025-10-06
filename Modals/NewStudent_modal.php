<div id="addStudentModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 5% auto; padding: 2rem; border-radius: 0.5rem; width: 90%; max-width: 600px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1f2937;">Add New Student</h2>
            <span class="close-modal" style="font-size: 1.5rem; font-weight: bold; cursor: pointer;" onclick="closeModal('addStudentModal')">&times;</span>
        </div>
        
        <!-- Student Basic Information -->
        <div class="form-section">
            <h3 style="font-size: 1.1rem; font-weight: 600; color: #1f2937; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #e5e7eb;">Student Information</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label for="schoolId" class="form-label">School ID <span class="required">*</span></label>
                    <input type="text" id="schoolId" required class="form-input" maxlength="9" pattern="^(GE|GJ|GS|GC)-[0-9A-Za-z]{1,6}$" title="School ID must start with GE-, GJ-, GS-, or GC- followed by 1-6 alphanumeric characters" oninput="validateSchoolId(this)">
                    <span id="schoolIdError" style="color: red; font-size: 0.8rem; display: none;"></span>
                </div>
                <div>
                    <label for="section" class="form-label">Section</label>
                    <input type="text" id="section" class="form-input" maxlength="1" oninput="this.value = this.value.toUpperCase()">
                </div>
                <div>
                    <label for="department" class="form-label">Department <span class="required">*</span></label>
                    <select id="department" required class="form-input">
                        <option value="">Select Department</option >
                        <option value="Elementary">Elementary</option>
                        <option value="Junior Highschool">Junior Highschool</option>
                        <option value="Senior Highschool">Senior Highschool</option>
                        <option value="College">College</option>
                    </select>
                </div>
                <div>
                    <label for="gradeLevel" class="form-label">Grade Level <span class="required">*</span></label>
                    <select id="gradeLevel" required class="form-input" disabled>
                        <option value="">Select Department first</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="form-section">
            <h3 style="font-size: 1.1rem; font-weight: 600; color: #1f2937; margin: 1.5rem 0 1rem 0; padding-bottom: 0.5rem; border-bottom: 1px solid #e5e7eb;">Personal Information</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label for="lastName" class="form-label">Last Name <span class="required">*</span></label>
                    <input type="text" id="lastName" required class="form-input">
                </div>
                <div>
                    <label for="firstName" class="form-label">First Name <span class="required">*</span></label>
                    <input type="text" id="firstName" required class="form-input">
                </div>
                <div>
                    <label for="middleName" class="form-label">Middle Name</label>
                    <input type="text" id="middleName" class="form-input">
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label for="birthDate" class="form-label">Date of Birth <span class="required">*</span></label>
                    <input type="date" id="birthDate" required class="form-input" onchange="calculateAge()">
                </div>
                <div>
                    <label for="age" class="form-label">Age</label>
                    <input type="number" id="age" class="form-input" readonly style="cursor: not-allowed; color: #6b7280;" placeholder="Auto-calculated from birthday">
                </div>
                <div>
                    <label for="gender" class="form-label">Gender <span class="required">*</span></label>
                    <select id="gender" required class="form-input">
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Prefer not to say">Prefer not to say</option>
                    </select>
                </div>
            </div>
            <div style="margin-bottom: 1rem;">
                <label for="address" class="form-label">Complete Address <span class="required">*</span></label>
                <textarea id="address" rows="2" required class="form-input"></textarea>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label for="contactNumber" class="form-label">Contact Number</label>
                    <input type="tel" id="contactNumber" class="form-input" pattern="09[0-9]{9}" title="Please enter a valid 11-digit Philippine mobile number (09XXXXXXXXX)" maxlength="11"oninput="validateContactNumber(this, 'contactNumberError')">
                    <span id="contactNumberError" style="color: red; font-size: 0.8rem; display: none;"></span>
                </div>
                <div>
                    <label for="email" class="form-label">Email Address</label>
                    <input type="text" id="email" placeholder="example@gmail.com" class="form-input" oninput="autoAppendDomain(this)">
                </div>
            </div>
        </div>

        <!-- Guardian Information -->
        <div class="form-section">
            <h3 style="font-size: 1.1rem; font-weight: 600; color: #1f2937; margin: 1.5rem 0 1rem 0; padding-bottom: 0.5rem; border-bottom: 1px solid #e5e7eb;">Guardian Information</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label for="guardianFirstName" class="form-label">First Name <span class="required">*</span></label>
                    <input type="text" id="guardianFirstName" required class="form-input">
                </div>
                <div>
                    <label for="guardianLastName" class="form-label">Last Name <span class="required">*</span></label>
                    <input type="text" id="guardianLastName" required class="form-input">
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label for="guardianContact" class="form-label">Contact Number <span class="required">*</span></label>
                    <input type="tel" id="guardianContact" required class="form-input" pattern="09[0-9]{9}" title="Please enter a valid 11-digit Philippine mobile number (09XXXXXXXXX)" maxlength="11" oninput="validateContactNumber(this, 'guardianContactError')">
                    <span id="guardianContactError" style="color: red; font-size: 0.8rem; display: none;"></span>
                </div>
                <div>
                    <label for="guardianEmail" class="form-label">Email Address</label>
                    <input type="text" id="guardianEmail" placeholder="example@gmail.com" class="form-input" oninput="autoAppendDomain(this)">
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div class="form-section">
            <h3 style="font-size: 1.1rem; font-weight: 600; color: #1f2937; margin: 1.5rem 0 1rem 0; padding-bottom: 0.5rem; border-bottom: 1px solid #e5e7eb;">Emergency Contact</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label for="emergencyName" class="form-label">Full Name <span class="required">*</span></label>
                    <input type="text" id="emergencyName" required class="form-input">
                </div>
                <div>
                    <label for="emergencyContact" class="form-label">Contact Number <span class="required">*</span></label>
                    <input type="tel" id="emergencyContact" required class="form-input" pattern="09[0-9]{9}" title="Please enter a valid 11-digit Philippine mobile number (09XXXXXXXXX)" maxlength="11"oninput="validateContactNumber(this, 'emergencyContactError')">
                    <span id="emergencyContactError" style="color: red; font-size: 0.8rem; display: none;"></span>
                </div>
                <div>
                    <label for="emergencyRelation" class="form-label">Relation <span class="required">*</span></label>
                    <input type="text" id="emergencyRelation" required class="form-input" placeholder="e.g., Mother, Father, etc." oninput="this.value = this.value.toLowerCase().replace(/(^|\s)\S/g, l => l.toUpperCase())">
                </div>
            </div>
        </div>

        <!-- Medical Information -->
        <div class="form-section">
            <h3 style="font-size: 1.1rem; font-weight: 600; color: #1f2937; margin: 1.5rem 0 1rem 0; padding-bottom: 0.5rem; border-bottom: 1px solid #e5e7eb;">Medical Information</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label for="bloodType" class="form-label">Blood Type</label>
                    <select id="bloodType" class="form-input">
                        <option value="">Select Blood Type</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                </div>
            </div>
            <div style="margin-bottom: 1rem;">
                <label for="Allergies" class="form-label">Known Allergies</label>
                <textarea id="Allergies" rows="2" class="form-input" placeholder="List any known allergies (separate by comma)"></textarea>
            </div>
            <div style="margin-bottom: 1rem;">
                <label for="Conditions" class="form-label">Chronic Conditions</label>
                <textarea id="Conditions" rows="2" class="form-input" placeholder="List any chronic medical conditions"></textarea>
            </div>
            <div style="margin-bottom: 1rem;">
                <label for="Medications" class="form-label">Current Medications</label>
                <textarea id="Medications" rows="2" class="form-input" placeholder="List current medications and dosages"></textarea>
            </div>
        </div>

        <!-- Form Actions -->
        <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
            <button type="button" class="btn btn-secondary" onclick="closeModal('addStudentModal')" style="background-color: #6b7280; color: white;">Cancel</button>
            <button type="button" class="btn btn-primary" style="background-color: #4a6cf7; color: white;" onclick="AddStudent()">Add Student</button>
        </div>
    </div>
</div>
    
<script>
    function AddStudent() {
        // Add loading state
        const submitBtn = document.querySelector('button[onclick="AddStudent()"]');
        const originalBtnText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
        
        formData = new FormData();
        formData.append('action', 'addStudent');
        // Student Basic Information
        formData.append('schoolId', document.getElementById('schoolId').value);
        formData.append('section', document.getElementById('section').value);
        formData.append('department', document.getElementById('department').value);
        formData.append('gradeLevel', document.getElementById('gradeLevel').value);
        // Personal Information
        formData.append('lastName', document.getElementById('lastName').value);
        formData.append('firstName', document.getElementById('firstName').value);
        formData.append('middleName', document.getElementById('middleName').value);
        formData.append('birthDate', document.getElementById('birthDate').value);
        formData.append('age', document.getElementById('age').value);
        formData.append('gender', document.getElementById('gender').value);
        formData.append('address', document.getElementById('address').value);
        formData.append('contactNumber', document.getElementById('contactNumber').value);
        formData.append('email', document.getElementById('email').value);
        // Guardian Information
        formData.append('guardianFirstName', document.getElementById('guardianFirstName').value);
        formData.append('guardianLastName', document.getElementById('guardianLastName').value);
        formData.append('guardianContact', document.getElementById('guardianContact').value);
        formData.append('guardianEmail', document.getElementById('guardianEmail').value);
        // Emergency Contact
        formData.append('emergencyName', document.getElementById('emergencyName').value);
        formData.append('emergencyContact', document.getElementById('emergencyContact').value);
        formData.append('emergencyRelation', document.getElementById('emergencyRelation').value);
        // Medical Information
        formData.append('bloodType', document.getElementById('bloodType').value);
        formData.append('Allergies', document.getElementById('Allergies').value);
        formData.append('Conditions', document.getElementById('Conditions').value);
        formData.append('Medications', document.getElementById('Medications').value);

        fetch('../Functions/patientFunctions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
            } else {
                alert(data.message);
            }
            window.location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the student.');
        })
            .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        });
    }
</script>
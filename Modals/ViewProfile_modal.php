<div id="viewStudentModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
        <div class="modal-content" style="background-color: #fefefe; margin: 5% auto; padding: 2rem; border-radius: 0.5rem; width: 90%; max-width: 780px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <form id="studentForm" method="POST" action="../Functions/patientFunctions.php">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h2 style="font-size: 1.5rem; font-weight: 600; color: #1f2937;">Student Profile</h2>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                       
                    <span class="close-modal" style="font-size: 1.5rem; font-weight: bold; cursor: pointer;" onclick="closeModal('viewStudentModal')">&times;</span>
                    </div>
                </div>
                
                <div id="studentProfileContent">
                    <div class="profile-header" style="display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                        <div class="flex gap-4 items-center justify-start">
                            <div class="avatar" style="width: 80px; height: 80px; background-color: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: 600;" id="studentAvatar">
                                JD
                            </div>
                            <div>
                                <h3 style="font-size: 1.5rem; font-weight: 600; margin: 0 0 0.25rem 0;" id="studentName">Loading...</h3>
                                <p style="margin: 0; color: #6b7280;" id="studentBasicInfo">ID: Loading...</p>
                            </div>
                        </div>
                        <div class="flex gap-4 items-center justify-center">
                            <button type="button" id="editStudentBtn" class="btn btn-primary w-[80px] flex items-center justify-center font-normal">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button type="submit" id="saveStudentBtn" class="btn btn-success w-[220px] bg-blue-600 text-white flex items-center justify-center font-normal hidden">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <button type="button" id="cancelEditBtn" class="btn btn-secondary w-[90px] flex items-center justify-center font-normal hidden">
                                Cancel
                            </button>
                        </div>
                    </div>

                    <div class="tabs" style="margin-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb; display: flex;">
                        <button type="button" class="tab-btn" data-tab="studentinfo">Student Info</button>
                        <button type="button" class="tab-btn" data-tab="personal">Personal Info</button>
                        <button type="button" class="tab-btn" data-tab="guardian">Guardian Info</button>
                        <button type="button" class="tab-btn" data-tab="medical">Medical Info<span id="allergyDot" class="bg-red-600 px-[5px] text-[10px] text-white font-bold rounded-full -mt-8 -ml-8 animate-heartbeat hidden">!</span></button>
                        <button type="button" class="tab-btn text-nowrap" data-tab="emergency">Emergency Contact</button>
                    </div>
                    
                    <!-- Student Info Tab -->
                    <div id="studentinfoTab" class="tab-content">
                        <div class="info-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="info-item">
                                <p class="info-label">School ID</p>
                                <p class="info-value" id="schoolID">-</p>
                                <input type="hidden" id="schoolIDInput" name="schoolID" value="">
                            </div>
                            <div class="info-item">
                                <p class="info-label">Department</p>
                                <p class="info-value view-mode" id="Department">-</p>
                                <select name="department" class="editable-field edit-mode" id="editDepartment" style="display: none;" onchange="updateGradeLevelOptions(this.value, 'editGradeLevel')">
                                    <option value="" selected>Select Department</option>
                                    <option value="Elementary">Elementary</option>
                                    <option value="Junior Highschool">Junior Highschool</option>
                                    <option value="Senior Highschool">Senior Highschool</option>
                                    <option value="College">College</option>
                                </select>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Grade Level</p>
                                <p class="info-value view-mode" id="GradeLevel">-</p>
                                <select name="GradeLevel" class="editable-field edit-mode" id="editGradeLevel" style="display: none;" disabled>
                                    <option value="">Select Department first</option>
                                </select>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Section</p>
                                <p class="info-value view-mode" id="Section">-</p>
                                <input name="section" type="text" class="editable-field edit-mode" id="editSection" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Personal Info Tab -->
                    <div id="personalTab" class="tab-content">
                        <div class="info-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="info-item">
                                <p class="info-label">First Name</p>
                                <p class="info-value view-mode" id="studentFirstName">-</p>
                                <input name="firstname" type="text" class="editable-field edit-mode" id="editFirstName" style="display: none;">
                            </div>
                            <div class="info-item">
                                <p class="info-label">Middle Name</p>
                                <p class="info-value view-mode" id="studentMiddleName">-</p>
                                <input name="middlename" type="text" class="editable-field edit-mode" id="editMiddleName" style="display: none;">
                            </div>
                            <div class="info-item">
                                <p class="info-label">Last Name</p>
                                <p class="info-value view-mode" id="studentLastName">-</p>
                                <input name="lastname" type="text" class="editable-field edit-mode" id="editLastName" style="display: none;">
                            </div>
                            <div class="info-item">
                                <p class="info-label">Gender</p>
                                <p class="info-value view-mode" id="Gender">-</p>
                                <select name="gender" class="editable-field edit-mode" id="editGender" style="display: none;">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Birthdate</p>
                                <p class="info-value view-mode" id="Birthdate">-</p>
                                <input name="birthdate" type="date" class="editable-field edit-mode" id="editBirthdate" style="display: none;">
                            </div>
                            <div class="info-item">
                                <p class="info-label">Age</p>
                                <p class="info-value view-mode" id="Age">-</p>
                                <input name="age" type="number" class="editable-field edit-mode" id="editAge" style="display: none;" min="1" max="30">
                            </div>
                            <div class="info-item">
                                <p class="info-label">Contact Number</p>
                                <p class="info-value view-mode" id="ContactNumber">-</p>
                                <input name="contactNumber" type="tel" class="editable-field edit-mode" id="editContactNumber" style="display: none;">
                            </div>
                            <div class="info-item">
                                <p class="info-label">Student Email</p>
                                <p class="info-value view-mode" id="StudentEmailAddress">-</p>
                                <input name="email" type="tel" class="editable-field edit-mode" id="editStudentEmailAddress" style="display: none;">
                            </div>
                            <div class="info-item">
                                <p class="info-label">Address</p>
                                <p class="info-value view-mode" id="Address">-</p>
                                <textarea name="address" class="editable-field edit-mode" id="editAddress" style="display: none; min-height: 60px;"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Guardian Info Tab -->
                    <div id="guardianTab" class="tab-content">
                        <div class="info-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="info-item">
                                <p class="info-label">Guardian First Name</p>
                                <p class="info-value view-mode" id="GuardianFirstName">-</p>
                                <input name="guardianFirstName" type="text" class="editable-field edit-mode" id="editGuardianFirstName" style="display: none;">
                            </div>
                            <div class="info-item">
                                <p class="info-label">Guardian Last Name</p>
                                <p class="info-value view-mode" id="GuardianLastName">-</p>
                                <input name="guardianLastName" type="text" class="editable-field edit-mode" id="editGuardianLastName" style="display: none;">
                            </div>
                            <div class="info-item">
                                <p class="info-label">Guardian Contact Number</p>
                                <p class="info-value view-mode" id="GuardianContactNumber">-</p>
                                <input name="guardianContactNumber" type="tel" class="editable-field edit-mode" id="editGuardianContactNumber" style="display: none;">
                            </div>
                            <div class="info-item">
                                <p class="info-label">Guardian Email</p>
                                <p class="info-value view-mode" id="GuardianEmailAddress">-</p>
                                <input name="guardianEmailAddress" type="email" class="editable-field edit-mode" id="editGuardianEmailAddress" style="display: none;">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Emergency Contact Tab -->
                    <div id="emergencyTab" class="tab-content">
                        <div class="info-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="info-item">
                                <p class="info-label">Guardian Name</p>
                                <p class="info-value view-mode" id="guardianName">-</p>
                                <input name="emergencyContactName" type="text" class="editable-field edit-mode" id="editGuardianName" style="display: none;">
                            </div>
                            <div class="info-item">
                                <p class="info-label">Relationship</p>
                                <p class="info-value view-mode" id="guardianRelationship">-</p>
                                <input name="emergencyContactRelation" type="text" class="editable-field edit-mode" id="editGuardianRelationship" style="display: none;">
                            </div>
                            <div class="info-item">
                                <p class="info-label">Contact Number</p>
                                <p class="info-value view-mode" id="emergencyContactNumber">-</p>
                                <input name="emergencyContactNumber" type="tel" class="editable-field edit-mode" id="editEmergencyContactNumber" style="display: none;">
                            </div>
                        </div>
                    </div>

                    <!-- Medical Info Tab -->
                    <div id="medicalTab" class="tab-content">
                        <div class="info-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="info-item">
                                <p class="info-label">Blood Type</p>
                                <p class="info-value view-mode" id="BloodType">-</p>
                                <select name="bloodType" class="editable-field edit-mode" id="editBloodType" style="display: none;">
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
                            <div class="info-item">
                                <p class="info-label">Allergies</p>
                                <p class="info-value view-mode" id="KnownAllergies">None</p>
                                <textarea name="knownAllergies" class="editable-field edit-mode" id="editKnownAllergies" style="display: none; min-height: 60px;"></textarea>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Chronic Conditions</p>
                                <p class="info-value view-mode" id="ChronicConditions">None</p>
                                <textarea name="chronicConditions" class="editable-field edit-mode" id="editChronicConditions" style="display: none; min-height: 60px;"></textarea>
                            </div>
                            <div class="info-item">
                                <p class="info-label">Current Medication(s)</p>
                                <p class="info-value view-mode" id="CurrentMedication">None</p>
                                <textarea name="currentMedication" class="editable-field edit-mode" id="editCurrentMedication" style="display: none; min-height: 60px;"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" name="action" value="updateStudent">
            </form>  
        </div>
    </div>
<div id="checkInModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); overflow-y: auto;">
    <div class="modal-content" style="background-color: #fff; margin: 2% auto; border-radius: 12px; width: 90%; max-width: 700px; max-height: 90vh; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); display: flex; flex-direction: column; overflow: hidden;">
        <!-- Modal Header -->
        <div style="padding: 1.5rem; border-bottom: 1px solid #e5e7eb; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="font-size: 1.5rem; font-weight: 600; color: white; margin: 0;">
                    <i class="fas fa-user-plus" style="margin-right: 10px;"></i>Student Check-in
                </h2>
                <button onclick="closeModal('checkInModal')" style="background: rgba(255,255,255,0.2); border: none; border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; color: white; font-size: 1.25rem; transition: all 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                    &times;
                </button>
            </div>
        </div>
        
        <!-- Hidden Inputs -->
        <input type="hidden" id="CheckInStudentIdHidden" value="">
        <input type="hidden" id="CheckInStaffIdHidden" value="">
        
        <!-- Main Content -->
        <div id="studentProfileContent" style="padding: 1.5rem; overflow-y: auto; max-height: calc(90vh - 120px);">
            <!-- Student Profile Header -->
            <div class="profile-header" style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem; padding: 1.5rem; background-color: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0;">
                <div id="CheckInAvatar" style="width: 80px; height: 80px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: 600; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <span id="studentAvatar">JD</span>
                </div>
                <div>
                    <h3 style="font-size: 1.5rem; font-weight: 700; margin: 0 0 0.25rem 0; color: #1e293b;" id="CheckInStudentName">Loading...</h3>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="background: #e0f2fe; color: #0369a1; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.875rem; font-weight: 500;" id="CheckInStudentID">ID: Loading...</span>
                    </div>
                </div>
            </div>

            <!-- Form Fields -->
            <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.25rem; margin-bottom: 1.5rem;">
                <!-- Date & Time -->
                <div class="form-group">
                    <label for="checkInDateTime" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #475569; font-size: 0.9375rem;">
                        <i class="far fa-calendar-alt" style="margin-right: 6px; color: #64748b;"></i>Date & Time
                    </label>
                    <div style="position: relative;">
                        <input type="datetime-local" id="checkInDateTime" readonly
                            style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; background-color: #f8fafc; color: #334155; font-size: 0.9375rem; transition: all 0.2s; cursor: not-allowed;"
                            value="<?php 
                                date_default_timezone_set('Asia/Manila');
                                echo date('Y-m-d\TH:i');
                            ?>">
                    </div>
                </div>
                
                <!-- Reason for Visit -->
                <div class="form-group">
                    <label for="reasonForVisit" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #475569; font-size: 0.9375rem;">
                        <i class="fas fa-stethoscope" style="margin-right: 6px; color: #64748b;"></i>Reason for Visit
                    </label>
                    <div style="position: relative;">
                        <select id="reasonForVisit" required
                            style="width: 100%; padding: 0.75rem; padding-right: 2.5rem; border: 1px solid #e2e8f0; border-radius: 8px; background-color: #fff; color: #334155; font-size: 0.9375rem; appearance: none; cursor: pointer; transition: all 0.2s;"
                            onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';"
                            onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';">
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
                        <div style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #94a3b8;">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Section -->
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="notes" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #475569; font-size: 0.9375rem;">
                    <i class="far fa-edit" style="margin-right: 6px; color: #64748b;"></i>Additional Notes
                </label>
                <div style="position: relative;">
                    <textarea id="notes" rows="3" required
                        style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; resize: vertical; min-height: 100px; font-family: inherit; font-size: 0.9375rem; color: #334155; transition: all 0.2s;"
                        placeholder="Enter any additional notes or details here..."
                        onfocus="this.style.borderColor='#3b82f6'; this.style.boxShadow='0 0 0 3px rgba(59, 130, 246, 0.1)';"
                        onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none';"></textarea>
                </div>
            </div>

            </div> <!-- Close the scrollable content div -->
            
            <!-- Action Buttons (sticky to bottom) -->
            <div class="form-actions" style="margin-top: auto; padding: 1.5rem; background: white; border-top: 1px solid #e5e7eb; position: sticky; bottom: 0; display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="closeModal('checkInModal')" 
                    style="padding: 0.75rem 1.5rem; background-color: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 8px; cursor: pointer; font-weight: 500; color: #475569; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem;"
                    onmouseover="this.style.backgroundColor='#e2e8f0'"
                    onmouseout="this.style.backgroundColor='#f1f5f9'">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" onclick="studentCheckIn()" 
                    style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 500; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);"
                    onmouseover="this.style.opacity='0.9'; this.style.boxShadow='0 4px 6px -1px rgba(37, 99, 235, 0.3)'"
                    onmouseout="this.style.opacity='1'; this.style.boxShadow='0 4px 6px -1px rgba(37, 99, 235, 0.2)'">
                    <i class="fas fa-check"></i> Check In
                </button>
            </div>
        </div>
    </div>
</div>
    
<script>
    
    function studentCheckIn() {
        formData = new FormData()
        formData.append('action', 'Checkin')
        formData.append('CheckInStudentId', document.getElementById('CheckInStudentIdHidden').value)
        formData.append('CheckInStaffId', document.getElementById('CheckInStaffIdHidden').value)
        formData.append('CheckInDateTime', document.getElementById('checkInDateTime').value)
        formData.append('reasonForVisit', document.getElementById('reasonForVisit').value)
        formData.append('notes', document.getElementById('notes').value)

        fetch('../Functions/patientFunctions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Check-in successful!')
                window.location.reload();
            } else {
                alert('Check-in failed: ' + data.message)
            }
        })
        .catch(error => {
            console.error('Error:', error)
            alert('An error occurred while checking in the student')
        })
    }
    
</script>    
<div id="checkInModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
        <div class="modal-content" style="background-color: #fefefe; margin: 5% auto; padding: 2rem; border-radius: 0.5rem; width: 90%; max-width: 700px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <form id="studentForm" method="POST" action="../Functions/patientFunctions.php">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h2 style="font-size: 1.5rem; font-weight: 600; color: #1f2937;">Student Check-in</h2>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <span class="close-modal" style="font-size: 1.5rem; font-weight: bold; cursor: pointer;" onclick="closeModal('checkInModal')">&times;</span>
                    </div>
                </div>
                
                <div id="studentProfileContent">
                    <div class="profile-header" style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                        <div id="CheckInAvatar" style="width: 80px; height: 80px; background-color: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: 600;" id="studentAvatar">
                            JD
                        </div>
                        <div>
                            <h3 style="font-size: 1.5rem; font-weight: 600; margin: 0 0 0.25rem 0;" id="CheckInStudentName">Loading...</h3>
                            <p style="margin: 0; color: #6b7280;" id="CheckInStudentID">ID: Loading...</p>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="checkInDateTime" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151;">Date & Time</label>
                        <input type="datetime-local" id="checkInDateTime" name="checkInDateTime" readonly
                            style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; cursor: not-allowed;"
                            value="<?php 
                                date_default_timezone_set('Asia/Manila');
                                echo date('Y-m-d\TH:i');
                            ?>">
                    </div>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="reasonForVisit" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151;">Reason for Visit</label>
                        <textarea id="reasonForVisit" name="reasonForVisit" rows="3" required
                            style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; resize: vertical;"
                            placeholder="Please describe the reason for the visit"></textarea>
                    </div>
                    
                    <input type="hidden" name="action" value="Checkin">
                    <input type="hidden" id="CheckInStudentIdHidden" name="CheckInStudentId" value="">
                    <input type="hidden" id="CheckInStaffIdHidden" name="CheckInStaffId" value="">

                    <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                        <button type="button" onclick="closeModal('checkInModal')" 
                            style="padding: 0.5rem 1rem; background-color: #f3f4f6; border: 1px solid #d1d5db; border-radius: 0.375rem; cursor: pointer; font-weight: 500; color: #374151;">
                            Cancel
                        </button>
                        <button type="submit" 
                            style="padding: 0.5rem 1.5rem; background-color: #3b82f6; color: white; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 500;">
                            Check In
                        </button>
                    </div>
                </div>
            </form>  
        </div>
    </div>
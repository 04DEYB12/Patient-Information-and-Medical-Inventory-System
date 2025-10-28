<style>
    .record-table-container table tbody tr {
        transition: background-color 0.2s;
    }
    .record-table-container table tbody tr:hover {
        background-color: #f9fafb;
    }
    .record-table-container table tbody tr:not(:last-child) {
        border-bottom: 1px solid #e5e7eb;
    }
    .status-active {
        color: #10B981;
        font-weight: 500;
    }
    .status-completed {
        color: #3B82F6;
        font-weight: 500;
    }
    .status-pending {
        color: #F59E0B;
        font-weight: 500;
    }
</style>

<div id="RecordModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 5% auto; padding: 2rem; border-radius: 0.5rem; width: 90%; max-width: 700px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);"> 
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; white-space: nowrap;" id="pdfHeader">
            <div style="display: flex; flex-direction: column; margin-bottom: 20px; justify-content: space-between; width: 100%; align-items: center;">
                <span class="close-modal w-full flex items-center justify-end" style="font-size: 1.5rem; font-weight: bold; cursor: pointer; color: #6b7280;" onclick="closeModal('RecordModal')">&times;</span>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <img src="../Images/GranbyLogo.png" alt="School Logo" style="width: 50px; height: 50px; object-fit: contain; flex-shrink: 0;">
                    <div style=" width: 100%; display: flex; flex-direction: column; justify-items: center; align-items: center">
                        <h6 style="padding-bottom: 5px; font-size: 1.1rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Granby Colleges of Science and Technology</h6>
                        <p style="margin: 0.25rem 0 0; padding-bottom: 5px; color: #6b7280; font-size: 0.875rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">Patient Information & Medical Inventory System</p>
                    </div>
                    <img src="../Images/PIAMISLOGO.jpg" alt="System Logo" style="width: 65px; height: 65px; object-fit: contain; flex-shrink: 0;">
                </div>
                
            </div>
        </div>

        <!-- User guide rectangle -->
        <div class="user-click-guide w-full mb-2 mt-4 p-4 rounded-lg bg-gradient-to-r from-sky-50 to-blue-50 shadow-sm border border-sky-100 flex items-start gap-3">
            <!-- Emoji icon -->
            <i class="bx bx-info-circle"></i>

            <!-- Text -->
            <div class="flex-1">
                <h4 class="text-sm font-semibold text-sky-700">Tip</h4>
                <p class="mt-1 text-sm text-slate-700">
                Table rows are <span class="font-medium text-sky-600">clickable</span> — click a row to view details and perform actions.
                </p>
            </div>
        </div>

        <div id="studentProfileContent">
            <div class="profile-header" style="margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                <h5 style="text-align: center; font-size: 1.5rem; margin: 0 0 1.5rem 0; color: #1f2937; letter-spacing: 1px;">
                    STUDENT RECORD HISTORY
                </h5>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="font-size: 1rem; color: #4b5563;">
                        <strong>Name:</strong> <span id="RecordStudentName">Loading...</span>
                    </div>
                    <div style="font-size: 1rem; color: #4b5563;">
                        <strong>School ID:</strong> <span id="RecordStudentID">Loading...</span>
                    </div>
                </div>
            </div>

            <div class="record-table-container" style="overflow-x: auto; margin-bottom: 1.5rem;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                    <thead>
                        <tr style="background-color: #eeeeeeff; text-align: left; border-radius: 5px 5px 0px 0px;">
                            <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; font-weight: 600; border-radius: 5px 0px 0px 0px; color: #35373aff;">Date & Time</th>
                            <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #35373aff;">Reason</th>
                            <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #35373aff;">Status</th>
                            <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #35373aff;">Outcome</th>
                            <th style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; font-weight: 600; border-radius: 0px 5px 0px 0px; color: #35373aff;">Staff</th>
                        </tr>
                    </thead>
                    <tbody id="recordTableBody" style="background-color: white;">
                        <!-- Records will be loaded here -->
                        <tr>
                            <td colspan="5" style="padding: 1.5rem; text-align: center; color: #6b7280;">Loading records...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                <button type="button" onclick="closeModal('RecordModal')" 
                    style="padding: 0.5rem 1rem; background-color: #f3f4f6; border: 1px solid #d1d5db; border-radius: 0.375rem; cursor: pointer; font-weight: 500; color: #374151;">
                    Cancel
                </button>
                <button type="button" id="downloadPdfBtn"
                    onclick="downloadAsPDF()"
                    style="padding: 0.5rem 1.5rem; background-color: #3b82f6; color: white; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 500; transition: background-color 0.2s;"
                    onmouseover="this.style.backgroundColor='#2563eb'"
                    onmouseout="this.style.backgroundColor='#3b82f6'">
                    Download PDF
                </button>
            </div>
        </div>
    </div>
</div>
    

<div id="EditRecordModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 5% auto; padding: 2rem; border-radius: 0.5rem; width: 90%; max-width: 700px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.5rem; font-weight: 600; color: #1f2937;" id="EditRecordModalTitle">Edit Record</h2>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <span class="close-modal" style="font-size: 1.5rem; font-weight: bold; cursor: pointer;" onclick="closeModal('EditRecordModal')">&times;</span>
            </div>
        </div>
        
        <div id="studentProfileContent">
            <div class="profile-header" style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb;">
                <div id="EditAvatar" style="width: 80px; height: 80px; background-color: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: 600;" id="studentAvatar">
                    JD
                </div>
                <div>
                    <h3 style="font-size: 1.5rem; font-weight: 600; margin: 0 0 0.25rem 0;" id="EditStudentName">Loading...</h3>
                    <p style="margin: 0; color: #6b7280;" id="EditStudentID">ID: Loading...</p>
                </div>
            </div>
            
            <input type="hidden" id="recordID" name="recordID">

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="EditCheckInDateTime" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151;">Check-in Date & Time</label>
                <p style="margin: 0; color: #6b7280;" id="EditCheckInDateTime">Loading...</p>
            </div>
            
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="EditReason" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151;">Reason</label>
                <p style="margin: 0; color: #6b7280;" id="EditReason">Loading...</p>
            </div>
            
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="EditNotes" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151;">Notes</label>
                <p style="margin: 0; color: #6b7280;" id="EditNotes">Loading...</p>
            </div>
            
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="EditStatus" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151;">Status</label>
                <p style="margin: 0; color: #6b7280;" id="EditStatus">Loading...</p>
            </div>
            
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="EditDateTime" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151;">Update Date & Time</label>
                <input type="datetime-local" id="EditDateTime" name="EditDateTime" readonly
                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; cursor: not-allowed;"
                    value="<?php 
                        date_default_timezone_set('Asia/Manila');
                        echo date('Y-m-d\TH:i');
                    ?>">
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label for="EditOutcome" style="display: block; margin-bottom: 0.5rem; font-weight: 500; color: #374151;">Outcome</label>
                <textarea id="EditOutcome" name="EditOutcome" rows="3" required
                    style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;"
                    placeholder="Enter treatment outcome..."></textarea>
                    <p id="EditOutcome" style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;"></p>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem; padding-top: 1rem; border-top: 1px solid #e5e7eb;">
                <button type="button" onclick="closeModal('EditRecordModal')"
                    style="padding: 0.5rem 1rem; background-color: #e5e7eb; color: #374151; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 500;"
                    onmouseover="this.style.backgroundColor='#d1d5db'"
                    onmouseout="this.style.backgroundColor='#e5e7eb'">
                    Cancel
                </button>
                <button type="button" id="EditSaveBtn"
                    style="padding: 0.5rem 1rem; background-color: #3b82f6; color: white; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 500;"
                    onmouseover="this.style.backgroundColor='#2563eb'"
                    onmouseout="this.style.backgroundColor='#3b82f6'">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add jsPDF library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    // download modal as PDF
    function downloadAsPDF() {
        // Create a clone of the modal content for PDF generation
        const modal = document.getElementById('RecordModal').cloneNode(true);
        // Remove the close button from the clone
        const closeButton = modal.querySelector('.close-modal');
        if (closeButton) closeButton.remove();
        
        // Create a temporary container for PDF generation
        const tempContainer = document.createElement('div');
        tempContainer.style.position = 'absolute';
        tempContainer.style.left = '-9999px';
        tempContainer.style.width = '700px'; // Match the modal width
        tempContainer.style.padding = '20px';
        tempContainer.style.background = 'white';
        
        // Clone the modal content and prepare it for PDF
        const modalContent = modal.querySelector('.modal-content').cloneNode(true);
        
        // Remove close button and form actions
        const elementsToRemove = modalContent.querySelectorAll('.form-actions, .close-modal, .user-click-guide');
        elementsToRemove.forEach(el => el.remove());
        
        // Remove close button from header if it exists
        const header = modalContent.querySelector('.modal-header');
        if (header) {
            const closeBtn = header.querySelector('.close-modal');
            if (closeBtn) closeBtn.remove();
        }
        
        // Add the prepared content to the temp container
        tempContainer.appendChild(modalContent);
        document.body.appendChild(tempContainer);
        
        // Show loading state
        const originalButtonText = document.querySelector('#downloadPdfBtn').innerHTML;
        document.querySelector('#downloadPdfBtn').innerHTML = 'Generating PDF...';
        document.querySelector('#downloadPdfBtn').disabled = true;
        
        html2canvas(tempContainer.firstChild, {
            scale: 2,
            logging: false,
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#ffffff'
        }).then(canvas => {
            document.body.removeChild(tempContainer);
            
            // Create a new PDF with proper dimensions
            const pdf = new jspdf.jsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4'
            });
            
            const imgData = canvas.toDataURL('image/png');
            const pdfWidth = 190; // A4 width (210mm) with 10mm margins on each side
            const pageHeight = 277; // A4 height (297mm) with 10mm top and bottom margins
            const imgHeight = (canvas.height * pdfWidth) / canvas.width;
            let heightLeft = imgHeight;
            let position = 10; // Start 10mm from top
            
            // Add first page
            pdf.addImage(imgData, 'PNG', 10, position, pdfWidth, imgHeight);
            heightLeft -= (pageHeight - 20); // Subtract page height (minus margins)
            
            // Add additional pages if content is too long
            while (heightLeft >= 0) {
                pdf.addPage();
                position = heightLeft - imgHeight;
                if (position < 10) position = 10; // Ensure minimum top margin
                pdf.addImage(imgData, 'PNG', 10, -position, pdfWidth, imgHeight);
                heightLeft -= (pageHeight - 10); // Subtract page height (minus top margin only for subsequent pages)
            }
            
            // Get student name for filename
            const studentName = document.getElementById('RecordStudentName').textContent.trim() || 'Student';
            const formattedName = studentName.replace(/\s+/g, ' ').replace(/[^\w\s-]/g, '').replace(/\s+/g, ' ').trim();
            const filename = `${formattedName} - Records.pdf`;
            
            // Download the PDF with student's name
            pdf.save(filename);
            
            // Restore button state
            document.querySelector('#downloadPdfBtn').innerHTML = originalButtonText;
            document.querySelector('#downloadPdfBtn').disabled = false;
        }).catch(error => {
            console.error('Error generating PDF:', error);
            alert('Error generating PDF. Please try again.');
            document.querySelector('#downloadPdfBtn').innerHTML = originalButtonText;
            document.querySelector('#downloadPdfBtn').disabled = false;
        });
    }
    
    // edit check-in record
    const EditSaveBtn = document.getElementById('EditSaveBtn');
    EditSaveBtn.addEventListener('click', function() {
        if (confirm('Are you sure you want to update this check-in record?')) {
            const recordId = document.getElementById('recordID').value;
            const studentId = document.getElementById('EditStudentID').textContent;
            const updatedAt = document.getElementById('EditDateTime').value;
            const outcome = document.getElementById('EditOutcome').value;
            
            const formData = new FormData();
            formData.append('action', 'updateCheckInRecord');
            formData.append('recordId', recordId);
            formData.append('studentId', studentId);
            formData.append('updatedAt', updatedAt);
            formData.append('outcome', outcome);
            
            fetch('../Functions/patientFunctions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Refresh the user list to show updated status
                    alert('Check-in record updated successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.error || 'Failed to update check-in record'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating check-in record');
            });
        }
    });
</script>
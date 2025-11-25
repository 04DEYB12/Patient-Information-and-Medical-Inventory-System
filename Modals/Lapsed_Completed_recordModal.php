<div id="ViewRecordModal" class="hidden fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50">
    <div class="bg-white rounded-lg p-8 w-[90%] max-w-4xl mx-auto my-12 shadow-lg">
        <div class="modal-header flex flex-col items-center mb-8" id="pdfHeader">
            <span class="self-end text-2xl font-bold text-gray-500 cursor-pointer" onclick="closeModal('ViewRecordModal')">
                &times;
            </span>
            <div class="flex items-center gap-4 justify-center">
                <img src="../Images/GranbyLogo.png" alt="School Logo" class="w-12 h-12 object-contain flex-shrink-0">
                <div class="flex flex-col items-center w-full">
                    <h6 class="text-lg font-medium text-gray-900 whitespace-nowrap overflow-hidden text-ellipsis">
                        Granby Colleges of Science and Technology
                    </h6>
                    <p class="text-sm text-gray-500 whitespace-nowrap overflow-hidden text-ellipsis">
                        Patient Information & Medical Inventory System
                    </p>
                </div>
                <img src="../Images/PIAMISLOGO.jpg" alt="System Logo" class="w-16 h-16 object-contain flex-shrink-0">
            </div>
        </div>

        <div id="studentProfileContent">
            <div class="mb-8 pb-6 border-b border-gray-200">
                <h5 class="text-2xl text-center text-gray-900 tracking-wider mb-6">
                    STUDENT MEDICAL RECORD
                </h5>
                
                <p class="py-2 border border-gray-700 mb-4 font-semibold text-gray-900 text-center w-full">
                    STUDENT INFORMATION
                </p>
                
                <div class="bg-gray-50 rounded p-3 mb-4">
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div class="mb-3">
                            <div class="text-sm text-gray-500">Full Name</div>
                            <div class="text-md text-gray-900 py-1 border-b border-gray-200" id="ViewRecord_StudentName">Loading...</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-sm text-gray-500">School ID</div>
                            <div class="text-md text-gray-900 py-1 border-b border-gray-200" id="ViewRecord_StudentID">Loading...</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="text-sm text-gray-500">Complete Address</div>
                        <div class="text-md text-gray-900 py-1 border-b border-gray-200" id="ViewRecord_StudentAddress">Loading...</div>
                    </div>
                    
                    <div class="grid grid-cols-5 gap-2 mb-3">
                        <div>
                            <div class="text-sm text-gray-500">Gender</div>
                            <div class="text-md text-gray-900 py-1 border-b border-gray-200" id="ViewRecord_StudentGender">Loading...</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Age</div>
                            <div class="text-md text-gray-900 py-1 border-b border-gray-200" id="ViewRecord_StudentAge">Loading...</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Department</div>
                            <div class="text-md text-gray-900 py-1 border-b border-gray-200" id="ViewRecord_StudentDepartment">Loading...</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Level</div>
                            <div class="text-md text-gray-900 py-1 border-b border-gray-200" id="ViewRecord_StudentGradeLevel">Loading...</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Section</div>
                            <div class="text-md text-gray-900 py-1 border-b border-gray-200" id="ViewRecord_StudentSection">Loading...</div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <div class="text-sm text-gray-500">Contact Number</div>
                            <div class="text-md text-gray-900 py-1 border-b border-gray-200" id="ViewRecord_StudentContactNumber">Loading...</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Email Address</div>
                            <div class="text-md text-gray-900 py-1 border-b border-gray-200" id="ViewRecord_StudentEmail">Loading...</div>
                        </div>
                    </div>
                </div>
                
                <p class="py-2 border border-gray-700 mb-4 font-semibold text-gray-900 text-center w-full">
                    MEDICAL ASSESSMENT
                </p>
                
                <div class="bg-gray-50 rounded p-3">
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div class="mb-3">
                            <div class="text-sm text-gray-500">Check-up Date & Time</div>
                            <div class="text-md text-gray-900 py-1 border-b border-gray-200" id="ViewRecord_DateTime">Loading...</div>
                        </div>
                        <div class="mb-3">
                            <div class="text-sm text-gray-500">Status</div>
                            <div class="text-md text-gray-900 py-1 border-b border-gray-200" id="ViewRecord_Status">Loading...</div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="text-sm text-gray-500">Reason for Visit</div>
                        <div class="text-md text-gray-900 py-1 border-b border-gray-200" id="ViewRecord_Reason">Loading...</div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <div class="text-sm text-gray-500">Notes</div>
                            <div class="text-md text-gray-900 py-1 border-b border-gray-200" id="ViewRecord_Notes">Loading...</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Follow-up Date</div>
                            <div class="text-md text-gray-900 py-1 border-b border-gray-200" id="ViewRecord_FollowUpDate">Loading...</div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <div class="text-sm text-gray-500">Treatment Outcome</div>
                            <div class="text-md text-gray-900 py-1 border-b border-gray-200" id="ViewRecord_Outcome">Loading...</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500">Assist By</div>
                            <div class="text-md text-gray-900 py-1 border-b border-gray-200" id="ViewRecord_AssistBy">Loading...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="form-actions" style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
            <button type="button" onclick="closeModal('ViewRecordModal')" 
                style="padding: 0.5rem 1rem; background-color: #f3f4f6; border: 1px solid #d1d5db; border-radius: 0.375rem; cursor: pointer; font-weight: 500; color: #374151;">
                Cancel
            </button>
            <button type="button" id="downloadPdfBtn_ThisRecord"
                onclick="downloadAsPDF_thisRecord()"
                style="padding: 0.5rem 1.5rem; background-color: #3b82f6; color: white; border: none; border-radius: 0.375rem; cursor: pointer; font-weight: 500; transition: background-color 0.2s;"
                onmouseover="this.style.backgroundColor='#2563eb'"
                onmouseout="this.style.backgroundColor='#3b82f6'">
                Download PDF
            </button>
        </div>
    </div>
</div>

<!-- Add jsPDF library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>

    // download modal as PDF
    function downloadAsPDF_thisRecord() {
        // Get the original modal content
        const modal = document.getElementById('ViewRecordModal');
        
        // Create a deep clone of the modal for PDF generation
        const modalClone = modal.cloneNode(true);
        
        // Create a temporary container
        const tempContainer = document.createElement('div');
        tempContainer.style.position = 'fixed';
        tempContainer.style.left = '-9999px';
        tempContainer.style.top = '0';
        tempContainer.style.width = '700px';
        tempContainer.style.padding = '20px';
        tempContainer.style.background = 'white';
        tempContainer.style.zIndex = '9999';
        
        // Get the modal content
        const modalContent = modalClone.querySelector('.bg-white.rounded-lg');
        if (!modalContent) {
            console.error('Modal content not found');
            return;
        }
        
        // Remove interactive elements
        const elementsToRemove = modalContent.querySelectorAll('button, .close-modal, .form-actions');
        elementsToRemove.forEach(el => el.remove());
        
        // Add to document
        tempContainer.appendChild(modalContent);
        document.body.appendChild(tempContainer);
        
        // Show loading state
        const downloadBtn = document.querySelector('#downloadPdfBtn_ThisRecord');
        const originalButtonText = downloadBtn.innerHTML;
        downloadBtn.innerHTML = 'Generating PDF...';
        downloadBtn.disabled = true;
        
        // Use a small delay to ensure the clone is rendered
        setTimeout(() => {
            html2canvas(tempContainer.firstChild, {
                scale: 2,
                logging: true,
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#ffffff'
            }).then(canvas => {
                // Clean up
                document.body.removeChild(tempContainer);
                
                // Initialize PDF
                const pdf = new jspdf.jsPDF({
                    orientation: 'portrait',
                    unit: 'mm',
                    format: 'a4'
                });
                
                // Calculate dimensions
                const pdfWidth = 190; // A4 width (210mm) with 10mm margins
                const pageHeight = 277; // A4 height (297mm) with 10mm margins
                const imgWidth = pdfWidth;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;
                
                let heightLeft = imgHeight;
                let position = 10; // Start 10mm from top
                let pageNumber = 1;
                
                // Add first page
                pdf.addImage(canvas, 'PNG', 10, position, imgWidth, imgHeight, '', 'FAST');
                heightLeft -= (pageHeight - 20);
                
                // Add additional pages if needed
                while (heightLeft >= 0) {
                    position = heightLeft - imgHeight;
                    if (position < 10) position = 10;
                    pdf.addPage();
                    pdf.addImage(canvas, 'PNG', 10, -position, imgWidth, imgHeight, '', 'FAST');
                    heightLeft -= (pageHeight - 10);
                    pageNumber++;
                }
                
                // Get student name for filename
                const studentName = document.getElementById('ViewRecord_StudentName')?.textContent.trim() || 'Student_Record';
                const formattedName = studentName.replace(/\s+/g, '_').replace(/[^\w\s-]/g, '');
                const filename = `${formattedName}_Medical_Record.pdf`;
                
                // Save the PDF
                pdf.save(filename);
                
            }).catch(error => {
                console.error('Error generating PDF:', error);
                // Fallback to basic alert if showAlert is not available
                if (typeof showAlert === 'function') {
                    showAlert('Error generating PDF. Please try again.', 'error');
                } else {
                    alert('Error generating PDF. Please try again.');
                }
            }).finally(() => {
                // Always restore button state
                downloadBtn.innerHTML = originalButtonText;
                downloadBtn.disabled = false;
            });
        }, 500);
    }
    
</script>
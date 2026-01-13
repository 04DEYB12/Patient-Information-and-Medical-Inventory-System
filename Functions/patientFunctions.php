<?php
include '../Landing Repository/Connection.php';

error_log('POST data: ' . print_r($_POST, true));

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Send JSON response (for GET requests)
 */
function sendJsonResponse($data, $statusCode = 200) {
    // Clean any output that might have been generated before
    if (ob_get_level() > 0) {
        ob_clean();
    }
    
    // Set JSON header
    header('Content-Type: application/json');
    http_response_code($statusCode);
    
    // Output JSON and exit
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit();
}

// Audit Function
function audit($user_id, $action_type, $table_name, $record_id, $action_details) {
    global $con;
    
    $query = "INSERT INTO audit_trail (user_id, action_type, table_name, record_id, action_details) 
                VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $con->prepare($query);
    if ($stmt === false) {
        error_log('Failed to prepare audit trail statement: ' . $con->error);
    } else {
        $stmt->bind_param('sssss', $user_id, $action_type, $table_name, $record_id, $action_details);
        if (!$stmt->execute()) {
            error_log('Failed to execute audit trail statement: ' . $stmt->error);
        }
        $stmt->close();
    }
}

// Handle GET requests for student data
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'getVisitReasons': // --------------------------------------- GET VISIT REASONS ---------------------------------------
            header('Content-Type: application/json');
            
            // Query to get the most common reasons for visits
            $query = "SELECT 
                        Reason as reason, 
                        COUNT(*) as count 
                        FROM studentcheckins 
                        WHERE Reason IS NOT NULL AND Reason != ''
                        GROUP BY Reason 
                        ORDER BY count DESC 
                        LIMIT 10"; // Limit to top 10 reasons
            
            $result = mysqli_query($con, $query);
            
            if ($result) {
                $reasons = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $reasons[] = [
                        'reason' => $row['reason'],
                        'count' => (int)$row['count']
                    ];
                }
                
                // If no reasons found, return a default message
                if (empty($reasons)) {
                    echo json_encode([
                        'success' => true,
                        'reasons' => [
                            ['reason' => 'No visit data available', 'count' => 1]
                        ]
                    ]);
                } else {
                    echo json_encode([
                        'success' => true,
                        'reasons' => $reasons
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to fetch visit reasons: ' . mysqli_error($con)
                ]);
            }
            exit();
            
        case 'getCheckInRecords': // --------------------------------------- GET CHECK IN RECORDS ---------------------------------------
            // Enable error reporting
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            
            // Set JSON content type
            header('Content-Type: application/json');
            
            try {
                if (!isset($_GET['studentId'])) {
                    throw new Exception('Student ID is required');
                }
                
                $studentId = $_GET['studentId'];
                
                // First, check if the tables exist
                $checkTables = mysqli_query($con, "SHOW TABLES LIKE 'studentcheckins'");
                if (mysqli_num_rows($checkTables) == 0) {
                    throw new Exception('studentcheckins table does not exist');
                }
                
                // Query to get check-in records for the student
                $query = "SELECT c.id, c.StudentID, c.DateTime, c.Reason, c.Status, c.Outcome, c.FollowUpDate,
                        CONCAT(cp.FirstName, ' ', cp.LastName) as staff_name 
                        FROM studentcheckins c 
                        LEFT JOIN clinicpersonnel cp ON c.StaffID = cp.PersonnelID 
                        WHERE c.StudentID = '$studentId' 
                        ORDER BY c.DateTime DESC";
                
                $result = mysqli_query($con, $query);
                
                if ($result === false) {
                    throw new Exception('Query failed: ' . mysqli_error($con));
                }
                
                $records = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $records[] = [
                        'id' => $row['id'],
                        'StudentID' => $row['StudentID'],
                        'DateTime' => $row['DateTime'],
                        'Reason' => $row['Reason'],
                        'Status' => $row['Status'],
                        'Outcome' => $row['Outcome'],
                        'FollowUpDate' => $row['FollowUpDate'],
                        'staff_name' => $row['staff_name']
                    ];
                }
                
                echo json_encode([
                    'success' => true,
                    'records' => $records
                ]);
                exit();
                
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage() . ' | Database error: ' . mysqli_error($con)
                ]);
            }
            break;
        case 'getRecords': // --------------------------------------- GET RECORDS ---------------------------------------
            // Enable error reporting
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            
            // Set JSON content type
            header('Content-Type: application/json');
            
            try {
                if (!isset($_GET['studentId'])) {
                    throw new Exception('Student ID is required');
                }
                
                $studentId = $_GET['studentId'];
                $recordId = $_GET['recordId'];
                
                // First, check if the tables exist
                $checkTables = mysqli_query($con, "SHOW TABLES LIKE 'studentcheckins'");
                if (mysqli_num_rows($checkTables) == 0) {
                    throw new Exception('studentcheckins table does not exist');
                }
                
                // Query to get check-in records for the student
                $query = "SELECT sc.*, cp.FirstName, cp.LastName, sc.Status AS Status FROM studentcheckins sc 
                         JOIN clinicpersonnel cp ON sc.StaffID = cp.PersonnelID 
                         WHERE sc.ID = '$recordId' AND sc.StudentID = '$studentId'";
                
                $result = mysqli_query($con, $query);
                
                if ($result === false) {
                    throw new Exception('Query failed: ' . mysqli_error($con));
                }
                
                $records = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $records[] = [
                        'id' => $row['ID'],
                        'StudentID' => $row['StudentID'],
                        'DateTime' => $row['DateTime'],
                        'Reason' => $row['Reason'],
                        'Notes' => $row['Notes'],
                        'Status' => $row['Status'],
                        'Outcome' => $row['Outcome'],
                        'UpdatedAt' => $row['UpdatedAt'],
                        'FollowUpDate' => $row['FollowUpDate'],
                        'AssistBy' => $row['FirstName'] . ' ' . $row['LastName']
                    ];
                }
                
                echo json_encode([
                    'success' => true,
                    'records' => $records
                ]);
                exit();
                
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage() . ' | Database error: ' . mysqli_error($con)
                ]);
            }
            
            break;
        case 'getStudent': // --------------------------------------- GET STUDENT ---------------------------------------
            header('Content-Type: application/json');
            if (!isset($_GET['id'])) {
                echo json_encode(['success' => false, 'message' => 'Student ID is required']);
                exit();
            }
            
            $studentId = mysqli_real_escape_string($con, $_GET['id']);
            $query = "SELECT * FROM student WHERE School_ID = '$studentId' LIMIT 1";
            $result = mysqli_query($con, $query);
            
            if ($result && mysqli_num_rows($result) > 0) {
                $student = mysqli_fetch_assoc($result);
                echo json_encode([
                    'success' => true,
                    'student' => $student
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Student not found'
                ]);
            }
            break;
            
        case 'getMedicines': // --------------------------------------- GET MEDICINES ---------------------------------------
            header('Content-Type: application/json');
            try {
                $query = "SELECT med_id, name, SUM(quantity) AS stock_quantity FROM medicine WHERE expiry_date >= CURRENT_DATE GROUP BY name";
                $result = mysqli_query($con, $query);
                
                $medicines = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $medicines[] = [
                        'id' => $row['med_id'],
                        'name' => $row['name'],
                        'stock_quantity' => $row['stock_quantity']
                    ];
                }
                
                echo json_encode([
                    'success' => true,
                    'medicines' => $medicines
                ]);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to load medicines: ' . $e->getMessage()
                ]);
            }
            break;
        case 'LoadBatches': // --------------------------------------- LOAD SPECIFIC MEDICINE BATCHES ---------------------------------------
            header('Content-Type: application/json');
            if (!isset($_GET['medicine_name'])) {
                echo json_encode(['success' => false, 'message' => 'Medicine name is required']);
                exit();
            }
            try {
                $query = "SELECT med_id, name, quantity AS stock_quantity, expiry_date FROM medicine WHERE expiry_date >= CURRENT_DATE AND name = '" . mysqli_real_escape_string($con, $_GET['medicine_name']) . "' ORDER BY expiry_date ASC";
                $result = mysqli_query($con, $query);
                
                $medicines = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $medicines[] = [
                        'id' => $row['med_id'],
                        'name' => $row['name'],
                        'stock_quantity' => $row['stock_quantity'],
                        'expiry_date' => $row['expiry_date']
                    ];
                }
                
                echo json_encode([
                    'success' => true,
                    'medicines' => $medicines
                ]);
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to load batches: ' . $e->getMessage()
                ]);
            }
            break;
        default:
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Invalid action'
            ]);
    }
    exit();
}

// Handle POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $action = $_POST['action'];
    
    switch ($action) {
        case 'addStudent': // --------------------------------------- ADD STUDENT ---------------------------------------
            // Student Basic Information
            $schoolId = $_POST['schoolId'];
            $section = $_POST['section'];
            $department = $_POST['department'];
            $gradeLevel = $_POST['gradeLevel'];
            // Personal Information
            $lastName = $_POST['lastName'];
            $firstName = $_POST['firstName'];
            $middleName = $_POST['middleName'];
            $birthDate = $_POST['birthDate'];
            $age = $_POST['age'];
            $gender = $_POST['gender'];
            $address = $_POST['address'];
            $contactNumber = $_POST['contactNumber'];
            $email = $_POST['email'];
            // Guardian Information
            $guardianFirstName = $_POST['guardianFirstName'];
            $guardianLastName = $_POST['guardianLastName'];
            $guardianContact = $_POST['guardianContact'];
            $guardianEmail = $_POST['guardianEmail'];
            // Emergency Contact
            $emergencyName = $_POST['emergencyName'];
            $emergencyContact = $_POST['emergencyContact'];
            $emergencyRelation = $_POST['emergencyRelation'];
            // Medical Information
            $bloodType = $_POST['bloodType'];
            $Allergies = $_POST['Allergies'];
            $Conditions = $_POST['Conditions'];
            $Medications = $_POST['Medications'];
            
            $query = "SELECT * FROM student WHERE School_ID = '$schoolId'";
            $result = mysqli_query($con, $query);
            
            if (mysqli_num_rows($result) > 0) {
                sendJsonResponse(['success' => false, 'message' => 'Student ID already exists']);
            } else {
                $query = "INSERT INTO student ( School_ID, FirstName, LastName, MiddleName, DateOfBirth, Age, Gender, Department, GradeLevel, Section, StudentContactNumber, StudentEmailAddress, Address, GuardianFirstName, GuardianLastName, GuardianContactNumber, GuardianEmailAddress, EmergencyContactName, EmergencyContactNumber, EmergencyContactRelation, BloodType, KnownAllergies, ChronicConditions, CurrentMedications ) 
                        VALUES ('$schoolId', '$firstName', '$lastName', '$middleName', '$birthDate', '$age', '$gender', '$department', '$gradeLevel', '$section', '$contactNumber', '$email', '$address', '$guardianFirstName', '$guardianLastName', '$guardianContact', '$guardianEmail', '$emergencyName', '$emergencyContact', '$emergencyRelation', '$bloodType', '$Allergies', '$Conditions', '$Medications')";
            
                if (mysqli_query($con, $query)) {
                    $user_id = $_SESSION['User_ID'];
                    $actionType = 'CREATE';
                    $tableName = 'student';
                    $recordId = $schoolId;
                    $actionDetails = "New student added: $schoolId";
                    
                    audit($user_id, $actionType, $tableName, $recordId, $actionDetails);
                    sendJsonResponse(['success' => true, 'message' => 'New record created successfully']);
                } else {
                    sendJsonResponse(['success' => false, 'message' => 'Error: ' . $query . "<br>" . mysqli_error($con)]);
                }
            }
            break;
        case 'updateStudent': // --------------------------------------- UPDATE STUDENT ---------------------------------------
            //student info
            $schoolID = $_POST['schoolId'];
            $department = $_POST['editDepartment'];
            $gradeLevel = $_POST['editGradeLevel'];
            $section = $_POST['editSection'];
            //personal info
            $firstname = $_POST['editFirstName'];
            $middlename = $_POST['editMiddleName'];
            $lastname = $_POST['editLastName'];
            $gender = $_POST['editGender'];
            $birthdate = $_POST['editBirthdate'];
            $age = $_POST['editAge'];
            $contactNumber = $_POST['editContactNumber'];
            $email = $_POST['editStudentEmailAddress'];
            $address = $_POST['editAddress'];
            //guardian info
            $guardianFirstName = $_POST['editGuardianFirstName'];
            $guardianLastName = $_POST['editGuardianLastName'];
            $guardianContactNumber = $_POST['editGuardianContactNumber'];
            $guardianEmailAddress = $_POST['editGuardianEmailAddress'];
            //emergency contact info
            $emergencyContactName = $_POST['editGuardianName'];
            $emergencyContactRelation = $_POST['editGuardianRelationship'];
            $emergencyContactNumber = $_POST['editEmergencyContactNumber'];
            //medical info
            $bloodType = $_POST['editBloodType'];
            $knownAllergies = $_POST['editKnownAllergies'];
            $chronicConditions = $_POST['chronieditChronicConditionscConditions'];
            $currentMedications = $_POST['editCurrentMedication'];
            
            // Start building the update query
            $updateQuery = "UPDATE student SET ";

            // Array to store the fields to update
            $updateFields = array();
            $params = array();
            $types = "";

            // Check and add each field if it's not empty
            if (!empty($department)) { 
                $updateFields[] = "Department = ?";
                $params[] = $department;
                $types .= "s";
            }
            if (!empty($gradeLevel)) {
                $updateFields[] = "GradeLevel = ?";
                $params[] = $gradeLevel;
                $types .= "s";
            }
            if (!empty($section)) {
                $updateFields[] = "Section = ?";
                $params[] = $section;
                $types .= "s";
            }
            if (!empty($firstname)) {
                $updateFields[] = "FirstName = ?";
                $params[] = $firstname;
                $types .= "s";
            }
            if (!empty($middlename)) {
                $updateFields[] = "MiddleName = ?";
                $params[] = $middlename;
                $types .= "s";
            }
            if (!empty($lastname)) {
                $updateFields[] = "LastName = ?";
                $params[] = $lastname;
                $types .= "s";
            }
            if (!empty($gender)) {
                $updateFields[] = "Gender = ?";
                $params[] = $gender;
                $types .= "s";
            }
            if (!empty($birthdate)) {
                $updateFields[] = "DateOfBirth = ?";
                $params[] = $birthdate;
                $types .= "s";
            }
            if (!empty($age)) {
                $updateFields[] = "Age = ?";
                $params[] = $age;
                $types .= "i";
            }
            if (!empty($contactNumber)) {
                $updateFields[] = "StudentContactNumber = ?";
                $params[] = $contactNumber;
                $types .= "s";
            }
            if (!empty($email)) {
                $updateFields[] = "StudentEmailAddress = ?";
                $params[] = $email;
                $types .= "s";
            }
            if (!empty($address)) {
                $updateFields[] = "Address = ?";
                $params[] = $address;
                $types .= "s";
            }
            if (!empty($guardianFirstName)) {
                $updateFields[] = "GuardianFirstName = ?";
                $params[] = $guardianFirstName;
                $types .= "s";
            }
            if (!empty($guardianLastName)) {
                $updateFields[] = "GuardianLastName = ?";
                $params[] = $guardianLastName;
                $types .= "s";
            }
            if (!empty($guardianContactNumber)) {
                $updateFields[] = "GuardianContactNumber = ?";
                $params[] = $guardianContactNumber;
                $types .= "s";
            }
            if (!empty($guardianEmailAddress)) {
                $updateFields[] = "GuardianEmailAddress = ?";
                $params[] = $guardianEmailAddress;
                $types .= "s";
            }
            if (!empty($emergencyContactName)) {
                $updateFields[] = "EmergencyContactName = ?";
                $params[] = $emergencyContactName;
                $types .= "s";
            }
            if (!empty($emergencyContactRelation)) {
                $updateFields[] = "EmergencyContactRelation = ?";
                $params[] = $emergencyContactRelation;
                $types .= "s";
            }
            if (!empty($emergencyContactNumber)) {
                $updateFields[] = "EmergencyContactNumber = ?";
                $params[] = $emergencyContactNumber;
                $types .= "s";
            }
            if (!empty($bloodType)) {
                $updateFields[] = "BloodType = ?";
                $params[] = $bloodType;
                $types .= "s";
            }
            if (!empty($knownAllergies)) {
                $updateFields[] = "KnownAllergies = ?";
                $params[] = $knownAllergies;
                $types .= "s";
            }
            if (!empty($chronicConditions)) {
                $updateFields[] = "ChronicConditions = ?";
                $params[] = $chronicConditions;
                $types .= "s";
            }
            if (!empty($currentMedications)) {
                $updateFields[] = "CurrentMedications = ?";
                $params[] = $currentMedications;
                $types .= "s";
            }

            // Only proceed if there are fields to update
            if (!empty($updateFields)) {
                // Add the update fields to the query
                $updateQuery .= implode(", ", $updateFields);
                
                // Add the WHERE clause
                $updateQuery .= " WHERE School_ID = ?";
                $types .= "s";
                $params[] = $schoolID;
                
                // Prepare and execute the statement
                $stmt = $con->prepare($updateQuery);
                
                // Dynamically bind parameters
                $bindParams = array_merge(array($types), $params);
                $bindParamsReferences = array();
                foreach($bindParams as $key => $value) {
                    $bindParamsReferences[$key] = &$bindParams[$key];
                }
                call_user_func_array(array($stmt, 'bind_param'), $bindParamsReferences);
                
                if ($stmt->execute()) {
                    $user_id = $_SESSION['User_ID'];
                    $actionType = 'UPDATE';
                    $tableName = 'student';
                    $recordId = $schoolID;
                    $actionDetails = "Student updated: $schoolID";
                    
                    audit($user_id, $actionType, $tableName, $recordId, $actionDetails);
                    sendJsonResponse(['success' => true, 'message' => 'Record updated successfully']);
                } else {
                    sendJsonResponse(['success' => false, 'message' => 'Error updating record: ' . $con->error]);
                }
                $stmt->close();
            } else {
                sendJsonResponse(['success' => false, 'message' => 'No fields to update']);
            }
            
            break;
    
        case 'Checkin': // --------------------------------------- CHECKIN ---------------------------------------
            $studentId = $_POST['CheckInStudentId'];
            $staffId = $_POST['CheckInStaffId'];
            $checkInDateTime = $_POST['CheckInDateTime'];
            $reasonForVisit = $_POST['reasonForVisit'];
            $notes = $_POST['notes'];
            $status = "In Progress";
            
            $query = "INSERT INTO studentcheckins (StudentID, DateTime, Reason, Notes, Status, StaffID) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param("ssssss", $studentId, $checkInDateTime, $reasonForVisit, $notes, $status, $staffId);
            
            if ($stmt->execute()) {
                $user_id = $staffId;
                $actionType = 'CREATE';
                $tableName = 'studentcheckins';
                $recordId = $studentId;
                $actionDetails = "New check-in added: $studentId";
                
                audit($user_id, $actionType, $tableName, $recordId, $actionDetails);
                sendJsonResponse(['success' => true,'message' => 'Check-in successful!']);
            } else {
                sendJsonResponse(['success' => false,'message' => 'Error: ' . $query . "<br>" . $con->error]);
            }
            $stmt->close();
            break;
        case 'updateCheckInRecord': // --------------------------------------- UPDATE CHECKIN ---------------------------------------
            $recordId = $_POST['recordId'];
            $studentId = str_replace('ID: ', '', $_POST['studentId']);
            $FollowUpDate_record = $_POST['FollowUpDate_record'];
            $checkup_reason_record = $_POST['checkup_reason_record'];
            $Notes_record = $_POST['Notes_record'];
            
            
            $query = "UPDATE studentcheckins ";
            if(!empty($FollowUpDate_record)){
                $query .= "SET Status = 'Follow-up', Reason = ?, Notes = ?, FollowUpDate = ? WHERE id = ?";
                $stmt = mysqli_prepare($con, $query);
                if ($stmt === false) {
                    throw new Exception('Failed to prepare statement: ' . mysqli_error($con));
                }
                mysqli_stmt_bind_param($stmt, 'sssi', $checkup_reason_record, $Notes_record, $FollowUpDate_record, $recordId);
            } else {
                $query .= "SET Reason = ?, Notes = ? WHERE id = ?";
                $stmt = mysqli_prepare($con, $query);
                if ($stmt === false) {
                    throw new Exception('Failed to prepare statement: ' . mysqli_error($con));
                }
                mysqli_stmt_bind_param($stmt, 'ssi', $checkup_reason_record, $Notes_record, $recordId);
            }
            
            if (mysqli_stmt_execute($stmt)) {
            
                if(!empty($FollowUpDate_record)){
                
                    // Get student's email from database
                    $emailQuery = "SELECT * FROM student WHERE School_ID = ?";
                    $emailStmt = mysqli_prepare($con, $emailQuery);
                    mysqli_stmt_bind_param($emailStmt, 's', $studentId);
                    mysqli_stmt_execute($emailStmt);
                    $emailResult = mysqli_stmt_get_result($emailStmt);
                    $studentData = mysqli_fetch_assoc($emailResult);
                    $studentEmail = $studentData['StudentEmailAddress'] ?? null;
                    $studentName = $studentData['FirstName'] . " ". $studentData['MiddleName'] . " " . $studentData['LastName'];
                    
                    // Send email
                    require '../phpmailer/src/Exception.php';
                    require '../phpmailer/src/PHPMailer.php';
                    require '../phpmailer/src/SMTP.php';
                    $mail = new PHPMailer(true);

                    try {
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'davemalaran2004@gmail.com';
                        $mail->Password = 'tgjtrujoubpihahl'; 
                        $mail->SMTPSecure = 'ssl';
                        $mail->Port = 465;

                        $mail->setFrom('davemalaran2004@gmail.com', 'Patient Information & Medical Inventory System');
                        $mail->addAddress($studentEmail);
                        $mail->isHTML(true);

                        $mail->Subject = "Follow-up Assessment Scheduled";
                        $mail->Body = "
                            <h3>Follow-up Assessment Confirmation</h3>
                            <p>Dear " . nl2br(htmlspecialchars($studentName)) . ",</p>
                            <p>Your follow-up assessment has been scheduled for: <strong>" . date('F j, Y \a\t g:i A', strtotime($FollowUpDate_record)) . "</strong></p>
                            <p><strong>Reason for Follow-up:</strong> " . htmlspecialchars($checkup_reason_record) . "</p>
                            <p><strong>Notes:</strong> " . nl2br(htmlspecialchars($Notes_record)) . "</p>
                            <p>Please arrive 10 minutes before your scheduled time. If you need to reschedule, please contact the clinic in advance.</p>
                            <p>Best regards,<br>Granby Colleges of Science and Technology - PIAMIS</p>
                        ";

                        if (!$mail->send()) {
                            sendJsonResponse(['success' => false, 'message' => 'Failed to send Follow-up email.']);
                        } else {
                            sendJsonResponse(['success' => true, 'message' => 'Follow-up email sent successfully.']);
                        }
                    } catch (Exception $e) {
                        sendJsonResponse(['success' => false, 'message' => 'Follow-up email could not be sent.']);
                    }
                }
            
                $user_id = $_SESSION['User_ID'];
                $actionType = 'UPDATE';
                $tableName = 'studentcheckins';
                $recordId = $studentId;
                $actionDetails = "Check-up updated: $studentId";
                
                audit($user_id, $actionType, $tableName, $recordId, $actionDetails);
                sendJsonResponse([
                    'success' => true,
                    'message' => 'Check-up record updated successfully!'
                ]);
            } else {
                throw new Exception('Failed to update Check-up record: ' . mysqli_error($con));
            }
            break;
        case 'updateStudentStatus': // --------------------------------------- UPDATE STUDENT STATUS ---------------------------------------
            $schoolId = $_POST['schoolID'];
            $newStatus = $_POST['newStatus'];
            
            // Update user status in database
            $query = "UPDATE student SET Status = ? WHERE School_ID = ?";
            $stmt = mysqli_prepare($con, $query);
            
            if ($stmt === false) {
                throw new Exception('Failed to prepare statement: ' . mysqli_error($con));
            }
            
            mysqli_stmt_bind_param($stmt, 'ss', $newStatus, $schoolId);
            
            if (mysqli_stmt_execute($stmt)) {
                sendJsonResponse([
                    'success' => true,
                    'message' => 'Student status updated successfully!'
                ]);
            } else {
                throw new Exception('Failed to update student status: ' . mysqli_error($con));
            }
            break;
        case 'MarkasDone_Record': // --------------------------------------- MARK AS DONE ---------------------------------------
            $recordId = $_POST['recordId'];
            $studentId = str_replace('ID: ', '', $_POST['studentId']);
            $Outcome = $_POST['Outcome'];
            
            $query = "UPDATE studentcheckins SET Status = 'Completed', Outcome = ? WHERE id = ?";
            $stmt = mysqli_prepare($con, $query);
            
            if ($stmt === false) {
                throw new Exception('Failed to prepare statement: ' . mysqli_error($con));
            }
            
            mysqli_stmt_bind_param($stmt, 'ss', $Outcome, $recordId);
            
            if (mysqli_stmt_execute($stmt)) {
                $user_id = $_SESSION['User_ID'];
                $actionType = 'UPDATE';
                $tableName = 'studentcheckins';
                $recordId = $studentId;
                $actionDetails = "Check-up marked as done: $studentId";
                
                audit($user_id, $actionType, $tableName, $recordId, $actionDetails);
                sendJsonResponse([
                    'success' => true,
                    'message' => 'Check-up record marked as done successfully!'
                ]);
            } else {
                throw new Exception('Failed to mark check-up record as done: ' . mysqli_error($con));
            }
            break;
    }    
}
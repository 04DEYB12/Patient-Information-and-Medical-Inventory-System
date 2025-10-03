<?php
include '../Landing Repository/Connection.php';

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
                $query = "SELECT c.id, c.StudentID, c.DateTime, c.Reason, c.Status, c.Outcome, 
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
                $query = "SELECT * FROM studentcheckins WHERE ID = '$recordId' AND StudentID = '$studentId'";
                
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
                        'Status' => $row['Status'],
                        'Outcome' => $row['Outcome'],
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
            $school_id = $_POST['school_id'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $middle_name = $_POST['middle_name'];
            $date_of_birth = $_POST['date_of_birth'];
            $age = $_POST['age'];
            $gender = $_POST['gender'];
            $department = $_POST['department'];
            $gradeLevel = $_POST['gradeLevel'];
            $section = $_POST['section'];
            $student_contact_number = $_POST['student_contact_number'];
            $student_email_address = $_POST['student_email_address'];
            $address = $_POST['address'];
            $guardian_firstName = $_POST['guardian_firstName'];
            $guardian_lastName = $_POST['guardian_lastName'];
            $guardian_contactNumber = $_POST['guardian_contactNumber'];
            $guardian_emailAddress = $_POST['guardian_emailAddress'];
            $emergency_contactName = $_POST['emergency_contactName'];
            $emergency_contactNumber = $_POST['emergency_contactNumber'];
            $emergency_contactRelation = $_POST['emergency_contactRelation'];
            $blood_type = $_POST['blood_type'];
            $known_allergies = $_POST['known_allergies'];
            $chronic_conditions = $_POST['chronic_conditions'];
            $current_medications = $_POST['current_medications'];
            
            $query = "SELECT * FROM student WHERE School_ID = '$school_id'";
            $result = mysqli_query($con, $query);
            
            if (mysqli_num_rows($result) > 0) {
                echo "<script>alert('Student ID already exists'); window.location.href = '../PIAIMS Repository/PIAIMS.php';</script>";
            } else {
                $query = "INSERT INTO student ( School_ID, FirstName, LastName, MiddleName, DateOfBirth, Age, Gender, Department, GradeLevel, Section, StudentContactNumber, StudentEmailAddress, Address, GuardianFirstName, GuardianLastName, GuardianContactNumber, GuardianEmailAddress, EmergencyContactName, EmergencyContactNumber, EmergencyContactRelation, BloodType, KnownAllergies, ChronicConditions, CurrentMedications ) 
                        VALUES ('$school_id', '$first_name', '$last_name', '$middle_name', '$date_of_birth', '$age', '$gender', '$department', '$gradeLevel', '$section', '$student_contact_number', '$student_email_address', '$address', '$guardian_firstName', '$guardian_lastName', '$guardian_contactNumber', '$guardian_emailAddress', '$emergency_contactName', '$emergency_contactNumber', '$emergency_contactRelation', '$blood_type', '$known_allergies', '$chronic_conditions', '$current_medications')";
            
                if (mysqli_query($con, $query)) {
                    echo "<script>alert('New record created successfully'); window.location.href = '../PIAIMS Repository/PIAIMS.php';</script>";
                } else {
                    echo "Error: " . $query . "<br>" . mysqli_error($con);
                }
            }
            break;
        case 'updateStudent': // --------------------------------------- UPDATE STUDENT ---------------------------------------
            //student info
            $schoolID = $_POST['schoolID'];
            $department = $_POST['department'];
            $gradeLevel = $_POST['GradeLevel'];
            $section = $_POST['section'];
            
            //personal info
            $firstname = $_POST['firstname'];
            $middlename = $_POST['middlename'];
            $lastname = $_POST['lastname'];
            $gender = $_POST['gender'];
            $birthdate = $_POST['birthdate'];
            $age = $_POST['age'];
            $contactNumber = $_POST['contactNumber'];
            $email = $_POST['email'];
            $address = $_POST['address'];
            
            //guardian info
            $guardianFirstName = $_POST['guardianFirstName'];
            $guardianLastName = $_POST['guardianLastName'];
            $guardianContactNumber = $_POST['guardianContactNumber'];
            $guardianEmailAddress = $_POST['guardianEmailAddress'];
            
            //emergency contact info
            $emergencyContactName = $_POST['emergencyContactName'];
            $emergencyContactRelation = $_POST['emergencyContactRelation'];
            $emergencyContactNumber = $_POST['emergencyContactNumber'];
            
            //medical info
            $bloodType = $_POST['bloodType'];
            $knownAllergies = $_POST['knownAllergies'];
            $chronicConditions = $_POST['chronicConditions'];
            $currentMedications = $_POST['currentMedication'];
            
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
                    echo "<script>alert('Record updated successfully'); window.location.href = '../PIAIMS Repository/Patients.php';</script>";
                } else {
                    echo "Error updating record: " . $con->error;
                }
                $stmt->close();
            } else {
                echo "No fields to update";
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
            $studentId = $_POST['studentId'];
            $updatedAt = $_POST['updatedAt'];
            $outcome = $_POST['outcome'];
            
            // Update user status in database
            $query = "UPDATE studentcheckins SET Status = 'completed', Outcome = ?, UpdatedAt = ? WHERE id = ?";
            $stmt = mysqli_prepare($con, $query);
            
            if ($stmt === false) {
                throw new Exception('Failed to prepare statement: ' . mysqli_error($con));
            }
            
            mysqli_stmt_bind_param($stmt, 'ssi', $outcome, $updatedAt, $recordId);
            
            if (mysqli_stmt_execute($stmt)) {
                sendJsonResponse([
                    'success' => true,
                    'message' => 'Check-in record updated successfully!'
                ]);
            } else {
                throw new Exception('Failed to update Check-in record: ' . mysqli_error($con));
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
    }    
}
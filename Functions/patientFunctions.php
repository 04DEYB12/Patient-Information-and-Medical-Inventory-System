<?php
include '../Landing Repository/Connection.php';

// Handle GET requests for student data
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'getVisitReasons':
            header('Content-Type: application/json');
            
            // Query to get the most common reasons for visits
            $query = "SELECT 
                        Reason as reason, 
                        COUNT(*) as count 
                      FROM checkin 
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
            
        case 'getCheckInRecords':
            if (!isset($_GET['studentId'])) {
                echo json_encode(['success' => false, 'message' => 'Student ID is required']);
                exit();
            }
            
            header('Content-Type: application/json');
            $studentId = mysqli_real_escape_string($con, $_GET['studentId']);
            
            // Query to get check-in records for the student
            $query = "SELECT c.check_in_time, c.reason, c.status, c.outcome, 
                             CONCAT(s.FirstName, ' ', s.LastName) as staff_name 
                      FROM checkin c 
                      LEFT JOIN staff s ON c.staff_id = s.StaffID 
                      WHERE c.student_id = '$studentId' 
                      ORDER BY c.check_in_time DESC";
            
            $result = mysqli_query($con, $query);
            
            if ($result) {
                $records = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $records[] = [
                        'check_in_time' => $row['check_in_time'],
                        'reason' => $row['reason'],
                        'status' => strtolower($row['status']),
                        'outcome' => $row['outcome'],
                        'staff_name' => $row['staff_name']
                    ];
                }
                
                echo json_encode([
                    'success' => true,
                    'records' => $records
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to fetch records: ' . mysqli_error($con)
                ]);
            }
            break;
            
        case 'getStudent':
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
                    echo "<script>alert('Record updated successfully'); window.location.href = '../PIAIMS Repository/PIAIMS.php';</script>";
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
            $reasonForVisit = $_POST['reasonForVisit'];
            $checkInDateTime = $_POST['checkInDateTime'];
            $staffId = $_POST['CheckInStaffId'];
            $status = "In Progress";
            
            $query = "INSERT INTO studentcheckins (StudentID, DateTime, Reason,Status, StaffID) VALUES (?, ?, ?, ?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param("sssss", $studentId, $checkInDateTime, $reasonForVisit, $status, $staffId);
            
            if ($stmt->execute()) {
                echo "<script>alert('Check-in successful!'); window.location.href = '../PIAIMS Repository/PIAIMS.php';</script>";
            } else {
                echo "Error: " . $query . "<br>" . $con->error;
            }
            $stmt->close();
            break;
    
    }    
}
<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering to catch any unwanted output
if (ob_get_level() === 0) {
    ob_start();
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include connection file
include '../Landing Repository/Connection.php';

// Check if connection was successful
if (!isset($con) || !$con) {
    die("<script>alert('Database connection failed: " . mysqli_connect_error() . "'); window.history.back();</script>");
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

/**
 * Send alert and redirect (for POST requests)
 */
function sendAlertAndRedirect($message, $url = '../PIAIMS Repository/AccountManagement.php') {
    // Clean any output that might have been generated before
    if (ob_get_level() > 0) {
        ob_clean();
    }
    
    // Output JavaScript alert and redirect
    echo "<script>
        alert('" . addslashes($message) . "');
        window.location.href = '" . htmlspecialchars($url, ENT_QUOTES) . "';
    </script>";
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

// Handle GET requests (API-like endpoints)
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action'])) {
    try {
        switch ($_GET['action']) {
            case 'get_users':
                if (!isset($_SESSION['User_ID'])) {
                    sendJsonResponse(['error' => 'Unauthorized'], 401);
                }
                
                $query = "SELECT 
                            PersonnelID as id,
                            CONCAT(FirstName, ' ', COALESCE(MiddleName, ''), ' ', LastName) as name,
                            RoleName as role,
                            EmailAddress as email,
                            ContactNumber as phone,
                            HireDate as hiredate,
                            Status as status
                        FROM clinicpersonnel cp 
                        JOIN userrole ur ON cp.RoleID = ur.RoleID";
                
                $result = mysqli_query($con, $query);
                
                if ($result === false) {
                    throw new Exception('Query failed: ' . mysqli_error($con));
                }
                
                $users = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $users[] = $row;
                }
                
                // Send JSON response
                sendJsonResponse($users);
                break;
                
            default:
                sendJsonResponse(['error' => 'Invalid action'], 400);
        }
        
    } catch (Exception $e) {
        error_log('Error in UserFunctions.php (GET): ' . $e->getMessage());
        sendJsonResponse([
            'error' => 'An error occurred',
            'message' => $e->getMessage()
        ], 500);
    }
    
    mysqli_close($con);
    exit();
}

// Handle POST requests (form submissions)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $action = $_POST['action'];
    
    try {
        switch ($action) {
            case 'login': // ------------------------------- Login --------------------------------
                $email = $_POST['email'];
                $password = $_POST['password'];
                
                if(empty($email) || empty($password)) {
                    sendJsonResponse(['success' => false, 'error' => 'Please fill in all fields!']);
                    break;
                }
            
                $query = "SELECT * FROM clinicpersonnel cp JOIN userrole ur ON cp.RoleID = ur.RoleID WHERE cp.EmailAddress = ? LIMIT 1";
                $stmt = $con->prepare($query);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result && $result->num_rows > 0) {
                    $user_data = $result->fetch_assoc();
                    if (password_verify($password, $user_data['PasswordHash'])) {
                        if($user_data['Status'] == 'Active') {
                            $_SESSION['User_ID'] = $user_data['PersonnelID'];
                            $_SESSION['role'] = $user_data['RoleName'];
                            session_regenerate_id(true);
                            
                            sendJsonResponse([
                                'success' => true,
                                'redirect' => '../PIAIMS Repository/Dashboard.php',
                                'message' => 'Login successful!'
                            ]);
                        } else { sendJsonResponse(['success' => false, 'error' => 'Account is inactive!']); }
                    } else { sendJsonResponse(['success' => false, 'error' => 'Incorrect password!']); }
                } else { sendJsonResponse(['success' => false, 'error' => 'Account not found!']); }
                break;
            case 'send_otp': // ------------------------------- Send OTP --------------------------------
                $email = $_POST['email'];
                
                $stmt = $con->prepare("SELECT PersonnelID FROM clinicpersonnel WHERE EmailAddress = ? LIMIT 1");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                
                if ($stmt->get_result()->num_rows === 0) {
                    sendJsonResponse(['success' => false, 'error' => 'No Account Associated with this Email!']); break;
                }
                
                
                // Check recent OTP requests (limit to 3 per 5 minutes)
                $checkAttempts = $con->prepare("
                    SELECT COUNT(*) as attempt_count, MAX(created_at) as last_attempt 
                    FROM otp_request 
                    WHERE email = ? AND created_at > DATE_SUB(NOW(), INTERVAL 5 MINUTE)
                ");
                $checkAttempts->bind_param("s", $email);
                $checkAttempts->execute();
                $result = $checkAttempts->get_result();
                $data = $result->fetch_assoc();

                if ($data['attempt_count'] >= 3) {
                    $lastAttempt = new DateTime($data['last_attempt']);
                    $nextAllowed = $lastAttempt->add(new DateInterval('PT5M'));
                    $now = new DateTime();
                    
                    if ($now < $nextAllowed) {
                        $remaining = $now->diff($nextAllowed)->format('%i minutes %s seconds');
                        sendJsonResponse(['success' => false, 'error' => "You've reached the maximum of 3 OTP requests. Please try again in $remaining."]);
                        break;
                    }
                }
                
                // Generate 6-digit OTP
                $number = rand(100000, 999999);
                $otp = password_hash($number, PASSWORD_DEFAULT);

                // Store OTP
                $insert = $con->prepare("INSERT INTO otp_request (email, otp_code, created_at) VALUES (?, ?, NOW())");
                $insert->bind_param("ss", $email, $otp);
                $insert->execute();
                
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
                    $mail->addAddress($email);
                    $mail->isHTML(true);

                    $mail->Subject = "Your OTP for Password Reset";
                    $mail->Body = "Hello,<br><br>Your OTP code is: <b>$number</b><br><br>This code is valid for the next 10 minutes.";

                    if (!$mail->send()) {
                        sendJsonResponse(['success' => false, 'error' => 'Failed to send OTP. Please try again.']);
                    } else {
                        sendJsonResponse(['success' => true, 'message' => 'OTP sent successfully to your email.']);
                    }
                } catch (Exception $e) {
                    sendJsonResponse(['success' => false, 'error' => 'OTP could not be sent.']);
                }
                
                break;
            case 'verify_otp': // ------------------------------- Verify OTP --------------------------------
                $email = $_POST['email'];
                $otp = $_POST['otp'];
                
                if(empty($email) || empty($otp)) {
                    sendJsonResponse(['success' => false, 'error' => 'Please fill in all fields!']);
                    break;
                }
                
                // Check OTP (valid, unused, within 10 minutes)
                $query = "SELECT * FROM otp_request WHERE email = ? AND is_used = 0 
                        AND created_at >= (NOW() - INTERVAL 10 MINUTE) 
                        ORDER BY created_at DESC";

                $stmt = $con->prepare($query);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                $otpMatched = false;
                $otpData = null;

                // Loop through results to find a matching OTP
                while ($row = $result->fetch_assoc()) {
                    if (password_verify($otp, $row['otp_code'])) {
                        $otpMatched = true;
                        $otpData = $row;
                        break;
                    }
                }

                if (!$otpMatched) {
                    sendJsonResponse(['success' => false, 'error' => 'Invalid or Expired OTP!']);
                    break;
                }
                
                // Mark OTP as used
                $updateQuery = "UPDATE otp_request SET is_used = 1 WHERE id = ?";
                $updateStmt = $con->prepare($updateQuery);
                $updateStmt->bind_param("i", $otpData['id']);
                $updateStmt->execute();
                sendJsonResponse(['success' => true, 'message' => 'OTP verified successfully']);
                
                break;
            case 'change_password': // ------------------------- Change Password -----------------------------------
                $email = $_POST['email'];
                $newPassword = $_POST['new_password'];
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                
                date_default_timezone_set('Asia/Manila');
                $PasswordChangeDT = date('Y-m-d H:i:s');
                date_default_timezone_set(date_default_timezone_get());
            
                try {
                    $update = $con->prepare("UPDATE clinicpersonnel SET PasswordHash = ?, PasswordChangeDT = ? WHERE EmailAddress = ?");
                    $update->bind_param("sss", $hashedPassword, $PasswordChangeDT, $email);
                    if (!$update->execute()) {
                        throw new Exception($update->error);
                    }
                    sendJsonResponse(['success' => true, 'message' => 'Password changed successfully']);
                } catch (Exception $e) {
                    sendJsonResponse(['success' => false, 'error' => 'Failed to update password: ' . $e->getMessage()]);
                }
                break;
            case 'addUser': // ------------------------------- Add User --------------------------------
                $firstName = mysqli_real_escape_string($con, $_POST['firstName']);
                $lastName = mysqli_real_escape_string($con, $_POST['lastName']);
                $middleName = isset($_POST['middleName']) ? mysqli_real_escape_string($con, $_POST['middleName']) : '';
                $contactNumber = mysqli_real_escape_string($con, $_POST['contactNumber']);
                $email = mysqli_real_escape_string($con, $_POST['email']);
                $password = "PIAMIS" . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $role = 2;
                date_default_timezone_set('Asia/Manila');
                $hiredate = date('Y-m-d H:i:s');
                $status = "Active";
                
                // Get the highest current PersonnelID and increment by 1
                $result = $con->query("SELECT MAX(CAST(REPLACE(PersonnelID, 'PIAMIS', '') AS UNSIGNED)) as max_id 
                                        FROM clinicpersonnel 
                                        WHERE PersonnelID LIKE 'PIAMIS%'");
                $row = $result->fetch_assoc();
                $nextId = 1; // Default if no records exist
                
                if ($row['max_id'] !== null) {
                    $nextId = (int)$row['max_id'] + 1;
                }
                
                $personnelID = 'PIAMIS' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
                
                // Check if email exists
                $query = "SELECT * FROM clinicpersonnel WHERE EmailAddress = '$email'";
                $result = mysqli_query($con, $query);
                
                if (mysqli_num_rows($result) > 0) {
                    sendAlertAndRedirect('Email already exists');
                }

                // Insert new user
                $query = "INSERT INTO clinicpersonnel (PersonnelID,FirstName, LastName, MiddleName, RoleID, ContactNumber, EmailAddress, PasswordHash, HireDate, Status) 
                        VALUES ('$personnelID', '$firstName', '$lastName', '$middleName', $role, '$contactNumber', '$email', '$hashedPassword', '$hiredate', '$status')";
            
                if (mysqli_query($con, $query)) {
                    // Send email with credentials
                    require '../phpmailer/src/Exception.php';
                    require '../phpmailer/src/PHPMailer.php';
                    require '../phpmailer/src/SMTP.php';
                    
                    $mail = new PHPMailer(true);

                    try {
                        // Server settings
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'davemalaran2004@gmail.com';
                        $mail->Password = 'tgjtrujoubpihahl';
                        $mail->SMTPSecure = 'ssl';
                        $mail->Port = 465;

                        // Recipients
                        $mail->setFrom('davemalaran2004@gmail.com', 'Patient Information & Medical Inventory System');
                        $mail->addAddress($email);

                        $mail -> isHTML(true);

                        $mail-> Subject = "Your Patient Information & Medical Inventory System Account is Ready";


                        // Email content
                        $mail->isHTML(true);
                        $mail->Subject = 'Your Patient Information & Medical Inventory System Account is Ready';
                        
                        $mail->Body = "Dear $firstName $lastName,<br><br>
                        Your account for the <b>Patient Information & Medical Inventory System</b> has been successfully created.<br><br>
                    
                        <b>Login Details:</b><br>
                        - Email: $email<br>
                        - Temporary Password: <b>$password</b><br><br>
                    
                        <span style='color: #d32f2f; font-weight: bold;'>For security reasons, we strongly recommend changing your password upon first login.</span><br><br>
                    
                        You can log in using your email address and the temporary password provided above. After logging in, please navigate to your profile settings to update your password to something more secure and memorable.<br><br>
                    
                        If you have any issues accessing your account or need assistance, please don't hesitate to contact our support team.<br><br>
                    
                        Best regards,<br>
                        <i>Granby Colleges of Science and Technology</i><br>
                        System Administrator";

                        $mail->send();
                        
                        $user_id = $_SESSION['User_ID'];
                        $actionType = 'CREATE';
                        $tableName = 'clinicpersonnel';
                        $recordId = (string)$personnelID;
                        $actionDetails = "New user added: $firstName $lastName";
                        
                        audit($user_id, $actionType, $tableName, $recordId, $actionDetails);
                        sendAlertAndRedirect('User added successfully');
                        
                        } catch (Exception $e) {
                            error_log('Mailer Error: ' . $mail->ErrorInfo);
                            sendAlertAndRedirect('User added, but failed to send email: ' . $mail->ErrorInfo);
                        }
                } else {
                    throw new Exception('Failed to add user: ' . mysqli_error($con));
                }
                break;
            case 'toggleUserStatus': // ------------------------------- Toggle User Status --------------------------------
                $userId = $_POST['userId'];
                $newStatus = $_POST['newStatus'];
                
                // Validate status
                if (!in_array($newStatus, ['Active', 'Inactive'])) {
                    sendJsonResponse(['error' => 'Invalid status'], 400);
                }
                
                // Update user status in database
                $query = "UPDATE clinicpersonnel SET Status = ? WHERE PersonnelID = ?";
                $stmt = mysqli_prepare($con, $query);
                
                if ($stmt === false) {
                    throw new Exception('Failed to prepare statement: ' . mysqli_error($con));
                }
                
                mysqli_stmt_bind_param($stmt, 'ss', $newStatus, $userId);
                
                if (mysqli_stmt_execute($stmt)) {
                    $user_id = $_SESSION['User_ID'];
                    $actionType = 'UPDATE';
                    $tableName = 'clinicpersonnel';
                    $recordId = (string)$userId;
                    $actionDetails = "User status updated: $newStatus";
                    
                    audit($user_id, $actionType, $tableName, $recordId, $actionDetails);
                    sendJsonResponse([
                        'success' => true,
                        'message' => 'User status updated successfully',
                        'newStatus' => $newStatus
                    ]);
                } else {
                    throw new Exception('Failed to update user status: ' . mysqli_error($con));
                }
                break;
            case 'updateEmail': // ------------------------------- Update Email --------------------------------
                $userId = mysqli_real_escape_string($con, $_POST['hidden_userId']);
                $newEmail = mysqli_real_escape_string($con, $_POST['editEmailInput']);
                
                // Validate email
                if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                    sendAlertAndRedirect('Invalid email format');
                }
                
                // Update user email in database
                $query = "UPDATE clinicpersonnel SET EmailAddress = ? WHERE PersonnelID = ?";
                $stmt = mysqli_prepare($con, $query);
                
                if ($stmt === false) {
                    throw new Exception('Failed to prepare statement: ' . mysqli_error($con));
                }
                
                if ($stmt === false) {
                    throw new Exception('Failed to prepare statement: ' . mysqli_error($con));
                }
                
                mysqli_stmt_bind_param($stmt, 'ss', $newEmail, $userId);
                
                if (mysqli_stmt_execute($stmt)) {
                    $user_id = $_SESSION['User_ID'];
                    $actionType = 'UPDATE';
                    $tableName = 'clinicpersonnel';
                    $recordId = (string)$userId;
                    $actionDetails = "User email updated: $newEmail";
                    
                    audit($user_id, $actionType, $tableName, $recordId, $actionDetails);
                    sendAlertAndRedirect('User email updated successfully');
                } else {
                    throw new Exception('Failed to update email: ' . mysqli_error($con));
                }
                break;
            case 'PasswordReset': // ------------------------------- Password Reset --------------------------------
                try {
                    // Validate input
                    if (!isset($_POST['userId']) || empty($_POST['userId'])) {
                        throw new Exception('User ID is required');
                    }
                    
                    $PersonnelID = $_POST['userId'];
                    $newPassword = "PIAMIS" . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    
                    // Get user details first to verify user exists and get email
                    $userQuery = "SELECT PersonnelID, EmailAddress, CONCAT(FirstName, ' ', LastName) as FullName 
                                 FROM clinicpersonnel 
                                 WHERE PersonnelID = ?";
                    $userStmt = mysqli_prepare($con, $userQuery);
                    
                    if ($userStmt === false) {
                        throw new Exception('Database error: ' . mysqli_error($con));
                    }
                    
                    mysqli_stmt_bind_param($userStmt, 's', $PersonnelID);
                    mysqli_stmt_execute($userStmt);
                    $userResult = mysqli_stmt_get_result($userStmt);
                    $user = mysqli_fetch_assoc($userResult);
                    
                    if (!$user) {
                        throw new Exception('User not found');
                    }
                    
                    // Update user's password
                    $updateQuery = "UPDATE clinicpersonnel SET PasswordHash = ? WHERE PersonnelID = ?";
                    $updateStmt = mysqli_prepare($con, $updateQuery);
                    
                    if ($updateStmt === false) {
                        throw new Exception('Database error: ' . mysqli_error($con));
                    }
                    
                    mysqli_stmt_bind_param($updateStmt, 'ss', $hashedPassword, $PersonnelID);
                    
                    if (!mysqli_stmt_execute($updateStmt)) {
                        throw new Exception('Failed to update password: ' . mysqli_error($con));
                    }
                    
                    // Send email with new password
                    require '../phpmailer/src/Exception.php';
                    require '../phpmailer/src/PHPMailer.php';
                    require '../phpmailer/src/SMTP.php';
                    
                    $mail = new PHPMailer(true);

                    try {
                        // Server settings
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'davemalaran2004@gmail.com';
                        $mail->Password = 'tgjtrujoubpihahl';
                        $mail->SMTPSecure = 'ssl';
                        $mail->Port = 465;
                        
                        // Recipients
                        $mail->setFrom('davemalaran2004@gmail.com', 'Patient Information & Medical Inventory System');
                        $mail->addAddress($user['EmailAddress']);

                        // Email content
                        $mail->isHTML(true);
                        $mail->Subject = 'Your Password Has Been Reset';
                        
                        $mail->Body = "Dear {$user['FullName']},<br><br>
                        Your password for the <b>Patient Information & Medical Inventory System</b> has been successfully reset.<br><br>
                    
                        <b>Your new temporary password is:</b><br>
                        <span style='font-size: 18px; font-weight: bold;'>$newPassword</span><br><br>
                    
                        <span style='color: #d32f2f; font-weight: bold;'>For security reasons, please change this password immediately after logging in.</span><br><br>
                    
                        You can log in using your email address and the temporary password provided above. After logging in, please navigate to your profile settings to update your password to something more secure and memorable.<br><br>
                    
                        If you did not request this password reset, please contact the system administrator immediately.<br><br>
                    
                        Best regards,<br>
                        <i>Granby Colleges of Science and Technology</i><br>
                        System Administrator";

                        $mail->send();
                        
                        $user_id = $_SESSION['User_ID'];
                        $actionType = 'UPDATE';
                        $tableName = 'clinicpersonnel';
                        $recordId = (string)$PersonnelID;
                        $actionDetails = "User Password Reset";
                        
                        audit($user_id, $actionType, $tableName, $recordId, $actionDetails);
                        
                        // Return success response
                        sendJsonResponse([
                            'success' => true,
                            'message' => 'Password has been reset successfully. <br> The new password has been sent to the user\'s email.'
                        ]);
                        
                    } catch (Exception $e) {
                        error_log('Mailer Error: ' . $e->getMessage());
                        // Even if email fails, we still consider the password reset successful
                        // but we inform the admin about the email failure
                        sendJsonResponse([
                            'success' => true,
                            'message' => 'Password has been reset, <br> but failed to send email.<br> ERROR: ' . $e->getMessage(),
                            'newPassword' => $newPassword // Include the password in the response as fallback
                        ]);
                    }
                    
                } catch (Exception $e) {
                    error_log('Password Reset Error: ' . $e->getMessage());
                    sendJsonResponse([
                        'success' => false,
                        'error' => $e->getMessage()
                    ]);
                }
                break;
            case 'updateUserRole': // ------------------------------- Update User Role --------------------------------
                $userId = $_POST['userId'];
                $newRole = $_POST['newRole'];
                $RoleID = $newRole === 'Administrator' ? 1 : 2;
                
                // Validate status
                if (!in_array($newRole, ['Administrator', 'Staff'])) {
                    sendJsonResponse(['error' => 'Invalid Role'], 400);
                }
                // Update user status in database
                $query = "UPDATE clinicpersonnel SET RoleID = ? WHERE PersonnelID = ?";
                $stmt = mysqli_prepare($con, $query);
            
                if ($stmt === false) {
                    throw new Exception('Failed to prepare statement: ' . mysqli_error($con));
                }
            
                mysqli_stmt_bind_param($stmt, 'ss', $RoleID, $userId);
            
                if (mysqli_stmt_execute($stmt)) {
                    $user_id = $_SESSION['User_ID'];
                    $actionType = 'UPDATE';
                    $tableName = 'clinicpersonnel';
                    $recordId = (string)$userId;
                    $actionDetails = "User Role updated: $newRole";
                    
                    audit($user_id, $actionType, $tableName, $recordId, $actionDetails);
                    
                    sendJsonResponse([
                        'success' => true,
                        'message' => 'User Role updated successfully',
                        'newRole' => $newRole
                    ]);
                } else {
                    throw new Exception('Failed to update user role: ' . mysqli_error($con));
                }
                break;
            case 'updateUser_Name': // ------------------------------- Update User Name --------------------------------
                $userId = $_POST['userId'];
                $password = $_POST['password'];
                $firstName = mysqli_real_escape_string($con, $_POST['firstName']);
                $middleName = mysqli_real_escape_string($con, $_POST['middleName']);
                $lastName = mysqli_real_escape_string($con, $_POST['lastName']);
                
                // Verify password first
                $query = "SELECT PasswordHash FROM clinicpersonnel WHERE PersonnelID = ?";
                $stmt = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt, 's', $userId);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $user = mysqli_fetch_assoc($result);
                
                if (!$user || !password_verify($password, $user['PasswordHash'])) {
                    sendJsonResponse([
                        'success' => false,
                        'message' => 'Incorrect password. Please try again.'
                    ]);
                    exit();
                }
                // Build dynamic query based on which fields have values
                $updates = [];
                $params = [];
                $types = '';
                
                if (!empty($firstName)) {
                    $updates[] = 'FirstName = ?';
                    $params[] = $firstName;
                    $types .= 's';
                }
                
                if (!empty($middleName)) {
                    $updates[] = 'MiddleName = ?';
                    $params[] = $middleName;
                    $types .= 's';
                }
                
                if (!empty($lastName)) {
                    $updates[] = 'LastName = ?';
                    $params[] = $lastName;
                    $types .= 's';
                }
                
                if (empty($updates)) {
                    throw new Exception('No fields to update');
                }
                
                $types .= 's'; // for PersonnelID
                $params[] = $userId;
                
                $query = "UPDATE clinicpersonnel SET " . implode(', ', $updates) . " WHERE PersonnelID = ?";
                $stmt = mysqli_prepare($con, $query);
                
                if ($stmt === false) {
                    throw new Exception('Failed to prepare statement: ' . mysqli_error($con));
                }
                
                // Bind parameters dynamically
                $bindParams = array_merge([$stmt, $types], $params);
                $bindResult = call_user_func_array('mysqli_stmt_bind_param', $bindParams);
                
                if ($bindResult === false) {
                    throw new Exception('Failed to bind parameters: ' . mysqli_stmt_error($stmt));
                }
                
                if (mysqli_stmt_execute($stmt)) {
                    sendJsonResponse([
                        'success' => true,
                        'message' => 'User Name updated successfully'
                    ]);
                } else {
                    throw new Exception('Failed to update user name: ' . mysqli_error($con));
                }
                break;
            case 'UpdateEmail': // ------------------------------- My Profile: Update Email --------------------------------
                $userId = $_POST['userId'];
                $password = $_POST['password'];
                $NewEmail = mysqli_real_escape_string($con, $_POST['NewEmail']);
                
                // Verify password first
                $query = "SELECT PasswordHash FROM clinicpersonnel WHERE PersonnelID = ?";
                $stmt = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt, 's', $userId);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $user = mysqli_fetch_assoc($result);
                
                if (!$user || !password_verify($password, $user['PasswordHash'])) {
                    sendJsonResponse([
                        'success' => false,
                        'message' => 'Incorrect password. Please try again.'
                    ]);
                    exit();
                }
                
                // Validate email
                if (!filter_var($NewEmail, FILTER_VALIDATE_EMAIL)) {
                    sendJsonResponse([
                        'success' => false,
                        'message' => 'Invalid email format.'
                    ]);
                }
                
                // Update user email in database
                $query = "UPDATE clinicpersonnel SET EmailAddress = ? WHERE PersonnelID = ?";
                $stmt = mysqli_prepare($con, $query);
                
                mysqli_stmt_bind_param($stmt, 'ss', $NewEmail, $userId);
                
                if (mysqli_stmt_execute($stmt)) {
                    sendJsonResponse([
                        'success' => true,
                        'message' => 'Email updated successfully.'
                    ]);
                } else {
                    throw new Exception('Failed to update email: ' . mysqli_error($con));
                }
                
                break;
            case 'confirmPassword': // ------------------------------- Confirm Password --------------------------------
                $userId = $_POST['userId'];
                $currentPassword = $_POST['currentPassword'];
                
                $query = "SELECT PasswordHash FROM clinicpersonnel WHERE PersonnelID = ?";
                $stmt = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt, 'i', $userId);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $user = mysqli_fetch_assoc($result);
                
                if (!$user || !password_verify($currentPassword, $user['PasswordHash'])) {
                    sendJsonResponse([
                        'success' => false,
                        'message' => 'Incorrect password. Please try again.'
                    ]);
                    exit();
                }
                
                // If we get here, password is correct
                sendJsonResponse([
                    'success' => true,
                    'message' => 'Password verified successfully'
                ]);
                exit();
                break;
            case 'UpdatePassword': // ------------------------------- Update Password --------------------------------
                $userId = $_POST['userId'];
                $newPassword = $_POST['confirmNewPassword'];
                
                // Validate password strength
                if (strlen($newPassword) < 8) {
                    sendJsonResponse([
                        'success' => false,
                        'error' => 'Password must be at least 8 characters long.'
                    ]);
                    exit();
                }
                
                // Hash the new password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                
                date_default_timezone_set('Asia/Manila');
                $PasswordChangeDT = date('Y-m-d H:i:s');
                date_default_timezone_set(date_default_timezone_get());
                
                // Update the password in the database
                $query = "UPDATE clinicpersonnel SET PasswordHash = ?, PasswordChangeDT = ? WHERE PersonnelID = ?";
                $stmt = mysqli_prepare($con, $query);
                
                if (!$stmt) {
                    sendJsonResponse([
                        'success' => false,
                        'error' => 'Database error: ' . mysqli_error($con)
                    ]);
                    exit();
                }
                
                mysqli_stmt_bind_param($stmt, 'ssi', $hashedPassword, $PasswordChangeDT, $userId);
                $result = mysqli_stmt_execute($stmt);
                
                if ($result) {
                    // Log the user out after password change for security
                    session_destroy();
                    
                    sendJsonResponse([
                        'success' => true,
                        'message' => 'Password updated successfully! You will be redirected to login page.',
                        'redirect' => '../Landing Repository/Loginpage.php'
                    ]);
                } else {
                    sendJsonResponse([
                        'success' => false,
                        'error' => 'Failed to update password. Please try again.'
                    ]);
                }
                exit();
                break;
            default:
                sendAlertAndRedirect('Invalid action');
        }
    } catch (Exception $e) {
        error_log('Error in UserFunctions.php (POST): ' . $e->getMessage());
        sendAlertAndRedirect('An error occurred: ' . $e->getMessage());
    }
    
    mysqli_close($con);
    exit();
}
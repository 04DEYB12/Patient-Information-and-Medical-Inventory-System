<?php

// Get user details
$stmt = mysqli_prepare($con, "SELECT * FROM clinicpersonnel as cp JOIN userrole as ur ON cp.RoleID = ur.RoleID WHERE cp.PersonnelID = ?");
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($instructor_row = mysqli_fetch_assoc($result)) {
        $firstname = htmlspecialchars($instructor_row['FirstName']);
        $lastname = htmlspecialchars($instructor_row['LastName']);
    } else {
        $firstname = $lastname = "Unknown";
    }
    $middlename = htmlspecialchars($instructor_row['MiddleName']);
    $PersonnelID = $instructor_row['PersonnelID'];
    $fullname = $firstname . " " . $middlename . " " . $lastname;
    $role = htmlspecialchars($instructor_row['RoleName']);
    $ContactNumber = $instructor_row['ContactNumber'];
    $Email = $instructor_row['EmailAddress'];
    $HireDate = $instructor_row['HireDate'];
    $Status = $instructor_row['Status'];
    
    $Address = $instructor_row['Address'] ? $instructor_row['Address'] : "N/A";
    $Office = $instructor_row['Office'] ? $instructor_row['Office'] : "N/A";
    
    $Password = $instructor_row['PasswordHash'];
    $PasswordChangeDT = $instructor_row['PasswordChangeDT'] ? 
        date('F j, Y, g:i A', strtotime($instructor_row['PasswordChangeDT'])) : 
        'Never changed';
} else {
    die("Database query failed.");
}

// Get student count
$student_count_stmt = mysqli_prepare($con, "SELECT COUNT(*) as total_students FROM student");
if ($student_count_stmt) {
    mysqli_stmt_execute($student_count_stmt);
    $result = mysqli_stmt_get_result($student_count_stmt);
    if ($student_row = mysqli_fetch_assoc($result)) {
        $student_count = $student_row['total_students'];
    } else {
        die("Database query failed.");
    }
}

// Get clinic personnel count
$clinicPersonnel_count_stmt = mysqli_prepare($con, "SELECT COUNT(*) as total_clinicPersonnel FROM clinicpersonnel");
if ($clinicPersonnel_count_stmt) {
    mysqli_stmt_execute($clinicPersonnel_count_stmt);
    $result = mysqli_stmt_get_result($clinicPersonnel_count_stmt);
    if ($clinicPersonnel_row = mysqli_fetch_assoc($result)) {
        $clinicPersonnel_count = $clinicPersonnel_row['total_clinicPersonnel'];
    } else {
        die("Database query failed.");
    }
}        
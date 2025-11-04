<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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

// Include connection file
include '../Landing Repository/Connection.php';

// Check if connection was successful
if (!isset($con) || !$con) {
    sendJsonResponse(['success' => false, 'message' => 'Database connection failed'], 500);
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';

// Handle POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and validate input
    $recipientEmail = $_POST['recipientEmail'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';
    
    // Validate required fields
    if (empty($recipientEmail) || empty($subject) || empty($message)) {
        sendJsonResponse([
            'success' => false, 
            'message' => 'All fields are required',
            'missing_fields' => [
                'recipientEmail' => empty($recipientEmail),
                'subject' => empty($subject),
                'message' => empty($message)
            ]
        ], 400);
    }
    
    // Validate email format
    if (!filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {
        sendJsonResponse([
            'success' => false,
            'message' => 'Invalid email format'
        ], 400);
    }

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
        $mail->addAddress($recipientEmail);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
            
        if (!$mail->send()) {
            sendJsonResponse([
                'success' => false, 
                'message' => 'Failed to send email',
                'error' => $mail->ErrorInfo
            ], 500);
        } else {
            sendJsonResponse([
                'success' => true, 
                'message' => 'Email sent successfully'
            ]);
        }
    
    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        sendJsonResponse([
            'success' => false,
            'message' => 'An error occurred while sending the email',
            'error' => $mail->ErrorInfo
        ], 500);
    }
} else {
    // If not a POST request
    sendJsonResponse([
        'success' => false,
        'message' => 'Invalid request method'
    ], 405);
}
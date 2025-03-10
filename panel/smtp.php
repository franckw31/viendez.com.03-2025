<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

function sendMailViaSMTP($to, $subject, $message, $debug = false) {
    $mail = new PHPMailer(true);

    try {
        // Debug mode
        if ($debug) {
            $mail->SMTPDebug = 2;
        }

        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'wenger.franck@gmail.com';
        
        // Use App Password instead of regular password for Gmail
        // Generate one at: https://myaccount.google.com/apppasswords
        $mail->Password = 'Kookies7*wengerfranck'; // Replace with Gmail App Password
        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Use SMTPS
        $mail->Port = 465; // SMTPS port

        // Recipients
        $mail->setFrom('wenger.franck@gmail.com', 'Viendez');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->CharSet = 'UTF-8';

        $result = $mail->send();
        return ['success' => true, 'message' => 'Email sent successfully'];
    } catch (Exception $e) {
        return ['success' => false, 'message' => "Mail Error: {$mail->ErrorInfo}"];
    }
}

// Test function
function testSMTPConnection() {
    $result = sendMailViaSMTP(
        'your-test-email@example.com',
        'Test Email',
        'This is a test email from Viendez',
        true // Enable debug mode
    );
    
    return $result;
}

// Uncomment to test:
// $test_result = testSMTPConnection();
// print_r($test_result);
?>

<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendVerificationEmail($userEmail, $verificationLink) {
    $mail = new PHPMailer(true);
    
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'your-email@example.com'; // SMTP username
        $mail->Password = 'your-email-password'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('your-email@example.com', 'Your App Name');
        $mail->addAddress($userEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';

        // Load email template
        $emailTemplate = file_get_contents('verification_email.html');
        $emailTemplate = str_replace('{{verification_link}}', $verificationLink, $emailTemplate);

        $mail->Body = $emailTemplate;
        $mail->AltBody = 'Please click the link to verify your email: ' . $verificationLink;

        $mail->send();
        echo 'Please Check Your Email';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    if (!empty($username) && !empty($password) && !empty($email)) {
        // Generate a unique verification link
        $baseUrl = 'http://yourwebsite.com/verify.php';
        $userId = 123; // Replace with actual user ID from your database
        $token = bin2hex(random_bytes(16));
        
        // Store the user information and the token in your database
        // ...

        $verificationLink = $baseUrl . '?id=' . $userId . '&token=' . $token;

        // Send verification email
        sendVerificationEmail($email, $verificationLink);
    } else {
        echo 'Complete Your Registration';
    }
}
?>

<?php
require 'config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendVerificationEmail($email, $token) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;

        // Recipient
        $mail->setFrom(SMTP_USER, 'Your Site');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $verificationUrl = SITE_URL . "/verify.php?token=$token&email=$email";
        $body = file_get_contents('../templates/verification.html');
        $body = str_replace('{{verificationUrl}}', $verificationUrl, $body);
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(16));

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO users (username, password, email, token) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $password, $email, $token);

    if ($stmt->execute() && sendVerificationEmail($email, $token)) {
        echo 'Registration successful! Please check your email to verify your account.';
    } else {
        echo 'Registration failed or email could not be sent.';
    }

    $stmt->close();
    $conn->close();
}
?>

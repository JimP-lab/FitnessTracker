<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php'; // Ensure the correct path
session_start();

// Function to send verification email
function sendResetPassword($email) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sfitnesstracker116@gmail.com';
        $mail->Password = 'whff cbnq qsbs veod';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 465;

        $mail->setFrom('sfitnesstracker116@gmail.com', 'FitnessTracker');
        $mail->addAddress($email);
        $mail->addReplyTo('sfitnesstracker116@gmail.com', 'FitnessTracker');

        $mail->isHTML(true);
        $mail->Subject = 'Reset Your Password';
        $mail->Body = '
        <!DOCTYPE html>
        <html lang="en">
        <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333333;
            text-align: center;
            margin-bottom: 30px;
        }
        p {
            font-size: 16px;
            color: #333333;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            font-size: 16px;
            padding: 15px 30px;
            border-radius: 25px;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666666;
        }
        </style>
        </head>
        <body>
        <div class="container">
        <h1>Password Reset</h1>
         <p>Click the button below to Reset Your Password</p>
        <a href="https://jimp-lab.github.io/FitResetPassword/" class="button" target="_blank">Reset Your Password</a>
        <p>Regards<br>Jim<br> Fitness Tracker Founder</p>
        <p class="footer">© 2024 Fitness Tracker. All rights reserved.</p>
        </div>
        </body>
        </html>';
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['email'])) {
        $email = htmlspecialchars($data['email']);
        if (sendResetPassword($email)) {
            echo "Check your email";
        } else {
            echo "Please try again later.";
        }
    } else {
        echo "Invalid request.";
    }
}
?>

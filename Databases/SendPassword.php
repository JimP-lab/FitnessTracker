<?php
$host = 'localhost';
$dbname =  'your_database';
$username = 'your_username';
$password = 'your_password';

// Create connection
$mysqli = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

// Get the posted email
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];

// Prepare the SQL statement
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

// Check if the email exists in the database
if ($stmt->num_rows > 0) {
    $mail = require(__DIR__ . '/Mailer.php'); 
    $mail->setFrom("sfitnesstracker116@gmail.com");
    $mail->addAddress($email);
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
    <!-- Button to reset password -->
     <a href="https://fitnesstracker.site/ResetPassword.html" class="button" target="_blank">Reset Your Password</a>
    <p>Regards<br>Jim<br> Fitness Tracker Founder</p>
    <p class="footer">© 2024 Fitness Tracker. All rights reserved.</p>
    </div>
    </body>
    </html>';
    try {
        $mail->send();
        echo "Check Your Email.";
    } catch (Exception $e) {
        echo "Message Could Not Be Sent. Mailer error: {$mail->ErrorInfo}";
    }
} else {
    echo "Email Not Found.";
}

$stmt->close();
$mysqli->close();
?>

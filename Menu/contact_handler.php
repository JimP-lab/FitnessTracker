<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON data']);
    exit;
}

// Validate required fields
$name = trim($input['name'] ?? '');
$email = trim($input['email'] ?? '');
$message = trim($input['message'] ?? '');

if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(['error' => 'All fields are required']);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email format']);
    exit;
}

// Get the mailer instance
$mail = require(__DIR__ . '/../phpmailer/Mailer.php');

if (!$mail) {
    http_response_code(500);
    echo json_encode(['error' => 'Email service unavailable']);
    exit;
}

try {
    // Set up the email
    $mail->setFrom('sfitnesstracker116@gmail.com', 'Fitness Tracker Contact Form');
    $mail->addAddress('sfitnesstracker116@gmail.com'); // Your email address
    $mail->addReplyTo($email, $name);
    
    $mail->isHTML(true);
    $mail->Subject = 'New Contact Form Submission from ' . $name;
    
    $mail->Body = "
    <html>
    <head>
        <title>New Contact Form Submission</title>
    </head>
    <body>
        <h2>New Contact Form Submission</h2>
        <p><strong>Name:</strong> " . htmlspecialchars($name) . "</p>
        <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
        <p><strong>Message:</strong></p>
        <div style='background-color: #f5f5f5; padding: 10px; border-left: 4px solid #4CAF50;'>
            " . nl2br(htmlspecialchars($message)) . "
        </div>
        <p><em>This message was sent from the Fitness Tracker contact form.</em></p>
    </body>
    </html>
    ";
    
    $mail->AltBody = "New Contact Form Submission\n\n"
                   . "Name: $name\n"
                   . "Email: $email\n"
                   . "Message:\n$message\n\n"
                   . "This message was sent from the Fitness Tracker contact form.";
    
    $mail->send();
    
    echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
    
} catch (Exception $e) {
    error_log("Contact form error: " . $mail->ErrorInfo);
    http_response_code(500);
    echo json_encode(['error' => 'Failed to send message. Please try again later.']);
}
?>
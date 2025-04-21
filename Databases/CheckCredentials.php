<?php
session_start();  // Start the session

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$password = $data['password'];

// Database credentials
$host = 'localhost';
$dbname = 'fitness tracker';
$dbuser = 'fit';
$dbpass = '';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the query to fetch username, password, and email confirmation status
    $stmt = $pdo->prepare("SELECT username, password, email_confirmed FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Check if user exists
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if email is confirmed
        if ($row['email_confirmed'] != 1) {
            echo json_encode(['status' => 'error', 'message' => 'Please check your email and confirm your address to log in']);
            exit;
        }

        // Verify password
        if (password_verify($password, $row['password'])) {
            // Set the session variable for the user id
            $_SESSION['user_id'] = $row['username'];
            echo json_encode(['status' => 'success', 'message' => 'Login Successful!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Incorrect Password.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User Not Found.']);
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>

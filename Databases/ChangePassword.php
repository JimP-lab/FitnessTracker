<?php
// Database credentials
$host = 'localhost';
$dbname = 'fitness tracker';
$username = 'fit';
$password = '122';

// Get the posted data
$data = json_decode(file_get_contents('php://input'), true);
$user = $data['username'];
$newPassword = $data['password'];

// Validate the inputs (e.g., check length, complexity, etc.)
if (strlen($newPassword) < 10) {
    echo json_encode(['status' => 'error', 'message' => 'Password Must Be At Least 10 Characters Long.']);
    exit;
}

// Create connection
$mysqli = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_errno) {
    echo json_encode(['status' => 'error', 'message' => 'Connection Error: ' . $mysqli->connect_error]);
    exit();
}

// Hash the new password
$hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

// Update the password in the database
$sql = "UPDATE users SET password = ? WHERE username = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $hashedPassword, $user);
if ($stmt->execute()) {
    // Log the user in by starting a session
    session_start();
    $_SESSION['user_id'] = $user; // Assuming 'user_id' is the user's username
    echo json_encode(['status' => 'success', 'message' => 'Password Was Updated']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed To Update']);
}

$stmt->close();
$mysqli->close();
?>

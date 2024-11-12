<?php
$host = 'localhost';
$dbname = 'u879781544_Fit_Tracker';
$username = 'u879781544_Fit';
$password = '8??60eZs';

// Create connection
$mysqli = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

// Get the posted data
$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$email = $data['email'];
$password = password_hash($data['password'], PASSWORD_BCRYPT);

// Insert the user credentials into the database
$sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sss", $username, $email, $password);

if ($stmt->execute()) {
    echo "User Registered Successfully.";
} else {
    echo "Registration Failed: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>
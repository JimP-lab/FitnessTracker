<?php
// Decode incoming JSON data
$data = json_decode(file_get_contents('php://input'), true);
$email = $data['email'];
$username = $data['username'];

// Database credentials
$host = 'localhost';
$dbname = 'fit';
$dbUsername = 'root';
$dbPassword = '';

// Connect to the database
$mysqli = new mysqli($host, $dbUsername, $dbPassword, $dbname);

// Check for a connection error
if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

// Prepare the SQL statement to check if a user with the same email or username exists
$sql = "SELECT * FROM users WHERE email = ? OR username = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $email, $username);
$stmt->execute();
$stmt->store_result();

// Check if any record was found
if ($stmt->num_rows > 0) {
    // If a user with the same email or username exists
    echo "User Exists";
} else {
    // If no user is found
    echo "User Does Not Exist";
}

// Close the statement and database connection
$stmt->close();
$mysqli->close();
?>

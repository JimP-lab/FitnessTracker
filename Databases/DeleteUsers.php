<?php
$servername = 'localhost';
$dbname = 'fit';
$username = 'root';
$password = '';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];

$sql = "DELETE FROM users WHERE username = '$username'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(array('status' => 'success', 'message' => 'Your Account Was Deleted.'));
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Error' . $conn->error)); 
}

$conn->close();
?>

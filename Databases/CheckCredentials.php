<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'];
$password = $data['password'];
// Database credentials
$host = 'localhost';
$dbname = 'your_database';
$dbuser = 'your_username';
$dbpass = 'your_password';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the query
    $stmt = $pdo->prepare("SELECT password FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Check if user exists
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // Verify password
        if (password_verify($password, $row['password'])) {
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

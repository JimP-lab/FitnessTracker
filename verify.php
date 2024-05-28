<?php
// Database configuration
$servername = "your_servername";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user ID and token from the URL
$userId = $_GET['id'];
$token = $_GET['token'];

if (!empty($userId) && !empty($token)) {
    // Prepare and bind
    $stmt = $conn->prepare("SELECT id, is_verified FROM users WHERE id = ? AND token = ?");
    $stmt->bind_param("is", $userId, $token);

    // Execute statement
    $stmt->execute();

    // Bind result variables
    $stmt->bind_result($id, $is_verified);

    // Fetch value
    if ($stmt->fetch()) {
        if (!$is_verified) {
            // Verify the user
            $stmt->close();

            $stmt = $conn->prepare("UPDATE users SET is_verified = 1, token = NULL WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "Email verified successfully!";
            } else {
                echo "Failed to verify email.";
            }
        } else {
            echo "Email is already verified.";
        }
    } else {
        echo "Invalid verification link.";
    }

    // Close statement
    $stmt->close();
} else {
    echo "Invalid request.";
}

// Close connection
$conn->close();
?>

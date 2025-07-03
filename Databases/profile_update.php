<?php
session_start();
header('Content-Type: application/json');

// Database credentials
$host = 'localhost';
$dbname = 'u879781544_Fit_Tracker';
$dbuser = 'u879781544_Fit';
$dbpass = '8??60eZs';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ensure the user is logged in
    if (!isset($_SESSION['username'])) {
        echo json_encode(['status' => 'error', 'message' => 'User info washt saved']);
        exit;
    }

    $logged_in_username = $_SESSION['username']; // Securely get the logged-in user

    // Fetch user information
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $stmt = $pdo->prepare("SELECT id, username, profile_image FROM user_information WHERE username = :username");
        $stmt->bindParam(':username', $logged_in_username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            if ($user['profile_image']) {
                $user['profile_image'] = base64_encode($user['profile_image']); // Convert image to Base64 for UI
            }
            echo json_encode(['status' => 'success', 'user' => $user]);
        } else {
            echo json_encode(['status' => 'empty', 'message' => 'No profile information found']);
        }
        exit;
    }

    // Handle profile update
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_username = $_POST['username'];
        $new_password = $_POST['password'];
        $profile_image_data = null;

        // Handle profile image upload
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['size'] > 0) {
            $profile_image_data = file_get_contents($_FILES['profile_image']['tmp_name']);
        }

        // Hash the new password only if it's provided
        $hashed_password = !empty($new_password) ? password_hash($new_password, PASSWORD_DEFAULT) : null;

        // Update existing user information
        $query = "UPDATE user_information SET username = :username";
        if ($hashed_password) {
            $query .= ", password = :password";
        }
        if ($profile_image_data) {
            $query .= ", profile_image = :profile_image";
        }
        $query .= " WHERE username = :logged_in_username";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':username', $new_username);
        if ($hashed_password) {
            $stmt->bindParam(':password', $hashed_password);
        }
        $stmt->bindParam(':logged_in_username', $logged_in_username);
        if ($profile_image_data) {
            $stmt->bindParam(':profile_image', $profile_image_data, PDO::PARAM_LOB);
        }
        $stmt->execute();

        // Update session username if changed
        $_SESSION['username'] = $new_username;

        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
        exit;
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>

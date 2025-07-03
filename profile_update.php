<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

    // Removed session check. Username will now be provided explicitly in the request.

    // -------------------------------------------------------------------------
    // 1) FETCH USER INFORMATION (GET REQUEST)
    // -------------------------------------------------------------------------
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $requestedId = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $requestedUsername = isset($_GET['username']) ? trim($_GET['username']) : '';

        if (!$requestedId && $requestedUsername === '') {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'id or username is required']);
            exit;
        }

        if ($requestedId) {
            $stmt = $pdo->prepare(
                "SELECT id, username, profile_image
                 FROM user_information
                 WHERE id = :id"
            );
            $stmt->bindParam(':id', $requestedId, PDO::PARAM_INT);
        } else {
            $stmt = $pdo->prepare(
                "SELECT id, username, profile_image
                 FROM user_information
                 WHERE username = :username"
            );
            $stmt->bindParam(':username', $requestedUsername);
        }
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        /* NEW: Return Base64 encoded image so it can be rendered on <img> src */
        if ($user) {
            if (!empty($user['profile_image'])) {
                $user['profile_image'] = base64_encode($user['profile_image']);
            }
            echo json_encode(['status' => 'success', 'user' => $user]);
        } else {
            echo json_encode(['status' => 'empty', 'message' => 'No profile information found']);
        }
        exit;
    }

    // -------------------------------------------------------------------------
    // 2) UPDATE USER INFORMATION (POST REQUEST)
    // -------------------------------------------------------------------------
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        /*
        We keep the original logic but add some basic validation and make sure
        the incoming fields are fetched safely even if they are missing.
        */
        $user_id            = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $new_username       = isset($_POST['username']) ? trim($_POST['username']) : '';
        $new_password       = isset($_POST['password']) ? trim($_POST['password']) : '';
        $profile_image_data = null;

        // Handle profile image upload (if any)
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['size'] > 0) {
            $profile_image_data = file_get_contents($_FILES['profile_image']['tmp_name']);
        }

        // Hash the new password only if it's provided
        $hashed_password = !empty($new_password) ? password_hash($new_password, PASSWORD_DEFAULT) : null;

        if (!$user_id && $new_username === '') {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'id or username is required']);
            exit;
        }

        $setParts = [];
        if ($new_username !== '') {
            $setParts[] = 'username = :username';
        }
        if ($hashed_password) {
            $setParts[] = 'password = :password';
        }
        if ($profile_image_data !== null) {
            $setParts[] = 'profile_image = :profile_image';
        }

        if (empty($setParts)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'No fields to update']);
            exit;
        }

        $query = 'UPDATE user_information SET ' . implode(', ', $setParts);
        if ($user_id) {
            $query .= ' WHERE id = :id';
        } else {
            $query .= ' WHERE username = :username_where';
        }

        $stmt = $pdo->prepare($query);

        if ($new_username !== '') {
            $stmt->bindParam(':username', $new_username);
        }
        if ($hashed_password) {
            $stmt->bindParam(':password', $hashed_password);
        }
        if ($profile_image_data !== null) {
            $stmt->bindParam(':profile_image', $profile_image_data, PDO::PARAM_LOB);
        }
        if ($user_id) {
            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        } else {
            $stmt->bindParam(':username_where', $new_username);
        }

        $stmt->execute();

        // Retrieve the updated info to send back to UI
        if ($user_id) {
            $refreshStmt = $pdo->prepare('SELECT id, username, profile_image FROM user_information WHERE id = :id');
            $refreshStmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        } else {
            $refreshStmt = $pdo->prepare('SELECT id, username, profile_image FROM user_information WHERE username = :username');
            $refreshStmt->bindParam(':username', $new_username);
        }
        $refreshStmt->execute();
        $updatedUser = $refreshStmt->fetch(PDO::FETCH_ASSOC);

        if ($updatedUser && !empty($updatedUser['profile_image'])) {
            $updatedUser['profile_image'] = base64_encode($updatedUser['profile_image']);
        }

        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully', 'user' => $updatedUser]);
        exit;
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
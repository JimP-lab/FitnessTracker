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

    // -------------------------------------------------------------------------
    // 1) FETCH USER INFORMATION (GET REQUEST)
    // -------------------------------------------------------------------------
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        /*
        ---------------------------------------------------------------------
        Original behaviour: return id, username, profile_image for currently
        logged-in user.
        ---------------------------------------------------------------------*/
        $stmt = $pdo->prepare("SELECT id, username, profile_image FROM user_information WHERE username = :username");
        $stmt->bindParam(':username', $logged_in_username);
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
        $new_username       = isset($_POST['username']) ? trim($_POST['username']) : '';
        $new_password       = isset($_POST['password']) ? trim($_POST['password']) : '';
        $profile_image_data = null;

        // Handle profile image upload (if any)
        if (isset($_FILES['profile_image']) && $_FILES['profile_image']['size'] > 0) {
            $profile_image_data = file_get_contents($_FILES['profile_image']['tmp_name']);
        }

        // Hash the new password only if it's provided
        $hashed_password = !empty($new_password) ? password_hash($new_password, PASSWORD_DEFAULT) : null;

        /*
        ---------------------------------------------------------------------
        ORIGINAL CODE START (kept, but we build the query dynamically so we
        don't lose existing functionality while making it more flexible).
        ---------------------------------------------------------------------*/
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
        /*
        ---------------------------------------------------------------------
        ORIGINAL CODE END
        ---------------------------------------------------------------------*/

        // Update session username if changed so subsequent GET yields new data
        if (!empty($new_username)) {
            $_SESSION['username'] = $new_username;
        }

        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
        exit;
    }

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
?>
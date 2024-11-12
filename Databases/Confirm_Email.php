<?php
// Ensure token is set and not empty
if (isset($_GET['token']) && !empty($_GET['token'])) {
    $token = $_GET['token'];

    // Database connection
    $host = 'localhost';
    $dbname = 'your_database';
    $username = 'your_username';
    $password = 'your_password';

    // Create connection
    $mysqli = new mysqli($host, $username, $password, $dbname);

    // Check connection
    if ($mysqli->connect_errno) {
        die("Connection error: " . $mysqli->connect_error);
    }

    // Prepare SQL statement to find the user by token
    $sql = "SELECT * FROM users WHERE email_confirm_token = ?";
    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a user is found with the token
        if ($result->num_rows > 0) {
            // Fetch the user details
            $user = $result->fetch_assoc();

            // Check if the email is already confirmed
            if ($user['email_confirmed'] == 1) {
                echo "This email address has already been confirmed.";
            } else {
                // Confirm the email and clear the token
                $sql_update = "UPDATE users SET email_confirm_token = NULL, email_confirmed = 1 WHERE email_confirm_token = ?";
                $stmt_update = $mysqli->prepare($sql_update);

                if ($stmt_update) {
                    $stmt_update->bind_param("s", $token);
                    $stmt_update->execute();

                    // Redirect to the new home page with a confirmation message
                    header("Location:https://fitnesstracker.site/index.html?registered=success");
                    exit();
                } else {
                    echo "Error preparing update statement: " . $mysqli->error;
                }
            }
        } else {
            echo "This email address has already been confirmed.";
        }
    } else {
        echo "Error preparing statement: " . $mysqli->error;
    }

    // Close database connections
    if ($stmt) $stmt->close();
    if (isset($stmt_update)) $stmt_update->close();
    $mysqli->close();
} else {
    echo "No Token Provided.";
}
?>

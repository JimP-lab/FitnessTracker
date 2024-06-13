<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $email = htmlspecialchars($_POST['email']);

    // Save the user credentials to the session
    $_SESSION['users'][] = [
        'username' => $username,
        'password' => $password,
        'email' => $email
    ];

    // End the session and send success response
    session_write_close();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Users</title>
</head>
<body>
    <h1>Registered Users</h1>
    <?php
    if (!empty($_SESSION['users'])) {
        echo "<ul>";
        foreach ($_SESSION['users'] as $user) {
            echo "<li>Username: " . $user['username'] . ", Password: " . $user['password'] . ", Email: " . $user['email'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "No users registered yet.";
    }
    ?>
</body>
</html>

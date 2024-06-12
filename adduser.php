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

    // Redirect to avoid resubmission
    header('Location: adduser.php');
    exit;
} else {
    // Display the credentials of registered users
    echo "<h1>Registered Users</h1>";
    if (!empty($_SESSION['users'])) {
        echo "<ul>";
        foreach ($_SESSION['users'] as $user) {
            echo "<li>Username: " . $user['username'] . ", Password: " . $user['password'] . ", Email: " . $user['email'] . "</li>";
        }
        echo "</ul>";
    } else {
        echo "No users registered yet.";
    }
}
?>

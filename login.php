<?php
session_start();
include 'db.php';
include 'auth.php';

// If form is submitted, check username and password
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['pass']);

    if (empty($username) || empty($password)) {
        $error = "Username and password are required!";
    } else {
        // Secure login function that uses password_verify()
        $user = login($username, $password, $pdo); // Now login returns the user data

        if ($user) {
            session_regenerate_id(true);  // Prevent session fixation
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            echo "Login successful";
            header('Location: feedback_form.php');
            exit();
        } else {
            $error = "Invalid username or password!";
            echo "Login failed";
            header('Location: index.php');
        }
    }
}
?>

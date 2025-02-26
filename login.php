<?php
session_start();
include 'db.php';
include 'auth.php';

// Force HTTPS
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

// If form is submitted, check username and password
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Username and password are required!";
    } else {
        // Secure login function that uses password_verify()
        if ($user = login($username, $password)) {
            session_regenerate_id(true);  // Prevent session fixation
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            header('Location: feedback_form.php');
            exit();
        } else {
            $error = "Invalid username or password!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST">
        <label for="username"><strong>Username:</strong></label>
        <input type="text" id="username" name="username" required><br>
        <label for="password"><strong>Password:</strong></label>
        <input type="password" id="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlentities($error); ?></p>
    <?php endif; ?>
</body>
</html>
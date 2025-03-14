<?php
// If the user is already logged in, redirect them to feedback form
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: feedback_form.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Signup</title>
</head>
<body>

    <!-- Login Form -->
    <h3>Login</h3>
    <form action="login.php" method="post">
        <p><input type="text" name="username" placeholder="Username"></p>
        <p><input type="password" name="pass" placeholder="Password"></p>
        <p><button type="submit">Login</button></p>
    </form>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <hr>

    <!-- Signup Form -->
    <h3>Sign Up</h3>
    <form action="signup.php" method="post">
        <p><input type="text" name="username" placeholder="Username"></p>
        <p><input type="password" name="pass" placeholder="Password"></p>
        <p><input type="text" name="first_name" placeholder="First Name"></p>
        <p><input type="text" name="last_name" placeholder="Last Name"></p>
        <p><button type="submit">Sign Up</button></p>
    </form>
    
</body>
</html>

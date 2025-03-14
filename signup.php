<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = trim($_POST['pass']);
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);

    // Check if username already exists
    $stmt = $pdo->prepare('SELECT id FROM students WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        echo "Username already taken!";
        header("Refresh: 3; url=index.php"); // Wait for 3 seconds and redirect
        exit();
    } else {
        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into the database
        $stmt = $pdo->prepare('INSERT INTO students (username, pass, first_name, last_name) VALUES (:username, :pass, :first_name, :last_name)');
        $stmt->execute([
            'username' => $username,
            'pass' => $hashedPassword,
            'first_name' => $firstName,
            'last_name' => $lastName
        ]);
        
        echo "Signup successful! You can now login.";
        header("Refresh: 3; url=index.php"); // Wait for 3 seconds and redirect
        exit();
    }
}
?>
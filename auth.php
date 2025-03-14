<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check credentials with data in database
function login($username, $password, $pdo) {
    try {
        // Secure query (fetch hashed password only)
        $sql = "SELECT id, username, pass FROM students WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['username' => $username]); // Bind parameter

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify password securely
        if ($user && password_verify($password, $user['pass'])) {
            session_regenerate_id(true); // Prevent session fixation
            return $user; // Return the user data
        }
        return false;
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

// Check whether user is logged in
function check_login() {
    return isset($_SESSION['user_id']);
}

// Fetch the current user (ensure proper security)
function current_user($pdo) {
    if (check_login()) {
        $sql = "SELECT students.*, exams.id AS exam_id, exams.exam_name AS exam_name, exams.exam_date
                FROM students 
                LEFT JOIN exam_students ON students.id = exam_students.student_id
                LEFT JOIN exams ON exams.id = exam_students.exam_id
                WHERE students.id = ?
                ORDER BY exams.exam_date DESC 
                LIMIT 1";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $_SESSION['user_id'], PDO::PARAM_INT); 
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}
?>

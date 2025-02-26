<?php 
session_start();

// Check credentials with data in database
function login($username, $password) {
    global $connect;

    // Secure query (fetch hashed password only)
    $sql = "SELECT id, Password FROM student WHERE Username=?";
    $stmt = $connect->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify password securely
    if ($user && password_verify($password, $user['Password'])) {
        session_regenerate_id(true); // Prevent session fixation
        $_SESSION['user_id'] = $user['id'];
        return true;
    } 
    return false;
}

// Check whether user is logged in
function check_login() {
    return isset($_SESSION['user_id']);
}

// Fetch the current user (ensure proper security)
function current_user() {
    global $connect;
    if (check_login()) {
        $sql = "SELECT student.*, exam.id AS exam_id, exam.name AS exam_name, exam.exam_date
                FROM student 
                LEFT JOIN exam_student ON student.id = exam_student.student_id
                LEFT JOIN exam ON exam.id = exam_student.exam_id
                WHERE student.id=?
                ORDER BY exam.exam_date DESC 
                LIMIT 1";
        $stmt = $connect->prepare($sql);
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->num_rows ? $result->fetch_assoc() : null;
    }
    return null;
}
?>
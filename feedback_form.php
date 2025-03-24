<?php

session_start();
include 'db.php';
include 'auth.php';

// Redirect if not logged in
if (!check_login()) {
    header('Location: index.php');
    exit();
}

$user = current_user($pdo);

// Generate CSRF token to prevent Cross-Site Request Forgery
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF Protection
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }

    // Sanitize inputs
    $exam_id = filter_var($_POST['exam_id'], FILTER_VALIDATE_INT);
    $stud_id = $user['id'];
    $general_feedback = $_POST['general_feedback'];
    $quest_no = filter_var($_POST['quest_no'], FILTER_VALIDATE_INT);
    $narrative = $_POST['narrative']; 

    // Initialize feedback options to avoid SQL error in case of null values
    $not_covered = 0;
    $unclear = 0;
    $more_than_2 = 0;
    $no_correct = 0;

    // Validate feedback options
    $valid_feedback = ['not_covered', 'unclear', 'more_than_2', 'no_correct'];
    if (in_array($general_feedback, $valid_feedback)) {
        switch ($general_feedback) {
            case 'not_covered': 
                $not_covered = 1; 
                break;
            case 'unclear': 
                $unclear = 1; 
                break;
            case 'more_than_2': 
                $more_than_2 = 1; 
                break;
            case 'no_correct': 
                $no_correct = 1; 
                break;
        }

        // Prepare the SQL query
        $sql = "INSERT INTO feedback (exam_id, stud_id, quest_no, not_covered, unclear, more_than_2, no_correct, narrative) 
                VALUES (:exam_id, :stud_id, :quest_no, :not_covered, :unclear, :more_than_2, :no_correct, :narrative)";
        $stmt = $pdo->prepare($sql);

        // Check if prepare() failed
        if (!$stmt) {
            die("SQL error: " . $pdo->errorInfo()[2]);
        }

        $stmt->bindValue(":exam_id", $exam_id, PDO::PARAM_INT);
        $stmt->bindValue(":stud_id", $stud_id, PDO::PARAM_INT);
        $stmt->bindValue(":quest_no", $quest_no, PDO::PARAM_INT);
        $stmt->bindValue(":not_covered", $not_covered, PDO::PARAM_INT);
        $stmt->bindValue(":unclear", $unclear, PDO::PARAM_INT);
        $stmt->bindValue(":more_than_2", $more_than_2, PDO::PARAM_INT);
        $stmt->bindValue(":no_correct", $no_correct, PDO::PARAM_INT);
        $stmt->bindValue(":narrative", $narrative, PDO::PARAM_STR);

        $stmt->execute();

        $success = "Feedback submitted successfully!";
    } 
    else {
        $error = "Invalid feedback choice.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Feedback Form</title>
</head>
<body>
    <h2>Feedback Form</h2>
    <form method="POST">
        <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
        <p><strong>Student Name:</strong> <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
        <p><strong>Exam Name:</strong> <?php echo htmlspecialchars($user['exam_name']); ?></p>
        <p><strong>Exam Date:</strong> <?php echo htmlspecialchars($user['exam_date']); ?></p>
        <input type="hidden" name="exam_id" value="<?php echo $user['exam_id']; ?>"><br>
        
        <label for="quest_no">Question Number:</label>
        <select id="quest_no" name="quest_no" required>
            <?php for ($i = 1; $i <= 10; $i++): ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
            <?php endfor; ?>
        </select><br><br>

        <strong>General feedback:</strong><br><br>
        <input type="radio" name="general_feedback" value="not_covered" id="not_covered"> <label for="not_covered">Not Covered</label><br>
        <input type="radio" name="general_feedback" value="unclear" id="unclear"> <label for="unclear">Unclear</label><br>
        <input type="radio" name="general_feedback" value="more_than_2" id="more_than_2"> <label for="more_than_2">More than two Correct Answers</label><br>
        <input type="radio" name="general_feedback" value="no_correct" id="no_correct"> <label for="no_correct">No Correct Answer</label><br>
        <br><br>

        <label for="narrative"><strong>Specific feedback:</strong></label><br><br>
        <textarea id="narrative" name="narrative" rows="10" cols="150" required></textarea><br>
        <input type="submit" value="Submit">
    
    </form>
    <?php if (isset($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <!-- Logout Form -->
    <?php if (isset($_SESSION["user_id"])) { ?>
        <h3>Logout</h3>
        <form action="logout.php" method="post">
        <button>Logout</button>
        </form>
    <?php } ?>
    
</body>
</html>

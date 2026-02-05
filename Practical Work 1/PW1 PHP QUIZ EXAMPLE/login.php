<?php
session_start(); // Start the session

if (isset($_POST['username'])) {
    $_SESSION['username'] = htmlspecialchars($_POST['username']); // Sanitize user input
    $_SESSION['score'] = 0;
    $_SESSION['question_index'] = 0;
    $_SESSION['mistakes'] = []; // Array to track mistakes
    header("Location: quiz.php"); // Redirect to the quiz page
    exit();
}

// Rest of the HTML code below...
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Login</title>
</head>
<body>
    <h2>Login to Start Quiz</h2>
    <form method="post" action="login.php">
        <label>Username: <input type="text" name="username" required></label><br>
        <input type="submit" value="Start Quiz">
    </form>
</body>
</html>

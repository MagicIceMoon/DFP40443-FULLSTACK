<?php
session_start();
// Check if user is logged in, redirect if not
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$score = $_SESSION['score'];
$mistakes = $_SESSION['mistakes'];
$username = $_SESSION['username'];

// Handle restart: destroy session and redirect
if (isset($_POST['restart'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}
// Rest of the HTML code below...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Quiz Results</title>
    <style>
        table, th, td { border: 1px solid black; border-collapse: collapse; padding: 5px; }
    </style>
</head>
<body>
    <h2>Quiz Results for <?php echo htmlspecialchars($username); ?></h2>
    <p>Your final score is: **<?php echo $score; ?>**</p>

    <?php if (!empty($mistakes)): ?>
        <h3>Review Mistakes:</h3>
        <table>
            <tr><th>Question</th><th>Your Answer</th><th>Correct Answer</th></tr>
            <?php foreach ($mistakes as $mistake): ?>
                <tr>
                    <td><?php echo htmlspecialchars($mistake['question']); ?></td>
                    <td><?php echo htmlspecialchars($mistake['submitted']); ?></td>
                    <td><?php echo htmlspecialchars($mistake['correct']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <form method="post" action="result.php">
        <input type="submit" name="restart" value="Restart Quiz">
    </form>
</body>
</html>

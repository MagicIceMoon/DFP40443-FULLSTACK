<?php
$questions = [
    'session_start()',
    'begin_session()',
    'start_session'
];
?>

<html>
<head>
</head>
<body>
    <form action="q3.php" method="POST">
        <h2>Question 2:</h2>
        <p>Which function starts a session?<p>
            <?php foreach ($questions as $answer => $ans): ?>
                <label>
                    <input type="radio" required>
                    <?php echo htmlspecialchars($ans); ?>
                </label><br>
                <?php endforeach; ?>
                <br>
                <input type="submit" value="Next Question">
        </form>
</body>
</html>
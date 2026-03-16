<?php
$questions = [
    'const()',
    'define()',
    'var()'
];
?>

<html>
<head>
</head>
<body>
    <form action="result.php" method="POST">
        <h2>Question 3:</h2>
        <p>How do you define a constant?<p>
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
<?php
session_start();
// Check if user is logged in, redirect if not
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Multidimensional array of questions
$questions = [
    [
        'question' => 'What does PHP stand for?',
        'options' => ['Personal Home Page', 'Hypertext Preprocessor', 'Private Home Page'],
        'answer' => 'Hypertext Preprocessor'
    ],
    [
        'question' => 'Which function is used to start a session?',
        'options' => ['start_session()', 'session_begin()', 'session_start()'],
        'answer' => 'session_start()'
    ],
    [
        'question' => 'How do you define a constant?',
        'options' => ['const()', 'define()', 'var()'],
        'answer' => 'define()'
    ]
    // Add more questions here
];

$totalQuestions = count($questions);

// PHP logic for processing answers and managing flow
if (isset($_POST['answer'])) {
    $currentQuestionIndex = $_SESSION['question_index'] - 1;
    $correctAnswer = $questions[$currentQuestionIndex]['answer'];
    $submittedAnswer = $_POST['answer'];

    if ($submittedAnswer == $correctAnswer) {
        $_SESSION['score']++;
    } else {
        $_SESSION['mistakes'][] = [
            'question' => $questions[$currentQuestionIndex]['question'],
            'submitted' => $submittedAnswer,
            'correct' => $correctAnswer
        ];
    }
}

// Move to the next question index
if ($_SESSION['question_index'] < $totalQuestions) {
    $currentQuestion = $questions[$_SESSION['question_index']];
    $_SESSION['question_index']++;
} else {
    header("Location: result.php"); // Redirect to result page when finished
    exit();
}
// Rest of the HTML code below...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Quiz Page</title>
</head>
<body>
    <h2>Question <?php echo $_SESSION['question_index']; ?> of <?php echo $totalQuestions; ?></h2>
    <form method="post" action="quiz.php">
        <p><strong><?php echo htmlspecialchars($currentQuestion['question']); ?></strong></p>
        <?php foreach ($currentQuestion['options'] as $option): ?>
            <label>
                <input type="radio" name="answer" value="<?php echo htmlspecialchars($option); ?>" required>
                <?php echo htmlspecialchars($option); ?>
            </label><br>
        <?php endforeach; ?>
        <input type="submit" value="Next Question">
    </form>
</body>
</html>

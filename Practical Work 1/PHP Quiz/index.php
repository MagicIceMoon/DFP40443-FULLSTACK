<?php
$config = include('config/app_config.php');
session_start(); 

if (isset($_POST['username'])) {
    $_SESSION['username'] = htmlspecialchars($_POST['username']); // Sanitize user input
    $_SESSION['score'] = 0;
    $_SESSION['question_index'] = 0;
    $_SESSION['mistakes'] = []; // Array to track mistakes
    header("Location: question/q1.php"); // Redirect to the quiz page
    exit();
}

?>
<html>
    <head>
        <title><?php echo $config['site_name']?></title>
    </head>
    <body>
        <h1>PHP Knowledge Questions</h1>
        <p>Answer ALL questions.<p>
        <form action="question/q1.php" method="POST">
            Enter Name: <input type="text" name="username">
            <input type="submit"value="Start Quiz">
        </form>
    </body>
</html>
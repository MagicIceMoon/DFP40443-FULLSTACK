<?php
session_start();

if(!isset($_SESSION['loggedin'])) {
    header("location:login.php");
    exit();
}
?>
<html>
    <head>
</head>
<body>
    <h1>This is a dashboard <?php echo $_SESSION['username']; ?></h1>
    <a href="about.php">About me</a>
    <a href="index.php">Index</a>
    <a href="logout.php">Log Out</a>
</body>
</html>
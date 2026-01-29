<?php
session_start();
echo $_SESSION['username'];
?>

<html>
    <head>
</head>
<body>
    <h1> Hello <?php echo $_SESSION['username']; ?></h1>
</body>
</html>
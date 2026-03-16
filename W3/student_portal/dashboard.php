<?php
require 'includes/functions.php';
include 'includes/header.php';

if (isset($_POST['user']) && isset($_POST['pass'])) {

$user = $_POST['user'];
$pass = $_POST['pass'];

if(isValidUser($user, $pass)) {
    echo "<h1>Welcome to your Dashboard</h1>";
    echo "<p>Succesfulyy authenticated as: " .htmlspecialchars($user)."</p>";
} else {
    echo "<h2 style='color:red;'>Access Denied!</h2>";
    echo "<a href='index.php'>Try Again</a>";
}
} else {
    echo "<h2>Please login first</h2>";
}

include 'includes/footer.php';
?>
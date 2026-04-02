<?php
<<<<<<< HEAD
$conn = mysqli_connect("localhost", "root", "", "sms");
=======
$conn = mysqli_connect("127.0.0.1:3307", "root", "", "sms");
>>>>>>> eef7a19 (Mini Project)

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
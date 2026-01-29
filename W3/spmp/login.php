<?php
session_start();

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $namapengguna = $_POST['user'];
    $katalaluan = $_POST['pass'];

if($namapengguna == "afiq" && $katalaluan == "root") {
    $_SESSION['username'] = $namapengguna;
    $_SESSION['loggedin'] = true;
    header("location:dashboard.php");
    exit();
}
}
?>
<form method="POST" action="">
    User<input type="text" name="user">
    Password<input type="password" name="pass">
    <input type="submit" value="login">
</form>
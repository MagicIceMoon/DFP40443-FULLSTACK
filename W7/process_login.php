<?php
require_once "config/app_config.php";

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users JOIN roles ON roles.id = users.role_id WHERE username=?"

$stnt = mysqli_prepare($conn,$sql);
mysqli_stnt_bind_param($stnt,"s",$username);
mysqli_stnt_execute($stnt);

?>
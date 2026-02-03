<?php
$config = include('config/app_config.php');
require_once('include/alumni_logic.php');

$isLoggedIn=false;
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $user = $_POsT['username'];
        $pass = $$_POST['password'];

        if($user !== $config['admin_user'] || $pass !== $config['admin_pass']) {

        } $isLoggedIn = true;
    } catch (Exception ) {
}
}
?>
<html>
    <head>
        <title></title>
    </head>
    <body>
        <form>
            <input type="text" name="username">
            <input type="password" name="password">
            <input type="submit"value="Login">
        </form>
    </body>
</html>
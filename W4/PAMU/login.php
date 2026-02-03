<?php
$config = include('config/app_config.php');
?>
<html>
    <head>
        <title><?php echo $config['site_name']?></title>
    </head>
    <body>
        <form action="index.php" method="POST">
            User<input type="text" name="username">
            Password<input type="password" name="password">
            <input type="submit"value="Login">
        </form>
    </body>
</html>
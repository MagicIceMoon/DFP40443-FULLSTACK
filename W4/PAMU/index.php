<?php
$config = include('config/app_config.php');
require_once('include/alumni_logic.php');

$isLoggedIn=false;
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $user = $_POST['username'];
        $pass = $_POST['password'];

        if($user !== $config['admin_user'] || $pass !== $config['admin_password']) {

        } $isLoggedIn = true;
    } catch (Exception $e) {
}
}
?>
<html>
<head>
    <title><?php echo $config['site_name']; ?></title>
</head>
<body style="background-color: #FDDC5C; <?php echo $config ['theme_color']; ?>">
    <header>
        <nav>
            <ul style="display; flex; list-style=type: style name">
                <?php echo generatedMenu($pages);?>
            </ul>
        </nav>
    </header>
    Welcome <?php echo $_POST['username']; ?>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam accumsan id ex in finibus. Donec viverra lorem at libero venenatis vestibulum. Etiam purus quam, feugiat eget dolor sit amet, pretium accumsan dui. Donec mi velit, tincidunt a nulla ut, congue tristique odio. Aenean ac augue turpis. Quisque venenatis eros vitae luctus imperdiet. In placerat, ligula quis finibus vehicula, risus nulla dapibus nisi, sit amet accumsan odio quam eu orci. Phasellus facilisis, magna sed interdum tempor, tortor odio commodo urna, at scelerisque tellus enim ut ante. Vestibulum non iaculis justo, sed pharetra purus. Morbi tincidunt nibh sed nisi vestibulum elementum. Curabitur at libero nec velit dapibus venenatis. Praesent tempor ligula sed felis condimentum viverra. Pellentesque in nunc in nisl venenatis tincidunt vel sit amet libero. Quisque scelerisque eleifend tristique. Phasellus elementum maximus lectus at efficitur.</p>
    <footer></footer>
</body>
</html>
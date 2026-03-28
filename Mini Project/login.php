<?php
session_start();
require_once("config/app_config.php");

if (isset($_SESSION['id'])) {
    header("Location: dashboard.php");
    exit();
}

$mesej = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT users.id, users.username, users.password, roles.roles_name 
            FROM users 
            JOIN roles ON users.roles_id = roles.id 
            WHERE users.username = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_bind_result($stmt, $id, $db_username, $db_password, $role);

    if (mysqli_stmt_fetch($stmt)) {
        if ($password == $db_password) {
            $_SESSION['id']       = $id;
            $_SESSION['username'] = $db_username;
            $_SESSION['role']     = $role;
            header("Location: dashboard.php");
            exit();
        } else {
            $mesej = "Invalid username or password!";
        }
    } else {
        $mesej = "Invalid username or password!";
    }

    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: #2e67bb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 380px;
            overflow: hidden;
        }
        .login-header {
            background: #377adf;
            color: white;
            padding: 25px;
            text-align: center;
        }
        .login-header i {
            font-size: 40px;
            margin-bottom: 8px;
            display: block;
        }
        .login-header h5 {
            margin: 0;
            font-weight: bold;
        }
        .login-header small {
            opacity: 0.8;
            font-size: 12px;
        }
        .login-body {
            padding: 25px;
        }
        .btn-login {
            background: #377adf;
            color: white;
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: bold;
        }
        .btn-login:hover {
            background: #122d54;
            color: white;
        }
        .hint-box {
            border-radius: 6px;
            padding: 10px 15px;
            font-size: 12px;
            color: #555;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <i class="bi bi-mortarboard-fill"></i>
        <h5>Student Management System</h5>
        <small>DFP40443 Full Stack Web Development</small>
    </div>
    <div class="login-body">

        <?php if ($mesej): ?>
            <div class="alert alert-danger py-2" style="font-size:13px;">
                <i class="bi bi-exclamation-circle me-1"></i><?php echo $mesej; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label fw-bold" style="font-size:13px;">Username</label>
                <div class="input-group">
                    <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold" style="font-size:13px;">Password</label>
                <div class="input-group">
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="hint-box">
            <i>username: admin | password: admin123</i>
        </div>

    </div>
</div>
</body>
</html>

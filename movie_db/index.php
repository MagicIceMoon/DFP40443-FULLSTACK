<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'config/db.php';

    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "All fields are required.";
    } else {
        $stmt = mysqli_prepare($conn, "SELECT id, username, password FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineVault &mdash; Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --red: #e50914;
            --dark: #0a0a0a;
            --card: #141414;
            --border: #2a2a2a;
        }
        body {
            background-color: var(--dark);
            color: #fff;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: radial-gradient(ellipse at 20% 50%, rgba(229,9,20,0.07) 0%, transparent 60%);
        }
        .brand {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.8rem;
            color: var(--red);
            letter-spacing: 3px;
        }
        .login-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
        }
        .form-control {
            background: #1e1e1e;
            border: 1px solid var(--border);
            color: #fff;
            border-radius: 8px;
        }
        .form-control:focus {
            background: #1e1e1e;
            border-color: var(--red);
            color: #fff;
            box-shadow: 0 0 0 2px rgba(229,9,20,0.2);
        }
        .btn-primary {
            background: var(--red);
            border: none;
            border-radius: 8px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        .btn-primary:hover { background: #c1070f; }
        label { color: #aaa; font-size: 0.85rem; }
        a { color: var(--red); }
        a:hover { color: #ff2b37; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="text-center mb-4">
        <div class="brand">CineVault</div>
        <p class="text-secondary mt-1" style="font-size:0.85rem;">Sign in to manage your movie database</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger py-2" style="font-size:0.85rem;"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control"
                   value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                   placeholder="Enter username">
        </div>
        <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter password">
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2">Sign In</button>
    </form>

    <p class="text-center mt-4 mb-0" style="font-size:0.85rem; color:#666;">
        No account? <a href="register.php">Register here</a>
    </p>
</div>
</body>
</html>

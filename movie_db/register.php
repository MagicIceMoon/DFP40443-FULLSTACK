<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'config/db.php';

    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if (empty($username) || empty($password) || empty($confirm)) {
        $error = "All fields are required.";
    } elseif (strlen($username) < 3) {
        $error = "Username must be at least 3 characters.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if username exists
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $error = "Username already taken.";
        } else {
            mysqli_stmt_close($stmt);
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt2 = mysqli_prepare($conn, "INSERT INTO users (username, password) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt2, "ss", $username, $hashed);

            if (mysqli_stmt_execute($stmt2)) {
                $success = "Account created! You can now <a href='index.php'>login</a>.";
            } else {
                $error = "Registration failed. Please try again.";
            }
            mysqli_stmt_close($stmt2);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineVault &mdash; Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root { --red: #e50914; --dark: #0a0a0a; --card: #141414; --border: #2a2a2a; }
        body {
            background-color: var(--dark); color: #fff;
            font-family: 'Inter', sans-serif; min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            background-image: radial-gradient(ellipse at 80% 50%, rgba(229,9,20,0.07) 0%, transparent 60%);
        }
        .brand { font-family: 'Bebas Neue', sans-serif; font-size: 2.8rem; color: var(--red); letter-spacing: 3px; }
        .login-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; padding: 2.5rem; width: 100%; max-width: 420px; }
        .form-control { background: #1e1e1e; border: 1px solid var(--border); color: #fff; border-radius: 8px; }
        .form-control:focus { background: #1e1e1e; border-color: var(--red); color: #fff; box-shadow: 0 0 0 2px rgba(229,9,20,0.2); }
        .btn-primary { background: var(--red); border: none; border-radius: 8px; font-weight: 500; }
        .btn-primary:hover { background: #c1070f; }
        label { color: #aaa; font-size: 0.85rem; }
        a { color: var(--red); }
    </style>
</head>
<body>
<div class="login-card">
    <div class="text-center mb-4">
        <div class="brand">CineVault</div>
        <p class="text-secondary mt-1" style="font-size:0.85rem;">Create your account</p>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger py-2" style="font-size:0.85rem;"><?= $error ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success py-2" style="font-size:0.85rem;"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" id="regForm" novalidate>
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control"
                   value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>"
                   placeholder="Choose a username">
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="At least 6 characters">
        </div>
        <div class="mb-4">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" placeholder="Repeat password">
        </div>
        <button type="submit" class="btn btn-primary w-100 py-2">Create Account</button>
    </form>

    <p class="text-center mt-4 mb-0" style="font-size:0.85rem; color:#666;">
        Already have an account? <a href="index.php">Login</a>
    </p>
</div>

<script>
document.getElementById('regForm').addEventListener('submit', function(e) {
    const username = this.username.value.trim();
    const password = this.password.value;
    const confirm  = this.confirm_password.value;

    if (!username || !password || !confirm) {
        e.preventDefault();
        alert('All fields are required.');
        return;
    }
    if (password.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters.');
        return;
    }
    if (password !== confirm) {
        e.preventDefault();
        alert('Passwords do not match.');
    }
});
</script>
</body>
</html>

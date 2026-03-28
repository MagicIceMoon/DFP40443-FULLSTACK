<?php
session_start();
if (!isset($_SESSION["id"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

require_once("config/app_config.php");

$mesej = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"]; 
    $email    = $_POST["email"];
    $role_admin = 1;

    $stmt = mysqli_prepare($conn, "INSERT INTO users (username, password, email, roles_id) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssi", $username, $password, $email, $role_admin);

    if(mysqli_stmt_execute($stmt)) {
        header("Location: dashboard.php?msg=Admin just successfully registered!");
        exit();
    } else {
        $mesej = "Error: Username may already exist or a database problem.";
    }
}

$page_title = "Add Admin - SMS";
include("includes/header.php");
?>

<div class="container mt-5" style="max-width: 450px;">
    <div class="form-card">
        <div class="form-card-header">
            <h5 class="mb-0"><i class="bi bi-shield-lock-fill me-2"></i>Add New Admin</h5>
        </div>
        <div class="card-body p-4">

            <?php if ($message): ?>
                <div class="alert alert-danger py-2 small"><?php echo $message; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Admin Username</label>
                    <input type="text" name="username" class="form-control" required placeholder="Example: mahmud_admin">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Enter a password">
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="admin@sms.com">
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-person-plus-fill me-1"></i>Save Admin
                    </button>
                    <a href="dashboard.php" class="btn btn-outline-secondary py-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

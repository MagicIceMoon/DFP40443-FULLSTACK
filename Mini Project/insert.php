<?php

session_start();
if (!isset($_SESSION["id"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

require_once("config/app_config.php");

$sqlPeranan = "SELECT * FROM roles";
$hasilPeranan = mysqli_query($conn, $sqlPeranan);

$mesej = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $roles_id = $_POST["roles_id"];

    $stmt = mysqli_prepare($conn,"INSERT INTO users (username, password, email, roles_id) VALUES (?,?,?,?)");
    mysqli_stmt_bind_param($stmt, "sssi", $username, $password, $email, $roles_id);

    if(mysqli_stmt_execute($stmt)) {
        $mesej = "success";
    } else {
        $mesej = "error";
    }

    header("Location: insert_user.php?msg=" . $mesej);
    exit();
}

$page_title = "Add User - SMS";
include("includes/header.php");
?>

<div class="container mt-4" style="max-width:500px;">
    <div class="form-card">
        <div class="form-card-header">
            <i class="bi bi-person-plus me-2"></i>Add New User
        </div>
        <div class="form-card-body">
 
            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
                <div class="alert alert-success py-2" style="font-size:13px;">
                    <i class="bi bi-check-circle me-1"></i>User added successfully!
                </div>
            <?php elseif (isset($_GET['msg']) && $_GET['msg'] == 'error'): ?>
                <div class="alert alert-danger py-2" style="font-size:13px;">
                    <i class="bi bi-exclamation-circle me-1"></i>Failed to add user!
                </div>
            <?php endif; ?>
 
            <form method="POST" action="">
 
                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size:13px;">Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                </div>
 
                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size:13px;">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                </div>
 
                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size:13px;">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="email@example.com">
                </div>
 
                <div class="mb-4">
                    <label class="form-label fw-bold" style="font-size:13px;">Role</label>
                    <select name="roles_id" class="form-select" required>
                        <option value="">-- Select Role --</option>
                        <?php while ($row = mysqli_fetch_assoc($hasilPeranan)): ?>
                            <option value="<?php echo $row['id']; ?>">
                                <?php echo $row['roles_name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
 
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i>Save User
                    </button>
                    <a href="dashboard.php" class="btn btn-outline-secondary px-4">Cancel</a>
                </div>
 
            </form>
        </div>
    </div>
</div>
 
<?php include('includes/footer.php'); ?>
 
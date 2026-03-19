<?php

session_start();
if (!isset($_SESSION["id"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

require_once("config/app_config.php");
$maklumat = mysqli_query($conn,"SELECT users.id, users.username, users.email, roles.roles_name FROM users JOIN roles ON users.roles_id = roles.id");

$mesej = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $userid = $_POST["user_id"];

    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt,"i", $userid);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: delete.php?msg=success");
        } else {
        header("Location: delete.php?msg=error");
        }
        exit();
}

$page_title = "Delete user - SMS";
include("includes/header.php");
?>
<div class="container mt-4">
 
    <!-- Title -->
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">
            <i class="bi bi-trash me-2 text-danger"></i>Manage Users
        </h5>
        <a href="dashboard.php" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
        </a>
    </div>
 
    <!-- Message -->
    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
        <div class="alert alert-success alert-dismissible fade show py-2" style="font-size:13px;">
            <i class="bi bi-check-circle me-1"></i>User deleted successfully!
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif (isset($_GET['msg']) && $_GET['msg'] == 'error'): ?>
        <div class="alert alert-danger alert-dismissible fade show py-2" style="font-size:13px;">
            <i class="bi bi-exclamation-circle me-1"></i>Failed to delete user!
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
 
    <!-- Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header" style="background:#1a3c6e; color:white; font-weight:bold; font-size:14px;">
            <i class="bi bi-people me-2"></i>User List
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($pengguna = mysqli_fetch_assoc($maklumat)): ?>
                    <tr>
                        <td><?php echo $pengguna['id']; ?></td>
                        <td>
                            <i class="bi bi-person-circle me-1 text-secondary"></i>
                            <?php echo htmlspecialchars($pengguna['username']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($pengguna['email']); ?></td>
                        <td>
                            <?php
                            $badge = $pengguna['roles_name'] == 'admin' ? 'danger' : 'primary';
                            ?>
                            <span class="badge bg-<?php echo $badge; ?>">
                                <?php echo $pengguna['roles_name']; ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="user_id" value="<?php echo $pengguna['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Delete user: <?php echo htmlspecialchars(addslashes($pengguna['username'])); ?>?')">
                                    <i class="bi bi-trash me-1"></i>Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
 
</div>
 
<?php include('includes/footer.php'); ?>
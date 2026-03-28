<?php
session_start();
if (!isset($_SESSION["id"]) || $_SESSION["role"] != "admin") {
    header("Location: login.php");
    exit();
}

require_once("config/app_config.php");

$mesej = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // User Account Data
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email    = $_POST["email"];
    $roles_id = $_POST["roles_id"];

    $name     = $_POST["name"];
    $reg_no   = $_POST["reg_no"];
    $program  = $_POST["program"];
    $semester = $_POST["semester"];
    $gpa      = $_POST["gpa"];

    $stmt1 = mysqli_prepare($conn, "INSERT INTO users (username, password, email, roles_id) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt1, "sssi", $username, $password, $email, $roles_id);

    if(mysqli_stmt_execute($stmt1)) {
        $last_user_id = mysqli_insert_id($conn);

        $stmt2 = mysqli_prepare($conn, "INSERT INTO students (reg_no, name, program, semester, gpa, status, email, user_id) VALUES (?, ?, ?, ?, ?, 'Active', ?, ?)");
        mysqli_stmt_bind_param($stmt2, "sssdssi", $reg_no, $name, $program, $semester, $gpa, $email, $last_user_id);

        if(mysqli_stmt_execute($stmt2)) {
            header("Location: dashboard.php?msg=New student added successfully!");
            exit();
        } else {
            $mesej = "Error creating student profile.";
        }
    } else {
        $mesej = "Error creating user account.";
    }
}

$page_title = "Add Student - SMS";
include("includes/header.php");
?>

<div class="container mt-4" style="max-width:600px;">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="mb-0"><i class="bi bi-person-plus-fill me-2"></i>Register New Student</h5>
        </div>
        <div class="card-body p-4">
            
            <?php if ($mesej): ?>
                <div class="alert alert-danger"><?php echo $mesej; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <!-- Account Section -->
                <h6 class="fw-bold">Login Credentials</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>

                <!-- Academic Section -->
                <h6 class="fw-bold">Academic Profile</h6>
                <div class="mb-3">
                    <label class="form-label fw-bold">Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. John Doe" required>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Registration No.</label>
                        <input type="text" name="reg_no" class="form-control" placeholder="2026XXX" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="student@email.com">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Program</label>
                    <select name="program" class="form-select" required>
                        <option value="Diploma IT">Diploma IT</option>
                        <option value="Diploma Business">Diploma Business</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Current Semester</label>
                        <input type="number" name="semester" class="form-control" min="1" max="8" value="1">
                    </div>
                    <div class="col-md-6 mb-4">
                        <label class="form-label fw-bold">Current GPA</label>
                        <input type="number" step="0.01" name="gpa" class="form-control" placeholder="0.00">
                    </div>
                </div>

                <!-- Role hidden as '2' for Students based on your SQL -->
                <input type="hidden" name="roles_id" value="2">

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary py-2">
                        <i class="bi bi-save me-1"></i>Save & View on Dashboard
                    </button>
                    <a href="dashboard.php" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>

        </div>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); exit();
}

require_once("config/app_config.php");

$id       = $_GET['id'] ?? 0;
$programs = ['Full Stack Web Dev', 'Diploma IT', 'Diploma Computer Science', 'Diploma Business'];

$stmt = mysqli_prepare($conn, "SELECT * FROM students WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$s      = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$s) { header("Location: dashboard.php"); exit(); }

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $program  = $_POST['program'];
    $semester = $_POST['semester'];
    $gpa      = $_POST['gpa'];
    $status   = $_POST['status'];

    $stmt2 = mysqli_prepare($conn, "UPDATE students SET name=?, email=?, program=?, semester=?, gpa=?, status=? WHERE id=?");
    mysqli_stmt_bind_param($stmt2, "sssiisi", $name, $email, $program, $semester, $gpa, $status, $id);

    if (mysqli_stmt_execute($stmt2)) {
        header("Location: dashboard.php?msg=Student updated successfully!");
        exit();
    } else {
        $error = "Error: " . mysqli_stmt_error($stmt2);
    }
    mysqli_stmt_close($stmt2);
}

$page_title = "Edit Student - SMS";
include("includes/header.php");
?>

<div class="container mt-4">
    <div class="form-card">
        <div class="form-card-header">
            <i class="bi bi-pencil-square me-2"></i>Edit Student
        </div>
        <div class="form-card-body">

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="row">

                    <div class="col-12">
                        <label class="fw-bold">Registration No</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($s['reg_no']); ?>" disabled style="background:#f5f5f5;">
                    </div>

                    <div class="col-12">
                        <label class="fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($s['name']); ?>" required>
                    </div>

                    <div class="col-12">
                        <label class="fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($s['email']); ?>">
                    </div>

                    <div class="col-12">
                        <label class="fw-bold">Program</label>
                        <select name="program" class="form-select">
                            <?php foreach ($programs as $p): ?>
                                <option value="<?php echo $p; ?>" <?php echo $s['program'] == $p ? 'selected' : ''; ?>>
                                    <?php echo $p; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="fw-bold">Semester</label>
                        <input type="number" name="semester" class="form-control" value="<?php echo $s['semester']; ?>" min="1" max="8">
                    </div>

                    <div class="col-md-4">
                        <label class="fw-bold">GPA</label>
                        <input type="number" name="gpa" class="form-control" step="0.01" min="0" max="4" value="<?php echo $s['gpa']; ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <?php foreach (['Active', 'Inactive', 'Graduated'] as $st): ?>
                                <option <?php echo $s['status'] == $st ? 'selected' : ''; ?>><?php echo $st; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-1"></i>Update Student
                    </button>
                    <a href="dashboard.php" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>

        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
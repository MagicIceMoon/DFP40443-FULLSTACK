<?php
session_start();
if (!isset($_SESSION['id'])) { header("Location: login.php"); exit(); }

require_once("config/app_config.php");

$cari    = $_GET['cari'] ?? '';
$program = $_GET['program'] ?? '';

$like     = "%$cari%";

$prog = $program != '' ? $program :'%';

$stmt = mysqli_prepare($conn, "SELECT * FROM students WHERE (name LIKE ? OR reg_no LIKE ?) AND program LIKE ? ORDER BY name ASC");
mysqli_stmt_bind_param($stmt, 'sss', $like, $like, $prog);
mysqli_stmt_execute($stmt);
$pelajar = mysqli_stmt_get_result($stmt);

$prog_result = mysqli_query($conn, "SELECT program, COUNT(*) as total FROM students GROUP BY program ORDER BY program");
$prog_list = [];
while ($row = mysqli_fetch_assoc($prog_result)) {
    $prog_list[] = $row;
}

$total  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM students"))['c'];
$active = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM students WHERE status='Active'"))['c'];
$inactive = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM students WHERE status='Inactive'"))['c'];

function programColor($id) {
    srand($id);
    $r = rand(50, 180);
    $g = rand(50, 180);
    $b = rand(50, 180);
    return "rgb($r, $g, $b)";
}

$page_title = "Dashboard - SMS";
include("includes/header.php");
?>

<div class="container-fluid mt-4">

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div style="font-size:28px; font-weight:bold; color:#1a3c6e;"><?php echo $total; ?></div>
                        <div style="font-size:13px;">Total Students</div>
                    </div>
                    <i class="bi bi-people-fill" style="font-size:30px; color:#1a3c6e; opacity:0.5;"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card green">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div style="font-size:28px; font-weight:bold; color:#27ae60;"><?php echo $active; ?></div>
                        <div style="font-size:13px;">Active</div>
                    </div>
                    <i class="bi bi-person-check-fill" style="font-size:30px; color:#27ae60; opacity:0.5;"></i>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4">
            <div class="stat-card orange">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div style="font-size:28px; font-weight:bold; color:#e8a020;"><?php echo $inactive; ?></div>
                        <div style="font-size:13px;">Inactive</div>
                    </div>
                    <i class="bi bi-person-dash-fill" style="font-size:30px; color:#e8a020; opacity:0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="card border-0 shadow-sm mb-4 rounded-3">
        <div class="card-body py-3">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="cari" class="form-control"
                               placeholder="Search name / reg no..."
                               value="<?php echo htmlspecialchars($cari); ?>">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="program" class="form-select">
                        <option value="">All Programs</option>
                        <?php foreach ($prog_list as $row): ?>
                            <option value="<?php echo htmlspecialchars($row['program']); ?>"
                                <?php echo $program == $row['program'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($row['program']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                    <a href="dashboard.php" class="btn btn-outline-secondary ms-1">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Result info -->
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <small class="text-muted">
            Showing <strong><?php echo mysqli_num_rows($pelajar); ?></strong> of <strong><?php echo $total; ?></strong> students
            <?php if ($cari || $program): ?>
                <span class="badge bg-primary ms-1">Filtered</span>
            <?php endif; ?>
        </small>
    </div>

    <!-- Success Message -->
    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success alert-dismissible fade show py-2" style="font-size:13px;">
            <i class="bi bi-check-circle me-1"></i><?php echo htmlspecialchars($_GET['msg']); ?>
            <button type="button" class="btn-close py-2" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Student Cards -->
    <div class="row g-3">

        <?php if (mysqli_num_rows($pelajar) > 0):
            while ($s = mysqli_fetch_assoc($pelajar)):
                $bg        = programColor($s['id']);
                $initial   = strtoupper(substr($s['name'], 0, 1));
                $gpa_color = $s['gpa'] >= 3.5 ? '#27ae60' : ($s['gpa'] >= 2.5 ? '#f39c12' : '#e74c3c');
                $status_color = $s['status'] == 'Active' ? 'success' : 'danger';
        ?>

        <div class="col-6 col-md-4 col-lg-3">
            <div class="student-card h-100">

                <!-- Avatar -->
                <div class="card-avatar" style="background:<?php echo $bg ?>;">
                    <?php echo $initial; ?>
                    <span class="reg-badge"><?php echo htmlspecialchars($s['reg_no']); ?></span>
                </div>

                <!-- Info -->
                <div class="card-info">
                    <div class="student-name"><?php echo htmlspecialchars($s['name']); ?></div>
                    <p>
                        <i class="bi bi-journals" style="font-size:11px;"></i>
                        <?php echo htmlspecialchars($s['program']); ?><br>

                        <i class="bi bi-calendar" style="font-size:11px;"></i>
                        Sem <?php echo $s['semester']; ?> &nbsp;|&nbsp;
                        <span style="color:<?php echo $gpa_color; ?>; font-weight:bold;">
                            GPA <?php echo number_format($s['gpa'], 2); ?>
                        </span><br>

                        <i class="bi bi-envelope-at-fill" style="font-size:11px;"></i>
                        <?php echo htmlspecialchars($s['email']); ?><br>

                        <span class="badge bg-<?php echo $status_color; ?> mt-1" style="font-size:11px;">
                            <?php echo $s['status']; ?>
                        </span>
                    </p>
                </div>

                <!-- Admin -->
                <?php if ($_SESSION['role'] == 'admin'): ?>
                <div class="card-actions">
                    <a href="delete.php?id=<?php echo $s['id']; ?>"
                       onclick="return confirm('Delete <?php echo htmlspecialchars(addslashes($s['name'])); ?>?')"
                       class="btn btn-danger btn-sm w-50">
                        <i class="bi bi-trash"></i> Delete
                    </a>
                    <a href="update.php?id=<?php echo $s['id']; ?>"
                       class="btn btn-warning btn-sm w-50">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                </div>
                <?php endif; ?>

            </div>
        </div>

        <?php endwhile; else: ?>
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi-search" style="font-size:40px;"></i>
                <p class="mt-2">No students found.</p>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php include 'includes/footer.php'; ?>
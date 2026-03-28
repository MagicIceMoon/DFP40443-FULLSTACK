<?php
/*require_once("config/app_config.php");*/
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title><?php echo $page_title ?? 'Student Management System'; ?></title>
    <style>
        body {
            background: #eef0fc;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background: #377adf !important;
        }
        .navbar-brand {
            font-weight: bold;
            color: white !important;
            font-size: 18px;
        }
        .navbar .nav-link {
            color: rgba(255,255,255,0.85) !important;
        }
        .navbar .nav-link:hover {
            color: white !important;
        }
        .badge-role {
            background: #e8a020;
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
        }
        .student-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0);
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .student-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0);
        }
        .card-avatar {
            height: 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            font-weight: bold;
            position: relative;
        }
        .card-avatar .reg-badge {
            position: absolute;
            bottom: 8px;
            left: 8px;
            background: rgba(0,0,0,0.4);
            color: white;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 20px;
        }
        .card-info {
            padding: 12px;
        }
        .card-info .student-name {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
            color: #222;
        }
        .card-info p {
            font-size: 12px;
            color: #666;
            margin: 0;
            line-height: 1.8;
        }
        .card-actions {
            padding: 8px 12px;
            border-top: 1px solid #f0f0f0;
            background: #fafafa;
            display: flex;
            gap: 6px;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            border-left: 4px solid #377adf;
        }
        .stat-card.green  { border-color: #01bd4f; }
        .stat-card.orange { border-color: #f1a31c; }
        .form-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .form-card-header {
            background: #377adf;
            color: white;
            padding: 14px 20px;
            font-weight: bold;
            font-size: 15px;
        }
        .form-card-body {
            padding: 25px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid px-4">
    <a class="navbar-brand" href="dashboard.php">
      <i class="bi bi-mortarboard-fill me-2"></i>Student Management System
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php">
            <i class="bi bi-grid me-1"></i>Dashboard
          </a>
        </li>
        <?php if(($_SESSION["role"] ?? "") == "admin"): ?>
          <li class="nav-item">
            <a class="nav-link" href="insert.php">
              <i class="bi bi-person-plus me-1"></i>Add Student
            </a>
          </li>
          <?php endif; ?>
          <?php if(($_SESSION["role"] ?? "") == "admin"): ?>
          <li class="nav-item">
            <a class="nav-link" href="insert_admin.php">
              <i class="bi bi-shield-lock me-1"></i>Add Admin
            </a>
          </li>
          <?php endif; ?>
      </ul>
      
      <ul class="navbar-nav">
        <li class="nav-item d-flex align-items-center me-3">
          <i class="bi bi-person-circle text-white me-2"></i>
          <span class="text-white me-2"><?php echo $_SESSION['username'] ?? ''; ?></span>
          <span class="badge-role"><?php echo $_SESSION['role'] ?? ''; ?></span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php">
            <i class="bi bi-box-arrow-right me-1"></i>Logout
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

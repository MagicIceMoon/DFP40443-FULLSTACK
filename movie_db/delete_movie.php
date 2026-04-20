<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require 'config/db.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Get image path first
    $stmt = mysqli_prepare($conn, "SELECT image_path FROM movies WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res  = mysqli_stmt_get_result($stmt);
    $row  = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if ($row) {
        // Delete image file from server
        if (!empty($row['image_path']) && file_exists("uploads/" . $row['image_path'])) {
            unlink("uploads/" . $row['image_path']);
        }

        // Delete record from database
        $del = mysqli_prepare($conn, "DELETE FROM movies WHERE id = ?");
        mysqli_stmt_bind_param($del, "i", $id);
        mysqli_stmt_execute($del);
        mysqli_stmt_close($del);
    }
}

header("Location: dashboard.php");
exit();
?>

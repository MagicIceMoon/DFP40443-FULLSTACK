<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require 'config/db.php';

$movie  = ['id'=>'','title'=>'','genre'=>'','director'=>'','release_year'=>'','description'=>'','image_path'=>''];
$errors = [];
$is_edit = false;

// Load existing movie if editing
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $edit_id = intval($_GET['id']);
    $stmt = mysqli_prepare($conn, "SELECT * FROM movies WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if ($row) {
        $movie   = $row;
        $is_edit = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title        = htmlspecialchars(trim($_POST['title']));
    $genre        = htmlspecialchars(trim($_POST['genre']));
    $director     = htmlspecialchars(trim($_POST['director']));
    $release_year = trim($_POST['release_year']);
    $description  = htmlspecialchars(trim($_POST['description']));
    $id           = intval($_POST['id']);

    // Server-side validation
    if (empty($title))        $errors[] = "Title is required.";
    if (empty($genre))        $errors[] = "Genre is required.";
    if (empty($director))     $errors[] = "Director is required.";
    if (empty($release_year) || !is_numeric($release_year) || $release_year < 1888 || $release_year > date('Y') + 2)
                              $errors[] = "Enter a valid release year.";
    if (empty($description))  $errors[] = "Description is required.";

    // Sticky values
    $movie['title']        = $title;
    $movie['genre']        = $genre;
    $movie['director']     = $director;
    $movie['release_year'] = $release_year;
    $movie['description']  = $description;
    $movie['id']           = $id;

    // Handle image upload
    $image_path = $movie['image_path']; // keep old image by default
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_size      = 2 * 1024 * 1024; // 2MB

        if (!in_array($_FILES['image']['type'], $allowed_types)) {
            $errors[] = "Only JPG, PNG, GIF, WEBP images are allowed.";
        } elseif ($_FILES['image']['size'] > $max_size) {
            $errors[] = "Image must be under 2MB.";
        } else {
            $ext        = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $new_name   = uniqid('movie_', true) . '.' . $ext;
            $upload_dir = 'uploads/';

            if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_name)) {
                // Delete old image if editing
                if ($is_edit && !empty($movie['image_path']) && file_exists($upload_dir . $movie['image_path'])) {
                    unlink($upload_dir . $movie['image_path']);
                }
                $image_path = $new_name;
            } else {
                $errors[] = "Failed to upload image.";
            }
        }
    }

    if (empty($errors)) {
        if ($id > 0) {
            // UPDATE
            $stmt = mysqli_prepare($conn, "UPDATE movies SET title=?, genre=?, director=?, release_year=?, description=?, image_path=? WHERE id=?");
            mysqli_stmt_bind_param($stmt, "ssssssi", $title, $genre, $director, $release_year, $description, $image_path, $id);
        } else {
            // INSERT
            $stmt = mysqli_prepare($conn, "INSERT INTO movies (title, genre, director, release_year, description, image_path) VALUES (?,?,?,?,?,?)");
            mysqli_stmt_bind_param($stmt, "ssssss", $title, $genre, $director, $release_year, $description, $image_path);
        }

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: dashboard.php");
            exit();
        } else {
            $errors[] = "Database error. Please try again.";
        }
        mysqli_stmt_close($stmt);
    }

    $is_edit = $id > 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineVault &mdash; <?= $is_edit ? 'Edit Movie' : 'Add Movie' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --red: #e50914; --dark: #0a0a0a; --card: #141414; --border: #2a2a2a; }
        body { background: var(--dark); color: #fff; font-family: 'Inter', sans-serif; min-height: 100vh; }
        .navbar { background: rgba(10,10,10,0.95) !important; border-bottom: 1px solid var(--border); }
        .brand { font-family: 'Bebas Neue', sans-serif; font-size: 1.8rem; color: var(--red); letter-spacing: 3px; }
        .form-card { background: var(--card); border: 1px solid var(--border); border-radius: 14px; padding: 2rem; }
        .form-control, .form-select { background: #1a1a1a; border: 1px solid var(--border); color: #fff; border-radius: 8px; }
        .form-control:focus, .form-select:focus { background: #1a1a1a; border-color: var(--red); color: #fff; box-shadow: 0 0 0 2px rgba(229,9,20,0.15); }
        .form-control::placeholder { color: #444; }
        .form-select option { background: #1a1a1a; }
        label { color: #aaa; font-size: 0.83rem; margin-bottom: 5px; }
        .btn-save { background: var(--red); border: none; border-radius: 8px; font-weight: 500; }
        .btn-save:hover { background: #c1070f; }
        .btn-back { background: #1e1e1e; border: 1px solid var(--border); color: #aaa; border-radius: 8px; }
        .btn-back:hover { background: #2a2a2a; color: #fff; }
        .page-title { font-family: 'Bebas Neue', sans-serif; font-size: 1.6rem; letter-spacing: 2px; }
        .img-preview { width: 100%; border-radius: 10px; object-fit: cover; max-height: 280px; border: 1px solid var(--border); }
        .preview-placeholder { background: #1a1a1a; border: 1px solid var(--border); border-radius: 10px; height: 200px; display: flex; align-items: center; justify-content: center; color: #333; font-size: 2.5rem; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container px-4">
        <span class="brand">CineVault</span>
        <a href="dashboard.php" class="ms-auto text-secondary" style="font-size:0.85rem; text-decoration:none;">
            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>
</nav>

<div class="container py-4" style="max-width:720px;">
    <div class="d-flex align-items-center gap-2 mb-3">
        <span class="page-title"><?= $is_edit ? 'EDIT MOVIE' : 'ADD MOVIE' ?></span>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger py-2" style="font-size:0.85rem;">
            <?php foreach ($errors as $e): ?><div>• <?= $e ?></div><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="form-card">
        <form method="POST" enctype="multipart/form-data" id="movieForm" novalidate>
            <input type="hidden" name="id" value="<?= $movie['id'] ?>">

            <div class="row g-3">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Movie Title *</label>
                        <input type="text" name="title" class="form-control"
                               value="<?= htmlspecialchars($movie['title']) ?>" placeholder="e.g. Inception">
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Genre *</label>
                            <select name="genre" class="form-select">
                                <option value="">Select genre</option>
                                <?php
                                $genres = ['Action','Adventure','Animation','Comedy','Crime','Drama','Fantasy','Horror','Mystery','Romance','Sci-Fi','Thriller','Documentary','Biography'];
                                foreach ($genres as $g):
                                    $sel = ($movie['genre'] == $g) ? 'selected' : '';
                                ?>
                                <option value="<?= $g ?>" <?= $sel ?>><?= $g ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Release Year *</label>
                            <input type="number" name="release_year" class="form-control"
                                   value="<?= htmlspecialchars($movie['release_year']) ?>"
                                   placeholder="e.g. 2010" min="1888" max="<?= date('Y') + 2 ?>">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Director *</label>
                        <input type="text" name="director" class="form-control"
                               value="<?= htmlspecialchars($movie['director']) ?>" placeholder="e.g. Christopher Nolan">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Movie Poster</label>
                    <?php if (!empty($movie['image_path']) && file_exists("uploads/" . $movie['image_path'])): ?>
                        <img src="uploads/<?= htmlspecialchars($movie['image_path']) ?>"
                             class="img-preview mb-2" id="imgPreview" alt="poster">
                    <?php else: ?>
                        <div class="preview-placeholder mb-2" id="previewPlaceholder">🎬</div>
                        <img src="" class="img-preview mb-2 d-none" id="imgPreview" alt="poster">
                    <?php endif; ?>
                    <input type="file" name="image" class="form-control" accept="image/*"
                           onchange="previewImage(this)" style="font-size:0.8rem;">
                    <div class="text-secondary mt-1" style="font-size:0.72rem;">JPG, PNG, WEBP &bull; Max 2MB</div>
                </div>

                <div class="col-12">
                    <label class="form-label">Description *</label>
                    <textarea name="description" class="form-control" rows="4"
                              placeholder="Brief plot summary..."><?= htmlspecialchars($movie['description']) ?></textarea>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-save text-white px-4 py-2">
                    <i class="bi bi-check-lg me-1"></i> <?= $is_edit ? 'Update Movie' : 'Save Movie' ?>
                </button>
                <a href="dashboard.php" class="btn btn-back px-4 py-2">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    const preview     = document.getElementById('imgPreview');
    const placeholder = document.getElementById('previewPlaceholder');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

document.getElementById('movieForm').addEventListener('submit', function(e) {
    const title       = this.title.value.trim();
    const genre       = this.genre.value;
    const director    = this.director.value.trim();
    const year        = parseInt(this.release_year.value);
    const description = this.description.value.trim();
    const errs        = [];

    if (!title)       errs.push('Title is required.');
    if (!genre)       errs.push('Genre is required.');
    if (!director)    errs.push('Director is required.');
    if (!year || year < 1888 || year > <?= date('Y') + 2 ?>) errs.push('Enter a valid release year.');
    if (!description) errs.push('Description is required.');

    if (errs.length > 0) {
        e.preventDefault();
        alert(errs.join('\n'));
    }
});
</script>
</body>
</html>

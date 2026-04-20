<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
require 'config/db.php';

$result = mysqli_query($conn, "SELECT * FROM movies ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineVault &mdash; Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { --red: #e50914; --dark: #0a0a0a; --card: #141414; --border: #2a2a2a; }
        body { background: var(--dark); color: #fff; font-family: 'Inter', sans-serif; }
        .navbar { background: rgba(10,10,10,0.95) !important; border-bottom: 1px solid var(--border); backdrop-filter: blur(10px); }
        .brand { font-family: 'Bebas Neue', sans-serif; font-size: 1.8rem; color: var(--red); letter-spacing: 3px; }
        .search-box { background: #1a1a1a; border: 1px solid var(--border); color: #fff; border-radius: 30px; padding: 0.5rem 1.2rem; width: 300px; }
        .search-box:focus { outline: none; border-color: var(--red); box-shadow: 0 0 0 2px rgba(229,9,20,0.15); color: #fff; background: #1a1a1a; }
        .search-box::placeholder { color: #555; }
        .btn-add { background: var(--red); color: #fff; border: none; border-radius: 8px; font-weight: 500; font-size: 0.85rem; }
        .btn-add:hover { background: #c1070f; color: #fff; }
        .movie-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; transition: transform 0.2s, border-color 0.2s; }
        .movie-card:hover { transform: translateY(-4px); border-color: #444; }
        .movie-poster { width: 100%; height: 220px; object-fit: cover; }
        .movie-poster-placeholder { width: 100%; height: 220px; background: #1e1e1e; display: flex; align-items: center; justify-content: center; font-size: 3rem; color: #333; }
        .movie-body { padding: 1rem; }
        .movie-title { font-weight: 600; font-size: 1rem; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .movie-meta { font-size: 0.75rem; color: #666; }
        .badge-genre { background: rgba(229,9,20,0.15); color: var(--red); border: 1px solid rgba(229,9,20,0.3); border-radius: 20px; padding: 2px 10px; font-size: 0.72rem; }
        .btn-edit { background: #1e3a5f; color: #5b9bd5; border: none; border-radius: 6px; font-size: 0.78rem; padding: 4px 12px; }
        .btn-edit:hover { background: #1a3050; color: #7ab3e8; }
        .btn-del { background: rgba(229,9,20,0.1); color: var(--red); border: 1px solid rgba(229,9,20,0.2); border-radius: 6px; font-size: 0.78rem; padding: 4px 12px; }
        .btn-del:hover { background: rgba(229,9,20,0.25); color: #ff3340; }
        .section-title { font-family: 'Bebas Neue', sans-serif; font-size: 1.4rem; letter-spacing: 2px; color: #aaa; }
        #searchResults { display: none; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container-fluid px-4">
        <span class="brand">CineVault</span>
        <div class="d-flex align-items-center gap-3 ms-auto">
            <input type="text" id="searchInput" class="search-box form-control"
                   placeholder="&#128269; Search movies..." onkeyup="liveSearch(this.value)">
            <a href="add_movie.php" class="btn btn-add px-3 py-2">
                <i class="bi bi-plus-lg me-1"></i> Add Movie
            </a>
            <span class="text-secondary" style="font-size:0.82rem;">
                <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($_SESSION['username']) ?>
            </span>
            <a href="logout.php" class="text-secondary" style="font-size:0.82rem;" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</nav>

<div class="container-fluid px-4 py-4">

    <!-- Search Results -->
    <div id="searchResults"></div>

    <!-- All Movies -->
    <div id="allMovies">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <span class="section-title">ALL MOVIES</span>
            <span class="text-secondary" style="font-size:0.8rem;">
                <?= mysqli_num_rows($result) ?> title(s)
            </span>
        </div>

        <?php if (mysqli_num_rows($result) == 0): ?>
            <div class="text-center py-5">
                <div style="font-size:3rem;">🎬</div>
                <p class="text-secondary mt-2">No movies yet. <a href="add_movie.php" style="color:var(--red);">Add your first one!</a></p>
            </div>
        <?php else: ?>
        <div class="row g-3">
            <?php while ($movie = mysqli_fetch_assoc($result)): ?>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <div class="movie-card h-100">
                    <?php if (!empty($movie['image_path']) && file_exists("uploads/" . $movie['image_path'])): ?>
                        <img src="uploads/<?= htmlspecialchars($movie['image_path']) ?>"
                             class="movie-poster" alt="<?= htmlspecialchars($movie['title']) ?>">
                    <?php else: ?>
                        <div class="movie-poster-placeholder">🎥</div>
                    <?php endif; ?>
                    <div class="movie-body">
                        <div class="movie-title" title="<?= htmlspecialchars($movie['title']) ?>">
                            <?= htmlspecialchars($movie['title']) ?>
                        </div>
                        <div class="movie-meta mb-2">
                            <?= htmlspecialchars($movie['director']) ?> &bull; <?= $movie['release_year'] ?>
                        </div>
                        <span class="badge-genre"><?= htmlspecialchars($movie['genre']) ?></span>
                        <div class="d-flex gap-1 mt-2">
                            <a href="add_movie.php?id=<?= $movie['id'] ?>" class="btn btn-edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="delete_movie.php?id=<?= $movie['id'] ?>" class="btn btn-del"
                               onclick="return confirm('Delete \'<?= htmlspecialchars(addslashes($movie['title'])) ?>\'? This cannot be undone.')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function liveSearch(query) {
    const resultsDiv = document.getElementById('searchResults');
    const allMovies  = document.getElementById('allMovies');

    if (query.trim() === '') {
        resultsDiv.style.display = 'none';
        allMovies.style.display  = 'block';
        return;
    }

    allMovies.style.display = 'none';
    resultsDiv.style.display = 'block';
    resultsDiv.innerHTML = '<p class="text-secondary" style="font-size:0.85rem;">Searching...</p>';

    fetch('ajax_search.php?q=' + encodeURIComponent(query))
        .then(res => res.text())
        .then(data => { resultsDiv.innerHTML = data; })
        .catch(() => { resultsDiv.innerHTML = '<p class="text-danger">Search error.</p>'; });
}
</script>

</body>
</html>

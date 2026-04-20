<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit();
}
require 'config/db.php';

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if (empty($q)) {
    echo '<p class="text-secondary">Type to search...</p>';
    exit();
}

$like = "%" . $q . "%";
$stmt = mysqli_prepare($conn, "SELECT * FROM movies WHERE title LIKE ? OR genre LIKE ? OR director LIKE ? ORDER BY title ASC");
mysqli_stmt_bind_param($stmt, "sss", $like, $like, $like);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$total  = mysqli_num_rows($result);
?>

<div class="d-flex align-items-center justify-content-between mb-3">
    <span style="font-family:'Bebas Neue',sans-serif;font-size:1.4rem;letter-spacing:2px;color:#aaa;">
        SEARCH RESULTS
    </span>
    <span class="text-secondary" style="font-size:0.8rem;">
        <?= $total ?> result(s) for "<?= htmlspecialchars($q) ?>"
    </span>
</div>

<?php if ($total == 0): ?>
    <div class="text-center py-4">
        <div style="font-size:2.5rem;">🔍</div>
        <p class="text-secondary mt-2" style="font-size:0.9rem;">
            No movies found for "<?= htmlspecialchars($q) ?>"
        </p>
    </div>
<?php else: ?>
<div class="row g-3">
<?php while ($movie = mysqli_fetch_assoc($result)): ?>
    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div style="background:#141414;border:1px solid #2a2a2a;border-radius:12px;overflow:hidden;">
            <?php if (!empty($movie['image_path']) && file_exists("uploads/" . $movie['image_path'])): ?>
                <img src="uploads/<?= htmlspecialchars($movie['image_path']) ?>"
                     style="width:100%;height:220px;object-fit:cover;"
                     alt="<?= htmlspecialchars($movie['title']) ?>">
            <?php else: ?>
                <div style="width:100%;height:220px;background:#1e1e1e;display:flex;align-items:center;justify-content:center;font-size:3rem;color:#333;">🎥</div>
            <?php endif; ?>
            <div style="padding:1rem;">
                <div style="font-weight:600;font-size:1rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#fff;">
                    <?= htmlspecialchars($movie['title']) ?>
                </div>
                <div style="font-size:0.75rem;color:#666;margin-bottom:6px;">
                    <?= htmlspecialchars($movie['director']) ?> &bull; <?= $movie['release_year'] ?>
                </div>
                <span style="background:rgba(229,9,20,0.15);color:#e50914;border:1px solid rgba(229,9,20,0.3);border-radius:20px;padding:2px 10px;font-size:0.72rem;">
                    <?= htmlspecialchars($movie['genre']) ?>
                </span>
                <div style="display:flex;gap:4px;margin-top:8px;">
                    <a href="add_movie.php?id=<?= $movie['id'] ?>"
                       style="background:#1e3a5f;color:#5b9bd5;border-radius:6px;font-size:0.78rem;padding:4px 12px;text-decoration:none;">✏️</a>
                    <a href="delete_movie.php?id=<?= $movie['id'] ?>"
                       style="background:rgba(229,9,20,0.1);color:#e50914;border:1px solid rgba(229,9,20,0.2);border-radius:6px;font-size:0.78rem;padding:4px 12px;text-decoration:none;"
                       onclick="return confirm('Delete this movie?')">🗑️</a>
                </div>
            </div>
        </div>
    </div>
<?php endwhile; ?>
</div>
<?php endif; ?>
<?php mysqli_stmt_close($stmt); ?>

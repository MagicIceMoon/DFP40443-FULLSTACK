<?php
session_start();
include("db.php");
$sqlProduct = "SELECT * FROM products";
$hasilSQL = mysqli_query($conn, $sqlProduct);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>View Product</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Ecommerce</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="add_product.php">Add Product</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="delete_product.php">Delete Product</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="edit_product.php">Edit Product</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="view_products.php">View Product <span class="sr-only">(current)</span></a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($hasilSQL) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($hasilSQL)): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td>
                                        <?php if (!empty($row['image_path']) && file_exists($row['image_path'])): ?>
                                            <img src="<?= htmlspecialchars($row['image_path']) ?>" alt="<?= htmlspecialchars($row['product_name']) ?>" class="product-img">
                                        <?php else: ?>
                                            <div class="product-img bg-secondary align-items-center justify-content-center text-white rounded">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['product_name']) ?></td>
                                    <td>RM <?= htmlspecialchars($row['price'], 2) ?></td>
                                    <td class="text-center">
                                        <a href="edit_product.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <i class="bi bi-inbox d-block"></i>
                                            No products found <a href="add_product.php">Add one now!</a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
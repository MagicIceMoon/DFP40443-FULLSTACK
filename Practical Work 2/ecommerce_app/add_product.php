<?php
session_start();
include("db.php");
$sqlProduct = "SELECT * FROM products";
$hasilSQL = mysqli_query($conn, $sqlProduct);

$mesej = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = $_POST["product_name"];
    $productPrice = $_POST["price"];
    $imagePath = $_POST["image_path"];

    $arahanSQL = mysqli_prepare($conn, "INSERT INTO products (product_name,price,image_path) VALUES (?,?,?)");
    mysqli_stmt_bind_param($arahanSQL, "sds", $productName, $productPrice, $imagePath);
    if(mysqli_stmt_execute($arahanSQL)) {
        $mesej = "<p style='color:green;'>Success Insert Product!</p>";
    } else {
        $mesej = "<p style='color:red;'>Not Success Insert Product!</p>". mysqli_stmt_error($arahanSQL);
    }
    $hasilSQL = mysqli_query($conn, $sqlProduct);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <title>Add Product</title>
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
                    <a class="nav-link" href="add_product.php">Add Product <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="delete_product.php">Delete Product</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="edit_product.php">Edit Product</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="view_products.php">View Product</span></a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-secondary text-white">
            <h5>Add Product</h5>
        </div>
    <div class="card-body">
    <form method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6 mb-3">
            <label class="form-label fw-bold" for="productName">Product Name</label>
            <input type="text" class="form-control" id="productName" placeholder="Mie Ayam">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
            <label class="form-label fw-bold" for="productPrice">Product Price</label>
            <input type="number" class="form-control" id="productPrice" placeholder="34.00">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
            <label class="form-label fw-bold" for="productImagePath">Put the Product Image</label>
            <input type="file" class="form-control-file" id="productImagePath">
            </div>
        </div>

        <div class="d-grip gap-2">
            <button type="submit" class="btn btn-secondary">
                <i class="bi bi-save"></i>Add Product
            </button>
            <a href="add_product.php" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
    </div>
    </div>
    </div>
</body>
</html>
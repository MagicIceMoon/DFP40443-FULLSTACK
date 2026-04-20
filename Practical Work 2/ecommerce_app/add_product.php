<?php
session_start();
include("db.php");
$sqlProduct = "SELECT * FROM products";
$hasilSQL = mysqli_query($conn, $sqlProduct);

$product_name = $price = $image_error = $name_error = $price_error = "";
$mesej = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = htmlspecialchars(stripcslashes(trim($_POST["product_name"])));
    $price = htmlspecialchars(stripcslashes(trim($_POST["price"])));

    if(empty($product_name)) {
        $name_error = "Product name required";
    }

    if(empty($price)) {
        $price_error = "Price is required";
    } elseif (!is_numeric($price) || $price <= 0) {
        $price_error = "Price must be a valid positive number";
    }

    $imagePath = "";

    if (!isset($_FILES['image']) || $_FILES['image']['error'] == UPLOAD_ERR_NO_FILE) {
        $image_error = "Please upload a image";
    } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $image_error - "File upload error please try again";
    } else {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $file_size = $_FILES['image']['size'];

        if (!in_array($file_ext, $allowed_extensions)) {
            $image_error = "Only JPG, JPEG, PNG and GIF files allowed";
        } elseif ($file_size > 2 * 1024 * 1024) {
            $image_error = "File size not exceed 2MB";
        }
    }

    if (empty($name_error) && empty($price_error) && empty($image_error)) {
        $upload_dir = "product_images/";
        
        if(!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $new_filename = uniqid() . "_" . basename($_FILES['image']['name']);
        $target_path = $upload_dir . $new_filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $imagePath = $target_path;

            $arahanSQL = mysqli_prepare($conn, "INSERT INTO products (product_name, price, image_path) VALUES (?,?,?)");

            mysqli_stmt_bind_param($arahanSQL, "sds", $product_name, $price, $imagePath);

            if(mysqli_stmt_execute($arahanSQL)) {
                $mesej = "<p style='color:green;'>Success Insert Product!</p>";
                $product_name = $price = "";
            } else {
                $mesej = "<p style='color:red;'>Not Success Insert Product!</p>". mysqli_stmt_error($arahanSQL);
            }
            mysqli_stmt_close($arahanSQL);
        } else {
            $image_error = "Failed to move uploaded file.";
        }
    }


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>
        function validateForm() {
            let valid = true;

            const name = document.getElementById('product_name').value.trim();
            const price = document.getElementById('price').value.trim();
            const image = document.getElementById('image').value;

            if(name === '') {
                alert('Require a product name');
                valid = false;
            } else if (price === '') {
                alert('Require a product price');
                valid - false;
            }else if (isNaN(price) || parseFloat(price) <= 0) {
                alert('Price must in the positive number');
                valid = false;
            } else if (image === '') {
                alert('Require a product image');
                valid = false;
            }
            return valid;
        }
    </script>
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
                    <a class="nav-link" href="delete_product.php">Delete Product</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="edit_product.php">Edit Product</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="view_products.php">View Product</a>
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
    <form method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        <div class="row">
            <div class="col-md-6 mb-3">
            <label class="form-label fw-bold" for="product_name">Product Name</label>
            <input type="text" class="form-control <?= $name_error ? 'is-invalid' : '' ?>" id="product_name" placeholder="Mie Ayam"
            value="<?= htmlspecialchars($product_name) ?>" name="product_name">
            <?php if ($name_error): ?>
                <div class="invalid-feedback"><?- $name_error ?></div>
            <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
            <label class="form-label fw-bold" for="price">Product Price</label>
            <input type="number" class="form-control <?= $price_error ? 'is-invalid' : '' ?>" id="price" placeholder="34.00"
            value="<?= htmlspecialchars($price) ?>" name="price">
            <?php if ($price_error): ?>
                <div class="invalid-feedback"><?- $price_error ?></div>
            <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
            <label class="form-label fw-bold" for="productImagePath">Put the Product Image</label>
            <input type="file" class="form-control <?= $image_error ? 'is-invalid' : '' ?>" id="productImagePath" name="image" accept=".jpg,.jpeg,.png,.gif">
            <div class="form-text">Max 2MB. Allowed: JPG, JPEG, PNG, GIF.</div>
            <?php if ($image_error): ?>
                <div class="invalid-feedback"><?- $image_error ?></div>
            <?php endif; ?>
            </div>
        </div>

        <div class="d-grip gap-2">
            <button type="submit" class="btn btn-secondary">
                <i class="bi bi-save"></i>Add Product
            </button>
            <a href="view_products.php" class="btn btn-outline-secondary">Go to View Products</a>
        </div>
    </form>
    </div>
    </div>
    </div>
</body>
</html>
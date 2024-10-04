<?php
include 'dbinit.php';

// Initialize variables for furniture details
$errors = [];
$successMessage = "";
$name = "";
$description = "";
$quantity = "";
$price = "";
$imagePath = "";
$furniture = null;

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture posted form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $quantity = trim($_POST['quantity']);
    $price = trim($_POST['price']);
    
    // Validate fields
    if (empty($name)) {
        $errors['name'] = 'Please enter a furniture name.';
    }
    if (empty($description)) {
        $errors['description'] = 'Please enter a description.';
    }
    if (empty($quantity) || !is_numeric($quantity) || $quantity < 0) {
        $errors['quantity'] = 'Please enter a valid quantity.';
    }
    if (empty($price) || !is_numeric($price) || $price < 0) {
        $errors['price'] = 'Please enter a valid price.';
    }

    // Handle file upload with validation
    if (isset($_FILES['furniturePhoto']) && $_FILES['furniturePhoto']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['furniturePhoto']['tmp_name'];
        $fileName = $_FILES['furniturePhoto']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Valid file extensions
        $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');

        if (in_array($fileExtension, $allowedfileExtensions)) {
            // file to store uploaded images
            $uploadFileDir = './uploaded_images/';
            // To avoid file traversal issues
            $dest_path = $uploadFileDir . basename($fileName); 

            // Move file to the specified folder
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $imagePath = $dest_path;
            } else {
                $errors['file_upload'] = 'There was an error moving the uploaded file.';
            }
        } else {
            $errors['file_upload'] = 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions);
        }
    } else {
        // If there's no ID, the image is required for new products
        if (empty($_POST['id'])) { 
            $errors['file_upload'] = 'Please add a product photo.';
        }
    }

    // Check if there are any validation errors
    if (empty($errors)) {
        try {
            // Insert or update based on if the ID exists
            if (isset($_POST['id']) && $_POST['id'] != "") {
                $id = $_POST['id'];
                $updateQuery = "UPDATE furniture SET FurnitureName = :name, FurnitureDescription = :description, QuantityAvailable = :quantity, Price = :price, ProductPhoto = :imagePath WHERE FurnitureID = :id";
                $stmt = $pdo->prepare($updateQuery);
                $stmt->execute(['name' => $name, 'description' => $description, 'quantity' => $quantity, 'price' => $price, 'imagePath' => $imagePath ?: null, 'id' => $id]);

                $successMessage = 'Product updated successfully.';
            } else {
                $insertQuery = "INSERT INTO furniture (FurnitureName, FurnitureDescription, QuantityAvailable, Price, ProductPhoto) VALUES (:name, :description, :quantity, :price, :imagePath)";
                $stmt = $pdo->prepare($insertQuery);
                $stmt->execute(['name' => $name, 'description' => $description, 'quantity' => $quantity, 'price' => $price, 'imagePath' => $imagePath ?: null]);

                $successMessage = 'Product added successfully.';
                // Clear form inputs after success
                $name = $description = $quantity = $price = "";
            }
        } catch (PDOException $e) {
            $errors['database'] = 'Database error: ' . $e->getMessage();
        }
    }
    // Check if we are in edit mode
} else if (isset($_GET['id'])) { 
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM furniture WHERE FurnitureID = :id");
    $stmt->execute(['id' => $id]);
    $furniture = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($furniture) {
        // Prepopulate fields with existing data
        $name = $furniture['FurnitureName'];
        $description = $furniture['FurnitureDescription'];
        $quantity = $furniture['QuantityAvailable'];
        $price = $furniture['Price'];
        $imagePath = $furniture['ProductPhoto'];
    } else {
        $errors['furniture'] = 'Furniture not found.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($furniture) ? 'Edit' : 'Add'; ?> Furniture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="display.php">HomeLuxe</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="display.php">View Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="addedit.php">Add Product</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1><?= isset($furniture) ? 'Edit' : 'Add'; ?> Furniture</h1>
    
    <!-- Display success message -->
    <?php if (!empty($successMessage)): ?>
        <div class="alert alert-success">
            <?= $successMessage; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="addedit.php" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= isset($furniture) ? $furniture['FurnitureID'] : ''; ?>">
        
        <div class="mb-3">
            <label for="name" class="form-label">Furniture Name</label>
            <input type="text" class="form-control" id="name" name="name" required value="<?= htmlspecialchars($name); ?>">
            <?php if (isset($errors['name'])): ?>
                <div class="text-danger"><?= $errors['name']; ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($description); ?></textarea>
            <?php if (isset($errors['description'])): ?>
                <div class="text-danger"><?= $errors['description']; ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity Available</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required value="<?= htmlspecialchars($quantity); ?>" min="0">
            <?php if (isset($errors['quantity'])): ?>
                <div class="text-danger"><?= $errors['quantity']; ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" required value="<?= htmlspecialchars($price); ?>" min="0" step="0.01">
            <?php if (isset($errors['price'])): ?>
                <div class="text-danger"><?= $errors['price']; ?></div>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label for="furniturePhoto" class="form-label">Furniture Photo</label>
            <input type="file" class="form-control" id="furniturePhoto" name="furniturePhoto" <?= !isset($furniture) ? 'required' : ''; ?>>
            <?php if (isset($errors['file_upload'])): ?>
                <div class="text-danger"><?= $errors['file_upload']; ?></div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary"><?= isset($furniture) ? 'Update' : 'Add'; ?> Furniture</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

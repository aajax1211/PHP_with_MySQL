<?php
include 'dbinit.php';

// Checking if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    // Add or update on bases of if the ID exists
    if (isset($_POST['id']) && $_POST['id'] != "") {
        $id = $_POST['id'];
        $updateQuery = "UPDATE furniture SET FurnitureName = :name, FurnitureDescription = :description, QuantityAvailable = :quantity, Price = :price WHERE FurnitureID = :id";
        $stmt = $pdo->prepare($updateQuery);
        $stmt->execute(['name' => $name, 'description' => $description, 'quantity' => $quantity, 'price' => $price, 'id' => $id]);
    } else {
        $insertQuery = "INSERT INTO furniture (FurnitureName, FurnitureDescription, QuantityAvailable, Price) VALUES (:name, :description, :quantity, :price)";
        $stmt = $pdo->prepare($insertQuery);
        $stmt->execute(['name' => $name, 'description' => $description, 'quantity' => $quantity, 'price' => $price]);
    }

    header("Location: display.php"); // Redirecting to display page after submission
}

// If editing, fetch product details
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM furniture WHERE FurnitureID = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id]);
    $furniture = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Edit Furniture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">HomeLuxe Admin</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="display.php">View Products</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="addedit.php">Add Product</a>
      </li>
    </ul>
  </div>
</nav>

    <div class="container mt-5">
        <h1><?= isset($furniture) ? 'Edit' : 'Add'; ?> Furniture</h1>
        <form method="POST" action="addedit.php">
            <input type="hidden" name="id" value="<?= isset($furniture) ? $furniture['FurnitureID'] : ''; ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Furniture Name</label>
                <input type="text" class="form-control" id="name" name="name" required value="<?= isset($furniture) ? $furniture['FurnitureName'] : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description"><?= isset($furniture) ? $furniture['FurnitureDescription'] : ''; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity Available</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required value="<?= isset($furniture) ? $furniture['QuantityAvailable'] : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="text" class="form-control" id="price" name="price" required value="<?= isset($furniture) ? $furniture['Price'] : ''; ?>">
            </div>
            <button type="submit" class="btn btn-success"><?= isset($furniture) ? 'Update' : 'Add'; ?> Furniture</button>
        </form>
    </div>
</body>
</html>

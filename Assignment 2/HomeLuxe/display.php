<?php
include 'dbinit.php';

// Fetching all products from the furniture table
$query = "SELECT * FROM furniture";
$stmt = $pdo->prepare($query);
$stmt->execute();
$furnitureItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomeLuxe Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" integrity="sha384-tViUnnbYAV00FLIhhi3v/dWt3Jxw4gZQcNoSCxCIFNJVCx7/D55/wXsrNIRANwdD" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <h1>HomeLuxe Furniture Admin Panel</h1>
        <a href="addedit.php" class="btn btn-primary mb-3">Add New Product</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Furniture ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Quantity Available</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($furnitureItems as $item): ?>
                <tr>
                    <td><?= $item['FurnitureID']; ?></td>
                    <td><?= $item['FurnitureName']; ?></td>
                    <td><?= $item['FurnitureDescription']; ?></td>
                    <td><?= $item['QuantityAvailable']; ?></td>
                    <td><?= $item['Price']; ?></td>
                    <td>
                        <a href="addedit.php?id=<?= $item['FurnitureID']; ?>" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="display.php?delete=<?= $item['FurnitureID']; ?>" class="btn btn-sm btn-danger">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php
// Deleting a product if 'delete' is set
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM furniture WHERE FurnitureID = :id";
    $stmt = $pdo->prepare($deleteQuery);
    $stmt->execute(['id' => $id]);
    header("Location: display.php");
}
?>
</body>
</html>

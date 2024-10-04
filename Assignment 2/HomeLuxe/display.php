<?php
include 'dbinit.php';

// Deleting a product if 'delete' is set
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $deleteQuery = "DELETE FROM furniture WHERE FurnitureID = :id";
    $stmt = $pdo->prepare($deleteQuery);
    $stmt->execute(['id' => $id]);
    header("Location: display.php");
    exit; // Ensure the script stops after the header redirect
}

// Initialize search variable
$search = '';

// Check if the search form is submitted
if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

// Prepare the query with search functionality
$query = "SELECT * FROM furniture WHERE FurnitureName LIKE :search";
$stmt = $pdo->prepare($query);
$stmt->execute(['search' => '%' . $search . '%']);
$furnitureItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomeLuxe Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="./style.css">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="display.php">HomeLuxe</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="display.php">View Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="addedit.php">Add Product</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1>HomeLuxe Furniture Admin Panel</h1>
    <a href="addedit.php" class="btn btn-primary mb-3">Add New Product</a>

    <!-- Search Form -->
    <form class="d-flex mb-3" method="GET" action="display.php">
        <input class="form-control me-2" type="search" name="search" placeholder="Search by Product Name" aria-label="Search" value="<?= htmlspecialchars($search); ?>">
        <button class="btn btn-outline-success" type="submit">Search</button>
    </form>

    <!-- Display products as cards -->
    <div class="row">
    <?php foreach ($furnitureItems as $item): ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm" style="border-radius: 15px; transition: transform 0.3s; overflow: hidden;">
                <img src="<?= $item['ProductPhoto'] ?: 'placeholder.png'; ?>" class="card-img-top" alt="Product Image" style="height: 200px; object-fit: cover; width:100%;">
                <div class="card-body">
                    <h5 class="card-title" style="font-weight: bold; color: #343a40;"><?= $item['FurnitureName']; ?></h5>
                    <p class="card-text" style="color: #6c757d;"><?= $item['FurnitureDescription']; ?></p>
                    <p style="font-style: italic; color: #868e96;">Added by: <?= $item['ProductAddedBy']; ?></p>
                    <p><strong style="color: #28a745;">Quantity:</strong> <?= $item['QuantityAvailable']; ?></p>
                    <p><strong style="color: #dc3545;">Price:</strong> $<?= number_format($item['Price'], 2); ?></p>
                    <div class="d-flex">
                        <a href="addedit.php?id=<?= $item['FurnitureID']; ?>" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <button class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $item['FurnitureID']; ?>)">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this product?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="#" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
function confirmDelete(id) {
    document.getElementById('confirmDeleteBtn').setAttribute('href', 'display.php?delete=' + id);
    var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>

</body>
</html>

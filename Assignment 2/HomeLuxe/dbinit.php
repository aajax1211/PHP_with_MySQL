<?php
// dbinit.php: Database connection and initialization

$host = "localhost";
$dbname = "homeluxe";
$username = "root";
$password = "Ajit@1997";

try {
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Creates database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    
    // Connecting to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    
    // Create furniture table if it doesn't exist
    $createTable = "
        CREATE TABLE IF NOT EXISTS furniture (
            FurnitureID INT AUTO_INCREMENT PRIMARY KEY,
            FurnitureName VARCHAR(255) NOT NULL,
            FurnitureDescription TEXT,
            QuantityAvailable INT NOT NULL,
            Price DECIMAL(10, 2) NOT NULL,
            ProductAddedBy VARCHAR(255) DEFAULT 'Ajit Behl'
        )";
    
    $pdo->exec($createTable);
    
    echo "Database and table initialized successfully.";
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

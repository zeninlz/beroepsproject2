<?php
session_start();

// Controleer of de gebruiker is ingelogd als beheerder (admin). Als niet, stuur ze naar het inlogscherm.
if (!isset($_SESSION['voornaam']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Inclusief het bestand "loggedin.php" om inlogstatus te behouden.
include("loggedin.php");
require('database.php');

// Query om het aantal gebruikers in de database op te halen.
$queryUserCount = "SELECT COUNT(*) AS user_count FROM klanten";
$stmtUserCount = $pdo->query($queryUserCount);
$userCount = $stmtUserCount->fetch(PDO::FETCH_ASSOC);

// Query om het aantal bestellingen in de database op te halen.
$queryOrderCount = "SELECT COUNT(*) AS order_count FROM bestellingen";
$stmtOrderCount = $pdo->query($queryOrderCount);
$orderCount = $stmtOrderCount->fetch(PDO::FETCH_ASSOC);

// Query om het totale inkomen uit bestellingen op te halen.
$queryTotalEarnings = "SELECT SUM(total_amount) AS total_earnings FROM bestellingen";
$stmtTotalEarnings = $pdo->query($queryTotalEarnings);
$totalEarnings = $stmtTotalEarnings->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<nav class="navbar">
    <div class="form">
        <h1>Welcome, <?php echo $_SESSION['voornaam']; ?>!</h1>
        <h3><a href="logout.php">Logout</a></h3>
        <a href="home.php">Back To Home</a>
    </div>
</nav>

<section class="product-management">
<div class="live-info" onclick="window.location.href='admin_users.php'">
    <h3>Users: </h3>
    <p><?php echo $userCount['user_count']; ?></p>
</div>

<div class="live-info" onclick="window.location.href='admin_view_orders.php'">
    <h3>Orders: </h3>
    <p><?php echo $orderCount['order_count']; ?></p>
</div>

<div class="live-info">
    <h3>Total Earnings: </h3>
    <p>$<?php echo number_format($totalEarnings['total_earnings'], 2); ?></p>
</div>

<div class="product-form">
    <h2>Add New Product</h2>
    <form action="admin_add_product.php" method="post">
    <div class="form-group">
        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" required>
    </div>
    <div class="form-group">
        <label for="product_price">Product Price:</label>
        <input type="number" id="product_price" name="product_price" step="0.01" required>
    </div>
    <div class="form-group">
        <label for="product_description">Product Description:</label>
        <textarea id="product_description" name="product_description" rows="4"></textarea>
    </div>
    <div class="form-group">
        <input type="submit" name="add_product" value="Add Product" class="submit-button">
    </div>
    </form>
</div>
</section>

<h2>Existing Products</h2>
<table border="1">
    <thead>
    <tr>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Product Price</th>
        <th>Product Description</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
    <?php
    // Query om bestaande producten op te halen.
    $query = "SELECT * FROM `producten`";
    $stmt = $pdo->query($query);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['producten_id'] . "</td>";
        echo "<td>" . $row['naam'] . "</td>";
        echo "<td>" . $row['prijs'] . "</td>";
        echo "<td>" . $row['beschrijving'] . "</td>";
        echo "<td><a href='edit_product.php?id=" . $row['producten_id'] . "'>Edit</a></td>";
        echo "<td><a href='delete_product.php?id=" . $row['producten_id'] . "'>Delete</a></td>";
        echo "</tr>";
    }
    ?>
    </tbody>
</table>
</body>
</html>

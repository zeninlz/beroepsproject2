<?php
session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['voornaam'])) {
    header("Location: login.php");
    exit();
}

// Controleer of 'order_id' is ingesteld en een geldig nummer is
if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    header("Location: home.php");
    exit();
}

// Inclusie van de databaseconfiguratie
require('database.php');

// Haal gebruikersinformatie en bestellingdetails op
$userEmail = $_SESSION['email'];
$order_id = $_GET['order_id'];

$query = "SELECT b.*, u.voornaam, u.achternaam, u.email, u.adres, bp.producten_id, p.naam, p.prijs, bp.quantity
          FROM bestellingen AS b
          INNER JOIN bestellingen_producten AS bp ON b.bestellingen_id = bp.bestellingen_id
          INNER JOIN producten AS p ON bp.producten_id = p.producten_id
          INNER JOIN klanten AS u ON b.email = u.email
          WHERE b.email = :email AND b.bestellingen_id = :order_id";

$stmt = $pdo->prepare($query);
$stmt->bindParam(':email', $userEmail);
$stmt->bindParam(':order_id', $order_id);
$stmt->execute();
$orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Als er geen bestelgegevens zijn gevonden, ga terug naar de startpagina
if (empty($orderDetails)) {
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="order_confirmation.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Order Confirmation</h1>
            <div class="form">
                <!-- Hyperlinks naar Home en Logout -->
                <h3><a href="home.php">Home</a></h3>
                <h3><a id="logout" href="logout.php">Logout</a></h3>
            </div>
        </header>

        <h2>Your Order Details</h2>
        <!-- Toon bestelgegevens -->
        <p><strong>Order ID:</strong> <?= $order_id ?></p>
        <p><strong>First Name:</strong> <?= $orderDetails[0]['voornaam'] ?></p>
        <p><strong>Last Name:</strong> <?= $orderDetails[0]['achternaam'] ?></p>
        <p><strong>Email:</strong> <?= $orderDetails[0]['email'] ?></p>
        <p><strong>Adres:</strong> <?= $orderDetails[0]['adres'] ?></p>
        <table class="order-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loop door bestelde items -->
                <?php foreach ($orderDetails as $item): ?>
                    <tr>
                        <td><?= $item['naam'] ?></td>
                        <td>$<?= $item['prijs'] ?></td>
                        <td><?= $item['quantity'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Bereken en toon het totaalbedrag van de bestelling -->
        <p><strong>Total Amount:</strong> $<?= number_format(array_sum(array_column($orderDetails, 'prijs')), 2) ?></p>

        <p>Thank you for your order! You will receive an email confirmation shortly.</p>
        <h2><a href="receipt.php">View Order History</a></h2>
    </div>
</body>
</html>

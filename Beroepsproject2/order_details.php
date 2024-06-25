<?php
session_start(); // Start de PHP-sessie om sessievariabelen te gebruiken.

if (!isset($_SESSION['voornaam'])) { // Controleert of de gebruiker is ingelogd. Als niet, stuur ze naar het inlogscherm.
    header("Location: login.php"); // Redirect naar login.php.
    exit(); // Beëindigt de verdere uitvoering van het script.
}

require('database.php'); // Inclusie van een PHP-bestand om verbinding te maken met de database.

$userEmail = $_SESSION['email']; // Haalt het e-mailadres van de ingelogde gebruiker op.

if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) { // Controleert of een geldig bestellings-ID in de querystring aanwezig is.
    header("Location: receipt.php"); // Redirect naar de bestellingsgeschiedenispagina.
    exit(); // Beëindigt de verdere uitvoering van het script.
}

$order_id = $_GET['order_id']; // Haalt het bestellings-ID op uit de querystring.

$query = "SELECT b.*, bp.producten_id, p.naam, p.prijs, bp.quantity
          FROM bestellingen AS b
          INNER JOIN bestellingen_producten AS bp ON b.bestellingen_id = bp.bestellingen_id
          INNER JOIN producten AS p ON bp.producten_id = p.producten_id
          WHERE b.email = :email AND b.bestellingen_id = :order_id";
// SQL-query om bestelinformatie op te halen, inclusief productdetails.

$stmt = $pdo->prepare($query); // Bereidt de SQL-query voor.
$stmt->bindParam(':email', $userEmail); // Bindt het e-mailadres aan de query.
$stmt->bindParam(':order_id', $order_id); // Bindt het bestellings-ID aan de query.
$stmt->execute(); // Voert de query uit om bestelinformatie op te halen.
$orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC); // Slaat de bestelinformatie op in een array.

if (empty($orderDetails)) { // Als er geen bestelinformatie is gevonden.
    header("Location: receipt.php"); // Redirect naar de bestellingsgeschiedenispagina.
    exit(); // Beëindigt de verdere uitvoering van het script.
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Details</title>
    <link rel="stylesheet" href="order_details.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Order Details</h1>
            <div class="form">
                <h3><a href="receipt.php">Back to Order History</a></h3>
                <h3><a id="logout" href="logout.php">Logout</a></h3>
            </div>
        </header>

        <h2>Order ID: <?= $order_id ?></h2> <!-- Toont het bestellings-ID op de pagina. -->
        <table class="order-details">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orderDetails as $item): ?> <!-- Voor elk item in de bestelling. -->
                    <tr>
                        <td><?= $item['naam'] ?></td> <!-- Toont de productnaam. -->
                        <td>$<?= $item['prijs'] ?></td> <!-- Toont de productprijs. -->
                        <td><?= $item['quantity'] ?></td> <!-- Toont de hoeveelheid van het product in de bestelling. -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p><strong>Total Amount:</strong> $<?= number_format(array_sum(array_column($orderDetails, 'prijs')), 2) ?></p>
        <!-- Berekent en toont het totaalbedrag van de bestelling. -->
    </div>
</body>
</html>

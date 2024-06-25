<?php
session_start(); // Start de PHP-sessie om sessievariabelen te gebruiken.

if (!isset($_SESSION['voornaam'])) { // Controleert of de gebruiker is ingelogd. Als niet, stuur ze naar het inlogscherm.
    header("Location: login.php"); // Redirect naar login.php.
    exit(); // Beëindigt de verdere uitvoering van het script.
}

require('database.php'); // Inclusie van een PHP-bestand om verbinding te maken met de database.

$userEmail = $_SESSION['email']; // Haalt het e-mailadres van de ingelogde gebruiker op.

$query = "SELECT bestellingen_id, datum, total_amount FROM bestellingen WHERE email = :email"; // SQL-query om bestelgeschiedenis op te halen.
$stmt = $pdo->prepare($query); // Bereidt de SQL-query voor.
$stmt->bindParam(':email', $userEmail); // Bindt het e-mailadres als parameter.
$stmt->execute(); // Voert de query uit en haalt de bestellingen op.
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC); // Slaat de bestellingen op in een associatieve array.
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"> <!-- Charset-instelling voor de tekstcodering. -->
    <title>Order History</title> <!-- De titel van de webpagina. -->
    <link rel="stylesheet" href="receipt.css"> <!-- Koppeling naar een externe CSS-stijlblad. -->
</head>
<body>
    <div class="container">
        <header>
            <h1>Order History</h1>
            <div class="form">
                <h3><a href="home.php">Home</a></h3> <!-- Koppeling naar de startpagina. -->
                <h3><a id="logout" href="logout.php">Logout</a></h3> <!-- Koppeling om uit te loggen. -->
            </div>
        </header>

        <h2>Your Order History</h2>

        <?php if (!empty($orders)): ?> <!-- Controleert of er bestellingen zijn. -->
            <table class="order-history"> <!-- Tabel voor het weergeven van bestellingen. -->
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Order Date</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?> <!-- Voor elke bestelling in de lijst. -->
                        <tr>
                            <td><?= $order['bestellingen_id'] ?></td> <!-- Toont het bestelnummer. -->
                            <td><?= $order['datum'] ?></td> <!-- Toont de besteldatum. -->
                            <td>$<?= number_format($order['total_amount'], 2) ?></td> <!-- Toont het totale bedrag van de bestelling. -->
                            <td><a href="order_details.php?order_id=<?= $order['bestellingen_id'] ?>">View Details</a></td> <!-- Koppeling om details van de bestelling te bekijken. -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You haven't placed any orders yet.</p> <!-- Bericht als er geen bestellingen zijn. -->
        <?php endif; ?>
    </div>
</body>
</html>

<?php
// Start de PHP-sessie
session_start();

// Controleer of de gebruiker is ingelogd, anders stuur ze naar de inlogpagina en stop de uitvoering van de code
if (!isset($_SESSION['voornaam'])) {
    header("Location: login.php");
    exit();
}

// Inclusie van het databasebestand
require('database.php');

$userEmail = $_SESSION['email'];
$cartItems = [];
$totalAmount = 0;

// Controleer of er items in de winkelwagen zitten
if (!empty($_SESSION['cart'])) {
    // Haal de product-IDs op uit de winkelwagen
    $cartItemIds = array_keys($_SESSION['cart']);
    $cartItemIdsString = implode(',', $cartItemIds);

    // Haal de productgegevens op vanuit de database op basis van de product-IDs
    $query = "SELECT producten_id, naam, prijs FROM producten WHERE producten_id IN ($cartItemIdsString)";
    $stmt = $pdo->query($query);

    if ($stmt) {
        // Verzamel de productgegevens en bereken het totaalbedrag van de bestelling
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($cartItems as &$item) {
            $itemId = $item['producten_id'];
            $item['quantity'] = $_SESSION['cart'][$itemId];
            $itemTotal = $item['prijs'] * $item['quantity'];
            $totalAmount += $itemTotal;
        }
        unset($item);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="checkout.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Checkout</h1>
            <div class="form">
                <h3><a href="cart.php">Back to Cart</a></h3>
                <h3><a href="home.php">Home</a></h3>
                <h3><a id="logout" href="logout.php">Logout</a></h3>
            </div>
        </header>

        <h2>Your Order Summary</h2>
        <?php if (!empty($cartItems)): ?>
            <!-- Tabel met bestellingsinformatie -->
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td><?= $item['naam'] ?></td>
                            <td>$<?= $item['prijs'] ?></td>
                            <td><?= $item['quantity'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">Total Amount:</th>
                        <th>$<?= number_format($totalAmount, 2) ?></th>
                    </tr>
                </tfoot>
            </table>

            <!-- Formulier om de bestelling te plaatsen -->
            <form method="post" action="place_order.php">
                <button type="submit" name="place_order">Place Order</button>
            </form>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
</body>
</html>

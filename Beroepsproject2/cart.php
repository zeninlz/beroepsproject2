<?php
session_start();

// Controleer of de gebruiker is ingelogd. Als niet, stuur ze naar het inlogscherm.
if (!isset($_SESSION['voornaam'])) {
    header("Location: login.php");
    exit();
}

require('database.php');

$userEmail = $_SESSION['email'];
$cartItems = []; // Een lege array om winkelwagenitems bij te houden.
$totalAmount = 0; // Houdt het totale bedrag van de bestelling bij.

// Verwerk de POST-verzoeken, zoals het toevoegen en verwijderen van items uit de winkelwagen.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $productId = $_POST['product_id'];

        // Controleer of de winkelwagen in de sessie-array bestaat, anders maak deze aan.
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Verhoog de hoeveelheid van een product in de winkelwagen of voeg het toe.
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]++;
        } else {
            $_SESSION['cart'][$productId] = 1;
        }
    } elseif (isset($_POST['remove_from_cart'])) {
        $productId = $_POST['product_id'];

        // Controleer of het product in de winkelwagen bestaat en verminder de hoeveelheid of verwijder het.
        if (isset($_SESSION['cart'][$productId])) {
            if ($_SESSION['cart'][$productId] > 1) {
                $_SESSION['cart'][$productId]--;
            } else {
                unset($_SESSION['cart'][$productId]);
            }
        }
    }
}

// Haal productinformatie op voor items in de winkelwagen.
if (!empty($_SESSION['cart'])) {
    $cartItemIds = array_keys($_SESSION['cart']);
    $cartItemIdsString = implode(',', $cartItemIds);

    $query = "SELECT producten_id, naam, prijs FROM producten WHERE producten_id IN ($cartItemIdsString)";
    $stmt = $pdo->query($query);

    if ($stmt) {
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Voor elk item in de winkelwagen, bereken de hoeveelheid en het totale bedrag.
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
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="cart.css">
</head>
<body>
    <div class="container">
        <header>
            <a href="cart.php">
                <img src="bag-check-fill.svg" alt="Shopping Cart" width="60" height="60">
                <?php
                // Toon het aantal items in de winkelwagen in de navigatiebalk.
                if (!empty($_SESSION['cart'])) {
                    $cartItemCount = array_sum($_SESSION['cart']);
                    echo "<span class='cart-count'>$cartItemCount</span>";
                }
                ?>
            </a>
            <h3><a href="receipt.php">Orders</a></h3>
            <h3><a href="home.php">Home</a></h3>
            <div class="top-bar">
                <div class="form">
                    <h1>Hey, <?php echo $_SESSION['voornaam']; ?>!</h1>
                    <h3><a id="logout" href="logout.php">Logout</a></h3>
                </div>
            </div>
        </header>

        <h1>Your Shopping Cart</h1>
        <?php if (!empty($cartItems)): ?>
            <div class="cart-container">
                <?php foreach ($cartItems as $item): ?>
                    <div class="cart-item">
                        <span class="cart-item-name"><?= $item['naam'] ?></span>
                        <span class="cart-item-price">$<?= $item['prijs'] ?></span>
                        <span class="cart-item-quantity">Quantity: <?= $item['quantity'] ?></span>
                        <form method="post">
                            <input type="hidden" name="product_id" value="<?= $item['producten_id'] ?>">
                            <button type="submit" name="remove_from_cart">Remove</button>
                        </form>
                    </div>
                <?php endforeach; ?>
                <div class="cart-total">
                    <strong>Total Amount:</strong> $<?= number_format($totalAmount, 2) ?>
                </div>
                <form method="post" action="place_order.php">
                    <button type="submit" name="place_order">Place Order</button>
                </form>
            </div>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
session_start(); // Start de PHP-sessie om sessievariabelen te gebruiken.

if (!isset($_SESSION['voornaam'])) { // Controleert of de gebruiker is ingelogd. Als niet, stuur ze naar het inlogscherm.
    header("Location: login.php"); // Redirect naar login.php.
    exit(); // Beëindigt de verdere uitvoering van het script.
}

require('database.php'); // Inclusie van een PHP-bestand om verbinding te maken met de database.

$userEmail = $_SESSION['email']; // Haalt het e-mailadres van de ingelogde gebruiker op.
$cartItems = []; // Initialiseert een lege array voor winkelwagenitems.

if (empty($_SESSION['cart'])) { // Controleert of de winkelwagen leeg is. Als wel, stuur ze naar de winkelwagenpagina.
    header("Location: cart.php"); // Redirect naar cart.php.
    exit(); // Beëindigt de verdere uitvoering van het script.
}

$totalAmount = 0; // Initialiseert de totale kosten op nul.
$cartItemIds = array_keys($_SESSION['cart']); // Haalt de product-ID's op uit de sessie-winkelwagen.
$cartItemIdsString = implode(',', $cartItemIds); // Converteert de product-ID's naar een door komma's gescheiden string.

$query = "SELECT producten_id, naam, prijs FROM producten WHERE producten_id IN ($cartItemIdsString)"; // SQL-query om productinformatie op te halen.
$stmt = $pdo->query($query); // Voert de query uit.

if ($stmt) { // Als de query succesvol is uitgevoerd.
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC); // Haalt productinformatie op en slaat deze op in de $cartItems-array.

    foreach ($cartItems as $item) { // Voor elk item in de winkelwagen.
        $itemId = $item['producten_id']; // Haalt de product-ID op.
        $itemTotal = $item['prijs'] * $_SESSION['cart'][$itemId]; // Berekent de totale kosten voor dat item.
        $totalAmount += $itemTotal; // Voegt de kosten van het huidige item toe aan de totale kosten.
    }
}

$insertOrderQuery = "INSERT INTO bestellingen (email, datum, total_amount) VALUES (:email, NOW(), :totalAmount)"; // SQL-query voor het invoegen van een nieuwe bestelling.
$stmtInsertOrder = $pdo->prepare($insertOrderQuery); // Bereidt de SQL-query voor.

$stmtInsertOrder->bindParam(':email', $userEmail); // Bindt het e-mailadres aan de query.
$stmtInsertOrder->bindParam(':totalAmount', $totalAmount); // Bindt de totale kosten aan de query.

if ($stmtInsertOrder->execute()) { // Als de bestelling succesvol is geplaatst.
    $orderId = $pdo->lastInsertId(); // Haalt het ID van de laatst ingevoegde bestelling op.

    foreach ($cartItems as $item) { // Voor elk item in de winkelwagen.
        $productId = $item['producten_id']; // Haalt de product-ID op.
        $quantity = $_SESSION['cart'][$productId]; // Haalt de hoeveelheid van het huidige item uit de sessie.

        $insertOrderItemQuery = "INSERT INTO bestellingen_producten (bestellingen_id, producten_id, quantity) VALUES (:orderId, :productId, :quantity)"; // SQL-query voor het invoegen van bestelinformatie.
        $stmtInsertOrderItem = $pdo->prepare($insertOrderItemQuery); // Bereidt de SQL-query voor.

        $stmtInsertOrderItem->bindParam(':orderId', $orderId); // Bindt het bestellings-ID aan de query.
        $stmtInsertOrderItem->bindParam(':productId', $productId); // Bindt de product-ID aan de query.
        $stmtInsertOrderItem->bindParam(':quantity', $quantity); // Bindt de hoeveelheid aan de query.

        $stmtInsertOrderItem->execute(); // Voert de query uit om de bestelinformatie in te voegen.
    }

    unset($_SESSION['cart']); // Verwijdert de winkelwagen uit de sessie, aangezien de bestelling is geplaatst.

    header("Location: order_confirmation.php?order_id=$orderId"); // Redirect naar de bevestigingspagina met het bestellings-ID.
    exit(); // Beëindigt de verdere uitvoering van het script.
} else {
    echo "Error placing the order."; // Toont een foutmelding als de bestelling niet kan worden geplaatst.
}
?>

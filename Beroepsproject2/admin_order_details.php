<?php
session_start(); // Start de sessie om de gebruikerssessie te initialiseren.

if (!isset($_SESSION['voornaam']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Als de gebruiker niet is ingelogd of geen beheerder is, stuur ze naar het inlogscherm.
    exit(); // Beëindig de scriptuitvoering.
}

require('database.php'); // Laad het bestand 'database.php' om de databaseverbinding in te stellen.

if (isset($_GET['id'])) { // Controleer of er een 'id'-parameter in de URL aanwezig is.
    $orderID = $_GET['id']; // Haal de waarde van 'id' op uit de URL.

    // Haal de orderinformatie op en de bijbehorende gebruikersinformatie op basis van de order-ID.
    $orderQuery = "SELECT b.*, k.voornaam AS user_firstname, k.achternaam AS user_lastname, k.adres AS user_address
                   FROM `bestellingen` AS b
                   JOIN `klanten` AS k ON b.email = k.email
                   WHERE b.bestellingen_id=?";
    $orderStmt = $pdo->prepare($orderQuery);
    $orderStmt->execute([$orderID]);

    $order = $orderStmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo "Order not found."; // Als er geen order wordt gevonden, geef een foutmelding weer.
        exit(); // Beëindig de scriptuitvoering.
    }

    // Haal de items van de order op en bijbehorende productinformatie op basis van de order-ID.
    $orderItemsQuery = "SELECT p.naam AS product_name, p.prijs AS product_price, bp.quantity AS quantity
                        FROM `bestellingen_producten` AS bp
                        JOIN `producten` AS p ON bp.producten_id = p.producten_id
                        WHERE bp.bestellingen_id=?";
    $orderItemsStmt = $pdo->prepare($orderItemsQuery);
    $orderItemsStmt->execute([$orderID]);

    $orderItems = $orderItemsStmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "Order ID not specified."; // Als er geen 'id'-parameter in de URL aanwezig is, geef een foutmelding weer.
    exit(); // Beëindig de scriptuitvoering.
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Details</title>
    <link rel="stylesheet" href="admin_order_details.css"> <!-- Koppel een CSS-bestand voor opmaak. -->
</head>
<body>
    <div class="form">
        <h3>Welcome, <?php echo $_SESSION['voornaam']; ?>!</h3> <!-- Begroet de ingelogde gebruiker. -->
        <h3><a id="logout" href="logout.php">Logout</a></h3> <!-- Bied een link om uit te loggen. -->
        <a href="admin_view_orders.php">Terug naar orders</a> <!-- Bied een link om terug te gaan naar de lijst met orders. -->
    </div>

    <h2>Order Details</h2>

    <table border="1">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User Email</th>
                <th>User First Name</th> 
                <th>User Last Name</th>
                <th>User Address</th>
                <th>Date</th>
                <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $order['bestellingen_id']; ?></td> <!-- Toon het order-ID. -->
                <td><?php echo $order['email']; ?></td> <!-- Toon het e-mailadres van de gebruiker. -->
                <td><?php echo $order['user_firstname']; ?></td> <!-- Toon de voornaam van de gebruiker. -->
                <td><?php echo $order['user_lastname']; ?></td> <!-- Toon de achternaam van de gebruiker. -->
                <td><?php echo $order['user_address']; ?></td> <!-- Toon het adres van de gebruiker. -->
                <td><?php echo $order['datum']; ?></td> <!-- Toon de datum van de order. -->
                <td>$<?php echo number_format($order['total_amount'], 2); ?></td> <!-- Toon het totale bedrag in het juiste formaat. -->
            </tr>
        </tbody>
    </table>

    <h3>Order Items</h3>
    <?php
    if (count($orderItems) > 0) { // Controleer of er items zijn in de order.
        echo "<table border='1'>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Product Name</th>";
        echo "<th>Product Price</th>";
        echo "<th>Quantity</th>";
        echo "<th>Total Price</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        foreach ($orderItems as $item) { // Loop door de items en toon de productinformatie.
            $totalPrice = $item['product_price'] * $item['quantity'];

            echo "<tr>";
            echo "<td>{$item['product_name']}</td>";
            echo "<td>$" . number_format($item['product_price'], 2) . "</td>";
            echo "<td>{$item['quantity']}</td>";
            echo "<td>$" . number_format($totalPrice, 2) . "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "No items found for this order."; // Als er geen items zijn, geef een melding weer.
    }
    ?>
</body>
</html>

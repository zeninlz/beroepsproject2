<?php
session_start(); // Start de PHP-sessie of hervat een bestaande sessie.

// Controleer of de gebruiker is ingelogd en een 'admin'-rol heeft, zo niet, stuur ze naar het inlogscherm.
if (!isset($_SESSION['voornaam']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require('database.php'); // Laad het databasebestand in met de databaseverbinding.

$query = "SELECT * FROM `bestellingen` ORDER BY bestellingen_id DESC"; // Bereid een SQL-query voor om bestellingen op te halen en sorteert ze op bestellingen_id in aflopende volgorde.
$stmt = $pdo->query($query); // Voert de query uit en bewaart het resultaat in de $stmt variabele.

if ($stmt->rowCount() === 0) {
    $noOrdersMessage = "No orders have been placed."; // Als er geen bestellingen zijn, stel een bericht in dat er geen bestellingen zijn.
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>View Orders</title>
    <link rel="stylesheet" href="admin_view_orders.css"> <!-- Laad de stijlinformatie voor deze pagina. -->
</head>
<body>
    <div class="form">
        <h3>Welcome, <?php echo $_SESSION['voornaam']; ?>!</h3> <!-- Toon de naam van de ingelogde gebruiker. -->
        <h3><a id="logout" href="logout.php">Logout</a></h3> <!-- Bied een link aan om uit te loggen. -->
        <a href="admin.php">Terug naar dashboard</a> <!-- Bied een link om terug te gaan naar het admin-dashboard. -->
    </div>

    <h2>View Orders</h2> <!-- Tussenkop voor het weergeven van bestellingen. -->

    <?php
    if (isset($noOrdersMessage)) {
        echo "<p class='no-orders-message'>$noOrdersMessage</p>"; // Toon een bericht als er geen bestellingen zijn.
    } else {
        echo "<table border='1'>"; // Begin met het opmaken van een HTML-tabel voor het weergeven van bestellingen.
        echo "<thead>"; // Begin van de tabelkop.
        echo "<tr>"; // Begin van een rij in de tabelkop.
        echo "<th>Order ID</th>"; // Eerste kolomkop: Order ID.
        echo "<th>User Email</th>"; // Tweede kolomkop: Gebruikers-e-mail.
        echo "<th>Date</th>"; // Derde kolomkop: Datum.
        echo "<th>Total Amount</th>"; // Vierde kolomkop: Totale bedrag.
        echo "<th>View Details</th>"; // Vijfde kolomkop: Link om details te bekijken.
        echo "</tr>"; // Sluit de rij in de tabelkop.
        echo "</thead>"; // Sluit de tabelkop.
        echo "<tbody>"; // Begin van de tabelinhoud.

        // Loop door de resultaten van de databasequery en toon elke bestelling in een rij van de tabel.
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>"; // Begin een rij in de tabelinhoud.
            echo "<td>" . $row['bestellingen_id'] . "</td>"; // Toon de bestel-ID.
            echo "<td>" . $row['email'] . "</td>"; // Toon de gebruikers-e-mail.
            echo "<td>" . $row['datum'] . "</td>"; // Toon de datum.
            echo "<td>$" . number_format($row['total_amount'], 2) . "</td>"; // Toon het totale bedrag in dollars met 2 decimalen.
            echo "<td><a href='admin_order_details.php?id=" . $row['bestellingen_id'] . "'>View Details</a></td>"; // Bied een link om besteldetails te bekijken.
            echo "</tr>"; // Sluit de rij in de tabelinhoud.
        }

        echo "</tbody>"; // Sluit de tabelinhoud.
        echo "</table>"; // Sluit de HTML-tabel voor het weergeven van bestellingen.
    }
    ?>

</body>
</html>

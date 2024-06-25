<?php
session_start(); // Start een PHP-sessie om sessievariabelen te kunnen gebruiken.

if (!isset($_SESSION['voornaam']) || $_SESSION['role'] !== 'admin') {
    // Controleer of de gebruiker is ingelogd en een beheerder is. Zo niet, stuur ze naar het inlogscherm.
    header("Location: login.php");
    exit();
}

require('database.php'); // Inclusief het bestand 'database.php' om toegang te krijgen tot de database.

if (isset($_GET['edit_user_email'])) {
    // Als 'edit_user_email' in de URL is ingesteld, stuur de gebruiker door naar de bewerkingspagina voor dat e-mailadres.
    header("Location: admin_edit_user.php?email=" . $_GET['edit_user_email']);
    exit();
} elseif (isset($_GET['delete_user_email'])) {
    // Als 'delete_user_email' in de URL is ingesteld, stuur de gebruiker door naar de verwijderingspagina voor dat e-mailadres.
    header("Location: admin_delete_user.php?email=" . $_GET['delete_user_email']);
    exit();
}

$query = "SELECT * FROM `klanten`"; // Een SQL-query om alle klantgegevens op te halen.
$stmt = $pdo->query($query); // Voer de query uit met de databaseverbinding en sla het resultaat op in $stmt.
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Users</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="form">
        <h3>Welcome, <?php echo $_SESSION['voornaam']; ?>!</h3>
        <!-- Toon een welkomstbericht met de naam van de ingelogde gebruiker. -->
        <h3><a id="logout" href="logout.php">Logout</a></h3>
        <!-- Bied een koppeling om uit te loggen naar 'logout.php'. -->
        <a href="admin.php">Terug naar Dashboard</a>
        <!-- Bied een koppeling om terug te keren naar het dashboard. -->
    </div>

    <h2>User Management</h2>
    <table border="1">
    <thead>
        <tr>
            <th>User ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Adres</th>
            <th>Phone number</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Loop door de resultaten van de databasequery en toon de gegevens in een tabel.
            echo "<tr>";
            echo "<td>" . $row['voornaam'] . "</td>"; // Toon de voornaam.
            echo "<td>" . $row['achternaam'] . "</td>"; // Toon de achternaam.
            echo "<td>" . $row['email'] . "</td>"; // Toon het e-mailadres.
            echo "<td>" . $row['adres'] . "</td>"; // Toon het adres.
            echo "<td>" . $row['telefoonnummer'] . "</td>";
            echo "<td><a href='admin_users.php?edit_user_email=" . $row['email'] . "'>Edit</a></td>";
            // Voeg een bewerkingskoppeling toe voor de gebruiker.
            echo "<td><a href='admin_delete_user.php?delete_user_email=" . $row['email'] . "'>Delete</a></td>";
            // Voeg een verwijderingskoppeling toe voor de gebruiker.
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>

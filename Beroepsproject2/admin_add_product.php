<?php
session_start(); // Start een PHP-sessie om sessievariabelen te kunnen gebruiken.

if (!isset($_SESSION['voornaam']) || $_SESSION['role'] !== 'admin') {
    // Controleer of de gebruiker is ingelogd en of hun rol 'admin' is. Zo niet, stuur ze naar het inlogscherm.
    header("Location: login.php");
    exit(); // Stop de verdere uitvoering van de code om ongeautoriseerde toegang te voorkomen.
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Controleer of het verzoek een HTTP POST-verzoek is, wat betekent dat er gegevens worden verzonden via een formulier.

    require('database.php'); // Laad het databasebestand in om een databaseverbinding tot stand te brengen.

    $product_name = $_POST['product_name']; // Haal de productnaam op uit het POST-verzoek.
    $product_price = $_POST['product_price']; // Haal de productprijs op uit het POST-verzoek.
    $product_description = $_POST['product_description']; // Haal de productomschrijving op uit het POST-verzoek.

    $query = "INSERT INTO producten (naam, prijs, beschrijving) VALUES (?, ?, ?)"; // Bereid een SQL-query voor om een nieuw product in de database in te voegen.
    $stmt = $pdo->prepare($query); // Bereid de SQL-query voor gebruik.

    if ($stmt->execute([$product_name, $product_price, $product_description])) {
        // Voer de SQL-query uit met de ingevulde gegevens. Als de uitvoering slaagt, wordt de gebruiker doorgestuurd naar de beheerderspagina.
        header("Location: admin.php");
        exit();
    } else {
        // Als de uitvoering van de SQL-query mislukt, toon een foutmelding.
        echo "Er is een fout opgetreden bij het toevoegen van het product.";
    }
}
?>

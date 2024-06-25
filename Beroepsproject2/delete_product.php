<?php
// Start de PHP-sessie om gebruikersinformatie op te slaan
session_start();

// Controleer of de gebruiker is ingelogd als admin, anders stuur ze naar de inlogpagina
if (!isset($_SESSION['voornaam']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Vereis de database.php om de databaseverbinding te verkrijgen
require('database.php');

// Controleer of een "id" parameter is ingesteld in het GET-verzoek
if (isset($_GET['id'])) {
    $productID = $_GET['id'];

    // Controleer of het product met het opgegeven ID bestaat in de database
    $checkQuery = "SELECT * FROM `producten` WHERE producten_id=?";
    $stmtCheck = $pdo->prepare($checkQuery);
    $stmtCheck->execute([$productID]);

    // Als het product bestaat, verwijder het uit de database
    if ($stmtCheck->rowCount() > 0) {
        $deleteQuery = "DELETE FROM `producten` WHERE producten_id=?";
        $stmtDelete = $pdo->prepare($deleteQuery);
        $stmtDelete->execute([$productID]);

        // Stuur de gebruiker terug naar het admin-dashboard na het verwijderen
        header("Location: admin.php");
        exit();
    } else {
        // Als het product niet wordt gevonden, toon een foutmelding
        echo "Product not found.";
        exit();
    }
} else {
    // Als er geen "id" parameter in het GET-verzoek is opgegeven, toon een foutmelding
    echo "Product ID not specified.";
    exit();
}
?>

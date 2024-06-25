<?php
// Controleer of de PHP-sessie al gestart is; zo niet, start deze.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Controleer of de POST-variabelen 'add_to_cart' en 'product_id' zijn ingesteld.
if (isset($_POST['add_to_cart']) && isset($_POST['product_id'])) {
    // Haal de product-ID op uit de POST-gegevens.
    $productID = $_POST['product_id'];

    // Controleer of de 'cart'-sessievariabele al bestaat; zo niet, maak een lege array.
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Controleer of het product al in het winkelwagentje zit.
    if (isset($_SESSION['cart'][$productID])) {
        // Als het product al in het winkelwagentje zit, verhoog het aantal met 1.
        $_SESSION['cart'][$productID]++;
    } else {
        // Als het product nog niet in het winkelwagentje zit, voeg het toe met een aantal van 1.
        $_SESSION['cart'][$productID] = 1;
    }
}

// Stuur de gebruiker terug naar de vorige pagina (de pagina waar de aanvraag vandaan kwam).
header("Location: " . $_SERVER["HTTP_REFERER"]);

// Stop de verdere uitvoering van het script.
exit();
?>

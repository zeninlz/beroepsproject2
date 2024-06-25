<?php
session_start(); // sessie start

if (!isset($_SESSION['voornaam'])) {
    header("Location: login.php"); // Als de sessie variabele 'voornaam' bestaat, stuur de gebruiker naar home.php en stop de script uitvoering.
    exit();
}
// verwijder uit de cart waarvan het product_id is
if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
    $productId = $_GET['product_id'];

    if (isset($_SESSION['cart'][$productId])) {
        unset($_SESSION['cart'][$productId]);

        header("Location: cart.php");
        exit();
    }
}
// ga terug naar cart.php
header("Location: cart.php");
exit();
?>

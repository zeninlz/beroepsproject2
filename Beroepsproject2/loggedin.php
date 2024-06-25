<?php
    // Controleer of de PHP-sessie is gestart
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Controleer of de gebruiker niet is ingelogd, stuur ze door naar de inlogpagina
    if (!isset($_SESSION["voornaam"])) {
        header("Location: login.php");
        exit();
    }
?>

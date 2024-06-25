<?php
    session_start(); // Start de PHP sessie
    if(session_destroy()) { // Vernietig de sessie (log de gebruiker uit) en controleer of dit succesvol was
        header("Location: login.php"); // Stuur de gebruiker terug naar het inlogscherm
    }
?>
<!DOCTYPE html> <!-- De doctype geeft aan dat dit een HTML-document is. -->
<html>
<head>
    <meta charset="utf-8"/> <!-- Charset-instelling voor de tekstcodering. -->
    <title>Registration</title> <!-- De titel van de webpagina. -->
    <link rel="stylesheet" href="register.css"/> <!-- Koppeling naar een externe CSS-stijlblad. -->
</head>
<body>
<?php
require('database.php'); // Inclusie van een PHP-bestand om verbinding te maken met de database.

if (isset($_POST['submit'])) { // Controleert of het formulier is ingediend.
    $voornaam = $_POST['voornaam']; // Haalt de voornaam op uit het formulier.
    $achternaam = $_POST['achternaam']; // Haalt de achternaam op uit het formulier.
    $email = $_POST['email']; // Haalt het e-mailadres op uit het formulier.
    $adres = $_POST['adres']; // Haalt het adres op uit het formulier.
    $telefoonnummer = $_POST['telefoonnummer']; // Haalt het telefoonnummer op uit het formulier.
    $wachtwoord = $_POST['wachtwoord']; // Haalt het wachtwoord op uit het formulier.

    $checkQuery = "SELECT email FROM `klanten` WHERE email=?"; // SQL-query om te controleren of het e-mailadres al bestaat.
    $stmtCheck = $pdo->prepare($checkQuery); // Bereidt de SQL-query voor.
    $stmtCheck->execute([$email]); // Voert de query uit.

    if ($stmtCheck->rowCount() > 0) { // Controleert of er al een gebruiker is met hetzelfde e-mailadres.
        echo "<div class='form'>
              <h3>Email already registered. Please use a different email.</h3><br/>
              <p class='link'>Click here to <a href='register.php'>register</a> again.</p>
              </div>"; // Toont een bericht als het e-mailadres al geregistreerd is.
    } else {
        // Voegt de gebruiker toe aan de database als het e-mailadres niet al bestaat.
        $query = "INSERT INTO `klanten` (voornaam, achternaam, email, adres, telefoonnummer, wachtwoord)
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($query); // Bereidt de SQL-query voor.
        $result = $stmt->execute([$voornaam, $achternaam, $email, $adres, $telefoonnummer, $wachtwoord]);

        if ($result) {
            echo "<div class='form'>
                  <h3>You are registered successfully.</h3><br/>
                  <p class='link'>Click here to <a href='login.php'>Login</a></p>
                  </div>"; // Toont een succesbericht na succesvolle registratie.
        } else {
            echo "<div class='form'>
                  <h3>Registration failed. Please try again.</h3><br/>
                  <p class='link'>Click here to <a href='register.php'>register</a> again.</p>
                  </div>"; // Toont een foutbericht als de registratie mislukt.
        }
    }
} else {
?>
    <!-- HTML-formulier voor registratie als het formulier nog niet is ingediend. -->
    <form class="form" action="" method="post">
        <h1>SoftwareShop</h1>
        <h1 class="login-title">Registration</h1>
        <input type="text" class="login-input" name="voornaam" placeholder="First Name" required>
        <input type="text" class="login-input" name="achternaam" placeholder="Last Name" required>
        <input type="email" class="login-input" name="email" placeholder="Email" required>
        <input type="text" class="login-input" name="adres" placeholder="Addres" required>
        <input type="number" class="login-input" name="telefoonnummer" placeholder="Phonenumber">
        <input type="password" class="login-input" name="wachtwoord" placeholder="Password" required>
        <input type="submit" name="submit" value="Register" class="login-button">
        <p class="link"><a href="login.php">Click to Login</a></p>
    </form>
<?php
}
?>
</body>
</html>




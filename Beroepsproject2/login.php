<?php
session_start(); // Start de PHP sessie

if (isset($_SESSION['voornaam'])) {
    header("Location: home.php"); // Als de sessie variabele 'voornaam' bestaat, stuur de gebruiker naar home.php en stop de script uitvoering.
    exit();
}

require('database.php');  // Laad de database connectie

if (isset($_POST['submit'])) { // Als het login formulier is verzonden
    $email = $_POST['email']; // Haal de ingevoerde e-mail op
    $enteredPassword = $_POST['wachtwoord']; // Haal het ingevoerde wachtwoord op

    $query = "SELECT * FROM `klanten` WHERE email=?"; // Query om gebruikersinformatie uit de database op te halen op basis van de ingevoerde e-mail
    $stmt = $pdo->prepare($query); // Bereid de query voor
    $stmt->execute([$email]); // Voer de query uit met de ingevoerde e-mail
    $result = $stmt->fetch(PDO::FETCH_ASSOC); // Haal de resultaten op als een associatieve array

    if ($result) { // Als er een resultaat is gevonden met de ingevoerde e-mail
        $email = $result['email']; // Haal het e-mailadres uit de resultaten
        $userRole = $result['role']; // Haal de rol van de gebruiker uit de resultaten

            if ($enteredPassword === $result['wachtwoord']) {{ // Controleer of het ingevoerde wachtwoord overeenkomt met het opgeslagen wachtwoord in de database
                if ($userRole === 'admin') { // Als de gebruiker een admin is
                    $_SESSION['email'] = $email; // Sla het e-mailadres op in de sessie
                    $_SESSION['voornaam'] = $result['voornaam']; // Sla de voornaam op in de sessie
                    $_SESSION['role'] = 'admin'; // Sla de rol 'admin' op in de sessie
                } else {  // Als de gebruiker geen admin is
                    $_SESSION['email'] = $email; // Sla het e-mailadres op in de sessie
                    $_SESSION['voornaam'] = $result['voornaam']; // Sla de voornaam op in de sessie
                    $_SESSION['role'] = 'user'; // Sla de rol 'user' op in de sessie
                }
                header("Location: home.php"); // Stuur de gebruiker naar home.php
                exit(); // Stop de script uitvoering
            }
            } else { // als je geen toegang hebt tot het admin paneel
                echo "<div class='form'>
                      <h3>Je hebt geen toegang tot het admin-paneel.</h3><br/>
                      <p class='link'>Klik hier om opnieuw <a href='login.php'>in te loggen</a>.</p>
                      </div>";
            }
        } else { // Als er geen resultaat is gevonden met de ingevoerde e-mail
            echo "<div class='form'>
                  <h3>Onjuiste e-mail/wachtwoord.</h3><br/>
                  <p class='link'>Klik hier om opnieuw <a href='login.php'>in te loggen</a>.</p>
                  </div>";
        }
    } else {  // Als het login formulier niet is verzonden
       
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Login</title>
    <link rel="stylesheet" href="login.css"/>
</head>
<body>
    
    <form class="form" method="post" name="login">
        <h1>SoftwareShop</h1>
        <h1 class="login-title">Login</h1>
        <input type="email" class="login-input" name="email" placeholder="Email" autofocus="true" required/>
        <input type="password" class="login-input" name="wachtwoord" placeholder="Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
        <input type="submit" value="Login" name="submit" class="login-button"/>
        <p class="link"><a href="register.php">Click to register</a></p>
    </form>
</body>
</html>



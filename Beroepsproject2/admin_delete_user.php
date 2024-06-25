<?php
session_start();

// Controleer of de gebruiker is ingelogd als beheerder
if (!isset($_SESSION['voornaam']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php"); // Stuur niet-ingelogde gebruikers terug naar de inlogpagina
    exit();
}

require('database.php');

if (isset($_GET['delete_user_email'])) {
    $email = $_GET['delete_user_email'];

    $query = "DELETE FROM `klanten` WHERE `email` = :email";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);

    if ($stmt->execute()) {
        header("Location: admin_users.php"); // Als de gebruiker is verwijderd, ga terug naar de beheerderspagina
        exit();
    } else {
        echo "Error deleting user. Please try again."; // Geef een foutmelding als er een probleem is opgetreden tijdens het verwijderen
    }
} else {
    echo "Invalid request."; // Als er geen e-mail is opgegeven, is het een ongeldige aanvraag
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Delete User</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="form">
        <h3>Welcome, <?php echo $_SESSION['voornaam']; ?>!</h3> <!-- Toon de naam van de ingelogde beheerder -->
        <h3><a id="logout" href="logout.php">Logout</a></h3>
        <a href="admin.php">Back to Dashboard</a>
    </div>

    <h2>Delete User</h2>
    <p>Are you sure you want to delete this user?</p>

    <?php
    if (isset($_GET['delete_user_email'])) {
        echo '<p>Email: ' . $_GET['delete_user_email'] . '</p>'; // Toon de e-mail van de te verwijderen gebruiker, als deze is opgegeven
    }
    ?>

    <form method="post">
        <button type="submit" name="confirm_delete">Confirm Delete</button>
    </form>
</body>
</html>

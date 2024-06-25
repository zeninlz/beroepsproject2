<?php
session_start();

// Controleer of de gebruiker is ingelogd en een admin is. Als niet, stuur ze naar de login-pagina.
if (!isset($_SESSION['voornaam']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

require('database.php');

// Controleer of de 'email'-parameter is ingesteld in de URL. Zo niet, stuur de gebruiker terug naar de beheerdersgebruikerspagina.
if (!isset($_GET['email'])) {
    header("Location: admin_users.php");
    exit();
}

$userEmail = $_GET['email'];

// Zoek de gebruikersinformatie op basis van het opgegeven e-mailadres.
$query = "SELECT * FROM `klanten` WHERE email = :email";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':email', $userEmail, PDO::PARAM_STR);
$stmt->execute();
$userInfo = $stmt->fetch(PDO::FETCH_ASSOC);

// Als er geen gebruikersinformatie is gevonden, stuur de gebruiker terug naar de beheerdersgebruikerspagina.
if (!$userInfo) {
    header("Location: admin_users.php");
    exit();
}

// Als het HTTP-verzoeksmethode POST is en het formulier is ingediend voor het bijwerken van de gebruikersgegevens.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $newFirstName = $_POST['new_first_name'];
    $newLastName = $_POST['new_last_name'];
    $newEmail = $_POST['new_email'];
    $newAdres = $_POST['new_adres'];

    // Voorbereiden en uitvoeren van een SQL-query om de gebruikersinformatie bij te werken.
    $updateQuery = "UPDATE `klanten` SET voornaam = :new_first_name, achternaam = :new_last_name, email = :new_email, adres = :new_adres WHERE email = :email";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':new_first_name', $newFirstName, PDO::PARAM_STR);
    $updateStmt->bindParam(':new_last_name', $newLastName, PDO::PARAM_STR);
    $updateStmt->bindParam(':new_email', $newEmail, PDO::PARAM_STR);
    $updateStmt->bindParam(':new_adres', $newAdres, PDO::PARAM_STR);
    $updateStmt->bindParam(':email', $userEmail, PDO::PARAM_STR);

    // Als het bijwerken is geslaagd, stuur de gebruiker terug naar de beheerdersgebruikerspagina.
    if ($updateStmt->execute()) {
        header("Location: admin_users.php");
        exit();
    } else {
        $errorMessage = "Error updating user details.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="admin_edit_user.css">
</head>
<body>
    <div class="form">
        <h3>Welcome, <?php echo $_SESSION['voornaam']; ?>!</h3>
        <h3><a id="logout" href="logout.php">Logout</a></h3>
        <a href="admin_users.php">Terug naar users</a>
    </div>

    <h2>Edit User</h2>
    <?php
    // Toon een foutmelding als er een fout is opgetreden bij het bijwerken van de gebruikersgegevens.
    if (isset($errorMessage)) {
        echo "<p class='error-message'>$errorMessage</p>";
    }
    ?>
    <form class="edit" method="post">
        <label for="new_first_name">New First Name:</label>
        <input type="text" id="new_first_name" name="new_first_name" value="<?php echo $userInfo['voornaam']; ?>" required>
        <br>
        <label for="new_last_name">New Last Name:</label>
        <input type="text" id="new_last_name" name="new_last_name" value="<?php echo $userInfo['achternaam']; ?>" required>
        <br>
        <label for="new_email">New Email:</label>
        <input type="email" id="new_email" name="new_email" value="<?php echo $userInfo['email']; ?>" required>
        <br>
        <label for="new_adres">New Address:</label>
        <input type="text" id="new_adres" name="new_adres" value="<?php echo $userInfo['adres']; ?>" required>
        <br>
        <input type="submit" name="update_user" value="Update User">
    </form>
</body>
</html>

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

// Controleer of het verzoek een HTTP POST-verzoek is en of de "submit" knop is ingedrukt
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    // Haal de productinformatie op uit het POST-verzoek
    $productID = $_POST['productID'];
    $productName = $_POST['productName'];
    $productPrice = $_POST['productPrice'];
    $productDescription = $_POST['productDescription'];

    // Bereid de SQL-query voor om een bestaand product bij te werken in de database
    $query = "UPDATE `producten` SET naam=?, prijs=?, beschrijving=? WHERE producten_id=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$productName, $productPrice, $productDescription, $productID]);

    // Stuur de gebruiker terug naar de admin-pagina na het bijwerken
    header("Location: admin.php");
    exit();
}

// Controleer of een "id" parameter is ingesteld in het GET-verzoek
if (isset($_GET['id'])) {
    $productID = $_GET['id'];

    // Haal productgegevens op uit de database op basis van het meegegeven product ID
    $query = "SELECT * FROM `producten` WHERE producten_id=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$productID]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Controleer of het product is gevonden
    if (!$product) {
        echo "Product not found.";
        exit();
    }
} else {
    echo "Product ID not specified.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Product</title>
    <link rel="stylesheet" href="edit_product.css">
</head>
<body>
    <h2>Edit Product</h2>
    <form method="post">
        <input type="hidden" name="productID" value="<?php echo $product['producten_id']; ?>">
        <label for="productName">Product Name:</label>
        <input type="text" name="productName" id="productName" value="<?php echo $product['naam']; ?>" required><br>
        <label for="productPrice">Product Price:</label>
        <input type="text" name="productPrice" id="productPrice" value="<?php echo $product['prijs']; ?>" required><br>
        <label for="productDescription">Product Description:</label>
        <textarea name="productDescription" id="productDescription" rows="4" required><?php echo $product['beschrijving']; ?></textarea><br>
        <input type="submit" name="submit" value="Update Product">
    </form>
    <p><a href="admin.php">Back to Admin Dashboard</a></p>
</body>
</html>

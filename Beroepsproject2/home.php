<?php
// Start de PHP-sessie als deze nog niet is gestart
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclusie van het bestand "loggedin.php"
include("loggedin.php");

// Als de gebruiker niet is ingelogd, stuur ze naar de inlogpagina en stop de uitvoering van de code
if (!isset($_SESSION['voornaam'])) {
    header("Location: login.php");
    exit();
}

// Controleer of de gebruiker een beheerder is
$isAdmin = isset($_SESSION['role']) && ($_SESSION['role'] === 'admin');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Home</title>
    <link rel="stylesheet" href="home.css">
    <script src="home.js"></script>
</head>
<body>

<header>
    <nav class="navbar">
        <a href="cart.php">
            <img src="bag-check-fill.svg" alt="Shopping Cart" width="60" height="60">
            <?php
            // Toon het aantal items in de winkelwagen als deze is ingesteld
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                $cartItemCount = array_sum($_SESSION['cart']);
                echo "<span class='cart-count'>$cartItemCount</span>";
            }
            ?>
        </a>
        <h1><a href="receipt.php">Orders</a></h1>
        <div class="top-bar">
            <div class="form">
                <!-- Groet de gebruiker met hun voornaam -->
                <h1>Hey, <?php echo $_SESSION['voornaam']; ?>!</h1>
                <h3><a id="logout" href="logout.php">Logout</a></h3><br>
                <?php if ($isAdmin): ?>
                    <!-- Toon de link naar de beheerderspagina als de gebruiker een beheerder is -->
                    <h3><a id="admin-link" href="admin.php">Admin Page</a></h3>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>

<div class="product-container">
    <?php
    // Inclusie van het databasebestand
    require('database.php');

    $query = "SELECT * FROM producten";
    $stmt = $pdo->query($query);

    if ($stmt) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $productNaam = $row['naam'];
            $productPrijs = $row['prijs'];
            $productBeschrijving = $row['beschrijving'];

            echo "<div class='product'>";
            echo "<h1>$productNaam</h1>";
            echo "<p class='price'>$ $productPrijs</p>";
            echo "<p>$productBeschrijving</p>";
            echo "<form method='post' action='add_to_cart.php' onsubmit='addToCart(event, this);'>";
            echo "<input type='hidden' name='product_id' value='{$row['producten_id']}'>";
            echo "<button type='submit' name='add_to_cart'>Add to Cart</button>";
            echo "</form>";
            echo "</div>";
        }
    } else {
        // Toon een bericht als er geen producten beschikbaar zijn
        echo "<p>No products available.</p>";
    }
    ?>
</div>

<div class="footer-basic">
    <footer>
        <ul class="list-inline">
            <li class="list-inline-item"><a href="home.php">Home</a></li>
            <li class="list-inline-item"><a href="about.html">About</a></li>
            <li class="list-inline-item"><a href="privacy_policy.html">Privacy Policy</a></li>
        </ul>
        <p class="copyright">SoftwareShop © 2023</p>
    </footer>
</div>
</body>
</html>

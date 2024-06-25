<?php
   // hier word de connectie gemaakt met database
   // Databasegegevens 
    $host = 'localhost:3306'; 
    $db = 'Softwareshop'; 
    $user = 'root'; 
    $pass = ''; 
    $charset = 'utf8mb4'; 
    
    // DSN (Data Source Name) voor de databaseverbinding
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    // Opties voor de PDO-verbinding
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Zorgt ervoor dat PDO uitzonderingen gooit bij fouten
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Stelt de standaard fetch-modus in op associatieve array
        PDO::ATTR_EMULATE_PREPARES => false, // Schakelt het emuleren van prepared statements uit
    ];
    
    // Probeer een databaseverbinding tot stand te brengen
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int) $e->getCode());
    }
?>

<?php
define('DB_SERVER', 'ip:port'); // ip et port db ex : localhost:3007
define('DB_USERNAME', 'root'); // nom utilisateur db
define('DB_PASSWORD', ''); // mot de passe utilisateur db
define('DB_NAME', 'shifumi'); // nom db

try {
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USERNAME, DB_PASSWORD);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}

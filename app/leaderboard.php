<?php
try {
    $stmt = $pdo->query("SELECT username, victoires, defaites, parties FROM utilisateur ORDER BY victoires DESC LIMIT 10");
    $leaderboard = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Génère un classement vide en cas d'erreur pour éviter de casser la page
    $leaderboard = [];
}

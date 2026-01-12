<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//logique déconnection

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['auth_action'])) {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($_POST['auth_action'] === 'login') {

        //logique connection

        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $authError = "Identifiants incorrects.";
        }
    } elseif ($_POST['auth_action'] === 'register') {

        //logique inscription

        $stmt = $pdo->prepare("SELECT user_id FROM utilisateur WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            $authError = "Ce nom d'utilisateur est déjà pris.";
        } else {
            $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO utilisateur (username, password, parties, victoires) VALUES (?, ?, 0, 0)");

            if ($stmt->execute([$username, $hashedPwd])) {
                $_SESSION['user_id'] = $pdo->lastInsertId();
                $_SESSION['username'] = $username;
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } else {
                $authError = "Erreur lors de l'inscription.";
            }
        }
    }
}

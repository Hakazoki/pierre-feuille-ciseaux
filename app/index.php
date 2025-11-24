<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>Shifumi</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <h1 class=text-center>shifumi</h1>
    <?php
    session_start();
    $_SESSION['score'] = $_SESSION['score'] ?? 0;
    if (isset($_POST['restart'])) {
        session_destroy();
        header("Location: ./index.php");
        exit();
    }
    $pierre = 'pierre';
    $feuille = 'feuille';
    $ciseaux = 'ciseaux';
    $options = [$pierre, $feuille, $ciseaux];
    if ((isset($_POST['choix'])) and !empty($_POST['choix'])) {
        $choixjoueur = $_POST["choix"] ?? '';
        if (empty($choixjoueur)) {
            echo "Vide";
        } else {
            echo "<br> Joueur : ";
            echo $choixjoueur;
        }
        $choixadversaire = $options[array_rand($options)];
        echo "<br> Adversaire : ";
        echo $choixadversaire;
        if ($choixjoueur == $choixadversaire) {
            $_SESSION['resultat'] = "Egalité";
            // echo "<br> Egalité";
        } else if (($choixjoueur == $pierre && $choixadversaire == $ciseaux or $choixjoueur == $feuille && $choixadversaire == $pierre or $choixjoueur == $ciseaux && $choixadversaire == $feuille)) {
            // echo "<br> Vous avez gagné !";
            $_SESSION['resultat'] = "Vous avez gagné !";
            $_SESSION['score'] = $_SESSION['score'] + 1;
        } else {
            $_SESSION['resultat'] = "Vous avez perdu !";
            // echo "<br> Vous avez perdu !";
        }
        echo "<br> Resultat : ";
        echo $_SESSION['resultat'];
        echo "<hr> Score : ";
        echo $_SESSION['score'];
    } else {
        echo "<br> Veuillez choisir une option";
    }
    ?>
    <div class="grid grid-cols-3 gap-4 justify-items-center mt-10 content-center">
        <form action="#" method="POST">
            <button type="submit" name="choix" value="<?php echo $pierre; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Pierre</button>
            <button type="submit" name="choix" value="<?php echo $feuille; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Feuille</button>
            <button type="submit" name="choix" value="<?php echo $ciseaux; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Ciseaux</button>
            <button type="submit" name="choix" value="" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">reset</button>
            <button type="submit" name="restart" value="oui" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">restart</button>

        </form>
    </div>

</body>

</html>
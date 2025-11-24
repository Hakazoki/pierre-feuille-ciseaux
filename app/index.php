<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.css" rel="stylesheet" />
    <title>Shifumi</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body class="dark">

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

    <main class="flex-1 p-6 dark">
        <div class="flex items-start justify-between">
            <div class="w-full max-w-2xl mx-auto">
                <!-- carte état de la partie -->
                <div id="state-block" class="mx-auto bg-white border border-slate-200 rounded-lg shadow-sm p-4 text-center">
                    <div class="text-sm text-slate-500">Partie en cours</div>
                    <div id="state-text" class="mt-2 text-xl font-semibold">Choix</div>
                    <div id="round-result" class="mt-3 text-slate-600"></div>
                </div>

                <!-- Choices -->
                <div class="mt-6 grid grid-cols-3 gap-4 justify-items-center">
                    <button id="pierre" class="choice-btn bg-amber-500" title="pierre">
                        ✊<span class="sr-only">Pierre</span>
                    </button>
                    <button id="feuille" class="choice-btn bg-sky-500" title="feuille">
                        ✋<span class="sr-only">Feuille</span>
                    </button>
                    <button id="ciseaux" class="choice-btn bg-rose-500" title="ciseaux">
                        ✌️<span class="sr-only">Ciseaux</span>
                    </button>
                </div>

                <!-- tableau historique -->
                <div class="mt-6 bg-white border border-slate-200 rounded-lg p-4 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-slate-500">Historique</div>
                        <button id="clear-history" class="text-sm text-rose-600 hover:underline">Clear</button>
                    </div>
                    <div id="history" class="mt-3 text-sm text-slate-700 space-y-1"></div>
                </div>
            </div>

            <!-- tableau des scores -->
            <div class="ml-6 w-44">
                <div class="bg-white border border-slate-200 rounded-lg p-4 shadow-sm text-center">
                    <div class="text-xs text-slate-500">Score</div>
                    <div class="mt-2 grid grid-cols-1 gap-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Joueur</span>
                            <span id="score-you" class="font-semibold">0</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Ordinateur</span>
                            <span id="score-comp" class="font-semibold">0</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Egalité</span>
                            <span id="score-tie" class="font-semibold">0</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button id="auto-play" class="w-full px-3 py-2 bg-slate-100 rounded-md text-sm hover:bg-slate-200">Auto-play</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
</body>

</html>
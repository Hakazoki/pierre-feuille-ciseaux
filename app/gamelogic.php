<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('OPTIONS', ['pierre', 'feuille', 'ciseaux', 'lézard', 'spock']);
define('GAMESTATE', ['VICTOIRE', 'DÉFAITE', 'ÉGALITÉ']);

// fonction timestamp et ip

function timestamp()
{
    global $pdo;


    date_default_timezone_set('Europe/Paris');
    $ip = $_SERVER['REMOTE_ADDR'];
    $time = date('Y-m-d H:i:s');

    $_SESSION['last_game_time'] = date('H:i');

    if (isset($_SESSION['user_id'])) {
        try {
            $sql = "UPDATE utilisateur SET ip = :ip, timestamp = :time WHERE user_id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'ip' => $ip,
                'time' => $time,
                'id' => $_SESSION['user_id']
            ]);
        } catch (PDOException $e) {
        }
    }
}

function initpartie()
{
    $_SESSION['score']           = 0;
    $_SESSION['egalites']        = 0;
    $_SESSION['defaite']         = 0;
    $_SESSION['choixjoueur']     = [];
    $_SESSION['choixadversaire'] = [];
    $_SESSION['etat']            = null;
    $_SESSION['tour']            = 1;
    $_SESSION['mode']            = 'traditionnel';
    $_SESSION['random']          = 'oui';
    timestamp();
}

function determinerGagnant($MoveJoueur, $MoveAdversaire)
{
    $regles = [
        0 => [2, 3],
        1 => [0, 4],
        2 => [1, 3],
        3 => [1, 4],
        4 => [0, 2]
    ];
    if ($MoveJoueur === $MoveAdversaire) return 2;
    if (in_array($MoveAdversaire, $regles[$MoveJoueur])) return 0;
    return 1;
}

function rediriger()
{
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

function restart()
{
    $_SESSION['score'] = 0;
    $_SESSION['egalites'] = 0;
    $_SESSION['defaite'] = 0;
    $_SESSION['choixjoueur'] = [];
    $_SESSION['choixadversaire'] = [];
    $_SESSION['etat'] = null;
    $_SESSION['tour'] = 1;
    timestamp();
    rediriger();
}

if (isset($_POST['restart'])) restart();
if (isset($_POST['switch_mode'])) {
    $_SESSION['mode'] = $_POST['switch_mode'];
    restart();
}
if (isset($_POST['switch_ia'])) {
    $_SESSION['random'] = $_POST['switch_ia'];
    restart();
}
if (isset($_POST['clear_history'])) {
    $_SESSION['choixjoueur'] = [];
    $_SESSION['choixadversaire'] = [];
    rediriger();
}

//déterminer choix joueur

if (empty($_SESSION['choixjoueur']) && !isset($_SESSION['mode'])) initpartie();

//déterminer choix ordi + résolution

if (!empty($_POST['choix']) && in_array($_POST['choix'], OPTIONS)) {
    $maxIndex = ($_SESSION['mode'] === 'traditionnel') ? 2 : 4;
    $MoveJoueur = array_search($_POST['choix'], OPTIONS);

    if (isset($_SESSION['random']) && $_SESSION['random'] === 'oui') {
        $MoveAdversaire = rand(0, $maxIndex);
    } else {
        $etapelogique = ($_SESSION['tour'] - 1) % 5 + 1;
        switch ($etapelogique) {
            case 1:
                $MoveAdversaire = rand(0, $maxIndex);
                break;
            case 2:
                $couptour1 = $_SESSION['choixjoueur'][0];
                $id1 = array_search($couptour1, OPTIONS);
                $wins = [];
                for ($i = 0; $i <= $maxIndex; $i++) if (determinerGagnant($id1, $i) === 1) $wins[] = $i;
                $MoveAdversaire = !empty($wins) ? $wins[array_rand($wins)] : rand(0, $maxIndex);
                break;
            case 3:
                $prev = $_SESSION['choixadversaire'][sizeof($_SESSION['choixadversaire']) - 2];
                $MoveAdversaire = array_search($prev, OPTIONS);
                break;
            case 4:
                $exclus = [array_search(end($_SESSION['choixadversaire']), OPTIONS), array_search(prev($_SESSION['choixadversaire']), OPTIONS)];
                do {
                    $MoveAdversaire = rand(0, $maxIndex);
                } while (in_array($MoveAdversaire, $exclus));
                break;
            case 5:
                $MoveAdversaire = array_search($_SESSION['choixjoueur'][sizeof($_SESSION['choixjoueur']) - 1], OPTIONS);
                break;
        }
    }

    //résolution de la partie et update stats de la page

    $resultat = determinerGagnant($MoveJoueur, $MoveAdversaire);
    if ($resultat === 0) $_SESSION['score']++;
    elseif ($resultat === 1) $_SESSION['defaite']++;
    else $_SESSION['egalites']++;

    //update de la DB

    //check si l'utilisateur est login  
    if (isset($_SESSION['user_id'])) {
        try {
            // Incrément du nb de parties
            $sql = "UPDATE utilisateur SET parties = parties + 1";

            // Incrément des victoires
            if ($resultat === 0) {
                $sql .= ", victoires = victoires + 1";
            }

            $sql .= " WHERE user_id = :id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $_SESSION['user_id']]);
        } catch (PDOException $e) {

            error_log("Erreur DB: " . $e->getMessage());
        }
    }


    //update stats de session

    $_SESSION['tour']++;
    $_SESSION['choixjoueur'][] = OPTIONS[$MoveJoueur];
    $_SESSION['choixadversaire'][] = OPTIONS[$MoveAdversaire];
    $_SESSION['etat'] = $resultat;
    rediriger();
}

$nextModeVal = ($_SESSION['mode'] === 'traditionnel') ? 'spock' : 'traditionnel';
$nextRandomVal = ($_SESSION['random'] === 'oui') ? 'non' : 'oui';

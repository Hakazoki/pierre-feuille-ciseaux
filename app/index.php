<?php
session_start();


define('OPTIONS', ['pierre', 'feuille', 'ciseaux', 'lézard', 'spock']);
define('GAMESTATE', ['VICTOIRE', 'DÉFAITE', 'ÉGALITÉ']);



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
}

// 0=Pierre, 1=Feuille, 2=Ciseaux, 3=Lézard, 4=Spock

function determinerGagnant($MoveJoueur, $MoveAdversaire)
{
    $regles = [
        0 => [2, 3],
        1 => [0, 4],
        2 => [1, 3],
        3 => [1, 4],
        4 => [0, 2]
    ];

    if ($MoveJoueur === $MoveAdversaire) {
        return 2;
    }

    if (in_array($MoveAdversaire, $regles[$MoveJoueur])) {
        return 0;
    }

    return 1;
}

function rediriger()
{
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

function restart()
{
    $_SESSION['score']           = 0;
    $_SESSION['egalites']        = 0;
    $_SESSION['defaite']         = 0;
    $_SESSION['choixjoueur']     = [];
    $_SESSION['choixadversaire'] = [];
    $_SESSION['etat']            = null;
    $_SESSION['tour']            = 1;
    rediriger();
}

if (isset($_POST['restart'])) {
    restart();
}

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

if (empty($_SESSION['choixjoueur']) && !isset($_SESSION['mode'])) {
    initpartie();
}

if (!empty($_POST['choix']) && in_array($_POST['choix'], OPTIONS)) {

    $maxIndex = ($_SESSION['mode'] === 'traditionnel') ? 2 : 4;
    $MoveJoueur = array_search($_POST['choix'], OPTIONS);

    if (isset($_SESSION['random']) && $_SESSION['random'] === 'oui') {
        $MoveAdversaire = rand(0, $maxIndex);
    } else {

        $etapelogique = ($_SESSION['tour'] - 1) % 5 + 1;

        switch ($etapelogique) {


            case 1: // tour 1 : choix aléatoire 
                $MoveAdversaire = rand(0, $maxIndex);
                break;

            case 2: // tour 2 : option qui bat le choix du tour 1 du joueur
                $couptour1 = $_SESSION['choixjoueur'][0];
                $idcouptour1 = array_search($couptour1, OPTIONS);
                $optionsgagnantes = [];
                for ($i = 0; $i <= $maxIndex; $i++) {
                    if (determinerGagnant($idcouptour1, $i) === 1) {
                        $optionsgagnantes[] = $i;
                    }
                }
                if (!empty($optionsgagnantes)) {
                    $MoveAdversaire = $optionsgagnantes[array_rand($optionsgagnantes)];
                }
                break;

            case 3: // tour 3 : répète son choix du tour 1
                $couptour1 = $_SESSION['choixadversaire'][sizeof($_SESSION['choixadversaire']) - 2];
                $MoveAdversaire = array_search($couptour1, OPTIONS);
                break;

            case 4: // tour 4 : option qu'il n'as pas encore dite ou pas depuis longtemps
                $exclus = [
                    array_search(end($_SESSION['choixadversaire']), OPTIONS),
                    array_search(prev($_SESSION['choixadversaire']), OPTIONS)
                ];
                do {
                    $MoveAdversaire = rand(0, $maxIndex);
                } while (in_array($MoveAdversaire, $exclus));
                break;

            case 5: // tour 5 : répète le choix du joueur au tour 4
                $MoveAdversaire = array_search($_SESSION['choixjoueur'][sizeof($_SESSION['choixjoueur']) - 1], OPTIONS);
                break;
        }
    }

    $resultat = determinerGagnant($MoveJoueur, $MoveAdversaire);

    switch ($resultat) {
        case 0:
            $_SESSION['score']++;
            break;
        case 1:
            $_SESSION['defaite']++;
            break;
        case 2:
            $_SESSION['egalites']++;
            break;
    }

    $_SESSION['tour']++;
    $_SESSION['choixjoueur'][]     = OPTIONS[$MoveJoueur];
    $_SESSION['choixadversaire'][] = OPTIONS[$MoveAdversaire];
    $_SESSION['etat']              = $resultat;

    rediriger();
}
?>

<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Shifumi - <?= ucfirst($_SESSION['mode']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>

    <nav class="navbar navbar-glass">
        <div class="container-fluid px-lg-5">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <span class="brand-logo">SHIFUMI</span>
                <span class="badge bg-white text-dark border border-white bg-opacity-25 small"><?= ucfirst($_SESSION['mode']) ?></span>
                <span class="badge bg-white text-dark border border-white bg-opacity-25 small"><?= ucfirst($_SESSION['random']) ?></span>

            </a>

            <button class="btn btn-menu px-3 py-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu">
                <i class="bi bi-list fs-5"></i>
            </button>
        </div>
    </nav>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasMenu">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menu</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <div class="mb-4">
                <form method="POST">
                    <div class="d-grid gap-2">
                        <button type="submit" name="switch_mode" value="traditionnel" class="btn btn-outline-light <?= $_SESSION['mode'] == 'traditionnel' ? 'active' : '' ?>">
                            Mode Classique
                        </button>
                        <button type="submit" name="switch_mode" value="spock" class="btn btn-outline-info <?= $_SESSION['mode'] == 'spock' ? 'active' : '' ?>">
                            Mode Spock 🖖
                        </button>
                    </div>
                </form>
            </div>
            <hr class="border-secondary opacity-25">
            <div class="mb-4">
                <form method="POST">
                    <div class="d-grid gap-2">
                        <button type="submit" name="switch_ia" value="oui" class="btn btn-outline-light <?= $_SESSION['random'] == 'oui' ? 'active' : '' ?>">
                            Mode Random
                        </button>
                        <button type="submit" name="switch_ia" value="non" class="btn btn-outline-info <?= $_SESSION['random'] == 'non' ? 'active' : '' ?>">
                            Mode IA
                        </button>
                    </div>
                </form>
            </div>
            <div class="mt-auto">
                <a href="#" class="nav-link-custom text-danger"><i class="bi bi-box-arrow-right"></i> Login / Logout</a>
            </div>
        </div>
    </div>

    <main class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">

                <div class="row g-3 mb-4">
                    <div class="col-4">
                        <div class="stat-card">
                            <div class="stat-value text-win"><?= $_SESSION['score'] ?></div>
                            <div class="stat-label">Joueur</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-card">
                            <div class="stat-value text-tie"><?= $_SESSION['egalites'] ?></div>
                            <div class="stat-label">Égalité</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-card">
                            <div class="stat-value text-loss"><?= $_SESSION['defaite'] ?></div>
                            <div class="stat-label">CPU</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="stat-card">
                            <div class="stat-value text"><?= $_SESSION['tour'] ?></div>
                            <div class="stat-label">Tour</div>
                        </div>
                    </div>
                </div>

                <div class="game-area mb-4">
                    <div class="text-center result-display mb-4">
                        <?php if ($_SESSION['etat'] !== null): ?>
                            <?php
                            $cssClass = 'text-white';
                            if ($_SESSION['etat'] === 0) $cssClass = 'text-win';
                            if ($_SESSION['etat'] === 1) $cssClass = 'text-loss';
                            if ($_SESSION['etat'] === 2) $cssClass = 'text-tie';
                            ?>
                            <div class="result-title mb-2 <?= $cssClass ?>">
                                <?= GAMESTATE[$_SESSION['etat']] ?>
                            </div>
                            <div class="d-flex justify-content-center gap-4 align-items-center fs-5 text">
                                <span><?= strtoupper(end($_SESSION['choixjoueur'])) ?></span>
                                <span class="badge bg-dark border border-secondary rounded-pill px-3">VS</span>
                                <span><?= strtoupper(end($_SESSION['choixadversaire'])) ?></span>
                            </div>
                        <?php else: ?>
                            <div class="h3 text-white fw-bold mb-2">Prêt à jouer ?</div>
                            <div class="text">Faites votre choix pour commencer</div>
                        <?php endif; ?>
                    </div>

                    <form action="" method="POST" class="row g-3 justify-content-center">
                        <?php
                        $emojis = ['pierre' => '🪨', 'feuille' => '📄', 'ciseaux' => '✂️', 'lézard' => '🦎', 'spock' => '🖖'];

                        $playableOptions = ($_SESSION['mode'] === 'traditionnel') ? array_slice(OPTIONS, 0, 3) : OPTIONS;

                        foreach ($playableOptions as $opt):
                        ?>
                            <div class="col-4">
                                <button type="submit" name="choix" value="<?= $opt ?>" class="choice-btn">
                                    <span class="emoji"><?= $emojis[$opt] ?></span>
                                    <span class="small fw-bold text-uppercase"><?= $opt ?></span>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </form>

                    <div class="text-center mt-4">
                        <form method="POST" class="d-inline">
                            <button type="submit" name="restart" value="1" class="btn btn-sm btn-outline-secondary rounded-pill px-4">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Restart Game
                            </button>
                        </form>
                    </div>
                </div>

                <div class="history-container">
                    <div class="history-header">
                        <span class="text-white fw-bold small text-uppercase letter-spacing-1">Historique</span>
                        <form method="POST" class="m-0">
                            <button type="submit" name="clear_history" class="btn btn-link text p-0 text-decoration-none small">
                                <i class="bi bi-trash"></i> Clear
                            </button>
                        </form>
                    </div>

                    <div class="history-list">
                        <?php
                        $history = array_reverse(array_map(null, $_SESSION['choixjoueur'], $_SESSION['choixadversaire']));

                        if (empty($history)): ?>
                            <div class="text-center p-4 text small">
                                <i class="bi bi-clock-history d-block fs-4 mb-2 opacity-50"></i>
                                L'historique est vide
                            </div>
                        <?php else: ?>
                            <?php foreach ($history as $pair):
                                [$jName, $aName] = $pair;

                                $jIndex = array_search($jName, OPTIONS);
                                $aIndex = array_search($aName, OPTIONS);

                                $res = determinerGagnant($jIndex, $aIndex);

                                $rowClass = 'loss';
                                if ($res === 0) $rowClass = 'win';
                                if ($res === 2) $rowClass = 'tie';
                            ?>
                                <div class="history-item <?= $rowClass ?>">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-person-fill opacity-50"></i>
                                        <span class="<?= $rowClass == 'win' ? 'text-white' : 'text-muted' ?>"><?= ucfirst($jName) ?></span>
                                    </div>

                                    <div class="text-center opacity-25 small">
                                        <i class="bi bi-x-lg"></i>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-end gap-2">
                                        <span class="<?= $rowClass == 'loss' ? 'text-white' : 'text-muted' ?>"><?= ucfirst($aName) ?></span>
                                        <i class="bi bi-cpu-fill opacity-50"></i>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>


</html>
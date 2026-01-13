<?php
require_once './config.php';
require_once './authentification.php';
require_once './gamelogic.php';
require_once './leaderboard.php';

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
    <?php if (isset($_SESSION['username']) && strtolower($_SESSION['username']) === 'spock'): ?>
        <style>
            body {
                background: url('./images/giphy.gif') no-repeat center center fixed !important;
                background-size: cover !important;
            }
        </style>
    <?php endif; ?>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-custom glass-panel">
        <div class="container-fluid px-lg-5">

            <a class="navbar-brand d-flex align-items-center" href="#">
                <span class="brand-logo">SHIFUMI</span>
            </a>

            <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list fs-1"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">

                <form method="POST" class="d-flex flex-column flex-lg-row gap-2 align-items-start align-items-lg-center m-0 me-auto mt-3 mt-lg-0">
                    <button type="submit" name="switch_mode" value="<?= $nextModeVal ?>"
                        class="badge-btn w-100 w-lg-auto <?= $_SESSION['mode'] === 'spock' ? 'active-mode' : '' ?>"
                        title="Click to switch mode">
                        <?= $_SESSION['mode'] === 'traditionnel' ? 'Mode Classique' : 'Mode Spock 🖖' ?>
                    </button>

                    <button type="submit" name="switch_ia" value="<?= $nextRandomVal ?>"
                        class="badge-btn w-100 w-lg-auto"
                        title="Click to toggle AI">
                        <?= $_SESSION['random'] === 'oui' ? 'Random' : 'IA Active 🤖' ?>
                    </button>
                </form>

                <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center gap-3 mt-3 mt-lg-0">

                    <button type="button" class="btn btn-link text-warning text-decoration-none opacity-75 hover-opacity-100 ps-0" data-bs-toggle="modal" data-bs-target="#leaderboardModal" title="Classement">
                        <i class="bi bi-trophy-fill fs-5"></i>
                        <span class="d-inline-block d-lg-none ms-2 fw-bold">Classement</span> <span class="d-none d-lg-inline-block ms-1">Classement</span> </button>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-white small opacity-75">
                                Bonjour, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
                            </span>
                            <a href="?logout=true" class="btn btn-link text-danger text-decoration-none opacity-75 hover-opacity-100" title="Déconnexion">
                                <i class="bi bi-box-arrow-right fs-5"></i>
                            </a>
                        </div>
                    <?php else: ?>
                        <button type="button" class="btn btn-link text-white text-decoration-none opacity-75 hover-opacity-100 ps-0" data-bs-toggle="modal" data-bs-target="#authModal">
                            <i class="bi bi-box-arrow-in-right fs-5"></i>
                            <span class="ms-1">Login</span>
                        </button>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </nav>

    <main class="container">

        <?php if (isset($authError)): ?>
            <div class="alert alert-danger alert-dismissible fade show mt-3 w-50 mx-auto" role="alert">
                <?= $authError ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row justify-content-center">
            <div class="col-lg-7">

                <div class="row g-3 mb-4">
                    <div class="col-4">
                        <div class="stat-card panel">
                            <div class="stat-value text-win"><?= $_SESSION['score'] ?></div>
                            <div class="stat-label">Joueur</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-card panel">
                            <div class="stat-value text-tie"><?= $_SESSION['egalites'] ?></div>
                            <div class="stat-label">Égalité</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="stat-card panel">
                            <div class="stat-value text-loss"><?= $_SESSION['defaite'] ?></div>
                            <div class="stat-label">CPU</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="stat-card panel d-flex justify-content-between align-items-center px-4">
                            <div class="text-start">
                                <div class="text-white fw-bold fs-5"><?= $_SESSION['last_game_time'] ?? '--:--' ?></div>
                                <div class="stat-label" style="font-size: 0.65rem;">Début</div>
                            </div>

                            <div class="text-center">
                                <div class="stat-value text"><?= $_SESSION['tour'] ?></div>
                                <div class="stat-label">Tour</div>
                            </div>

                            <?php
                            $nbpartiesdecisives = $_SESSION['score'] + $_SESSION['defaite'];
                            $winrate = ($nbpartiesdecisives > 0) ? round(($_SESSION['score'] / $nbpartiesdecisives) * 100) : 0;

                            $couleur_winrate = 'text';
                            if ($winrate >= 50) $couleur_winrate = 'text-win';
                            if ($winrate < 50 && $nbpartiesdecisives > 0) $couleur_winrate = 'text-loss';
                            ?>
                            <div class="text-end">
                                <div class="<?= $couleur_winrate ?> fw-bold fs-5"><?= $winrate ?>%</div>
                                <div class="stat-label" style="font-size: 0.65rem;">Winrate</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="game-area panel panel-lg mb-4">
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
                        $options = ($_SESSION['mode'] === 'traditionnel') ? array_slice(OPTIONS, 0, 3) : OPTIONS;
                        foreach ($options as $opt):
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
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Redémarrer la partie
                            </button>
                        </form>
                    </div>
                </div>

                <div class="history-container panel">
                    <div class="history-header">
                        <span class="text-white fw-bold small text-uppercase letter-spacing-1">Historique</span>
                        <form method="POST" class="m-0">
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
                            <?php foreach ($history as $round):
                                [$jName, $aName] = $round;
                                $jIndex = array_search($jName, OPTIONS);
                                $aIndex = array_search($aName, OPTIONS);
                                $res = determinerGagnant($jIndex, $aIndex);
                                $rowClass = ($res === 0) ? 'win' : (($res === 2) ? 'tie' : 'loss');
                            ?>
                                <div class="history-item <?= $rowClass ?>">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="bi bi-person-fill opacity-50"></span>
                                        <span class="<?= $rowClass == 'win' ? 'text-white' : 'text-muted' ?>"><?= ucfirst($jName) ?></span>
                                    </div>
                                    <div class="text-center opacity-25 small"><span class="bi bi-x-lg"></span></div>
                                    <div class="d-flex align-items-center justify-content-end gap-2">
                                        <span class="<?= $rowClass == 'loss' ? 'text-white' : 'text-muted' ?>"><?= ucfirst($aName) ?></span>
                                        <span class="bi bi-cpu-fill opacity-50"></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <div class="modal fade" id="leaderboardModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content modal-custom">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-trophy-fill text-warning me-2"></i>Classement Top 10</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-striped mb-0" style="background: transparent;">
                            <thead>
                                <tr>
                                    <th class="text-center py-3 text small text-uppercase" style="background: rgba(0,0,0,0.2);">#</th>
                                    <th class="py-3 text small text-uppercase" style="background: rgba(0,0,0,0.2);">Joueur</th>
                                    <th class="text-center py-3 text small text-uppercase" style="background: rgba(0,0,0,0.2);">Winrate</th>
                                    <th class="text-end py-3 text small text-uppercase me-3" style="background: rgba(0,0,0,0.2);">Victoires</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($leaderboard)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text">Aucun joueur classé pour le moment.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php $rank = 1;
                                    foreach ($leaderboard as $joueur): ?>
                                        <tr style="border-color: var(--panel-border);">
                                            <td class="text-center align-middle">
                                                <?php
                                                if ($rank === 1) echo '🥇';
                                                elseif ($rank === 2) echo '🥈';
                                                elseif ($rank === 3) echo '🥉';
                                                else echo '<span class="opacity-50 text-white small">' . $rank . '</span>';
                                                ?>
                                            </td>
                                            <td class="align-middle">
                                                <span class="<?= $joueur['username'] === ($_SESSION['username'] ?? '') ? 'text-info fw-bold' : 'text-white' ?>">
                                                    <?= htmlspecialchars($joueur['username']) ?>
                                                </span>
                                                <div class="small text" style="font-size: 0.75em;">
                                                    <?= $joueur['victoires'] + $joueur['defaites'] ?> parties décisives
                                                </div>
                                                <div class="small text text-muted" style="font-size: 0.75em;">
                                                    <?= $joueur['parties'] - $joueur['defaites'] - $joueur['victoires'] ?> égalitées
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <?php
                                                $parties_decisives = $joueur['victoires'] + $joueur['defaites'];
                                                if ($parties_decisives > 0) {
                                                    $p_winrate = round(($joueur['victoires'] / $parties_decisives) * 100);
                                                } else {
                                                    $p_winrate = 0;
                                                }
                                                $p_color = ($p_winrate >= 50) ? 'text-success' : 'text-danger';
                                                ?>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <span class="<?= $p_color ?> fw-bold small"><?= $p_winrate ?>%</span>
                                                    <span class="text-muted" style="font-size: 0.65em; opacity: 0.7;">
                                                        <?= $joueur['victoires'] ?>W - <?= $joueur['defaites'] ?>L
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="text-end align-middle">
                                                <span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-25 rounded-pill">
                                                    <?= $joueur['victoires'] ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php $rank++;
                                    endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="authModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-custom">
                <div class="modal-header">
                    <h5 class="modal-title">Authentification</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="nav nav-tabs nav-fill mb-3" id="authTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-panel" type="button" role="tab">Connexion</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register-panel" type="button" role="tab">Inscription</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="authTabsContent">

                        <div class="tab-pane fade show active" id="login-panel" role="tabpanel">
                            <form method="POST">
                                <input type="hidden" name="auth_action" value="login">
                                <div class="mb-3">
                                    <label class="form-label modal-label">Nom d'utilisateur</label>
                                    <input type="text" name="username" class="form-control form-control-dark" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label modal-label">Mot de passe</label>
                                    <input type="password" name="password" class="form-control form-control-dark" required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Se connecter</button>
                                </div>
                            </form>
                        </div>

                        <div class="tab-pane fade" id="register-panel" role="tabpanel">
                            <form method="POST" onsubmit="return validationpw(this)">
                                <input type="hidden" name="auth_action" value="register">
                                <div class="mb-3">
                                    <label class="form-label modal-label">Nom d'utilisateur</label>
                                    <input type="text" name="username" class="form-control form-control-dark" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label modal-label">Mot de passe</label>
                                    <input type="password" name="password" id="reg_password" class="form-control form-control-dark" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label modal-label">Confirmer le mot de passe</label>
                                    <input type="password" name="password2" id="reg_password2" class="form-control form-control-dark" required>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success">S'inscrire</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function validationpw(form) {
            const password = form.password.value;
            const password2 = form.password2.value;
            if (password !== password2) {
                alert("Les mots de passe ne correspondent pas.");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>
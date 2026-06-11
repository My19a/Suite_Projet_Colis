<?php /** @var array $utilisateurs ; @var int $nbEnLigne ; @var int $seuil */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilisateurs connectés</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
    <style>
        .pastille { display:inline-block; width:9px; height:9px; border-radius:50%; margin-right:7px; vertical-align:middle; }
        .pastille-on  { background:#22c55e; box-shadow:0 0 0 3px rgba(34,197,94,.2); }
        .pastille-off { background:#cbd5e1; }
        .badge-en-ligne   { background:#dcfce7; color:#166534; }
        .badge-hors-ligne { background:#f1f5f9; color:#64748b; }
    </style>
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Administration</h2>
        <p><?= e($_SESSION['user']->getFullName()) ?></p>
    </div>

    <nav class="menu">
        <a href="/admin/dashboard">Tableau de bord</a>
        <a class="actif" href="/presence">Utilisateurs connectés</a>
        <a href="/admin/utilisateurs">Utilisateurs</a>
        <a href="/tickets">Assistance</a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Déconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Utilisateurs connectés</h1>
            <p class="page-subtitle"><?= $nbEnLigne ?> utilisateur(s) en ligne (activité &lt; <?= $seuil ?> min)</p>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Rôle</th>
                        <th>État</th>
                        <th>Dernière activité</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($utilisateurs)): ?>
                        <tr><td colspan="4" class="empty-state">Aucun utilisateur.</td></tr>
                    <?php else: ?>
                        <?php foreach ($utilisateurs as $u): ?>
                            <?php $enLigne = (int) $u['en_ligne'] === 1; ?>
                            <tr>
                                <td>
                                    <span class="pastille <?= $enLigne ? 'pastille-on' : 'pastille-off' ?>"></span>
                                    <?= e($u['fullName']) ?>
                                </td>
                                <td><?= ucfirst(str_replace('_', ' ', e($u['role']))) ?></td>
                                <td>
                                    <span class="badge <?= $enLigne ? 'badge-en-ligne' : 'badge-hors-ligne' ?>">
                                        <?= $enLigne ? 'En ligne' : 'Hors ligne' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (empty($u['derniere_activite'])): ?>
                                        Jamais connecté
                                    <?php elseif ($enLigne): ?>
                                        À l'instant
                                    <?php else: ?>
                                        <?= date('d/m/Y H:i', strtotime($u['derniere_activite'])) ?>
                                        (il y a <?= (int) $u['minutes_inactif'] ?> min)
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

</body>
</html>

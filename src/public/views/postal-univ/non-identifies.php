<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colis non identifiés – Postal Université</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Postal Université</h2>
        <p>Gestion des colis</p>
    </div>

    <nav class="menu">
        <a href="/postal-univ/dashboard">Tableau de bord</a>
        <a href="/postal-univ/reception">Réception colis</a>
        <a href="/postal-univ/colis">Liste colis</a>
        <a class="actif" href="/postal-univ/non-identifies">Non identifiés</a>
        <a href="/postal-univ/historique">Historique</a>
        <a href="/tickets">Assistance<?php if (function_exists('ticketNotifsCount') && ($__n=ticketNotifsCount())>0): ?> <span style="display:inline-block;min-width:18px;height:18px;line-height:18px;text-align:center;background:#ef4444;color:#fff;border-radius:999px;padding:0 5px;font-size:11px;font-weight:700;margin-left:6px;"><?= $__n ?></span><?php endif; ?></a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Déconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Colis non identifiés</h1>
            <p class="page-subtitle">Colis sans correspondance ou information incomplète</p>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N° suivi</th>
                        <th>Date réception</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colis)): ?>
                        <tr><td colspan="4" class="empty-state">Aucun colis non identifié</td></tr>
                    <?php else: ?>
                        <?php foreach ($colis as $c): ?>
                        <tr>
                            <td>#<?= $c["id_colis"] ?></td>
                            <td><strong><?= htmlspecialchars($c["numero_suivi"] ?: "—") ?></strong></td>
                            <td><?= $c["date_reception"] ?></td>
                            <td><span class="badge badge-non_identifie"><?= htmlspecialchars($c["statut"]) ?></span></td>
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

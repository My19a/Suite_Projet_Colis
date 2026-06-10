<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colis en attente – Service Postal IUT</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Postal IUT</h2>
        <p>Service Postal</p>
    </div>

    <nav class="menu">
        <a href="/postal/dashboard">Tableau de bord</a>
        <a href="/postal/colis/recus">Colis reçus</a>
        <a href="/postal/colis/remis">Colis remis</a>
        <a href="/postal/colis/recherche">Recherche colis</a>
        <a class="actif" href="/postal/colis/attente">Colis en attente</a>
        <a href="/postal/colis/non-identifies">Colis non identifiés</a>
        <a href="/postal/historique">Historique global</a>
        <a href="/tickets">Assistance<?php if (function_exists('ticketNotifsCount') && ($__n=ticketNotifsCount())>0): ?> <span style="display:inline-block;min-width:18px;height:18px;line-height:18px;text-align:center;background:#ef4444;color:#fff;border-radius:999px;padding:0 5px;font-size:11px;font-weight:700;margin-left:6px;"><?= $__n ?></span><?php endif; ?></a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Déconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Colis en attente</h1>
            <p class="page-subtitle">Tous les colis reçus mais non encore livrés</p>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Département</th>
                        <th>N° commande</th>
                        <th>N° suivi</th>
                        <th>Date réception</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colis)): ?>
                        <tr>
                            <td colspan="6" class="empty-state">Aucun colis en attente</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($colis as $c): ?>
                        <tr>
                            <td><a href="/postal/colis/details?id=<?= $c["id_colis"] ?>" class="btn-link">#<?= $c["id_colis"] ?></a></td>
                            <td><?= htmlspecialchars($c["departement"] ?: "Non identifie") ?></td>
                            <td><?= htmlspecialchars($c["numero_commande"]) ?></td>
                            <td><?= htmlspecialchars($c["numero_suivi"]) ?></td>
                            <td><?= $c["date_reception"] ?></td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="/postal/colis/details?id=<?= $c["id_colis"] ?>">Ouvrir</a>
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

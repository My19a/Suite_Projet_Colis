<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bons de commande – Directeur</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Directeur IUT</h2>
        <p>Validation et signature</p>
    </div>

    <nav class="menu">
        <a href="/directeur/dashboard">Tableau de bord</a>
        <a href="/directeur/devis">Devis à signer</a>
        <a class="actif" href="/directeur/bons-commande">Bons de commande</a>
        <a href="/tickets">Assistance<?php if (function_exists('ticketNotifsCount') && ($__n=ticketNotifsCount())>0): ?> <span style="display:inline-block;min-width:18px;height:18px;line-height:18px;text-align:center;background:#ef4444;color:#fff;border-radius:999px;padding:0 5px;font-size:11px;font-weight:700;margin-left:6px;"><?= $__n ?></span><?php endif; ?></a>
    </nav>

    <div class="utilisateur-connecte">
        <div class="utilisateur-nom"><?= isset($_SESSION["user"]) ? htmlspecialchars($_SESSION["user"]->getFullName()) : "" ?></div>
        <div class="utilisateur-role"><?= isset($_SESSION["user"]) ? htmlspecialchars($_SESSION["user"]->getRole()) : "" ?></div>
    </div>
    <div class="deconnexion">
        <a href="/logout">Déconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Bons de commande</h1>
            <p class="page-subtitle">Historique des bons de commande validés</p>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N° commande</th>
                        <th>Objet</th>
                        <th>Montant</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bons)): ?>
                        <tr><td colspan="5" class="empty-state">Aucun bon de commande</td></tr>
                    <?php else: ?>
                        <?php foreach ($bons as $b): ?>
                        <tr>
                            <td>#<?= $b["id_bon_commande"] ?></td>
                            <td><strong><?= htmlspecialchars($b["numero_commande"]) ?></strong></td>
                            <td><?= htmlspecialchars($b["objet"] ?: "—") ?></td>
                            <td><span class="montant"><?= $b["montant_estime"] ? number_format($b["montant_estime"], 2, ',', ' ') . " EUR" : "—" ?></span></td>
                            <td><?= $b["date_commande"] ?></td>
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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Devis à vérifier – Service Financier</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Service Financier</h2>
        <p>Gestion budgétaire</p>
    </div>

    <nav class="menu">
        <a href="/finance/dashboard">Tableau de bord</a>
        <a class="actif" href="/finance/devis">Devis à vérifier</a>
        <a href="/finance/bons-commande">Bons de commande</a>
        <a href="/finance/budgets">Budgets</a>
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
            <h1 class="page-title">Devis à vérifier</h1>
            <p class="page-subtitle">Devis soumis par les départements</p>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Objet</th>
                        <th>Département</th>
                        <th>Montant</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($devis)): ?>
                        <tr><td colspan="6" class="empty-state">Aucun devis à vérifier</td></tr>
                    <?php else: ?>
                        <?php foreach ($devis as $d): ?>
                        <tr>
                            <td>#<?= $d["id_devis"] ?></td>
                            <td><strong><?= htmlspecialchars($d["objet"]) ?></strong></td>
                            <td><?= htmlspecialchars($d["departement"] ?? "—") ?></td>
                            <td><span class="montant"><?= number_format($d["montant_estime"], 2, ',', ' ') ?> EUR</span></td>
                            <td><?= $d["date_demande"] ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a class="btn btn-sm btn-success" href="/finance/valider-devis?id=<?= $d["id_devis"] ?>">Valider</a>
                                    <a class="btn btn-sm btn-danger" href="/finance/rejeter-devis?id=<?= $d["id_devis"] ?>">Rejeter</a>
                                    <a class="btn btn-sm btn-secondary" href="/finance/voir-devis?id=<?= $d["id_devis"] ?>" target="_blank">Voir</a>
                                </div>
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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budgets – Service Financier</title>
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
        <a href="/finance/devis">Devis à vérifier</a>
        <a href="/finance/bons-commande">Bons de commande</a>
        <a class="actif" href="/finance/budgets">Budgets</a>
        <a href="/tickets">Assistance<?php if (function_exists('ticketNotifsCount') && ($__n=ticketNotifsCount())>0): ?> <span style="display:inline-block;min-width:18px;height:18px;line-height:18px;text-align:center;background:#ef4444;color:#fff;border-radius:999px;padding:0 5px;font-size:11px;font-weight:700;margin-left:6px;"><?= $__n ?></span><?php endif; ?></a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Déconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Budgets des départements</h1>
            <p class="page-subtitle">Suivi budgétaire global</p>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Département</th>
                        <th>Budget total</th>
                        <th>Budget utilisé</th>
                        <th>Budget restant</th>
                        <th>Etat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($budgets)): ?>
                        <tr><td colspan="5" class="empty-state">Aucun budget trouve</td></tr>
                    <?php else: ?>
                        <?php foreach ($budgets as $b): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($b["nom"]) ?></strong></td>
                            <td><?= number_format($b["budget_total"], 2, ',', ' ') ?> EUR</td>
                            <td><?= number_format($b["budget_utilise"], 2, ',', ' ') ?> EUR</td>
                            <td><span class="montant"><?= number_format($b["budget_restant"], 2, ',', ' ') ?> EUR</span></td>
                            <td>
                                <?php if ($b["budget_restant"] < 0): ?>
                                    <span class="badge badge-refuse">Dépassé</span>
                                <?php else: ?>
                                    <span class="badge badge-valide">OK</span>
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

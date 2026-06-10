<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – Service Financier</title>
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
        <a class="actif" href="/finance/dashboard">Tableau de bord</a>
        <a href="/finance/devis">Devis à vérifier</a>
        <a href="/finance/bons-commande">Bons de commande</a>
        <a href="/finance/budgets">Budgets</a>
        <a href="/tickets">Assistance<?php if (function_exists('ticketNotifsCount') && ($__n=ticketNotifsCount())>0): ?> <span style="display:inline-block;min-width:18px;height:18px;line-height:18px;text-align:center;background:#ef4444;color:#fff;border-radius:999px;padding:0 5px;font-size:11px;font-weight:700;margin-left:6px;"><?= $__n ?></span><?php endif; ?></a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Déconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-subtitle">Suivi budgétaire et validation des devis</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-warning">
            <span class="stat-label">Devis en attente</span>
            <div class="stat-value"><?= $stats["devis_attente"] ?></div>
            <div class="stat-description">À vérifier</div>
        </div>

        <div class="stat-card stat-blue">
            <span class="stat-label">Bons de commande</span>
            <div class="stat-value"><?= $stats["bons_commande"] ?></div>
            <div class="stat-description">Total</div>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Budgets des départements</h2>
            <a href="/finance/budgets" class="btn-link">Voir tout</a>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Département</th>
                        <th>Budget total</th>
                        <th>Budget utilisé</th>
                        <th>Restant</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($budgets)): ?>
                        <tr><td colspan="4" class="empty-state">Aucun budget trouve</td></tr>
                    <?php else: ?>
                        <?php foreach ($budgets as $b): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($b["nom"]) ?></strong></td>
                            <td><?= number_format($b["budget_total"], 2, ',', ' ') ?> EUR</td>
                            <td><?= number_format($b["budget_utilise"], 2, ',', ' ') ?> EUR</td>
                            <td><span class="montant"><?= number_format($b["budget_total"] - $b["budget_utilise"], 2, ',', ' ') ?> EUR</span></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Devis à vérifier</h2>
            <a href="/finance/devis" class="btn-link">Voir tout</a>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Objet</th>
                        <th>Département</th>
                        <th>Montant</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($devis)): ?>
                        <tr><td colspan="5" class="empty-state">Aucun devis en attente</td></tr>
                    <?php else: ?>
                        <?php foreach ($devis as $d): ?>
                        <tr>
                            <td>#<?= $d["id_devis"] ?></td>
                            <td><strong><?= htmlspecialchars($d["objet"]) ?></strong></td>
                            <td><?= htmlspecialchars($d["departement"]) ?></td>
                            <td><span class="montant"><?= number_format($d["montant_estime"], 2, ',', ' ') ?> EUR</span></td>
                            <td>
                                <div class="action-buttons">
                                    <a class="btn btn-sm btn-success" href="/finance/valider-devis?id=<?= $d["id_devis"] ?>">Valider</a>
                                    <a class="btn btn-sm btn-danger" href="/finance/rejeter-devis?id=<?= $d["id_devis"] ?>">Rejeter</a>
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

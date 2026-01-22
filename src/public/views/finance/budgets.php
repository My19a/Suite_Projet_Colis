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
        <p>Gestion budgetaire</p>
    </div>

    <nav class="menu">
        <a href="/finance/dashboard">Tableau de bord</a>
        <a href="/finance/devis">Devis a verifier</a>
        <a href="/finance/bons-commande">Bons de commande</a>
        <a class="actif" href="/finance/budgets">Budgets</a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Deconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Budgets des departements</h1>
            <p class="page-subtitle">Suivi budgetaire global</p>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Departement</th>
                        <th>Budget total</th>
                        <th>Budget utilise</th>
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
                                    <span class="badge badge-refuse">Depasse</span>
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

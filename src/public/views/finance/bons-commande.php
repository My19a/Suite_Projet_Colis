<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bons de commande – Service Financier</title>
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
        <a class="actif" href="/finance/bons-commande">Bons de commande</a>
        <a href="/finance/budgets">Budgets</a>
    </nav>

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
                        <th>N° commande</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bons)): ?>
                        <tr><td colspan="4" class="empty-state">Aucun bon de commande</td></tr>
                    <?php else: ?>
                        <?php foreach ($bons as $b): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($b["numero_commande"]) ?></strong></td>
                            <td><?= $b["date_commande"] ?></td>
                            <td><span class="montant"><?= number_format($b["montant_estime"], 2, ',', ' ') ?> EUR</span></td>
                            <td><span class="badge badge-<?= strtolower($b["statut"]) ?>"><?= ucfirst($b["statut"]) ?></span></td>
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

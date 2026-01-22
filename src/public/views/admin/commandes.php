<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bons de commande – Admin</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Administrateur</h2>
        <p>Gestion du systeme</p>
    </div>

    <nav class="menu">
        <a href="/admin/dashboard">Tableau de bord</a>
        <a href="/admin/utilisateurs">Utilisateurs</a>
        <a href="/admin/departements">Departements</a>
        <a href="/admin/fournisseurs">Fournisseurs</a>
        <a href="/admin/devis">Tous les devis</a>
        <a class="actif" href="/admin/commandes">Bons de commande</a>
        <a href="/admin/colis">Tous les colis</a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Deconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Bons de commande</h1>
            <p class="page-subtitle">Liste de tous les bons de commande</p>
        </div>
    </div>

    <div class="section">
        <div class="stats-grid">
            <?php foreach ($stats as $statut => $count): ?>
            <div class="stat-card">
                <div class="stat-value"><?= $count ?></div>
                <div class="stat-label"><?= ucfirst(str_replace('_', ' ', $statut)) ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="section">
        <form method="get" class="search-form">
            <input type="text" name="q" class="form-input" placeholder="Rechercher par numero..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            <button type="submit" class="btn btn-primary">Rechercher</button>
        </form>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>N° Commande</th>
                        <th>Departement</th>
                        <th>Fournisseur</th>
                        <th>Montant</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($commandes)): ?>
                        <tr><td colspan="6" class="empty-state">Aucune commande</td></tr>
                    <?php else: ?>
                        <?php foreach ($commandes as $c): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($c['numero_commande']) ?></strong></td>
                            <td><?= htmlspecialchars($c['departement'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($c['fournisseur'] ?? '-') ?></td>
                            <td><?= number_format($c['montant_estime'] ?? 0, 2, ',', ' ') ?> EUR</td>
                            <td><span class="badge badge-<?= $c['statut'] ?>"><?= $c['statut'] ?></span></td>
                            <td><?= $c['date_commande'] ?></td>
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

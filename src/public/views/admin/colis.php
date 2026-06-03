<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tous les colis – Admin</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Administrateur</h2>
        <p>Gestion du système</p>
    </div>

    <nav class="menu">
        <a href="/admin/dashboard">Tableau de bord</a>
        <a href="/admin/utilisateurs">Utilisateurs</a>
        <a href="/admin/departements">Départements</a>
        <a href="/admin/fournisseurs">Fournisseurs</a>
        <a href="/admin/devis">Tous les devis</a>
        <a class="actif" href="/admin/colis">Tous les colis</a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Déconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Tous les colis</h1>
            <p class="page-subtitle">Vision globale et traçabilité complète des colis</p>
        </div>
    </div>

    <?php if (!empty($stats)): ?>
    <div class="stats-grid">
        <?php foreach ($stats as $s): ?>
        <div class="stat-card">
            <div class="stat-value"><?= $s['total'] ?></div>
            <div class="stat-label"><?= $s['statut'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="section">
        <div class="search-card">
            <form method="get" class="search-form">
                <input type="text" name="q" class="form-input" placeholder="placeholder="placeholder=""Recherche : n° suivi, BC, departement, statut"" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                <button type="submit" class="btn btn-primary">Rechercher</button>
            </form>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N° suivi</th>
                        <th>Bon de commande</th>
                        <th>Département</th>
                        <th>Statut</th>
                        <th>Date réception</th>
                        <th>Date retrait</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colis)): ?>
                        <tr><td colspan="7" class="empty-state">Aucun colis trouve</td></tr>
                    <?php else: ?>
                        <?php foreach ($colis as $c): ?>
                        <tr>
                            <td>#<?= $c['id_colis'] ?></td>
                            <td><strong><?= htmlspecialchars($c['numero_suivi'] ?: '—') ?></strong></td>
                            <td><?= htmlspecialchars($c['numero_commande'] ?: '—') ?></td>
                            <td><?= htmlspecialchars($c['departement'] ?: '—') ?></td>
                            <td><span class="badge badge-<?= strtolower(str_replace(' ', '_', $c['statut'])) ?>"><?= $c['statut'] ?></span></td>
                            <td><?= $c['date_reception'] ?: '—' ?></td>
                            <td><?= $c['date_retrait'] ?: '—' ?></td>
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

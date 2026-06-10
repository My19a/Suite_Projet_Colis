<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – Service Postal IUT</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Postal IUT</h2>
        <p>Gestion des colis</p>
    </div>

    <nav class="menu">
        <a class="actif" href="/postal/dashboard">Tableau de bord</a>
        <a href="/postal/confirmation">Confirmation reception</a>
        <a href="/postal/colis/recus">Colis recus</a>
        <a href="/postal/colis/remis">Colis remis</a>
        <a href="/postal/colis/recherche">Recherche colis</a>
        <a href="/postal/colis/non-identifies">Non identifies</a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Deconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-subtitle">Vue d'ensemble des colis du service postal IUT</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-blue">
            <span class="stat-label">Recus a l'IUT</span>
            <div class="stat-value"><?= $stats["recus"] ?></div>
            <div class="stat-description">Colis recus</div>
        </div>

        <div class="stat-card stat-warning">
            <span class="stat-label">En attente</span>
            <div class="stat-value"><?= $stats["en_attente"] ?></div>
            <div class="stat-description">A retirer</div>
        </div>

        <div class="stat-card stat-success">
            <span class="stat-label">Retires</span>
            <div class="stat-value"><?= $stats["retires"] ?></div>
            <div class="stat-description">Colis livres</div>
        </div>

        <div class="stat-card stat-danger">
            <span class="stat-label">Non identifies</span>
            <div class="stat-value"><?= $stats["non_identifies"] ?></div>
            <div class="stat-description">A traiter</div>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Derniers colis recus</h2>
            <a href="/postal/colis/recus" class="btn-link">Voir tout</a>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N° suivi</th>
                        <th>Departement</th>
                        <th>Date reception</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colis)): ?>
                        <tr>
                            <td colspan="5" class="empty-state">Aucun colis trouve</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($colis as $c): ?>
                        <tr>
                            <td><a href="/postal/colis/details?id=<?= $c["id_colis"] ?>" class="btn-link">#<?= $c["id_colis"] ?></a></td>
                            <td><?= htmlspecialchars($c["numero_suivi"]) ?></td>
                            <td><?= htmlspecialchars($c["departement"] ?: "—") ?></td>
                            <td><?= $c["date_reception"] ?></td>
                            <td><span class="badge badge-<?= strtolower(str_replace(' ', '_', $c["statut"])) ?>"><?= ucfirst(str_replace('_', ' ', $c["statut"])) ?></span></td>                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Postal Universite</title>
    <link rel="stylesheet" href="/assets/css/theme.css">
</head>

<body class="tableau-bord">

<aside class="barre-laterale">
    <div class="entete-barre">
        <img src="/assets/img/logo-iutv.png" class="logo" alt="Logo IUT">
        <h2>Postal Universite</h2>
        <p>Gestion des colis</p>
    </div>

    <nav class="menu">
        <a class="actif" href="/postal-univ/dashboard">Tableau de bord</a>
        <a href="/postal-univ/reception">Reception colis</a>
        <a href="/postal-univ/colis">Liste colis</a>
        <a href="/postal-univ/non-identifies">Non identifies</a>
        <a href="/postal-univ/historique">Historique</a>
    </nav>

    <div class="deconnexion">
        <a href="/logout">Deconnexion</a>
    </div>
</aside>

<main class="contenu">

    <div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-subtitle">Gestion des colis du service postal universitaire</p>
        </div>
        <a href="/postal-univ/reception" class="btn btn-primary">Recevoir un colis</a>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-blue">
            <span class="stat-label">Colis recus</span>
            <div class="stat-value"><?= $stats["recus"] ?></div>
            <div class="stat-description">Total</div>
        </div>

        <div class="stat-card stat-warning">
            <span class="stat-label">A transferer</span>
            <div class="stat-value"><?= $stats["a_transferer"] ?></div>
            <div class="stat-description">Vers l'IUT</div>
        </div>

        <div class="stat-card stat-success">
            <span class="stat-label">Transferes</span>
            <div class="stat-value"><?= $stats["transferes"] ?></div>
            <div class="stat-description">Vers l'IUT</div>
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
            <a href="/postal-univ/colis" class="btn-link">Voir tout</a>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N° suivi</th>
                        <th>Date reception</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colis_recents)): ?>
                        <tr><td colspan="4" class="empty-state">Aucun colis recu</td></tr>
                    <?php else: ?>
                        <?php foreach ($colis_recents as $c): ?>
                        <tr>
                            <td>#<?= $c["id_colis"] ?></td>
                            <td><strong><?= htmlspecialchars($c["numero_suivi"]) ?></strong></td>
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

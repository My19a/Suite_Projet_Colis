<?php
$titre = 'Service Postal Université';
$actif = '/postal-univ/dashboard';
$avecTutoriel = true;
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-subtitle">Gestion des colis du service postal universitaire</p>
        </div>
        <a href="/postal-univ/reception" class="btn btn-primary">Recevoir un colis</a>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-blue">
            <span class="stat-label">Colis reçus</span>
            <div class="stat-value"><?= $stats["recus"] ?></div>
            <div class="stat-description">Total</div>
        </div>

        <div class="stat-card stat-warning">
            <span class="stat-label">A transferer</span>
            <div class="stat-value"><?= $stats["a_transferer"] ?></div>
            <div class="stat-description">Vers l'IUT</div>
        </div>

        <div class="stat-card stat-success">
            <span class="stat-label">Transférés</span>
            <div class="stat-value"><?= $stats["transferes"] ?></div>
            <div class="stat-description">Vers l'IUT</div>
        </div>

        <div class="stat-card stat-danger">
            <span class="stat-label">Non identifiés</span>
            <div class="stat-value"><?= $stats["non_identifies"] ?></div>
            <div class="stat-description">À traiter</div>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Derniers colis reçus</h2>
            <a href="/postal-univ/colis" class="btn-link">Voir tout</a>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N° suivi</th>
                        <th>Date réception</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colis_recents)): ?>
                        <tr><td colspan="4" class="empty-state">Aucun colis reçu</td></tr>
                    <?php else: ?>
                        <?php foreach ($colis_recents as $c): ?>
                        <tr>
                            <td>#<?= $c["id_colis"] ?></td>
                            <td><strong><?= htmlspecialchars($c["numero_suivi"]) ?></strong></td>
                            <td><?= $c["date_reception"] ?></td>
                            <td><span class="badge badge-<?= strtolower(str_replace(' ', '_', $c["statut"])) ?>"><?= $c["statut"] ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>

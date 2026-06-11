<?php
$titre = 'Dashboard – Service Postal IUT';
$actif = '/postal/dashboard';
$avecTutoriel = true;
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-subtitle">Vue d'ensemble des colis du service postal IUT</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-blue">
            <span class="stat-label">Reçus a l'IUT</span>
            <div class="stat-value"><?= $stats["recus"] ?></div>
            <div class="stat-description">Colis reçus</div>
        </div>

        <div class="stat-card stat-warning">
            <span class="stat-label">En attente</span>
            <div class="stat-value"><?= $stats["en_attente"] ?></div>
            <div class="stat-description">À retirer</div>
        </div>

        <div class="stat-card stat-success">
            <span class="stat-label">Retirés</span>
            <div class="stat-value"><?= $stats["retires"] ?></div>
            <div class="stat-description">Colis livrés</div>
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
            <a href="/postal/colis/recus" class="btn-link">Voir tout</a>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N° suivi</th>
                        <th>Département</th>
                        <th>Date réception</th>
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
                            <td><span class="badge badge-<?= strtolower(str_replace(' ', '_', $c["statut"])) ?>"><?= $c["statut"] ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>

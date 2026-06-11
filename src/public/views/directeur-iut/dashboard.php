<?php
$titre = 'Tableau de bord – Directeur';
$actif = '/directeur/dashboard';
$avecTutoriel = true;
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Tableau de bord</h1>
            <p class="page-subtitle">Validation et suivi des décisions financières</p>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-warning">
            <span class="stat-label">Devis à signer</span>
            <div class="stat-value"><?= $stats["devis_attente"] ?></div>
            <div class="stat-description">En attente</div>
        </div>

        <div class="stat-card stat-success">
            <span class="stat-label">BC signés</span>
            <div class="stat-value"><?= $stats["bc_signes"] ?></div>
            <div class="stat-description">Bons de commande</div>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Devis validés par le service financier</h2>
            <a href="/directeur/devis" class="btn-link">Voir tout</a>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Objet</th>
                        <th>Montant</th>
                        <th>Date demande</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($devis)): ?>
                        <tr><td colspan="5" class="empty-state">Aucun devis à signer</td></tr>
                    <?php else: ?>
                        <?php foreach ($devis as $d): ?>
                        <tr>
                            <td>#<?= $d["id_devis"] ?></td>
                            <td><strong><?= htmlspecialchars($d["objet"]) ?></strong></td>
                            <td><span class="montant"><?= number_format($d["montant_estime"], 2, ',', ' ') ?> EUR</span></td>
                            <td><?= $d["date_demande"] ?></td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="/directeur/signer-devis?id=<?= $d["id_devis"] ?>">Signer</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-header">
            <h2 class="section-title">Historique des bons de commande</h2>
            <a href="/directeur/bons-commande" class="btn-link">Voir tout</a>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N° commande</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bons)): ?>
                        <tr><td colspan="3" class="empty-state">Aucun bon de commande</td></tr>
                    <?php else: ?>
                        <?php foreach ($bons as $b): ?>
                        <tr>
                            <td>#<?= $b["id_bon_commande"] ?></td>
                            <td><strong><?= htmlspecialchars($b["numero_commande"]) ?></strong></td>
                            <td><?= $b["date_commande"] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>

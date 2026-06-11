<?php
$titre = 'Dashboard – Service Financier';
$actif = '/finance/dashboard';
$avecTutoriel = true;
require __DIR__ . '/../partials/header.php';
?>

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

<?php require __DIR__ . '/../partials/footer.php'; ?>

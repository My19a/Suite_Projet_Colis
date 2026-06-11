<?php
$titre = 'Budgets – Service Financier';
$actif = '/finance/budgets';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Budgets des départements</h1>
            <p class="page-subtitle">Suivi budgétaire global</p>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Département</th>
                        <th>Budget total</th>
                        <th>Budget utilisé</th>
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
                                    <span class="badge badge-refuse">Dépassé</span>
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

<?php require __DIR__ . '/../partials/footer.php'; ?>

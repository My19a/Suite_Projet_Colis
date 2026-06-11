<?php
$titre = 'Bons de commande – Service Financier';
$actif = '/finance/bons-commande';
require __DIR__ . '/../partials/header.php';
?>

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

<?php require __DIR__ . '/../partials/footer.php'; ?>

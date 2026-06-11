<?php
$titre = 'Bons de commande – Directeur';
$actif = '/directeur/bons-commande';
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
                        <th>ID</th>
                        <th>N° commande</th>
                        <th>Objet</th>
                        <th>Montant</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bons)): ?>
                        <tr><td colspan="5" class="empty-state">Aucun bon de commande</td></tr>
                    <?php else: ?>
                        <?php foreach ($bons as $b): ?>
                        <tr>
                            <td>#<?= $b["id_bon_commande"] ?></td>
                            <td><strong><?= htmlspecialchars($b["numero_commande"]) ?></strong></td>
                            <td><?= htmlspecialchars($b["objet"] ?: "—") ?></td>
                            <td><span class="montant"><?= $b["montant_estime"] ? number_format($b["montant_estime"], 2, ',', ' ') . " EUR" : "—" ?></span></td>
                            <td><?= $b["date_commande"] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>

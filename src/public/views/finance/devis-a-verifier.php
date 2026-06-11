<?php
$titre = 'Devis à vérifier – Service Financier';
$actif = '/finance/devis';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Devis à vérifier</h1>
            <p class="page-subtitle">Devis soumis par les départements</p>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Objet</th>
                        <th>Département</th>
                        <th>Montant</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($devis)): ?>
                        <tr><td colspan="6" class="empty-state">Aucun devis à vérifier</td></tr>
                    <?php else: ?>
                        <?php foreach ($devis as $d): ?>
                        <tr>
                            <td>#<?= $d["id_devis"] ?></td>
                            <td><strong><?= htmlspecialchars($d["objet"]) ?></strong></td>
                            <td><?= htmlspecialchars($d["departement"] ?? "—") ?></td>
                            <td><span class="montant"><?= number_format($d["montant_estime"], 2, ',', ' ') ?> EUR</span></td>
                            <td><?= $d["date_demande"] ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a class="btn btn-sm btn-success" href="/finance/valider-devis?id=<?= $d["id_devis"] ?>">Valider</a>
                                    <a class="btn btn-sm btn-danger" href="/finance/rejeter-devis?id=<?= $d["id_devis"] ?>">Rejeter</a>
                                    <a class="btn btn-sm btn-secondary" href="/finance/voir-devis?id=<?= $d["id_devis"] ?>" target="_blank">Voir</a>
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

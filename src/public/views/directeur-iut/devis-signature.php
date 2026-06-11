<?php
$titre = 'Signature devis – Directeur';
$actif = '/directeur/devis';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Signature du devis</h1>
            <p class="page-subtitle">Verifier et signer le devis</p>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Objet</th>
                        <th>Montant</th>
                        <th>Date</th>
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

<?php require __DIR__ . '/../partials/footer.php'; ?>

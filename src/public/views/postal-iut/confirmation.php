<?php
$titre = 'Confirmation des colis – Postal IUT';
$actif = '/postal/confirmation';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Confirmation des colis</h1>
            <p class="page-subtitle">Colis transférés par le service postal universitaire et en attente de confirmation a l'IUT</p>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N° suivi</th>
                        <th>Bon de commande</th>
                        <th>Département</th>
                        <th>Date transfert</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colis)): ?>
                        <tr>
                            <td colspan="6" class="empty-state">Aucun colis a confirmer</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($colis as $c): ?>
                        <tr>
                            <td>#<?= $c["id_colis"] ?></td>
                            <td><?= htmlspecialchars($c["numero_suivi"]) ?></td>
                            <td><?= htmlspecialchars($c["numero_commande"]) ?></td>
                            <td><?= htmlspecialchars($c["departement"] ?: "—") ?></td>
                            <td><?= $c["date_reception"] ?></td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="/postal/confirmer?id=<?= $c["id_colis"] ?>">Confirmer réception</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>

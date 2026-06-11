<?php
$titre = 'Colis en attente – Service Postal IUT';
$actif = '/postal/colis/attente';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Colis en attente</h1>
            <p class="page-subtitle">Tous les colis reçus mais non encore livrés</p>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Département</th>
                        <th>N° commande</th>
                        <th>N° suivi</th>
                        <th>Date réception</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colis)): ?>
                        <tr>
                            <td colspan="6" class="empty-state">Aucun colis en attente</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($colis as $c): ?>
                        <tr>
                            <td><a href="/postal/colis/details?id=<?= $c["id_colis"] ?>" class="btn-link">#<?= $c["id_colis"] ?></a></td>
                            <td><?= htmlspecialchars($c["departement"] ?: "Non identifie") ?></td>
                            <td><?= htmlspecialchars($c["numero_commande"]) ?></td>
                            <td><?= htmlspecialchars($c["numero_suivi"]) ?></td>
                            <td><?= $c["date_reception"] ?></td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="/postal/colis/details?id=<?= $c["id_colis"] ?>">Ouvrir</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>

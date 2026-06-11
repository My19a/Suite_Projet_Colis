<?php
$titre = 'Historique global – Service Postal IUT';
$actif = '/postal/historique';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Historique global</h1>
            <p class="page-subtitle">Dernieres actions effectuées sur tous les colis</p>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Colis</th>
                        <th>N° commande</th>
                        <th>N° suivi</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($historique)): ?>
                        <tr>
                            <td colspan="5" class="empty-state">Aucun historique disponible</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($historique as $h): ?>
                        <tr>
                            <td><?= $h["date_action"] ?></td>
                            <td>
                                <a href="/postal/colis/details?id=<?= $h["colis_id"] ?>" class="btn-link">#<?= $h["colis_id"] ?></a>
                            </td>
                            <td><?= htmlspecialchars($h["numero_commande"] ?: "—") ?></td>
                            <td><?= htmlspecialchars($h["numero_suivi"] ?: "—") ?></td>
                            <td><span class="badge"><?= htmlspecialchars($h["action"]) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>

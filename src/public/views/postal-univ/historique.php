<?php
$titre = 'Historique – Postal Université';
$actif = '/postal-univ/historique';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Historique des actions</h1>
            <p class="page-subtitle">Traçabilité complète des colis</p>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>ID Colis</th>
                        <th>N° Suivi</th>
                        <th>Action</th>
                        <th>Utilisateur</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($historique)): ?>
                        <tr><td colspan="5" class="empty-state">Aucun historique</td></tr>
                    <?php else: ?>
                        <?php foreach ($historique as $h): ?>
                        <tr>
                            <td><?= $h["date_action"] ?></td>
                            <td>#<?= $h["id_colis"] ?></td>
                            <td><strong><?= htmlspecialchars($h["numero_suivi"] ?: "—") ?></strong></td>
                            <td><?= htmlspecialchars($h["action"]) ?></td>
                            <td><?= htmlspecialchars($h["utilisateur"] ?? "—") ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>

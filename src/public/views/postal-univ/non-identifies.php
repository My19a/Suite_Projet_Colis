<?php
$titre = 'Colis non identifiés – Postal Université';
$actif = '/postal-univ/non-identifies';
require __DIR__ . '/../partials/header.php';
?>

<div class="page-header">
        <div class="page-header-info">
            <h1 class="page-title">Colis non identifiés</h1>
            <p class="page-subtitle">Colis sans correspondance ou information incomplète</p>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>N° suivi</th>
                        <th>Date réception</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($colis)): ?>
                        <tr><td colspan="4" class="empty-state">Aucun colis non identifié</td></tr>
                    <?php else: ?>
                        <?php foreach ($colis as $c): ?>
                        <tr>
                            <td>#<?= $c["id_colis"] ?></td>
                            <td><strong><?= htmlspecialchars($c["numero_suivi"] ?: "—") ?></strong></td>
                            <td><?= $c["date_reception"] ?></td>
                            <td><span class="badge badge-non_identifie"><?= htmlspecialchars($c["statut"]) ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php require __DIR__ . '/../partials/footer.php'; ?>
